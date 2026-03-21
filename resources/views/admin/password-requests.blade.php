@extends('layouts.app')

@section('page-title', 'Password Reset Requests')

@section('content')
<style>
.bidb-wrap { background:var(--bg); min-height:100vh; padding:28px; }
.page-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.page-hdr h1 { font-size:22px; font-weight:700; color:var(--primary); margin:0; }
.breadcrumb { font-size:13px; color:var(--muted); margin-top:2px; }
.breadcrumb a { color:var(--primary); text-decoration:none; }
.card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); margin-bottom:24px; overflow:hidden; }
.card-header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.card-title { font-weight:700; color:var(--primary); font-size:14px; display:flex; align-items:center; gap:8px; }
.card-body { padding:20px; }
.empty-state { text-align:center; padding:36px; color:var(--muted); font-size:13px; }
.req-list { display:flex; flex-direction:column; gap:12px; padding:16px 20px; }
.req-card { border:1px solid var(--border); border-radius:10px; padding:16px; background:var(--bg); }
.req-card.urgent { border-left:4px solid #f59e0b; }
.req-meta { display:flex; align-items:center; gap:10px; margin-bottom:8px; flex-wrap:wrap; }
.req-name { font-weight:700; color:var(--primary); font-size:14px; }
.req-email { font-size:12px; color:var(--muted); }
.req-time { font-size:11px; color:var(--muted); margin-left:auto; }
.req-note { font-size:13px; color:var(--text); background:#f8fafc; border-radius:7px; padding:8px 12px; margin-bottom:12px; border:1px solid var(--border); font-style:italic; }
.req-actions { display:flex; gap:8px; flex-wrap:wrap; }
.btn { display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border-radius:8px; border:none; cursor:pointer; font-family:inherit; font-size:12px; font-weight:600; transition:all .15s; text-decoration:none; }
.btn-primary { background:var(--primary); color:#fff; }
.btn-primary:hover { background:var(--primary-light); }
.btn-ghost { background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0; }
.btn-ghost:hover { background:#e2e8f0; }
.badge { display:inline-flex; align-items:center; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:600; }
.badge-pending { background:#fef3c7; color:#92400e; }
.badge-resolved { background:#dcfce7; color:#166534; }
.alert-success { background:#dcfce7; border:1px solid #bbf7d0; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px; display:flex; align-items:center; gap:8px; }
/* Reset modal */
.modal-backdrop { display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:200; align-items:center; justify-content:center; }
.modal-backdrop.open { display:flex; }
.modal { background:var(--card); border-radius:14px; width:380px; max-width:95vw; box-shadow:0 20px 60px rgba(0,0,0,.2); overflow:hidden; }
.modal-header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.modal-header h3 { font-size:14px; font-weight:700; color:var(--primary); margin:0; }
.modal-close { background:none; border:none; font-size:20px; color:var(--muted); cursor:pointer; }
.modal-body { padding:20px; }
.form-group { margin-bottom:14px; }
.form-label { display:block; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin-bottom:5px; }
.form-input { width:100%; padding:8px 12px; border:1.5px solid var(--border); border-radius:8px; font-size:13px; font-family:inherit; color:var(--text); background:var(--bg); outline:none; box-sizing:border-box; }
.form-input:focus { border-color:var(--primary); }
.modal-footer { padding:14px 20px; border-top:1px solid var(--border); display:flex; justify-content:flex-end; gap:8px; }
/* Resolved section */
.res-table { width:100%; border-collapse:collapse; font-size:12px; }
.res-table th { padding:9px 14px; text-align:left; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); border-bottom:1.5px solid var(--border); background:#f8fafc; }
.res-table td { padding:10px 14px; border-bottom:1px solid var(--border); color:var(--text); }
.res-table tbody tr:last-child td { border-bottom:none; }
</style>

<div class="bidb-wrap">
    <div class="page-hdr">
        <div>
            <h1><i class="fas fa-key" style="margin-right:8px"></i>Password Reset Requests</h1>
            <div class="breadcrumb">Home › <a href="{{ route('settings.index') }}">Settings</a> › <span>Password Requests</span></div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    {{-- Pending --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-clock"></i> Pending Requests
                @if($pending->count())
                    <span style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:700">{{ $pending->count() }}</span>
                @endif
            </div>
        </div>

        @if($pending->isEmpty())
            <div class="empty-state">
                <i class="fas fa-check-circle" style="font-size:28px;opacity:.3;display:block;margin-bottom:8px"></i>
                No pending password reset requests.
            </div>
        @else
            <div class="req-list">
                @foreach($pending as $req)
                <div class="req-card urgent">
                    <div class="req-meta">
                        <div>
                            <div class="req-name">{{ $req->user->name }}</div>
                            <div class="req-email">{{ $req->user->email }}</div>
                        </div>
                        <span class="badge badge-pending" style="margin-left:8px">Pending</span>
                        <span class="req-time"><i class="fas fa-clock" style="margin-right:3px"></i>{{ $req->created_at->diffForHumans() }}</span>
                    </div>
                    @if($req->note)
                        <div class="req-note">"{{ $req->note }}"</div>
                    @endif
                    <div class="req-actions">
                        <button class="btn btn-primary" onclick="openResetModal({{ $req->id }}, '{{ addslashes($req->user->name) }}')">
                            <i class="fas fa-key"></i> Reset Password
                        </button>
                        <form method="POST" action="{{ route('password.requests.dismiss', $req->id) }}" style="display:inline">
                            @csrf
                            <button type="submit" class="btn btn-ghost">
                                <i class="fas fa-times"></i> Dismiss
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Recently Resolved --}}
    @if($resolved->isNotEmpty())
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-history"></i> Recently Resolved</div>
        </div>
        <div style="overflow-x:auto">
            <table class="res-table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Resolved By</th>
                        <th>Resolved At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resolved as $req)
                    <tr>
                        <td>
                            <div style="font-weight:600">{{ $req->user->name }}</div>
                            <div style="color:var(--muted);font-size:11px">{{ $req->user->email }}</div>
                        </td>
                        <td>{{ $req->resolver?->name ?? '—' }}</td>
                        <td>{{ $req->resolved_at?->format('M d, Y h:i A') ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

{{-- Reset Password Modal --}}
<div class="modal-backdrop" id="resetBackdrop">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-key" style="margin-right:6px"></i>Reset Password</h3>
            <button class="modal-close" onclick="closeResetModal()">&times;</button>
        </div>
        <form method="POST" id="resetForm" action="">
            @csrf
            <div class="modal-body">
                <p style="font-size:13px;color:var(--muted);margin-bottom:16px">
                    Setting a new password for <strong id="resetUserName"></strong>.
                </p>
                <div class="form-group">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-input" placeholder="Min. 6 characters" required minlength="6">
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="new_password_confirmation" class="form-input" placeholder="Repeat new password" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost" onclick="closeResetModal()">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Password</button>
            </div>
        </form>
    </div>
</div>

<script>
function openResetModal(id, name) {
    document.getElementById('resetUserName').textContent = name;
    document.getElementById('resetForm').action = '/admin/password-requests/' + id + '/resolve';
    document.getElementById('resetBackdrop').classList.add('open');
}
function closeResetModal() {
    document.getElementById('resetBackdrop').classList.remove('open');
}
</script>
@endsection
