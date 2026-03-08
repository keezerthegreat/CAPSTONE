@extends('layouts.app')

@section('page-title', 'Families')

@section('content')
<style>
:root { --primary:#1a3a6b; --primary-light:#2554a0; --bg:#f0f4f8; --card:#fff; --text:#1e293b; --muted:#64748b; --border:#e2e8f0; }
.bidb-wrap { background:var(--bg); min-height:100vh; padding:28px; }
.page-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.page-hdr h1 { font-size:22px; font-weight:700; color:var(--primary); margin:0; }
.breadcrumb { font-size:13px; color:var(--muted); margin-top:2px; }
.breadcrumb span { color:var(--primary); font-weight:500; }
.fam-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:24px; }
.fam-stat { background:var(--card); border-radius:12px; padding:18px 20px; border:1px solid var(--border); box-shadow:0 1px 4px rgba(0,0,0,.05); }
.fam-stat .slabel { font-size:12px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:6px; }
.fam-stat .svalue { font-size:28px; font-weight:800; color:var(--primary); }
.card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); margin-bottom:24px; overflow:hidden; }
.filter-row { display:flex; gap:10px; flex-wrap:wrap; padding:16px 20px; border-bottom:1px solid var(--border); }
.search-wrap { position:relative; flex:1; min-width:220px; }
.search-wrap input { width:100%; padding:9px 14px 9px 36px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; font-family:inherit; outline:none; box-sizing:border-box; }
.search-wrap input:focus { border-color:var(--primary); }
.search-wrap .si { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--muted); font-size:13px; }
.table-wrap { overflow-x:auto; }
table { width:100%; border-collapse:collapse; font-size:13px; }
thead tr { background:#f8fafc; border-bottom:2px solid var(--border); }
th { padding:12px 16px; text-align:left; font-weight:700; color:var(--muted); font-size:11px; text-transform:uppercase; letter-spacing:.06em; white-space:nowrap; }
td { padding:13px 16px; border-bottom:1px solid var(--border); color:var(--text); vertical-align:middle; }
tbody tr:hover { background:#f8fafc; }
tbody tr:last-child td { border-bottom:none; }
.btn { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:8px; border:none; cursor:pointer; font-family:inherit; font-size:13px; font-weight:600; transition:all .15s; text-decoration:none; }
.btn-primary { background:var(--primary); color:#fff; }
.btn-primary:hover { background:var(--primary-light); }
.btn-sm { padding:5px 10px; font-size:12px; }
.btn-view   { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; }
.btn-edit   { background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; }
.btn-delete { background:#fff1f2; color:#be123c; border:1px solid #fecdd3; }
.action-btns { display:flex; gap:5px; }
.empty-state { text-align:center; padding:48px 20px; color:var(--muted); }
.alert-success { background:#dcfce7; border:1px solid #bbf7d0; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px; display:flex; align-items:center; gap:8px; }
</style>

<div class="bidb-wrap">
  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-people-roof" style="margin-right:8px;font-size:20px"></i>Family Records</h1>
      <div class="breadcrumb">Home › <span>Families</span></div>
    </div>
    <a href="{{ route('families.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i> Add Family
    </a>
  </div>

  @if(session('success'))
    <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif

  <div class="fam-stats">
    <div class="fam-stat"><div class="slabel">Total Families</div><div class="svalue">{{ $families->count() }}</div></div>
    <div class="fam-stat"><div class="slabel">Total Members</div><div class="svalue">{{ $families->sum('member_count') }}</div></div>
    <div class="fam-stat"><div class="slabel">Linked to Households</div><div class="svalue">{{ $families->whereNotNull('household_id')->count() }}</div></div>
  </div>

  <div class="card">
    <div class="filter-row">
      <div class="search-wrap">
        <span class="si"><i class="fas fa-search"></i></span>
        <input type="text" id="searchInput" placeholder="Search by family name or head...">
      </div>
    </div>

    <div class="table-wrap">
      <table id="familiesTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Family Name</th>
            <th>Head of Family</th>
            <th>Members</th>
            <th>Linked Household</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($families as $index => $family)
          <tr>
            <td style="color:var(--muted);font-size:12px">{{ $index + 1 }}</td>
            <td>
              <div style="font-weight:700">{{ $family->family_name }}</div>
              <div style="font-size:11px;color:var(--muted)">ID #{{ $family->id }}</div>
            </td>
            <td>{{ $family->head_last_name }}, {{ $family->head_first_name }} {{ $family->head_middle_name }}</td>
            <td>{{ $family->member_count }}</td>
            <td>
              @if($family->household)
                <span style="background:#eff6ff;color:#1d4ed8;padding:2px 8px;border-radius:20px;font-size:11px;font-weight:600">
                  HH #{{ $family->household->household_number }}
                </span>
              @else
                <span style="color:var(--muted);font-size:12px">—</span>
              @endif
            </td>
            <td>
              <div class="action-btns">

<a href="{{ route('families.show', $family->id) }}" class="btn btn-sm btn-view">
<i class="fas fa-eye"></i> View
</a>

@if(auth()->user()->role == 'admin')

<a href="{{ route('families.edit', $family->id) }}" class="btn btn-sm btn-edit">
<i class="fas fa-edit"></i> Edit
</a>

<form method="POST" action="{{ route('families.destroy', $family->id) }}" style="display:inline" onsubmit="return confirm('Delete this family?')">
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
            <td colspan="6">
              <div class="empty-state">
                <div style="font-size:40px;opacity:.3;margin-bottom:12px"><i class="fas fa-people-roof"></i></div>
                <div style="font-weight:600">No families found</div>
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
document.getElementById('searchInput').addEventListener('keyup', function() {
  const val = this.value.toLowerCase();
  document.querySelectorAll('#familiesTable tbody tr').forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
  });
});
</script>
@endsection