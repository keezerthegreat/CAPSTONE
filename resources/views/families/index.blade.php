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
.dblclick-hint { font-size:11px; color:var(--muted); font-style:italic; padding:6px 20px 10px; }
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
/* Modal */
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
/* Members table inside modal */
.mem-table { width:100%; border-collapse:collapse; font-size:12px; margin-top:4px; }
.mem-table th { padding:7px 10px; background:#f8fafc; text-align:left; font-size:10px; font-weight:700; text-transform:uppercase; color:var(--muted); border-bottom:1.5px solid var(--border); }
.mem-table td { padding:8px 10px; border-bottom:1px solid var(--border); color:var(--text); }
.mem-table tbody tr:last-child td { border-bottom:none; }
.badge-head { background:#fef3c7; color:#92400e; display:inline-flex; align-items:center; padding:2px 7px; border-radius:20px; font-size:10px; font-weight:600; }
.modal-footer { padding:14px 22px; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; }
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
    <div class="fam-stat"><div class="slabel">Total Families</div><div class="svalue" id="stat-families">{{ $families->count() }}</div></div>
    <div class="fam-stat"><div class="slabel">Total Members</div><div class="svalue" id="stat-members">{{ $families->sum('member_count') }}</div></div>
    <div class="fam-stat"><div class="slabel">Linked to Households</div><div class="svalue" id="stat-linked">{{ $families->whereNotNull('household_id')->count() }}</div></div>
  </div>

  <div class="card">
    <div class="filter-row">
      <div class="search-wrap">
        <span class="si"><i class="fas fa-search"></i></span>
        <input type="text" id="searchInput" placeholder="Search by family name or head...">
      </div>
      <div class="filter-controls">

        <!-- Sitio filter -->
        <div class="flt-wrap" id="fam-wrap-sitio">
          <button class="flt-btn" id="fam-btn-sitio" onclick="toggleFamFlt('sitio')">
            <i class="fas fa-map-pin"></i>
            <span id="fam-lbl-sitio">Sitio</span>
            <i class="fas fa-chevron-down flt-caret" id="fam-caret-sitio"></i>
            <span class="flt-x" id="fam-x-sitio" style="display:none" onclick="event.stopPropagation();clearFamFlt('sitio')">×</span>
          </button>
          <div class="flt-dropdown" id="fam-dd-sitio">
            <div class="flt-option selected" data-val="" onclick="setFamFlt('sitio','','Sitio')">All Sitios</div>
            @foreach(['Chrysanthemum','Dahlia','Dama de Noche','Ilang-Ilang 1','Ilang-Ilang 2','Jasmin','Rosal','Sampaguita'] as $s)
            <div class="flt-option" data-val="{{ $s }}" onclick="setFamFlt('sitio','{{ $s }}','{{ $s }}')">{{ $s }}</div>
            @endforeach
            <div class="flt-option" data-val="__none__" onclick="setFamFlt('sitio','__none__','No Household')">Not linked to household</div>
          </div>
        </div>

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
          <tr ondblclick='openFamilyModal(@json($family))' data-sitio="{{ $family->household?->sitio ?? '' }}" data-members="{{ $family->member_count }}" data-linked="{{ $family->household_id ? '1' : '0' }}">
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
                <button onclick='event.stopPropagation();openFamilyModal(@json($family))' class="btn btn-sm btn-view">
                  <i class="fas fa-eye"></i> View
                </button>
                @if(auth()->user()->role == 'admin')
                  <a href="{{ route('families.edit', $family->id) }}" class="btn btn-sm btn-edit" onclick="event.stopPropagation()">
                    <i class="fas fa-edit"></i> Edit
                  </a>
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
const famFlt = { sitio: '' };

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
function setFamFlt(key, val, label) {
  famFlt[key] = val;
  document.getElementById('fam-lbl-' + key).textContent = val ? label : (key === 'sitio' ? 'Sitio' : key);
  document.getElementById('fam-btn-' + key).classList.toggle('active', !!val);
  document.getElementById('fam-caret-' + key).style.display = val ? 'none' : '';
  document.getElementById('fam-x-' + key).style.display = val ? '' : 'none';
  document.querySelectorAll('#fam-dd-' + key + ' .flt-option').forEach(opt => {
    opt.classList.toggle('selected', opt.dataset.val === val);
  });
  document.getElementById('fam-dd-' + key).classList.remove('open');
  applyFilters();
}
function clearFamFlt(key) { setFamFlt(key, '', key === 'sitio' ? 'Sitio' : key); }

document.addEventListener('click', function(e) {
  const wrap = document.getElementById('fam-wrap-sitio');
  if (wrap && !wrap.contains(e.target)) document.getElementById('fam-dd-sitio').classList.remove('open');
});

function applyFilters() {
  const search = document.getElementById('searchInput').value.toLowerCase();
  const sitio  = famFlt.sitio;
  let famCount = 0, memCount = 0, linkedCount = 0;

  document.querySelectorAll('#familiesTable tbody tr').forEach(row => {
    if (!row.dataset.hasOwnProperty('sitio')) { row.style.display = ''; return; }
    const rowSitio   = row.dataset.sitio;
    const rowMembers = parseInt(row.dataset.members) || 0;
    const rowLinked  = row.dataset.linked === '1';
    const textMatch  = row.textContent.toLowerCase().includes(search);
    const sitioMatch = !sitio || (sitio === '__none__' ? rowSitio === '' : rowSitio === sitio);

    if (textMatch && sitioMatch) {
      row.style.display = '';
      famCount++;
      memCount += rowMembers;
      if (rowLinked) linkedCount++;
    } else {
      row.style.display = 'none';
    }
  });

  document.getElementById('stat-families').textContent = famCount;
  document.getElementById('stat-members').textContent  = memCount;
  document.getElementById('stat-linked').textContent   = linkedCount;
}

document.getElementById('searchInput').addEventListener('keyup', applyFilters);
</script>

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
      <button onclick="closeFamilyModal()" class="btn btn-sm" style="background:#f1f5f9;color:var(--muted);border:1px solid var(--border)">
        <i class="fas fa-times"></i> Close
      </button>
    </div>
  </div>
</div>

<script>
function openFamilyModal(f) {
  document.getElementById('familyModal').classList.add('open');
  document.getElementById('fm-name').textContent    = f.family_name || '—';
  document.getElementById('fm-head').textContent    = (f.head_last_name || '—') + ', ' + (f.head_first_name || '') + (f.head_middle_name ? ' ' + f.head_middle_name : '');
  document.getElementById('fm-members').textContent = (f.member_count || 0) + ' member(s)';
  document.getElementById('fm-hh').textContent      = f.household ? 'HH #' + f.household.household_number + ' — ' + f.household.sitio : 'Not linked';
  if (f.notes) {
    document.getElementById('fm-notes').textContent = f.notes;
    document.getElementById('fm-notes-wrap').style.display = '';
  } else {
    document.getElementById('fm-notes-wrap').style.display = 'none';
  }

  // Members table
  const body = document.getElementById('fm-members-body');
  const members = f.members || [];
  const headName = (f.head_last_name || '—') + ', ' + (f.head_first_name || '') + (f.head_middle_name ? ' ' + f.head_middle_name : '');
  const hr = f.head_resident || {};
  const headRow = `<tr>
    <td style="color:var(--muted);font-size:11px">1</td>
    <td style="font-weight:600">${headName}</td>
    <td>${hr.gender || '—'} / ${hr.age || '—'} yrs</td>
    <td>${hr.civil_status || '—'}</td>
    <td><span class="badge-head"><i class="fas fa-crown" style="margin-right:3px;font-size:9px"></i>Head</span></td>
    <td>${f.head_role ? `<span style="background:#eff6ff;color:#1d4ed8;padding:2px 7px;border-radius:20px;font-size:10px;font-weight:600">${f.head_role}</span>` : '<span style="color:var(--muted);font-size:11px">—</span>'}</td>
  </tr>`;
  const otherMembers = members.filter(m => m.id !== f.head_resident_id);
  let rows = headRow + otherMembers.map((m, i) => {
    const name = (m.last_name || '') + ', ' + (m.first_name || '') + (m.middle_name ? ' ' + m.middle_name : '');
    const rel  = m.family_role ? `<span style="background:#eff6ff;color:#1d4ed8;padding:2px 7px;border-radius:20px;font-size:10px;font-weight:600">${m.family_role}</span>` : '<span style="color:var(--muted);font-size:11px">—</span>';
    return `<tr>
      <td style="color:var(--muted);font-size:11px">${i+2}</td>
      <td style="font-weight:600">${name}</td>
      <td>${m.gender || '—'} / ${m.age || '—'} yrs</td>
      <td>${m.civil_status || '—'}</td>
      <td><span style="color:var(--muted);font-size:11px">Member</span></td>
      <td>${rel}</td>
    </tr>`;
  }).join('');
  body.innerHTML = `<table class="mem-table">
    <thead><tr><th>#</th><th>Full Name</th><th>Sex / Age</th><th>Civil Status</th><th>Role</th><th>Relationship</th></tr></thead>
    <tbody>${rows}</tbody>
  </table>`;

  @if(auth()->user()->role == 'admin')
  document.getElementById('fm-edit-link').innerHTML = `<a href="/families/${f.id}/edit" style="color:var(--primary);font-weight:600;text-decoration:none"><i class="fas fa-edit" style="margin-right:4px"></i>Edit this family</a>`;
  @endif
}
function closeFamilyModal() {
  document.getElementById('familyModal').classList.remove('open');
}
document.getElementById('familyModal').addEventListener('click', function(e) {
  if (e.target === this) closeFamilyModal();
});
</script>

@endsection
