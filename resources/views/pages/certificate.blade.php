@extends('layouts.app')

@section('page-title', 'Barangay Certificate Forms')

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
/* Document editor body */
#ct-doc-body p { margin:0 0 14px; text-align:justify; font-family:"Times New Roman",serif; font-size:15px; line-height:1.9; }
#ct-doc-body p:last-child { margin-bottom:0; }
.doc-toolbar { display:flex; align-items:center; gap:2px; padding:5px 8px; background:#f8fafc; border:1px solid #dde1e7; border-radius:6px 6px 0 0; flex-wrap:wrap; }
.doc-toolbar button { width:26px; height:24px; background:none; border:1px solid transparent; border-radius:4px; cursor:pointer; font-size:13px; color:#374151; display:flex; align-items:center; justify-content:center; }
.doc-toolbar button:hover { background:#e2e8f0; border-color:#cbd5e1; }
.doc-toolbar .sep { width:1px; height:16px; background:#dde1e7; margin:0 3px; }
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
<div class="svalue">{{ $totalCertificates }}</div>
</div>
<div class="stat-card">
<div class="slabel">Issued This Month</div>
<div class="svalue">{{ $monthCertificates }}</div>
</div>
</div>

{{-- SPLIT SCREEN: Form + Live Preview --}}
<div style="display:grid;grid-template-columns:420px 1fr;gap:20px;margin-bottom:20px;align-items:start">

{{-- LEFT: Form --}}
<div class="card" style="position:sticky;top:20px">
<div class="card-header"><div class="card-title"><i class="fas fa-plus-circle"></i> Issue New Certificate</div></div>
<div class="card-body" style="padding:18px">
<form method="POST" action="{{ route('certificate.store') }}" id="ct-form">
@csrf
<input type="hidden" name="body_content" id="ct-body-hidden">

<div class="form-group">
<label>Document Type <span class="req">*</span></label>
<select name="certificate_type" id="ct-type-select" required onchange="ctUpdate()">
<option value="">Select type...</option>
<option value="Barangay Certification">Barangay Certification (Custom)</option>
<option value="Good Moral Character Clearance">Good Moral Character Clearance</option>
<option value="Certificate of Residency">Certificate of Residency</option>
<option value="Certificate of Indigency">Certificate of Indigency</option>
<option value="Certificate of Unemployment">Certificate of Unemployment</option>
<option value="Certificate of Residency for Voters">Certificate of Residency for Voters</option>
<option value="Certificate of Guardianship">Certificate of Guardianship</option>
<option value="Barangay Business Clearance">Barangay Business Clearance</option>
</select>
</div>

<div class="form-group">
<label>Resident Name <span class="req">*</span></label>
<input type="hidden" name="resident_name" id="ct-resident-name" required>
<button type="button" class="res-picker-btn" id="ct-picker-btn" onclick="openResPicker()">
  <span id="ct-picker-label" class="placeholder">Click to select a resident...</span>
  <i class="fas fa-search" style="color:var(--muted);font-size:12px"></i>
</button>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
<div class="form-group">
<label>Civil Status</label>
<select name="civil_status" id="ct-civil-status" onchange="ctUpdate()">
<option value="Single">Single</option>
<option value="Married">Married</option>
<option value="Widowed">Widowed</option>
<option value="Separated">Separated</option>
<option value="Annulled">Annulled</option>
<option value="Common Law (Live-in)">Common Law (Live-in)</option>
<option value="Divorced">Divorced</option>
</select>
</div>
<div class="form-group">
<label>Purok / Address</label>
<input type="text" name="purok" id="ct-purok" placeholder="e.g. Sampaguita" oninput="ctUpdate()">
</div>
</div>

<div class="form-group">
<label>Requestor</label>
<select name="requestor" id="ct-requestor" onchange="ctUpdate()">
<option value="The Subject (Self)">The Subject (Self)</option>
<option value="Parent/Guardian">Parent/Guardian</option>
<option value="Representative">Representative</option>
</select>
</div>

<div style="border-top:1px solid var(--border);padding-top:12px;margin-top:4px">
<div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px">Receipt Footer</div>
<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
<div class="form-group" style="margin-bottom:8px">
<label>O.R. Number</label>
<input type="text" name="or_number" id="ct-or-number" placeholder="e.g. 8954164" oninput="ctUpdate()">
</div>
<div class="form-group" style="margin-bottom:8px">
<label>Amount (₱)</label>
<input type="number" name="amount" id="ct-amount" step="0.01" min="0" placeholder="0.00" oninput="ctUpdate()">
</div>
</div>
</div>

<div style="display:flex;gap:8px;margin-top:8px">
<button type="submit" class="btn btn-primary" style="flex:1">
<i class="fas fa-save"></i> Save Record
</button>
</div>

</form>
</div>
</div>

{{-- RIGHT: Document Editor --}}
<div class="card" style="overflow:hidden">
<div class="card-header" style="display:flex;align-items:center;justify-content:space-between">
  <div class="card-title"><i class="fas fa-file-alt"></i> Document Editor</div>
  <span style="font-size:11px;color:var(--muted)"><i class="fas fa-pencil-alt" style="margin-right:4px"></i>Click on the body text to edit directly</span>
</div>
<div style="background:#e5e7eb;padding:24px;min-height:640px;overflow-y:auto;display:flex;justify-content:center;align-items:flex-start">
  <div style="background:#fff;color:#000;width:100%;max-width:680px;min-height:900px;padding:50px 60px;box-shadow:0 4px 20px rgba(0,0,0,.15);font-family:'Times New Roman',serif;font-size:15px;line-height:1.9;display:flex;flex-direction:column">

    {{-- Header --}}
    <div style="text-align:center;margin-bottom:24px">
      <img src="{{ asset('images/cogon.png') }}" style="width:80px;height:80px;object-fit:contain;display:block;margin:0 auto 8px" onerror="this.style.display='none'">
      <div style="font-size:13px;font-style:italic">Republic of the Philippines</div>
      <div style="font-size:14px;font-weight:bold;text-transform:uppercase">Office of the Punong Barangay</div>
      <div style="font-size:13px">Barangay Cogon, Ormoc City</div>
    </div>

    {{-- Title --}}
    <div id="ct-doc-title" style="text-align:center;font-size:18px;font-weight:bold;text-transform:uppercase;margin:20px 0 24px;letter-spacing:1px">
      BARANGAY CERTIFICATION
    </div>

    {{-- Editable Body --}}
    <div style="margin-bottom:14px;flex:1">
      <div class="doc-toolbar">
        <button type="button" onclick="ctFmt('bold')" title="Bold (Ctrl+B)"><b style="font-family:Georgia,serif">B</b></button>
        <button type="button" onclick="ctFmt('italic')" title="Italic (Ctrl+I)"><i style="font-family:Georgia,serif">I</i></button>
        <button type="button" onclick="ctFmt('underline')" title="Underline (Ctrl+U)"><u style="font-family:Georgia,serif">U</u></button>
        <div class="sep"></div>
        <button type="button" onclick="ctFmt('justifyFull')" title="Justify text"><i class="fas fa-align-justify" style="font-size:10px"></i></button>
        <button type="button" onclick="ctFmt('justifyLeft')" title="Align left"><i class="fas fa-align-left" style="font-size:10px"></i></button>
        <button type="button" onclick="ctFmt('justifyCenter')" title="Align center"><i class="fas fa-align-center" style="font-size:10px"></i></button>
        <div style="flex:1"></div>
        <button type="button" onclick="ctUpdate()" title="Regenerate body from current form values"
          style="width:auto;padding:0 8px;font-size:11px;font-family:inherit;color:#64748b;gap:4px">
          <i class="fas fa-redo-alt" style="font-size:9px"></i> Reset to template
        </button>
      </div>
      <div id="ct-doc-body" contenteditable="true"
        style="outline:none;min-height:180px;border:1px solid #dde1e7;border-top:none;border-radius:0 0 6px 6px;padding:10px 8px;cursor:text;transition:background .15s"
        onfocus="this.style.background='rgba(239,246,255,0.6)'"
        onblur="this.style.background=''"
        oninput="ctSyncBody()">
        <p style="color:#aaa;text-align:center">Select a document type to generate the body.</p>
      </div>
    </div>

    {{-- Issued date --}}
    <p id="ct-issued-line" style="margin-top:14px"></p>

    {{-- Signature --}}
    <div style="margin-top:50px;text-align:right">
      <strong style="display:block;font-size:15px">ATTY. MA. CASSANDRA T. CODILLA, RCE</strong>
      <span style="font-size:13px;font-style:italic">Punong Barangay</span>
    </div>

    {{-- Receipt --}}
    <div id="ct-receipt" style="display:none;margin-top:30px;font-size:13px;color:#555"></div>

  </div>
</div>
</div>

</div>
{{-- END SPLIT SCREEN --}}

<div class="card">
<div class="card-header" style="display:flex;align-items:center;justify-content:space-between">
  <div class="card-title"><i class="fas fa-list"></i> Issued Certificates</div>
  @if(auth()->user()->role === 'admin')
  <button type="button" id="certBulkBtn" onclick="submitCertBulk()"
    style="display:none;background:#fff1f2;color:#be123c;border:1px solid #fecdd3;
           padding:6px 12px;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;align-items:center;gap:5px">
    <i class="fas fa-trash"></i> Delete Selected (<span id="certCount">0</span>)
  </button>
  @endif
</div>

@if(auth()->user()->role === 'admin')
<div id="certSelectAllBanner" style="display:none;padding:8px 16px;background:#eff6ff;border-bottom:1px solid #bfdbfe;font-size:13px;color:#1e40af;text-align:center">
  All <strong>{{ $certificates->perPage() }}</strong> certificates on this page are selected.
  <a href="#" onclick="certSelectAll(); return false;" style="font-weight:700;color:#1d4ed8;text-decoration:underline">Select all <strong>{{ $certificates->total() }}</strong> certificates</a>
  &nbsp;&middot;&nbsp;<a href="#" onclick="certClearSelect(); return false;" style="color:#64748b;text-decoration:underline">Clear</a>
</div>
@endif
<div class="table-wrap">
<table>
<thead>
<tr>
@if(auth()->user()->role === 'admin')
<th style="width:36px"><input type="checkbox" id="certSelectAll" onchange="certToggleAll(this)" style="width:15px;height:15px;cursor:pointer"></th>
@endif
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

@if(auth()->user()->role === 'admin')
<td onclick="event.stopPropagation()"><input type="checkbox" class="cert-check" value="{{ $cert->id }}" style="width:15px;height:15px;cursor:pointer"></td>
@endif
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

@if(auth()->user()->role === 'admin')
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

@if($certificates->hasPages())
<div style="padding:12px 16px;display:flex;align-items:center;justify-content:space-between;border-top:1px solid var(--border);font-size:13px;color:var(--muted)">
  <span>Showing {{ $certificates->firstItem() }}–{{ $certificates->lastItem() }} of {{ $certificates->total() }} certificates</span>
  {{ $certificates->links() }}
</div>
@endif

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
  ctUpdate();
}
document.getElementById('resPickerModal').addEventListener('click', function(e) {
  if (e.target === this) closeResPicker();
});

// ── Live Preview ──────────────────────────────────────────────────────────
const ctBodies = {
  'Barangay Certification':
    `<p>TO WHOM IT MAY CONCERN:</p>
     <p style="text-indent:2em">THIS IS TO CERTIFY that <strong>[NAME]</strong>, of legal age, [CIVIL_STATUS], Filipino Citizen, is a bona fide resident of Barangay Cogon, Ormoc City.</p>
     <p style="text-indent:2em">[Type custom content here...]</p>
     <p style="text-indent:2em">This certification is issued upon the request of <strong>[REQUESTOR]</strong>.</p>`,
  'Good Moral Character Clearance':
    `<p>TO WHOM IT MAY CONCERN:</p>
     <p style="text-indent:2em">THIS IS TO CERTIFY that <strong>[NAME]</strong>, of legal age, [CIVIL_STATUS], Filipino Citizen, is a bona fide resident of Barangay Cogon, Ormoc City.</p>
     <p style="text-indent:2em">CERTIFYING FURTHER, that based on the records of this office, the above-named person is known to be of <strong>good moral character</strong>, law-abiding, and has no derogatory record on file as of this date.</p>
     <p style="text-indent:2em">This certification is issued upon the request of <strong>[REQUESTOR]</strong>.</p>`,
  'Certificate of Residency':
    `<p>TO WHOM IT MAY CONCERN:</p>
     <p style="text-indent:2em">THIS IS TO CERTIFY that <strong>[NAME]</strong>, of legal age, [CIVIL_STATUS], Filipino Citizen, is a bona fide permanent resident of Purok [PUROK], Barangay Cogon, Ormoc City.</p>
     <p style="text-indent:2em">This certification is issued upon the request of <strong>[REQUESTOR]</strong>.</p>`,
  'Certificate of Indigency':
    `<p>TO WHOM IT MAY CONCERN:</p>
     <p style="text-indent:2em">THIS IS TO CERTIFY that <strong>[NAME]</strong>, of legal age, [CIVIL_STATUS], Filipino Citizen, is a permanent resident of PUROK [PUROK], Barangay COGON, ORMOC.</p>
     <p style="text-indent:2em">CERTIFYING FURTHER, that based on the records of this office, the above-named person belongs to an <strong>INDIGENT FAMILY</strong> in this Barangay. The family's annual income is barely enough to sustain their daily needs.</p>
     <p style="text-indent:2em">This certification is issued upon the request of <strong>[REQUESTOR]</strong>.</p>`,
  'Certificate of Unemployment':
    `<p>TO WHOM IT MAY CONCERN:</p>
     <p style="text-indent:2em">THIS IS TO CERTIFY that <strong>[NAME]</strong>, of legal age, [CIVIL_STATUS], Filipino Citizen, is a bona fide resident of Purok [PUROK], Barangay Cogon, Ormoc City, and is currently <strong>UNEMPLOYED</strong> as of this date.</p>
     <p style="text-indent:2em">This certification is issued upon the request of <strong>[REQUESTOR]</strong>.</p>`,
  'Certificate of Residency for Voters':
    `<p>TO WHOM IT MAY CONCERN:</p>
     <p style="text-indent:2em">THIS IS TO CERTIFY that <strong>[NAME]</strong>, of legal age, [CIVIL_STATUS], Filipino Citizen, is a bona fide resident of Purok [PUROK], Barangay Cogon, Ormoc City, and is qualified to <strong>register as a voter</strong> within this jurisdiction in accordance with the requirements of the Commission on Elections (COMELEC).</p>
     <p style="text-indent:2em">This certification is issued upon the request of <strong>[REQUESTOR]</strong>.</p>`,
  'Certificate of Guardianship':
    `<p>TO WHOM IT MAY CONCERN:</p>
     <p style="text-indent:2em">THIS IS TO CERTIFY that <strong>[NAME]</strong>, of legal age, [CIVIL_STATUS], Filipino Citizen, is a bona fide resident of Purok [PUROK], Barangay Cogon, Ormoc City, and is recognized as the <strong>legal guardian</strong> of the minor/dependent under their care.</p>
     <p style="text-indent:2em">This certification is issued upon the request of <strong>[REQUESTOR]</strong>.</p>`,
  'Barangay Business Clearance':
    `<p>TO WHOM IT MAY CONCERN:</p>
     <p style="text-indent:2em">THIS IS TO CERTIFY that <strong>[NAME]</strong>, is an existing business establishment within the territorial jurisdiction of Barangay Cogon, Ormoc City.</p>
     <p style="text-indent:2em">This certification is issued to establish and confirm the exact location of the said business within Barangay Cogon in compliance with the requirements of the Bureau of Internal Revenue (BIR).</p>
     <p style="text-indent:2em">This certification is issued upon the request of <strong>[REQUESTOR]</strong>.</p>`,
};

function ctGetVal(id) { return (document.getElementById(id)||{}).value || ''; }

function ctSyncBody() {
  document.getElementById('ct-body-hidden').value = document.getElementById('ct-doc-body').innerHTML;
}

function ctFmt(cmd) {
  document.getElementById('ct-doc-body').focus();
  document.execCommand(cmd, false, null);
  ctSyncBody();
}

function ctUpdate() {
  const type      = ctGetVal('ct-type-select');
  const name      = ctGetVal('ct-resident-name') || '___________________';
  const civil     = ctGetVal('ct-civil-status')  || 'Single';
  const purok     = ctGetVal('ct-purok')          || '___________';
  const requestor = ctGetVal('ct-requestor')      || 'the above-named person';
  const orNum     = ctGetVal('ct-or-number');
  const amount    = ctGetVal('ct-amount');
  const today     = new Date();
  const day       = today.getDate();
  const suffix    = ['th','st','nd','rd'][day%10<4&&~~(day%100/10)!==1?day%10:0]||'th';
  const months    = ['January','February','March','April','May','June','July','August','September','October','November','December'];
  const dateStr   = `${day}${suffix} day of ${months[today.getMonth()]}, ${today.getFullYear()}`;

  // Update document title
  document.getElementById('ct-doc-title').textContent = type || 'BARANGAY CERTIFICATION';

  // Update editable body from template
  const bodyTpl  = ctBodies[type] || '';
  const bodyHtml = bodyTpl
    .replace(/\[NAME\]/g, name).replace(/\[CIVIL_STATUS\]/g, civil)
    .replace(/\[PUROK\]/g, purok).replace(/\[REQUESTOR\]/g, requestor);
  document.getElementById('ct-doc-body').innerHTML = bodyHtml
    || '<p style="color:#aaa;text-align:center;font-family:sans-serif;font-size:13px">Select a document type to generate the body.</p>';
  ctSyncBody();

  // Update issued line
  document.getElementById('ct-issued-line').innerHTML =
    `Issued this ${day}<sup>${suffix}</sup> day of ${months[today.getMonth()]} ${today.getFullYear()} at Barangay Cogon, Ormoc City, Philippines.`;

  // Update receipt
  const receipt = document.getElementById('ct-receipt');
  if (orNum || amount) {
    receipt.style.display = 'block';
    receipt.innerHTML = (orNum ? `O.R. No.: <strong>${orNum}</strong>&nbsp;&nbsp;` : '')
      + (amount ? `Amount Paid: <strong>₱${parseFloat(amount).toFixed(2)}</strong>` : '');
  } else {
    receipt.style.display = 'none';
  }
}

// Init on load
document.addEventListener('DOMContentLoaded', ctUpdate);
</script>

<form id="certBulkForm" method="POST" action="{{ route('certificate.bulkDestroy') }}" style="display:none">
  @csrf
  @method('DELETE')
</form>
<script>
let certSelectAllMode = false;
function certToggleAll(src) {
  document.querySelectorAll('.cert-check').forEach(cb => cb.checked = src.checked);
  certSelectAllMode = false;
  certUpdateBtn();
  document.getElementById('certSelectAllBanner').style.display = src.checked ? 'block' : 'none';
}
document.addEventListener('change', function(e) {
  if (e.target.classList.contains('cert-check')) { certSelectAllMode = false; certUpdateBtn(); }
});
function certUpdateBtn() {
  const checked = document.querySelectorAll('.cert-check:checked');
  const btn = document.getElementById('certBulkBtn');
  if (!btn) return;
  document.getElementById('certCount').textContent = certSelectAllMode ? '{{ $certificates->total() }}' : checked.length;
  btn.style.display = (checked.length > 0 || certSelectAllMode) ? 'inline-flex' : 'none';
}
function certSelectAll() {
  certSelectAllMode = true;
  document.getElementById('certSelectAllBanner').innerHTML =
    'All <strong>{{ $certificates->total() }}</strong> certificates are selected. ' +
    '<a href="#" onclick="certClearSelect(); return false;" style="color:#be123c;font-weight:700;text-decoration:underline">Clear selection</a>';
  certUpdateBtn();
}
function certClearSelect() {
  certSelectAllMode = false;
  document.getElementById('certSelectAll').checked = false;
  document.querySelectorAll('.cert-check').forEach(cb => cb.checked = false);
  certUpdateBtn();
  document.getElementById('certSelectAllBanner').style.display = 'none';
}
function submitCertBulk() {
  const form = document.getElementById('certBulkForm');
  form.querySelectorAll('input[name="ids[]"], input[name="select_all"]').forEach(el => el.remove());
  if (certSelectAllMode) {
    const inp = document.createElement('input');
    inp.type = 'hidden'; inp.name = 'select_all'; inp.value = '1';
    form.appendChild(inp);
    confirmDelete(form, 'Delete ALL {{ $certificates->total() }} certificates? This cannot be undone.');
  } else {
    const checked = document.querySelectorAll('.cert-check:checked');
    if (!checked.length) return;
    checked.forEach(cb => {
      const inp = document.createElement('input');
      inp.type = 'hidden'; inp.name = 'ids[]'; inp.value = cb.value;
      form.appendChild(inp);
    });
    confirmDelete(form, 'Delete ' + checked.length + ' certificate(s)? This cannot be undone.');
  }
}
</script>
@endsection