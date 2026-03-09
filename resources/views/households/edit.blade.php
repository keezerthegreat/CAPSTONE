@extends('layouts.app')

@section('page-title', 'Edit Household')

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
input, select, textarea { padding:9px 12px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; font-family:inherit; color:var(--text); outline:none; background:#fff; width:100%; box-sizing:border-box; }
input:focus, select:focus, textarea:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(26,58,107,.08); }
.btn { display:inline-flex; align-items:center; gap:6px; padding:10px 20px; border-radius:8px; border:none; cursor:pointer; font-family:inherit; font-size:14px; font-weight:600; transition:all .15s; text-decoration:none; }
.btn-primary { background:var(--primary); color:#fff; }
.btn-primary:hover { background:var(--primary-light); }
.btn-outline { background:#fff; color:var(--primary); border:1.5px solid var(--primary); }
.alert-error { background:#fee2e2; border:1px solid #fecaca; color:#991b1b; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px; }
.map-hint { font-size:12px; color:var(--muted); margin-bottom:8px; display:flex; align-items:center; gap:6px; }
#map { height:320px; border-radius:10px; border:1.5px solid var(--border); margin-top:4px; }
/* Resident search widget */
.res-search-wrap { position:relative; }
.res-dropdown { position:absolute; top:100%; left:0; right:0; background:#fff; border:1.5px solid var(--primary); border-top:none; border-radius:0 0 8px 8px; max-height:220px; overflow-y:auto; z-index:100; display:none; box-shadow:0 4px 12px rgba(0,0,0,.1); }
.res-dropdown.open { display:block; }
.res-option { padding:9px 14px; cursor:pointer; font-size:14px; color:var(--text); border-bottom:1px solid var(--border); }
.res-option:last-child { border-bottom:none; }
.res-option:hover { background:#eff6ff; }
.res-selected { display:none; align-items:center; gap:8px; margin-top:8px; padding:8px 12px; background:#f0f9ff; border:1.5px solid #bae6fd; border-radius:8px; font-size:13px; color:#0369a1; }
.res-selected.show { display:flex; }
.res-clear { background:none; border:none; cursor:pointer; color:#64748b; font-size:18px; line-height:1; margin-left:auto; padding:0; }
/* Members list */
.member-list { display:flex; flex-direction:column; gap:6px; margin-top:10px; }
.member-item { display:flex; align-items:center; gap:10px; padding:7px 12px; background:#f8fafc; border:1px solid var(--border); border-radius:8px; font-size:13px; }
.member-item .m-name { flex:1; font-weight:600; color:var(--text); }
.member-item .m-remove { background:none; border:none; cursor:pointer; color:#94a3b8; font-size:16px; padding:0; line-height:1; transition:color .15s; }
.member-item .m-remove:hover { color:#be123c; }
.member-empty { font-size:13px; color:var(--muted); font-style:italic; padding:6px 0; }
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
          <div class="form-group half">
            <label>Household Number <span class="req">*</span></label>
            <input type="text" name="household_number" value="{{ old('household_number', $household->household_number) }}" required>
          </div>
          <div class="form-group">
            <label>Classification <span class="req">*</span></label>
            <select name="residency_type" required>
              <option value="">Select...</option>
              @foreach(['Residential','Commercial','Rented'] as $rt)
                <option value="{{ $rt }}" {{ $household->residency_type == $rt ? 'selected':'' }}>{{ $rt }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- Household Head & Members -->
    <div class="card">
      <div class="card-header"><div class="card-title"><i class="fas fa-users"></i> Household Head &amp; Members</div></div>
      <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
          <div>
            <div class="form-group">
              <label>Household Head <span class="req">*</span></label>
              <div class="res-search-wrap">
                <input type="text" id="residentSearch" placeholder="Type name to search..." autocomplete="off">
                <div class="res-dropdown" id="resDropdown"></div>
              </div>
              <input type="hidden" name="head_resident_id" id="headResidentId" value="{{ old('head_resident_id', $household->head_resident_id) }}">
              <div class="res-selected {{ $household->head_resident_id ? 'show' : '' }}" id="resSelected">
                <i class="fas fa-user-check"></i>
                <span id="resSelectedName">{{ $household->head_last_name }}, {{ $household->head_first_name }}{{ $household->head_middle_name ? ' '.$household->head_middle_name : '' }}</span>
                <button type="button" class="res-clear" onclick="clearResident()" title="Clear">×</button>
              </div>
            </div>
          </div>
          <div>
            <div class="form-group" style="margin-bottom:10px">
              <label>Add Member</label>
              <div class="res-search-wrap">
                <input type="text" id="memberSearch" placeholder="Search resident to add..." autocomplete="off">
                <div class="res-dropdown" id="memberDropdown"></div>
              </div>
            </div>
            <div class="member-list" id="memberList">
              <div class="member-empty" id="memberEmpty" style="{{ $household->members->count() ? 'display:none' : '' }}">No members added yet.</div>
            </div>
            <div id="memberInputs"></div>
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
// Map
const lat    = {{ $household->latitude ?? 11.0064 }};
const lng    = {{ $household->longitude ?? 124.6076 }};
const hasPin = {{ $household->latitude ? 'true' : 'false' }};

const map = L.map('map').setView([lat, lng], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

let marker;
if (hasPin) marker = L.marker([lat, lng]).addTo(map);

map.on('click', function(e) {
  document.getElementById('latitude').value  = e.latlng.lat.toFixed(6);
  document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
  if (marker) map.removeLayer(marker);
  marker = L.marker(e.latlng).addTo(map);
});

// Resident data
const residents = @json($residents);

// ── Pre-load existing members ──────────────────────────────────
const addedMembers = {};
@foreach($household->members as $m)
  addedMembers[{{ $m->id }}] = "{{ $m->last_name }}, {{ $m->first_name }}{{ $m->middle_name ? ' '.$m->middle_name : '' }}";
@endforeach

// ── Member search ──────────────────────────────────────────────
const memberSearch   = document.getElementById('memberSearch');
const memberDropdown = document.getElementById('memberDropdown');
const memberList     = document.getElementById('memberList');
const memberEmpty    = document.getElementById('memberEmpty');
const memberInputs   = document.getElementById('memberInputs');

memberSearch.addEventListener('input', function() {
  buildMemberDropdown(this.value, memberDropdown, addMember);
});
memberSearch.addEventListener('blur', () => setTimeout(() => memberDropdown.classList.remove('open'), 150));

function addMember(r) {
  memberSearch.value = '';
  memberDropdown.classList.remove('open');
  if (addedMembers[r.id]) return;
  addedMembers[r.id] = r.last_name + ', ' + r.first_name + (r.middle_name ? ' ' + r.middle_name : '');
  renderMembers();
}
function removeMember(id) {
  delete addedMembers[id];
  renderMembers();
}
function renderMembers() {
  const ids = Object.keys(addedMembers);
  memberEmpty.style.display = ids.length ? 'none' : '';
  memberList.querySelectorAll('.member-item').forEach(el => el.remove());
  memberInputs.innerHTML = '';
  ids.forEach(id => {
    const item = document.createElement('div');
    item.className = 'member-item';
    item.innerHTML = `<i class="fas fa-user" style="color:var(--muted)"></i>
      <span class="m-name">${addedMembers[id]}</span>
      <button type="button" class="m-remove" onclick="removeMember(${id})">×</button>`;
    memberList.appendChild(item);
    const inp = document.createElement('input');
    inp.type = 'hidden'; inp.name = 'members[]'; inp.value = id;
    memberInputs.appendChild(inp);
  });
}
function buildMemberDropdown(query, dropdownEl, onSelect) {
  dropdownEl.innerHTML = '';
  const q = query.toLowerCase().trim();
  if (!q) { dropdownEl.classList.remove('open'); return; }
  const matches = residents.filter(r =>
    (r.last_name + ' ' + r.first_name + ' ' + (r.middle_name || '')).toLowerCase().includes(q)
  ).slice(0, 10);
  if (!matches.length) {
    dropdownEl.innerHTML = '<div class="res-option" style="color:#94a3b8;cursor:default">No residents found</div>';
  } else {
    matches.forEach(r => {
      const div = document.createElement('div');
      div.className = 'res-option';
      div.textContent = r.last_name + ', ' + r.first_name + (r.middle_name ? ' ' + r.middle_name : '');
      div.addEventListener('mousedown', (e) => { e.preventDefault(); onSelect(r); });
      dropdownEl.appendChild(div);
    });
  }
  dropdownEl.classList.add('open');
}

// Render pre-loaded members
renderMembers();

// ── Head search ──────────────────────────────────────────────
const searchInput  = document.getElementById('residentSearch');
const dropdown     = document.getElementById('resDropdown');
const hiddenInput  = document.getElementById('headResidentId');
const selectedBox  = document.getElementById('resSelected');
const selectedName = document.getElementById('resSelectedName');

searchInput.addEventListener('input', function() {
  const q = this.value.toLowerCase().trim();
  dropdown.innerHTML = '';
  if (!q) { dropdown.classList.remove('open'); return; }
  const matches = residents.filter(r =>
    (r.last_name + ' ' + r.first_name + ' ' + (r.middle_name || '')).toLowerCase().includes(q)
  ).slice(0, 10);
  if (!matches.length) {
    dropdown.innerHTML = '<div class="res-option" style="color:#94a3b8;cursor:default">No residents found</div>';
  } else {
    matches.forEach(r => {
      const div = document.createElement('div');
      div.className = 'res-option';
      div.textContent = r.last_name + ', ' + r.first_name + (r.middle_name ? ' ' + r.middle_name : '');
      div.addEventListener('mousedown', (e) => { e.preventDefault(); selectResident(r); });
      dropdown.appendChild(div);
    });
  }
  dropdown.classList.add('open');
});

searchInput.addEventListener('blur', () => setTimeout(() => dropdown.classList.remove('open'), 150));

function selectResident(r) {
  hiddenInput.value = r.id;
  searchInput.value = '';
  dropdown.classList.remove('open');
  selectedName.textContent = r.last_name + ', ' + r.first_name + (r.middle_name ? ' ' + r.middle_name : '');
  selectedBox.classList.add('show');
}

function clearResident() {
  hiddenInput.value = '';
  selectedBox.classList.remove('show');
  searchInput.value = '';
  searchInput.focus();
}
</script>
@endsection
