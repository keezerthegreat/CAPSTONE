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
.modal { background:#fff; border-radius:16px; width:580px; max-width:95vw; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.2); }
.modal-header { padding:18px 22px 14px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.modal-header h2 { font-size:15px; font-weight:700; color:var(--primary); margin:0; }
.modal-close { background:none; border:none; font-size:22px; color:var(--muted); cursor:pointer; line-height:1; padding:0; }
.modal-body { padding:20px 22px; }
.modal-section { margin-bottom:18px; }
.modal-section-title { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; margin-bottom:10px; padding-bottom:5px; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:6px; }
.mgrid { display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; }
.mi { display:flex; flex-direction:column; gap:3px; }
.mi .ml { font-size:10px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; }
.mi .mv { font-size:13px; color:var(--text); font-weight:500; background:#f8fafc; border:1px solid var(--border); border-radius:7px; padding:6px 10px; }
.mi.span2 { grid-column:span 2; }
.mi.span3 { grid-column:span 3; }
.mem-table { width:100%; border-collapse:collapse; font-size:12px; margin-top:4px; }
.mem-table th { padding:7px 10px; background:#f8fafc; text-align:left; font-size:10px; font-weight:700; text-transform:uppercase; color:var(--muted); border-bottom:1.5px solid var(--border); }
.mem-table td { padding:8px 10px; border-bottom:1px solid var(--border); color:var(--text); }
.mem-table tbody tr:last-child td { border-bottom:none; }
.badge-head { background:#fef3c7; color:#92400e; display:inline-flex; align-items:center; padding:2px 7px; border-radius:20px; font-size:10px; font-weight:600; }
.modal-footer { padding:14px 22px; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; }
#fam-print-frame { display:none; }
@media print {
  body * { visibility:hidden !important; }
  #fam-print-frame, #fam-print-frame * { visibility:visible !important; }
  #fam-print-frame { display:block !important; position:fixed; top:0; left:0; width:100%; padding:15mm; box-sizing:border-box; font-family:Arial,sans-serif; font-size:10pt; color:#000; background:#fff; z-index:99999; }
  @page { size:A4; margin:0; }
}
.fp-header { text-align:center; margin-bottom:10px; border-bottom:2.5px solid #000; padding-bottom:8px; }
.fp-brgy-name { font-size:13pt; font-weight:bold; text-transform:uppercase; }
.fp-brgy-sub  { font-size:9pt; color:#444; margin-top:1px; }
.fp-doc-title { font-size:14pt; font-weight:bold; text-transform:uppercase; margin-top:6px; }
.fp-doc-sub   { font-size:8.5pt; color:#555; margin-top:2px; }
.fp-section   { border:1.5px solid #000; padding:10px 12px; margin-bottom:8px; }
.fp-section-title { font-size:9pt; font-weight:bold; text-transform:uppercase; border-bottom:1px solid #000; padding-bottom:3px; margin-bottom:8px; }
.fp-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:8px; }
.fp-grid3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:8px; }
.fp-field { display:flex; flex-direction:column; margin-bottom:4px; }
.fp-lbl { font-size:7.5pt; font-weight:bold; color:#555; text-transform:uppercase; margin-bottom:2px; }
.fp-val { border-bottom:1px solid #000; font-size:9.5pt; min-height:16px; padding:1px 2px; }
.fp-mem-table { width:100%; border-collapse:collapse; font-size:9pt; margin-top:4px; }
.fp-mem-table th { padding:5px 8px; background:#f0f0f0; text-align:left; font-size:8pt; font-weight:bold; border:1px solid #000; }
.fp-mem-table td { padding:6px 8px; border:1px solid #000; }
.fp-sign-row { display:grid; grid-template-columns:1fr 1fr 1fr; gap:32px; margin-top:16px; }
.fp-sign-block { display:flex; flex-direction:column; align-items:center; }
.fp-sign-line  { border-top:1px solid #000; width:100%; margin-top:36px; }
.fp-sign-lbl   { font-size:7.5pt; text-align:center; margin-top:3px; color:#333; }
.fp-note { font-size:7.5pt; font-style:italic; margin-top:10px; border-top:1px solid #ccc; padding-top:5px; color:#444; }
</style>

<div class="bidb-wrap">
  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-people-roof" style="margin-right:8px;font-size:20px"></i>Family Records</h1>
      <div class="breadcrumb">Home › <span>Families</span></div>
    </div>
    <div style="display:flex;gap:8px;align-items:center">
      @if(auth()->user()->role == 'admin')
      <button type="button" id="bulkDeleteBtn" onclick="submitBulkDelete()"
        style="display:none;background:#fff1f2;color:#be123c;border:1px solid #fecdd3;
               padding:8px 14px;border-radius:8px;font-size:13px;font-weight:600;
               cursor:pointer;align-items:center;gap:6px">
        <i class="fas fa-trash"></i> Delete Selected (<span id="selectedCount">0</span>)
      </button>
      @endif
      <a href="{{ route('families.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Family
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif

  <div class="fam-stats">
    <div class="fam-stat"><div class="slabel">Total Families</div><div class="svalue">{{ $totalFamilies }}</div></div>
    <div class="fam-stat"><div class="slabel">Total Members</div><div class="svalue">{{ $totalMembers }}</div></div>
    <div class="fam-stat"><div class="slabel">Linked to Households</div><div class="svalue">{{ $totalLinked }}</div></div>
  </div>

  <div class="card">
    <div class="filter-row">
      <div class="search-wrap">
        <span class="si"><i class="fas fa-search"></i></span>
        <input type="text" id="searchInput" placeholder="Search by family name or head..." value="{{ $filters['search'] }}" onkeydown="if(event.key==='Enter'){famApplyFilter('search',this.value)}">
      </div>
      <div class="filter-controls">
        <div class="flt-wrap" id="fam-wrap-sitio">
          <button class="flt-btn {{ $filters['sitio'] ? 'active' : '' }}" id="fam-btn-sitio" onclick="toggleFamFlt('sitio')">
            <i class="fas fa-map-pin"></i>
            <span id="fam-lbl-sitio">{{ $filters['sitio'] ? ($filters['sitio'] === '__none__' ? 'No Household' : $filters['sitio']) : 'Purok' }}</span>
            <i class="fas fa-chevron-down flt-caret" id="fam-caret-sitio" style="{{ $filters['sitio'] ? 'display:none' : '' }}"></i>
            <span class="flt-x" id="fam-x-sitio" style="{{ $filters['sitio'] ? '' : 'display:none' }}" onclick="event.stopPropagation();famApplyFilter('sitio','')">×</span>
          </button>
          <div class="flt-dropdown" id="fam-dd-sitio">
            <div class="flt-option {{ !$filters['sitio'] ? 'selected':'' }}" onclick="famApplyFilter('sitio','')">All Puroks</div>
            @foreach(['Chrysanthemum','Dahlia','Dama de Noche','Ilang-Ilang','Jasmin','Rosal','Sampaguita'] as $s)
            <div class="flt-option {{ $filters['sitio']===$s ? 'selected':'' }}" onclick="famApplyFilter('sitio','{{ $s }}')">{{ $s }}</div>
            @endforeach
            <div class="flt-option {{ $filters['sitio']==='__none__' ? 'selected':'' }}" onclick="famApplyFilter('sitio','__none__')">Not linked to household</div>
          </div>
        </div>
        @if($filters['search'] || $filters['sitio'])
        <button class="flt-btn" onclick="famClearAll()" style="color:var(--danger)">
          <i class="fas fa-times"></i> Clear Filters
        </button>
        @endif
      </div>
    </div>

    @if(auth()->user()->role == 'admin')
    <div id="selectAllBanner" style="display:none;padding:8px 16px;background:#eff6ff;border-bottom:1px solid #bfdbfe;font-size:13px;color:#1e40af;text-align:center">
      All <strong>{{ $families->perPage() }}</strong> families on this page are selected.
      <a href="#" onclick="selectAllRecords(); return false;" style="font-weight:700;color:#1d4ed8;text-decoration:underline">Select all <strong>{{ $families->total() }}</strong> families</a>
      &nbsp;&middot;&nbsp;<a href="#" onclick="clearSelectAll(); return false;" style="color:#64748b;text-decoration:underline">Clear</a>
    </div>
    @endif
    <div class="table-wrap">
      <table id="familiesTable">
        <thead>
          <tr>
            @if(auth()->user()->role == 'admin')
            <th style="width:40px"><input type="checkbox" id="selectAll" onchange="toggleAll(this)" style="width:16px;height:16px;cursor:pointer" title="Select All"></th>
            @endif
            <th>#</th>
            <th>Family Name</th>
            <th>Head of Family</th>
            <th>Members</th>
            <th>Linked Household</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($families as $family)
          <tr ondblclick='openFamilyModal(@json($family))'>
            @if(auth()->user()->role == 'admin')
            <td onclick="event.stopPropagation()">
              <input type="checkbox" class="row-check" value="{{ $family->id }}" style="width:16px;height:16px;cursor:pointer">
            </td>
            @endif
            <td style="color:var(--muted);font-size:12px">{{ $families->firstItem() + $loop->index }}</td>
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
                <button onclick='event.stopPropagation();openFamilyModal(@json($family))' class="btn btn-sm btn-view">
                  <i class="fas fa-eye"></i> View
                </button>
                <a href="{{ route('families.edit', $family->id) }}" class="btn btn-sm btn-edit" onclick="event.stopPropagation()">
                  <i class="fas fa-edit"></i> Edit
                </a>
                @if(auth()->user()->role == 'admin')
                <form method="POST" action="{{ route('families.destroy', $family->id) }}" style="display:inline" onsubmit="return confirmDelete(this,'Delete the {{ addslashes($family->family_name) }} family record? This cannot be undone.')" onclick="event.stopPropagation()">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-delete"><i class="fas fa-trash"></i> Delete</button>
                </form>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="{{ auth()->user()->role == 'admin' ? 7 : 6 }}">
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

    @if($families->hasPages())
    <div style="padding:12px 16px;display:flex;align-items:center;justify-content:space-between;border-top:1px solid var(--border);font-size:13px;color:var(--muted)">
      <span>Showing {{ $families->firstItem() }}–{{ $families->lastItem() }} of {{ $families->total() }} families</span>
      {{ $families->links() }}
    </div>
    @endif
  </div>
</div>

<form id="bulkForm" method="POST" action="{{ route('families.bulkDestroy') }}" style="display:none">
  @csrf
  @method('DELETE')
</form>

<!-- Family Print Frame -->
<div id="fam-print-frame">
  <div class="fp-header">
    <div class="fp-brgy-name">Barangay Cogon</div>
    <div class="fp-brgy-sub">Ormoc City, Leyte | Region VIII (Eastern Visayas)</div>
    <div class="fp-doc-title">Family Record</div>
    <div class="fp-doc-sub">Family Name: <span id="fp-fname" style="font-weight:bold"></span></div>
  </div>
  <div class="fp-section">
    <div class="fp-section-title">I. Family Information</div>
    <div class="fp-grid3">
      <div class="fp-field"><span class="fp-lbl">Family Name</span><span id="fp-fname2" class="fp-val" style="font-weight:bold"></span></div>
      <div class="fp-field"><span class="fp-lbl">No. of Members</span><span id="fp-fcount" class="fp-val"></span></div>
      <div class="fp-field"><span class="fp-lbl">Linked Household</span><span id="fp-fhh" class="fp-val"></span></div>
    </div>
    <div class="fp-field" style="margin-top:6px"><span class="fp-lbl">Notes / Remarks</span><span id="fp-fnotes" class="fp-val" style="min-height:20px"></span></div>
  </div>
  <div class="fp-section">
    <div class="fp-section-title">II. Head of Family</div>
    <div class="fp-grid3">
      <div class="fp-field"><span class="fp-lbl">Full Name</span><span id="fp-fhead" class="fp-val" style="font-weight:bold"></span></div>
      <div class="fp-field"><span class="fp-lbl">Sex</span><span id="fp-hgender" class="fp-val"></span></div>
      <div class="fp-field"><span class="fp-lbl">Age</span><span id="fp-hage" class="fp-val"></span></div>
      <div class="fp-field"><span class="fp-lbl">Civil Status</span><span id="fp-hcivil" class="fp-val"></span></div>
      <div class="fp-field"><span class="fp-lbl">Occupation</span><span id="fp-hocc" class="fp-val"></span></div>
    </div>
  </div>
  <div class="fp-section">
    <div class="fp-section-title">III. Family Members</div>
    <table class="fp-mem-table">
      <thead><tr><th style="width:30px">#</th><th>Full Name</th><th>Sex / Age</th><th>Civil Status</th><th>Role</th><th>HH Role</th></tr></thead>
      <tbody id="fp-mem-tbody"></tbody>
    </table>
  </div>
  <div class="fp-sign-row">
    <div class="fp-sign-block"><div class="fp-sign-line"></div><div class="fp-sign-lbl">Head of Family Signature</div></div>
    <div class="fp-sign-block"><div class="fp-sign-line"></div><div class="fp-sign-lbl">Barangay Secretary</div></div>
    <div class="fp-sign-block"><div class="fp-sign-line"></div><div class="fp-sign-lbl">Barangay Captain</div></div>
  </div>
  <div class="fp-note">Printed: <span id="fp-today"></span> | This document is an official record of Barangay Cogon, Ormoc City.</div>
</div>

<!-- Family Profile Modal -->
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
          <div class="mi span2"><span class="ml">Head of Family</span><span class="mv" id="fm-head"></span></div>
          <div class="mi"><span class="ml">No. of Members</span><span class="mv" id="fm-members"></span></div>
          <div class="mi span3"><span class="ml">Linked Household</span><span class="mv" id="fm-hh"></span></div>
          <div class="mi span3" id="fm-notes-wrap" style="display:none"><span class="ml">Notes</span><span class="mv" id="fm-notes"></span></div>
        </div>
      </div>
      <div class="modal-section">
        <div class="modal-section-title"><i class="fas fa-users"></i> Family Members</div>
        <div id="fm-members-body"></div>
      </div>
    </div>
    <div class="modal-footer">
      <span style="font-size:12px;color:var(--muted)" id="fm-edit-link"></span>
      <div style="display:flex;gap:8px">
        <button type="button" onclick="printFamilyForm()" class="btn btn-sm" style="background:#fff;color:#374151;border:1.5px solid #d1d5db"><i class="fas fa-print"></i> Print Record</button>
        <button onclick="closeFamilyModal()" class="btn btn-sm" style="background:#f1f5f9;color:var(--muted);border:1px solid var(--border)"><i class="fas fa-times"></i> Close</button>
      </div>
    </div>
  </div>
</div>

<script>
var _famData = null;

function printFamilyForm() {
  var f = _famData; if (!f) return;
  var today = new Date().toLocaleDateString('en-US',{month:'long',day:'2-digit',year:'numeric'});
  var hr = f.head_resident || {};
  var members = f.members || [];
  function t(id, val) { var e = document.getElementById(id); if(e) e.textContent = val || ''; }
  t('fp-fname', f.family_name); t('fp-fname2', f.family_name);
  t('fp-fhead', (f.head_last_name||'') + ', ' + (f.head_first_name||'') + (f.head_middle_name ? ' '+f.head_middle_name : ''));
  t('fp-fcount', (f.member_count || 0) + ' member(s)');
  t('fp-fhh', f.household ? 'HH #' + f.household.household_number + ' — ' + (f.household.sitio||'') : 'Not linked');
  t('fp-fnotes', f.notes); t('fp-hgender', hr.gender);
  t('fp-hage', hr.age ? hr.age + ' yrs old' : ''); t('fp-hcivil', hr.civil_status);
  t('fp-hocc', hr.occupation); t('fp-today', today);
  var tbody = document.getElementById('fp-mem-tbody');
  var headName = (f.head_last_name||'') + ', ' + (f.head_first_name||'') + (f.head_middle_name ? ' '+f.head_middle_name : '');
  var headRow = '<tr><td style="text-align:center">1</td><td style="font-weight:600">'+headName+'</td><td>'+(hr.gender||'—')+' / '+(hr.age||'—')+' yrs</td><td>'+(hr.civil_status||'—')+'</td><td>Head</td><td>'+(f.head_role||'—')+'</td></tr>';
  var others = members.filter(function(m){ return m.id !== f.head_resident_id; });
  var otherRows = others.map(function(m, i) {
    var name = (m.last_name||'') + ', ' + (m.first_name||'') + (m.middle_name ? ' '+m.middle_name : '');
    var hhRole = m.family_role === 'head' ? 'HH Head' : (m.family_role || '—');
    return '<tr><td style="text-align:center">'+(i+2)+'</td><td style="font-weight:600">'+name+'</td><td>'+(m.gender||'—')+' / '+(m.age||'—')+' yrs</td><td>'+(m.civil_status||'—')+'</td><td>Member</td><td>'+hhRole+'</td></tr>';
  }).join('');
  tbody.innerHTML = headRow + otherRows;
  window.print();
}

function openFamilyModal(f) {
  _famData = f;
  document.getElementById('familyModal').classList.add('open');
  document.getElementById('fm-name').textContent    = f.family_name || '—';
  document.getElementById('fm-head').textContent    = (f.head_last_name || '—') + ', ' + (f.head_first_name || '') + (f.head_middle_name ? ' ' + f.head_middle_name : '');
  document.getElementById('fm-members').textContent = (f.member_count || 0) + ' member(s)';
  document.getElementById('fm-hh').textContent      = f.household ? 'HH #' + f.household.household_number + ' — ' + f.household.sitio : 'Not linked';
  if (f.notes) { document.getElementById('fm-notes').textContent = f.notes; document.getElementById('fm-notes-wrap').style.display = ''; }
  else { document.getElementById('fm-notes-wrap').style.display = 'none'; }
  const body = document.getElementById('fm-members-body');
  const members = f.members || [];
  const headName = (f.head_last_name || '—') + ', ' + (f.head_first_name || '') + (f.head_middle_name ? ' ' + f.head_middle_name : '');
  const hr = f.head_resident || {};
  const headRow = `<tr><td style="color:var(--muted);font-size:11px">1</td><td style="font-weight:600">${headName}</td><td>${hr.gender || '—'} / ${hr.age || '—'} yrs</td><td>${hr.civil_status || '—'}</td><td><span class="badge-head"><i class="fas fa-crown" style="margin-right:3px;font-size:9px"></i>Head</span></td><td>${f.head_role ? `<span style="background:#eff6ff;color:#1d4ed8;padding:2px 7px;border-radius:20px;font-size:10px;font-weight:600">${f.head_role}</span>` : '<span style="color:var(--muted);font-size:11px">—</span>'}</td></tr>`;
  const otherMembers = members.filter(m => m.id !== f.head_resident_id);
  let rows = headRow + otherMembers.map((m, i) => {
    const name = (m.last_name || '') + ', ' + (m.first_name || '') + (m.middle_name ? ' ' + m.middle_name : '');
    let hhRoleBadge;
    if (m.family_role === 'head') {
      hhRoleBadge = `<span style="background:#f3f4f6;color:#6b7280;padding:2px 7px;border-radius:20px;font-size:10px;font-weight:600">HH Head</span>`;
    } else if (m.family_role === 'member') {
      hhRoleBadge = `<span style="color:var(--muted);font-size:11px">member</span>`;
    } else {
      hhRoleBadge = '<span style="color:var(--muted);font-size:11px">—</span>';
    }
    return `<tr><td style="color:var(--muted);font-size:11px">${i+2}</td><td style="font-weight:600">${name}</td><td>${m.gender || '—'} / ${m.age || '—'} yrs</td><td>${m.civil_status || '—'}</td><td><span style="color:var(--muted);font-size:11px">Member</span></td><td>${hhRoleBadge}</td></tr>`;
  }).join('');
  body.innerHTML = `<table class="mem-table"><thead><tr><th>#</th><th>Full Name</th><th>Sex / Age</th><th>Civil Status</th><th>Role</th><th>HH Role</th></tr></thead><tbody>${rows}</tbody></table>`;
  document.getElementById('fm-edit-link').innerHTML = `<a href="/families/${f.id}/edit" style="color:var(--primary);font-weight:600;text-decoration:none"><i class="fas fa-edit" style="margin-right:4px"></i>Edit this family</a>`;
}
function closeFamilyModal() { document.getElementById('familyModal').classList.remove('open'); }
document.getElementById('familyModal').addEventListener('click', function(e) { if (e.target === this) closeFamilyModal(); });

function positionDropdown(el, btn) {
  const r = btn.getBoundingClientRect();
  el.style.top  = (r.bottom + 6) + 'px';
  el.style.left = r.left + 'px';
  requestAnimationFrame(function() {
    if (el.offsetWidth && r.left + el.offsetWidth > window.innerWidth - 8)
      el.style.left = Math.max(8, window.innerWidth - el.offsetWidth - 8) + 'px';
  });
}
function toggleFamFlt(key) {
  const isOpen = document.getElementById('fam-dd-' + key).classList.contains('open');
  if (!isOpen) {
    const dd = document.getElementById('fam-dd-' + key);
    positionDropdown(dd, document.getElementById('fam-btn-' + key));
    dd.classList.add('open');
  } else {
    document.getElementById('fam-dd-' + key).classList.remove('open');
  }
}
function famApplyFilter(key, val) {
  document.getElementById('fam-dd-' + key)?.classList.remove('open');
  const url = new URL(window.location.href);
  if (val) { url.searchParams.set(key, val); } else { url.searchParams.delete(key); }
  url.searchParams.delete('page');
  window.location = url.toString();
}
function famClearAll() {
  const url = new URL(window.location.href);
  ['search','sitio','page'].forEach(k => url.searchParams.delete(k));
  window.location = url.toString();
}
document.addEventListener('click', function(e) {
  const wrap = document.getElementById('fam-wrap-sitio');
  if (wrap && !wrap.contains(e.target)) document.getElementById('fam-dd-sitio').classList.remove('open');
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
  document.getElementById('selectedCount').textContent = selectAllMode ? '{{ $families->total() }}' : checked.length;
  btn.style.display = (checked.length > 0 || selectAllMode) ? 'inline-flex' : 'none';
}
function selectAllRecords() {
  selectAllMode = true;
  document.getElementById('selectAllBanner').innerHTML =
    'All <strong>{{ $families->total() }}</strong> families are selected. ' +
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
    confirmDelete(form, 'Delete ALL {{ $families->total() }} families? This cannot be undone.');
  } else {
    const checked = document.querySelectorAll('.row-check:checked');
    if (!checked.length) return;
    checked.forEach(cb => {
      const inp = document.createElement('input');
      inp.type = 'hidden'; inp.name = 'ids[]'; inp.value = cb.value;
      form.appendChild(inp);
    });
    confirmDelete(form, 'Delete ' + checked.length + ' selected family/families? This cannot be undone.');
  }
}
</script>

@endsection