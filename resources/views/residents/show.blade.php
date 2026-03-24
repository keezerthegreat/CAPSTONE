@extends('layouts.app')

@section('page-title', 'Resident Profile')

@section('content')
<style>
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
.btn-print { background:#fff; color:#374151; border:1.5px solid #d1d5db; }
.btn-print:hover { background:#f9fafb; }
.deceased-banner { background:#fff1f2; border:1.5px solid #fecdd3; border-radius:12px; padding:14px 20px; margin-bottom:20px; display:flex; align-items:center; gap:12px; }
.deceased-banner i { color:#be123c; font-size:20px; }
.deceased-banner .title { font-weight:700; color:#be123c; font-size:15px; }
.deceased-banner .sub { font-size:13px; color:#64748b; margin-top:2px; }

/* ── RBI Print Form (hidden on screen, visible on print) ── */
#rbi-print { display:none; }

@media print {
  .bidb-wrap { display:none !important; }
  #rbi-print  { display:block !important; }
  @page { size:A4; margin:15mm 15mm 15mm 15mm; }
}

/* RBI Form Styles */
#rbi-print {
  font-family: Arial, sans-serif;
  font-size: 10pt;
  color: #000;
  background: #fff;
  padding: 0;
  line-height: 1.4;
}
.rbi-formid { font-size:9pt; font-weight:bold; margin-bottom:2px; }
.rbi-title  { font-size:12pt; font-weight:bold; text-align:center; text-transform:uppercase; margin-bottom:10px; letter-spacing:.04em; }
.rbi-top-grid { display:grid; grid-template-columns:1fr 1fr; gap:0; border:1px solid #000; margin-bottom:8px; }
.rbi-top-cell { display:flex; align-items:center; border-bottom:1px solid #000; padding:3px 6px; gap:6px; }
.rbi-top-cell:nth-child(odd) { border-right:1px solid #000; }
.rbi-top-cell:last-child, .rbi-top-cell:nth-last-child(2) { border-bottom:none; }
.rbi-top-lbl { font-size:8pt; font-weight:bold; white-space:nowrap; min-width:65px; }
.rbi-top-val { flex:1; border-bottom:1px solid #000; font-size:9pt; padding:1px 2px; min-height:14px; }
.rbi-section-box { border:1.5px solid #000; padding:10px 12px; margin-bottom:8px; }
.rbi-section-title { font-size:9pt; font-weight:bold; text-transform:uppercase; margin-bottom:8px; border-bottom:1px solid #000; padding-bottom:3px; }
.rbi-field-row { display:flex; align-items:flex-end; gap:6px; margin-bottom:8px; }
.rbi-field-lbl { font-size:8pt; font-weight:bold; white-space:nowrap; min-width:90px; }
.rbi-underline { flex:1; border-bottom:1px solid #000; font-size:9.5pt; min-height:16px; padding:1px 3px; }
.rbi-underline.sm { flex:0 0 60px; }
.rbi-underline.md { flex:0 0 110px; }
.rbi-name-cols { display:grid; grid-template-columns:1fr 1fr 1fr 50px; gap:8px; flex:1; }
.rbi-named-col { display:flex; flex-direction:column; }
.rbi-named-col .rbi-underline { margin-bottom:1px; }
.rbi-col-sub { font-size:7.5pt; text-align:center; color:#333; margin-top:1px; }
.rbi-dob-cols { display:flex; gap:8px; align-items:flex-end; }
.rbi-dob-box { display:flex; flex-direction:column; align-items:center; }
.rbi-dob-box .rbi-underline { width:40px; text-align:center; }
.rbi-dob-box .rbi-col-sub { width:40px; }
.rbi-dob-box.yr .rbi-underline { width:65px; }
.rbi-dob-box.yr .rbi-col-sub { width:65px; }
.rbi-check-row { display:flex; align-items:center; gap:18px; flex-wrap:wrap; flex:1; }
.rbi-check-item { display:flex; align-items:center; gap:4px; font-size:9pt; }
.rbi-checkbox { width:11px; height:11px; border:1px solid #000; display:inline-flex; align-items:center; justify-content:center; font-size:9pt; line-height:1; flex-shrink:0; }
.rbi-checkbox.checked::after { content:'✓'; font-size:9pt; line-height:1; }
.rbi-addr-cols { display:grid; grid-template-columns:80px 1fr; gap:8px; flex:1; }
.rbi-addr-full { display:flex; flex-direction:column; }
.rbi-two-col { display:grid; grid-template-columns:1fr 1fr; gap:8px; flex:1; }
.rbi-col { display:flex; flex-direction:column; }
.rbi-divider { border:none; border-top:1px solid #000; margin:8px 0; }
.rbi-cert-text { font-size:8.5pt; font-style:italic; margin:6px 0 14px; }
.rbi-sign-row { display:grid; grid-template-columns:1fr 1fr; gap:32px; margin-top:10px; }
.rbi-sign-block { display:flex; flex-direction:column; }
.rbi-sign-line { border-top:1px solid #000; margin-top:28px; }
.rbi-sign-label { font-size:7.5pt; text-align:center; margin-top:3px; color:#333; }
.rbi-thumb-area { display:flex; gap:16px; justify-content:center; margin-top:8px; }
.rbi-thumb-box { display:flex; flex-direction:column; align-items:center; }
.rbi-thumb-rect { width:62px; height:75px; border:1px solid #000; }
.rbi-thumb-lbl { font-size:7.5pt; margin-top:3px; text-align:center; }
.rbi-attest-row { display:flex; align-items:flex-end; gap:12px; margin-top:14px; }
.rbi-hh-box { border:1px solid #000; width:100px; min-height:22px; padding:2px 4px; font-size:9pt; }
.rbi-note { font-size:7.5pt; font-style:italic; margin-top:14px; border-top:1px solid #ccc; padding-top:5px; color:#444; }
.rbi-special-checks { display:flex; gap:20px; flex-wrap:wrap; margin-top:4px; }
.rbi-income-field { display:flex; align-items:flex-end; gap:4px; }
.rbi-peso { font-size:9pt; padding-bottom:2px; }
</style>

@php
  $dob     = $resident->birthdate ? \Carbon\Carbon::parse($resident->birthdate) : null;
  $dobMM   = $dob ? $dob->format('m') : '';
  $dobDD   = $dob ? $dob->format('d') : '';
  $dobYYYY = $dob ? $dob->format('Y') : '';
  $cs      = strtolower(trim($resident->civil_status ?? ''));
  $hh      = $resident->household;
  $hhNo    = $hh ? $hh->household_no : '';
  $today   = \Carbon\Carbon::now()->format('F d, Y');
@endphp

<div class="bidb-wrap">
  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-user" style="margin-right:8px"></i>Resident Profile</h1>
      <div class="breadcrumb">Home › <a href="{{ route('residents.index') }}">Residents</a> › <span>{{ $resident->first_name }} {{ $resident->last_name }}</span></div>
    </div>
    <div style="display:flex;gap:8px">
      <button type="button" onclick="window.print()" class="btn btn-print"><i class="fas fa-print"></i> Print RBI Form</button>
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

  {{-- Transferred Banner --}}
  @if($resident->transferred_to)
  <div style="background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:12px;padding:14px 20px;margin-bottom:20px;display:flex;align-items:center;gap:12px;">
    <i class="fas fa-map-marker-alt" style="color:#1d4ed8;font-size:20px"></i>
    <div>
      <div style="font-weight:700;color:#1d4ed8;font-size:15px">This resident has been transferred</div>
      <div style="font-size:13px;color:#64748b;margin-top:2px">Transferred to: <strong>{{ $resident->transferred_to }}</strong></div>
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
        @if($resident->is_solo_parent)<span class="badge" style="background:#fef9c3;color:#854d0e">Solo Parent</span>@endif
        @if($resident->is_labor_force)<span class="badge" style="background:#e0f2fe;color:#075985">Labor Force</span>@endif
        @if($resident->is_unemployed)<span class="badge" style="background:#fee2e2;color:#991b1b">Unemployed</span>@endif
        @if($resident->is_ofw)<span class="badge" style="background:#d1fae5;color:#065f46">OFW</span>@endif
        @if($resident->is_indigenous)<span class="badge" style="background:#fdf4ff;color:#6b21a8">Indigenous</span>@endif
        @if($resident->is_out_of_school_child)<span class="badge" style="background:#fff7ed;color:#9a3412">Out of School Child</span>@endif
        @if($resident->is_out_of_school_youth)<span class="badge" style="background:#fff7ed;color:#9a3412">Out of School Youth</span>@endif
        @if($resident->is_student)<span class="badge" style="background:#eff6ff;color:#1e40af">Student</span>@endif
      </div>
    </div>
    <div class="card-body">
      <div class="info-grid">
        <div class="info-item"><div class="label">Last Name</div><div class="value">{{ $resident->last_name }}</div></div>
        <div class="info-item"><div class="label">First Name</div><div class="value">{{ $resident->first_name }}</div></div>
        <div class="info-item"><div class="label">Middle Name</div><div class="value">{{ $resident->middle_name ?? '—' }}</div></div>
        <div class="info-item"><div class="label">Suffix</div><div class="value">{{ $resident->suffix ?? '—' }}</div></div>
        <div class="info-item"><div class="label">Sex</div><div class="value">{{ $resident->gender }}</div></div>
        <div class="info-item"><div class="label">Date of Birth</div><div class="value">{{ \Carbon\Carbon::parse($resident->birthdate)->format('F d, Y') }}</div></div>
        <div class="info-item"><div class="label">Place of Birth</div><div class="value">{{ $resident->place_of_birth ?? '—' }}</div></div>
        <div class="info-item"><div class="label">Age</div><div class="value">{{ $resident->age }} years old</div></div>
        <div class="info-item"><div class="label">Civil Status</div><div class="value">{{ $resident->civil_status ?? '—' }}</div></div>
        <div class="info-item"><div class="label">Citizenship</div><div class="value">{{ $resident->nationality ?? '—' }}</div></div>
        <div class="info-item"><div class="label">Inhabitant</div><div class="value">{{ $resident->resident_type ?? '—' }}</div></div>
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
        <div class="info-item"><div class="label">PhilSys Card No.</div><div class="value">{{ $resident->philsys_number ?? '—' }}</div></div>
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

  @if($resident->household)
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-home"></i> Household</div>
      <a href="{{ route('households.show', $resident->household->id) }}" class="btn btn-outline" style="font-size:12px;padding:5px 12px">View Household</a>
    </div>
    <div class="card-body">
      <div class="info-grid">
        <div class="info-item">
          <div class="label">Household No.</div>
          <div class="value">
            <button onclick="document.getElementById('hh-modal').classList.add('open')" style="background:none;border:none;padding:0;color:var(--primary);font-weight:700;font-size:14px;cursor:pointer;text-decoration:underline;text-underline-offset:3px;font-family:inherit">
              {{ $resident->household->household_number }}
            </button>
          </div>
        </div>
        <div class="info-item"><div class="label">Head</div><div class="value">{{ $resident->household->head_last_name }}, {{ $resident->household->head_first_name }}{{ $resident->household->head_middle_name ? ' '.strtoupper(substr($resident->household->head_middle_name,0,1)).'.' : '' }}</div></div>
        <div class="info-item"><div class="label">Purok</div><div class="value">{{ $resident->household->sitio ?? '—' }}</div></div>
        <div class="info-item"><div class="label">Residency Type</div><div class="value">{{ $resident->household->residency_type ?? '—' }}</div></div>
        <div class="info-item"><div class="label">Members</div><div class="value">{{ $resident->household->member_count }} member(s)</div></div>
      </div>
    </div>
  </div>

  <!-- Household Quick-View Modal -->
  <div id="hh-modal" class="hh-modal-backdrop" onclick="if(event.target===this)this.classList.remove('open')">
    <div class="hh-modal">
      <div class="hh-modal-header">
        <div style="font-size:16px;font-weight:700;color:var(--primary)"><i class="fas fa-home" style="margin-right:8px"></i>{{ $resident->household->household_number }}</div>
        <button onclick="document.getElementById('hh-modal').classList.remove('open')" style="background:none;border:none;font-size:22px;color:var(--muted);cursor:pointer;line-height:1;padding:0">&times;</button>
      </div>
      <div class="hh-modal-body">
        <div class="hh-modal-section">
          <div class="hh-modal-section-title"><i class="fas fa-user-tie"></i> Household Head</div>
          <div class="hh-modal-grid">
            <div class="hh-mi"><span class="hh-ml">Last Name</span><span class="hh-mv">{{ $resident->household->head_last_name ?? '—' }}</span></div>
            <div class="hh-mi"><span class="hh-ml">First Name</span><span class="hh-mv">{{ $resident->household->head_first_name ?? '—' }}</span></div>
            <div class="hh-mi"><span class="hh-ml">Middle Name</span><span class="hh-mv">{{ $resident->household->head_middle_name ?? '—' }}</span></div>
          </div>
        </div>
        <div class="hh-modal-section">
          <div class="hh-modal-section-title"><i class="fas fa-map-marker-alt"></i> Address</div>
          <div class="hh-modal-grid">
            <div class="hh-mi"><span class="hh-ml">Purok</span><span class="hh-mv">{{ $resident->household->sitio ?? '—' }}</span></div>
            <div class="hh-mi"><span class="hh-ml">Street</span><span class="hh-mv">{{ $resident->household->street ?? '—' }}</span></div>
            <div class="hh-mi"><span class="hh-ml">Barangay</span><span class="hh-mv">{{ $resident->household->barangay ?? '—' }}</span></div>
            <div class="hh-mi"><span class="hh-ml">City</span><span class="hh-mv">{{ $resident->household->city ?? '—' }}</span></div>
            <div class="hh-mi"><span class="hh-ml">Province</span><span class="hh-mv">{{ $resident->household->province ?? '—' }}</span></div>
            <div class="hh-mi"><span class="hh-ml">GPS</span><span class="hh-mv">{{ $resident->household->latitude && $resident->household->longitude ? $resident->household->latitude.', '.$resident->household->longitude : 'Not pinned' }}</span></div>
          </div>
        </div>
        <div class="hh-modal-section">
          <div class="hh-modal-section-title"><i class="fas fa-info-circle"></i> Details</div>
          <div class="hh-modal-grid">
            <div class="hh-mi"><span class="hh-ml">Residency Type</span><span class="hh-mv">{{ $resident->household->residency_type ?? '—' }}</span></div>
            <div class="hh-mi"><span class="hh-ml">Members</span><span class="hh-mv">{{ $resident->household->member_count }} member(s)</span></div>
            <div class="hh-mi"><span class="hh-ml">Notes</span><span class="hh-mv">{{ $resident->household->notes ?? '—' }}</span></div>
          </div>
        </div>
      </div>
      <div class="hh-modal-footer">
        <button onclick="document.getElementById('hh-modal').classList.remove('open')" class="btn btn-outline" style="font-size:13px">Close</button>
        <a href="{{ route('households.show', $resident->household->id) }}" class="btn btn-primary" style="font-size:13px"><i class="fas fa-eye"></i> Full View</a>
      </div>
    </div>
  </div>
  <style>
  .hh-modal-backdrop { display:none; position:fixed; inset:0; background:rgba(0,0,0,.35); z-index:9999; align-items:center; justify-content:center; }
  .hh-modal-backdrop.open { display:flex; }
  .hh-modal { background:var(--card); border-radius:16px; width:560px; max-width:95vw; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.2); }
  .hh-modal-header { padding:20px 24px 16px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
  .hh-modal-body { padding:20px 24px; }
  .hh-modal-section { margin-bottom:20px; }
  .hh-modal-section:last-child { margin-bottom:0; }
  .hh-modal-section-title { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; margin-bottom:12px; padding-bottom:6px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:6px; }
  .hh-modal-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
  .hh-mi { display:flex; flex-direction:column; gap:3px; }
  .hh-ml { font-size:10px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; }
  .hh-mv { font-size:13px; color:var(--text); font-weight:500; background:var(--bg); border:1px solid var(--border); border-radius:7px; padding:7px 10px; }
  .hh-modal-footer { padding:16px 24px; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; }
  </style>
  @endif

  @if($resident->family)
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-users"></i> Family</div>
      <a href="{{ route('families.show', $resident->family->id) }}" class="btn btn-outline" style="font-size:12px;padding:5px 12px">View Family</a>
    </div>
    <div class="card-body">
      <div class="info-grid">
        <div class="info-item"><div class="label">Family Name</div><div class="value">{{ $resident->family->family_name }}</div></div>
        <div class="info-item"><div class="label">Head</div><div class="value">{{ $resident->family->head_last_name }}, {{ $resident->family->head_first_name }}{{ $resident->family->head_middle_name ? ' '.strtoupper(substr($resident->family->head_middle_name,0,1)).'.' : '' }}</div></div>
        <div class="info-item"><div class="label">Members</div><div class="value">{{ $resident->family->member_count }} member(s)</div></div>
      </div>
    </div>
  </div>
  @endif

  <!-- Sector Classifications -->
  <div class="card">
    <div class="card-header"><div class="card-title"><i class="fas fa-tags"></i> Sector Classifications</div></div>
    <div class="card-body">
      @php
        $sectors = [
          ['field' => 'is_senior',              'label' => 'Senior Citizen',       'color' => '#92400e', 'bg' => '#fef3c7'],
          ['field' => 'is_pwd',                 'label' => 'PWD',                  'color' => '#991b1b', 'bg' => '#fee2e2'],
          ['field' => 'is_voter',               'label' => 'Registered Voter',     'color' => '#6b21a8', 'bg' => '#f3e8ff'],
          ['field' => 'is_solo_parent',         'label' => 'Solo Parent',          'color' => '#065f46', 'bg' => '#d1fae5'],
          ['field' => 'is_labor_force',         'label' => 'Labor Force',          'color' => '#1e40af', 'bg' => '#dbeafe'],
          ['field' => 'is_unemployed',          'label' => 'Unemployed',           'color' => '#9a3412', 'bg' => '#ffedd5'],
          ['field' => 'is_ofw',                 'label' => 'OFW',                  'color' => '#164e63', 'bg' => '#cffafe'],
          ['field' => 'is_indigenous',          'label' => 'Indigenous Person',    'color' => '#713f12', 'bg' => '#fef9c3'],
          ['field' => 'is_out_of_school_child', 'label' => 'Out of School Child',  'color' => '#831843', 'bg' => '#fce7f3'],
          ['field' => 'is_out_of_school_youth', 'label' => 'Out of School Youth',  'color' => '#4c1d95', 'bg' => '#ede9fe'],
          ['field' => 'is_student',             'label' => 'Student',              'color' => '#134e4a', 'bg' => '#ccfbf1'],
        ];
        $active = array_filter($sectors, fn($s) => $resident->{$s['field']});
      @endphp
      @if(count($active))
        <div style="display:flex;flex-wrap:wrap;gap:8px">
          @foreach($active as $sector)
            <span class="badge" style="background:{{ $sector['bg'] }};color:{{ $sector['color'] }}">{{ $sector['label'] }}</span>
          @endforeach
        </div>
      @else
        <p style="color:var(--muted);font-size:13px;margin:0">No sector classifications assigned.</p>
      @endif
    </div>
  </div>

</div>

<!-- ════════════════════════════════════════════════════ -->
<!--  RBI PRINT FORM — only visible when printing        -->
<!-- ════════════════════════════════════════════════════ -->
<div id="rbi-print">

  <div class="rbi-formid">RBI FORM B</div>
  <div class="rbi-title">Individual Record of Barangay Inhabitant</div>

  <!-- Location header -->
  <div class="rbi-top-grid">
    <div class="rbi-top-cell">
      <span class="rbi-top-lbl">REGION:</span>
      <span class="rbi-top-val">VIII (Eastern Visayas)</span>
    </div>
    <div class="rbi-top-cell">
      <span class="rbi-top-lbl">CITY / MUN.:</span>
      <span class="rbi-top-val">{{ $resident->city }}</span>
    </div>
    <div class="rbi-top-cell">
      <span class="rbi-top-lbl">PROVINCE:</span>
      <span class="rbi-top-val">{{ $resident->province }}</span>
    </div>
    <div class="rbi-top-cell">
      <span class="rbi-top-lbl">BARANGAY:</span>
      <span class="rbi-top-val">{{ $resident->barangay }}</span>
    </div>
  </div>

  <!-- Section I: Personal Information -->
  <div class="rbi-section-box">
    <div class="rbi-section-title">I. Personal Information</div>

    <!-- Name -->
    <div class="rbi-field-row">
      <span class="rbi-field-lbl">NAME:</span>
      <div class="rbi-name-cols">
        <div class="rbi-named-col">
          <div class="rbi-underline">{{ $resident->last_name }}</div>
          <div class="rbi-col-sub">Last Name</div>
        </div>
        <div class="rbi-named-col">
          <div class="rbi-underline">{{ $resident->first_name }}</div>
          <div class="rbi-col-sub">First Name</div>
        </div>
        <div class="rbi-named-col">
          <div class="rbi-underline">{{ $resident->middle_name ?? '' }}</div>
          <div class="rbi-col-sub">Middle Name</div>
        </div>
        <div class="rbi-named-col">
          <div class="rbi-underline">{{ $resident->suffix ?? '' }}</div>
          <div class="rbi-col-sub">Ext.</div>
        </div>
      </div>
    </div>

    <!-- Date of Birth + Age -->
    <div class="rbi-field-row">
      <span class="rbi-field-lbl">DATE OF BIRTH:</span>
      <div class="rbi-dob-cols">
        <div class="rbi-dob-box">
          <div class="rbi-underline" style="width:40px;text-align:center">{{ $dobMM }}</div>
          <div class="rbi-col-sub" style="width:40px">MM</div>
        </div>
        <div class="rbi-dob-box">
          <div class="rbi-underline" style="width:40px;text-align:center">{{ $dobDD }}</div>
          <div class="rbi-col-sub" style="width:40px">DD</div>
        </div>
        <div class="rbi-dob-box">
          <div class="rbi-underline" style="width:65px;text-align:center">{{ $dobYYYY }}</div>
          <div class="rbi-col-sub" style="width:65px">YYYY</div>
        </div>
      </div>
      <span class="rbi-field-lbl" style="margin-left:24px">AGE:</span>
      <div class="rbi-underline sm">{{ $resident->age }}</div>
    </div>

    <!-- Sex & Civil Status -->
    <div class="rbi-field-row" style="align-items:flex-start;gap:0">
      <div style="display:flex;align-items:center;gap:10px;flex:1">
        <span class="rbi-field-lbl">SEX:</span>
        <div class="rbi-check-row">
          <span class="rbi-check-item">
            <span class="rbi-checkbox {{ $resident->gender === 'Male' ? 'checked' : '' }}"></span> Male
          </span>
          <span class="rbi-check-item">
            <span class="rbi-checkbox {{ $resident->gender === 'Female' ? 'checked' : '' }}"></span> Female
          </span>
          <span class="rbi-check-item">
            <span class="rbi-checkbox {{ $resident->gender === 'Other' ? 'checked' : '' }}"></span> Other
          </span>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:10px;flex:2">
        <span class="rbi-field-lbl">CIVIL STATUS:</span>
        <div class="rbi-check-row">
          <span class="rbi-check-item">
            <span class="rbi-checkbox {{ $cs === 'single' ? 'checked' : '' }}"></span> Single
          </span>
          <span class="rbi-check-item">
            <span class="rbi-checkbox {{ $cs === 'married' ? 'checked' : '' }}"></span> Married
          </span>
          <span class="rbi-check-item">
            <span class="rbi-checkbox {{ in_array($cs, ['widow','widower','widow/er']) ? 'checked' : '' }}"></span> Widow/er
          </span>
          <span class="rbi-check-item">
            <span class="rbi-checkbox {{ $cs === 'separated' ? 'checked' : '' }}"></span> Separated
          </span>
        </div>
      </div>
    </div>

    <!-- Citizenship & Religion -->
    <div class="rbi-field-row">
      <span class="rbi-field-lbl">CITIZENSHIP:</span>
      <div class="rbi-underline md">{{ $resident->nationality ?? '' }}</div>
      <span class="rbi-field-lbl" style="margin-left:20px">RELIGION:</span>
      <div class="rbi-underline">{{ $resident->religion ?? '' }}</div>
    </div>

    <!-- Occupation -->
    <div class="rbi-field-row">
      <span class="rbi-field-lbl">OCCUPATION:</span>
      <div class="rbi-underline">{{ $resident->occupation ?? '' }}</div>
    </div>

    <!-- Employer + Monthly Income -->
    <div class="rbi-field-row">
      <span class="rbi-field-lbl">EMPLOYER:</span>
      <div class="rbi-underline">{{ $resident->employer ?? '' }}</div>
      <span class="rbi-field-lbl" style="margin-left:20px;white-space:nowrap">MONTHLY INCOME:</span>
      <div class="rbi-income-field">
        <span class="rbi-peso">₱</span>
        <div class="rbi-underline md">{{ $resident->monthly_income ? number_format($resident->monthly_income, 2) : '' }}</div>
      </div>
    </div>

    <!-- Education -->
    <div class="rbi-field-row">
      <span class="rbi-field-lbl">EDUCATION:</span>
      <div class="rbi-underline">{{ $resident->education_level ?? '' }}</div>
    </div>

    <!-- Contact + Email -->
    <div class="rbi-field-row">
      <span class="rbi-field-lbl">CONTACT NO.:</span>
      <div class="rbi-underline md">{{ $resident->contact_number ?? '' }}</div>
      <span class="rbi-field-lbl" style="margin-left:20px">EMAIL:</span>
      <div class="rbi-underline">{{ $resident->email ?? '' }}</div>
    </div>
    <div class="rbi-field-row">
      <span class="rbi-field-lbl">PHILSYS NO.:</span>
      <div class="rbi-underline md">{{ $resident->philsys_number ?? '' }}</div>
    </div>

    <!-- Address -->
    <div class="rbi-field-row" style="align-items:flex-start">
      <span class="rbi-field-lbl" style="margin-top:2px">RESIDENCE<br>ADDRESS:</span>
      <div style="flex:1;display:flex;flex-direction:column;gap:6px">
        <div style="display:flex;gap:10px">
          <div style="flex:2">
            <div class="rbi-underline">{{ $resident->address ?? '' }}</div>
            <div class="rbi-col-sub" style="text-align:left">Sitio / Purok / Street / House No.</div>
          </div>
        </div>
        <div style="display:flex;gap:10px">
          <div style="flex:1">
            <div class="rbi-underline">{{ $resident->barangay ?? '' }}</div>
            <div class="rbi-col-sub" style="text-align:left">Barangay</div>
          </div>
          <div style="flex:1">
            <div class="rbi-underline">{{ $resident->city ?? '' }}</div>
            <div class="rbi-col-sub" style="text-align:left">City / Municipality</div>
          </div>
          <div style="flex:1">
            <div class="rbi-underline">{{ $resident->province ?? '' }}</div>
            <div class="rbi-col-sub" style="text-align:left">Province</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Section II: Special Classifications -->
  <div class="rbi-section-box">
    <div class="rbi-section-title">II. Special Classifications</div>
    <div class="rbi-special-checks">
      <span class="rbi-check-item">
        <span class="rbi-checkbox {{ $resident->is_senior ? 'checked' : '' }}"></span> Senior Citizen (60+)
      </span>
      <span class="rbi-check-item">
        <span class="rbi-checkbox {{ $resident->is_pwd ? 'checked' : '' }}"></span> Person with Disability (PWD)
      </span>
      <span class="rbi-check-item">
        <span class="rbi-checkbox {{ $resident->is_voter ? 'checked' : '' }}"></span> Registered Voter
      </span>
      <span class="rbi-check-item">
        <span class="rbi-checkbox {{ $resident->is_solo_parent ? 'checked' : '' }}"></span> Solo Parent
      </span>
      <span class="rbi-check-item">
        <span class="rbi-checkbox {{ $resident->is_deceased ? 'checked' : '' }}"></span> Deceased
        @if($resident->is_deceased && $resident->date_of_death)
          <span style="margin-left:6px;font-size:8.5pt">({{ \Carbon\Carbon::parse($resident->date_of_death)->format('M d, Y') }})</span>
        @endif
      </span>
      @if($resident->transferred_to)
      <span class="rbi-check-item" style="grid-column:span 2">
        <span class="rbi-checkbox checked"></span> Transferred
        <span style="margin-left:6px;font-size:8.5pt">to <strong>{{ $resident->transferred_to }}</strong></span>
      </span>
      @endif
    </div>
  </div>

  <!-- Certification + Thumbprints -->
  <div class="rbi-section-box">
    <p class="rbi-cert-text">I hereby certify that the above information is true and correct to the best of my knowledge.</p>

    <div class="rbi-sign-row">
      <!-- Left: Date + Signature -->
      <div>
        <div style="display:flex;flex-direction:column;gap:18px">
          <div>
            <div class="rbi-underline">{{ $today }}</div>
            <div class="rbi-sign-label">Date Accomplished</div>
          </div>
          <div>
            <div class="rbi-underline"> </div>
            <div class="rbi-sign-label">Signature of Resident / Person Accomplishing the Form</div>
          </div>
        </div>
      </div>
      <!-- Right: Thumbprints -->
      <div>
        <div style="font-size:8pt;text-align:center;margin-bottom:6px">Thumbprints</div>
        <div class="rbi-thumb-area">
          <div class="rbi-thumb-box">
            <div class="rbi-thumb-rect"></div>
            <div class="rbi-thumb-lbl">Left Thumbmark</div>
          </div>
          <div class="rbi-thumb-box">
            <div class="rbi-thumb-rect"></div>
            <div class="rbi-thumb-lbl">Right Thumbmark</div>
          </div>
        </div>
      </div>
    </div>

    <hr class="rbi-divider">

    <div class="rbi-attest-row">
      <span style="font-size:8.5pt;font-weight:bold">Attested by:</span>
      <div style="flex:1">
        <div class="rbi-underline"> </div>
        <div class="rbi-sign-label">Barangay Secretary</div>
      </div>
      <div style="display:flex;flex-direction:column;align-items:center">
        <div class="rbi-hh-box">{{ $hhNo }}</div>
        <div style="font-size:7.5pt;margin-top:3px;text-align:center">Household Number</div>
      </div>
    </div>
  </div>

  <div class="rbi-note">Note: The Household No. shall be filled up by the Barangay Secretary. &nbsp;|&nbsp; Resident ID #{{ $resident->id }} &nbsp;|&nbsp; Printed: {{ $today }}</div>

</div>

@if(request('print') == '1')
<script>window.addEventListener('load', function() { window.print(); });</script>
@endif
@endsection
