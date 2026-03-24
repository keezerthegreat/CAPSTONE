@extends('layouts.app')

@section('page-title', 'Edit Barangay Clearance')

@section('content')
<style>
.bidb-wrap { background:var(--bg); min-height:100vh; padding:28px; }
.page-hdr { margin-bottom:24px; }
.page-hdr h1 { font-size:22px; font-weight:700; color:var(--primary); margin:0; }
.breadcrumb { font-size:13px; color:var(--muted); margin-top:2px; }
.breadcrumb a { color:var(--primary); font-weight:500; text-decoration:none; }
.breadcrumb a:hover { text-decoration:underline; }
.card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); overflow:hidden; }
.card-header { padding:16px 20px; border-bottom:1px solid var(--border); background:#f8fafc; }
.card-title { font-weight:700; color:var(--primary); font-size:14px; display:flex; align-items:center; gap:8px; }
.card-body { padding:18px; }
.form-group { display:flex; flex-direction:column; gap:5px; margin-bottom:14px; }
label { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; }
label .req { color:#dc2626; margin-left:2px; }
input, select, textarea { padding:9px 12px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; font-family:inherit; color:var(--text); outline:none; background:#fff; width:100%; box-sizing:border-box; }
input:focus, select:focus, textarea:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(26,58,107,.08); }
.btn { display:inline-flex; align-items:center; gap:6px; padding:9px 16px; border-radius:8px; border:none; cursor:pointer; font-family:inherit; font-size:13px; font-weight:600; transition:all .15s; text-decoration:none; }
.btn-primary { background:var(--primary); color:#fff; flex:1; justify-content:center; }
.btn-primary:hover { background:var(--primary-light); }
.btn-secondary { background:#f1f5f9; color:var(--muted); border:1.5px solid var(--border); }
.btn-secondary:hover { background:#e2e8f0; color:var(--text); }
.info-box { background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:12px 16px; margin-bottom:20px; font-size:13px; color:#1d4ed8; display:flex; align-items:center; gap:8px; }
[data-theme="dark"] .info-box { background:#1e2d4a; border-color:#2a4a7f; color:#7ba5f5; }
#cl-doc-body p { margin:0 0 14px; text-align:justify; font-family:"Times New Roman",serif; font-size:15px; line-height:1.9; }
#cl-doc-body p:last-child { margin-bottom:0; }
.doc-toolbar { display:flex; align-items:center; gap:2px; padding:5px 8px; background:#f8fafc; border:1px solid #dde1e7; border-radius:6px 6px 0 0; flex-wrap:wrap; }
.doc-toolbar button { width:26px; height:24px; background:none; border:1px solid transparent; border-radius:4px; cursor:pointer; font-size:13px; color:#374151; display:flex; align-items:center; justify-content:center; }
.doc-toolbar button:hover { background:#e2e8f0; border-color:#cbd5e1; }
.doc-toolbar .sep { width:1px; height:16px; background:#dde1e7; margin:0 3px; }
</style>

<div class="bidb-wrap">

  <div class="page-hdr">
    <h1><i class="fas fa-edit" style="margin-right:8px"></i>Edit Barangay Clearance</h1>
    <div class="breadcrumb">
      <a href="{{ route('dashboard') }}">Home</a> ›
      <a href="{{ route('clearance.index') }}">Clearance Forms</a> ›
      <span style="color:var(--muted)">Edit</span>
    </div>
  </div>

  <div class="info-box">
    <i class="fas fa-info-circle"></i>
    Editing clearance <strong>{{ $clearance->clearance_no }}</strong> — issued on {{ \Carbon\Carbon::parse($clearance->date_issued)->format('F d, Y') }}
  </div>

  {{-- SPLIT SCREEN: Form + Document Editor --}}
  <div style="display:grid;grid-template-columns:420px 1fr;gap:20px;align-items:start">

  {{-- LEFT: Form --}}
  <div class="card" style="position:sticky;top:20px">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-edit"></i> Edit Clearance — <span style="font-weight:400;color:var(--muted)">{{ $clearance->clearance_no }}</span></div>
    </div>
    <div class="card-body">
      <form action="{{ route('clearance.update', $clearance->id) }}" method="POST" id="cl-form">
        @csrf
        @method('PUT')
        <input type="hidden" name="body_content" id="cl-body-hidden">

        <div class="form-group">
          <label>Document Type <span class="req">*</span></label>
          <select name="certificate_type" id="cl-type-select" required onchange="clOnTypeChange()">
            <option value="Barangay Clearance"              {{ $clearance->certificate_type == 'Barangay Clearance'              ? 'selected' : '' }}>Barangay Clearance</option>
            <option value="Residency Clearance"             {{ $clearance->certificate_type == 'Residency Clearance'             ? 'selected' : '' }}>Residency Clearance</option>
            <option value="Good Moral Clearance"            {{ $clearance->certificate_type == 'Good Moral Clearance'            ? 'selected' : '' }}>Good Moral Clearance</option>
            <option value="Police Clearance Endorsement"    {{ $clearance->certificate_type == 'Police Clearance Endorsement'    ? 'selected' : '' }}>Police Clearance Endorsement</option>
            <option value="First Time Job Seeker Clearance" {{ $clearance->certificate_type == 'First Time Job Seeker Clearance' ? 'selected' : '' }}>First Time Job Seeker Clearance</option>
            @if(!in_array($clearance->certificate_type, ['Barangay Clearance','Residency Clearance','Good Moral Clearance','Police Clearance Endorsement','First Time Job Seeker Clearance']))
            <option value="{{ $clearance->certificate_type }}" selected>{{ $clearance->certificate_type }}</option>
            @endif
          </select>
        </div>

        <div class="form-group">
          <label>Resident Name <span class="req">*</span></label>
          <input type="text" name="resident_name" id="cl-resident-name"
            value="{{ $clearance->resident_name }}" required oninput="clUpdateMeta()">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
        <div class="form-group">
          <label>Civil Status</label>
          <select name="civil_status" id="cl-civil-status" onchange="clUpdateMeta()">
            @foreach(['Single','Married','Widowed','Separated','Annulled','Common Law (Live-in)','Divorced'] as $cs)
            <option value="{{ $cs }}" {{ ($clearance->civil_status ?? 'Single') == $cs ? 'selected' : '' }}>{{ $cs }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label>Purok / Address</label>
          <input type="text" name="purok" id="cl-purok"
            value="{{ $clearance->purok }}" placeholder="e.g. Sampaguita" oninput="clUpdateMeta()">
        </div>
        </div>

        <div class="form-group">
          <label>Requestor</label>
          <select name="requestor" id="cl-requestor" onchange="clUpdateMeta()">
            <option value="The Subject (Self)"  {{ ($clearance->requestor ?? 'The Subject (Self)') == 'The Subject (Self)'  ? 'selected' : '' }}>The Subject (Self)</option>
            <option value="Parent/Guardian"     {{ ($clearance->requestor ?? '') == 'Parent/Guardian'     ? 'selected' : '' }}>Parent/Guardian</option>
            <option value="Representative"      {{ ($clearance->requestor ?? '') == 'Representative'      ? 'selected' : '' }}>Representative</option>
          </select>
        </div>

        <div style="border-top:1px solid var(--border);padding-top:12px;margin-top:4px">
          <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px">Receipt Footer</div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
          <div class="form-group" style="margin-bottom:8px">
            <label>O.R. Number</label>
            <input type="text" name="or_number" id="cl-or-number"
              value="{{ $clearance->or_number }}" placeholder="e.g. 8954164" oninput="clUpdateMeta()">
          </div>
          <div class="form-group" style="margin-bottom:8px">
            <label>Amount (₱)</label>
            <input type="number" name="amount" id="cl-amount" step="0.01" min="0"
              value="{{ $clearance->amount }}" placeholder="0.00" oninput="clUpdateMeta()">
          </div>
          </div>
        </div>

        <div style="display:flex;gap:8px;margin-top:8px">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Update Clearance
          </button>
          <a href="{{ route('clearance.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancel
          </a>
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
        <div id="cl-doc-title" style="text-align:center;font-size:18px;font-weight:bold;text-transform:uppercase;margin:20px 0 24px;letter-spacing:1px">
          {{ strtoupper($clearance->certificate_type) }}
        </div>

        {{-- Editable Body --}}
        <div style="margin-bottom:14px;flex:1">
          <div class="doc-toolbar">
            <button type="button" onclick="clFmt('bold')" title="Bold (Ctrl+B)"><b style="font-family:Georgia,serif">B</b></button>
            <button type="button" onclick="clFmt('italic')" title="Italic (Ctrl+I)"><i style="font-family:Georgia,serif">I</i></button>
            <button type="button" onclick="clFmt('underline')" title="Underline (Ctrl+U)"><u style="font-family:Georgia,serif">U</u></button>
            <div class="sep"></div>
            <button type="button" onclick="clFmt('justifyFull')" title="Justify text"><i class="fas fa-align-justify" style="font-size:10px"></i></button>
            <button type="button" onclick="clFmt('justifyLeft')" title="Align left"><i class="fas fa-align-left" style="font-size:10px"></i></button>
            <button type="button" onclick="clFmt('justifyCenter')" title="Align center"><i class="fas fa-align-center" style="font-size:10px"></i></button>
            <div style="flex:1"></div>
            <button type="button" onclick="clOnTypeChange()" title="Regenerate body from template for selected type"
              style="width:auto;padding:0 8px;font-size:11px;font-family:inherit;color:#64748b;gap:4px">
              <i class="fas fa-redo-alt" style="font-size:9px"></i> Reset to template
            </button>
          </div>
          <div id="cl-doc-body" contenteditable="true"
            style="outline:none;min-height:180px;border:1px solid #dde1e7;border-top:none;border-radius:0 0 6px 6px;padding:10px 8px;cursor:text;transition:background .15s"
            onfocus="this.style.background='rgba(239,246,255,0.6)'"
            onblur="this.style.background=''"
            oninput="clSyncBody()">
          </div>
        </div>

        {{-- Issued date --}}
        <p id="cl-issued-line" style="margin-top:14px"></p>

        {{-- Signature --}}
        <div style="margin-top:50px;text-align:right">
          <strong style="display:block;font-size:15px">ATTY. MA. CASSANDRA T. CODILLA, RCE</strong>
          <span style="font-size:13px;font-style:italic">Punong Barangay</span>
        </div>

        {{-- Receipt --}}
        <div id="cl-receipt" style="display:none;margin-top:30px;font-size:13px;color:#555"></div>

      </div>
    </div>
  </div>

  </div>
  {{-- END SPLIT SCREEN --}}

</div>

<script>
const clBodies = {
  'Barangay Clearance':
    `<p>TO WHOM IT MAY CONCERN:</p>
     <p style="text-indent:2em">THIS IS TO CERTIFY that <strong>[NAME]</strong>, of legal age, [CIVIL_STATUS], Filipino Citizen, is a bona fide resident of Purok [PUROK], Barangay Cogon, Ormoc City.</p>
     <p style="text-indent:2em">THIS IS TO CERTIFY FURTHER, that based on the records of this office, the above-named person has no derogatory record on file and is hereby <strong>CLEARED</strong> for whatever purpose this may serve.</p>
     <p style="text-indent:2em">This clearance is hereby issued upon the request of <strong>[REQUESTOR]</strong>.</p>`,
  'Residency Clearance':
    `<p>TO WHOM IT MAY CONCERN:</p>
     <p style="text-indent:2em">THIS IS TO CERTIFY that <strong>[NAME]</strong>, of legal age, [CIVIL_STATUS], Filipino Citizen, is a bona fide permanent resident of Purok [PUROK], Barangay Cogon, Ormoc City, and is hereby cleared for the purpose stated herein.</p>
     <p style="text-indent:2em">This clearance is hereby issued upon the request of <strong>[REQUESTOR]</strong>.</p>`,
  'Good Moral Clearance':
    `<p>TO WHOM IT MAY CONCERN:</p>
     <p style="text-indent:2em">THIS IS TO CERTIFY that <strong>[NAME]</strong>, of legal age, [CIVIL_STATUS], Filipino Citizen, is a bona fide resident of Purok [PUROK], Barangay Cogon, Ormoc City.</p>
     <p style="text-indent:2em">CERTIFYING FURTHER, that based on the records of this office, the above-named person is known to be of <strong>good moral character</strong>, law-abiding, and has no derogatory record on file as of this date.</p>
     <p style="text-indent:2em">This clearance is hereby issued upon the request of <strong>[REQUESTOR]</strong>.</p>`,
  'Police Clearance Endorsement':
    `<p>TO WHOM IT MAY CONCERN:</p>
     <p style="text-indent:2em">THIS IS TO CERTIFY that <strong>[NAME]</strong>, of legal age, [CIVIL_STATUS], Filipino Citizen, is a bona fide resident of Purok [PUROK], Barangay Cogon, Ormoc City.</p>
     <p style="text-indent:2em">This barangay hereby <strong>ENDORSES</strong> the above-named person for the issuance of a Police Clearance, as they have no pending complaints or derogatory record in this barangay as of this date.</p>
     <p style="text-indent:2em">This endorsement is issued upon the request of <strong>[REQUESTOR]</strong>.</p>`,
  'First Time Job Seeker Clearance':
    `<p>TO WHOM IT MAY CONCERN:</p>
     <p style="text-indent:2em">THIS IS TO CERTIFY that <strong>[NAME]</strong>, of legal age, [CIVIL_STATUS], Filipino Citizen, is a bona fide resident of Purok [PUROK], Barangay Cogon, Ormoc City, and is a <strong>FIRST TIME JOB SEEKER</strong> pursuant to Republic Act No. 11261 (First Time Jobseekers Assistance Act).</p>
     <p style="text-indent:2em">The above-named person is entitled to the exemption from payment of fees for the issuance of this clearance and other government documents.</p>
     <p style="text-indent:2em">This clearance is issued upon the request of <strong>[REQUESTOR]</strong>.</p>`,
};

function clGetVal(id) { return (document.getElementById(id)||{}).value || ''; }

function clSyncBody() {
  document.getElementById('cl-body-hidden').value = document.getElementById('cl-doc-body').innerHTML;
}

function clFmt(cmd) {
  document.getElementById('cl-doc-body').focus();
  document.execCommand(cmd, false, null);
  clSyncBody();
}

// Called when type changes — resets body from template
function clOnTypeChange() {
  const type      = clGetVal('cl-type-select');
  const name      = clGetVal('cl-resident-name') || '___________________';
  const civil     = clGetVal('cl-civil-status')  || 'Single';
  const purok     = clGetVal('cl-purok')          || '___________';
  const requestor = clGetVal('cl-requestor')      || 'the above-named person';

  document.getElementById('cl-doc-title').textContent = type || 'BARANGAY CLEARANCE';

  const bodyTpl  = clBodies[type] || '';
  const bodyHtml = bodyTpl
    .replace(/\[NAME\]/g, name).replace(/\[CIVIL_STATUS\]/g, civil)
    .replace(/\[PUROK\]/g, purok).replace(/\[REQUESTOR\]/g, requestor);
  document.getElementById('cl-doc-body').innerHTML = bodyHtml
    || '<p style="color:#aaa;text-align:center;font-family:sans-serif;font-size:13px">No template for this type.</p>';
  clSyncBody();
}

// Called when non-type fields change — only updates title, date, receipt (NOT body)
function clUpdateMeta() {
  const type   = clGetVal('cl-type-select');
  const orNum  = clGetVal('cl-or-number');
  const amount = clGetVal('cl-amount');
  const saved  = new Date(@json($clearance->date_issued));
  const day    = saved.getDate();
  const suffix = ['th','st','nd','rd'][day%10<4&&~~(day%100/10)!==1?day%10:0]||'th';
  const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
  const dateStr = `${day}${suffix} day of ${months[saved.getMonth()]}, ${saved.getFullYear()}`;

  document.getElementById('cl-doc-title').textContent = type || 'BARANGAY CLEARANCE';
  document.getElementById('cl-issued-line').innerHTML =
    `Issued this ${day}<sup>${suffix}</sup> day of ${months[saved.getMonth()]} ${saved.getFullYear()} at Barangay Cogon, Ormoc City, Philippines.`;

  const receipt = document.getElementById('cl-receipt');
  if (orNum || amount) {
    receipt.style.display = 'block';
    receipt.innerHTML = (orNum ? `O.R. No.: <strong>${orNum}</strong>&nbsp;&nbsp;` : '')
      + (amount ? `Amount Paid: <strong>₱${parseFloat(amount).toFixed(2)}</strong>` : '');
  } else {
    receipt.style.display = 'none';
  }
}

document.addEventListener('DOMContentLoaded', function() {
  // Populate body from saved content
  const savedBody = @json($clearance->body_content ?? '');
  const bodyEl = document.getElementById('cl-doc-body');
  if (savedBody) {
    bodyEl.innerHTML = savedBody;
  } else {
    bodyEl.innerHTML = '<p style="color:#aaa;text-align:center;font-family:sans-serif;font-size:13px">No body content saved. Select a type change to generate from template.</p>';
  }
  clSyncBody();
  clUpdateMeta();
});
</script>
@endsection
