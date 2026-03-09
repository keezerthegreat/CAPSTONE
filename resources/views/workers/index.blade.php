@extends('layouts.app')

@section('page-title', 'Worker Information')

@section('content')
<style>
:root { --primary:#1a3a6b; --primary-light:#2554a0; --bg:#f0f4f8; --card:#fff; --text:#1e293b; --muted:#64748b; --border:#e2e8f0; }
.bidb-wrap { background:var(--bg); min-height:100vh; padding:28px; }
.page-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.page-hdr h1 { font-size:22px; font-weight:700; color:var(--primary); margin:0; }
.breadcrumb { font-size:13px; color:var(--muted); margin-top:2px; }
.breadcrumb span { color:var(--primary); font-weight:500; }
.card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); overflow:hidden; margin-bottom:20px; }
.card-header { padding:16px 20px; border-bottom:1px solid var(--border); background:#f8fafc; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
.card-title { font-weight:700; color:var(--primary); font-size:14px; display:flex; align-items:center; gap:8px; }
.alert-success { background:#dcfce7; border:1px solid #bbf7d0; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px; display:flex; align-items:center; gap:8px; }
.btn { display:inline-flex; align-items:center; gap:6px; padding:9px 16px; border-radius:8px; border:none; cursor:pointer; font-family:inherit; font-size:13px; font-weight:600; transition:all .15s; text-decoration:none; }
.btn-primary { background:var(--primary); color:#fff; }
.btn-primary:hover { background:var(--primary-light); }
.btn-view   { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; padding:5px 10px; font-size:12px; }
.btn-edit   { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; padding:5px 10px; font-size:12px; }
.btn-delete { background:#fff1f2; color:#be123c; border:1px solid #fecdd3; padding:5px 10px; font-size:12px; }
.btn-view:hover   { background:#dcfce7; }
.btn-edit:hover   { background:#dbeafe; }
.btn-delete:hover { background:#ffe4e6; }
.action-btns { display:flex; gap:5px; }
.search-input { padding:8px 12px; border:1.5px solid var(--border); border-radius:8px; font-size:13px; font-family:inherit; outline:none; width:240px; }
.search-input:focus { border-color:var(--primary); box-shadow:0 0 0 3px rgba(26,58,107,.08); }
.table-wrap { overflow-x:auto; }
table { width:100%; border-collapse:collapse; font-size:13px; }
thead tr { background:#f8fafc; border-bottom:2px solid var(--border); }
th { padding:12px 16px; text-align:left; font-weight:700; color:var(--muted); font-size:11px; text-transform:uppercase; letter-spacing:.06em; white-space:nowrap; }
td { padding:12px 16px; border-bottom:1px solid var(--border); color:var(--text); vertical-align:middle; }
tbody tr:hover { background:#f8fafc; }
tbody tr:last-child td { border-bottom:none; }
.badge { display:inline-flex; align-items:center; padding:2px 10px; border-radius:20px; font-size:11px; font-weight:600; }
.badge-regular   { background:#dcfce7; color:#166534; }
.badge-joborder  { background:#fef9c3; color:#854d0e; }
.badge-volunteer { background:#dbeafe; color:#1e40af; }
.badge-na        { background:#f1f5f9; color:#64748b; }
.avatar { width:38px; height:38px; border-radius:50%; object-fit:cover; border:2px solid var(--border); }

/* Modal */
.modal-backdrop { display:none; position:fixed; inset:0; background:rgba(0,0,0,.35); z-index:200; align-items:center; justify-content:center; }
.modal-backdrop.open { display:flex; }
.modal { background:#fff; border-radius:16px; width:520px; max-width:95vw; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.2); }
.modal-header { padding:20px 24px 16px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.modal-header h2 { font-size:16px; font-weight:700; color:var(--primary); margin:0; }
.modal-close { background:none; border:none; font-size:20px; color:var(--muted); cursor:pointer; line-height:1; padding:0; }
.modal-body { padding:24px; }
.modal-photo { text-align:center; margin-bottom:20px; }
.modal-photo img { width:80px; height:80px; border-radius:50%; object-fit:cover; border:3px solid var(--border); }
.info-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.info-item { display:flex; flex-direction:column; gap:3px; }
.info-item .ilabel { font-size:10px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.06em; }
.info-item .ivalue { font-size:13px; color:var(--text); font-weight:500; background:#f8fafc; border:1px solid var(--border); border-radius:7px; padding:7px 10px; }
.modal-footer { padding:16px 24px; border-top:1px solid var(--border); display:flex; justify-content:flex-end; }
</style>

<div class="bidb-wrap">

  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-user-tie" style="margin-right:8px"></i>Worker Information</h1>
      <div class="breadcrumb">Home › <span>Worker Information</span></div>
    </div>
    @if(auth()->user()->role == 'admin')
    <a href="{{ route('workers.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i> Add Worker
    </a>
    @endif
  </div>

  @if(session('success'))
    <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-list"></i> Workers List</div>
      <input type="text" id="workerSearch" class="search-input" placeholder="Search worker...">
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Photo</th>
            <th>Name</th>
            <th>Position</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($workers as $worker)
          <tr>
            <td>
              <img src="{{ $worker->photo ? asset('storage/'.$worker->photo) : 'https://ui-avatars.com/api/?name='.urlencode($worker->first_name.' '.$worker->last_name).'&background=dbeafe&color=1d4ed8&size=80' }}"
                   class="avatar">
            </td>
            <td>
              <div style="font-weight:600">{{ $worker->first_name }} {{ $worker->middle_name }} {{ $worker->last_name }}</div>
            </td>
            <td>{{ $worker->position }}</td>
            <td>
              @if($worker->employment_status == 'Regular')
                <span class="badge badge-regular">Regular</span>
              @elseif($worker->employment_status == 'Job Order')
                <span class="badge badge-joborder">Job Order</span>
              @elseif($worker->employment_status == 'Volunteer')
                <span class="badge badge-volunteer">Volunteer</span>
              @else
                <span class="badge badge-na">N/A</span>
              @endif
            </td>
            <td>
              <div class="action-btns">
                <button onclick='openModal(@json($worker))' class="btn btn-view">
                  <i class="fas fa-eye"></i> View
                </button>
                @if(auth()->user()->role == 'admin')
                <a href="{{ route('workers.edit', $worker->id) }}" class="btn btn-edit">
                  <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('workers.destroy', $worker->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this worker?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-delete"><i class="fas fa-trash"></i></button>
                </form>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" style="text-align:center;padding:32px;color:var(--muted)">
              <i class="fas fa-user-tie" style="font-size:32px;opacity:.3;display:block;margin-bottom:8px"></i>
              No workers found.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>

<!-- View Worker Modal -->
<div id="viewModal" class="modal-backdrop">
  <div class="modal">
    <div class="modal-header">
      <h2><i class="fas fa-user-tie" style="margin-right:8px"></i>Worker Details</h2>
      <button class="modal-close" onclick="closeModal()">×</button>
    </div>
    <div class="modal-body">
      <div class="modal-photo">
        <img id="v_photo" src="" alt="Worker Photo">
      </div>
      <div class="info-grid">
        <div class="info-item" style="grid-column:span 2">
          <span class="ilabel">Full Name</span>
          <span class="ivalue" id="v_name"></span>
        </div>
        <div class="info-item">
          <span class="ilabel">Birthdate</span>
          <span class="ivalue" id="v_birthdate"></span>
        </div>
        <div class="info-item">
          <span class="ilabel">Gender</span>
          <span class="ivalue" id="v_gender"></span>
        </div>
        <div class="info-item">
          <span class="ilabel">Civil Status</span>
          <span class="ivalue" id="v_civil_status"></span>
        </div>
        <div class="info-item">
          <span class="ilabel">Contact</span>
          <span class="ivalue" id="v_contact"></span>
        </div>
        <div class="info-item" style="grid-column:span 2">
          <span class="ilabel">Email</span>
          <span class="ivalue" id="v_email"></span>
        </div>
        <div class="info-item" style="grid-column:span 2">
          <span class="ilabel">Address</span>
          <span class="ivalue" id="v_address"></span>
        </div>
        <div class="info-item">
          <span class="ilabel">Position</span>
          <span class="ivalue" id="v_position"></span>
        </div>
        <div class="info-item">
          <span class="ilabel">Date Hired</span>
          <span class="ivalue" id="v_date_hired"></span>
        </div>
        <div class="info-item" style="grid-column:span 2">
          <span class="ilabel">Employment Status</span>
          <span class="ivalue" id="v_status"></span>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button onclick="closeModal()" class="btn" style="background:#f1f5f9;color:var(--muted);border:1px solid var(--border)">
        <i class="fas fa-times"></i> Close
      </button>
    </div>
  </div>
</div>

<script>
document.getElementById("workerSearch").addEventListener("keyup", function() {
    let value = this.value.toLowerCase();
    document.querySelectorAll("tbody tr").forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
    });
});

function openModal(worker) {
    document.getElementById('viewModal').classList.add('open');
    document.getElementById('v_name').textContent =
        (worker.first_name ?? '') + ' ' + (worker.middle_name ?? '') + ' ' + (worker.last_name ?? '');
    document.getElementById('v_birthdate').textContent = worker.birthdate ?? '—';
    document.getElementById('v_gender').textContent = worker.gender ?? '—';
    document.getElementById('v_civil_status').textContent = worker.civil_status ?? '—';
    document.getElementById('v_contact').textContent = worker.contact_number ?? '—';
    document.getElementById('v_email').textContent = worker.email ?? '—';
    document.getElementById('v_address').textContent = worker.address ?? '—';
    document.getElementById('v_position').textContent = worker.position ?? '—';
    document.getElementById('v_date_hired').textContent = worker.date_hired ?? '—';
    document.getElementById('v_status').textContent = worker.employment_status ?? '—';
    const photo = worker.photo
        ? '/storage/' + worker.photo
        : 'https://ui-avatars.com/api/?name=' + encodeURIComponent((worker.first_name ?? '') + ' ' + (worker.last_name ?? '')) + '&background=dbeafe&color=1d4ed8&size=160';
    document.getElementById('v_photo').src = photo;
}

function closeModal() {
    document.getElementById('viewModal').classList.remove('open');
}

document.getElementById('viewModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>

@endsection
