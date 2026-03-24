@extends('layouts.app')

@section('page-title', 'View Household')

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
.badge { display:inline-flex; align-items:center; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600; }
.badge-perm  { background:#dcfce7; color:#166534; }
.badge-trans { background:#fef3c7; color:#92400e; }
.badge-board { background:#dbeafe; color:#1e40af; }
.btn { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:8px; border:none; cursor:pointer; font-family:inherit; font-size:13px; font-weight:600; transition:all .15s; text-decoration:none; }
.btn-primary { background:var(--primary); color:#fff; }
.btn-edit { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; }
.btn-outline { background:#fff; color:var(--primary); border:1.5px solid var(--primary); }
#map { height:300px; border-radius:10px; border:1.5px solid var(--border); }
</style>

<div class="bidb-wrap">
  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-home" style="margin-right:8px"></i>Household Profile</h1>
      <div class="breadcrumb">Home › <a href="{{ route('households.index') }}">Households</a> › <span>{{ $household->household_number }}</span></div>
    </div>
    <div style="display:flex;gap:8px">
      <a href="{{ route('households.edit', $household->id) }}" class="btn btn-edit"><i class="fas fa-edit"></i> Edit</a>
      <a href="{{ route('households.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
  </div>

  <!-- Household Info -->
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-home"></i> Household Information</div>
      <div style="font-size:12px;color:var(--muted)">ID #{{ $household->id }}</div>
    </div>
    <div class="card-body">
      <div class="info-grid">
        <div class="info-item">
          <div class="label">Household Number</div>
          <div class="value" style="font-weight:700;color:var(--primary);font-size:16px">{{ $household->household_number }}</div>
        </div>
        <div class="info-item">
          <div class="label">Residential Type</div>
          <div class="value">
            @if($household->residency_type == 'Permanent')
              <span class="badge badge-perm">Permanent</span>
            @elseif($household->residency_type == 'Transient')
              <span class="badge badge-trans">Transient</span>
            @else
              <span class="badge badge-board">{{ $household->residency_type }}</span>
            @endif
          </div>
        </div>
        <div class="info-item">
          <div class="label">Number of Members</div>
          <div class="value">{{ $household->member_count }} member(s)</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Household Head -->
  <div class="card">
    <div class="card-header"><div class="card-title"><i class="fas fa-user"></i> Household Head</div></div>
    <div class="card-body">
      <div class="info-grid">
        <div class="info-item">
          <div class="label">Last Name</div>
          <div class="value">{{ $household->head_last_name }}</div>
        </div>
        <div class="info-item">
          <div class="label">First Name</div>
          <div class="value">{{ $household->head_first_name }}</div>
        </div>
        <div class="info-item">
          <div class="label">Middle Name</div>
          <div class="value">{{ $household->head_middle_name ?? '—' }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Address -->
  <div class="card">
    <div class="card-header"><div class="card-title"><i class="fas fa-map-marker-alt"></i> Address</div></div>
    <div class="card-body">
      <div class="info-grid">
        <div class="info-item">
          <div class="label">Purok</div>
          <div class="value">{{ $household->sitio }}</div>
        </div>
        <div class="info-item">
          <div class="label">Street / Sitio</div>
          <div class="value">{{ $household->street ?? '—' }}</div>
        </div>
        <div class="info-item">
          <div class="label">Barangay</div>
          <div class="value">{{ $household->barangay }}</div>
        </div>
        <div class="info-item">
          <div class="label">City / Municipality</div>
          <div class="value">{{ $household->city }}</div>
        </div>
        <div class="info-item">
          <div class="label">Province</div>
          <div class="value">{{ $household->province }}</div>
        </div>
        @if($household->notes)
        <div class="info-item full">
          <div class="label">Notes / Remarks</div>
          <div class="value">{{ $household->notes }}</div>
        </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Household Members -->
  <div class="card">
    <div class="card-header"><div class="card-title"><i class="fas fa-users"></i> Household Members</div></div>
    <div class="card-body" style="padding:0">
      @if($household->members->isEmpty())
        <p style="padding:24px;color:var(--muted);font-size:13px;font-style:italic;margin:0">No members linked yet.</p>
      @else
        @php
          $headId = $household->head_resident_id;
          // Group by family_id; null family goes last
          $grouped = $household->members->groupBy(fn($m) => $m->family_id ?? '__none__');
          // Put the head's family first
          $headMember = $household->members->firstWhere('id', $headId);
          $headFamilyKey = $headMember?->family_id ?? '__none__';
          $ordered = collect([$headFamilyKey => $grouped[$headFamilyKey]])
            ->merge($grouped->except($headFamilyKey));
          $familyNum = 0;
          $counter = 0;
        @endphp
        <table style="width:100%;border-collapse:collapse">
          <thead>
            <tr style="background:var(--header-bg,#f8fafc)">
              <th style="padding:10px 12px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);text-align:left;width:36px">#</th>
              <th style="padding:10px 12px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);text-align:left">Full Name</th>
              <th style="padding:10px 12px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);text-align:left">Sex / Age</th>
              <th style="padding:10px 12px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);text-align:left">Civil Status</th>
              <th style="padding:10px 12px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);text-align:left">Role</th>
            </tr>
          </thead>
          <tbody>
            @foreach($ordered as $familyKey => $familyMembers)
              @php $familyNum++; @endphp
              <tr style="background:var(--header-bg,#f8fafc)">
                <td colspan="5" style="padding:4px 12px;font-size:10px;font-weight:700;letter-spacing:.5px;color:var(--muted);border-top:1px solid var(--border);border-bottom:1px solid var(--border)">
                  Family {{ $familyNum }}
                </td>
              </tr>
              @foreach($familyMembers->sortBy(fn($m) => $m->id === $headId ? 0 : 1) as $member)
                @php $counter++; @endphp
                <tr style="border-bottom:1px solid var(--border)">
                  <td style="padding:10px 12px;font-size:12px;color:var(--muted)">{{ $counter }}</td>
                  <td style="padding:10px 12px;font-size:13px;font-weight:600">
                    <a href="{{ route('residents.show', $member->id) }}" style="color:var(--primary);text-decoration:none">
                      {{ $member->last_name }}, {{ $member->first_name }}{{ $member->middle_name ? ' '.$member->middle_name : '' }}
                    </a>
                  </td>
                  <td style="padding:10px 12px;font-size:13px;color:var(--muted)">{{ $member->gender ?? '—' }} / {{ $member->age ?? '—' }} yrs</td>
                  <td style="padding:10px 12px;font-size:13px">{{ $member->civil_status ?? '—' }}</td>
                  <td style="padding:10px 12px">
                    @if($member->id === $headId)
                      <span style="background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700"><i class="fas fa-crown" style="font-size:9px;margin-right:3px"></i>Head</span>
                    @else
                      <span style="color:var(--muted);font-size:12px">Member</span>
                    @endif
                  </td>
                </tr>
              @endforeach
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
  </div>

  <!-- Map -->
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-map-pin"></i> Household Location</div>
      @if($household->latitude && $household->longitude)
        <span style="font-size:12px;color:#16a34a"><i class="fas fa-check-circle"></i> Location pinned</span>
      @else
        <span style="font-size:12px;color:var(--muted)">No location pinned</span>
      @endif
    </div>
    <div class="card-body">
      @if($household->latitude && $household->longitude)
        <div class="info-grid" style="margin-bottom:16px">
          <div class="info-item">
            <div class="label">Latitude</div>
            <div class="value">{{ $household->latitude }}</div>
          </div>
          <div class="info-item">
            <div class="label">Longitude</div>
            <div class="value">{{ $household->longitude }}</div>
          </div>
        </div>
        <div id="map"></div>
      @else
        <div style="text-align:center;padding:32px;color:var(--muted)">
          <i class="fas fa-map-marker-alt" style="font-size:32px;opacity:.3;margin-bottom:12px;display:block"></i>
          No location has been pinned for this household.
          <div style="margin-top:8px"><a href="{{ route('households.edit', $household->id) }}" style="color:var(--primary);font-weight:600">Edit to add location →</a></div>
        </div>
      @endif
    </div>
  </div>

</div>

@if($household->latitude && $household->longitude)
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
const cogonBounds = L.latLngBounds([11.0112, 124.5982], [11.0307, 124.6100]);
const map = L.map('map', { maxBounds: cogonBounds, maxBoundsViscosity: 1.0, minZoom: 14 }).setView([{{ $household->latitude }}, {{ $household->longitude }}], 17);
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
L.marker([{{ $household->latitude }}, {{ $household->longitude }}])
  .addTo(map)
  .bindPopup('<strong>{{ $household->household_number }}</strong><br>{{ $household->head_last_name }}, {{ $household->head_first_name }}')
  .openPopup();
</script>
@endif

@endsection