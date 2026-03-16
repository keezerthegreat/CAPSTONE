<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
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
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            // Block archived accounts
            if (Auth::user()->is_archived) {
                Auth::logout();
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors([
                        'email' => 'This account has been archived. Please contact your administrator.',
                    ]);
            }
            $request->session()->regenerate();
            ActivityLog::log('logged_in', 'Auth', 'User logged in');
            return redirect()->route('dashboard');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'Invalid email or password.',
            ]);
    }

    public function logout()
    {
        ActivityLog::log('logged_out', 'Auth', 'User logged out');
        Auth::logout();
        Session::flush();
        return redirect()->route('login');
    }
}