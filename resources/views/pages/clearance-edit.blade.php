@extends('layouts.app')

@section('page-title', 'Edit Barangay Clearance')

@section('content')
<style>
:root { --primary:#1a3a6b; --primary-light:#2554a0; --bg:#f0f4f8; --card:#fff; --text:#1e293b; --muted:#64748b; --border:#e2e8f0; }
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
.clearance-no { font-size:12px; color:var(--muted); margin-left:auto; font-weight:600; }
.card-body { padding:24px; }
.form-group { display:flex; flex-direction:column; gap:5px; margin-bottom:18px; }
label { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; }
label .req { color:#dc2626; margin-left:2px; }
input, select { padding:10px 12px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; font-family:inherit; color:var(--text); outline:none; background:#fff; width:100%; box-sizing:border-box; transition:border-color .15s; }
input:focus, select:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(26,58,107,.08); }
.btn-row { display:flex; gap:10px; margin-top:8px; }
.btn { display:inline-flex; align-items:center; gap:6px; padding:10px 18px; border-radius:8px; border:none; cursor:pointer; font-family:inherit; font-size:13px; font-weight:600; transition:all .15s; text-decoration:none; }
.btn-primary { background:var(--primary); color:#fff; }
.btn-primary:hover { background:var(--primary-light); }
.btn-secondary { background:#f1f5f9; color:var(--muted); border:1.5px solid var(--border); }
.btn-secondary:hover { background:#e2e8f0; color:var(--text); }
.info-box { background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:12px 16px; margin-bottom:20px; font-size:13px; color:#1d4ed8; display:flex; align-items:center; gap:8px; }
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

  <div class="edit-wrap">
    <div class="info-box">
      <i class="fas fa-info-circle"></i>
      Editing clearance <strong>{{ $clearance->clearance_no }}</strong> — issued on {{ \Carbon\Carbon::parse($clearance->date_issued)->format('F d, Y') }}
    </div>

    <div class="card">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-file-alt"></i> Clearance Details</div>
        <span class="clearance-no">{{ $clearance->clearance_no }}</span>
      </div>
      <div class="card-body">
        <form action="{{ route('clearance.update', $clearance->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="form-group">
            <label>Resident Name <span class="req">*</span></label>
            <input type="text" name="resident_name" value="{{ $clearance->resident_name }}" required>
          </div>

          <div class="form-group">
            <label>Certificate Type <span class="req">*</span></label>
            <select name="certificate_type" required>
              <option value="Barangay Clearance"               {{ $clearance->certificate_type == 'Barangay Clearance'               ? 'selected' : '' }}>Barangay Clearance</option>
              <option value="Residency Clearance"              {{ $clearance->certificate_type == 'Residency Clearance'              ? 'selected' : '' }}>Residency Clearance</option>
              <option value="Good Moral Clearance"             {{ $clearance->certificate_type == 'Good Moral Clearance'             ? 'selected' : '' }}>Good Moral Clearance</option>
              <option value="Police Clearance Endorsement"     {{ $clearance->certificate_type == 'Police Clearance Endorsement'     ? 'selected' : '' }}>Police Clearance Endorsement</option>
              <option value="First Time Job Seeker Clearance"  {{ $clearance->certificate_type == 'First Time Job Seeker Clearance'  ? 'selected' : '' }}>First Time Job Seeker Clearance</option>
              {{-- Legacy values fallback --}}
              @if(!in_array($clearance->certificate_type, ['Barangay Clearance','Residency Clearance','Good Moral Clearance','Police Clearance Endorsement','First Time Job Seeker Clearance']))
              <option value="{{ $clearance->certificate_type }}" selected>{{ $clearance->certificate_type }}</option>
              @endif
            </select>
          </div>

          <div class="form-group">
            <label>Purpose <span class="req">*</span></label>
            <input type="text" name="purpose" value="{{ $clearance->purpose }}" required>
          </div>

          <div class="btn-row">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Update Clearance
            </button>
            <a href="{{ route('clearance.index') }}" class="btn btn-secondary">
              <i class="fas fa-times"></i> Discard Changes
            </a>
          </div>

        </form>
      </div>
    </div>
  </div>

</div>
@endsection