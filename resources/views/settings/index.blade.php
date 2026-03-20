@extends('layouts.app')

@section('page-title', 'Settings')

@section('content')
<style>
.settings-wrap { padding: 28px; }
.settings-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start; }

/* Cards */
.s-card { background: var(--card-bg, #fff); border: 1px solid var(--border-color, #e2e8f0); border-radius: 16px; overflow: hidden; box-shadow: 0 1px 6px rgba(0,0,0,.06); }
.s-card-header { padding: 18px 24px; border-bottom: 1px solid var(--border-color, #e2e8f0); background: var(--header-bg, #f8fafc); display: flex; align-items: center; gap: 10px; }
.s-card-header i { color: #1a3a6b; font-size: 15px; }
.s-card-title { font-weight: 700; font-size: 14px; color: var(--text-color, #1e293b); }
.s-card-body { padding: 24px; }

/* Form */
.form-group { margin-bottom: 16px; }
.form-label { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--muted-color, #64748b); margin-bottom: 6px; }
.form-label .req { color: #dc2626; margin-left: 2px; }
.form-input { width: 100%; padding: 10px 13px; border: 1.5px solid var(--border-color, #e2e8f0); border-radius: 9px; font-size: 14px; font-family: inherit; color: var(--text-color, #1e293b); background: var(--input-bg, #fff); outline: none; transition: border-color .2s, box-shadow .2s; }
.form-input:focus { border-color: #1a3a6b; box-shadow: 0 0 0 3px rgba(26,58,107,.08); }

/* Buttons */
.btn { display: inline-flex; align-items: center; gap: 7px; padding: 10px 18px; border-radius: 9px; border: none; cursor: pointer; font-family: inherit; font-size: 13px; font-weight: 600; transition: all .15s; text-decoration: none; }
.btn-green { background: #16a34a; color: #fff; width: 100%; justify-content: center; margin-top: 4px; }
.btn-green:hover { background: #15803d; }
.btn-danger { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; padding: 6px 12px; font-size: 12px; }
.btn-danger:hover { background: #ffe4e6; }

/* Alerts */
.alert { padding: 12px 16px; border-radius: 9px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px; }
.alert-success { background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; }
.alert-error   { background: #fff1f2; border: 1px solid #fecdd3; color: #be123c; }

/* Table */
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: 13px; }
thead tr { background: var(--header-bg, #f8fafc); border-bottom: 2px solid var(--border-color, #e2e8f0); }
th { padding: 11px 16px; text-align: left; font-weight: 700; color: var(--muted-color, #64748b); font-size: 11px; text-transform: uppercase; letter-spacing: .06em; }
td { padding: 12px 16px; border-bottom: 1px solid var(--border-color, #e2e8f0); color: var(--text-color, #1e293b); vertical-align: middle; }
tbody tr:last-child td { border-bottom: none; }
tbody tr:hover { background: var(--hover-bg, #f8fafc); }

/* Role badge */
.badge { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; }
.badge-admin    { background: #eff6ff; color: #1d4ed8; }
.badge-employee { background: #f0fdf4; color: #166534; }

/* Page header */
.page-hdr { margin-bottom: 24px; }
.page-hdr h1 { font-size: 22px; font-weight: 700; color: var(--text-color, #1e293b); margin: 0; }
.breadcrumb { font-size: 13px; color: var(--muted-color, #64748b); margin-top: 3px; }
.breadcrumb span { color: #1a3a6b; font-weight: 500; }

/* Avatar initials */
.avatar { width: 30px; height: 30px; border-radius: 50%; background: #1a3a6b; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; margin-right: 6px; }

.hidden { display: none !important; }

/* Restore panel */
.restore-panel { background: #fff7ed; border-bottom: 1px solid var(--border-color, #e2e8f0); padding: 20px 24px; }
.restore-panel .restore-icon { background: #fed7aa; color: #c2410c; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 15px; }
.restore-panel .restore-title { font-weight: 700; font-size: 13px; color: #c2410c; margin-bottom: 4px; }
.restore-panel .restore-desc { font-size: 12px; color: #92400e; margin-bottom: 14px; line-height: 1.6; }
.restore-panel .restore-file { font-size: 13px; color: #1e293b; background: #fff; border: 1.5px solid #fed7aa; border-radius: 8px; padding: 8px 12px; flex: 1; min-width: 220px; }
.btn-restore { background: #c2410c; color: #fff; }
.btn-restore:hover { background: #9a3412; }

[data-theme="dark"] .restore-panel { background: #2a1a0e !important; border-color: #4a2c10 !important; }
[data-theme="dark"] .restore-panel .restore-icon { background: #4a2c10 !important; color: #fb923c !important; }
[data-theme="dark"] .restore-panel .restore-title { color: #fb923c !important; }
[data-theme="dark"] .restore-panel .restore-desc { color: #c2845a !important; }
[data-theme="dark"] .restore-panel .restore-file { background: #1a0f08 !important; color: #d1d9e6 !important; border-color: #4a2c10 !important; }
[data-theme="dark"] .btn-restore { background: #9a3412 !important; color: #fff !important; }
[data-theme="dark"] .btn-restore:hover { background: #7c2d12 !important; }
[data-theme="dark"] .btn-restore-toggle { background: #2a1a0e !important; color: #fb923c !important; border-color: #4a2c10 !important; }
</style>

<div class="settings-wrap">

    <div class="page-hdr">
        <h1><i class="fas fa-cog" style="margin-right:8px"></i>Settings</h1>
        <div class="breadcrumb">Home › <span>Settings</span></div>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
    @endif

    <div class="settings-grid">

        {{-- LEFT COLUMN --}}
        <div>

            {{-- Create Employee Account --}}
            <div class="s-card" style="margin-bottom:24px">
                <div class="s-card-header">
                    <i class="fas fa-user-plus"></i>
                    <span class="s-card-title">Create Employee Account</span>
                </div>
                <div class="s-card-body">
                    <form method="POST" action="{{ route('settings.employee.store') }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Full Name <span class="req">*</span></label>
                            <input type="text" name="name" class="form-input" placeholder="e.g. Juan Dela Cruz" value="{{ old('name') }}" required>
                            @error('name')<div style="color:#dc2626;font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Address <span class="req">*</span></label>
                            <input type="email" name="email" class="form-input" placeholder="employee@barangay.gov.ph" value="{{ old('email') }}" required>
                            @error('email')<div style="color:#dc2626;font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password <span class="req">*</span></label>
                            <input type="password" name="password" class="form-input" placeholder="Min. 6 characters" required>
                            @error('password')<div style="color:#dc2626;font-size:12px;margin-top:4px">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm Password <span class="req">*</span></label>
                            <input type="password" name="password_confirmation" class="form-input" placeholder="Re-enter password" required>
                        </div>
                        <button type="submit" class="btn btn-green">
                            <i class="fas fa-user-plus"></i> Create Employee Account
                        </button>
                    </form>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN - Employee Accounts List --}}
        <div class="s-card">
            <div class="s-card-header">
                <i class="fas fa-users"></i>
                <span class="s-card-title">Employee Accounts ({{ $employees->count() }})</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                        <tr>
                            <td>
                                <span class="avatar">{{ strtoupper(substr($emp->name, 0, 1)) }}</span>
                                {{ $emp->name }}
                            </td>
                            <td style="color:var(--muted-color,#64748b)">{{ $emp->email }}</td>
                            <td><span class="badge badge-{{ $emp->role }}">{{ ucfirst($emp->role) }}</span></td>
                            <td>
                                <form method="POST" action="{{ route('settings.employee.destroy', $emp->id) }}"
                                    onsubmit="return confirmDelete(this, 'Delete employee account for {{ addslashes($emp->name) }}? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;padding:32px;color:var(--muted-color,#64748b)">
                                <i class="fas fa-users" style="font-size:32px;opacity:.3;display:block;margin-bottom:8px"></i>
                                No employee accounts yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Backup Card (full width) --}}
    <div class="s-card" style="margin-top:24px">
        <div class="s-card-header" style="justify-content:space-between">
            <div style="display:flex;align-items:center;gap:10px">
                <i class="fas fa-database"></i>
                <span class="s-card-title">Database Backup</span>
            </div>
            <div style="display:flex;gap:8px;align-items:center">
                <button type="button" onclick="document.getElementById('restore-panel').classList.toggle('hidden')"
                    class="btn btn-restore-toggle" style="width:auto;margin-top:0;background:#fff7ed;color:#c2410c;border:1px solid #fed7aa">
                    <i class="fas fa-upload"></i> Restore from Backup
                </button>
                <form method="POST" action="{{ route('settings.backup') }}">
                    @csrf
                    <button type="submit" class="btn btn-green" style="width:auto;margin-top:0">
                        <i class="fas fa-download"></i> Backup Now
                    </button>
                </form>
            </div>
        </div>

        {{-- Restore panel (hidden by default) --}}
        <div id="restore-panel" class="restore-panel hidden">
            <div style="display:flex;align-items:flex-start;gap:12px">
                <div class="restore-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div style="flex:1">
                    <div class="restore-title">Restore Database</div>
                    <div class="restore-desc">
                        This will <strong>replace all current data</strong> with the contents of the uploaded backup file.
                        The current database will be auto-saved as a backup before restoring.
                    </div>
                    <form method="POST" action="{{ route('settings.backup.restore') }}" enctype="multipart/form-data"
                        onsubmit="return confirm('Are you sure you want to restore this backup? All current data will be replaced.')">
                        @csrf
                        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                            <input type="file" name="backup_file" accept=".sqlite" required class="restore-file">
                            <button type="submit" class="btn btn-restore" style="width:auto;margin-top:0;flex-shrink:0">
                                <i class="fas fa-undo"></i> Restore Now
                            </button>
                        </div>
                        @error('backup_file')
                            <div style="color:#dc2626;font-size:12px;margin-top:6px">{{ $message }}</div>
                        @enderror
                    </form>
                </div>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Filename</th>
                        <th>Created</th>
                        <th>Size</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($backups as $backup)
                    <tr>
                        <td style="font-family:monospace;font-size:13px">{{ $backup['filename'] }}</td>
                        <td style="color:var(--muted-color,#64748b)">{{ $backup['created'] }}</td>
                        <td style="color:var(--muted-color,#64748b)">{{ $backup['size'] }} KB</td>
                        <td>
                            <a href="{{ route('settings.backup.download', $backup['filename']) }}"
                               class="btn btn-danger" style="text-decoration:none">
                                <i class="fas fa-file-download"></i> Download
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;padding:32px;color:var(--muted-color,#64748b)">
                            <i class="fas fa-database" style="font-size:32px;opacity:.3;display:block;margin-bottom:8px"></i>
                            No backups yet. Click "Backup Now" to create one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection