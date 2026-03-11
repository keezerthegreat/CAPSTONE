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
.edit-wrap { max-width:600px; }
.card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); overflow:hidden; }
.card-header { padding:16px 20px; border-bottom:1px solid var(--border); background:#f8fafc; display:flex; align-items:center; gap:10px; }
.card-title { font-weight:700; color:var(--primary); font-size:14px; display:flex; align-items:center; gap:8px; }
.cert-no { font-size:12px; color:var(--muted); margin-left:auto; font-weight:600; }
.card-body { padding:24px; }
.form-group { display:flex; flex-direction:column; gap:5px; margin-bottom:18px; }
label { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; }
label .req { color:#dc2626; margin-left:2px; }
input, select, textarea { padding:10px 12px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; font-family:inherit; color:var(--text); outline:none; background:#fff; width:100%; box-sizing:border-box; transition:border-color .15s; }
input:focus, select:focus, textarea:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(26,58,107,.08); }
textarea { resize:vertical; min-height:90px; }
.btn-row { display:flex; gap:10px; margin-top:8px; }
.btn { display:inline-flex; align-items:center; gap:6px; padding:10px 18px; border-radius:8px; border:none; cursor:pointer; font-family:inherit; font-size:13px; font-weight:600; transition:all .15s; text-decoration:none; }
.btn-primary { background:var(--primary); color:#fff; }
.btn-primary:hover { background:var(--primary-light); }
.btn-secondary { background:#f1f5f9; color:var(--muted); border:1.5px solid var(--border); }
.btn-secondary:hover { background:#e2e8f0; color:var(--text); }
.info-box { background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:12px 16px; margin-bottom:20px; font-size:13px; color:#1d4ed8; display:flex; align-items:center; gap:8px; }
[data-theme="dark"] .info-box { background:#1e2d4a; border-color:#2a4a7f; color:#7ba5f5; }
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

  <div class="edit-wrap">
    <div class="info-box">
      <i class="fas fa-info-circle"></i>
      Editing certificate <strong>{{ $certificate->certificate_no }}</strong> — issued on {{ \Carbon\Carbon::parse($certificate->issued_date)->format('F d, Y') }}
    </div>

    <div class="card">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-certificate"></i> Certificate Details</div>
        <span class="cert-no">{{ $certificate->certificate_no }}</span>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('certificate.update', $certificate->id) }}">
          @csrf
          @method('PUT')

          <div class="form-group">
            <label>Resident Name <span class="req">*</span></label>
            <input type="text" name="resident_name" value="{{ $certificate->resident_name }}" required>
          </div>

          <div class="form-group">
            <label>Certificate Type <span class="req">*</span></label>
            <select name="certificate_type" required>
              <option value="Good Moral Character Clearance" {{ $certificate->certificate_type == 'Good Moral Character Clearance' ? 'selected' : '' }}>Good Moral Character Clearance</option>
              <option value="Certificate of Residency" {{ $certificate->certificate_type == 'Certificate of Residency' ? 'selected' : '' }}>Certificate of Residency</option>
              <option value="Certificate of Indigency" {{ $certificate->certificate_type == 'Certificate of Indigency' ? 'selected' : '' }}>Certificate of Indigency</option>
              <option value="Certificate of Unemployment" {{ $certificate->certificate_type == 'Certificate of Unemployment' ? 'selected' : '' }}>Certificate of Unemployment</option>
              <option value="Certificate of Residency for Voters" {{ $certificate->certificate_type == 'Certificate of Residency for Voters' ? 'selected' : '' }}>Certificate of Residency for Voters</option>
              <option value="Certificate of Guardianship" {{ $certificate->certificate_type == 'Certificate of Guardianship' ? 'selected' : '' }}>Certificate of Guardianship</option>
            </select>
          </div>

          <div class="form-group">
            <label>Purpose <span class="req">*</span></label>
            <textarea name="purpose" required>{{ $certificate->purpose }}</textarea>
          </div>

          <div class="btn-row">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Update Certificate
            </button>
            <a href="{{ route('certificate.index') }}" class="btn btn-secondary">
              <i class="fas fa-times"></i> Discard Changes
            </a>
          </div>

        </form>
      </div>
    </div>
  </div>

</div>
@endsection