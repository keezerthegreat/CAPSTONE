@extends('layouts.app')

@section('page-title', 'Resident Profile')

@section('content')
<style>
:root { --primary:#1a3a6b; --primary-light:#2554a0; --bg:#f0f4f8; --card:#fff; --text:#1e293b; --muted:#64748b; --border:#e2e8f0; }
.bidb-wrap { background:var(--bg); min-height:100vh; padding:28px; }
.page-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.page-hdr h1 { font-size:22px; font-weight:700; color:var(--primary); margin:0; }
.breadcrumb { font-size:13px; color:var(--muted); margin-top:2px; }
.breadcrumb a { color:var(--primary); text-decoration:none; }
.breadcrumb span { color:var(--primary); font-weight:500; }
.card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden; }
.card-header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.card-title { font-weight:700; color:var(--primary); font-size:14px; display:flex; align-items:center; gap:8px; }
.card-body { padding:24px; }
.info-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
.info-item .label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin-bottom:4px; }
.info-item .value { font-size:14px; color:var(--text); font-weight:500; }
.info-item.full { grid-column:span 3; }
.badge { display:inline-flex; align-items:center; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600; margin:2px; }
.badge-senior { background:#fef3c7; color:#92400e; }
.badge-pwd    { background:#fee2e2; color:#991b1b; }
.badge-voter  { background:#f3e8ff; color:#6b21a8; }
.badge-deceased { background:#fee2e2; color:#be123c; }
.btn { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:8px; border:none; cursor:pointer; font-family:inherit; font-size:13px; font-weight:600; transition:all .15s; text-decoration:none; }
.btn-primary { background:var(--primary); color:#fff; }
.btn-primary:hover { background:var(--primary-light); }
.btn-outline { background:#fff; color:var(--primary); border:1.5px solid var(--primary); }
.btn-outline:hover { background:#f0f4f8; }
.deceased-banner { background:#fff1f2; border:1.5px solid #fecdd3; border-radius:12px; padding:14px 20px; margin-bottom:20px; display:flex; align-items:center; gap:12px; }
.deceased-banner i { color:#be123c; font-size:20px; }
.deceased-banner .title { font-weight:700; color:#be123c; font-size:15px; }
.deceased-banner .sub { font-size:13px; color:#64748b; margin-top:2px; }
</style>

<div class="bidb-wrap">
  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-user" style="margin-right:8px"></i>Resident Profile</h1>
      <div class="breadcrumb">Home › <a href="{{ route('residents.index') }}">Residents</a> › <span>{{ $resident->first_name }} {{ $resident->last_name }}</span></div>
    </div>
    <div style="display:flex;gap:8px">
      <a href="{{ route('residents.edit', $resident->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
      <a href="{{ route('residents.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
  </div>

  {{-- Deceased Banner --}}
  @if($resident->is_deceased)
  <div class="deceased-banner">
    <i class="fas fa-cross"></i>
    <div>
      <div class="title">This resident has been marked as Deceased</div>
      @if($resident->date_of_death)
        <div class="sub">Date of Death: {{ \Carbon\Carbon::parse($resident->date_of_death)->format('F d, Y') }}</div>
      @endif
    </div>
  </div>
  @endif

  <!-- Personal Info -->
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-user"></i> Personal Information</div>
      <div>
        @if($resident->is_deceased)<span class="badge badge-deceased"><i class="fas fa-cross" style="margin-right:4px"></i>Deceased</span>@endif
        @if($resident->is_senior)<span class="badge badge-senior">Senior Citizen</span>@endif
        @if($resident->is_pwd)<span class="badge badge-pwd">PWD</span>@endif
        @if($resident->is_voter)<span class="badge badge-voter">Registered Voter</span>@endif
      </div>
    </div>
    <div class="card-body">
      <div class="info-grid">
        <div class="info-item"><div class="label">Last Name</div><div class="value">{{ $resident->last_name }}</div></div>
        <div class="info-item"><div class="label">First Name</div><div class="value">{{ $resident->first_name }}</div></div>
        <div class="info-item"><div class="label">Middle Name</div><div class="value">{{ $resident->middle_name ?? '—' }}</div></div>
        <div class="info-item"><div class="label">Sex</div><div class="value">{{ $resident->gender }}</div></div>
        <div class="info-item"><div class="label">Date of Birth</div><div class="value">{{ \Carbon\Carbon::parse($resident->birthdate)->format('F d, Y') }}</div></div>
        <div class="info-item"><div class="label">Age</div><div class="value">{{ $resident->age }} years old</div></div>
        <div class="info-item"><div class="label">Civil Status</div><div class="value">{{ $resident->civil_status ?? '—' }}</div></div>
        <div class="info-item"><div class="label">Nationality</div><div class="value">{{ $resident->nationality ?? '—' }}</div></div>
        <div class="info-item"><div class="label">Religion</div><div class="value">{{ $resident->religion ?? '—' }}</div></div>
      </div>
    </div>
  </div>

  <!-- Contact Info -->
  <div class="card">
    <div class="card-header"><div class="card-title"><i class="fas fa-phone"></i> Contact Information</div></div>
    <div class="card-body">
      <div class="info-grid">
        <div class="info-item"><div class="label">Contact Number</div><div class="value">{{ $resident->contact_number ?? '—' }}</div></div>
        <div class="info-item"><div class="label">Email Address</div><div class="value">{{ $resident->email ?? '—' }}</div></div>
      </div>
    </div>
  </div>

  <!-- Address -->
  <div class="card">
    <div class="card-header"><div class="card-title"><i class="fas fa-map-marker-alt"></i> Address</div></div>
    <div class="card-body">
      <div class="info-grid">
        <div class="info-item"><div class="label">Province</div><div class="value">{{ $resident->province ?? '—' }}</div></div>
        <div class="info-item"><div class="label">City / Municipality</div><div class="value">{{ $resident->city ?? '—' }}</div></div>
        <div class="info-item"><div class="label">Barangay</div><div class="value">{{ $resident->barangay ?? '—' }}</div></div>
        <div class="info-item full"><div class="label">Complete Address</div><div class="value">{{ $resident->address ?? '—' }}</div></div>
      </div>
    </div>
  </div>

  <!-- Socio-Economic -->
  <div class="card">
    <div class="card-header"><div class="card-title"><i class="fas fa-briefcase"></i> Socio-Economic Information</div></div>
    <div class="card-body">
      <div class="info-grid">
        <div class="info-item"><div class="label">Occupation</div><div class="value">{{ $resident->occupation ?? '—' }}</div></div>
        <div class="info-item"><div class="label">Employer / Workplace</div><div class="value">{{ $resident->employer ?? '—' }}</div></div>
        <div class="info-item"><div class="label">Monthly Income</div><div class="value">{{ $resident->monthly_income ? '₱'.number_format($resident->monthly_income,2) : '—' }}</div></div>
        <div class="info-item"><div class="label">Education Level</div><div class="value">{{ $resident->education_level ?? '—' }}</div></div>
      </div>
    </div>
  </div>

</div>
@endsection