@extends('layouts.app')

@section('page-title', 'Household Location Map')

@section('content')
<style>
.bidb-wrap { background:var(--bg); min-height:100vh; padding:28px; }
.page-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.page-hdr h1 { font-size:22px; font-weight:700; color:var(--primary); margin:0; }
.breadcrumb { font-size:13px; color:var(--muted); margin-top:2px; }
.breadcrumb a { color:var(--primary); text-decoration:none; }
.breadcrumb span { color:var(--primary); font-weight:500; }
.card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden; }
.card-header { padding:16px 20px; border-bottom:1px solid var(--border); background:#f8fafc; display:flex; align-items:center; justify-content:space-between; }
.card-title { font-weight:700; color:var(--primary); font-size:14px; display:flex; align-items:center; gap:8px; }
.btn { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:8px; border:none; cursor:pointer; font-family:inherit; font-size:13px; font-weight:600; transition:all .15s; text-decoration:none; }
.btn-outline { background:#fff; color:var(--primary); border:1.5px solid var(--primary); }
.btn-outline:hover { background:#f0f4f8; }
.btn-sm { padding:5px 12px; font-size:12px; }
.stat-row { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:20px; }
.stat-card { background:var(--card); border-radius:12px; padding:16px 18px; border:1px solid var(--border); box-shadow:0 1px 4px rgba(0,0,0,.05); display:flex; align-items:center; gap:12px; }
.stat-icon { width:40px; height:40px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:16px; flex-shrink:0; }
.stat-icon.blue { background:#dbeafe; color:#1d4ed8; }
.stat-icon.green { background:#dcfce7; color:#16a34a; }
.stat-icon.yellow { background:#fef3c7; color:#d97706; }
.stat-info .slabel { font-size:11px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; }
.stat-info .svalue { font-size:22px; font-weight:800; color:var(--primary); }

/* Map */
#map { height:520px; width:100%; }
.map-wrap { position:relative; }
.fullscreen-btn {
  position:absolute; top:10px; right:10px; z-index:1000;
  background:#fff; border:2px solid rgba(0,0,0,.2); border-radius:6px;
  padding:6px 10px; cursor:pointer; font-size:13px; font-weight:600;
  color:var(--primary); display:flex; align-items:center; gap:6px;
  box-shadow:0 2px 6px rgba(0,0,0,.15);
}
.fullscreen-btn:hover { background:#f0f4f8; }

/* Modal */
.modal-backdrop { display:none; position:fixed; inset:0; background:rgba(0,0,0,.35); z-index:9999; align-items:center; justify-content:center; }
.modal-backdrop.open { display:flex; }
.modal { background:var(--card); border-radius:16px; width:580px; max-width:95vw; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.2); }
.modal-header { padding:20px 24px 16px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.modal-header h2 { font-size:16px; font-weight:700; color:var(--primary); margin:0; }
.modal-close { background:none; border:none; font-size:22px; color:var(--muted); cursor:pointer; line-height:1; padding:0; }
.modal-body { padding:24px; }
.modal-section { margin-bottom:20px; }
.modal-section-title { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; margin-bottom:12px; padding-bottom:6px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:6px; }
.mgrid { display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; }
.mi { display:flex; flex-direction:column; gap:3px; }
.mi .ml { font-size:10px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; }
.mi .mv { font-size:13px; color:var(--text); font-weight:500; background:var(--bg); border:1px solid var(--border); border-radius:7px; padding:7px 10px; }
.modal-footer { padding:16px 24px; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; }
.mem-table { width:100%; border-collapse:collapse; font-size:12px; margin-top:4px; }
.mem-table th { padding:7px 10px; background:var(--header-bg); text-align:left; font-size:10px; font-weight:700; text-transform:uppercase; color:var(--muted); border-bottom:1.5px solid var(--border); }
.mem-table td { padding:8px 10px; border-bottom:1px solid var(--border); color:var(--text); }
.mem-table tbody tr:last-child td { border-bottom:none; }
.badge-head { background:#fef3c7; color:#92400e; display:inline-flex; align-items:center; padding:2px 7px; border-radius:20px; font-size:10px; font-weight:600; }

/* Fullscreen mode */
.map-fullscreen {
  position:fixed;
  top:0; left:0;
  width:100vw;
  height:100vh;
  z-index:9999;
  border-radius:0;
  margin:0;
  overflow:hidden;
}
.map-fullscreen #map {
  height:100vh;
  width:100vw;
  border-radius:0;
}
</style>

<div class="bidb-wrap">

  <!-- Header -->
  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-map-marked-alt" style="margin-right:8px"></i>Household Location Map</h1>
      <div class="breadcrumb">Home › <a href="{{ route('households.index') }}">Households</a> › <span>Map View</span></div>
    </div>
    <a href="{{ route('households.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Households</a>
  </div>

  <!-- Stats -->
  <div class="stat-row">
    <div class="stat-card">
      <div class="stat-icon blue"><i class="fas fa-map-marker-alt"></i></div>
      <div class="stat-info">
        <div class="slabel">Pinned Households</div>
        <div class="svalue">{{ $households->count() }}</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon green"><i class="fas fa-map"></i></div>
      <div class="stat-info">
        <div class="slabel">Barangay</div>
        <div class="svalue" style="font-size:15px;margin-top:2px">Cogon, Ormoc City</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon yellow"><i class="fas fa-info-circle"></i></div>
      <div class="stat-info">
        <div class="slabel">Click a pin to view</div>
        <div class="svalue" style="font-size:13px;font-weight:500;margin-top:2px;color:var(--muted)">Resident details</div>
      </div>
    </div>
  </div>

  <!-- Map Card -->
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-map"></i> Barangay Cogon — Resident Location Map</div>
    </div>
    <div class="map-wrap" id="mapWrap">
      <div id="map"></div>
      <button class="fullscreen-btn" onclick="toggleFullscreen()">
        <i class="fas fa-expand" id="fs-icon2"></i> <span id="fs-text2">Fullscreen</span>
      </button>
    </div>
  </div>

</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
// Init map centered on Ormoc City / Barangay Cogon
const cogonBounds = L.latLngBounds([11.0112, 124.5982], [11.0307, 124.6100]);
const map = L.map('map', { maxBounds: cogonBounds, maxBoundsViscosity: 1.0, minZoom: 14 }).setView([11.0207, 124.6047], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© OpenStreetMap contributors'
}).addTo(map);
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

// Household data store for modal
const hhData = @json($households->keyBy('id'));


// Plot household pins
@foreach($households as $hh)
L.marker([{{ $hh->latitude }}, {{ $hh->longitude }}])
  .addTo(map)
  .bindPopup(`
    <div style="font-family:sans-serif;min-width:180px">
      <div style="font-weight:700;font-size:14px;margin-bottom:4px">{{ $hh->household_number }}</div>
      <div style="font-weight:600;font-size:13px">{{ $hh->head_last_name }}, {{ $hh->head_first_name }}</div>
      <div style="font-size:12px;color:#64748b">Sitio {{ $hh->sitio }}</div>
      <div style="font-size:11px;margin-top:6px;color:#1a3a6b;font-weight:600">Members: {{ $hh->member_count }}</div>
      <button onclick="openHouseholdModal({{ $hh->id }})" style="margin-top:10px;width:100%;padding:6px 0;background:#1a3a6b;color:#fff;border:none;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit">
        <i class="fas fa-eye" style="margin-right:5px"></i>View Household
      </button>
    </div>
  `);
@endforeach

// Fullscreen toggle
let isFullscreen = false;
function toggleFullscreen() {
  isFullscreen = !isFullscreen;
  const wrap = document.getElementById('mapWrap');
  if (isFullscreen) {
    wrap.classList.add('map-fullscreen');
    document.getElementById('fs-icon2').className = 'fas fa-compress';
    document.getElementById('fs-text2').textContent = 'Exit';
  } else {
    wrap.classList.remove('map-fullscreen');
    document.getElementById('fs-icon2').className = 'fas fa-expand';
    document.getElementById('fs-text2').textContent = 'Fullscreen';
  }
  setTimeout(() => map.invalidateSize(), 100);
}

// Exit fullscreen with Escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape' && isFullscreen) toggleFullscreen();
});

function openHouseholdModal(id) {
  const h = hhData[id];
  if (!h) return;
  document.getElementById('householdModal').classList.add('open');
  document.getElementById('hm-num').textContent     = h.household_number || '—';
  document.getElementById('hm-type').textContent    = h.residency_type   || '—';
  document.getElementById('hm-members').textContent = h.member_count ? h.member_count + ' member(s)' : '—';
  document.getElementById('hm-last').textContent    = h.head_last_name   || '—';
  document.getElementById('hm-first').textContent   = h.head_first_name  || '—';
  document.getElementById('hm-middle').textContent  = h.head_middle_name || '—';
  document.getElementById('hm-sitio').textContent   = h.sitio    || '—';
  document.getElementById('hm-street').textContent  = h.street   || '—';
  document.getElementById('hm-brgy').textContent    = h.barangay || '—';
  document.getElementById('hm-city').textContent    = h.city     || '—';
  document.getElementById('hm-prov').textContent    = h.province || '—';
  document.getElementById('hm-loc').textContent     = (h.latitude && h.longitude) ? '📍 ' + h.latitude + ', ' + h.longitude : 'Not pinned';

  const body = document.getElementById('hm-members-body');
  const members = h.members || [];
  if (!members.length) {
    body.innerHTML = '<p style="color:var(--muted);font-size:13px;font-style:italic;margin:0">No members linked yet.</p>';
  } else {
    const headMember = members.find(m => m.id === h.head_resident_id);
    const headFamilyId = headMember ? headMember.family_id : null;

    const groups = {};
    const groupOrder = [];
    members.forEach(m => {
      const key = m.family_id ? String(m.family_id) : '__none__';
      if (!groups[key]) { groups[key] = []; groupOrder.push(key); }
      groups[key].push(m);
    });

    const headFamilyKey = headFamilyId ? String(headFamilyId) : null;
    if (headFamilyKey && groupOrder[0] !== headFamilyKey) {
      const idx = groupOrder.indexOf(headFamilyKey);
      if (idx > 0) { groupOrder.splice(idx, 1); groupOrder.unshift(headFamilyKey); }
    }

    let counter = 0; let familyNum = 0;
    const rows = groupOrder.map(key => {
      familyNum++;
      const groupMembers = groups[key].slice().sort((a, b) => {
        if (a.id === h.head_resident_id) return -1;
        if (b.id === h.head_resident_id) return 1;
        return (a.last_name || '').localeCompare(b.last_name || '');
      });
      const headerLabel = key === '__none__' ? 'Ungrouped' : `Family ${familyNum}`;
      const headerRow = `<tr><td colspan="5" style="padding:3px 6px;font-size:10px;font-weight:700;letter-spacing:.5px;color:var(--muted);border-bottom:1px solid var(--border);background:var(--header-bg)">${headerLabel}</td></tr>`;
      const memberRows = groupMembers.map(m => {
        counter++;
        const isHead = m.id === h.head_resident_id;
        const role = isHead
          ? '<span class="badge-head"><i class="fas fa-crown" style="margin-right:3px;font-size:9px"></i>Head</span>'
          : '<span style="color:var(--muted);font-size:11px">Member</span>';
        const name = (m.last_name || '') + ', ' + (m.first_name || '') + (m.middle_name ? ' ' + m.middle_name : '');
        const nameLink = `<a href="#" onclick="event.preventDefault();openResidentPreview(${m.id})" style="color:var(--primary);text-decoration:none;font-weight:600;cursor:pointer">${name}</a>`;
        return `<tr><td style="color:var(--muted);font-size:11px">${counter}</td><td>${nameLink}</td><td>${m.gender || '—'} / ${m.age || '—'} yrs</td><td>${m.civil_status || '—'}</td><td>${role}</td></tr>`;
      }).join('');
      return headerRow + memberRows;
    }).join('');
    body.innerHTML = `<table class="mem-table"><thead><tr><th>#</th><th>Full Name</th><th>Sex / Age</th><th>Civil Status</th><th>Role</th></tr></thead><tbody>${rows}</tbody></table>`;
  }

  @if(auth()->user()->role === 'admin')
  document.getElementById('hm-edit-link').innerHTML = `<a href="/households/${h.id}/edit" style="color:var(--primary);font-weight:600;text-decoration:none"><i class="fas fa-edit" style="margin-right:4px"></i>Edit this household</a>`;
  @else
  document.getElementById('hm-edit-link').innerHTML = '';
  @endif
}
function closeHouseholdModal() {
  document.getElementById('householdModal').classList.remove('open');
}
</script>

<!-- Household View Modal -->
<div id="householdModal" class="modal-backdrop">
  <div class="modal">
    <div class="modal-header">
      <h2><i class="fas fa-home" style="margin-right:8px"></i>Household Profile</h2>
      <button class="modal-close" onclick="closeHouseholdModal()">×</button>
    </div>
    <div class="modal-body">
      <div class="modal-section">
        <div class="modal-section-title"><i class="fas fa-home"></i> Household Information</div>
        <div class="mgrid">
          <div class="mi"><span class="ml">Household No.</span><span class="mv" id="hm-num" style="font-weight:700;color:var(--primary)"></span></div>
          <div class="mi"><span class="ml">Residency Type</span><span class="mv" id="hm-type"></span></div>
          <div class="mi"><span class="ml">No. of Members</span><span class="mv" id="hm-members"></span></div>
        </div>
      </div>
      <div class="modal-section">
        <div class="modal-section-title"><i class="fas fa-user"></i> Household Head</div>
        <div class="mgrid">
          <div class="mi"><span class="ml">Last Name</span><span class="mv" id="hm-last"></span></div>
          <div class="mi"><span class="ml">First Name</span><span class="mv" id="hm-first"></span></div>
          <div class="mi"><span class="ml">Middle Name</span><span class="mv" id="hm-middle"></span></div>
        </div>
      </div>
      <div class="modal-section">
        <div class="modal-section-title"><i class="fas fa-map-marker-alt"></i> Address</div>
        <div class="mgrid">
          <div class="mi"><span class="ml">Sitio</span><span class="mv" id="hm-sitio"></span></div>
          <div class="mi"><span class="ml">Street / Purok</span><span class="mv" id="hm-street"></span></div>
          <div class="mi"><span class="ml">Barangay</span><span class="mv" id="hm-brgy"></span></div>
          <div class="mi"><span class="ml">City / Municipality</span><span class="mv" id="hm-city"></span></div>
          <div class="mi"><span class="ml">Province</span><span class="mv" id="hm-prov"></span></div>
          <div class="mi"><span class="ml">Location</span><span class="mv" id="hm-loc"></span></div>
        </div>
      </div>
      <div class="modal-section">
        <div class="modal-section-title"><i class="fas fa-users"></i> Household Members</div>
        <div id="hm-members-body"></div>
      </div>
    </div>
    <div class="modal-footer">
      <span style="font-size:12px;color:var(--muted)" id="hm-edit-link"></span>
      <button onclick="closeHouseholdModal()" class="btn btn-sm" style="background:#f1f5f9;color:var(--muted);border:1px solid var(--border)">
        <i class="fas fa-times"></i> Close
      </button>
    </div>
  </div>
</div>
<script>
document.getElementById('householdModal').addEventListener('click', function(e) {
  if (e.target === this) closeHouseholdModal();
});
</script>

@include('partials.resident-preview-modal')

@endsection