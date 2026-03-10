@extends('layouts.app')

@section('page-title', 'Certificate Forms')

@section('content')
<style>

.bidb-wrap { background:var(--bg); min-height:100vh; padding:28px; }
.page-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.page-hdr h1 { font-size:22px; font-weight:700; color:var(--primary); margin:0; }
.breadcrumb { font-size:13px; color:var(--muted); margin-top:2px; }
.breadcrumb span { color:var(--primary); font-weight:500; }
.two-col { display:grid; grid-template-columns:360px 1fr; gap:20px; align-items:start; }
.card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden; }
.card-header { padding:16px 20px; border-bottom:1px solid var(--border); background:#f8fafc; }
.card-title { font-weight:700; color:var(--primary); font-size:14px; display:flex; align-items:center; gap:8px; }
.card-body { padding:24px; }
.form-group { display:flex; flex-direction:column; gap:5px; margin-bottom:14px; }
label { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; }
label .req { color:#dc2626; margin-left:2px; }
input, select, textarea { padding:9px 12px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; font-family:inherit; color:var(--text); outline:none; background:#fff; width:100%; box-sizing:border-box; }
input:focus, select:focus, textarea:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(26,58,107,.08); }
.btn { display:inline-flex; align-items:center; gap:6px; padding:9px 16px; border-radius:8px; border:none; cursor:pointer; font-family:inherit; font-size:13px; font-weight:600; transition:all .15s; text-decoration:none; }
.btn-primary { background:var(--primary); color:#fff; width:100%; justify-content:center; }
.btn-primary:hover { background:var(--primary-light); }
.btn-print  { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; padding:5px 10px; font-size:12px; }
.btn-edit   { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; padding:5px 10px; font-size:12px; }
.btn-delete { background:#fff1f2; color:#be123c; border:1px solid #fecdd3; padding:5px 10px; font-size:12px; }
.btn-print:hover  { background:#dbeafe; }
.btn-edit:hover   { background:#dcfce7; }
.btn-delete:hover { background:#ffe4e6; }
.action-btns { display:flex; gap:5px; }
.table-wrap { overflow-x:auto; }
table { width:100%; border-collapse:collapse; font-size:13px; }
thead tr { background:#f8fafc; border-bottom:2px solid var(--border); }
th { padding:12px 16px; text-align:left; font-weight:700; color:var(--muted); font-size:11px; text-transform:uppercase; letter-spacing:.06em; white-space:nowrap; }
td { padding:12px 16px; border-bottom:1px solid var(--border); color:var(--text); vertical-align:middle; }
tbody tr:hover { background:#f8fafc; }
tbody tr:last-child td { border-bottom:none; }
.alert-success { background:#dcfce7; border:1px solid #bbf7d0; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px; display:flex; align-items:center; gap:8px; }
.stat-row { display:grid; grid-template-columns:repeat(2,1fr); gap:14px; margin-bottom:20px; }
.stat-card { background:var(--card); border-radius:12px; padding:16px 18px; border:1px solid var(--border); box-shadow:0 1px 4px rgba(0,0,0,.05); }
.stat-card .slabel { font-size:11px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:4px; }
.stat-card .svalue { font-size:24px; font-weight:800; color:var(--primary); }
.badge { display:inline-flex; align-items:center; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:600; background:#dbeafe; color:#1e40af; }

/* Resident picker */
.res-picker-btn { display:flex; align-items:center; justify-content:space-between; padding:9px 12px; border:1.5px solid var(--border); border-radius:8px; background:var(--card); color:var(--text); cursor:pointer; font-size:14px; font-family:inherit; width:100%; text-align:left; transition:border-color .15s; }
.res-picker-btn:hover { border-color:var(--primary); }
.res-picker-btn.selected { border-color:var(--primary); color:var(--primary); font-weight:600; }
.res-picker-btn .placeholder { color:var(--muted); font-weight:400; }
.rpicker-backdrop { display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:300; align-items:center; justify-content:center; }
.rpicker-backdrop.open { display:flex; }
.rpicker-modal { background:var(--card); border-radius:14px; width:500px; max-width:95vw; max-height:80vh; display:flex; flex-direction:column; box-shadow:0 20px 60px rgba(0,0,0,.25); overflow:hidden; }
.rpicker-header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; flex-shrink:0; }
.rpicker-header h3 { font-size:15px; font-weight:700; color:var(--primary); margin:0; }
.rpicker-close { background:none; border:none; font-size:20px; color:var(--muted); cursor:pointer; }
.rpicker-search { padding:12px 16px; border-bottom:1px solid var(--border); flex-shrink:0; }
.rpicker-search input { width:100%; padding:8px 12px; border:1.5px solid var(--border); border-radius:8px; font-size:13px; font-family:inherit; color:var(--text); background:var(--bg); outline:none; box-sizing:border-box; }
.rpicker-search input:focus { border-color:var(--primary); }
.rpicker-list { overflow-y:auto; flex:1; }
.rpicker-item { padding:10px 16px; cursor:pointer; border-bottom:1px solid var(--border); transition:background .1s; }
.rpicker-item:last-child { border-bottom:none; }
.rpicker-item:hover { background:var(--hover-bg); }
.rpicker-item .ri-name { font-size:13px; font-weight:600; color:var(--text); }
.rpicker-item .ri-meta { font-size:11px; color:var(--muted); margin-top:2px; }
.rpicker-empty { padding:24px; text-align:center; color:var(--muted); font-size:13px; }
</style>

<div class="bidb-wrap">
<div class="page-hdr">
<div>
<h1><i class="fas fa-certificate" style="margin-right:8px"></i>Certificate Management</h1>
<div class="breadcrumb">Home › <span>Certificates</span></div>
</div>
</div>

@if(session('success'))
<div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

<div class="stat-row">
<div class="stat-card">
<div class="slabel">Total Certificates Issued</div>
<div class="svalue">{{ $certificates->count() }}</div>
</div>
<div class="stat-card">
<div class="slabel">Issued This Month</div>
<div class="svalue">{{ $certificates->filter(fn($c) => \Carbon\Carbon::parse($c->issued_date)->isCurrentMonth())->count() }}</div>
</div>
</div>

<div class="two-col">

<div class="card">
<div class="card-header"><div class="card-title"><i class="fas fa-plus-circle"></i> Issue New Certificate</div></div>
<div class="card-body">
<form method="POST" action="{{ route('certificate.store') }}">
@csrf

<div class="form-group">
<label>Resident Name <span class="req">*</span></label>
<input type="hidden" name="resident_name" id="ct-resident-name" required>
<button type="button" class="res-picker-btn" id="ct-picker-btn" onclick="openResPicker()">
  <span id="ct-picker-label" class="placeholder">Click to select a resident...</span>
  <i class="fas fa-search" style="color:var(--muted);font-size:12px"></i>
</button>
</div>

<div class="form-group">
<label>Certificate Type <span class="req">*</span></label>
<select name="certificate_type" required>
<option value="">Select type...</option>
<option value="Good Moral Character Clearance">Certificate of Residency</option>
<option value="Residency Certificate">Certificate of Indigency</option>
<option value="Indigency Certificate">Certificate of Unemployment</option>
<option value="Business Operation">Certificate of Residency for Voters</option>
<option value="Business Operation">Certificate of Guardianship</option>

</select>
</div>

<div class="form-group">
<label>Purpose <span class="req">*</span></label>
<textarea name="purpose" placeholder="e.g. For employment purposes" required></textarea>
</div>

<button type="submit" class="btn btn-primary">
<i class="fas fa-certificate"></i> Issue Certificate
</button>

</form>
</div>
</div>

<div class="card">
<div class="card-header"><div class="card-title"><i class="fas fa-list"></i> Issued Certificates</div></div>

<div class="table-wrap">
<table>
<thead>
<tr>
<th>Certificate No.</th>
<th>Resident Name</th>
<th>Certificate Type</th>
<th>Date Issued</th>
<th>Actions</th>
</tr>
</thead>

<tbody>
@forelse($certificates as $cert)
<tr>

<td>
<span style="font-weight:700;color:var(--primary)">
{{ $cert->certificate_no }}
</span>
</td>

<td>{{ $cert->resident_name }}</td>

<td>
<span class="badge">{{ $cert->certificate_type }}</span>
</td>

<td>
{{ \Carbon\Carbon::parse($cert->issued_date)->format('M d, Y') }}
</td>

<td>
<div class="action-btns">

<a href="{{ route('certificate.print', $cert->id) }}" target="_blank" class="btn btn-print">
<i class="fas fa-print"></i> Print
</a>

<a href="{{ route('certificate.edit', $cert->id) }}" class="btn btn-edit">
<i class="fas fa-edit"></i> Edit
</a>

@if(auth()->user()->role == 'admin')
<form action="{{ route('certificate.destroy', $cert->id) }}" method="POST" style="display:inline">
@csrf
@method('DELETE')
<button type="submit" class="btn btn-delete">
<i class="fas fa-trash"></i> Delete</button>
</form>
@endif

</div>
</td>

</tr>

@empty
<tr>
<td colspan="5" style="text-align:center;padding:32px;color:var(--muted)">
<i class="fas fa-certificate" style="font-size:32px;opacity:.3;display:block;margin-bottom:8px"></i>
No certificates issued yet.
</td>
</tr>
@endforelse

</tbody>
</table>
</div>
</div>

</div>
</div>

<!-- Resident Picker Modal -->
<div id="resPickerModal" class="rpicker-backdrop">
  <div class="rpicker-modal">
    <div class="rpicker-header">
      <h3><i class="fas fa-users" style="margin-right:8px"></i>Select Resident</h3>
      <button class="rpicker-close" onclick="closeResPicker()">×</button>
    </div>
    <div class="rpicker-search">
      <input type="text" id="resPickerSearch" placeholder="Search by name..." oninput="filterResidents(this.value)">
    </div>
    <div class="rpicker-list" id="resPickerList"></div>
  </div>
</div>

<script>
const allResidents = @json($residents);

function openResPicker() {
  document.getElementById('resPickerModal').classList.add('open');
  document.getElementById('resPickerSearch').value = '';
  filterResidents('');
  setTimeout(() => document.getElementById('resPickerSearch').focus(), 50);
}
function closeResPicker() {
  document.getElementById('resPickerModal').classList.remove('open');
}
function filterResidents(q) {
  const list = document.getElementById('resPickerList');
  const term = q.toLowerCase();
  const filtered = allResidents.filter(r => {
    const full = (r.last_name + ' ' + r.first_name + ' ' + (r.middle_name||'')).toLowerCase();
    return !term || full.includes(term);
  });
  if (!filtered.length) {
    list.innerHTML = '<div class="rpicker-empty"><i class="fas fa-user-slash" style="font-size:24px;opacity:.3;display:block;margin-bottom:8px"></i>No residents found.</div>';
    return;
  }
  list.innerHTML = filtered.map(r => {
    const name = r.last_name + ', ' + r.first_name + (r.middle_name ? ' ' + r.middle_name : '');
    const meta = [r.address, r.barangay].filter(Boolean).join(', ') || 'Barangay Cogon';
    return `<div class="rpicker-item" onclick="selectResident('${name.replace(/'/g,"\\'")}')">
      <div class="ri-name">${name}</div>
      <div class="ri-meta">${meta}</div>
    </div>`;
  }).join('');
}
function selectResident(name) {
  document.getElementById('ct-resident-name').value = name;
  document.getElementById('ct-picker-label').textContent = name;
  document.getElementById('ct-picker-btn').classList.add('selected');
  closeResPicker();
}
document.getElementById('resPickerModal').addEventListener('click', function(e) {
  if (e.target === this) closeResPicker();
});
</script>

@endsection