@extends('layouts.app')

@section('page-title', 'Import Data')

@section('content')
<style>
.bidb-wrap { background:var(--bg); min-height:100vh; padding:28px; }
.page-hdr { margin-bottom:24px; }
.page-hdr h1 { font-size:22px; font-weight:700; color:var(--primary); margin:0; }
.breadcrumb { font-size:13px; color:var(--muted); margin-top:2px; }
.breadcrumb a { color:var(--primary); font-weight:500; text-decoration:none; }
.breadcrumb a:hover { text-decoration:underline; }
.import-wrap { max-width:680px; }
.card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); overflow:hidden; margin-bottom:20px; }
.card-header { padding:16px 20px; border-bottom:1px solid var(--border); background:var(--header-bg); display:flex; align-items:center; gap:10px; }
.card-title { font-weight:700; color:var(--primary); font-size:14px; display:flex; align-items:center; gap:8px; }
.card-body { padding:24px; }
label { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; display:block; margin-bottom:6px; }
.drop-zone { border:2px dashed var(--border); border-radius:10px; padding:40px 20px; text-align:center; cursor:pointer; transition:border-color .2s, background .2s; position:relative; }
.drop-zone:hover, .drop-zone.drag-over { border-color:var(--primary); background:var(--hover-bg); }
.drop-zone input[type="file"] { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
.drop-zone-icon { font-size:36px; color:var(--primary); opacity:.6; margin-bottom:10px; }
.drop-zone-text { font-size:14px; font-weight:600; color:var(--text); margin-bottom:4px; }
.drop-zone-sub { font-size:12px; color:var(--muted); }
.file-chosen { font-size:13px; color:var(--primary); font-weight:600; margin-top:10px; display:none; }
.btn-row { display:flex; gap:10px; margin-top:20px; }
.btn { display:inline-flex; align-items:center; gap:6px; padding:10px 20px; border-radius:8px; border:none; cursor:pointer; font-family:inherit; font-size:13px; font-weight:600; transition:all .15s; text-decoration:none; }
.btn-primary { background:var(--primary); color:#fff; }
.btn-primary:hover { background:var(--primary-light); }
.btn-secondary { background:var(--hover-bg); color:var(--muted); border:1.5px solid var(--border); }
.btn-secondary:hover { color:var(--text); }

/* Column mapping table */
.map-table { width:100%; border-collapse:collapse; font-size:13px; }
.map-table th { padding:8px 12px; text-align:left; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--muted); border-bottom:1.5px solid var(--border); }
.map-table td { padding:8px 12px; border-bottom:1px solid var(--border); color:var(--text); vertical-align:middle; }
.map-table tr:last-child td { border-bottom:none; }
.col-key { font-family:monospace; font-size:12px; background:var(--hover-bg); padding:2px 8px; border-radius:4px; border:1px solid var(--border); color:var(--primary); }
.req-badge { font-size:10px; background:#fee2e2; color:#991b1b; padding:1px 6px; border-radius:10px; font-weight:700; }
.opt-badge { font-size:10px; background:#f1f5f9; color:#64748b; padding:1px 6px; border-radius:10px; font-weight:700; }

.info-box { background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:12px 16px; margin-bottom:20px; font-size:13px; color:#1d4ed8; display:flex; align-items:flex-start; gap:10px; line-height:1.55; }
[data-theme="dark"] .info-box { background:#1e2d4a; border-color:#2a4a7f; color:#7ba5f5; }
</style>

<div class="bidb-wrap">
  <div class="page-hdr">
    <h1><i class="fas fa-file-import" style="margin-right:8px"></i>Import Data</h1>
    <div class="breadcrumb">
      <a href="{{ route('dashboard') }}">Home</a> ›
      <span>Import Data</span>
    </div>
  </div>

  <div class="import-wrap">

    @if(session('error'))
    <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:13px;">
      <i class="fas fa-exclamation-circle" style="margin-right:6px"></i>{{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:13px;">
      <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
    </div>
    @endif

    <div class="info-box">
      <i class="fas fa-info-circle" style="margin-top:2px;flex-shrink:0"></i>
      <div>
        Upload the <strong>RBI Excel file</strong> (.xlsx or .xls) directly — <strong>no renaming of columns needed</strong>.
        The importer reads the file based on column positions (B, C, F, G, J, K… etc.) starting at <strong>row 10</strong>.
        All records will be marked as <strong>Approved</strong> automatically.
        Rows with <em>**LACKING**</em> or empty names are skipped automatically.
      </div>
    </div>

    <!-- Upload form -->
    <div class="card">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-upload"></i> Upload File</div>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('residents.import') }}" enctype="multipart/form-data" onsubmit="showLoader()">
          @csrf
          <label>Excel / CSV File</label>
          <div class="drop-zone" id="dropZone">
            <input type="file" name="file" id="fileInput" accept=".xlsx,.xls,.csv" required>
            <div class="drop-zone-icon"><i class="fas fa-cloud-upload-alt"></i></div>
            <div class="drop-zone-text">Click to browse or drag & drop</div>
            <div class="drop-zone-sub">Supports .xlsx, .xls, .csv — max 10 MB</div>
            <div class="file-chosen" id="fileChosen"><i class="fas fa-check-circle"></i> <span id="fileName"></span></div>
          </div>

          <div class="btn-row">
            <button type="submit" class="btn btn-primary"><i class="fas fa-file-import"></i> Import</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Column mapping reference -->
    <div class="card">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-columns"></i> Column Reference</div>
      </div>
      <div class="card-body" style="padding:0">
        <table class="map-table">
          <thead>
            <tr>
              <th>Excel Column</th>
              <th>Data in File</th>
              <th>Saved As</th>
            </tr>
          </thead>
          <tbody>
            <tr><td><code class="col-key">Col B (2)</code></td><td>HH No.</td><td>HH Rank reference</td></tr>
            <tr><td><code class="col-key">Col C (3)</code></td><td>HH Rank (HEAD / MEMBER)</td><td>Family Role <span class="req-badge">Used</span></td></tr>
            <tr><td><code class="col-key">Col F (6)</code></td><td>Last Name</td><td>last_name <span class="req-badge">Required</span></td></tr>
            <tr><td><code class="col-key">Col G (7)</code></td><td>First Name</td><td>first_name <span class="req-badge">Required</span></td></tr>
            <tr><td><code class="col-key">Col E (5)</code></td><td>Full Name (FIRST M.I. LAST)</td><td>middle_name (extracted)</td></tr>
            <tr><td><code class="col-key">Col H (8)</code></td><td>Street / Address</td><td>address</td></tr>
            <tr><td><code class="col-key">Col J (10)</code></td><td>Purok (e.g. CHRYSANTHEMUM)</td><td>sitio</td></tr>
            <tr><td><code class="col-key">Col K (11)</code></td><td>City (e.g. ORMOC CITY, LEYTE)</td><td>city</td></tr>
            <tr><td><code class="col-key">Col L (12)</code></td><td>Birthdate (mm/dd/yyyy)</td><td>birthdate</td></tr>
            <tr><td><code class="col-key">Col M (13)</code></td><td>Age</td><td>age</td></tr>
            <tr><td><code class="col-key">Col N (14)</code></td><td>Gender (M / F)</td><td>gender → Male / Female</td></tr>
            <tr><td><code class="col-key">Col O (15)</code></td><td>Civil Status</td><td>civil_status</td></tr>
            <tr><td><code class="col-key">Col P (16)</code></td><td>Nationality</td><td>nationality</td></tr>
            <tr><td><code class="col-key">Col Q (17)</code></td><td>Occupation</td><td>occupation</td></tr>
            <tr><td><code class="col-key">Col R (18)</code></td><td>Sector code (a–i)</td><td>is_pwd (code d = PWD)</td></tr>
            <tr><td><code class="col-key">Col S (19)</code></td><td>PhilSys Number</td><td>philsys_number</td></tr>
            <tr><td><code class="col-key">Col T (20)</code></td><td>Religion</td><td>religion</td></tr>
            <tr><td><code class="col-key">Col U (21)</code></td><td>Contact Number</td><td>contact_number</td></tr>
            <tr><td><code class="col-key">Col V (22)</code></td><td>Email</td><td>email</td></tr>
            <tr><td><code class="col-key">Col W (23)</code></td><td>Education Level</td><td>education_level</td></tr>
          </tbody>
        </table>
        <div style="padding:12px 20px;font-size:12px;color:var(--muted);border-top:1px solid var(--border)">
          <strong>Sector codes:</strong> a=Labor Force &nbsp;b=Unemployed &nbsp;c=OFW &nbsp;<strong>d=PWD</strong> &nbsp;e=Solo Parent &nbsp;f=Indigenous &nbsp;g=Out of School Child &nbsp;h=Out of School Child &nbsp;i=Student
        </div>
      </div>
    </div>

  </div>
</div>

<script>
const fileInput = document.getElementById('fileInput');
const fileChosen = document.getElementById('fileChosen');
const fileName = document.getElementById('fileName');
const dropZone = document.getElementById('dropZone');

fileInput.addEventListener('change', function() {
  if (this.files.length) {
    fileName.textContent = this.files[0].name;
    fileChosen.style.display = 'block';
  }
});

dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));
dropZone.addEventListener('drop', e => {
  e.preventDefault();
  dropZone.classList.remove('drag-over');
  if (e.dataTransfer.files.length) {
    fileInput.files = e.dataTransfer.files;
    fileName.textContent = e.dataTransfer.files[0].name;
    fileChosen.style.display = 'block';
  }
});
</script>
@endsection
