<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // Validate input
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'role'     => 'required|in:admin,employee',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Block login if selected role doesn't match the user's actual role
            if ($user->role !== $request->role) {
                Auth::logout();
                Session::flush();

                return back()
                    ->withInput($request->only('email', 'role'))
                    ->withErrors([
                        'email' => 'Access denied. Your account is not registered as "' . ucfirst($request->role) . '".',
                    ]);
            }

            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return back()
            ->withInput($request->only('email', 'role'))
            ->withErrors([
                'email' => 'Invalid email or password.',
            ]);
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect()->route('login');
    }
}