<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\PasswordResetRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordRequestController extends Controller
{
    public function index()
    {
        $pending = PasswordResetRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $resolved = PasswordResetRequest::with(['user', 'resolver'])
            ->where('status', 'resolved')
            ->latest('resolved_at')
            ->take(20)
            ->get();

        return view('admin.password-requests', compact('pending', 'resolved'));
    }

    public function resolve(Request $request, PasswordResetRequest $passwordRequest)
    {
        $request->validate([
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $passwordRequest->user->update([
            'password' => Hash::make($request->new_password),
        ]);

        $passwordRequest->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolved_by' => Auth::id(),
        ]);

        ActivityLog::log('updated', 'User', "Reset password for: {$passwordRequest->user->name}");

        return back()->with('success', "Password reset for {$passwordRequest->user->name}.");
    }

    public function dismiss(PasswordResetRequest $passwordRequest)
    {
        $passwordRequest->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Request dismissed.');
    }
}
