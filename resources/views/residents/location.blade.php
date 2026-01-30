<h2>Resident Locations</h2>

<a href="{{ url()->previous() }}"
   style="
        display: inline-block;
        margin-bottom: 12px;
        padding: 6px 12px;
        background-color: #2c3e50;
        color: #ffffff;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
   ">
    ← Back
</a>

<div id="map" style="height:600px;"></div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
const map = L.map('map').setView([14.5995, 120.9842], 14);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

@foreach($residents as $resident)
    L.marker([{{ $resident->latitude }}, {{ $resident->longitude }}])
        .addTo(map)
        .bindPopup(`
            <strong>{{ $resident->full_name }}</strong><br>
            {{ $resident->address }}
        `);
@endforeach
</script>
