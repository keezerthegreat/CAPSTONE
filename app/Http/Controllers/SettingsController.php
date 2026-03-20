<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'employee')->orderBy('created_at', 'desc')->get();

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

        return view('settings.index', compact('employees', 'backups'));
    }

    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'employee',
        ]);

        return redirect()->route('settings.index')
            ->with('success', 'Employee account for "'.$request->name.'" has been created successfully.');
    }

    public function destroyEmployee($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting your own account
        if ($user->id == Auth::id()) {
            return redirect()->route('settings.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('settings.index')
            ->with('success', '"'.$name.'" account has been deleted.');
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
            'backup_file' => 'required|file|mimes:sqlite,application/octet-stream|max:102400',
        ]);

        $uploadedFile = $request->file('backup_file');

        // Only accept files with the expected naming pattern or a plain .sqlite extension
        $originalName = $uploadedFile->getClientOriginalName();
        if (! preg_match('/^database_[\d_-]+\.sqlite$/', $originalName) && ! str_ends_with($originalName, '.sqlite')) {
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

        // Replace the live database with the uploaded file
        $uploadedFile->move(dirname($dbPath), basename($dbPath));

        return redirect()->route('settings.index')
            ->with('success', 'Database restored successfully. A pre-restore backup was saved as "'.$autoBackupName.'".');
    }

    public function setTheme(Request $request)
    {
        $theme = $request->input('theme', 'light');
        session(['theme' => $theme]);

        return response()->json(['theme' => $theme]);
    }
}
