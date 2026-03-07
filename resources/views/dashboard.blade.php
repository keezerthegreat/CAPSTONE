@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
@php
function svgDonut($segments, $size=160, $thickness=30) {
    $total = array_sum(array_column($segments, 'value'));
    if ($total == 0) {
        $r = ($size/2) - ($thickness/2);
        $cx = $size/2; $cy = $size/2;
        return "<svg width=\"{$size}\" height=\"{$size}\" viewBox=\"0 0 {$size} {$size}\"><circle cx=\"{$cx}\" cy=\"{$cy}\" r=\"{$r}\" fill=\"none\" stroke=\"#e2e8f0\" stroke-width=\"{$thickness}\"/></svg>";
    }
    $r = ($size/2) - ($thickness/2);
    $cx = $size/2; $cy = $size/2;
    $circumference = 2 * M_PI * $r;
    $paths = '';
    $offset = 0;
    foreach ($segments as $seg) {
        $pct = $seg['value'] / $total;
        $dash = $pct * $circumference;
        $gap = $circumference - $dash;
        $rotate = ($offset * 360) - 90;
        $paths .= "<circle cx=\"{$cx}\" cy=\"{$cy}\" r=\"{$r}\" fill=\"none\" stroke=\"{$seg['color']}\" stroke-width=\"{$thickness}\" stroke-dasharray=\"{$dash} {$gap}\" stroke-dashoffset=\"0\" transform=\"rotate({$rotate} {$cx} {$cy})\" />";
        $offset += $pct;
    }
    return "<svg width=\"{$size}\" height=\"{$size}\" viewBox=\"0 0 {$size} {$size}\">{$paths}</svg>";
}
@endphp
<style>
:root { --primary:#1a3a6b; --primary-light:#2554a0; --accent:#f0a500; --bg:#f0f4f8; --card:#fff; --text:#1e293b; --muted:#64748b; --border:#e2e8f0; }
.dash-wrap { background:var(--bg); min-height:100vh; padding:28px; }
.dash-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.dash-hdr h1 { font-size:22px; font-weight:700; color:var(--primary); margin:0; }
.dash-hdr .sub { font-size:13px; color:var(--muted); margin-top:2px; }

/* Summary Cards */
.summary-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; margin-bottom:24px; }
.summary-card { background:var(--card); border-radius:16px; border:1px solid var(--border); box-shadow:0 2px 8px rgba(0,0,0,.06); padding:24px; position:relative; cursor:pointer; transition:box-shadow .2s, transform .2s; }
.summary-card:hover { box-shadow:0 6px 20px rgba(26,58,107,.13); transform:translateY(-2px); }
.summary-card .sc-icon { width:56px; height:56px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:24px; margin-bottom:14px; }
.summary-card .sc-label { font-size:12px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; margin-bottom:6px; }
.summary-card .sc-value { font-size:36px; font-weight:900; color:var(--primary); line-height:1; }
.summary-card .sc-sub { font-size:12px; color:var(--muted); margin-top:6px; }

