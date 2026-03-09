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
<input type="text" name="resident_name" placeholder="e.g. Juan Dela Cruz" required>
</div>

<div class="form-group">
<label>Certificate Type <span class="req">*</span></label>
<select name="certificate_type" required>
<option value="">Select type...</option>
<option value="Good Moral Character Clearance">Good Moral Character</option>
<option value="Residency Certificate">Residency Certificate</option>
<option value="Indigency Certificate">Indigency Certificate</option>
<option value="Business Operation">Business Operation</option>
</select>
</div>

<div class="form-group">
<label>Purpose <span class="req">*</span></label>
<textarea name="purpose" rows="3" placeholder="e.g. For employment purposes" required></textarea>
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
<th>Type</th>
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

@if(auth()->user()->role == 'admin')

<a href="{{ route('certificate.edit', $cert->id) }}" class="btn btn-edit">
<i class="fas fa-edit"></i> Edit
</a>

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

@endsection