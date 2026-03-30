<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $isAdmin = Auth::user()->isAdmin();
        $query = ActivityLog::latest();

        // Employees can only see their own logs
        if (! $isAdmin) {
            $query->where('user_id', Auth::id());
        } elseif ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('user_name', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        $logs = $query->paginate(25)->withQueryString();
        $users = $isAdmin ? User::orderBy('name')->get() : collect();

        return view('audit-log.index', compact('logs', 'users', 'isAdmin'));
    }

    public function clear()
    {
        ActivityLog::truncate();

        return redirect()->route('audit.index')->with('success', 'Audit log cleared.');
    }
}
