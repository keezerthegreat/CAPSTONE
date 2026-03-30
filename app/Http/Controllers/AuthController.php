<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
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
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $throttleKey = 'login:'.strtolower($request->input('email')).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => "Too many failed login attempts. Please try again in {$minutes} minute(s).",
                ]);
        }

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

            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            ActivityLog::log('logged_in', 'Auth', 'User logged in');

            return redirect()->route('dashboard');
        }

        RateLimiter::hit($throttleKey, 900); // Lock for 15 minutes after 3 failures
        $remaining = 3 - RateLimiter::attempts($throttleKey);

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => $remaining > 0
                    ? "Invalid email or password. {$remaining} attempt(s) remaining before lockout."
                    : 'Invalid email or password.',
            ]);
    }

    public function submitPasswordRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'note' => 'nullable|string|max:500',
        ], [], ['email' => 'fp_email']);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['fp_email' => 'No active account found with that email.']);
        }

        // Prevent duplicate pending requests
        $existing = PasswordResetRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($existing) {
            return back()->with('fp_success', 'You already have a pending request. Please wait for the admin to respond.');
        }

        PasswordResetRequest::create([
            'user_id' => $user->id,
            'note' => $request->note,
            'status' => 'pending',
        ]);

        return back()->with('fp_success', 'Request sent! The admin will reset your password shortly.');
    }

    public function logout()
    {
        ActivityLog::log('logged_out', 'Auth', 'User logged out');
        Auth::logout();
        Session::flush();

        return redirect()->route('login');
    }
}
