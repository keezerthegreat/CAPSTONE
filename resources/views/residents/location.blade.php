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

/* Fullscreen mode */
.map-fullscreen {
  position:fixed;
  top:0; left:0;
  width:100vw;
  height:100vh;
  z-index:9999;
  border-radius:0;
  margin:0;
}
.map-fullscreen #map {
  height:100vh;
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
const map = L.map('map').setView([11.0064, 124.6076], 15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Plot resident pins
@foreach($households as $hh)
L.marker([{{ $hh->latitude }}, {{ $hh->longitude }}])
  .addTo(map)
  .bindPopup(`
    <div style="font-family:sans-serif;min-width:160px">
      <div style="font-weight:700;font-size:14px;margin-bottom:4px">{{ $hh->household_number }}</div>
      <div style="font-weight:600;font-size:13px">{{ $hh->head_last_name }}, {{ $hh->head_first_name }}</div>
      <div style="font-size:12px;color:#64748b">Sitio {{ $hh->sitio }}</div>
      <div style="font-size:11px;margin-top:6px;color:#1a3a6b;font-weight:600">Members: {{ $hh->member_count }}</div>
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
    document.getElementById('fs-icon').className = 'fas fa-compress';
    document.getElementById('fs-text').textContent = 'Exit Fullscreen';
    document.getElementById('fs-icon2').className = 'fas fa-compress';
    document.getElementById('fs-text2').textContent = 'Exit';
  } else {
    wrap.classList.remove('map-fullscreen');
    document.getElementById('fs-icon').className = 'fas fa-expand';
    document.getElementById('fs-text').textContent = 'Fullscreen';
    document.getElementById('fs-icon2').className = 'fas fa-expand';
    document.getElementById('fs-text2').textContent = 'Fullscreen';
  }
  setTimeout(() => map.invalidateSize(), 100);
}

// Exit fullscreen with Escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape' && isFullscreen) toggleFullscreen();
});
</script>

@endsection