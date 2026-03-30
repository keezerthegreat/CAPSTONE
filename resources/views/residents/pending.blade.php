@extends('layouts.app')

@section('page-title', 'Pending Verification')

@section('content')

<style>
.bidb-wrap { background: var(--bg); min-height: 100vh; padding: 28px; }
.page-hdr { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
.page-hdr h1 { font-size: 22px; font-weight: 700; color: var(--primary); margin: 0; }
.breadcrumb { font-size: 13px; color: var(--muted); margin-top: 2px; }
.breadcrumb span { color: var(--primary); font-weight: 500; }
.card { background: var(--card); border-radius: 14px; border: 1px solid var(--border); box-shadow: 0 1px 6px rgba(0,0,0,.06); margin-bottom: 24px; overflow: hidden; }
.card-header { padding: 14px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
.card-title { font-weight: 700; color: var(--text); font-size: 15px; display: flex; align-items: center; gap: 8px; }
.count-badge { font-size: 11px; font-weight: 700; padding: 2px 9px; border-radius: 20px; }
.badge-amber { background: #f59e0b; color: #fff; }
.badge-purple { background: #7c3aed; color: #fff; }
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: 13px; }
thead tr { background: #f8fafc; border-bottom: 2px solid var(--border); }
th { padding: 12px 16px; text-align: left; font-weight: 700; color: var(--muted); font-size: 11px; text-transform: uppercase; letter-spacing: .06em; white-space: nowrap; }
td { padding: 12px 16px; border-bottom: 1px solid var(--border); color: var(--text); vertical-align: middle; }
tbody tr:last-child td { border-bottom: none; }
.btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer; font-family: inherit; font-size: 13px; font-weight: 600; transition: all .15s; text-decoration: none; }
.btn-sm { padding: 5px 10px; font-size: 12px; }
.btn-view   { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.btn-approve { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
.btn-approve:hover { background: #bbf7d0; }
.btn-reject  { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
.btn-reject:hover  { background: #ffe4e6; }
.action-btns { display: flex; gap: 5px; justify-content: flex-end; }
.empty-state { text-align: center; padding: 48px 20px; color: var(--muted); }
.alert-success { background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 8px; }
.alert-error { background: #fff1f2; border: 1px solid #fecdd3; color: #be123c; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 8px; }
</style>

<div class="bidb-wrap">

  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-clock" style="margin-right:8px;color:#d97706"></i>Pending Verification</h1>
      <div class="breadcrumb">
        <a href="{{ route('residents.index') }}" style="color:var(--muted);text-decoration:none">Residents</a>
        &rsaquo; <span>Pending Verification</span>
      </div>
    </div>
    <a href="{{ route('residents.index') }}" class="btn" style="background:var(--card);border:1px solid var(--border);color:var(--text)">
      <i class="fas fa-arrow-left"></i> Back to Residents
    </a>
  </div>

  @if(session('success'))
    <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
  @endif

  {{-- New Resident Submissions --}}
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <i class="fas fa-user-plus" style="color:#d97706"></i>
        New Resident Submissions
        <span class="count-badge badge-amber">{{ $pendingResidents->count() }}</span>
      </div>
      <span style="font-size:12px;color:var(--muted)">Submitted by employees — awaiting admin approval</span>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Resident</th>
            <th>Sex / Age</th>
            <th>Civil Status</th>
            <th>Address</th>
            <th>Submitted</th>
            <th style="text-align:center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pendingResidents as $pr)
          <tr>
            <td>
              <div style="font-weight:700">{{ $pr->last_name }}, {{ $pr->first_name }} {{ $pr->middle_name }}</div>
              <div style="font-size:11px;color:var(--muted)">ID #{{ $pr->id }}</div>
            </td>
            <td>{{ $pr->gender }} / {{ $pr->age }} yrs</td>
            <td>{{ $pr->civil_status ?? '—' }}</td>
            <td>
              <div>{{ $pr->address ?? '—' }}</div>
              <div style="font-size:11px;color:var(--muted)">{{ $pr->barangay }}, {{ $pr->city }}</div>
            </td>
            <td style="font-size:12px;color:var(--muted)">{{ $pr->created_at->format('M d, Y g:i A') }}</td>
            <td>
              <div class="action-btns">
                <button type="button" onclick='openResidentModal(@json($pr), "new")' class="btn btn-sm btn-view">
                  <i class="fas fa-eye"></i> View
                </button>
                <form method="POST" action="{{ route('residents.approve', $pr->id) }}" style="display:inline">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-approve"><i class="fas fa-check"></i> Approve</button>
                </form>
                <form method="POST" action="{{ route('residents.reject', $pr->id) }}" style="display:inline"
                  onsubmit="return confirm('Reject and remove the pending record for {{ addslashes($pr->first_name) }} {{ addslashes($pr->last_name) }}? This cannot be undone.')">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-reject"><i class="fas fa-times"></i> Reject</button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="empty-state"><i class="fas fa-check-circle" style="font-size:24px;color:#bbf7d0;display:block;margin-bottom:8px"></i>No pending new residents.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Pending Edit Requests --}}
  <div class="card">
    <div class="card-header">
      <div class="card-title">
        <i class="fas fa-pencil-alt" style="color:#7c3aed"></i>
        Pending Edit Requests
        <span class="count-badge badge-purple">{{ $pendingEdits->count() }}</span>
      </div>
      <span style="font-size:12px;color:var(--muted)">Proposed changes by employees — awaiting admin approval</span>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Resident</th>
            <th>Proposed Changes</th>
            <th>Sex / Age</th>
            <th>Address</th>
            <th>Submitted</th>
            <th style="text-align:center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pendingEdits as $pe)
          @php $pr = $pe->resident; $pd = $pe->proposed_data; @endphp
          <tr>
            <td>
              <div style="font-weight:700">{{ $pr->last_name }}, {{ $pr->first_name }} {{ $pr->middle_name }}</div>
              <div style="font-size:11px;color:var(--muted)">ID #{{ $pr->id }}</div>
              <div style="font-size:11px;color:#6d28d9;margin-top:2px">By {{ $pe->submitted_by_name }}</div>
            </td>
            <td>
              @php
                $fields = ['last_name'=>'Last Name','first_name'=>'First Name','middle_name'=>'Middle Name','gender'=>'Sex','birthdate'=>'Birthdate','age'=>'Age','civil_status'=>'Civil Status','address'=>'Address','barangay'=>'Barangay','city'=>'City','province'=>'Province','occupation'=>'Occupation','education_level'=>'Education'];
                $changes = [];
                foreach ($fields as $key => $label) {
                    $old = $pr->$key ?? null;
                    $new = $pd[$key] ?? null;
                    if ((string)$old !== (string)$new) {
                        $changes[] = ['label' => $label, 'old' => $old ?: '—', 'new' => $new ?: '—'];
                    }
                }
              @endphp
              @if(count($changes))
                <div style="font-size:11px">
                  @foreach($changes as $change)
                  <div style="margin-bottom:3px">
                    <span style="font-weight:600;color:var(--muted)">{{ $change['label'] }}:</span>
                    <span style="color:#be123c;text-decoration:line-through">{{ $change['old'] }}</span>
                    <span style="color:#64748b"> → </span>
                    <span style="color:#166534;font-weight:600">{{ $change['new'] }}</span>
                  </div>
                  @endforeach
                </div>
              @else
                <span style="color:var(--muted);font-size:12px">No tracked field changes</span>
              @endif
            </td>
            <td>{{ $pr->gender }} / {{ $pr->age }} yrs</td>
            <td>
              <div>{{ $pr->address ?? '—' }}</div>
              <div style="font-size:11px;color:var(--muted)">{{ $pr->barangay }}, {{ $pr->city }}</div>
            </td>
            <td style="font-size:12px;color:var(--muted)">{{ $pe->created_at->format('M d, Y g:i A') }}</td>
            <td>
              <div class="action-btns">
                <button type="button" onclick='openResidentModal(@json($pr), "edit")' class="btn btn-sm btn-view">
                  <i class="fas fa-eye"></i> View
                </button>
                <form method="POST" action="{{ route('residents.approveEdit', $pe->id) }}" style="display:inline">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-approve"><i class="fas fa-check"></i> Approve</button>
                </form>
                <form method="POST" action="{{ route('residents.rejectEdit', $pe->id) }}" style="display:inline"
                  onsubmit="return confirm('Reject the proposed edit for {{ addslashes($pr->first_name) }} {{ addslashes($pr->last_name) }}? The current record will remain unchanged.')">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-reject"><i class="fas fa-times"></i> Reject</button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="empty-state"><i class="fas fa-check-circle" style="font-size:24px;color:#bbf7d0;display:block;margin-bottom:8px"></i>No pending edit requests.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>

<script>
function openResidentModal(resident, type) {
  // Fallback: open show page
  if (resident && resident.id) {
    window.location.href = '/residents/' + resident.id;
  }
}
</script>

@endsection
