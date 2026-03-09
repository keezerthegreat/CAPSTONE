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
.dash-wrap { background:var(--bg); min-height:100vh; padding:24px 28px; }
.dash-hdr { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:22px; flex-wrap:wrap; gap:12px; }
.dash-hdr h1 { font-size:20px; font-weight:700; color:var(--primary); margin:0; letter-spacing:-.01em; }
.dash-hdr .sub { font-size:12px; color:var(--muted); margin-top:3px; font-weight:500; }

/* Summary Cards */
.summary-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:20px; }
.summary-card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 4px rgba(0,0,0,.05); padding:20px; position:relative; cursor:pointer; transition:box-shadow .2s,transform .2s; }
.summary-card:hover { box-shadow:0 4px 18px rgba(26,58,107,.11); transform:translateY(-2px); }
.summary-card .sc-icon { width:44px; height:44px; border-radius:11px; display:flex; align-items:center; justify-content:center; font-size:18px; margin-bottom:12px; }
.summary-card .sc-label { font-size:10.5px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.07em; margin-bottom:5px; }
.summary-card .sc-value { font-size:32px; font-weight:800; color:var(--primary); line-height:1; letter-spacing:-.02em; }
.summary-card .sc-sub { font-size:11.5px; color:var(--muted); margin-top:5px; }

/* Tooltip */
.sc-tooltip { display:none; position:absolute; top:calc(100% + 8px); left:50%; transform:translateX(-50%); background:#0f172a; color:#fff; border-radius:10px; padding:11px 14px; font-size:11.5px; z-index:100; width:210px; box-shadow:0 8px 24px rgba(0,0,0,.22); pointer-events:none; }
.sc-tooltip::before { content:''; position:absolute; top:-5px; left:50%; transform:translateX(-50%); border:5px solid transparent; border-bottom-color:#0f172a; border-top:none; }
.summary-card:hover .sc-tooltip { display:block; }
.sc-tooltip-row { display:flex; justify-content:space-between; padding:3px 0; border-bottom:1px solid rgba(255,255,255,.08); }
.sc-tooltip-row:last-child { border-bottom:none; }
.sc-tooltip-row .tl { color:#94a3b8; }
.sc-tooltip-row .tv { font-weight:700; color:#fff; }

/* Expand */
.sc-expand { display:none; background:var(--header-bg); border-top:1px solid var(--border); margin:16px -20px -20px; padding:14px 20px; border-radius:0 0 14px 14px; }
.sc-expand.open { display:block; }
.expand-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; }
.expand-item { background:var(--card); border-radius:9px; border:1px solid var(--border); padding:11px; text-align:center; }
.expand-item .ei-val { font-size:20px; font-weight:800; color:var(--primary); letter-spacing:-.01em; }
.expand-item .ei-label { font-size:10.5px; color:var(--muted); font-weight:600; margin-top:2px; }
.expand-item .ei-pct { font-size:10.5px; color:var(--muted); margin-top:1px; }

/* Donut charts */
.donut-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:20px; }
.donut-card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 4px rgba(0,0,0,.05); padding:18px; }
.donut-card .dc-title { font-size:12.5px; font-weight:700; color:var(--primary); margin-bottom:14px; display:flex; align-items:center; gap:7px; }
.donut-wrap { display:flex; align-items:center; justify-content:center; margin-bottom:12px; }
.donut-legend { display:flex; flex-wrap:wrap; gap:6px; justify-content:center; }
.legend-item { display:flex; align-items:center; gap:4px; font-size:11px; color:var(--text); font-weight:500; }
.legend-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }

