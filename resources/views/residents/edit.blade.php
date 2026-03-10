@extends('layouts.app')

@section('content')
<style>

.bidb-wrap { background:var(--bg); min-height:100vh; padding:28px; }
.page-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.page-hdr h1 { font-size:22px; font-weight:700; color:var(--primary); margin:0; }
.breadcrumb { font-size:13px; color:var(--muted); margin-top:2px; }
.breadcrumb span { color:var(--primary); font-weight:500; }
.card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden; }
.card-header { padding:16px 20px; border-bottom:1px solid var(--border); }
.card-title { font-weight:700; color:var(--primary); font-size:14px; }
.card-body { padding:24px; }
.form-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
.form-group { display:flex; flex-direction:column; gap:5px; }
.form-group.full { grid-column:span 3; }
label { font-size:12px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; }
input, select, textarea { padding:9px 12px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; font-family:inherit; color:var(--text); outline:none; background:#fff; width:100%; box-sizing:border-box; }
input:focus, select:focus, textarea:focus { border-color:var(--primary); }
.btn { display:inline-flex; align-items:center; gap:6px; padding:10px 20px; border-radius:8px; border:none; cursor:pointer; font-family:inherit; font-size:14px; font-weight:600; text-decoration:none; }
.btn-primary { background:var(--primary); color:#fff; }
.btn-outline { background:#fff; color:var(--primary); border:1.5px solid var(--primary); }
.alert-error { background:#fee2e2; border:1px solid #fecaca; color:#991b1b; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px; }
</style>

<div class="bidb-wrap">
  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-user-edit" style="margin-right:8px"></i>Edit Resident</h1>
      <div class="breadcrumb">Home › <a href="{{ route('residents.index') }}" style="color:var(--primary);text-decoration:none">Residents</a> › <span>Edit</span></div>
    </div>
    <a href="{{ route('residents.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
  </div>

  @if($errors->any())
    <div class="alert-error">
      <strong>Please fix the following errors:</strong>
      <ul style="margin:8px 0 0 20px">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
      </ul>
    </div>
  @endif

  @if(auth()->user()->role !== 'admin')
  <div style="background:#eff6ff;border:1.5px solid #bfdbfe;color:#1e40af;padding:14px 18px;border-radius:10px;margin-bottom:20px;font-size:13px;display:flex;align-items:flex-start;gap:10px">
    <i class="fas fa-info-circle" style="margin-top:2px;font-size:15px;flex-shrink:0"></i>
    <div>
      <strong>Edit Request Mode:</strong> Your changes will be submitted for admin verification.
      The current resident record will <strong>not change</strong> until an admin approves your proposed edits.
    </div>
  </div>
  @endif

  <form method="POST" action="{{ route('residents.update', $resident->id) }}">
    @csrf
    @method('PUT')

    <div class="card">
      <div class="card-header"><div class="card-title"><i class="fas fa-id-card" style="margin-right:6px"></i>Personal Information</div></div>
      <div class="card-body">
        <div class="form-grid">
          <div class="form-group">
            <label>Last Name *</label>
            <input type="text" name="last_name" value="{{ old('last_name', $resident->last_name) }}" required>
          </div>
          <div class="form-group">
            <label>First Name *</label>
            <input type="text" name="first_name" value="{{ old('first_name', $resident->first_name) }}" required>
          </div>
          <div class="form-group">
            <label>Middle Name</label>
            <input type="text" name="middle_name" value="{{ old('middle_name', $resident->middle_name) }}">
          </div>
          <div class="form-group">
            <label>Sex *</label>
            <select name="gender" required>
              <option value="">Select...</option>
              <option value="Male" {{ $resident->gender == 'Male' ? 'selected' : '' }}>Male</option>
              <option value="Female" {{ $resident->gender == 'Female' ? 'selected' : '' }}>Female</option>
              <option value="Other" {{ $resident->gender == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
          </div>
          <div class="form-group">
            <label>Date of Birth *</label>
            <input type="date" name="birthdate" id="birthdate" value="{{ old('birthdate', $resident->birthdate) }}" required>
          </div>
          <input type="hidden" name="age" id="age" value="{{ old('age', $resident->age) }}">
          <div class="form-group">
            <label>Civil Status</label>
            <select name="civil_status">
              <option value="">Select...</option>
              @foreach(['Single','Married','Widowed','Separated','Annulled','Live-in'] as $cs)
                <option value="{{ $cs }}" {{ $resident->civil_status == $cs ? 'selected' : '' }}>{{ $cs }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Nationality</label>
            <input type="text" name="nationality" value="{{ old('nationality', $resident->nationality) }}">
          </div>
          <div class="form-group">
            <label>Religion</label>
            <input type="text" name="religion" value="{{ old('religion', $resident->religion) }}">
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><div class="card-title"><i class="fas fa-phone" style="margin-right:6px"></i>Contact Information</div></div>
      <div class="card-body">
        <div class="form-grid">
          <div class="form-group">
            <label>Contact Number</label>
            <input type="text" name="contact_number" value="{{ old('contact_number', $resident->contact_number) }}" placeholder="09xxxxxxxxx">
          </div>
          <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" value="{{ old('email', $resident->email) }}">
          </div>
          <div class="form-group">
            <label>PhilSys Card Number</label>
            <input type="text" name="philsys_number" value="{{ old('philsys_number', $resident->philsys_number) }}" placeholder="e.g. 1234-5678-9012">
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><div class="card-title"><i class="fas fa-map-marker-alt" style="margin-right:6px"></i>Address</div></div>
      <div class="card-body">
        @php
          // Try to detect sitio from stored address for pre-selection
          $storedAddress = $resident->address ?? '';
          $detectedSitio = '';
          foreach ($sitios as $s) {
              if (stripos($storedAddress, $s) === 0) { $detectedSitio = $s; break; }
          }
          $oldSitio  = old('sitio_name', $detectedSitio);
          $oldStreet = old('street_no', $detectedSitio ? trim(ltrim(substr($storedAddress, strlen($detectedSitio)), ', ')) : $storedAddress);
        @endphp
        <div class="form-grid">
          <div class="form-group">
            <label>Province</label>
            <input type="text" value="Leyte" disabled style="background:#f1f5f9;color:#64748b;cursor:not-allowed;">
            <input type="hidden" name="province" value="Leyte">
          </div>
          <div class="form-group">
            <label>City / Municipality</label>
            <input type="text" value="Ormoc City" disabled style="background:#f1f5f9;color:#64748b;cursor:not-allowed;">
            <input type="hidden" name="city" value="Ormoc City">
          </div>
          <div class="form-group">
            <label>Barangay</label>
            <input type="text" value="Cogon" disabled style="background:#f1f5f9;color:#64748b;cursor:not-allowed;">
            <input type="hidden" name="barangay" value="Cogon">
          </div>
          <div class="form-group">
            <label>Purok *</label>
            <select name="sitio_name" required>
              <option value="">Select purok...</option>
              @foreach($sitios as $sitio)
                <option value="{{ $sitio }}" {{ $oldSitio === $sitio ? 'selected' : '' }}>{{ $sitio }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Sitio</label>
            <input type="text" name="purok" value="{{ old('purok') }}" placeholder="e.g. Sampaguita">
          </div>
          <div class="form-group full">
            <label>Street / House No.</label>
            <input type="text" name="street_no" value="{{ $oldStreet }}" placeholder="e.g. 123 Rizal St.">
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header"><div class="card-title"><i class="fas fa-briefcase" style="margin-right:6px"></i>Socio-Economic Information</div></div>
      <div class="card-body">
        <div class="form-grid">
          <div class="form-group">
            <label>Occupation</label>
            <input type="text" name="occupation" value="{{ old('occupation', $resident->occupation) }}">
          </div>
          <div class="form-group">
            <label>Employer / Workplace</label>
            <input type="text" name="employer" value="{{ old('employer', $resident->employer) }}">
          </div>
          <div class="form-group">
            <label>Monthly Income</label>
            <input type="number" name="monthly_income" value="{{ old('monthly_income', $resident->monthly_income) }}" min="0" step="0.01">
          </div>
          <div class="form-group">
            <label>Education Level</label>
            <select name="education_level">
              <option value="">Select...</option>
              @foreach(['No Formal Education','Elementary','High School','Senior High School','Vocational','College','Post-Graduate'] as $ed)
                <option value="{{ $ed }}" {{ $resident->education_level == $ed ? 'selected' : '' }}>{{ $ed }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>

<div class="card">
  <div class="card-header"><div class="card-title"><i class="fas fa-tags" style="margin-right:6px"></i>Special Classifications</div></div>
  <div class="card-body">
    <div style="display:flex;gap:32px;flex-wrap:wrap">
      <label style="display:flex;align-items:center;gap:8px;font-size:14px;text-transform:none;letter-spacing:0;cursor:pointer;font-weight:500">
        <input type="checkbox" name="is_senior" id="is_senior" value="1" {{ $resident->is_senior ? 'checked' : '' }} style="width:16px;height:16px;padding:0;margin:0">
        Senior Citizen (60+)
      </label>
      <label style="display:flex;align-items:center;gap:8px;font-size:14px;text-transform:none;letter-spacing:0;cursor:pointer;font-weight:500">
        <input type="checkbox" name="is_pwd" value="1" {{ $resident->is_pwd ? 'checked' : '' }} style="width:16px;height:16px;padding:0;margin:0">
        Person with Disability (PWD)
      </label>
      <label style="display:flex;align-items:center;gap:8px;font-size:14px;text-transform:none;letter-spacing:0;cursor:pointer;font-weight:500">
        <input type="checkbox" name="is_voter" value="1" {{ $resident->is_voter ? 'checked' : '' }} style="width:16px;height:16px;padding:0;margin:0">
        Registered Voter
      </label>
    </div>
  </div>
</div>

<!-- Deceased Status -->
<div class="card" style="border:1.5px solid #fecdd3">
  <div class="card-header" style="background:#fff1f2"><div class="card-title" style="color:#be123c"><i class="fas fa-cross" style="margin-right:6px"></i>Deceased Status</div></div>
  <div class="card-body">
    <div style="display:flex;gap:32px;flex-wrap:wrap;align-items:center">
      <label style="display:flex;align-items:center;gap:8px;font-size:14px;text-transform:none;letter-spacing:0;cursor:pointer;font-weight:500">
        <input type="checkbox" name="is_deceased" value="1" id="isDeceased" {{ $resident->is_deceased ? 'checked' : '' }} style="width:16px;height:16px;padding:0;margin:0">
        Mark as Deceased
      </label>
      <div id="deathDateField" style="{{ $resident->is_deceased ? '' : 'display:none' }}">
        <div style="display:flex;flex-direction:column;gap:5px">
          <label style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.06em">Date of Death</label>
          <input type="date" name="date_of_death" value="{{ $resident->date_of_death }}" style="padding:9px 12px;border:1.5px solid #fecdd3;border-radius:8px;font-size:14px;font-family:inherit;width:200px">
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('isDeceased').addEventListener('change', function() {
  document.getElementById('deathDateField').style.display = this.checked ? '' : 'none';
});
</script>

    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
      <a href="{{ route('residents.index') }}" class="btn btn-outline">Cancel</a>
      @if(auth()->user()->role === 'admin')
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
      @else
        <button type="submit" class="btn btn-primary" style="background:#2563eb"><i class="fas fa-paper-plane"></i> Submit for Verification</button>
      @endif
    </div>

  </form>
</div>
<script>
function updateSeniorCheckbox(age) {
  const cb = document.getElementById('is_senior');
  const label = cb.closest('label');
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

// Run on page load
updateSeniorCheckbox({{ $resident->age ?? 0 }});
</script>
@endsection