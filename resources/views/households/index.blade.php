@extends('layouts.app')

@section('page-title', 'Households')

@section('content')
<style>

.bidb-wrap { background:var(--bg); min-height:100vh; padding:28px; }
.page-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.page-hdr h1 { font-size:22px; font-weight:700; color:var(--primary); margin:0; }
.breadcrumb { font-size:13px; color:var(--muted); margin-top:2px; }
.breadcrumb span { color:var(--primary); font-weight:500; }
.res-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:24px; }
.res-stat { background:var(--card); border-radius:12px; padding:18px 20px; border:1px solid var(--border); box-shadow:0 1px 4px rgba(0,0,0,.05); }
.res-stat .slabel { font-size:12px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:6px; }
.res-stat .svalue { font-size:28px; font-weight:800; color:var(--primary); }
.card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); margin-bottom:24px; overflow:hidden; }
.filter-row { display:flex; gap:10px; flex-wrap:wrap; padding:16px 20px; border-bottom:1px solid var(--border); }
.search-wrap { position:relative; flex:1; min-width:220px; }
.search-wrap input { width:100%; padding:9px 14px 9px 36px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; font-family:inherit; outline:none; box-sizing:border-box; }
.search-wrap input:focus { border-color:var(--primary); }
.search-wrap .si { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--muted); font-size:13px; }
.filter-select { padding:9px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:13px; font-family:inherit; color:var(--text); outline:none; cursor:pointer; background:var(--card); }
.table-wrap { overflow-x:auto; }
table { width:100%; border-collapse:collapse; font-size:13px; }
thead tr { background:#f8fafc; border-bottom:2px solid var(--border); }
th { padding:12px 16px; text-align:left; font-weight:700; color:var(--muted); font-size:11px; text-transform:uppercase; letter-spacing:.06em; white-space:nowrap; }
td { padding:13px 16px; border-bottom:1px solid var(--border); color:var(--text); vertical-align:middle; }
tbody tr:hover { background:#f8fafc; }
tbody tr:last-child td { border-bottom:none; }
.badge { display:inline-flex; align-items:center; padding:2px 8px; border-radius:20px; font-size:11px; font-weight:600; }
.badge-perm  { background:#dcfce7; color:#166534; }
.badge-trans { background:#fef3c7; color:#92400e; }
.badge-board { background:#dbeafe; color:#1e40af; }
.btn { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:8px; border:none; cursor:pointer; font-family:inherit; font-size:13px; font-weight:600; transition:all .15s; text-decoration:none; }
.btn-primary { background:var(--primary); color:#fff; }
.btn-primary:hover { background:var(--primary-light); }
.btn-sm { padding:5px 10px; font-size:12px; }
.btn-view   { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
.btn-edit   { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; }
.btn-delete { background:#fff1f2; color:#be123c; border:1px solid #fecdd3; }
.btn-view:hover   { background:#dbeafe; }
.btn-edit:hover   { background:#dcfce7; }
.btn-delete:hover { background:#ffe4e6; }
.action-btns { display:flex; gap:5px; }
.empty-state { text-align:center; padding:48px 20px; color:var(--muted); }
.alert-success { background:#dcfce7; border:1px solid #bbf7d0; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px; display:flex; align-items:center; gap:8px; }
</style>

<div class="bidb-wrap">
  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-home" style="margin-right:8px"></i>Household Records</h1>
      <div class="breadcrumb">Home › <span>Households</span></div>
    </div>
    <div style="display:flex;gap:8px">
      <a href="{{ route('residents.location') }}" class="btn btn-outline">
        <i class="fas fa-map-marker-alt"></i> View Map
      </a>
      <a href="{{ route('households.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Household
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif

  <!-- Stats -->
  <div class="res-stats">
    <div class="res-stat">
      <div class="slabel">Total Households</div>
      <div class="svalue">{{ $households->count() }}</div>
    </div>
    <div class="res-stat">
      <div class="slabel">Permanent</div>
      <div class="svalue">{{ $households->where('residency_type','Permanent')->count() }}</div>
    </div>
    <div class="res-stat">
      <div class="slabel">Transient</div>
      <div class="svalue">{{ $households->where('residency_type','Transient')->count() }}</div>
    </div>
    <div class="res-stat">
      <div class="slabel">Total Members</div>
      <div class="svalue">{{ $households->sum('member_count') }}</div>
    </div>
  </div>

  <!-- Table Card -->
  <div class="card">
    <div class="filter-row">
      <div class="search-wrap">
        <span class="si"><i class="fas fa-search"></i></span>
        <input type="text" id="searchInput" placeholder="Search by household head or sitio...">
      </div>
      <select class="filter-select" id="filterResidency">
        <option value="">All — Residency</option>
        <option>Permanent</option>
        <option>Transient</option>
        <option>Boarder</option>
      </select>
      <select class="filter-select" id="filterSitio">
        <option value="">All — Sitio</option>
        @foreach(['Chrysanthemum','Dahlia','Dama de Noche','Ilang-Ilang 1','Ilang-Ilang 2','Jasmin','Rosal','Sampaguita'] as $sitio)
          <option>{{ $sitio }}</option>
        @endforeach
      </select>
    </div>

    <div class="table-wrap">
      <table id="householdsTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Household No.</th>
            <th>Household Head</th>
            <th>Sitio</th>
            <th>Members</th>
            <th>Residency</th>
            <th>Location</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($households as $index => $hh)
          <tr
            data-name="{{ strtolower($hh->head_last_name . ' ' . $hh->head_first_name) }}"
            data-sitio="{{ $hh->sitio }}"
            data-residency="{{ $hh->residency_type }}"
          >
            <td style="color:var(--muted);font-size:12px">{{ $index + 1 }}</td>
            <td><span style="font-weight:700;color:var(--primary)">{{ $hh->household_number }}</span></td>
            <td>
              <div style="font-weight:600">{{ $hh->head_last_name }}, {{ $hh->head_first_name }} {{ $hh->head_middle_name }}</div>
              <div style="font-size:11px;color:var(--muted)">Household Head</div>
            </td>
            <td>
              <div>{{ $hh->sitio }}</div>
              <div style="font-size:11px;color:var(--muted)">{{ $hh->street }}</div>
            </td>
            <td><span style="font-weight:700">{{ $hh->member_count }}</span> <span style="color:var(--muted);font-size:12px">member(s)</span></td>
            <td>
              @if($hh->residency_type == 'Permanent')
                <span class="badge badge-perm">Permanent</span>
              @elseif($hh->residency_type == 'Transient')
                <span class="badge badge-trans">Transient</span>
              @else
                <span class="badge badge-board">{{ $hh->residency_type }}</span>
              @endif
            </td>
            <td>
              @if($hh->latitude && $hh->longitude)
                <span style="color:#16a34a;font-size:12px"><i class="fas fa-map-marker-alt"></i> Pinned</span>
              @else
                <span style="color:var(--muted);font-size:12px">Not pinned</span>
              @endif
            </td>
            <td>
  <div class="action-btns">

    <a href="{{ route('households.show', $hh->id) }}" class="btn btn-sm btn-view">
      <i class="fas fa-eye"></i> View
    </a>

    @if(auth()->user()->role === 'admin')

      <a href="{{ route('households.edit', $hh->id) }}" class="btn btn-sm btn-edit">
        <i class="fas fa-edit"></i> Edit
      </a>

      <form method="POST"
            action="{{ route('households.destroy', $hh->id) }}"
            style="display:inline"
            onsubmit="return confirm('Delete this household?')">

        @csrf
        @method('DELETE')

        <button type="submit" class="btn btn-sm btn-delete">
          <i class="fas fa-trash"></i>
        </button>

      </form>

    @endif

  </div>
</td>
</tr>
          @empty
          <tr>
            <td colspan="8">
              <div class="empty-state">
                <div style="font-size:40px;opacity:.3;margin-bottom:12px"><i class="fas fa-home"></i></div>
                <div style="font-weight:600;margin-bottom:4px">No households found</div>
                <div>Add your first household to get started.</div>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function filterTable() {
  const q         = document.getElementById('searchInput').value.toLowerCase();
  const residency = document.getElementById('filterResidency').value;
  const sitio     = document.getElementById('filterSitio').value;
  document.querySelectorAll('#householdsTable tbody tr[data-name]').forEach(row => {
    const matchQ = !q         || (row.dataset.name||'').includes(q) || (row.dataset.sitio||'').toLowerCase().includes(q);
    const matchR = !residency || row.dataset.residency === residency;
    const matchS = !sitio     || row.dataset.sitio === sitio;
    row.style.display = (matchQ && matchR && matchS) ? '' : 'none';
  });
}
document.getElementById('searchInput').addEventListener('keyup', filterTable);
document.getElementById('filterResidency').addEventListener('change', filterTable);
document.getElementById('filterSitio').addEventListener('change', filterTable);
</script>
@endsection