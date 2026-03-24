@extends('layouts.app')

@section('page-title', 'Add Family')

@section('content')
<style>
.bidb-wrap { background:var(--bg); min-height:100vh; padding:18px 22px; }
.page-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:10px; }
.page-hdr h1 { font-size:19px; font-weight:700; color:var(--primary); margin:0; }
.breadcrumb { font-size:12px; color:var(--muted); margin-top:2px; }
.breadcrumb a { color:var(--primary); text-decoration:none; }
.breadcrumb span { color:var(--primary); font-weight:500; }
.card { background:var(--card); border-radius:12px; border:1px solid var(--border); box-shadow:0 1px 4px rgba(0,0,0,.05); margin-bottom:14px; overflow:hidden; }
.card-header { padding:11px 16px; border-bottom:1px solid var(--border); }
.card-title { font-weight:700; color:var(--primary); font-size:13px; display:flex; align-items:center; gap:7px; }
.card-body { padding:16px; }
.form-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; }
.form-group { display:flex; flex-direction:column; gap:4px; }
.form-group.full { grid-column:span 3; }
.form-group.half { grid-column:span 2; }
.form-group label { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--muted); }
.form-group input, .form-group select, .form-group textarea { padding:7px 11px; border:1.5px solid var(--border); border-radius:7px; font-size:13px; font-family:inherit; color:var(--text); outline:none; transition:border .15s; }
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color:var(--primary); }
.req { color:#e11d48; }
.btn { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:7px; border:none; cursor:pointer; font-family:inherit; font-size:13px; font-weight:600; transition:all .15s; text-decoration:none; }
.btn-primary { background:var(--primary); color:#fff; }
.btn-primary:hover { background:var(--primary-light); }
.btn-outline { background:#fff; color:var(--primary); border:1.5px solid var(--primary); }
.btn-outline:hover { background:#f0f4f8; }
.form-actions { display:flex; gap:10px; justify-content:flex-end; margin-top:8px; }
.alert-error { background:#fff1f2; border:1px solid #fecdd3; color:#be123c; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px; }
/* Resident search widget */
.res-search-wrap { position:relative; }
.res-dropdown { position:absolute; top:100%; left:0; right:0; background:#fff; border:1.5px solid var(--primary); border-top:none; border-radius:0 0 8px 8px; max-height:220px; overflow-y:auto; z-index:999; display:none; box-shadow:0 4px 12px rgba(0,0,0,.1); }
.res-dropdown.open { display:block; }
.res-option { padding:9px 14px; cursor:pointer; font-size:14px; color:var(--text); border-bottom:1px solid var(--border); }
.res-option:last-child { border-bottom:none; }
.res-option:hover { background:#eff6ff; }
.res-selected { display:none; align-items:center; gap:8px; margin-top:8px; padding:8px 12px; background:#f0f9ff; border:1.5px solid #bae6fd; border-radius:8px; font-size:13px; color:#0369a1; }
.res-selected.show { display:flex; }
.res-clear { background:none; border:none; cursor:pointer; color:#64748b; font-size:18px; line-height:1; margin-left:auto; padding:0; }
/* Members list */
.member-list { display:flex; flex-direction:column; gap:6px; margin-top:10px; }
.member-item { display:flex; align-items:center; gap:10px; padding:7px 12px; background:#f8fafc; border:1px solid var(--border); border-radius:8px; font-size:13px; }
.member-item .m-name { flex:1; font-weight:600; color:var(--text); }
.member-item .m-role { padding:4px 8px; border:1.5px solid var(--border); border-radius:6px; font-size:12px; font-family:inherit; color:var(--text); outline:none; background:#fff; cursor:pointer; min-width:110px; }
.member-item .m-role:focus { border-color:var(--primary); }
.member-item .m-remove { background:none; border:none; cursor:pointer; color:#94a3b8; font-size:16px; padding:0; line-height:1; transition:color .15s; }
.member-item .m-remove:hover { color:#be123c; }
.member-empty { font-size:13px; color:var(--muted); font-style:italic; padding:8px 0; }
</style>

<div class="bidb-wrap">
  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-plus" style="margin-right:8px"></i>Add Family</h1>
      <div class="breadcrumb">Home › <a href="{{ route('families.index') }}">Families</a> › <span>Add Family</span></div>
    </div>
  </div>

  @if($errors->any())
    <div class="alert-error">
      <i class="fas fa-exclamation-circle"></i>
      <ul style="margin:4px 0 0 16px;padding:0">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('families.store') }}">
    @csrf

    <!-- Family Info -->
    <div class="card">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-people-roof"></i> Family Information</div>
      </div>
      <div class="card-body">
        <div class="form-grid">

          <div class="form-group full">
            <label>Family Name <span class="req">*</span></label>
            <input type="text" name="family_name" value="{{ old('family_name') }}" placeholder="e.g. Dela Cruz Family" required>
          </div>

          <div class="form-group full">
            <label>Family Head <span class="req">*</span></label>
            <div class="res-search-wrap">
              <input type="text" id="headSearch" placeholder="Type name to search existing residents..." autocomplete="off">
              <div class="res-dropdown" id="headDropdown"></div>
            </div>
            <input type="hidden" name="head_resident_id" id="headResidentId" value="{{ old('head_resident_id') }}">
            <div class="res-selected" id="headSelected">
              <i class="fas fa-user-check"></i>
              <span id="headSelectedName"></span>
              <button type="button" class="res-clear" onclick="clearHead()" title="Clear">×</button>
            </div>
          </div>

          <div class="form-group full">
            <label>Head's Relationship to Family</label>
            <select name="head_role">
              <option value="">— Select relationship —</option>
              @foreach(['Father','Mother','Grandfather','Grandmother','Uncle','Aunt','Guardian','Other'] as $r)
                <option value="{{ $r }}" {{ old('head_role') == $r ? 'selected' : '' }}>{{ $r }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group half">
            <label>Linked Household</label>
            <select name="household_id">
              <option value="">— Not linked —</option>
              @foreach($households as $household)
                <option value="{{ $household->id }}" {{ old('household_id') == $household->id ? 'selected' : '' }}>
                  HH #{{ $household->household_number }} — {{ $household->head_last_name }}, {{ $household->head_first_name }} ({{ $household->sitio }})
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-group full">
            <label>Notes</label>
            <textarea name="notes" rows="2" placeholder="Optional notes...">{{ old('notes') }}</textarea>
          </div>

        </div>
      </div>
    </div>

    <!-- Family Members -->
    <div class="card" style="overflow:visible">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-users"></i> Family Members</div>
      </div>
      <div class="card-body">
        <div class="form-group" style="max-width:420px;margin-bottom:10px">
          <label>Add Member</label>
          <div class="res-search-wrap">
            <input type="text" id="memberSearch" placeholder="Search resident to add as member..." autocomplete="off">
            <div class="res-dropdown" id="memberDropdown"></div>
          </div>
        </div>
        <div class="member-list" id="memberList">
          <div class="member-empty" id="memberEmpty">No members added yet.</div>
        </div>
        <div id="memberInputs"></div>
      </div>
    </div>

    <div class="form-actions">
      <a href="{{ route('families.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Cancel</a>
      <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Family</button>
    </div>

  </form>
</div>

<script>
const residents = @json($residents);

// ── Head search ──────────────────────────────────────────────
const headSearch   = document.getElementById('headSearch');
const headDropdown = document.getElementById('headDropdown');
const headHidden   = document.getElementById('headResidentId');
const headSelected = document.getElementById('headSelected');
const headName     = document.getElementById('headSelectedName');

const oldHeadId = "{{ old('head_resident_id') }}";
if (oldHeadId) {
  const r = residents.find(x => x.id == oldHeadId);
  if (r) selectHead(r);
}

headSearch.addEventListener('input', function() {
  buildDropdown(this.value, headDropdown, selectHead);
});
headSearch.addEventListener('blur', () => setTimeout(() => headDropdown.classList.remove('open'), 150));

function selectHead(r) {
  headHidden.value = r.id;
  headSearch.value = '';
  headDropdown.classList.remove('open');
  headName.textContent = r.last_name + ', ' + r.first_name + (r.middle_name ? ' ' + r.middle_name : '');
  headSelected.classList.add('show');
}
function clearHead() {
  headHidden.value = '';
  headSelected.classList.remove('show');
  headSearch.value = '';
  headSearch.focus();
}

// ── Member search ─────────────────────────────────────────────
const memberSearch   = document.getElementById('memberSearch');
const memberDropdown = document.getElementById('memberDropdown');
const memberList     = document.getElementById('memberList');
const memberEmpty    = document.getElementById('memberEmpty');
const memberInputs   = document.getElementById('memberInputs');
const addedMembers = {}; // id -> { name, role }
const roleOptions  = ['Spouse/Partner','Father','Mother','Son','Daughter','Brother','Sister','Grandfather','Grandmother','Uncle','Aunt','Nephew','Niece','Cousin','Guardian','Other'];

memberSearch.addEventListener('input', function() {
  buildDropdown(this.value, memberDropdown, addMember);
});
memberSearch.addEventListener('blur', () => setTimeout(() => memberDropdown.classList.remove('open'), 150));

function addMember(r) {
  memberSearch.value = '';
  memberDropdown.classList.remove('open');
  if (addedMembers[r.id]) return;
  if (r.id == parseInt(document.getElementById('headResidentId').value)) return;
  const name = r.last_name + ', ' + r.first_name + (r.middle_name ? ' ' + r.middle_name : '');
  addedMembers[r.id] = { name, role: '' };
  renderMembers();
}

function removeMember(id) {
  delete addedMembers[id];
  renderMembers();
}

function isCustomRole(role) {
  return role && !roleOptions.includes(role);
}

function updateRole(id, val) {
  if (!addedMembers[id]) return;
  const customInp = document.getElementById(`mcustom_${id}`);
  const hiddenInp = document.getElementById(`mdinp_${id}`);
  if (val === 'Other') {
    addedMembers[id].role = customInp ? customInp.value : '';
    if (customInp) customInp.style.display = '';
  } else {
    addedMembers[id].role = val;
    if (customInp) { customInp.style.display = 'none'; customInp.value = ''; }
    if (hiddenInp) hiddenInp.value = val;
  }
}

function updateCustomRole(id, val) {
  if (!addedMembers[id]) return;
  addedMembers[id].role = val;
  const inp = document.getElementById(`mdinp_${id}`);
  if (inp) inp.value = val;
}

function renderMembers() {
  const ids = Object.keys(addedMembers);
  memberEmpty.style.display = ids.length ? 'none' : '';
  memberList.querySelectorAll('.member-item').forEach(el => el.remove());
  memberInputs.innerHTML = '';
  ids.forEach(id => {
    const { name, role } = addedMembers[id];
    const custom = isCustomRole(role);
    const selectedOpt = custom ? 'Other' : role;
    const customVal = custom ? role : '';
    const opts = roleOptions.map(o => `<option value="${o}" ${selectedOpt===o?'selected':''}>${o}</option>`).join('');
    const item = document.createElement('div');
    item.className = 'member-item';
    item.innerHTML = `<i class="fas fa-user" style="color:var(--muted)"></i>
      <span class="m-name">${name}</span>
      <select class="m-role" id="msel_${id}" onchange="updateRole(${id}, this.value)">
        <option value="">— Relationship —</option>${opts}
      </select>
      <input type="text" id="mcustom_${id}" placeholder="e.g. Housemaid"
        style="display:${custom?'':'none'};padding:4px 8px;border:1.5px solid var(--border);border-radius:6px;font-size:12px;font-family:inherit;color:var(--text);outline:none;min-width:110px;"
        value="${customVal}" oninput="updateCustomRole(${id}, this.value)">
      <button type="button" class="m-remove" onclick="removeMember(${id})" title="Remove">×</button>`;
    memberList.appendChild(item);
    const inp = document.createElement('input');
    inp.type = 'hidden'; inp.name = `member_data[${id}]`;
    inp.value = custom ? customVal : role;
    inp.id = `mdinp_${id}`;
    memberInputs.appendChild(inp);
  });
}

// ── Shared dropdown builder ───────────────────────────────────
function buildDropdown(query, dropdownEl, onSelect) {
  dropdownEl.innerHTML = '';
  const q = query.toLowerCase().trim();
  if (!q) { dropdownEl.classList.remove('open'); return; }
  const matches = residents.filter(r =>
    (r.last_name + ' ' + r.first_name + ' ' + (r.middle_name || '')).toLowerCase().includes(q)
  ).slice(0, 10);
  if (!matches.length) {
    dropdownEl.innerHTML = '<div class="res-option" style="color:#94a3b8;cursor:default">No residents found</div>';
  } else {
    matches.forEach(r => {
      const div = document.createElement('div');
      div.className = 'res-option';
      div.textContent = r.last_name + ', ' + r.first_name + (r.middle_name ? ' ' + r.middle_name : '');
      div.addEventListener('mousedown', (e) => { e.preventDefault(); onSelect(r); });
      dropdownEl.appendChild(div);
    });
  }
  dropdownEl.classList.add('open');
}
</script>
@endsection
