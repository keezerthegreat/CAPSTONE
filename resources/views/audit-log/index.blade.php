@extends('layouts.app')

@section('page-title', 'Audit Log')

@section('content')
<style>
.wrap { background:var(--bg); min-height:100vh; padding:28px; }
.page-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.page-hdr h1 { font-size:22px; font-weight:700; color:var(--primary); margin:0; }
.breadcrumb { font-size:13px; color:var(--muted); margin-top:2px; }
.card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden; }
.card-header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.card-title { font-weight:700; color:var(--primary); font-size:14px; display:flex; align-items:center; gap:8px; }
.card-body { padding:20px; }

/* Filter bar */
.filter-bar { display:flex; flex-wrap:wrap; gap:10px; align-items:flex-end; }
.filter-bar label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--muted); display:block; margin-bottom:4px; }
.filter-bar input, .filter-bar select {
    padding:7px 10px; border:1.5px solid var(--border); border-radius:7px;
    font-size:13px; color:var(--text); font-family:inherit; background:#fff;
    outline:none; transition:border .15s;
}
.filter-bar input:focus, .filter-bar select:focus { border-color:var(--primary); }
.filter-bar input[type="text"] { min-width:200px; }
.btn-filter { padding:8px 16px; background:var(--primary); color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; font-family:inherit; transition:background .15s; }
.btn-filter:hover { background:var(--primary-light); }
.btn-reset { padding:8px 14px; background:#fff; color:var(--muted); border:1.5px solid var(--border); border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; font-family:inherit; text-decoration:none; display:inline-flex; align-items:center; }
.btn-reset:hover { background:#f8fafc; }

/* Table */
.tbl-wrap { overflow-x:auto; }
table { width:100%; border-collapse:collapse; font-size:13px; }
thead { background:#f8fafc; }
th { padding:10px 14px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--muted); border-bottom:1.5px solid var(--border); white-space:nowrap; }
td { padding:12px 14px; border-bottom:1px solid var(--border); color:var(--text); vertical-align:middle; }
tr:last-child td { border-bottom:none; }
tr:hover td { background:#f8fafc; }
.empty { text-align:center; color:var(--muted); padding:40px; font-size:14px; }

/* Action badges */
.badge { display:inline-flex; align-items:center; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:700; white-space:nowrap; }
.badge-created  { background:#dcfce7; color:#15803d; }
.badge-updated  { background:#dbeafe; color:#1e40af; }
.badge-deleted  { background:#fee2e2; color:#991b1b; }
.badge-logged_in  { background:#fef9c3; color:#854d0e; }
.badge-logged_out { background:#f3e8ff; color:#6b21a8; }
.badge-printed  { background:#e0f2fe; color:#0369a1; }
.badge-default  { background:#f1f5f9; color:#475569; }

/* Module badges */
.mod { display:inline-block; padding:2px 8px; border-radius:5px; font-size:11px; font-weight:600; background:#f1f5f9; color:#475569; }

/* Pagination */
.pagination-wrap { display:flex; justify-content:space-between; align-items:center; padding:14px 20px; border-top:1px solid var(--border); }
.pagination-info { font-size:12px; color:var(--muted); }
.pagination-links { display:flex; gap:4px; }
.pagination-links a, .pagination-links span {
    padding:5px 10px; border-radius:6px; font-size:12px; font-weight:600;
    border:1.5px solid var(--border); text-decoration:none; color:var(--primary);
}
.pagination-links span.current { background:var(--primary); color:#fff; border-color:var(--primary); }
.pagination-links a:hover { background:#f0f4f8; }
</style>

<div class="wrap">
  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-history" style="margin-right:8px"></i>Audit Log</h1>
      <div class="breadcrumb">Home › Audit Log</div>
    </div>
  </div>

  <!-- Filters -->
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-filter"></i> Filter Logs</div>
    </div>
    <div class="card-body">
      <form method="GET" action="{{ route('audit.index') }}">
        <div class="filter-bar">
          <div>
            <label>Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or description…">
          </div>
          <div>
            <label>Module</label>
            <select name="module">
              <option value="">All Modules</option>
              @foreach(['Auth','Resident','Household','Family','Clearance','Certificate','Worker'] as $m)
                <option value="{{ $m }}" {{ request('module') === $m ? 'selected' : '' }}>{{ $m }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label>Action</label>
            <select name="action">
              <option value="">All Actions</option>
              @foreach(['created'=>'Created','updated'=>'Updated','deleted'=>'Deleted','logged_in'=>'Logged In','logged_out'=>'Logged Out'] as $val => $label)
                <option value="{{ $val }}" {{ request('action') === $val ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label>Date</label>
            <input type="date" name="date" value="{{ request('date') }}">
          </div>
          <div style="display:flex;gap:8px;align-items:flex-end">
            <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Filter</button>
            <a href="{{ route('audit.index') }}" class="btn-reset"><i class="fas fa-times"></i> Reset</a>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Log Table -->
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-list"></i> Activity Records</div>
      <span style="font-size:12px;color:var(--muted)">{{ $logs->total() }} total entries</span>
    </div>
    <div class="tbl-wrap">
      <table>
        <thead>
          <tr>
            <th>Date & Time</th>
            <th>User</th>
            <th>Action</th>
            <th>Module</th>
            <th>Description</th>
            <th>IP Address</th>
          </tr>
        </thead>
        <tbody>
          @forelse($logs as $log)
          <tr>
            <td style="white-space:nowrap;color:var(--muted);font-size:12px">
              {{ $log->created_at->format('M d, Y') }}<br>
              <span style="font-size:11px">{{ $log->created_at->format('h:i A') }}</span>
            </td>
            <td>
              <div style="font-weight:600">{{ $log->user_name }}</div>
              @if($log->user_id)
              <div style="font-size:11px;color:var(--muted)">ID #{{ $log->user_id }}</div>
              @endif
            </td>
            <td>
              @php
                $badgeClass = match($log->action) {
                  'created'    => 'badge-created',
                  'updated'    => 'badge-updated',
                  'deleted'    => 'badge-deleted',
                  'logged_in'  => 'badge-logged_in',
                  'logged_out' => 'badge-logged_out',
                  'printed'    => 'badge-printed',
                  default      => 'badge-default',
                };
              @endphp
              <span class="badge {{ $badgeClass }}">
                {{ ucfirst(str_replace('_', ' ', $log->action)) }}
              </span>
            </td>
            <td><span class="mod">{{ $log->module }}</span></td>
            <td style="max-width:320px">{{ $log->description }}</td>
            <td style="font-size:12px;color:var(--muted)">{{ $log->ip_address ?? '—' }}</td>
          </tr>
          @empty
          <tr><td colspan="6" class="empty"><i class="fas fa-inbox" style="font-size:24px;display:block;margin-bottom:8px"></i>No activity logs found</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($logs->hasPages())
    <div class="pagination-wrap">
      <div class="pagination-info">
        Showing {{ $logs->firstItem() }}–{{ $logs->lastItem() }} of {{ $logs->total() }} entries
      </div>
      <div class="pagination-links">
        @if($logs->onFirstPage())
          <span>&laquo;</span>
        @else
          <a href="{{ $logs->previousPageUrl() }}">&laquo;</a>
        @endif

        @foreach($logs->getUrlRange(max(1, $logs->currentPage()-2), min($logs->lastPage(), $logs->currentPage()+2)) as $page => $url)
          @if($page == $logs->currentPage())
            <span class="current">{{ $page }}</span>
          @else
            <a href="{{ $url }}">{{ $page }}</a>
          @endif
        @endforeach

        @if($logs->hasMorePages())
          <a href="{{ $logs->nextPageUrl() }}">&raquo;</a>
        @else
          <span>&raquo;</span>
        @endif
      </div>
    </div>
    @endif
  </div>
</div>
@endsection
