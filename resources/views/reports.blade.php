@extends('layouts.app')

@section('page-title', 'Reports')

@section('content')
<style>
:root { --primary:#1a3a6b; --primary-light:#2554a0; --bg:#f0f4f8; --card:#fff; --text:#1e293b; --muted:#64748b; --border:#e2e8f0; }
.bidb-wrap { background:var(--bg); min-height:100vh; padding:28px; }
.page-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.page-hdr h1 { font-size:22px; font-weight:700; color:var(--primary); margin:0; }
.breadcrumb { font-size:13px; color:var(--muted); margin-top:2px; }
.breadcrumb span { color:var(--primary); font-weight:500; }

/* Stat Cards */
.stat-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
.stat-card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); padding:20px; display:flex; align-items:center; gap:16px; }
.stat-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; }
.stat-icon.blue   { background:#dbeafe; color:#1d4ed8; }
.stat-icon.green  { background:#dcfce7; color:#16a34a; }
.stat-icon.yellow { background:#fef3c7; color:#d97706; }
.stat-icon.purple { background:#f3e8ff; color:#7c3aed; }
.stat-icon.red    { background:#fee2e2; color:#dc2626; }
.stat-info .slabel { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:4px; }
.stat-info .svalue { font-size:26px; font-weight:800; color:var(--primary); line-height:1; }

/* Cards */
.card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden; }
.card-header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; background:#f8fafc; }
.card-title { font-weight:700; color:var(--primary); font-size:14px; display:flex; align-items:center; gap:8px; }
.card-body { padding:20px; }

/* Two col */
.two-col { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px; }

/* Breakdown table */
.breakdown-table { width:100%; border-collapse:collapse; font-size:13px; }
.breakdown-table td { padding:9px 0; border-bottom:1px solid var(--border); color:var(--text); vertical-align:middle; }
.breakdown-table tr:last-child td { border-bottom:none; }
.breakdown-table .label-col { color:var(--muted); font-weight:500; width:40%; }
.breakdown-table .bar-col { width:35%; padding:0 12px; }
.breakdown-table .value-col { font-weight:700; color:var(--primary); text-align:right; }
.breakdown-table .pct-col { text-align:right; color:var(--muted); font-size:12px; padding-left:8px; width:10%; }
.prog-bar { height:6px; background:#e2e8f0; border-radius:4px; overflow:hidden; }
.prog-fill { height:100%; border-radius:4px; }

/* List table */
.list-table { width:100%; border-collapse:collapse; font-size:13px; }
.list-table th { padding:10px 14px; text-align:left; font-weight:700; color:var(--muted); font-size:11px; text-transform:uppercase; letter-spacing:.06em; background:#f8fafc; border-bottom:2px solid var(--border); }
.list-table td { padding:11px 14px; border-bottom:1px solid var(--border); color:var(--text); }
.list-table tr:last-child td { border-bottom:none; }
.list-table tbody tr:hover { background:#f8fafc; }

/* Badge */
.badge { display:inline-flex; align-items:center; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:600; }
.badge-senior { background:#fef3c7; color:#92400e; }
.badge-pwd    { background:#fee2e2; color:#991b1b; }
.badge-voter  { background:#f3e8ff; color:#6b21a8; }
.badge-minor  { background:#dbeafe; color:#1e40af; }

/* Print button */
.btn { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:8px; border:none; cursor:pointer; font-family:inherit; font-size:13px; font-weight:600; transition:all .15s; text-decoration:none; }
.btn-primary { background:var(--primary); color:#fff; }
.btn-primary:hover { background:var(--primary-light); }
.btn-outline { background:#fff; color:var(--primary); border:1.5px solid var(--primary); }

/* Tabs */
.tabs { display:flex; gap:4px; padding:16px 20px 0; border-bottom:1px solid var(--border); background:#f8fafc; }
.tab { padding:8px 16px; border-radius:8px 8px 0 0; font-size:13px; font-weight:600; cursor:pointer; color:var(--muted); border:1px solid transparent; border-bottom:none; transition:all .15s; }
.tab.active { background:#fff; color:var(--primary); border-color:var(--border); margin-bottom:-1px; }
.tab-content { display:none; }
.tab-content.active { display:block; }

@media print {
  .sidebar, .topbar, .page-hdr .btn, .tabs { display:none !important; }
  .bidb-wrap { padding:0; }
  .card { box-shadow:none; border:1px solid #ccc; }
}
</style>

<div class="bidb-wrap">

  <!-- Header -->
  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-chart-bar" style="margin-right:8px"></i>Reports</h1>
      <div class="breadcrumb">Home › <span>Reports</span></div>
    </div>
    <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Print Report</button>
  </div>

  <!-- Stat Cards -->
  <div class="stat-grid">
    <div class="stat-card">
      <div class="stat-icon blue"><i class="fas fa-users"></i></div>
      <div class="stat-info"><div class="slabel">Total Residents</div><div class="svalue">{{ $totalResidents }}</div></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon green"><i class="fas fa-star"></i></div>
      <div class="stat-info"><div class="slabel">Senior Citizens</div><div class="svalue">{{ $seniors }}</div></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon yellow"><i class="fas fa-wheelchair"></i></div>
      <div class="stat-info"><div class="slabel">PWD</div><div class="svalue">{{ $pwd }}</div></div>
    </div>
    <div class="stat-card">
      <div class="stat-icon purple"><i class="fas fa-vote-yea"></i></div>
      <div class="stat-info"><div class="slabel">Registered Voters</div><div class="svalue">{{ $voters }}</div></div>
    </div>
  </div>

  <!-- Tabs -->
  <div class="card" style="overflow:visible">
    <div class="tabs">
      <div class="tab active" onclick="switchTab('population')">Population Summary</div>
      <div class="tab" onclick="switchTab('demographics')">Demographics</div>
      <div class="tab" onclick="switchTab('seniors')">Senior Citizens</div>
      <div class="tab" onclick="switchTab('pwd')">PWD</div>
      <div class="tab" onclick="switchTab('voters')">Voters</div>
      <div class="tab" onclick="switchTab('minors')">Minors</div>
    </div>

    <!-- Population Summary Tab -->
    <div id="tab-population" class="tab-content active">
      <div class="card-body">
        <div class="two-col">

          <!-- Sex Breakdown -->
          <div>
            <div style="font-weight:700;color:var(--primary);margin-bottom:14px;font-size:13px"><i class="fas fa-venus-mars" style="margin-right:6px"></i>Sex Breakdown</div>
            <table class="breakdown-table">
              <tr>
                <td class="label-col">Male</td>
                <td class="bar-col"><div class="prog-bar"><div class="prog-fill" style="width:{{ $totalResidents > 0 ? round(($male/$totalResidents)*100) : 0 }}%;background:#3b82f6"></div></div></td>
                <td class="value-col">{{ $male }}</td>
                <td class="pct-col">{{ $totalResidents > 0 ? round(($male/$totalResidents)*100,1) : 0 }}%</td>
              </tr>
              <tr>
                <td class="label-col">Female</td>
                <td class="bar-col"><div class="prog-bar"><div class="prog-fill" style="width:{{ $totalResidents > 0 ? round(($female/$totalResidents)*100) : 0 }}%;background:#ec4899"></div></div></td>
                <td class="value-col">{{ $female }}</td>
                <td class="pct-col">{{ $totalResidents > 0 ? round(($female/$totalResidents)*100,1) : 0 }}%</td>
              </tr>
            </table>
          </div>

          <!-- Age Group -->
          <div>
            <div style="font-weight:700;color:var(--primary);margin-bottom:14px;font-size:13px"><i class="fas fa-birthday-cake" style="margin-right:6px"></i>Age Group Breakdown</div>
            <table class="breakdown-table">
              <tr>
                <td class="label-col">Minors (Below 18)</td>
                <td class="bar-col"><div class="prog-bar"><div class="prog-fill" style="width:{{ $totalResidents > 0 ? round(($minors/$totalResidents)*100) : 0 }}%;background:#3b82f6"></div></div></td>
                <td class="value-col">{{ $minors }}</td>
                <td class="pct-col">{{ $totalResidents > 0 ? round(($minors/$totalResidents)*100,1) : 0 }}%</td>
              </tr>
              <tr>
                <td class="label-col">Adults (18–59)</td>
                <td class="bar-col"><div class="prog-bar"><div class="prog-fill" style="width:{{ $totalResidents > 0 ? round(($adults/$totalResidents)*100) : 0 }}%;background:#10b981"></div></div></td>
                <td class="value-col">{{ $adults }}</td>
                <td class="pct-col">{{ $totalResidents > 0 ? round(($adults/$totalResidents)*100,1) : 0 }}%</td>
              </tr>
              <tr>
                <td class="label-col">Seniors (60+)</td>
                <td class="bar-col"><div class="prog-bar"><div class="prog-fill" style="width:{{ $totalResidents > 0 ? round(($seniors/$totalResidents)*100) : 0 }}%;background:#f59e0b"></div></div></td>
                <td class="value-col">{{ $seniors }}</td>
                <td class="pct-col">{{ $totalResidents > 0 ? round(($seniors/$totalResidents)*100,1) : 0 }}%</td>
              </tr>
            </table>
          </div>

          <!-- Civil Status -->
          <div>
            <div style="font-weight:700;color:var(--primary);margin-bottom:14px;font-size:13px"><i class="fas fa-heart" style="margin-right:6px"></i>Civil Status Breakdown</div>
            <table class="breakdown-table">
              @forelse($civilStatus as $cs)
              <tr>
                <td class="label-col">{{ $cs->civil_status ?? 'Not specified' }}</td>
                <td class="bar-col"><div class="prog-bar"><div class="prog-fill" style="width:{{ $totalResidents > 0 ? round(($cs->total/$totalResidents)*100) : 0 }}%;background:#8b5cf6"></div></div></td>
                <td class="value-col">{{ $cs->total }}</td>
                <td class="pct-col">{{ $totalResidents > 0 ? round(($cs->total/$totalResidents)*100,1) : 0 }}%</td>
              </tr>
              @empty
              <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:16px">No data yet.</td></tr>
              @endforelse
            </table>
          </div>

          <!-- Documents -->
          <div>
            <div style="font-weight:700;color:var(--primary);margin-bottom:14px;font-size:13px"><i class="fas fa-file-alt" style="margin-right:6px"></i>Documents Issued</div>
            <table class="breakdown-table">
              <tr>
                <td class="label-col">Clearances</td>
                <td class="bar-col"></td>
                <td class="value-col">{{ $totalClearances }}</td>
                <td class="pct-col"></td>
              </tr>
              <tr>
                <td class="label-col">Certificates</td>
                <td class="bar-col"></td>
                <td class="value-col">{{ $totalCertificates }}</td>
                <td class="pct-col"></td>
              </tr>
            </table>
          </div>

        </div>
      </div>
    </div>

    <!-- Demographics Tab -->
    <div id="tab-demographics" class="tab-content">
      <div class="card-body">
        <div style="font-weight:700;color:var(--primary);margin-bottom:14px;font-size:13px"><i class="fas fa-map-marker-alt" style="margin-right:6px"></i>Population by Sitio / Area</div>
        <table class="breakdown-table">
          @forelse($bySitio as $s)
          <tr>
            <td class="label-col">{{ $s->barangay ?? 'Not specified' }}</td>
            <td class="bar-col"><div class="prog-bar"><div class="prog-fill" style="width:{{ $totalResidents > 0 ? round(($s->total/$totalResidents)*100) : 0 }}%;background:#1a3a6b"></div></div></td>
            <td class="value-col">{{ $s->total }}</td>
            <td class="pct-col">{{ $totalResidents > 0 ? round(($s->total/$totalResidents)*100,1) : 0 }}%</td>
          </tr>
          @empty
          <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:16px">No data yet.</td></tr>
          @endforelse
        </table>

        <div style="font-weight:700;color:var(--primary);margin:24px 0 14px;font-size:13px"><i class="fas fa-graduation-cap" style="margin-right:6px"></i>Education Level Breakdown</div>
        <table class="breakdown-table">
          @forelse($byEducation as $ed)
          <tr>
            <td class="label-col">{{ $ed->education_level ?? 'Not specified' }}</td>
            <td class="bar-col"><div class="prog-bar"><div class="prog-fill" style="width:{{ $totalResidents > 0 ? round(($ed->total/$totalResidents)*100) : 0 }}%;background:#0284c7"></div></div></td>
            <td class="value-col">{{ $ed->total }}</td>
            <td class="pct-col">{{ $totalResidents > 0 ? round(($ed->total/$totalResidents)*100,1) : 0 }}%</td>
          </tr>
          @empty
          <tr><td colspan="4" style="color:var(--muted);text-align:center;padding:16px">No data yet.</td></tr>
          @endforelse
        </table>
      </div>
    </div>

    <!-- Senior Citizens Tab -->
    <div id="tab-seniors" class="tab-content">
      <div class="card-body" style="padding:0">
        <div style="padding:16px 20px;font-size:13px;color:var(--muted)">Total: <strong style="color:var(--primary)">{{ $seniors }}</strong> senior citizen(s)</div>
        <table class="list-table">
          <thead><tr><th>#</th><th>Full Name</th><th>Age</th><th>Sex</th><th>Address</th><th>Contact</th></tr></thead>
          <tbody>
            @forelse($seniorList as $i => $r)
            <tr>
              <td style="color:var(--muted)">{{ $i+1 }}</td>
              <td><strong>{{ $r->last_name }}, {{ $r->first_name }} {{ $r->middle_name }}</strong></td>
              <td>{{ $r->age }} yrs</td>
              <td>{{ $r->gender }}</td>
              <td>{{ $r->barangay }}, {{ $r->city }}</td>
              <td>{{ $r->contact_number ?? '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--muted)">No senior citizens found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- PWD Tab -->
    <div id="tab-pwd" class="tab-content">
      <div class="card-body" style="padding:0">
        <div style="padding:16px 20px;font-size:13px;color:var(--muted)">Total: <strong style="color:var(--primary)">{{ $pwd }}</strong> person(s) with disability</div>
        <table class="list-table">
          <thead><tr><th>#</th><th>Full Name</th><th>Age</th><th>Sex</th><th>Address</th><th>Contact</th></tr></thead>
          <tbody>
            @forelse($pwdList as $i => $r)
            <tr>
              <td style="color:var(--muted)">{{ $i+1 }}</td>
              <td><strong>{{ $r->last_name }}, {{ $r->first_name }} {{ $r->middle_name }}</strong></td>
              <td>{{ $r->age }} yrs</td>
              <td>{{ $r->gender }}</td>
              <td>{{ $r->barangay }}, {{ $r->city }}</td>
              <td>{{ $r->contact_number ?? '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--muted)">No PWD residents found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Voters Tab -->
    <div id="tab-voters" class="tab-content">
      <div class="card-body" style="padding:0">
        <div style="padding:16px 20px;font-size:13px;color:var(--muted)">Total: <strong style="color:var(--primary)">{{ $voters }}</strong> registered voter(s)</div>
        <table class="list-table">
          <thead><tr><th>#</th><th>Full Name</th><th>Age</th><th>Sex</th><th>Address</th><th>Contact</th></tr></thead>
          <tbody>
            @forelse($voterList as $i => $r)
            <tr>
              <td style="color:var(--muted)">{{ $i+1 }}</td>
              <td><strong>{{ $r->last_name }}, {{ $r->first_name }} {{ $r->middle_name }}</strong></td>
              <td>{{ $r->age }} yrs</td>
              <td>{{ $r->gender }}</td>
              <td>{{ $r->barangay }}, {{ $r->city }}</td>
              <td>{{ $r->contact_number ?? '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--muted)">No registered voters found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Minors Tab -->
    <div id="tab-minors" class="tab-content">
      <div class="card-body" style="padding:0">
        <div style="padding:16px 20px;font-size:13px;color:var(--muted)">Total: <strong style="color:var(--primary)">{{ $minors }}</strong> minor(s) below 18</div>
        <table class="list-table">
          <thead><tr><th>#</th><th>Full Name</th><th>Age</th><th>Sex</th><th>Address</th><th>Contact</th></tr></thead>
          <tbody>
            @forelse($minorList as $i => $r)
            <tr>
              <td style="color:var(--muted)">{{ $i+1 }}</td>
              <td><strong>{{ $r->last_name }}, {{ $r->first_name }} {{ $r->middle_name }}</strong></td>
              <td>{{ $r->age }} yrs</td>
              <td>{{ $r->gender }}</td>
              <td>{{ $r->barangay }}, {{ $r->city }}</td>
              <td>{{ $r->contact_number ?? '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center;padding:24px;color:var(--muted)">No minors found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

<script>
function switchTab(name) {
  document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
  document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
  document.getElementById('tab-' + name).classList.add('active');
  event.target.classList.add('active');
}
</script>

@endsection