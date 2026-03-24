@extends('layouts.app')

@section('page-title', 'Edit Certificate')

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
#ct-doc-body p { margin:0 0 14px; text-align:justify; font-family:"Times New Roman",serif; font-size:15px; line-height:1.9; }
#ct-doc-body p:last-child { margin-bottom:0; }
.doc-toolbar { display:flex; align-items:center; gap:2px; padding:5px 8px; background:#f8fafc; border:1px solid #dde1e7; border-radius:6px 6px 0 0; flex-wrap:wrap; }
.doc-toolbar button { width:26px; height:24px; background:none; border:1px solid transparent; border-radius:4px; cursor:pointer; font-size:13px; color:#374151; display:flex; align-items:center; justify-content:center; }
.doc-toolbar button:hover { background:#e2e8f0; border-color:#cbd5e1; }
.doc-toolbar .sep { width:1px; height:16px; background:#dde1e7; margin:0 3px; }
</style>

<div class="bidb-wrap">

  <div class="page-hdr">
    <h1><i class="fas fa-edit" style="margin-right:8px"></i>Edit Certificate</h1>
    <div class="breadcrumb">
      <a href="{{ route('dashboard') }}">Home</a> ›
      <a href="{{ route('certificate.index') }}">Certificates</a> ›
      <span style="color:var(--muted)">Edit</span>
    </div>
  </div>

  <div class="info-box">
    <i class="fas fa-info-circle"></i>
    Editing certificate <strong>{{ $certificate->certificate_no }}</strong> — issued on {{ \Carbon\Carbon::parse($certificate->issued_date)->format('F d, Y') }}
  </div>

  {{-- SPLIT SCREEN: Form + Document Editor --}}
  <div style="display:grid;grid-template-columns:420px 1fr;gap:20px;align-items:start">

  {{-- LEFT: Form --}}
  <div class="card" style="position:sticky;top:20px">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-edit"></i> Edit Certificate — <span style="font-weight:400;color:var(--muted)">{{ $certificate->certificate_no }}</span></div>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('certificate.update', $certificate->id) }}" id="ct-form">
        @csrf
        @method('PUT')
        <input type="hidden" name="body_content" id="ct-body-hidden">

        <div class="form-group">
          <label>Document Type <span class="req">*</span></label>
          <select name="certificate_type" id="ct-type-select" required onchange="ctOnTypeChange()">
            <option value="Barangay Certification"              {{ $certificate->certificate_type == 'Barangay Certification'              ? 'selected' : '' }}>Barangay Certification (Custom)</option>
            <option value="Good Moral Character Clearance"      {{ $certificate->certificate_type == 'Good Moral Character Clearance'      ? 'selected' : '' }}>Good Moral Character Clearance</option>
            <option value="Certificate of Residency"            {{ $certificate->certificate_type == 'Certificate of Residency'            ? 'selected' : '' }}>Certificate of Residency</option>
            <option value="Certificate of Indigency"            {{ $certificate->certificate_type == 'Certificate of Indigency'            ? 'selected' : '' }}>Certificate of Indigency</option>
            <option value="Certificate of Unemployment"         {{ $certificate->certificate_type == 'Certificate of Unemployment'         ? 'selected' : '' }}>Certificate of Unemployment</option>
            <option value="Certificate of Residency for Voters" {{ $certificate->certificate_type == 'Certificate of Residency for Voters' ? 'selected' : '' }}>Certificate of Residency for Voters</option>
            <option value="Certificate of Guardianship"         {{ $certificate->certificate_type == 'Certificate of Guardianship'         ? 'selected' : '' }}>Certificate of Guardianship</option>
            <option value="Barangay Business Clearance"         {{ $certificate->certificate_type == 'Barangay Business Clearance'         ? 'selected' : '' }}>Barangay Business Clearance</option>
          </select>
        </div>

        <div class="form-group">
          <label>Resident Name <span class="req">*</span></label>
          <input type="text" name="resident_name" id="ct-resident-name"
            value="{{ $certificate->resident_name }}" required oninput="ctUpdateMeta()">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
        <div class="form-group">
          <label>Civil Status</label>
          <select name="civil_status" id="ct-civil-status" onchange="ctUpdateMeta()">
            @foreach(['Single','Married','Widowed','Separated','Annulled','Common Law (Live-in)','Divorced'] as $cs)
            <option value="{{ $cs }}" {{ ($certificate->civil_status ?? 'Single') == $cs ? 'selected' : '' }}>{{ $cs }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label>Purok / Address</label>
          <input type="text" name="purok" id="ct-purok"
            value="{{ $certificate->purok }}" placeholder="e.g. Sampaguita" oninput="ctUpdateMeta()">
        </div>
        </div>

        <div class="form-group">
          <label>Requestor</label>
          <select name="requestor" id="ct-requestor" onchange="ctUpdateMeta()">
            <option value="The Subject (Self)"  {{ ($certificate->requestor ?? 'The Subject (Self)') == 'The Subject (Self)'  ? 'selected' : '' }}>The Subject (Self)</option>
            <option value="Parent/Guardian"     {{ ($certificate->requestor ?? '') == 'Parent/Guardian'     ? 'selected' : '' }}>Parent/Guardian</option>
            <option value="Representative"      {{ ($certificate->requestor ?? '') == 'Representative'      ? 'selected' : '' }}>Representative</option>
          </select>
        </div>

        <div style="border-top:1px solid var(--border);padding-top:12px;margin-top:4px">
          <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px">Receipt Footer</div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
          <div class="form-group" style="margin-bottom:8px">
            <label>O.R. Number</label>
            <input type="text" name="or_number" id="ct-or-number"
              value="{{ $certificate->or_number }}" placeholder="e.g. 8954164" oninput="ctUpdateMeta()">
          </div>
          <div class="form-group" style="margin-bottom:8px">
            <label>Amount (₱)</label>
            <input type="number" name="amount" id="ct-amount" step="0.01" min="0"
              value="{{ $certificate->amount }}" placeholder="0.00" oninput="ctUpdateMeta()">
          </div>
          </div>
        </div>

        <div style="display:flex;gap:8px;margin-top:8px">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Update Certificate
          </button>
          <a href="{{ route('certificate.index') }}" class="btn btn-secondary">
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
        <div id="ct-doc-title" style="text-align:center;font-size:18px;font-weight:bold;text-transform:uppercase;margin:20px 0 24px;letter-spacing:1px">
          {{ strtoupper($certificate->certificate_type) }}
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
            <button type="button" onclick="ctOnTypeChange()" title="Regenerate body from template for selected type"
              style="width:auto;padding:0 8px;font-size:11px;font-family:inherit;color:#64748b;gap:4px">
              <i class="fas fa-redo-alt" style="font-size:9px"></i> Reset to template
            </button>
          </div>
          <div id="ct-doc-body" contenteditable="true"
            style="outline:none;min-height:180px;border:1px solid #dde1e7;border-top:none;border-radius:0 0 6px 6px;padding:10px 8px;cursor:text;transition:background .15s"
            onfocus="this.style.background='rgba(239,246,255,0.6)'"
            onblur="this.style.background=''"
            oninput="ctSyncBody()">
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

</div>

<script>
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

// Called when type changes — resets body from template
function ctOnTypeChange() {
  const type      = ctGetVal('ct-type-select');
  const name      = ctGetVal('ct-resident-name') || '___________________';
  const civil     = ctGetVal('ct-civil-status')  || 'Single';
  const purok     = ctGetVal('ct-purok')          || '___________';
  const requestor = ctGetVal('ct-requestor')      || 'the above-named person';

  document.getElementById('ct-doc-title').textContent = type || 'BARANGAY CERTIFICATION';

  const bodyTpl  = ctBodies[type] || '';
  const bodyHtml = bodyTpl
    .replace(/\[NAME\]/g, name).replace(/\[CIVIL_STATUS\]/g, civil)
    .replace(/\[PUROK\]/g, purok).replace(/\[REQUESTOR\]/g, requestor);
  document.getElementById('ct-doc-body').innerHTML = bodyHtml
    || '<p style="color:#aaa;text-align:center;font-family:sans-serif;font-size:13px">No template for this type.</p>';
  ctSyncBody();
}

// Called when non-type fields change — only updates title, date, receipt (NOT body)
function ctUpdateMeta() {
  const type   = ctGetVal('ct-type-select');
  const orNum  = ctGetVal('ct-or-number');
  const amount = ctGetVal('ct-amount');
  const saved  = new Date(@json($certificate->issued_date));
  const day    = saved.getDate();
  const suffix = ['th','st','nd','rd'][day%10<4&&~~(day%100/10)!==1?day%10:0]||'th';
  const months = ['January','February','March','April','May','June','July','August','September','October','November','December'];
  const dateStr = `${day}${suffix} day of ${months[saved.getMonth()]}, ${saved.getFullYear()}`;

  document.getElementById('ct-doc-title').textContent = type || 'BARANGAY CERTIFICATION';
  document.getElementById('ct-issued-line').innerHTML =
    `Issued this ${day}<sup>${suffix}</sup> day of ${months[saved.getMonth()]} ${saved.getFullYear()} at Barangay Cogon, Ormoc City, Philippines.`;

  const receipt = document.getElementById('ct-receipt');
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
  const savedBody = @json($certificate->body_content ?? '');
  const bodyEl = document.getElementById('ct-doc-body');
  if (savedBody) {
    bodyEl.innerHTML = savedBody;
  } else {
    bodyEl.innerHTML = '<p style="color:#aaa;text-align:center;font-family:sans-serif;font-size:13px">No body content saved. Change the type to generate from template.</p>';
  }
  ctSyncBody();
  ctUpdateMeta();
});
</script>
@endsection
