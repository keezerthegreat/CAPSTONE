@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<style>
:root { --primary:#1a3a6b; --primary-light:#2554a0; --accent:#f0a500; --bg:#f0f4f8; --card:#fff; --text:#1e293b; --muted:#64748b; --border:#e2e8f0; }
.dash-wrap { background:var(--bg); min-height:100vh; padding:28px; }

/* Header */
.dash-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.dash-hdr h1 { font-size:22px; font-weight:700; color:var(--primary); margin:0; }
.dash-hdr .sub { font-size:13px; color:var(--muted); margin-top:2px; }

/* Stat Cards */
.stat-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
.stat-card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); padding:20px; display:flex; align-items:center; gap:16px; }
.stat-icon { width:52px; height:52px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0; }
.stat-icon.blue   { background:#dbeafe; color:#1d4ed8; }
.stat-icon.green  { background:#dcfce7; color:#16a34a; }
.stat-icon.yellow { background:#fef3c7; color:#d97706; }
.stat-icon.red    { background:#fee2e2; color:#dc2626; }
.stat-icon.purple { background:#f3e8ff; color:#7c3aed; }
.stat-info .label { font-size:12px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:4px; }
.stat-info .value { font-size:28px; font-weight:800; color:var(--primary); line-height:1; }
.stat-info .trend { font-size:11px; color:var(--muted); margin-top:4px; }

/* Cards */
.card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden; }
.card-header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.card-title { font-weight:700; color:var(--primary); font-size:14px; display:flex; align-items:center; gap:8px; }
.card-body { padding:20px; }

/* Two column layout */
.two-col { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px; }
.three-col { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; margin-bottom:20px; }

/* Breakdown table */
.breakdown-table { width:100%; border-collapse:collapse; font-size:13px; }
.breakdown-table td { padding:9px 0; border-bottom:1px solid var(--border); color:var(--text); }
.breakdown-table tr:last-child td { border-bottom:none; }
.breakdown-table .label-col { color:var(--muted); font-weight:500; }
.breakdown-table .value-col { font-weight:700; color:var(--primary); text-align:right; }
.breakdown-table .pct-col { text-align:right; color:var(--muted); font-size:12px; padding-left:12px; }

/* Progress bar */
.prog-bar { height:6px; background:#e2e8f0; border-radius:4px; margin-top:4px; overflow:hidden; }
.prog-fill { height:100%; border-radius:4px; }

/* Recent table */
.recent-table { width:100%; border-collapse:collapse; font-size:13px; }
.recent-table th { padding:10px 14px; text-align:left; font-weight:700; color:var(--muted); font-size:11px; text-transform:uppercase; letter-spacing:.06em; background:#f8fafc; border-bottom:2px solid var(--border); }
.recent-table td { padding:12px 14px; border-bottom:1px solid var(--border); color:var(--text); vertical-align:middle; }
.recent-table tr:last-child td { border-bottom:none; }
.recent-table tbody tr:hover { background:#f8fafc; }

/* Badge */
.badge { display:inline-flex; align-items:center; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:600; }
.badge-senior { background:#fef3c7; color:#92400e; }
.badge-pwd    { background:#fee2e2; color:#991b1b; }
.badge-voter  { background:#f3e8ff; color:#6b21a8; }

/* Quick action */
.quick-actions { display:grid; grid-template-columns:repeat(2,1fr); gap:10px; }
.qa-btn { display:flex; align-items:center; gap:10px; padding:12px 14px; border-radius:10px; border:1.5px solid var(--border); text-decoration:none; color:var(--text); font-size:13px; font-weight:600; transition:all .15s; background:var(--card); }
.qa-btn:hover { border-color:var(--primary); color:var(--primary); background:#f0f4f8; }
.qa-btn i { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:14px; }
</style>

<div class="dash-wrap">

  <!-- Header -->
  <div class="dash-hdr">
    <div>
      <h1><i class="fas fa-tachometer-alt" style="margin-right:8px"></i>Dashboard</h1>
      <div class="sub">Barangay Cogon, Ormoc City, Leyte — Overview</div>
    </div>
  </div>

  <!-- Stat Cards -->
  <div class="stat-grid">
    <div class="stat-card">
      <div class="stat-icon blue"><i class="fas fa-users"></i></div>
      <div class="stat-info">
        <div class="label">Total Residents</div>
        <div class="value">{{ $totalResidents }}</div>
        <div class="trend">Registered in system</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon green"><i class="fas fa-star"></i></div>
      <div class="stat-info">
        <div class="label">Senior Citizens</div>
        <div class="value">{{ $seniors }}</div>
        <div class="trend">{{ $totalResidents > 0 ? round(($seniors/$totalResidents)*100,1) : 0 }}% of population</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon yellow"><i class="fas fa-wheelchair"></i></div>
      <div class="stat-info">
        <div class="label">Persons w/ Disability</div>
        <div class="value">{{ $pwd }}</div>
        <div class="trend">{{ $totalResidents > 0 ? round(($pwd/$totalResidents)*100,1) : 0 }}% of population</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon purple"><i class="fas fa-vote-yea"></i></div>
      <div class="stat-info">
        <div class="label">Registered Voters</div>
        <div class="value">{{ $voters }}</div>
        <div class="trend">{{ $totalResidents > 0 ? round(($voters/$totalResidents)*100,1) : 0 }}% of population</div>
      </div>
    </div>
  </div>

  <!-- Second Row Stats -->
  <div class="stat-grid" style="margin-bottom:24px">
    <div class="stat-card">
      <div class="stat-icon blue"><i class="fas fa-mars"></i></div>
      <div class="stat-info">
        <div class="label">Male</div>
        <div class="value">{{ $male }}</div>
        <div class="trend">{{ $totalResidents > 0 ? round(($male/$totalResidents)*100,1) : 0 }}% of population</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon red"><i class="fas fa-venus"></i></div>
      <div class="stat-info">
        <div class="label">Female</div>
        <div class="value">{{ $female }}</div>
        <div class="trend">{{ $totalResidents > 0 ? round(($female/$totalResidents)*100,1) : 0 }}% of population</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon green"><i class="fas fa-child"></i></div>
      <div class="stat-info">
        <div class="label">Minors (Below 18)</div>
        <div class="value">{{ $minors }}</div>
        <div class="trend">{{ $totalResidents > 0 ? round(($minors/$totalResidents)*100,1) : 0 }}% of population</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon yellow"><i class="fas fa-file-alt"></i></div>
      <div class="stat-info">
        <div class="label">Clearances Issued</div>
        <div class="value">{{ $clearances }}</div>
        <div class="trend">Total issued</div>
      </div>
    </div>
  </div>

  <!-- Middle Row -->
  <div class="two-col">

    <!-- Sex Breakdown -->
    <div class="card">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-venus-mars"></i> Sex Breakdown</div>
      </div>
      <div class="card-body">
        <table class="breakdown-table">
          <tr>
            <td class="label-col">Male</td>
            <td>
              <div class="prog-bar"><div class="prog-fill" style="width:{{ $totalResidents > 0 ? round(($male/$totalResidents)*100) : 0 }}%;background:#3b82f6"></div></div>
            </td>
            <td class="value-col">{{ $male }}</td>
            <td class="pct-col">{{ $totalResidents > 0 ? round(($male/$totalResidents)*100,1) : 0 }}%</td>
          </tr>
          <tr>
            <td class="label-col">Female</td>
            <td>
              <div class="prog-bar"><div class="prog-fill" style="width:{{ $totalResidents > 0 ? round(($female/$totalResidents)*100) : 0 }}%;background:#ec4899"></div></div>
            </td>
            <td class="value-col">{{ $female }}</td>
            <td class="pct-col">{{ $totalResidents > 0 ? round(($female/$totalResidents)*100,1) : 0 }}%</td>
          </tr>
        </table>
      </div>
    </div>

    <!-- Age Group Breakdown -->
    <div class="card">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-birthday-cake"></i> Age Group Breakdown</div>
      </div>
      <div class="card-body">
        <table class="breakdown-table">
          <tr>
            <td class="label-col">Minors (Below 18)</td>
            <td>
              <div class="prog-bar"><div class="prog-fill" style="width:{{ $totalResidents > 0 ? round(($minors/$totalResidents)*100) : 0 }}%;background:#3b82f6"></div></div>
            </td>
            <td class="value-col">{{ $minors }}</td>
            <td class="pct-col">{{ $totalResidents > 0 ? round(($minors/$totalResidents)*100,1) : 0 }}%</td>
          </tr>
          <tr>
            <td class="label-col">Adults (18–59)</td>
            <td>
              <div class="prog-bar"><div class="prog-fill" style="width:{{ $totalResidents > 0 ? round(($adults/$totalResidents)*100) : 0 }}%;background:#10b981"></div></div>
            </td>
            <td class="value-col">{{ $adults }}</td>
            <td class="pct-col">{{ $totalResidents > 0 ? round(($adults/$totalResidents)*100,1) : 0 }}%</td>
          </tr>
          <tr>
            <td class="label-col">Seniors (60+)</td>
            <td>
              <div class="prog-bar"><div class="prog-fill" style="width:{{ $totalResidents > 0 ? round(($seniors/$totalResidents)*100) : 0 }}%;background:#f59e0b"></div></div>
            </td>
            <td class="value-col">{{ $seniors }}</td>
            <td class="pct-col">{{ $totalResidents > 0 ? round(($seniors/$totalResidents)*100,1) : 0 }}%</td>
          </tr>
        </table>
      </div>
    </div>

  </div>

  <!-- Recent Residents + Quick Actions -->
  <div class="two-col">

    <!-- Recent Residents -->
    <div class="card">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-clock"></i> Recently Added Residents</div>
        <a href="{{ route('residents.index') }}" style="font-size:12px;color:var(--primary);text-decoration:none;font-weight:600">View All →</a>
      </div>
      <div class="card-body" style="padding:0">
        <table class="recent-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Sex / Age</th>
              <th>Classifications</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentResidents as $r)
            <tr>
              <td>
                <div style="font-weight:600">{{ $r->last_name }}, {{ $r->first_name }}</div>
                <div style="font-size:11px;color:var(--muted)">ID #{{ $r->id }}</div>
              </td>
              <td>{{ $r->gender }} / {{ $r->age }} yrs</td>
              <td>
                @if($r->is_senior)<span class="badge badge-senior">Senior</span>@endif
                @if($r->is_pwd)<span class="badge badge-pwd">PWD</span>@endif
                @if($r->is_voter)<span class="badge badge-voter">Voter</span>@endif
                @if(!$r->is_senior && !$r->is_pwd && !$r->is_voter)<span style="color:var(--muted);font-size:12px">—</span>@endif
              </td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align:center;padding:24px;color:var(--muted)">No residents yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-bolt"></i> Quick Actions</div>
      </div>
      <div class="card-body">
        <div class="quick-actions">
          <a href="{{ route('residents.create') }}" class="qa-btn">
            <i class="fas fa-user-plus" style="background:#dbeafe;color:#1d4ed8"></i>
            Add Resident
          </a>
          <a href="{{ route('clearance.index') }}" class="qa-btn">
            <i class="fas fa-file-alt" style="background:#dcfce7;color:#16a34a"></i>
            New Clearance
          </a>
          <a href="{{ route('certificate.index') }}" class="qa-btn">
            <i class="fas fa-certificate" style="background:#fef3c7;color:#d97706"></i>
            New Certificate
          </a>
          <a href="{{ route('residents.index') }}" class="qa-btn">
            <i class="fas fa-users" style="background:#f3e8ff;color:#7c3aed"></i>
            View Residents
          </a>
          <a href="{{ route('residents.location') }}" class="qa-btn">
            <i class="fas fa-map-marker-alt" style="background:#fee2e2;color:#dc2626"></i>
            Resident Map
          </a>
          <a href="{{ route('workers.index') }}" class="qa-btn">
            <i class="fas fa-user-tie" style="background:#e0f2fe;color:#0284c7"></i>
            Worker Info
          </a>
        </div>
      </div>
    </div>

  </div>

</div>
@endsection