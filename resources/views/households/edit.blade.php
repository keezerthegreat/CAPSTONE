@extends('layouts.app')

@section('page-title', 'Edit Household')

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
.card-header { padding:16px 20px; border-bottom:1px solid var(--border); background:#f8fafc; }
.card-title { font-weight:700; color:var(--primary); font-size:14px; display:flex; align-items:center; gap:8px; }
.card-body { padding:24px; }
.form-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
.form-group { display:flex; flex-direction:column; gap:5px; }
.form-group.full { grid-column:span 3; }
.form-group.half { grid-column:span 2; }
label { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; }
label .req { color:#dc2626; margin-left:2px; }
input, select, textarea { padding:9px 12px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; font-family:inherit; color:var(--text); outline:none; background:#fff; width:100%; box-sizing:border-box; }
input:focus, select:focus, textarea:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(26,58,107,.08); }
.btn { display:inline-flex; align-items:center; gap:6px; padding:10px 20px; border-radius:8px; border:none; cursor:pointer; font-family:inherit; font-size:14px; font-weight:600; transition:all .15s; text-decoration:none; }
.btn-primary { background:var(--primary); color:#fff; }
.btn-primary:hover { background:var(--primary-light); }
.btn-outline { background:#fff; color:var(--primary); border:1.5px solid var(--primary); }
.alert-error { background:#fee2e2; border:1px solid #fecaca; color:#991b1b; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px; }
.map-hint { font-size:12px; color:var(--muted); margin-bottom:8px; display:flex; align-items:center; gap:6px; }
#map { height:320px; border-radius:10px; border:1.5px solid var(--border); margin-top:4px; }
</style>

<div class="bidb-wrap">
  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-edit" style="margin-right:8px"></i>Edit Household</h1>
      <div class="breadcrumb">Home › <a href="{{ route('households.index') }}">Households</a> › <span>Edit</span></div>
    </div>
    <a href="{{ route('households.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
  </div>

  @if($errors->any())
    <div class="alert-error">
      <strong><i class="fas fa-exclamation-circle"></i> Please fix the following errors:</strong>
      <ul style="margin:8px 0 0 20px">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('households.update', $household->id) }}">
    @csrf
    @method('PUT')

    <!-- Household Info -->
    <div class="card">
      <div class="card-header"><div class="card-title"><i class="fas fa-home"></i> Household Information</div></div>
      <div class="card-body">
        <div class="form-grid">
          <div class="form-group">
            <label>Household Number <span class="req">*</span></label>
            <input type="text" name="household_number" value="{{ old('household_number', $household->household_number) }}" required>
          </div>
          <div class="form-group">
            <label>Residency Type <span class="req">*</span></label>
            <select name="residency_type" required>
              <option value="">Select...</option>
              @foreach(['Permanent','Transient','Boarder'] as $rt)
                <option value="{{ $rt }}" {{ $household->residency_type == $rt ? 'selected':'' }}>{{ $rt }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Number of Members</label>
            <input type="number" name="member_count" value="{{ old('member_count', $household->member_count) }}" min="1">
          </div>
        </div>
      </div>
    </div>

    <!-- Household Head -->
    <div class="card">
      <div class="card-header"><div class="card-title"><i class="fas fa-user"></i> Household Head</div></div>
      <div class="card-body">
        <div class="form-grid">
          <div class="form-group">
            <label>Last Name <span class="req">*</span></label>
            <input type="text" name="head_last_name" value="{{ old('head_last_name', $household->head_last_name) }}" required>
          </div>
          <div class="form-group">
            <label>First Name <span class="req">*</span></label>
            <input type="text" name="head_first_name" value="{{ old('head_first_name', $household->head_first_name) }}" required>
          </div>
          <div class="form-group">
            <label>Middle Name</label>
            <input type="text" name="head_middle_name" value="{{ old('head_middle_name', $household->head_middle_name) }}">
          </div>
        </div>
      </div>
    </div>

    <!-- Address -->
    <div class="card">
      <div class="card-header"><div class="card-title"><i class="fas fa-map-marker-alt"></i> Address</div></div>
      <div class="card-body">
        <div class="form-grid">
          <div class="form-group">
            <label>Sitio <span class="req">*</span></label>
            <select name="sitio" required>
              <option value="">Select Sitio...</option>
              @foreach(['Chrysanthemum','Dahlia','Dama de Noche','Ilang-Ilang 1','Ilang-Ilang 2','Jasmin','Rosal','Sampaguita'] as $sitio)
                <option value="{{ $sitio }}" {{ $household->sitio == $sitio ? 'selected':'' }}>{{ $sitio }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group half">
            <label>Street / Purok</label>
            <input type="text" name="street" value="{{ old('street', $household->street) }}" placeholder="e.g. Rizal St.">
          </div>
          <div class="form-group">
            <label>Barangay</label>
            <input type="text" name="barangay" value="{{ old('barangay', $household->barangay) }}">
          </div>
          <div class="form-group">
            <label>City / Municipality</label>
            <input type="text" name="city" value="{{ old('city', $household->city) }}">
          </div>
          <div class="form-group">
            <label>Province</label>
            <input type="text" name="province" value="{{ old('province', $household->province) }}">
          </div>
          <div class="form-group full">
            <label>Notes / Remarks</label>
            <textarea name="notes" rows="2">{{ old('notes', $household->notes) }}</textarea>
          </div>
        </div>
      </div>
    </div>

    <!-- Map -->
    <div class="card">
      <div class="card-header"><div class="card-title"><i class="fas fa-map-pin"></i> Household Location <span style="font-size:11px;font-weight:400;color:var(--muted)">(Click map to update pin)</span></div></div>
      <div class="card-body">
        <div class="map-hint"><i class="fas fa-info-circle"></i> Click anywhere on the map to update the household's location pin.</div>
        <div class="form-grid" style="margin-bottom:12px">
          <div class="form-group">
            <label>Latitude</label>
            <input id="latitude" name="latitude" value="{{ old('latitude', $household->latitude) }}" placeholder="Click on map to set" readonly>
          </div>
          <div class="form-group">
            <label>Longitude</label>
            <input id="longitude" name="longitude" value="{{ old('longitude', $household->longitude) }}" placeholder="Click on map to set" readonly>
          </div>
        </div>
        <div id="map"></div>
      </div>
    </div>

    <!-- Submit -->
    <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px">
      <a href="{{ route('households.index') }}" class="btn btn-outline">Cancel</a>
      <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
    </div>

  </form>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
const lat = {{ $household->latitude ?? 11.0064 }};
const lng = {{ $household->longitude ?? 124.6076 }};
const hasPin = {{ $household->latitude ? 'true' : 'false' }};

const map = L.map('map').setView([lat, lng], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

let marker;
if (hasPin) {
  marker = L.marker([lat, lng]).addTo(map);
}

map.on('click', function(e) {
  document.getElementById('latitude').value  = e.latlng.lat.toFixed(6);
  document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
  if (marker) map.removeLayer(marker);
  marker = L.marker(e.latlng).addTo(map);
});
</script>
@endsection