@extends('layouts.app')

@section('content')
<style>

.bidb-wrap { background:var(--bg); min-height:100vh; padding:28px; }
.page-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.page-hdr h1 { font-size:22px; font-weight:700; color:var(--primary); margin:0; }
.breadcrumb { font-size:13px; color:var(--muted); margin-top:2px; }
.breadcrumb a { color:var(--primary); text-decoration:none; }
.breadcrumb span { color:var(--primary); font-weight:500; }
.card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden; }
.card-header { padding:16px 20px; border-bottom:1px solid var(--border); background:#f8fafc; }
.card-title { font-weight:700; color:var(--primary); font-size:14px; display:flex; align-items:center; gap:8px; }
.card-body { padding:24px; }
.form-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
.form-group { display:flex; flex-direction:column; gap:5px; }
.form-group.full { grid-column:span 3; }
.form-group.half { grid-column:span 2; }
label { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; }
label .req { color:#dc2626; margin-left:2px; }
input, select, textarea { padding:9px 12px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; font-family:inherit; color:var(--text); outline:none; transition:border-color .15s; background:#fff; width:100%; box-sizing:border-box; }
input:focus, select:focus, textarea:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(26,58,107,.08); }
input::placeholder { color:#94a3b8; }
.check-group { display:flex; gap:24px; flex-wrap:wrap; }
.check-item { display:flex; align-items:center; gap:8px; cursor:pointer; }
.check-item input[type=checkbox] { width:16px; height:16px; padding:0; margin:0; cursor:pointer; accent-color:var(--primary); }
.check-item span { font-size:14px; font-weight:500; color:var(--text); }
.btn { display:inline-flex; align-items:center; gap:6px; padding:10px 20px; border-radius:8px; border:none; cursor:pointer; font-family:inherit; font-size:14px; font-weight:600; transition:all .15s; text-decoration:none; }
.btn-primary { background:var(--primary); color:#fff; }
.btn-primary:hover { background:var(--primary-light); }
.btn-outline { background:#fff; color:var(--primary); border:1.5px solid var(--primary); }
.btn-outline:hover { background:#f0f4f8; }
.alert-error { background:#fee2e2; border:1px solid #fecaca; color:#991b1b; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px; }
.alert-success { background:#dcfce7; border:1px solid #bbf7d0; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px; display:flex; align-items:center; gap:8px; }
.map-hint { font-size:12px; color:var(--muted); margin-bottom:8px; display:flex; align-items:center; gap:6px; }
#map { height:320px; border-radius:10px; border:1.5px solid var(--border); margin-top:4px; }
/* Household suggestion panel */
.hh-suggestion { margin-top:16px; display:none; }
.hh-suggestion-label { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; margin-bottom:8px; display:flex; align-items:center; gap:6px; }
.hh-list { display:flex; flex-direction:column; gap:8px; }
.hh-card { border:1.5px solid var(--border); border-radius:10px; padding:12px 14px; cursor:pointer; transition:all .15s; background:#fff; display:flex; align-items:center; justify-content:space-between; gap:12px; }
.hh-card:hover { border-color:var(--primary); background:#eff6ff; }
.hh-card.selected { border-color:var(--primary); background:#eff6ff; box-shadow:0 0 0 3px rgba(26,58,107,.08); }
.hh-card-info { display:flex; flex-direction:column; gap:2px; }
.hh-card-title { font-size:13px; font-weight:700; color:var(--primary); }
.hh-card-sub { font-size:12px; color:var(--muted); }
.hh-card-badge { font-size:11px; font-weight:600; background:#e0e7ff; color:#3730a3; padding:3px 8px; border-radius:20px; white-space:nowrap; }
.hh-card.selected .hh-card-badge { background:var(--primary); color:#fff; }
.hh-none { font-size:13px; color:var(--muted); padding:10px 0; display:flex; align-items:center; gap:6px; }
.hh-loading { font-size:13px; color:var(--muted); padding:8px 0; display:flex; align-items:center; gap:6px; }
[data-theme="dark"] .hh-card { background:var(--card); border-color:var(--border); }
[data-theme="dark"] .hh-card:hover { background:var(--hover-bg); border-color:var(--primary); }
[data-theme="dark"] .hh-card.selected { background:rgba(91,141,238,.12); border-color:var(--primary); }
[data-theme="dark"] .hh-card-badge { background:rgba(91,141,238,.15); color:#93bbf7; }
</style>

<div class="bidb-wrap">

  <!-- Page Header -->
  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-user-plus"></i> Add New Resident</h1>
      <div class="breadcrumb">Home › <a href="{{ route('residents.index') }}">Residents</a> › <span>Add New</span></div>
    </div>
    <a href="{{ route('residents.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
  </div>

  @if(session('success'))
    <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif

  @if($errors->any())
    <div class="alert-error">
      <strong><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</strong>
      <ul style="margin:8px 0 0 20px">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('residents.store') }}">
    @csrf

  <!-- Personal Information -->
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-id-card"></i> Personal Information</div>
    </div>
    <div class="card-body">
      <div class="form-grid">
        <div class="form-group">
          <label>Last Name <span class="req">*</span></label>
          <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="e.g. Dela Cruz" required>
        </div>
        <div class="form-group">
          <label>First Name <span class="req">*</span></label>
          <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="e.g. Juan" required>
        </div>
        <div class="form-group">
          <label>Middle Name</label>
          <input type="text" name="middle_name" value="{{ old('middle_name') }}" placeholder="e.g. Santos">
        </div>
        <div class="form-group">
          <label>Suffix</label>
          <input type="text" name="suffix" value="{{ old('suffix') }}" placeholder="e.g. Jr., Sr., II">
        </div>
        <div class="form-group">
          <label>Sex <span class="req">*</span></label>
          <select name="gender" required>
            <option value="">Select...</option>
            <option value="Male"   {{ old('gender')=='Male'   ? 'selected':'' }}>Male</option>
            <option value="Female" {{ old('gender')=='Female' ? 'selected':'' }}>Female</option>
            <option value="Other"  {{ old('gender')=='Other'  ? 'selected':'' }}>Other</option>
          </select>
        </div>
        <div class="form-group">
          <label>Date of Birth <span class="req">*</span></label>
          <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate') }}" required>
        </div>
        <input type="hidden" name="age" id="age" value="{{ old('age') }}">
        
        <div class="form-group">
          <label>Place of Birth</label>
          <input type="text" name="place_of_birth" value="{{ old('place_of_birth') }}" placeholder="e.g. Ormoc City, Leyte">
        </div>

        <div class="form-group">
          <label>Civil Status</label>
          <select name="civil_status">
            <option value="">Select...</option>
            @foreach(['Single','Married','Widowed','Separated','Annulled','Common Law (Live-in)','Divorced'] as $cs)
              <option value="{{ $cs }}" {{ old('civil_status')==$cs ? 'selected':'' }}>{{ $cs }}</option>
            @endforeach
          </select>
        </div>

        
        <div class="form-group">
          <label>Citizenship</label>
          <select name="nationality">
            <option value="">Select...</option>
            @foreach(['Filipino', 'Foreigner'] as $cs)
              <option value="{{ $cs }}" {{ old('nationality')==$cs ? 'selected':'' }}>{{ $cs }}</option>
            @endforeach
          </select>
        </div>

        <div class="form-group">
          <label>Inhabitant</label>
          <select name="resident_type">
            <option value="">Select...</option>
            @foreach(['Migrant','Non-Migrant','Transient'] as $rt)
              <option value="{{ $rt }}" {{ old('resident_type')==$rt ? 'selected':'' }}>{{ $rt }}</option>
            @endforeach
          </select>
        </div>

        @php
          $religionOptions = [
            'Roman Catholic',
            'Islam',
            'Iglesia ni Cristo',
            "Jehovah's Witness",
            'Seventh-Day Adventist Church',
            'The Church of Jesus Christ of Latter-day Saints (Mormons)',
            'Baptist Church',
            'Born Again Christians',
            'Philippine Independent Church (Aglipayan)',
            'United Church of Christ in the Philippines (UCCP)',
            'United Methodist Church',
            'Episcopal Church in the Philippines',
            'Ang Dating Daan',
            'Bread of Life Ministries',
            'Lutheran Church in the Philippines',
          ];
          $oldReligion   = old('religion');
          $isOtherCreate = $oldReligion && !in_array($oldReligion, $religionOptions);
        @endphp
        <div class="form-group">
          <label>Religion</label>
          <input type="hidden" name="religion" id="religion_value" value="{{ $oldReligion }}">
          <select id="religion_select" onchange="handleReligionChange(this)">
            <option value="">Select...</option>
            @foreach($religionOptions as $rel)
              <option value="{{ $rel }}" {{ (!$isOtherCreate && $oldReligion === $rel) ? 'selected' : '' }}>{{ $rel }}</option>
            @endforeach
            <option value="Others" {{ $isOtherCreate ? 'selected' : '' }}>Others</option>
          </select>
          <input type="text" id="religion_other" placeholder="Please specify religion"
            value="{{ $isOtherCreate ? $oldReligion : '' }}"
            style="{{ $isOtherCreate ? '' : 'display:none;' }} margin-top:6px;"
            oninput="document.getElementById('religion_value').value = this.value">
        </div>
      </div>
    </div>
  </div>

    <!-- Contact Information -->
    <div class="card">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-phone"></i> Contact Information</div>
      </div>
      <div class="card-body">
        <div class="form-grid">
          <div class="form-group">
            <label>Contact Number</label>
            <input type="text" name="contact_number" value="{{ old('contact_number') }}" placeholder="e.g. 09xx-xxx-xxxx">
          </div>
          <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="e.g. juan@email.com">
          </div>
          <div class="form-group">
            <label>PhilSys Card Number</label>
            <input type="text" name="philsys_number" value="{{ old('philsys_number') }}" placeholder="e.g. 1234-5678-9012">
          </div>
        </div>
      </div>
    </div>

    <!-- Address -->
    <div class="card">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-map-marker-alt"></i> Complete Address</div>
      </div>
      <div class="card-body">
        <div class="form-grid">
          <div class="form-group">
            <label>Province</label>
            <input type="text" value="Leyte" disabled style="background:#f1f5f9;color:var(--muted);cursor:not-allowed;">
            <input type="hidden" name="province" value="Leyte">
          </div>
          <div class="form-group">
            <label>City / Municipality</label>
            <input type="text" value="Ormoc City" disabled style="background:#f1f5f9;color:var(--muted);cursor:not-allowed;">
            <input type="hidden" name="city" value="Ormoc City">
          </div>
          <div class="form-group">
            <label>Barangay</label>
            <input type="text" value="Cogon" disabled style="background:#f1f5f9;color:var(--muted);cursor:not-allowed;">
            <input type="hidden" name="barangay" value="Cogon">
          </div>
          <div class="form-group">
            <label>Purok <span class="req">*</span></label>
            <select name="sitio_name" required>
              <option value="">Select purok...</option>
              @foreach($sitios as $sitio)
                <option value="{{ $sitio }}" {{ old('sitio_name') === $sitio ? 'selected' : '' }}>{{ $sitio }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Sitio</label>
            <input type="text" name="purok" value="{{ old('purok') }}" placeholder="e.g. Sampaguita">
          </div>
          <div class="form-group full">
            <label>Street / House No.</label>
            <input type="text" name="street_no" value="{{ old('street_no') }}" placeholder="e.g. 123 Rizal St.">
          </div>
        </div>

        {{-- Household suggestion panel --}}
        <input type="hidden" name="household_id" id="household_id" value="{{ old('household_id') }}">
        <div class="hh-suggestion" id="hhSuggestion">
          <div class="hh-suggestion-label"><i class="fas fa-home"></i> Matching Households in this Barangay <span style="font-weight:400;color:#94a3b8">(optional — click to assign)</span></div>
          <div class="hh-list" id="hhList"></div>
        </div>
      </div>
    </div>

    <!-- Socio-Economic -->
    <div class="card">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-briefcase"></i> Socio-Economic Information</div>
      </div>
      <div class="card-body">
        <div class="form-grid">
          <div class="form-group">
            <label>Occupation</label>
            <input type="text" name="occupation" value="{{ old('occupation') }}" placeholder="e.g. Farmer, Student, N/A">
          </div>
          <div class="form-group">
            <label>Employer / Workplace</label>
            <input type="text" name="employer" value="{{ old('employer') }}" placeholder="e.g. DepEd">
          </div>
          <div class="form-group">
            <label>Estimated Monthly Income</label>
            <input type="number" name="monthly_income" value="{{ old('monthly_income') }}" placeholder="e.g. 15000" min="0">
          </div>
          <div class="form-group">
            <label>Highest Educational Attainment</label>
            <select name="education_level">
              <option value="">Select...</option>
              @foreach(['No Formal Education','Elementary','High School','Senior High School','Vocational','College','Post-Graduate'] as $ed)
                <option value="{{ $ed }}" {{ old('education_level')==$ed ? 'selected':'' }}>{{ $ed }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- Special Classifications -->
    <div class="card">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-tags"></i> Special Classifications <span style="font-size:11px;font-weight:400;color:var(--muted)">(Check all that apply)</span></div>
      </div>
      <div class="card-body">
        <div class="check-group">
          <label class="check-item" id="senior-label">
            <input type="checkbox" name="is_senior" id="is_senior" value="1" {{ old('is_senior') ? 'checked':'' }}>
            <span>Senior Citizen (60+)</span>
          </label>
          <label class="check-item">
            <input type="checkbox" name="is_pwd" value="1" {{ old('is_pwd') ? 'checked':'' }}>
            <span>Person with Disability (PWD)</span>
          </label>
          <label class="check-item">
            <input type="checkbox" name="is_voter" value="1" {{ old('is_voter') ? 'checked':'' }}>
            <span>Registered Voter</span>
          </label>
          <label class="check-item">
            <input type="checkbox" name="is_solo_parent" value="1" {{ old('is_solo_parent') ? 'checked':'' }}>
            <span>Solo Parent</span>
          </label>
        </div>
      </div>
    </div>

    {{-- Sector --}}
    <div class="card">
      <div class="card-header"><div class="card-title"><i class="fas fa-tags"></i> Sector</div></div>
      <div class="card-body">
        <div class="check-group">
          <label class="check-item">
            <input type="checkbox" name="is_labor_force" value="1" {{ old('is_labor_force') ? 'checked':'' }}>
            <span>Labor Force</span>
          </label>
          <label class="check-item">
            <input type="checkbox" name="is_unemployed" value="1" {{ old('is_unemployed') ? 'checked':'' }}>
            <span>Unemployed</span>
          </label>
          <label class="check-item">
            <input type="checkbox" name="is_ofw" value="1" {{ old('is_ofw') ? 'checked':'' }}>
            <span>OFW</span>
          </label>
          <label class="check-item">
            <input type="checkbox" name="is_indigenous" value="1" {{ old('is_indigenous') ? 'checked':'' }}>
            <span>Indigenous Person</span>
          </label>
          <label class="check-item">
            <input type="checkbox" name="is_out_of_school_child" value="1" {{ old('is_out_of_school_child') ? 'checked':'' }}>
            <span>Out of School Child</span>
          </label>
          <label class="check-item">
            <input type="checkbox" name="is_out_of_school_youth" value="1" {{ old('is_out_of_school_youth') ? 'checked':'' }}>
            <span>Out of School Youth</span>
          </label>
          <label class="check-item">
            <input type="checkbox" name="is_student" value="1" {{ old('is_student') ? 'checked':'' }}>
            <span>Student</span>
          </label>
        </div>
      </div>
    </div>

    <!-- Submit -->
    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
      <a href="{{ route('residents.index') }}" class="btn btn-outline">Cancel</a>
      @if(true)
      <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Resident Record</button>
      @else
      <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Submit for Verification</button>
      @endif
    </div>

  </form>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
const cogonBounds = L.latLngBounds([11.0112, 124.5982], [11.0307, 124.6100]);
const map = L.map('map', { maxBounds: cogonBounds, maxBoundsViscosity: 1.0, minZoom: 14 }).setView([11.0207, 124.6047], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
const cogonBoundary = [
  [11.030234977356443,124.60167151058135],[11.024138850617248,124.59993112167416],[11.023602921343993,124.60191038749116],
  [11.02199841553464,124.60224272719506],[11.013176512837916,124.60085297739778],[11.012933130596423,124.6015926549021],
  [11.012495960590513,124.60222183066207],[11.01147589471995,124.60307015753074],[11.012275845430722,124.60427413224107],
  [11.011819200932948,124.60627281394892],[11.012111976063025,124.60680203532627],[11.01277768276465,124.60734798244658],
  [11.013342546617736,124.60780452150698],[11.013330502643186,124.60781209246971],[11.015173877780228,124.60767381484305],
  [11.016824805731105,124.60713467157808],[11.019523490106792,124.60689925198847],[11.021487652923867,124.60695810706932],
  [11.023801055019462,124.6070467555349],[11.025545638342095,124.6096972420873],[11.026218983523677,124.60994669964413],
  [11.027841126946072,124.60905800709384],[11.02966220170373,124.608652639154],[11.030656901704731,124.6078574931883],
  [11.03023536631963,124.60167118828224]
];
L.polygon(cogonBoundary, { color: '#2563eb', weight: 2, fillColor: '#2563eb', fillOpacity: 0.05 }).addTo(map);
function isInsideCogon(lat, lng) {
  let inside = false;
  for (let i = 0, j = cogonBoundary.length - 1; i < cogonBoundary.length; j = i++) {
    const yi = cogonBoundary[i][0], xi = cogonBoundary[i][1];
    const yj = cogonBoundary[j][0], xj = cogonBoundary[j][1];
    if (((yi > lat) !== (yj > lat)) && (lng < (xj - xi) * (lat - yi) / (yj - yi) + xi)) inside = !inside;
  }
  return inside;
}

let marker;
map.on('click', function(e) {
    if (!isInsideCogon(e.latlng.lat, e.latlng.lng)) return;
    document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
    document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
    if (marker) map.removeLayer(marker);
    marker = L.marker(e.latlng).addTo(map);
});
</script>

<script>
function updateSeniorCheckbox(age) {
  const cb    = document.getElementById('is_senior');
  const label = document.getElementById('senior-label');
  if (age < 60) {
    cb.checked  = false;
    cb.disabled = true;
    label.style.opacity = '0.4';
    label.style.cursor  = 'not-allowed';
    label.title = 'Resident must be 60 or older to qualify as Senior Citizen';
  } else {
    cb.disabled = false;
    label.style.opacity = '1';
    label.style.cursor  = 'pointer';
    label.title = '';
  }
}

document.getElementById('birthdate').addEventListener('change', function() {
  const birthdate = new Date(this.value);
  const today = new Date();
  let age = today.getFullYear() - birthdate.getFullYear();
  const m = today.getMonth() - birthdate.getMonth();
  if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) age--;
  document.getElementById('age').value = age;
  updateSeniorCheckbox(age);
});

// Run on page load in case of validation bounce-back
(function() {
  const val = document.getElementById('age').value;
  if (val !== '') updateSeniorCheckbox(parseInt(val, 10));
})();
</script>

<script>
function handleReligionChange(select) {
  const otherInput = document.getElementById('religion_other');
  const hiddenVal  = document.getElementById('religion_value');
  if (select.value === 'Others') {
    otherInput.style.display = '';
    hiddenVal.value = otherInput.value;
  } else {
    otherInput.style.display = 'none';
    hiddenVal.value = select.value;
  }
}
</script>
<script>
(function() {
  const barangayInput = document.querySelector('input[name="barangay"][type="hidden"]');
  const panel         = document.getElementById('hhSuggestion');
  const list          = document.getElementById('hhList');
  const hiddenId      = document.getElementById('household_id');
  let selectedId      = '{{ old('household_id') }}';
  let debounceTimer;

  const hhMap = {};

  let rawHouseholds = [];
  let allHouseholds = [];
  let hhPage = 1;
  const HH_PER_PAGE = 10;

  function buildCards(page) {
    const total = allHouseholds.length;
    const totalPages = Math.ceil(total / HH_PER_PAGE);
    const start = (page - 1) * HH_PER_PAGE;
    const slice = allHouseholds.slice(start, start + HH_PER_PAGE);

    const cards = slice.map(h => {
      const isSelected = String(h.id) === String(selectedId);
      const sitio = h.sitio ? h.sitio : (h.street || '—');
      return `<div class="hh-card${isSelected ? ' selected' : ''}" data-id="${h.id}" onclick="selectHousehold(${h.id}, this)">
        <div class="hh-card-info">
          <div class="hh-card-title">Household #${h.household_number}</div>
          <div class="hh-card-sub">Head: ${h.head_first_name} ${h.head_last_name} &nbsp;·&nbsp; Purok: ${sitio}</div>
        </div>
        <span class="hh-card-badge">${isSelected ? '<i class="fas fa-check"></i> Assigned' : h.member_count + ' member(s)'}</span>
      </div>`;
    }).join('');

    const pagination = totalPages > 1 ? `
      <div style="display:flex;align-items:center;justify-content:space-between;margin-top:8px;font-size:12px;color:var(--muted);">
        <span>Showing ${start + 1}–${Math.min(start + HH_PER_PAGE, total)} of ${total}</span>
        <div style="display:flex;gap:4px;">
          <button type="button" onclick="hhGoPage(${page - 1})" ${page <= 1 ? 'disabled' : ''}
            style="padding:4px 10px;border:1px solid var(--border);border-radius:6px;background:none;cursor:pointer;font-size:12px;color:var(--muted);${page <= 1 ? 'opacity:.4;cursor:default;' : ''}">
            <i class="fas fa-chevron-left"></i>
          </button>
          <span style="padding:4px 8px;font-weight:600;color:var(--text);">${page} / ${totalPages}</span>
          <button type="button" onclick="hhGoPage(${page + 1})" ${page >= totalPages ? 'disabled' : ''}
            style="padding:4px 10px;border:1px solid var(--border);border-radius:6px;background:none;cursor:pointer;font-size:12px;color:var(--muted);${page >= totalPages ? 'opacity:.4;cursor:default;' : ''}">
            <i class="fas fa-chevron-right"></i>
          </button>
        </div>
      </div>` : '';

    list.innerHTML = cards + pagination;
  }

  function hhGoPage(page) {
    const totalPages = Math.ceil(allHouseholds.length / HH_PER_PAGE);
    if (page < 1 || page > totalPages) { return; }
    hhPage = page;
    buildCards(hhPage);
  }

  function applyPurokFilter() {
    const selected = sitioSelect ? sitioSelect.value.toLowerCase() : '';
    allHouseholds = selected
      ? rawHouseholds.filter(h => h.sitio && h.sitio.toLowerCase() === selected)
      : rawHouseholds;
    hhPage = 1;
    panel.style.display = 'block';
    if (allHouseholds.length === 0) {
      list.innerHTML = '<div class="hh-none"><i class="fas fa-info-circle"></i> No households found in this purok yet.</div>';
      return;
    }
    buildCards(hhPage);
  }

  function renderHouseholds(households) {
    panel.style.display = 'block';
    if (households.length === 0) {
      list.innerHTML = '<div class="hh-none"><i class="fas fa-info-circle"></i> No households found in this barangay yet.</div>';
      return;
    }
    rawHouseholds = households;
    households.forEach(h => { hhMap[h.id] = h; });
    applyPurokFilter();
  }

  function fetchHouseholds(barangay) {
    if (!barangay || barangay.trim().length < 2) {
      panel.style.display = 'none';
      return;
    }
    list.innerHTML = '<div class="hh-loading"><i class="fas fa-spinner fa-spin"></i> Looking up households…</div>';
    panel.style.display = 'block';

    fetch(`{{ route('households.search') }}?barangay=${encodeURIComponent(barangay)}`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(renderHouseholds)
    .catch(() => { list.innerHTML = '<div class="hh-none"><i class="fas fa-exclamation-circle"></i> Could not load households.</div>'; });
  }

  const sitioSelect  = document.querySelector('select[name="sitio_name"]');
  const streetInput  = document.querySelector('input[name="street_no"]');

  function autofillFromHousehold(h) {
    if (h.sitio && !sitioSelect.value) {
      // Try to match the sitio value exactly in the select options
      for (const opt of sitioSelect.options) {
        if (opt.value.toLowerCase() === h.sitio.toLowerCase()) {
          sitioSelect.value = opt.value;
          break;
        }
      }
    }
    if (h.street && !streetInput.value) {
      streetInput.value = h.street;
    }
  }

  function clearAutofill(h) {
    // Only clear if the value still matches what we auto-filled
    if (h.sitio) {
      for (const opt of sitioSelect.options) {
        if (opt.value.toLowerCase() === h.sitio.toLowerCase() && sitioSelect.value === opt.value) {
          sitioSelect.value = '';
          break;
        }
      }
    }
    if (h.street && streetInput.value === h.street) {
      streetInput.value = '';
    }
  }

  window.selectHousehold = function(id, el) {
    if (String(id) === String(selectedId)) {
      // Deselect
      const prev = hhMap[id];
      if (prev) clearAutofill(prev);
      selectedId = '';
      hiddenId.value = '';
      el.classList.remove('selected');
      el.querySelector('.hh-card-badge').textContent = el.querySelector('.hh-card-badge').textContent.replace(' Assigned', '');
    } else {
      selectedId = id;
      hiddenId.value = id;
      document.querySelectorAll('.hh-card').forEach(c => {
        c.classList.remove('selected');
        const badge = c.querySelector('.hh-card-badge');
        if (badge.innerHTML.includes('fa-check')) {
          badge.innerHTML = badge.dataset.count || badge.textContent;
        }
      });
      el.classList.add('selected');
      el.querySelector('.hh-card-badge').innerHTML = '<i class="fas fa-check"></i> Assigned';
      const h = hhMap[id];
      if (h) autofillFromHousehold(h);
    }
  };

  // Expose functions needed by inline onclick handlers to global scope
  window.hhGoPage = hhGoPage;
  window.selectHousehold = selectHousehold;

  // Re-filter household list when purok selection changes
  if (sitioSelect) {
    sitioSelect.addEventListener('change', applyPurokFilter);
  }

  // Barangay is fixed to "Cogon" — fetch households immediately on page load
  fetchHouseholds(barangayInput.value);
})();


</script>

@endsection