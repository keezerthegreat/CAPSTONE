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
          <label>Civil Status</label>
          <select name="civil_status">
            <option value="">Select...</option>
            @foreach(['Single','Married','Widowed','Separated','Annulled','Live-in'] as $cs)
              <option value="{{ $cs }}" {{ old('civil_status')==$cs ? 'selected':'' }}>{{ $cs }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label>Nationality</label>
          <input type="text" name="nationality" value="{{ old('nationality', 'Filipino') }}">
        </div>
        <div class="form-group">
          <label>Religion</label>
          <input type="text" name="religion" value="{{ old('religion') }}" placeholder="e.g. Roman Catholic">
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
            <label>Province <span class="req">*</span></label>
            <input type="text" name="province" value="{{ old('province') }}" placeholder="e.g. Leyte" required>
          </div>
          <div class="form-group">
            <label>City / Municipality <span class="req">*</span></label>
            <input type="text" name="city" value="{{ old('city') }}" placeholder="e.g. Ormoc City" required>
          </div>
          <div class="form-group">
            <label>Barangay <span class="req">*</span></label>
            <input type="text" name="barangay" value="{{ old('barangay') }}" placeholder="e.g. Cogon" required>
          </div>
          <div class="form-group full">
            <label>House No., Street / Purok / Sitio <span class="req">*</span></label>
            <textarea name="address" rows="2" placeholder="e.g. 123 Rizal St., Purok Sampaguita" required>{{ old('address') }}</textarea>
          </div>
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
          <label class="check-item">
            <input type="checkbox" name="is_senior" value="1" {{ old('is_senior') ? 'checked':'' }}>
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
        </div>
      </div>
    </div>

    <!-- Submit -->
    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
      <a href="{{ route('residents.index') }}" class="btn btn-outline">Cancel</a>
      <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Resident Record</button>
    </div>

  </form>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
const map = L.map('map').setView([11.0064, 124.6076], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
let marker;
map.on('click', function(e) {
    document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
    document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
    if (marker) map.removeLayer(marker);
    marker = L.marker(e.latlng).addTo(map);
});
</script>

<script>
document.getElementById('birthdate').addEventListener('change', function() {
  const birthdate = new Date(this.value);
  const today = new Date();
  let age = today.getFullYear() - birthdate.getFullYear();
  const m = today.getMonth() - birthdate.getMonth();
  if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) age--;
  document.getElementById('age').value = age;
});
</script>

@endsection