/* Cards */
.two-col { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px; }
.card { background:var(--card); border-radius:12px; border:1px solid var(--border); box-shadow:0 1px 4px rgba(0,0,0,.05); overflow:hidden; }
.card-header { padding:13px 18px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; background:var(--header-bg); }
.card-title { font-weight:700; color:var(--primary); font-size:13px; display:flex; align-items:center; gap:7px; }
.recent-table { width:100%; border-collapse:collapse; font-size:12.5px; }
.recent-table th { padding:9px 14px; text-align:left; font-weight:700; color:var(--muted); font-size:10.5px; text-transform:uppercase; letter-spacing:.07em; background:var(--header-bg); border-bottom:1px solid var(--border); }
.recent-table td { padding:11px 14px; border-bottom:1px solid var(--border); color:var(--text); vertical-align:middle; }
.recent-table tr:last-child td { border-bottom:none; }
.recent-table tbody tr:hover { background:var(--hover-bg); }
.badge { display:inline-flex; align-items:center; padding:2px 8px; border-radius:20px; font-size:10.5px; font-weight:600; }
.badge-senior { background:#fef3c7; color:#92400e; }
.badge-pwd    { background:#fee2e2; color:#991b1b; }
.badge-voter  { background:#f3e8ff; color:#6b21a8; }
.quick-actions { display:flex; gap:8px; padding:14px 18px; flex-wrap:wrap; }
.qa-btn { display:flex; align-items:center; gap:7px; padding:8px 14px; border-radius:9px; border:1.5px solid var(--border); text-decoration:none; color:var(--text); font-size:12.5px; font-weight:600; transition:all .15s; background:var(--card); white-space:nowrap; }
.qa-btn:hover { border-color:var(--primary); color:var(--primary); }
.qa-btn i { width:26px; height:26px; border-radius:7px; display:flex; align-items:center; justify-content:center; font-size:12px; flex-shrink:0; }
</style>

<div class="dash-wrap">

  <div class="dash-hdr">
    <div>
      <h1>Overview</h1>
      <div class="sub">Barangay Cogon, Ormoc City, Leyte &mdash; Population & Records Summary</div>
    </div>
  </div>

  <!-- Quick Actions Bar -->
  <div class="card" style="margin-bottom:20px">
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

  <!-- Recent Activity -->
  <div class="card" style="margin-bottom:20px">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-history"></i> Recent Activity</div>
      <a href="{{ route('audit.index') }}" style="font-size:12px;color:var(--primary);text-decoration:none;font-weight:600">View All →</a>
    </div>
    <table class="recent-table">
      <thead>
        <tr><th>User</th><th>Action</th><th>Module</th><th>Description</th><th>Time</th></tr>
      </thead>
      <tbody>
        @forelse($recentLogs as $log)
        @php
          $badgeMap = [
            'created'    => ['bg'=>'#dcfce7','color'=>'#15803d'],
            'updated'    => ['bg'=>'#dbeafe','color'=>'#1e40af'],
            'deleted'    => ['bg'=>'#fee2e2','color'=>'#991b1b'],
            'logged_in'  => ['bg'=>'#fef9c3','color'=>'#854d0e'],
            'logged_out' => ['bg'=>'#f3e8ff','color'=>'#6b21a8'],
            'printed'    => ['bg'=>'#e0f2fe','color'=>'#0369a1'],
          ];
          $b = $badgeMap[$log->action] ?? ['bg'=>'#f1f5f9','color'=>'#475569'];
        @endphp
        <tr>
          <td style="font-weight:600;white-space:nowrap">{{ $log->user_name }}</td>
          <td>
            <span style="display:inline-flex;align-items:center;padding:2px 9px;border-radius:20px;font-size:10.5px;font-weight:700;background:{{ $b['bg'] }};color:{{ $b['color'] }}">
              {{ ucfirst(str_replace('_',' ',$log->action)) }}
            </span>
          </td>
          <td>
            <span style="display:inline-block;padding:2px 7px;border-radius:5px;font-size:10.5px;font-weight:600;background:var(--header-bg);color:var(--muted);border:1px solid var(--border)">{{ $log->module }}</span>
          </td>
          <td style="color:var(--muted);font-size:12px;max-width:320px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $log->description }}</td>
          <td style="color:var(--muted);font-size:11px;white-space:nowrap">{{ $log->created_at->diffForHumans() }}</td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--muted)">No activity logged yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>

<script>
document.getElementById('residentCard').addEventListener('click', function() {
  document.getElementById('residentExpand').classList.toggle('open');
});
</script>
@endsection