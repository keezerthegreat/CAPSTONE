@extends('layouts.app')

@section('page-title', 'Residents')

@section('content')

<style>
:root {
  --primary: #1a3a6b;
  --primary-light: #2554a0;
  --bg: #f0f4f8;
  --card: #ffffff;
  --text: #1e293b;
  --muted: #64748b;
  --border: #e2e8f0;
}
.bidb-wrap { background: var(--bg); min-height: 100vh; padding: 28px; }
.page-hdr { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
.page-hdr h1 { font-size: 22px; font-weight: 700; color: var(--primary); margin: 0; }
.breadcrumb { font-size: 13px; color: var(--muted); margin-top: 2px; }
.breadcrumb span { color: var(--primary); font-weight: 500; }
.res-stats { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 24px; }
.res-stat { background: var(--card); border-radius: 12px; padding: 18px 20px; border: 1px solid var(--border); box-shadow: 0 1px 4px rgba(0,0,0,.05); }
.res-stat .slabel { font-size: 12px; font-weight: 600; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px; }
.res-stat .svalue { font-size: 28px; font-weight: 800; color: var(--primary); }
.card { background: var(--card); border-radius: 14px; border: 1px solid var(--border); box-shadow: 0 1px 6px rgba(0,0,0,.06); margin-bottom: 24px; overflow: hidden; }
.filter-row { display: flex; gap: 10px; flex-wrap: wrap; padding: 16px 20px; border-bottom: 1px solid var(--border); }
.search-wrap { position: relative; flex: 1; min-width: 220px; }
.search-wrap input { width: 100%; padding: 9px 14px 9px 36px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; outline: none; box-sizing: border-box; }
.search-wrap input:focus { border-color: var(--primary); }
.search-wrap .si { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 13px; }
.filter-select { padding: 9px 14px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 13px; font-family: inherit; color: var(--text); outline: none; cursor: pointer; background: var(--card); }
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: 13px; }
thead tr { background: #f8fafc; border-bottom: 2px solid var(--border); }
th { padding: 12px 16px; text-align: left; font-weight: 700; color: var(--muted); font-size: 11px; text-transform: uppercase; letter-spacing: .06em; white-space: nowrap; }
td { padding: 13px 16px; border-bottom: 1px solid var(--border); color: var(--text); vertical-align: middle; }
tbody tr:hover { background: #f8fafc; }
tbody tr:last-child td { border-bottom: none; }
.badge { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; margin: 1px; }
.badge-senior { background: #fef3c7; color: #92400e; }
.badge-pwd    { background: #fee2e2; color: #991b1b; }
.btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer; font-family: inherit; font-size: 13px; font-weight: 600; transition: all .15s; text-decoration: none; }
.btn-primary { background: var(--primary); color: #fff; }
.btn-primary:hover { background: var(--primary-light); }
.btn-sm { padding: 5px 10px; font-size: 12px; }
.btn-view   { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.btn-edit   { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.btn-delete { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
.action-btns { display: flex; gap: 5px; }
.empty-state { text-align: center; padding: 48px 20px; color: var(--muted); }
.alert-success { background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 8px; }
</style>

<div class="bidb-wrap">
  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-users" style="margin-right:8px;font-size:20px"></i>Resident Records</h1>
      <div class="breadcrumb">Home › <span>Residents</span></div>
    </div>
    <a href="{{ route('residents.create') }}" class="btn btn-primary">
      <i class="fas fa-user-plus"></i> Add Resident
    </a>
  </div>

  @if(session('success'))
    <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif

  <div class="res-stats">
    <div class="res-stat"><div class="slabel">Total Residents</div><div class="svalue">{{ $residents->count() }}</div></div>
    <div class="res-stat"><div class="slabel">Senior Citizens</div><div class="svalue">{{ $residents->where('is_senior', true)->count() }}</div></div>
    <div class="res-stat"><div class="slabel">Persons w/ Disability</div><div class="svalue">{{ $residents->where('is_pwd', true)->count() }}</div></div>
    <div class="res-stat"><div class="slabel">Registered Voters</div><div class="svalue">{{ $residents->where('is_voter', true)->count() }}</div></div>
  </div>

  <div class="card">
    <div class="filter-row">
      <div class="search-wrap">
        <span class="si"><i class="fas fa-search"></i></span>
        <input type="text" id="searchInput" placeholder="Search by name, address, or sitio...">
      </div>
      <select class="filter-select" id="filterGender">
        <option value="">All — Sex</option>
        <option>Male</option>
        <option>Female</option>
      </select>
      <select class="filter-select" id="filterClass">
        <option value="">All — Classification</option>
        <option value="senior">Senior Citizen</option>
        <option value="pwd">PWD</option>
        <option value="voter">Registered Voter</option>
      </select>
    </div>
    <div class="table-wrap">
      <table id="residentsTable">
        <thead>
          <tr>
            <th>#</th><th>Full Name</th><th>Sex / Age</th><th>Civil Status</th><th>Address</th><th>Classifications</th><th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($residents as $index => $resident)
          <tr data-name="{{ strtolower($resident->last_name . ' ' . $resident->first_name) }}" data-gender="{{ $resident->gender }}" data-senior="{{ $resident->is_senior ? 'senior' : '' }}" data-pwd="{{ $resident->is_pwd ? 'pwd' : '' }}" data-voter="{{ $resident->is_voter ? 'voter' : '' }}">
            <td style="color:var(--muted);font-size:12px">{{ $index + 1 }}</td>
            <td>
              <div style="font-weight:700">{{ $resident->last_name }}, {{ $resident->first_name }} {{ $resident->middle_name }}</div>
              <div style="font-size:11px;color:var(--muted)">ID #{{ $resident->id }}</div>
            </td>
            <td>{{ $resident->gender }} / {{ $resident->age }} yrs</td>
            <td>{{ $resident->civil_status ?? '—' }}</td>
            <td>
              <div>{{ $resident->address ?? '—' }}</div>
              <div style="font-size:11px;color:var(--muted)">{{ $resident->barangay }}, {{ $resident->city }}</div>
            </td>
            <td>
              @if($resident->is_senior)<span class="badge badge-senior">Senior</span>@endif
              @if($resident->is_pwd)<span class="badge badge-pwd">PWD</span>@endif
              @if($resident->is_voter)<span class="badge" style="background:#f3e8ff;color:#6b21a8">Voter</span>@endif
              @if(!$resident->is_senior && !$resident->is_pwd && !$resident->is_voter)<span style="color:var(--muted);font-size:12px">—</span>@endif
            </td>
            <td>
              <div class="action-btns">
                <a href="{{ route('residents.show', $resident->id) }}" class="btn btn-sm btn-view"><i class="fas fa-eye"></i> View</a>
                <a href="{{ route('residents.edit', $resident->id) }}" class="btn btn-sm btn-edit"><i class="fas fa-edit"></i> Edit</a>
                <form method="POST" action="{{ route('residents.destroy', $resident->id) }}" style="display:inline" onsubmit="return confirm('Delete this resident?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-delete"><i class="fas fa-trash"></i></button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="7"><div class="empty-state"><div style="font-size:40px;opacity:.3;margin-bottom:12px"><i class="fas fa-user-slash"></i></div><div style="font-weight:600">No residents found</div></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function filterTable() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  const gender = document.getElementById('filterGender').value;
  const cls = document.getElementById('filterClass').value;
  document.querySelectorAll('#residentsTable tbody tr[data-name]').forEach(row => {
    const matchQ = !q || (row.dataset.name||'').includes(q);
    const matchG = !gender || row.dataset.gender === gender;
    const matchC = !cls || row.dataset[cls] === cls;
    row.style.display = (matchQ && matchG && matchC) ? '' : 'none';
  });
}
document.getElementById('searchInput').addEventListener('keyup', filterTable);
document.getElementById('filterGender').addEventListener('change', filterTable);
document.getElementById('filterClass').addEventListener('change', filterTable);
</script>
@endsection