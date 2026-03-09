@extends('layouts.app')

@section('page-title', 'Residents')

@section('content')

<style>
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
.modal-backdrop { display:none; position:fixed; inset:0; background:rgba(0,0,0,.35); z-index:200; align-items:center; justify-content:center; }
.modal-backdrop.open { display:flex; }
.modal { background:#fff; border-radius:16px; width:600px; max-width:95vw; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.2); }
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
.modal-footer { padding:16px 24px; border-top:1px solid var(--border); display:flex; justify-content:flex-end; gap:8px; }
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
    <div class="res-stat"><div class="slabel">Total Residents</div><div class="svalue">{{ $residents->where('is_deceased', false)->count() }}</div></div>
    <div class="res-stat"><div class="slabel">Senior Citizens</div><div class="svalue">{{ $residents->where('is_senior', true)->where('is_deceased', false)->count() }}</div></div>
    <div class="res-stat"><div class="slabel">Persons w/ Disability</div><div class="svalue">{{ $residents->where('is_pwd', true)->where('is_deceased', false)->count() }}</div></div>
    <div class="res-stat"><div class="slabel">Registered Voters</div><div class="svalue">{{ $residents->where('is_voter', true)->where('is_deceased', false)->count() }}</div></div>
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
            <th>#</th>
            <th>Full Name</th>
            <th>Sex / Age</th>
            <th>Civil Status</th>
            <th>Address</th>
            <th>Classifications</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>
          @forelse($residents as $index => $resident)
          <tr>

            <td style="color:var(--muted);font-size:12px">{{ $index + 1 }}</td>

            <td>
              <div style="font-weight:700">{{ $resident->last_name }}, {{ $resident->first_name }} {{ $resident->middle_name }}
                @if($resident->is_deceased)
                  <span style="background:#fee2e2;color:#be123c;font-size:10px;padding:2px 7px;border-radius:20px;font-weight:600;margin-left:4px">Deceased</span>
                @endif
              </div>
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
              @if(!$resident->is_senior && !$resident->is_pwd && !$resident->is_voter)
              <span style="color:var(--muted);font-size:12px">—</span>
              @endif
            </td>

            <td>
              <div class="action-btns">

                <button onclick='openResidentModal(@json($resident))' class="btn btn-sm btn-view">
                  <i class="fas fa-eye"></i> View
                </button>

                @if(auth()->user()->role == 'admin')

                <a href="{{ route('residents.edit', $resident->id) }}" class="btn btn-sm btn-edit">
                  <i class="fas fa-edit"></i> Edit
                </a>

                <form method="POST" action="{{ route('residents.destroy', $resident->id) }}" style="display:inline">
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
            <td colspan="7">
              <div class="empty-state">
                <div style="font-size:40px;opacity:.3;margin-bottom:12px">
                  <i class="fas fa-user-slash"></i>
                </div>
                <div style="font-weight:600">No residents found</div>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Resident View Modal -->
<div id="residentModal" class="modal-backdrop">
  <div class="modal">
    <div class="modal-header">
      <h2><i class="fas fa-user" style="margin-right:8px"></i>Resident Profile</h2>
      <button class="modal-close" onclick="closeResidentModal()">×</button>
    </div>
    <div class="modal-body">

      <div class="modal-section">
        <div class="modal-section-title"><i class="fas fa-user"></i> Personal Information</div>
        <div id="rm-badges" style="margin-bottom:12px"></div>
        <div class="mgrid">
          <div class="mi"><span class="ml">Last Name</span><span class="mv" id="rm-last"></span></div>
          <div class="mi"><span class="ml">First Name</span><span class="mv" id="rm-first"></span></div>
          <div class="mi"><span class="ml">Middle Name</span><span class="mv" id="rm-middle"></span></div>
          <div class="mi"><span class="ml">Sex</span><span class="mv" id="rm-gender"></span></div>
          <div class="mi"><span class="ml">Date of Birth</span><span class="mv" id="rm-birth"></span></div>
          <div class="mi"><span class="ml">Age</span><span class="mv" id="rm-age"></span></div>
          <div class="mi"><span class="ml">Civil Status</span><span class="mv" id="rm-civil"></span></div>
          <div class="mi"><span class="ml">Nationality</span><span class="mv" id="rm-nat"></span></div>
          <div class="mi"><span class="ml">Religion</span><span class="mv" id="rm-rel"></span></div>
        </div>
      </div>

      <div class="modal-section">
        <div class="modal-section-title"><i class="fas fa-phone"></i> Contact Information</div>
        <div class="mgrid">
          <div class="mi"><span class="ml">Contact Number</span><span class="mv" id="rm-contact"></span></div>
          <div class="mi span2"><span class="ml">Email</span><span class="mv" id="rm-email"></span></div>
        </div>
      </div>

      <div class="modal-section">
        <div class="modal-section-title"><i class="fas fa-map-marker-alt"></i> Address</div>
        <div class="mgrid">
          <div class="mi"><span class="ml">Province</span><span class="mv" id="rm-prov"></span></div>
          <div class="mi"><span class="ml">City / Municipality</span><span class="mv" id="rm-city"></span></div>
          <div class="mi"><span class="ml">Barangay</span><span class="mv" id="rm-brgy"></span></div>
          <div class="mi span3"><span class="ml">Complete Address</span><span class="mv" id="rm-addr"></span></div>
        </div>
      </div>

      <div class="modal-section">
        <div class="modal-section-title"><i class="fas fa-briefcase"></i> Socio-Economic</div>
        <div class="mgrid">
          <div class="mi"><span class="ml">Occupation</span><span class="mv" id="rm-occ"></span></div>
          <div class="mi"><span class="ml">Employer</span><span class="mv" id="rm-emp"></span></div>
          <div class="mi"><span class="ml">Monthly Income</span><span class="mv" id="rm-inc"></span></div>
          <div class="mi span3"><span class="ml">Education Level</span><span class="mv" id="rm-edu"></span></div>
        </div>
      </div>

    </div>
    <div class="modal-footer">
      <button onclick="closeResidentModal()" class="btn btn-sm" style="background:#f1f5f9;color:var(--muted);border:1px solid var(--border)">
        <i class="fas fa-times"></i> Close
      </button>
    </div>
  </div>
</div>

<script>
function openResidentModal(r) {
  document.getElementById('residentModal').classList.add('open');
  document.getElementById('rm-last').textContent    = r.last_name   || '—';
  document.getElementById('rm-first').textContent   = r.first_name  || '—';
  document.getElementById('rm-middle').textContent  = r.middle_name || '—';
  document.getElementById('rm-gender').textContent  = r.gender      || '—';
  document.getElementById('rm-birth').textContent   = r.birthdate   || '—';
  document.getElementById('rm-age').textContent     = r.age ? r.age + ' yrs' : '—';
  document.getElementById('rm-civil').textContent   = r.civil_status  || '—';
  document.getElementById('rm-nat').textContent     = r.nationality   || '—';
  document.getElementById('rm-rel').textContent     = r.religion      || '—';
  document.getElementById('rm-contact').textContent = r.contact_number || '—';
  document.getElementById('rm-email').textContent   = r.email         || '—';
  document.getElementById('rm-prov').textContent    = r.province  || '—';
  document.getElementById('rm-city').textContent    = r.city      || '—';
  document.getElementById('rm-brgy').textContent    = r.barangay  || '—';
  document.getElementById('rm-addr').textContent    = r.address   || '—';
  document.getElementById('rm-occ').textContent     = r.occupation      || '—';
  document.getElementById('rm-emp').textContent     = r.employer        || '—';
  document.getElementById('rm-inc').textContent     = r.monthly_income ? '₱' + parseFloat(r.monthly_income).toLocaleString() : '—';
  document.getElementById('rm-edu').textContent     = r.education_level || '—';
  let badges = '';
  if (r.is_deceased) badges += '<span class="badge" style="background:#fee2e2;color:#be123c">Deceased</span> ';
  if (r.is_senior)   badges += '<span class="badge badge-senior">Senior Citizen</span> ';
  if (r.is_pwd)      badges += '<span class="badge badge-pwd">PWD</span> ';
  if (r.is_voter)    badges += '<span class="badge" style="background:#f3e8ff;color:#6b21a8">Registered Voter</span> ';
  document.getElementById('rm-badges').innerHTML = badges;
}
function closeResidentModal() {
  document.getElementById('residentModal').classList.remove('open');
}
document.getElementById('residentModal').addEventListener('click', function(e) {
  if (e.target === this) closeResidentModal();
});
</script>

@endsection