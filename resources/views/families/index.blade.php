@extends('layouts.app')

@section('page-title', 'Families')

@section('content')
<style>

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
.modal-backdrop { display:none; position:fixed; inset:0; background:rgba(0,0,0,.35); z-index:200; align-items:center; justify-content:center; }
.modal-backdrop.open { display:flex; }
.modal { background:#fff; border-radius:16px; width:520px; max-width:95vw; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.2); }
.modal-header { padding:20px 24px 16px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.modal-header h2 { font-size:16px; font-weight:700; color:var(--primary); margin:0; }
.modal-close { background:none; border:none; font-size:22px; color:var(--muted); cursor:pointer; line-height:1; padding:0; }
.modal-body { padding:24px; }
.modal-section { margin-bottom:20px; }
.modal-section-title { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; margin-bottom:12px; padding-bottom:6px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:6px; }
.mgrid { display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; }
.mi { display:flex; flex-direction:column; gap:3px; }
.mi .ml { font-size:10px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; }
.mi .mv { font-size:13px; color:var(--text); font-weight:500; background:#f8fafc; border:1px solid var(--border); border-radius:7px; padding:7px 10px; }
.mi.span2 { grid-column:span 2; }
.mi.span3 { grid-column:span 3; }
.modal-footer { padding:16px 24px; border-top:1px solid var(--border); display:flex; justify-content:flex-end; }
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

<button onclick='openFamilyModal(@json($family))' class="btn btn-sm btn-view">
<i class="fas fa-eye"></i> View
</button>

@if(auth()->user()->role == 'admin')

<a href="{{ route('families.edit', $family->id) }}" class="btn btn-sm btn-edit">
<i class="fas fa-edit"></i> Edit
</a>

<form method="POST" action="{{ route('families.destroy', $family->id) }}" style="display:inline">
@csrf
@method('DELETE')
<button type="submit" class="btn btn-sm btn-delete">
<i class="fas fa-trash"></i> Delete</button>
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

<!-- Family View Modal -->
<div id="familyModal" class="modal-backdrop">
  <div class="modal">
    <div class="modal-header">
      <h2><i class="fas fa-people-roof" style="margin-right:8px"></i>Family Profile</h2>
      <button class="modal-close" onclick="closeFamilyModal()">×</button>
    </div>
    <div class="modal-body">

      <div class="modal-section">
        <div class="modal-section-title"><i class="fas fa-people-roof"></i> Family Information</div>
        <div class="mgrid">
          <div class="mi span3"><span class="ml">Family Name</span><span class="mv" id="fm-name" style="font-weight:700;color:var(--primary);font-size:15px"></span></div>
          <div class="mi"><span class="ml">Head Last Name</span><span class="mv" id="fm-last"></span></div>
          <div class="mi"><span class="ml">Head First Name</span><span class="mv" id="fm-first"></span></div>
          <div class="mi"><span class="ml">Head Middle Name</span><span class="mv" id="fm-middle"></span></div>
          <div class="mi"><span class="ml">No. of Members</span><span class="mv" id="fm-members"></span></div>
          <div class="mi span2"><span class="ml">Linked Household</span><span class="mv" id="fm-hh"></span></div>
          <div class="mi span3" id="fm-notes-wrap" style="display:none"><span class="ml">Notes</span><span class="mv" id="fm-notes"></span></div>
        </div>
      </div>

    </div>
    <div class="modal-footer">
      <button onclick="closeFamilyModal()" class="btn btn-sm" style="background:#f1f5f9;color:var(--muted);border:1px solid var(--border)">
        <i class="fas fa-times"></i> Close
      </button>
    </div>
  </div>
</div>

<script>
function openFamilyModal(f) {
  document.getElementById('familyModal').classList.add('open');
  document.getElementById('fm-name').textContent    = f.family_name      || '—';
  document.getElementById('fm-last').textContent    = f.head_last_name   || '—';
  document.getElementById('fm-first').textContent   = f.head_first_name  || '—';
  document.getElementById('fm-middle').textContent  = f.head_middle_name || '—';
  document.getElementById('fm-members').textContent = f.member_count ? f.member_count + ' member(s)' : '—';
  document.getElementById('fm-hh').textContent      = f.household_id ? 'HH #' + f.household_id : 'Not linked';
  if (f.notes) {
    document.getElementById('fm-notes').textContent = f.notes;
    document.getElementById('fm-notes-wrap').style.display = '';
  } else {
    document.getElementById('fm-notes-wrap').style.display = 'none';
  }
}
function closeFamilyModal() {
  document.getElementById('familyModal').classList.remove('open');
}
document.getElementById('familyModal').addEventListener('click', function(e) {
  if (e.target === this) closeFamilyModal();
});
</script>

@endsection