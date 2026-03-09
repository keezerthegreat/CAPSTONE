<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'employee')->orderBy('created_at', 'desc')->get();
        $theme = session('theme', 'light');
        return view('settings.index', compact('employees', 'theme'));
    }

    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'employee',
        ]);

        return redirect()->route('settings.index')
            ->with('success', 'Employee account for "' . $request->name . '" has been created successfully.');
    }

    public function destroyEmployee(User $user)
    {
        // Prevent deleting your own account
        if ($user->id === auth()->id()) {
            return redirect()->route('settings.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('settings.index')
            ->with('success', '"' . $name . '" account has been deleted.');
    }

    public function setTheme(Request $request)
    {
        $theme = $request->input('theme', 'light');
        session(['theme' => $theme]);
        return response()->json(['theme' => $theme]);
    }
}