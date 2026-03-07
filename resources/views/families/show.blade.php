@extends('layouts.app')

@section('page-title', 'Family Profile')

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
.card-header { padding:16px 20px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.card-title { font-weight:700; color:var(--primary); font-size:14px; display:flex; align-items:center; gap:8px; }
.card-body { padding:24px; }
.info-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
.info-item .label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.06em; color:var(--muted); margin-bottom:4px; }
.info-item .value { font-size:14px; color:var(--text); font-weight:500; }
.info-item.full { grid-column:span 3; }
.btn { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:8px; border:none; cursor:pointer; font-family:inherit; font-size:13px; font-weight:600; transition:all .15s; text-decoration:none; }
.btn-primary { background:var(--primary); color:#fff; }
.btn-primary:hover { background:var(--primary-light); }
.btn-outline { background:#fff; color:var(--primary); border:1.5px solid var(--primary); }
.btn-outline:hover { background:#f0f4f8; }
</style>

<div class="bidb-wrap">
  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-people-roof" style="margin-right:8px"></i>Family Profile</h1>
      <div class="breadcrumb">Home › <a href="{{ route('families.index') }}">Families</a> › <span>{{ $family->family_name }}</span></div>
    </div>
    <div style="display:flex;gap:8px">
      <a href="{{ route('families.edit', $family->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
      <a href="{{ route('families.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-people-roof"></i> Family Information</div>
      <span style="background:#eff6ff;color:#1d4ed8;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600">
        {{ $family->member_count }} {{ $family->member_count == 1 ? 'Member' : 'Members' }}
      </span>
    </div>
    <div class="card-body">
      <div class="info-grid">
        <div class="info-item full">
          <div class="label">Family Name</div>
          <div class="value" style="font-size:18px;font-weight:700;color:var(--primary)">{{ $family->family_name }}</div>
        </div>
        <div class="info-item">
          <div class="label">Head Last Name</div>
          <div class="value">{{ $family->head_last_name }}</div>
        </div>
        <div class="info-item">
          <div class="label">Head First Name</div>
          <div class="value">{{ $family->head_first_name }}</div>
        </div>
        <div class="info-item">
          <div class="label">Head Middle Name</div>
          <div class="value">{{ $family->head_middle_name ?? '—' }}</div>
        </div>
        <div class="info-item">
          <div class="label">Number of Members</div>
          <div class="value">{{ $family->member_count }}</div>
        </div>
        <div class="info-item half">
          <div class="label">Linked Household</div>
          <div class="value">
            @if($family->household)
              <span style="background:#eff6ff;color:#1d4ed8;padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600">
                HH #{{ $family->household->household_number }} — {{ $family->household->head_last_name }}, {{ $family->household->head_first_name }} ({{ $family->household->sitio }})
              </span>
            @else
              <span style="color:var(--muted)">Not linked to any household</span>
            @endif
          </div>
        </div>
        @if($family->notes)
        <div class="info-item full">
          <div class="label">Notes</div>
          <div class="value">{{ $family->notes }}</div>
        </div>
        @endif
      </div>
    </div>
  </div>

</div>
@endsection