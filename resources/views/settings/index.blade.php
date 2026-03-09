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

/* Theme toggle */
.theme-options { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 8px; }
.theme-card { border: 2px solid var(--border-color, #e2e8f0); border-radius: 12px; padding: 16px; cursor: pointer; text-align: center; transition: all .2s; background: var(--card-bg, #fff); }
.theme-card:hover { border-color: #1a3a6b; }
.theme-card.active { border-color: #16a34a; background: #f0fdf4; }
.theme-preview { height: 60px; border-radius: 8px; margin-bottom: 10px; border: 1px solid rgba(0,0,0,.08); overflow: hidden; position: relative; }
.preview-light { background: linear-gradient(135deg, #f0f4f8 60%, #fff 100%); }
.preview-dark  { background: linear-gradient(135deg, #0f1e3d 60%, #1a3a6b 100%); }
.preview-sidebar-l { position: absolute; left: 0; top: 0; bottom: 0; width: 28%; background: #0f1e3d; }
.preview-sidebar-d { position: absolute; left: 0; top: 0; bottom: 0; width: 28%; background: #060e1f; }
.preview-topbar-l { position: absolute; top: 0; left: 28%; right: 0; height: 30%; background: #fff; border-bottom: 1px solid #e2e8f0; }
.preview-topbar-d { position: absolute; top: 0; left: 28%; right: 0; height: 30%; background: #1e293b; border-bottom: 1px solid #334155; }
.theme-name { font-size: 13px; font-weight: 700; color: var(--text-color, #1e293b); }
.theme-desc { font-size: 11px; color: var(--muted-color, #64748b); margin-top: 2px; }
.theme-card.active .theme-name { color: #16a34a; }
.check-icon { display: none; color: #16a34a; font-size: 16px; margin-bottom: 4px; }
.theme-card.active .check-icon { display: block; }

/* Page header */
.page-hdr { margin-bottom: 24px; }
.page-hdr h1 { font-size: 22px; font-weight: 700; color: var(--text-color, #1e293b); margin: 0; }
.breadcrumb { font-size: 13px; color: var(--muted-color, #64748b); margin-top: 3px; }
.breadcrumb span { color: #1a3a6b; font-weight: 500; }

/* Avatar initials */
.avatar { width: 30px; height: 30px; border-radius: 50%; background: #1a3a6b; color: #fff; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; margin-right: 6px; }
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

            {{-- Appearance --}}
            <div class="s-card">
                <div class="s-card-header">
                    <i class="fas fa-palette"></i>
                    <span class="s-card-title">Appearance</span>
                </div>
                <div class="s-card-body">
                    <p style="font-size:13px;color:var(--muted-color,#64748b);margin-bottom:14px">Choose your preferred display theme.</p>
                    <div class="theme-options">

                        <div class="theme-card {{ $theme === 'light' ? 'active' : '' }}" onclick="setTheme('light')">
                            <i class="fas fa-check-circle check-icon"></i>
                            <div class="theme-preview preview-light">
                                <div class="preview-sidebar-l"></div>
                                <div class="preview-topbar-l"></div>
                            </div>
                            <div class="theme-name">☀️ Light</div>
                            <div class="theme-desc">Clean & bright</div>
                        </div>

                        <div class="theme-card {{ $theme === 'dark' ? 'active' : '' }}" onclick="setTheme('dark')">
                            <i class="fas fa-check-circle check-icon"></i>
                            <div class="theme-preview preview-dark">
                                <div class="preview-sidebar-d"></div>
                                <div class="preview-topbar-d"></div>
                            </div>
                            <div class="theme-name">🌙 Dark</div>
                            <div class="theme-desc">Easy on the eyes</div>
                        </div>

                    </div>
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
                                      onsubmit="return confirm('Delete {{ $emp->name }}\'s account?')">
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
</div>

<script>
function setTheme(theme) {
    fetch('{{ route("settings.theme") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ theme })
    }).then(() => {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        // Update active card UI
        document.querySelectorAll('.theme-card').forEach(c => c.classList.remove('active'));
        event.currentTarget.classList.add('active');
    });
}
</script>
@endsection