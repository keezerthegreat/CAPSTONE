@extends('layouts.app')
@section('page-title', 'Households')
@section('content')
<style>
.bidb-wrap { background:var(--bg); min-height:100vh; padding:28px; }
.page-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.page-hdr h1 { font-size:22px; font-weight:700; color:var(--primary); margin:0; }
.breadcrumb { font-size:13px; color:var(--muted); margin-top:2px; }
.breadcrumb span { color:var(--primary); font-weight:500; }
.res-stats { display:grid; grid-template-columns:repeat(5,1fr); gap:10px; margin-bottom:20px; }
.res-stat { background:var(--card); border-radius:10px; padding:12px 16px; border:1px solid var(--border); box-shadow:0 1px 4px rgba(0,0,0,.05); }
.res-stat .slabel { font-size:11px; font-weight:600; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:4px; }
.res-stat .svalue { font-size:22px; font-weight:800; color:var(--primary); }
.card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); margin-bottom:24px; overflow:hidden; }
.filter-row { display:flex; flex-direction:column; gap:8px; padding:12px 20px; border-bottom:1px solid var(--border); }
.filter-controls { display:flex; gap:6px; flex-wrap:wrap; align-items:center; }
.search-wrap { position:relative; }
.search-wrap input { width:100%; padding:8px 14px 8px 34px; border:1.5px solid var(--border); border-radius:8px; font-size:13px; font-family:inherit; outline:none; box-sizing:border-box; background:var(--card); color:var(--text); }
.search-wrap input:focus { border-color:var(--primary); }
.search-wrap .si { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--muted); font-size:12px; }
.table-wrap { overflow-x:auto; }
table { width:100%; border-collapse:collapse; font-size:13px; }
thead tr { background:#f8fafc; border-bottom:2px solid var(--border); }
th { padding:12px 16px; text-align:left; font-weight:700; color:var(--muted); font-size:11px; text-transform:uppercase; letter-spacing:.06em; white-space:nowrap; }
td { padding:13px 16px; border-bottom:1px solid var(--border); color:var(--text); vertical-align:middle; }
tbody tr { cursor:pointer; }
tbody tr:hover { background:#f0f7ff; }
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
.modal-backdrop { display:none; position:fixed; inset:0; background:rgba(0,0,0,.35); z-index:200; align-items:center; justify-content:center; }
.modal-backdrop.open { display:flex; }
.modal { background:#fff; border-radius:16px; width:580px; max-width:95vw; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.2); }
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
.modal-footer { padding:16px 24px; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; }
#hh-print-frame { display:none; }
@media print {
  body * { visibility:hidden !important; }
  #hh-print-frame, #hh-print-frame * { visibility:visible !important; }
  #hh-print-frame { display:block !important; position:fixed; top:0; left:0; width:100%; padding:15mm; box-sizing:border-box; font-family:Arial,sans-serif; font-size:10pt; color:#000; background:#fff; z-index:99999; }
  @page { size:A4; margin:0; }
}
.hp-header { text-align:center; margin-bottom:10px; border-bottom:2.5px solid #000; padding-bottom:8px; }
.hp-brgy-name { font-size:13pt; font-weight:bold; text-transform:uppercase; }
.hp-brgy-sub  { font-size:9pt; color:#444; }
.hp-doc-title { font-size:14pt; font-weight:bold; text-transform:uppercase; margin-top:6px; }
.hp-doc-no    { font-size:8.5pt; color:#555; margin-top:2px; }
.hp-section   { border:1.5px solid #000; padding:10px 12px; margin-bottom:8px; }
.hp-section-title { font-size:9pt; font-weight:bold; text-transform:uppercase; border-bottom:1px solid #000; padding-bottom:3px; margin-bottom:8px; }
.hp-grid3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:8px; }
.hp-field { display:flex; flex-direction:column; margin-bottom:4px; }
.hp-lbl { font-size:7.5pt; font-weight:bold; color:#555; text-transform:uppercase; margin-bottom:2px; }
.hp-val { border-bottom:1px solid #000; font-size:9.5pt; min-height:16px; padding:1px 2px; }
.hp-mem-table { width:100%; border-collapse:collapse; font-size:9pt; margin-top:4px; }
.hp-mem-table th { padding:5px 8px; background:#f0f0f0; text-align:left; font-size:8pt; font-weight:bold; border:1px solid #000; }
.hp-mem-table td { padding:6px 8px; border:1px solid #000; }
.hp-sign-row { display:grid; grid-template-columns:1fr 1fr 1fr; gap:32px; margin-top:16px; }
.hp-sign-block { display:flex; flex-direction:column; align-items:center; }
.hp-sign-line { border-top:1px solid #000; width:100%; margin-top:36px; }
.hp-sign-lbl  { font-size:7.5pt; text-align:center; margin-top:3px; color:#333; }
.hp-note { font-size:7.5pt; font-style:italic; margin-top:10px; border-top:1px solid #ccc; padding-top:5px; color:#444; }
.mem-table { width:100%; border-collapse:collapse; font-size:12px; }
.mem-table th { padding:7px 10px; background:#f8fafc; text-align:left; font-size:10px; font-weight:700; text-transform:uppercase; color:var(--muted); border-bottom:1.5px solid var(--border); }
.mem-table td { padding:8px 10px; border-bottom:1px solid var(--border); color:var(--text); }
.mem-table tbody tr:last-child td { border-bottom:none; }
.badge-head { background:#fef3c7; color:#92400e; display:inline-flex; align-items:center; padding:2px 7px; border-radius:20px; font-size:10px; font-weight:600; }
</style>

<div class="bidb-wrap">
  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-home" style="margin-right:8px"></i>Household Records</h1>
      <div class="breadcrumb">Home › <span>Households</span></div>
    </div>
    <div style="display:flex;gap:8px;align-items:center">
      @if(auth()->user()->role === 'admin')
      <button type="button" id="bulkDeleteBtn" onclick="submitBulkDelete()"
        style="display:none;background:#fff1f2;color:#be123c;border:1px solid #fecdd3;
               padding:8px 14px;border-radius:8px;font-size:13px;font-weight:600;
               cursor:pointer;align-items:center;gap:6px">
        <i class="fas fa-trash"></i> Delete Selected (<span id="selectedCount">0</span>)
      </button>
      @endif
      <a href="{{ route('residents.location') }}" class="btn" style="background:#f1f5f9;color:var(--text);border:1.5px solid var(--border)">
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

  <div class="res-stats">
    <div class="res-stat"><div class="slabel">Total Households</div><div class="svalue">{{ $totalHouseholds }}</div></div>
    <div class="res-stat"><div class="slabel">Residential</div><div class="svalue">{{ $totalResidential }}</div></div>
    <div class="res-stat"><div class="slabel">Commercial</div><div class="svalue">{{ $totalCommercial }}</div></div>
    <div class="res-stat"><div class="slabel">Rented</div><div class="svalue">{{ $totalRented }}</div></div>
    <div class="res-stat"><div class="slabel">Total Members</div><div class="svalue">{{ $totalMembers }}</div></div>
  </div>

  <div class="card">
    <div class="filter-row">
      <div class="search-wrap">
        <span class="si"><i class="fas fa-search"></i></span>
        <input type="text" id="searchInput" placeholder="Search by household head or number..." value="{{ $filters['search'] }}" onkeydown="if(event.key==='Enter'){hhApplyFilter('search',this.value)}">
      </div>
      <div class="filter-controls">
        <div class="flt-wrap" id="hh-wrap-residency">
          <button class="flt-btn {{ $filters['residency'] ? 'active' : '' }}" id="hh-btn-residency" onclick="toggleHhFlt('residency')">
            <i class="fas fa-house"></i>
            <span id="hh-lbl-residency">{{ $filters['residency'] ?: 'Classification' }}</span>
            <i class="fas fa-chevron-down flt-caret" id="hh-caret-residency" style="{{ $filters['residency'] ? 'display:none' : '' }}"></i>
            <span class="flt-x" id="hh-x-residency" style="{{ $filters['residency'] ? '' : 'display:none' }}" onclick="event.stopPropagation();hhApplyFilter('residency','')">×</span>
          </button>
          <div class="flt-dropdown" id="hh-dd-residency">
            <div class="flt-option {{ !$filters['residency'] ? 'selected':'' }}" onclick="hhApplyFilter('residency','')">All</div>
            <div class="flt-option {{ $filters['residency']==='Residential' ? 'selected':'' }}" onclick="hhApplyFilter('residency','Residential')">Residential</div>
            <div class="flt-option {{ $filters['residency']==='Commercial' ? 'selected':'' }}" onclick="hhApplyFilter('residency','Commercial')">Commercial</div>
            <div class="flt-option {{ $filters['residency']==='Rented' ? 'selected':'' }}" onclick="hhApplyFilter('residency','Rented')">Rented</div>
          </div>
        </div>
        <div class="flt-wrap" id="hh-wrap-sitio">
          <button class="flt-btn {{ $filters['sitio'] ? 'active' : '' }}" id="hh-btn-sitio" onclick="toggleHhFlt('sitio')">
            <i class="fas fa-map-pin"></i>
            <span id="hh-lbl-sitio">{{ $filters['sitio'] ?: 'Purok' }}</span>
            <i class="fas fa-chevron-down flt-caret" id="hh-caret-sitio" style="{{ $filters['sitio'] ? 'display:none' : '' }}"></i>
            <span class="flt-x" id="hh-x-sitio" style="{{ $filters['sitio'] ? '' : 'display:none' }}" onclick="event.stopPropagation();hhApplyFilter('sitio','')">×</span>
          </button>
          <div class="flt-dropdown" id="hh-dd-sitio">
            <div class="flt-option {{ !$filters['sitio'] ? 'selected':'' }}" onclick="hhApplyFilter('sitio','')">All</div>
            @foreach(['Chrysanthemum','Dahlia','Dama de Noche','Ilang-Ilang','Jasmin','Rosal','Sampaguita'] as $sitioOpt)
            <div class="flt-option {{ $filters['sitio']===$sitioOpt ? 'selected':'' }}" onclick="hhApplyFilter('sitio','{{ $sitioOpt }}')">{{ $sitioOpt }}</div>
            @endforeach
          </div>
        </div>
        @if($filters['search'] || $filters['sitio'] || $filters['residency'])
        <button class="flt-btn" onclick="hhClearAll()" style="color:var(--danger)">
          <i class="fas fa-times"></i> Clear Filters
        </button>
        @endif
      </div>
    </div>

    @if(auth()->user()->role === 'admin')
    <div id="selectAllBanner" style="display:none;padding:8px 16px;background:#eff6ff;border-bottom:1px solid #bfdbfe;font-size:13px;color:#1e40af;text-align:center">
      All <strong>{{ $households->perPage() }}</strong> households on this page are selected.
      <a href="#" onclick="selectAllRecords(); return false;" style="font-weight:700;color:#1d4ed8;text-decoration:underline">Select all <strong>{{ $households->total() }}</strong> households</a>
      &nbsp;&middot;&nbsp;<a href="#" onclick="clearSelectAll(); return false;" style="color:#64748b;text-decoration:underline">Clear</a>
    </div>
    @endif
    <div class="table-wrap">
      <table id="householdsTable">
        <thead>
          <tr>
            @if(auth()->user()->role === 'admin')
            <th style="width:40px"><input type="checkbox" id="selectAll" onchange="toggleAll(this)" style="width:16px;height:16px;cursor:pointer" title="Select All"></th>
            @endif
            <th>#</th>
            <th>Household No.</th>
            <th>Household Head</th>
            <th>Purok</th>
            <th>Members</th>
            <th>Classification</th>
            <th>Location</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($households as $index => $hh)
          <tr data-name="{{ strtolower($hh->head_last_name . ' ' . $hh->head_first_name) }}" data-sitio="{{ $hh->sitio }}" data-residency="{{ $hh->residency_type }}" ondblclick='openHouseholdModal(@json($hh))'>
            @if(auth()->user()->role === 'admin')
            <td onclick="event.stopPropagation()">
              <input type="checkbox" class="row-check" value="{{ $hh->id }}" style="width:16px;height:16px;cursor:pointer">
            </td>
            @endif
            <td style="color:var(--muted);font-size:12px">{{ $households->firstItem() + $loop->index }}</td>
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
              @if($hh->residency_type == 'Residential')
                <span class="badge badge-perm">Residential</span>
              @elseif($hh->residency_type == 'Commercial')
                <span class="badge badge-trans">Commercial</span>
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
                <button onclick='event.stopPropagation();openHouseholdModal(@json($hh))' class="btn btn-sm btn-view"><i class="fas fa-eye"></i> View</button>
                <a href="{{ route('households.edit', $hh->id) }}" class="btn btn-sm btn-edit" onclick="event.stopPropagation()"><i class="fas fa-edit"></i> Edit</a>
                @if(auth()->user()->role === 'admin')
                <form method="POST" action="{{ route('households.destroy', $hh->id) }}" style="display:inline" onsubmit="return confirmDelete(this,'Delete Household #{{ $hh->household_number }}? All linked data may be affected.')" onclick="event.stopPropagation()">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-delete"><i class="fas fa-trash"></i> Delete</button>
                </form>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="9"><div class="empty-state"><div style="font-size:40px;opacity:.3;margin-bottom:12px"><i class="fas fa-home"></i></div><div style="font-weight:600;margin-bottom:4px">No households found</div><div>Add your first household to get started.</div></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($households->hasPages())
    <div style="padding:12px 16px;display:flex;align-items:center;justify-content:space-between;border-top:1px solid var(--border);font-size:13px;color:var(--muted)">
      <span>Showing {{ $households->firstItem() }}–{{ $households->lastItem() }} of {{ $households->total() }} households</span>
      {{ $households->links() }}
    </div>
    @endif
  </div>
</div>

<script>
const hhFltKeys = ['residency', 'sitio'];
function positionDropdown(el, btn) {
  const r = btn.getBoundingClientRect();
  el.style.top  = (r.bottom + 6) + 'px';
  el.style.left = r.left + 'px';
  requestAnimationFrame(function() {
    if (el.offsetWidth && r.left + el.offsetWidth > window.innerWidth - 8)
      el.style.left = Math.max(8, window.innerWidth - el.offsetWidth - 8) + 'px';
  });
}
function toggleHhFlt(key) {
  const isOpen = document.getElementById('hh-dd-' + key).classList.contains('open');
  hhFltKeys.forEach(k => document.getElementById('hh-dd-' + k).classList.remove('open'));
  if (!isOpen) {
    const dd = document.getElementById('hh-dd-' + key);
    positionDropdown(dd, document.getElementById('hh-btn-' + key));
    dd.classList.add('open');
  }
}
function hhApplyFilter(key, val) {
  document.getElementById('hh-dd-' + key)?.classList.remove('open');
  const url = new URL(window.location.href);
  if (val) { url.searchParams.set(key, val); } else { url.searchParams.delete(key); }
  url.searchParams.delete('page');
  window.location = url.toString();
}
function hhClearAll() {
  const url = new URL(window.location.href);
  ['search','sitio','residency','page'].forEach(k => url.searchParams.delete(k));
  window.location = url.toString();
}
document.addEventListener('click', function(e) {
  hhFltKeys.forEach(key => {
    const wrap = document.getElementById('hh-wrap-' + key);
    if (wrap && !wrap.contains(e.target)) document.getElementById('hh-dd-' + key).classList.remove('open');
  });
});

// ── BULK DELETE ──
let selectAllMode = false;
function toggleAll(source) {
  document.querySelectorAll('.row-check').forEach(cb => cb.checked = source.checked);
  selectAllMode = false;
  updateBulkBtn();
  document.getElementById('selectAllBanner').style.display = source.checked ? 'block' : 'none';
}
document.addEventListener('change', function(e) {
  if (e.target.classList.contains('row-check')) { selectAllMode = false; updateBulkBtn(); }
});
function updateBulkBtn() {
  const checked = document.querySelectorAll('.row-check:checked');
  const btn = document.getElementById('bulkDeleteBtn');
  if (!btn) return;
  document.getElementById('selectedCount').textContent = selectAllMode ? '{{ $households->total() }}' : checked.length;
  btn.style.display = (checked.length > 0 || selectAllMode) ? 'inline-flex' : 'none';
}
function selectAllRecords() {
  selectAllMode = true;
  document.getElementById('selectAllBanner').innerHTML =
    'All <strong>{{ $households->total() }}</strong> households are selected. ' +
    '<a href="#" onclick="clearSelectAll(); return false;" style="color:#be123c;font-weight:700;text-decoration:underline">Clear selection</a>';
  updateBulkBtn();
}
function clearSelectAll() {
  selectAllMode = false;
  document.getElementById('selectAll').checked = false;
  document.querySelectorAll('.row-check').forEach(cb => cb.checked = false);
  updateBulkBtn();
  document.getElementById('selectAllBanner').style.display = 'none';
}
function submitBulkDelete() {
  const form = document.getElementById('bulkForm');
  form.querySelectorAll('input[name="ids[]"], input[name="select_all"]').forEach(el => el.remove());
  if (selectAllMode) {
    const inp = document.createElement('input');
    inp.type = 'hidden'; inp.name = 'select_all'; inp.value = '1';
    form.appendChild(inp);
    confirmDelete(form, 'Delete ALL {{ $households->total() }} households? This cannot be undone.');
  } else {
    const checked = document.querySelectorAll('.row-check:checked');
    if (!checked.length) return;
    checked.forEach(cb => {
      const inp = document.createElement('input');
      inp.type = 'hidden'; inp.name = 'ids[]'; inp.value = cb.value;
      form.appendChild(inp);
    });
    confirmDelete(form, 'Delete ' + checked.length + ' selected household(s)? This cannot be undone.');
  }
}
</script>

<form id="bulkForm" method="POST" action="{{ route('households.bulkDestroy') }}" style="display:none">
  @csrf
  @method('DELETE')
</form>

<!-- Household Print Frame -->
<div id="hh-print-frame">
  <div class="hp-header">
    <div class="hp-brgy-name">Barangay Cogon</div>
    <div class="hp-brgy-sub">Ormoc City, Leyte | Region VIII (Eastern Visayas)</div>
    <div class="hp-doc-title">Household Record</div>
    <div class="hp-doc-no">Household No.: <span id="hp-hhno" style="font-weight:bold"></span></div>
  </div>
  <div class="hp-section">
    <div class="hp-section-title">I. Household Information</div>
    <div class="hp-grid3">
      <div class="hp-field"><span class="hp-lbl">Household Number</span><span id="hp-num" class="hp-val"></span></div>
      <div class="hp-field"><span class="hp-lbl">Residency / Classification</span><span id="hp-type" class="hp-val"></span></div>
      <div class="hp-field"><span class="hp-lbl">No. of Members</span><span id="hp-count" class="hp-val"></span></div>
    </div>
    <div class="hp-field" style="margin-top:6px"><span class="hp-lbl">Notes / Remarks</span><span id="hp-notes" class="hp-val" style="min-height:20px"></span></div>
  </div>
  <div class="hp-section">
    <div class="hp-section-title">II. Household Head</div>
    <div class="hp-grid3">
      <div class="hp-field"><span class="hp-lbl">Last Name</span><span id="hp-hlast" class="hp-val"></span></div>
      <div class="hp-field"><span class="hp-lbl">First Name</span><span id="hp-hfirst" class="hp-val"></span></div>
      <div class="hp-field"><span class="hp-lbl">Middle Name</span><span id="hp-hmid" class="hp-val"></span></div>
    </div>
  </div>
  <div class="hp-section">
    <div class="hp-section-title">III. Complete Address</div>
    <div class="hp-grid3">
      <div class="hp-field"><span class="hp-lbl">Purok</span><span id="hp-sitio" class="hp-val"></span></div>
      <div class="hp-field"><span class="hp-lbl">Street / Sitio</span><span id="hp-street" class="hp-val"></span></div>
      <div class="hp-field"><span class="hp-lbl">Barangay</span><span id="hp-brgy" class="hp-val"></span></div>
      <div class="hp-field"><span class="hp-lbl">City / Municipality</span><span id="hp-city" class="hp-val"></span></div>
      <div class="hp-field"><span class="hp-lbl">Province</span><span id="hp-prov" class="hp-val"></span></div>
      <div class="hp-field"><span class="hp-lbl">GPS Coordinates</span><span id="hp-gps" class="hp-val"></span></div>
    </div>
  </div>
  <div class="hp-section">
    <div class="hp-section-title">IV. Household Members</div>
    <table class="hp-mem-table">
      <thead><tr><th style="width:30px">#</th><th>Full Name</th><th>Sex / Age</th><th>Civil Status</th><th>Role</th></tr></thead>
      <tbody id="hp-mem-tbody"></tbody>
    </table>
  </div>
  <div class="hp-sign-row">
    <div class="hp-sign-block"><div class="hp-sign-line"></div><div class="hp-sign-lbl">Household Head Signature</div></div>
    <div class="hp-sign-block"><div class="hp-sign-line"></div><div class="hp-sign-lbl">Barangay Secretary</div></div>
    <div class="hp-sign-block"><div class="hp-sign-line"></div><div class="hp-sign-lbl">Barangay Captain</div></div>
  </div>
  <div class="hp-note">Printed: <span id="hp-today"></span> | This document is an official record of Barangay Cogon, Ormoc City.</div>
</div>

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
          <div class="mi"><span class="ml">Purok</span><span class="mv" id="hm-sitio"></span></div>
          <div class="mi"><span class="ml">Street / Sitio</span><span class="mv" id="hm-street"></span></div>
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
      <div style="display:flex;gap:8px">
        <button type="button" onclick="printHouseholdForm()" class="btn btn-sm" style="background:#fff;color:#374151;border:1.5px solid #d1d5db"><i class="fas fa-print"></i> Print Record</button>
        <button onclick="closeHouseholdModal()" class="btn btn-sm" style="background:#f1f5f9;color:var(--muted);border:1px solid var(--border)"><i class="fas fa-times"></i> Close</button>
      </div>
    </div>
  </div>
</div>

<script>
var _hhData = null;
function printHouseholdForm() {
  var h = _hhData; if (!h) return;
  var today = new Date().toLocaleDateString('en-US',{month:'long',day:'2-digit',year:'numeric'});
  var members = h.members || [];
  function t(id, val) { var e = document.getElementById(id); if(e) e.textContent = val || ''; }
  t('hp-num', h.household_number); t('hp-type', h.residency_type);
  t('hp-count', h.member_count ? h.member_count + ' member(s)' : '—');
  t('hp-notes', h.notes); t('hp-hlast', h.head_last_name); t('hp-hfirst', h.head_first_name);
  t('hp-hmid', h.head_middle_name); t('hp-sitio', h.sitio); t('hp-street', h.street);
  t('hp-brgy', h.barangay); t('hp-city', h.city); t('hp-prov', h.province);
  t('hp-gps', (h.latitude && h.longitude) ? h.latitude + ', ' + h.longitude : 'Not recorded');
  t('hp-today', today); t('hp-hhno', h.household_number);
  var tbody = document.getElementById('hp-mem-tbody');
  if (!members.length) { tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;font-style:italic;color:#666">No members linked.</td></tr>'; }
  else { tbody.innerHTML = members.map(function(m, i) {
    var name = (m.last_name||'') + ', ' + (m.first_name||'') + (m.middle_name ? ' '+m.middle_name : '');
    var role = (m.id === h.head_resident_id) ? 'Head' : 'Member';
    return '<tr><td style="text-align:center">'+(i+1)+'</td><td style="font-weight:600">'+name+'</td><td>'+(m.gender||'—')+' / '+(m.age||'—')+' yrs</td><td>'+(m.civil_status||'—')+'</td><td>'+role+'</td></tr>';
  }).join(''); }
  window.print();
}
function openHouseholdModal(h) {
  _hhData = h;
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
  if (!members.length) { body.innerHTML = '<p style="color:var(--muted);font-size:13px;font-style:italic;margin:0">No members linked yet.</p>'; }
  else {
    let rows = members.map((m, i) => {
      const isHead = m.id === h.head_resident_id;
      const role = isHead ? '<span class="badge-head"><i class="fas fa-crown" style="margin-right:3px;font-size:9px"></i>Head</span>' : '<span style="color:var(--muted);font-size:11px">Member</span>';
      const name = (m.last_name || '') + ', ' + (m.first_name || '') + (m.middle_name ? ' ' + m.middle_name : '');
      return `<tr><td style="color:var(--muted);font-size:11px">${i+1}</td><td style="font-weight:600">${name}</td><td>${m.gender || '—'} / ${m.age || '—'} yrs</td><td>${m.civil_status || '—'}</td><td>${role}</td></tr>`;
    }).join('');
    body.innerHTML = `<table class="mem-table"><thead><tr><th>#</th><th>Full Name</th><th>Sex / Age</th><th>Civil Status</th><th>Role</th></tr></thead><tbody>${rows}</tbody></table>`;
  }
  document.getElementById('hm-edit-link').innerHTML = `<a href="/households/${h.id}/edit" style="color:var(--primary);font-weight:600;text-decoration:none"><i class="fas fa-edit" style="margin-right:4px"></i>Edit this household</a>`;
}
function closeHouseholdModal() { document.getElementById('householdModal').classList.remove('open'); }
document.getElementById('householdModal').addEventListener('click', function(e) { if (e.target === this) closeHouseholdModal(); });
</script>

@endsection