/* Tooltip */
.sc-tooltip { display:none; position:absolute; top:calc(100% + 10px); left:50%; transform:translateX(-50%); background:#1e293b; color:#fff; border-radius:12px; padding:12px 16px; font-size:12px; z-index:100; width:220px; box-shadow:0 8px 24px rgba(0,0,0,.18); pointer-events:none; }
.sc-tooltip::before { content:''; position:absolute; top:-6px; left:50%; transform:translateX(-50%); border:6px solid transparent; border-bottom-color:#1e293b; border-top:none; }
.summary-card:hover .sc-tooltip { display:block; }
.sc-tooltip-row { display:flex; justify-content:space-between; padding:4px 0; border-bottom:1px solid rgba(255,255,255,.1); }
.sc-tooltip-row:last-child { border-bottom:none; }
.sc-tooltip-row .tl { color:#94a3b8; }
.sc-tooltip-row .tv { font-weight:700; color:#fff; }

/* Expand */
.sc-expand { display:none; background:#f8fafc; border-top:1px solid var(--border); margin:16px -24px -24px; padding:16px 24px; border-radius:0 0 16px 16px; }
.sc-expand.open { display:block; }
.expand-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
.expand-item { background:#fff; border-radius:10px; border:1px solid var(--border); padding:12px; text-align:center; }
.expand-item .ei-val { font-size:22px; font-weight:800; color:var(--primary); }
.expand-item .ei-label { font-size:11px; color:var(--muted); font-weight:600; margin-top:2px; }
.expand-item .ei-pct { font-size:11px; color:var(--muted); margin-top:2px; }

/* Donut charts */
.donut-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; margin-bottom:24px; }
.donut-card { background:var(--card); border-radius:16px; border:1px solid var(--border); box-shadow:0 2px 8px rgba(0,0,0,.06); padding:20px; }
.donut-card .dc-title { font-size:13px; font-weight:700; color:var(--primary); margin-bottom:16px; display:flex; align-items:center; gap:8px; }
.donut-wrap { display:flex; align-items:center; justify-content:center; margin-bottom:14px; }
.donut-legend { display:flex; flex-wrap:wrap; gap:8px; justify-content:center; }
.legend-item { display:flex; align-items:center; gap:5px; font-size:11px; color:var(--text); font-weight:500; }
.legend-dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; }

/* Bottom */
.two-col { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px; }
.card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); overflow:hidden; }
.card-header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.card-title { font-weight:700; color:var(--primary); font-size:14px; display:flex; align-items:center; gap:8px; }
.recent-table { width:100%; border-collapse:collapse; font-size:13px; }
.recent-table th { padding:10px 14px; text-align:left; font-weight:700; color:var(--muted); font-size:11px; text-transform:uppercase; letter-spacing:.06em; background:#f8fafc; border-bottom:2px solid var(--border); }
.recent-table td { padding:12px 14px; border-bottom:1px solid var(--border); color:var(--text); vertical-align:middle; }
.recent-table tr:last-child td { border-bottom:none; }
.recent-table tbody tr:hover { background:#f8fafc; }
.badge { display:inline-flex; align-items:center; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:600; }
.badge-senior { background:#fef3c7; color:#92400e; }
.badge-pwd    { background:#fee2e2; color:#991b1b; }
.badge-voter  { background:#f3e8ff; color:#6b21a8; }
.quick-actions { display:grid; grid-template-columns:repeat(2,1fr); gap:10px; padding:20px; }
.qa-btn { display:flex; align-items:center; gap:10px; padding:12px 14px; border-radius:10px; border:1.5px solid var(--border); text-decoration:none; color:var(--text); font-size:13px; font-weight:600; transition:all .15s; background:var(--card); }
.qa-btn:hover { border-color:var(--primary); color:var(--primary); background:#f0f4f8; }
.qa-btn i { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:14px; }
</style>

<div class="dash-wrap">

  <div class="dash-hdr">
    <div>
      <h1><i class="fas fa-tachometer-alt" style="margin-right:8px"></i>Dashboard</h1>
      <div class="sub">Barangay Cogon, Ormoc City, Leyte — Overview</div>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="summary-grid">

    <div class="summary-card" id="residentCard">
      <div class="sc-icon" style="background:#dbeafe;color:#1d4ed8"><i class="fas fa-users"></i></div>
      <div class="sc-label">Total Residents</div>
      <div class="sc-value">{{ $totalResidents }}</div>
      <div class="sc-sub">Living residents in system</div>
      <div class="sc-tooltip">
        <div class="sc-tooltip-row"><span class="tl">Senior Citizens</span><span class="tv">{{ $seniors }}</span></div>
        <div class="sc-tooltip-row"><span class="tl">Persons w/ Disability</span><span class="tv">{{ $pwd }}</span></div>
        <div class="sc-tooltip-row"><span class="tl">Registered Voters</span><span class="tv">{{ $voters }}</span></div>
        <div class="sc-tooltip-row"><span class="tl">Male / Female</span><span class="tv">{{ $male }} / {{ $female }}</span></div>
        <div class="sc-tooltip-row"><span class="tl" style="color:#94a3b8;font-style:italic">Click to expand</span></div>
      </div>
      <div class="sc-expand" id="residentExpand">
        <div style="font-size:12px;font-weight:700;color:var(--muted);margin-bottom:10px;text-transform:uppercase;letter-spacing:.05em">Detailed Breakdown</div>
        <div class="expand-grid">
          <div class="expand-item">
            <div class="ei-val">{{ $seniors }}</div>
            <div class="ei-label">Seniors</div>
            <div class="ei-pct">{{ $totalResidents > 0 ? round(($seniors/$totalResidents)*100,1) : 0 }}%</div>
          </div>
          <div class="expand-item">
            <div class="ei-val">{{ $pwd }}</div>
            <div class="ei-label">PWD</div>
            <div class="ei-pct">{{ $totalResidents > 0 ? round(($pwd/$totalResidents)*100,1) : 0 }}%</div>
          </div>
          <div class="expand-item">
            <div class="ei-val">{{ $voters }}</div>
            <div class="ei-label">Voters</div>
            <div class="ei-pct">{{ $totalResidents > 0 ? round(($voters/$totalResidents)*100,1) : 0 }}%</div>
          </div>
          <div class="expand-item">
            <div class="ei-val">{{ $minors }}</div>
            <div class="ei-label">Minors</div>
            <div class="ei-pct">{{ $totalResidents > 0 ? round(($minors/$totalResidents)*100,1) : 0 }}%</div>
          </div>
          <div class="expand-item">
            <div class="ei-val">{{ $adults }}</div>
            <div class="ei-label">Adults</div>
            <div class="ei-pct">{{ $totalResidents > 0 ? round(($adults/$totalResidents)*100,1) : 0 }}%</div>
          </div>
          <div class="expand-item">
            <div class="ei-val">{{ $clearances }}</div>
            <div class="ei-label">Clearances</div>
            <div class="ei-pct">Total issued</div>
          </div>
        </div>
      </div>
    </div>

    <div class="summary-card">
      <div class="sc-icon" style="background:#dcfce7;color:#16a34a"><i class="fas fa-home"></i></div>
      <div class="sc-label">Total Households</div>
      <div class="sc-value">{{ $totalHouseholds }}</div>
      <div class="sc-sub">Registered households</div>
    </div>

    <div class="summary-card">
      <div class="sc-icon" style="background:#f3e8ff;color:#7c3aed"><i class="fas fa-people-roof"></i></div>
      <div class="sc-label">Total Families</div>
      <div class="sc-value">{{ $totalFamilies }}</div>
      <div class="sc-sub">Registered families</div>
    </div>

  </div>

  <!-- Donut Charts (pure SVG) -->
  <div class="donut-grid">

    <div class="donut-card">
      <div class="dc-title"><i class="fas fa-venus-mars"></i> By Sex</div>
      <div class="donut-wrap">
        {!! svgDonut([
          ['value' => $male,   'color' => '#1a3a6b'],
          ['value' => $female, 'color' => '#f0a500'],
        ]) !!}
      </div>
      <div class="donut-legend">
        <div class="legend-item"><div class="legend-dot" style="background:#1a3a6b"></div>Male: {{ $male }}</div>
        <div class="legend-item"><div class="legend-dot" style="background:#f0a500"></div>Female: {{ $female }}</div>
      </div>
    </div>

    <div class="donut-card">
      <div class="dc-title"><i class="fas fa-birthday-cake"></i> By Age Group</div>
      <div class="donut-wrap">
        {!! svgDonut([
          ['value' => $minors,  'color' => '#22c55e'],
          ['value' => $adults,  'color' => '#3b82f6'],
          ['value' => $seniors, 'color' => '#f0a500'],
        ]) !!}
      </div>
      <div class="donut-legend">
        <div class="legend-item"><div class="legend-dot" style="background:#22c55e"></div>Minors: {{ $minors }}</div>
        <div class="legend-item"><div class="legend-dot" style="background:#3b82f6"></div>Adults: {{ $adults }}</div>
        <div class="legend-item"><div class="legend-dot" style="background:#f0a500"></div>Seniors: {{ $seniors }}</div>
      </div>
    </div>

    <div class="donut-card">
      <div class="dc-title"><i class="fas fa-heart"></i> By Civil Status</div>
      <div class="donut-wrap">
        {!! svgDonut([
          ['value' => $civilStatus['Single'],    'color' => '#6366f1'],
          ['value' => $civilStatus['Married'],   'color' => '#ec4899'],
          ['value' => $civilStatus['Widowed'],   'color' => '#a855f7'],
          ['value' => $civilStatus['Separated'], 'color' => '#94a3b8'],
          ['value' => $civilStatus['Annulled'],  'color' => '#f97316'],
          ['value' => $civilStatus['Live-in'],   'color' => '#14b8a6'],
        ]) !!}
      </div>
      <div class="donut-legend">
        <div class="legend-item"><div class="legend-dot" style="background:#6366f1"></div>Single: {{ $civilStatus['Single'] }}</div>
        <div class="legend-item"><div class="legend-dot" style="background:#ec4899"></div>Married: {{ $civilStatus['Married'] }}</div>
        <div class="legend-item"><div class="legend-dot" style="background:#a855f7"></div>Widowed: {{ $civilStatus['Widowed'] }}</div>
        <div class="legend-item"><div class="legend-dot" style="background:#94a3b8"></div>Separated: {{ $civilStatus['Separated'] }}</div>
        <div class="legend-item"><div class="legend-dot" style="background:#f97316"></div>Annulled: {{ $civilStatus['Annulled'] }}</div>
        <div class="legend-item"><div class="legend-dot" style="background:#14b8a6"></div>Live-in: {{ $civilStatus['Live-in'] }}</div>
      </div>
    </div>

  </div>

  <!-- Recent Residents + Quick Actions -->
  <div class="two-col">

    <div class="card">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-clock"></i> Recently Added Residents</div>
        <a href="{{ route('residents.index') }}" style="font-size:12px;color:var(--primary);text-decoration:none;font-weight:600">View All →</a>
      </div>
      <table class="recent-table">
        <thead>
          <tr><th>Name</th><th>Sex / Age</th><th>Classifications</th></tr>
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

    <div class="card">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-bolt"></i> Quick Actions</div>
      </div>
      <div class="quick-actions">
        <a href="{{ route('residents.create') }}" class="qa-btn">
          <i class="fas fa-user-plus" style="background:#dbeafe;color:#1d4ed8"></i>Add Resident
        </a>
        <a href="{{ route('clearance.index') }}" class="qa-btn">
          <i class="fas fa-file-alt" style="background:#dcfce7;color:#16a34a"></i>New Clearance
        </a>
        <a href="{{ route('certificate.index') }}" class="qa-btn">
          <i class="fas fa-certificate" style="background:#fef3c7;color:#d97706"></i>New Certificate
        </a>
        <a href="{{ route('residents.index') }}" class="qa-btn">
          <i class="fas fa-users" style="background:#f3e8ff;color:#7c3aed"></i>View Residents
        </a>
        <a href="{{ route('residents.location') }}" class="qa-btn">
          <i class="fas fa-map-marker-alt" style="background:#fee2e2;color:#dc2626"></i>Resident Map
        </a>
        <a href="{{ route('workers.index') }}" class="qa-btn">
          <i class="fas fa-user-tie" style="background:#e0f2fe;color:#0284c7"></i>Worker Info
        </a>
      </div>
    </div>

  </div>

</div>

<script>
document.getElementById('residentCard').addEventListener('click', function() {
  document.getElementById('residentExpand').classList.toggle('open');
});
</script>
@endsection