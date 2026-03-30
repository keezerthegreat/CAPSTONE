<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'employee')->orderBy('created_at', 'desc')->get();
        $admins = User::where('role', 'admin')->where('is_super_admin', false)->orderBy('created_at', 'desc')->get();
        $superAdmin = User::where('is_super_admin', true)->first();

        $backupDir = storage_path('backups');
        $backupFiles = file_exists($backupDir) ? glob($backupDir.DIRECTORY_SEPARATOR.'database_*.sqlite') : [];
        if ($backupFiles !== false) {
            rsort($backupFiles);
        }
        $backups = collect($backupFiles)->map(fn ($path) => [
            'filename' => basename($path),
            'size' => round(filesize($path) / 1024, 1),
            'created' => date('M d, Y g:i A', filemtime($path)),
        ]);

        return view('settings.index', compact('employees', 'admins', 'superAdmin', 'backups'));
    }

    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,employee',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $label = $request->role === 'admin' ? 'Admin' : 'Employee';

        return redirect()->route('settings.index')
            ->with('success', "{$label} account for \"{$request->name}\" has been created successfully.");
    }

    public function archiveEmployee($id)
    {
        $user = User::findOrFail($id);

        if ($user->isSuperAdmin()) {
            return redirect()->route('settings.index')->with('error', 'The Super Admin account cannot be archived.');
        }

        if ($user->id == Auth::id()) {
            return redirect()->route('settings.index')->with('error', 'You cannot archive your own account.');
        }

        $user->update(['is_archived' => true]);

        return redirect()->route('settings.index')->with('success', "\"{$user->name}\" account has been archived.");
    }

    public function unarchiveEmployee($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_archived' => false]);

        return redirect()->route('settings.index')->with('success', "\"{$user->name}\" account has been restored.");
    }

    public function destroyEmployee($id)
    {
        $user = User::findOrFail($id);

        if ($user->isSuperAdmin()) {
            return redirect()->route('settings.index')->with('error', 'The Super Admin account cannot be deleted.');
        }

        if ($user->id == Auth::id()) {
            return redirect()->route('settings.index')->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('settings.index')->with('success', "\"{$name}\" account has been deleted.");
    }

    public function backupNow()
    {
        Artisan::call('backup:database');

        return redirect()->route('settings.index')
            ->with('success', 'Database backup created successfully.');
    }

    public function downloadBackup(string $filename)
    {
        // Prevent path traversal — only allow the exact filename format
        if (! preg_match('/^database_[\d_-]+\.sqlite$/', $filename)) {
            abort(404);
        }

        $path = storage_path('backups'.DIRECTORY_SEPARATOR.$filename);

        if (! file_exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }

    public function restoreBackup(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|max:102400',
        ]);

        $uploadedFile = $request->file('backup_file');

        // Only accept .sqlite files by extension
        $originalName = $uploadedFile->getClientOriginalName();
        if (! str_ends_with(strtolower($originalName), '.sqlite')) {
            return redirect()->route('settings.index')
                ->with('error', 'Invalid backup file. Please upload a valid .sqlite backup file.');
        }

        $dbPath = database_path('database.sqlite');

        // Auto-backup the current database before overwriting
        $backupDir = storage_path('backups');
        if (! file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        $autoBackupName = 'database_'.now()->format('Y_m_d_His').'_pre_restore.sqlite';
        copy($dbPath, $backupDir.DIRECTORY_SEPARATOR.$autoBackupName);

        // Read the uploaded file contents before disconnecting
        $newDbContents = file_get_contents($uploadedFile->getRealPath());

        if ($newDbContents === false || strlen($newDbContents) < 1024) {
            return redirect()->route('settings.index')
                ->with('error', 'Failed to read the uploaded backup file. Please try again.');
        }

        // Close the active SQLite connection before replacing the file
        DB::disconnect('sqlite');

        // Write the new database contents directly — more reliable than rename() on Windows
        // when SQLite may still hold a brief file lock after disconnect
        $written = file_put_contents($dbPath, $newDbContents);

        if ($written === false) {
            DB::reconnect('sqlite');

            return redirect()->route('settings.index')
                ->with('error', 'Failed to overwrite the database file. Check file permissions and try again.');
        }

        // Reconnect so the rest of the request (session, redirect) uses the restored DB
        DB::reconnect('sqlite');

        // Run any migrations the restored backup may be missing
        Artisan::call('migrate', ['--force' => true]);

        // Ensure a super admin exists — the restored backup may predate that feature
        $hasSuperAdmin = DB::table('users')->where('is_super_admin', true)->exists();
        if (! $hasSuperAdmin) {
            DB::table('users')
                ->where('role', 'admin')
                ->orderBy('id')
                ->limit(1)
                ->update(['is_super_admin' => true]);
        }

        return redirect()->route('settings.index')
            ->with('success', 'Database restored successfully. Schema updated to latest version. A pre-restore backup was saved as "'.$autoBackupName.'".');
    }

    public function setTheme(Request $request)
    {
        $theme = $request->input('theme', 'light');
        session(['theme' => $theme]);

        return response()->json(['theme' => $theme]);
    }
}
