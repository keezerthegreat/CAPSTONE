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
.filter-area { padding: 12px 20px; border-bottom: 1px solid var(--border); display: flex; flex-direction: column; gap: 8px; }
.search-wrap { position: relative; }
.search-wrap input { width: 100%; padding: 8px 14px 8px 34px; border: 1.5px solid var(--border); border-radius: 8px; font-size: 13px; font-family: inherit; outline: none; box-sizing: border-box; }
.search-wrap input:focus { border-color: var(--primary); }
.search-wrap .si { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--muted); font-size: 12px; }
.filter-controls { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }
/* Filter controls row */
.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; font-size: 13px; }
thead tr { background: #f8fafc; border-bottom: 2px solid var(--border); }
th { padding: 12px 16px; text-align: left; font-weight: 700; color: var(--muted); font-size: 11px; text-transform: uppercase; letter-spacing: .06em; white-space: nowrap; }
td { padding: 13px 16px; border-bottom: 1px solid var(--border); color: var(--text); vertical-align: middle; }
tbody tr { cursor:pointer; }
tbody tr:hover { background: #f0f7ff; }
tbody tr:last-child td { border-bottom: none; }
.badge { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 600; margin: 1px; }
.badge-senior  { background: #fef3c7; color: #92400e; }
.badge-pwd     { background: #fee2e2; color: #991b1b; }

/* Pending verification section */
.pending-card { background: #fffbeb; border-radius: 14px; border: 1.5px solid #fcd34d; box-shadow: 0 1px 6px rgba(0,0,0,.06); margin-bottom: 24px; overflow: hidden; }
.pending-header { padding: 14px 20px; border-bottom: 1px solid #fcd34d; background: #fef3c7; display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
.pending-title { font-weight: 700; color: #92400e; font-size: 14px; display: flex; align-items: center; gap: 8px; }
.pending-count { background: #f59e0b; color: #fff; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 20px; }
.pending-note { font-size: 12px; color: #b45309; }
.btn-approve { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
.btn-approve:hover { background: #bbf7d0; }
.btn-reject  { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
.btn-reject:hover  { background: #ffe4e6; }
.pending-table thead tr { background: #fef9c3; }
.pending-table td { background: transparent; }
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
/* ── RBI Print Frame ── */
#rbi-print-frame { display:none; }
@media print {
  body * { visibility:hidden !important; }
  #rbi-print-frame, #rbi-print-frame * { visibility:visible !important; }
  #rbi-print-frame {
    display:block !important;
    position:fixed; top:0; left:0;
    width:100%; padding:15mm 15mm 15mm 15mm;
    box-sizing:border-box;
    font-family:Arial,sans-serif; font-size:10pt; color:#000; background:#fff;
    z-index:99999;
  }
  @page { size:A4; margin:0; }
}
/* RBI form inner styles */
.rp-formid { font-size:9pt; font-weight:bold; margin-bottom:2px; }
.rp-title  { font-size:12pt; font-weight:bold; text-align:center; text-transform:uppercase; margin-bottom:10px; letter-spacing:.04em; }
.rp-top-grid { display:grid; grid-template-columns:1fr 1fr; border:1px solid #000; margin-bottom:8px; }
.rp-top-cell { display:flex; align-items:center; border-bottom:1px solid #000; padding:3px 6px; gap:6px; }
.rp-top-cell:nth-child(odd) { border-right:1px solid #000; }
.rp-top-cell:last-child, .rp-top-cell:nth-last-child(2) { border-bottom:none; }
.rp-top-lbl { font-size:8pt; font-weight:bold; white-space:nowrap; min-width:70px; }
.rp-top-val { flex:1; border-bottom:1px solid #000; font-size:9pt; padding:1px 2px; min-height:14px; }
.rp-section-box { border:1.5px solid #000; padding:10px 12px; margin-bottom:8px; }
.rp-section-title { font-size:9pt; font-weight:bold; text-transform:uppercase; margin-bottom:8px; border-bottom:1px solid #000; padding-bottom:3px; }
.rp-field-row { display:flex; align-items:flex-end; gap:6px; margin-bottom:8px; }
.rp-field-lbl { font-size:8pt; font-weight:bold; white-space:nowrap; min-width:90px; }
.rp-uline { flex:1; border-bottom:1px solid #000; font-size:9.5pt; min-height:16px; padding:1px 3px; }
.rp-uline.sm { flex:0 0 60px; }
.rp-uline.md { flex:0 0 110px; }
.rp-name-cols { display:grid; grid-template-columns:1fr 1fr 1fr 50px; gap:8px; flex:1; }
.rp-named-col { display:flex; flex-direction:column; }
.rp-col-sub { font-size:7.5pt; text-align:center; color:#333; margin-top:1px; }
.rp-dob-cols { display:flex; gap:8px; align-items:flex-end; }
.rp-dob-box { display:flex; flex-direction:column; align-items:center; }
.rp-check-row { display:flex; align-items:center; gap:14px; flex-wrap:wrap; flex:1; }
.rp-check-item { display:flex; align-items:center; gap:4px; font-size:9pt; }
.rp-cb { width:11px; height:11px; border:1px solid #000; display:inline-flex; align-items:center; justify-content:center; font-size:9pt; line-height:1; flex-shrink:0; }
.rp-cb.on::after { content:'✓'; }
.rp-sign-row { display:grid; grid-template-columns:1fr 1fr; gap:32px; margin-top:10px; }
.rp-sign-line { border-top:1px solid #000; margin-top:28px; }
.rp-sign-lbl { font-size:7.5pt; text-align:center; margin-top:3px; color:#333; }
.rp-thumb-area { display:flex; gap:16px; justify-content:center; margin-top:8px; }
.rp-thumb-box { display:flex; flex-direction:column; align-items:center; }
.rp-thumb-rect { width:62px; height:75px; border:1px solid #000; }
.rp-thumb-lbl { font-size:7.5pt; margin-top:3px; text-align:center; }
.rp-attest-row { display:flex; align-items:flex-end; gap:12px; margin-top:14px; }
.rp-hh-box { border:1px solid #000; width:100px; min-height:22px; padding:2px 4px; font-size:9pt; }
.rp-note { font-size:7.5pt; font-style:italic; margin-top:14px; border-top:1px solid #ccc; padding-top:5px; color:#444; }
.rp-income-row { display:flex; align-items:flex-end; gap:4px; }
/* Age range popup */
.age-popup { display:none; position:fixed; top:0; left:0; background:var(--card); border:1.5px solid var(--border); border-radius:10px; padding:14px 16px; z-index:9000; box-shadow:0 8px 24px rgba(0,0,0,.12); min-width:230px; }
.age-popup.open { display:block; }
.age-popup-title { font-size:11px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:.05em; margin-bottom:10px; }
.age-range-row { display:flex; align-items:center; gap:8px; margin-bottom:10px; }
.age-range-row input { width:65px; padding:6px 8px; border:1.5px solid var(--border); border-radius:7px; font-size:13px; font-family:inherit; outline:none; text-align:center; }
.age-range-row input:focus { border-color:var(--primary); }
.age-range-row span { font-size:12px; color:var(--muted); }
.age-popup-actions { display:flex; gap:6px; justify-content:flex-end; }
.btn-age-apply { background:var(--primary); color:#fff; border:none; padding:5px 13px; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer; font-family:inherit; }
.btn-age-clear { background:#f1f5f9; color:var(--muted); border:1px solid var(--border); padding:5px 10px; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer; font-family:inherit; }
/* Pagination */
.pg-bar { display:flex; align-items:center; justify-content:space-between; padding:14px 4px 4px; flex-wrap:wrap; gap:10px; }
.pg-info { font-size:12px; color:var(--muted); }
.pg-controls { display:flex; align-items:center; gap:4px; }
.pg-btn { display:inline-flex; align-items:center; justify-content:center; min-width:34px; height:34px; padding:0 10px; border-radius:7px; border:1.5px solid var(--border); background:var(--card); color:var(--text); font-size:13px; font-weight:600; text-decoration:none; cursor:pointer; transition:all .15s; font-family:inherit; }
.pg-btn:hover:not([disabled]) { border-color:var(--primary); color:var(--primary); }
.pg-btn.active { background:var(--primary); color:#fff; border-color:var(--primary); }
.pg-btn[disabled] { opacity:.35; cursor:default; }
</style>

<div class="bidb-wrap">
  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-users" style="margin-right:8px;font-size:20px"></i>Resident Records</h1>
      <div class="breadcrumb">Home › <span>Residents</span></div>
    </div>

  <div style="display:flex;gap:10px;align-items:center">
    @if(auth()->user()->role == 'admin')
    <button type="button" id="bulkDeleteBtn" onclick="submitBulkDelete()"
      style="display:none;background:#fff1f2;color:#be123c;border:1px solid #fecdd3;
             padding:8px 14px;border-radius:8px;font-size:13px;font-weight:600;
             cursor:pointer;align-items:center;gap:6px">
      <i class="fas fa-trash"></i> Delete Selected (<span id="selectedCount">0</span>)
    </button>
    @endif
    <a href="{{ route('residents.create') }}" class="btn btn-primary">
      <i class="fas fa-user-plus"></i> Add Resident
    </a>
  </div>

  </div>

  @if(session('success'))
    <div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
  @endif

  <div class="res-stats">
    <div class="res-stat"><div class="slabel">Total Residents</div><div class="svalue">{{ $totalResidents }}</div></div>
    <div class="res-stat"><div class="slabel">Senior Citizens</div><div class="svalue">{{ $totalSeniors }}</div></div>
    <div class="res-stat"><div class="slabel">Persons w/ Disability</div><div class="svalue">{{ $totalPwd }}</div></div>
    @php $totalPendingCount = $pendingResidents->count() + $pendingEdits->count(); @endphp
    <div class="res-stat" style="{{ $totalPendingCount > 0 ? 'border-color:#fcd34d;background:#fffbeb;' : '' }}">
      <div class="slabel" style="{{ $totalPendingCount > 0 ? 'color:#92400e;' : '' }}">Pending Verification</div>
      <div class="svalue" style="{{ $totalPendingCount > 0 ? 'color:#d97706;' : '' }}" id="poll-pending-count" data-count="{{ $totalPendingCount }}">{{ $totalPendingCount }}</div>
    </div>
  </div>

  @php $totalPending = $pendingResidents->count() + $pendingEdits->count(); @endphp
  @if($totalPending > 0)
  <div class="pending-card">
    <div class="pending-header">
      <div class="pending-title">
        <i class="fas fa-clock"></i>
        Pending Verification
        <span class="pending-count">{{ $totalPending }}</span>
      </div>
      <span class="pending-note">
        @if(auth()->user()->role === 'admin')
          <i class="fas fa-shield-alt" style="margin-right:4px"></i>As admin, you can approve or reject these records.
        @else
          <i class="fas fa-eye" style="margin-right:4px"></i>These records are awaiting admin approval.
        @endif
      </span>
    </div>
    <div class="table-wrap">
      <table class="pending-table">
        <thead>
          <tr>
            <th>Type</th>
            <th>Resident</th>
            <th>Sex / Age</th>
            <th>Civil Status</th>
            <th>Address</th>
            <th>Submitted</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>

          {{-- New resident submissions --}}
          @foreach($pendingResidents as $pr)
          <tr>
            <td>
              <span style="background:#fef3c7;color:#92400e;font-size:10px;font-weight:700;padding:3px 8px;border-radius:20px;white-space:nowrap">
                <i class="fas fa-user-plus" style="margin-right:3px"></i>New Resident
              </span>
            </td>
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
            <td style="font-size:12px;color:var(--muted)">{{ $pr->created_at->format('M d, Y') }}</td>
            <td>
              <div class="action-btns">
                <button type="button" onclick='openResidentModal(@json($pr), "new")' class="btn btn-sm btn-view">
                  <i class="fas fa-eye"></i> View
                </button>
                @if(auth()->user()->role === 'admin')
                <form method="POST" action="{{ route('residents.approve', $pr->id) }}" style="display:inline">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-approve"><i class="fas fa-check"></i> Approve</button>
                </form>
                <form method="POST" action="{{ route('residents.reject', $pr->id) }}" style="display:inline"
                  onsubmit="return confirmReject(this, 'Reject and remove the pending record for {{ addslashes($pr->first_name) }} {{ addslashes($pr->last_name) }}? This cannot be undone.')">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-reject"><i class="fas fa-times"></i> Reject</button>
                </form>
                @endif
              </div>
            </td>
          </tr>
          @endforeach

          {{-- Pending edit requests --}}
          @foreach($pendingEdits as $pe)
          @php $pr = $pe->resident; $pd = $pe->proposed_data; @endphp
          <tr>
            <td>
              <span style="background:#ede9fe;color:#6d28d9;font-size:10px;font-weight:700;padding:3px 8px;border-radius:20px;white-space:nowrap">
                <i class="fas fa-pencil-alt" style="margin-right:3px"></i>Edit Request
              </span>
            </td>
            <td>
              <div style="font-weight:700">{{ $pr->last_name }}, {{ $pr->first_name }} {{ $pr->middle_name }}</div>
              <div style="font-size:11px;color:var(--muted)">ID #{{ $pr->id }} · By {{ $pe->submitted_by_name }}</div>
              <button type="button" onclick="toggleEditDiff({{ $pe->id }})"
                style="font-size:10px;color:#6d28d9;background:none;border:none;cursor:pointer;padding:0;margin-top:2px;font-family:inherit">
                <i class="fas fa-eye" style="margin-right:3px"></i>View proposed changes
              </button>
              <div id="edit-diff-{{ $pe->id }}" style="display:none;margin-top:8px;background:#f5f3ff;border:1px solid #ddd6fe;border-radius:8px;padding:10px;font-size:11px;">
                @php
                  $fields = ['last_name'=>'Last Name','first_name'=>'First Name','middle_name'=>'Middle Name','gender'=>'Sex','birthdate'=>'Birthdate','age'=>'Age','civil_status'=>'Civil Status','address'=>'Address','barangay'=>'Barangay','city'=>'City','province'=>'Province','occupation'=>'Occupation','education_level'=>'Education'];
                @endphp
                @foreach($fields as $key => $label)
                  @php $old = $pr->$key ?? '—'; $new = $pd[$key] ?? '—'; @endphp
                  @if((string)$old !== (string)$new)
                  <div style="display:flex;gap:6px;margin-bottom:4px;align-items:baseline">
                    <span style="font-weight:700;color:#6d28d9;min-width:90px">{{ $label }}:</span>
                    <span style="color:#be123c;text-decoration:line-through">{{ $old }}</span>
                    <span style="color:#64748b">→</span>
                    <span style="color:#166534;font-weight:600">{{ $new }}</span>
                  </div>
                  @endif
                @endforeach
              </div>
            </td>
            <td>{{ $pr->gender }} / {{ $pr->age }} yrs</td>
            <td>{{ $pr->civil_status ?? '—' }}</td>
            <td>
              <div>{{ $pr->address ?? '—' }}</div>
              <div style="font-size:11px;color:var(--muted)">{{ $pr->barangay }}, {{ $pr->city }}</div>
            </td>
            <td style="font-size:12px;color:var(--muted)">{{ $pe->created_at->format('M d, Y') }}</td>
            <td>
              <div class="action-btns">
                <button type="button" onclick='openResidentModal(@json($pr), "edit")' class="btn btn-sm btn-view">
                  <i class="fas fa-eye"></i> View
                </button>
                @if(auth()->user()->role === 'admin')
                <form method="POST" action="{{ route('residents.approveEdit', $pe->id) }}" style="display:inline">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-approve"><i class="fas fa-check"></i> Approve</button>
                </form>
                <form method="POST" action="{{ route('residents.rejectEdit', $pe->id) }}" style="display:inline"
                  onsubmit="return confirmReject(this, 'Reject the proposed edit for {{ addslashes($pr->first_name) }} {{ addslashes($pr->last_name) }}? The current record will remain unchanged.')">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-reject"><i class="fas fa-times"></i> Reject</button>
                </form>
                @endif
              </div>
            </td>
          </tr>
          @endforeach

        </tbody>
      </table>
    </div>
  </div>
  @endif

  <div class="card">
    @php
      $fGender = $filters['gender'] ?? '';
      $fCivil  = $filters['civil']  ?? '';
      $fPurok  = $filters['purok']  ?? '';
      $fClass  = $filters['classification'] ?? '';
      $fAgeMin = $filters['ageMin'] ?? null;
      $fAgeMax = $filters['ageMax'] ?? null;
      $fSearch = $filters['search'] ?? '';
      $ageLabel = ($fAgeMin || $fAgeMax) ? ($fAgeMin ?? '0').'–'.($fAgeMax ?? '∞').' yrs' : 'Age Range';
      $classLabel = match($fClass) { 'senior'=>'Senior Citizen','pwd'=>'PWD','voter'=>'Registered Voter','solo_parent'=>'Solo Parent',default=>'Classification' };
    @endphp
    <div class="filter-area">
      <div class="search-wrap" style="display:flex;gap:6px">
        <div style="position:relative;flex:1">
          <span class="si"><i class="fas fa-search"></i></span>
          <input type="text" id="searchInput" placeholder="Search by name, address, or sitio..." value="{{ $fSearch }}">
        </div>
        @if($fSearch)
        <a href="{{ request()->fullUrlWithQuery(['search'=>'','page'=>null]) }}" class="flt-btn" style="white-space:nowrap;text-decoration:none">
          <i class="fas fa-times"></i> Clear search
        </a>
        @endif
      </div>
      <div class="filter-controls">

        <!-- Sex -->
        <div class="flt-wrap" id="wrap-gender">
          <button class="flt-btn {{ $fGender ? 'active' : '' }}" id="btn-gender" onclick="toggleFlt('gender')">
            <i class="fas fa-venus-mars"></i>
            <span id="lbl-gender">{{ $fGender ?: 'Sex' }}</span>
            <i class="fas fa-chevron-down flt-caret" id="caret-gender" style="{{ $fGender ? 'display:none' : '' }}"></i>
            <span class="flt-x" id="x-gender" style="{{ $fGender ? '' : 'display:none' }}" onclick="event.stopPropagation();applyFilter('gender','')">×</span>
          </button>
          <div class="flt-dropdown" id="dd-gender">
            <div class="flt-option {{ !$fGender ? 'selected' : '' }}" onclick="applyFilter('gender','')">All</div>
            <div class="flt-option {{ $fGender==='Male' ? 'selected' : '' }}" onclick="applyFilter('gender','Male')">Male</div>
            <div class="flt-option {{ $fGender==='Female' ? 'selected' : '' }}" onclick="applyFilter('gender','Female')">Female</div>
          </div>
        </div>

        <!-- Civil Status -->
        <div class="flt-wrap" id="wrap-civil">
          <button class="flt-btn {{ $fCivil ? 'active' : '' }}" id="btn-civil" onclick="toggleFlt('civil')">
            <i class="fas fa-heart"></i>
            <span id="lbl-civil">{{ $fCivil ? ucfirst($fCivil) : 'Civil Status' }}</span>
            <i class="fas fa-chevron-down flt-caret" id="caret-civil" style="{{ $fCivil ? 'display:none' : '' }}"></i>
            <span class="flt-x" id="x-civil" style="{{ $fCivil ? '' : 'display:none' }}" onclick="event.stopPropagation();applyFilter('civil','')">×</span>
          </button>
          <div class="flt-dropdown" id="dd-civil">
            <div class="flt-option {{ !$fCivil ? 'selected' : '' }}" onclick="applyFilter('civil','')">All</div>
           @foreach(['single','married','widowed','separated','annulled','common law','divorced','live-in'] as $cv)
<div class="flt-option {{ strtolower($fCivil)===$cv ? 'selected' : '' }}" onclick="applyFilter('civil','{{ $cv }}')">{{ ucwords($cv) }}</div>
@endforeach
          </div>
        </div>

        <!-- Purok -->
        <div class="flt-wrap" id="wrap-sitio">
          <button class="flt-btn {{ $fPurok ? 'active' : '' }}" id="btn-sitio" onclick="toggleFlt('sitio')">
            <i class="fas fa-map-pin"></i>
            <span id="lbl-sitio">{{ $fPurok ?: 'Purok' }}</span>
            <i class="fas fa-chevron-down flt-caret" id="caret-sitio" style="{{ $fPurok ? 'display:none' : '' }}"></i>
            <span class="flt-x" id="x-sitio" style="{{ $fPurok ? '' : 'display:none' }}" onclick="event.stopPropagation();applyFilter('purok','')">×</span>
          </button>
          <div class="flt-dropdown" id="dd-sitio">
            <div class="flt-option {{ !$fPurok ? 'selected' : '' }}" onclick="applyFilter('purok','')">All</div>
            @foreach(['Chrysanthemum','Dahlia','Dama de Noche','Ilang-Ilang','Jasmin','Rosal','Sampaguita'] as $s)
            <div class="flt-option {{ strtolower($fPurok)===strtolower($s) ? 'selected' : '' }}" onclick="applyFilter('purok','{{ $s }}')">{{ $s }}</div>
            @endforeach
          </div>
        </div>

        <!-- Classification -->
        <div class="flt-wrap" id="wrap-class">
          <button class="flt-btn {{ $fClass ? 'active' : '' }}" id="btn-class" onclick="toggleFlt('class')">
            <i class="fas fa-tag"></i>
            <span id="lbl-class">{{ $classLabel }}</span>
            <i class="fas fa-chevron-down flt-caret" id="caret-class" style="{{ $fClass ? 'display:none' : '' }}"></i>
            <span class="flt-x" id="x-class" style="{{ $fClass ? '' : 'display:none' }}" onclick="event.stopPropagation();applyFilter('classification','')">×</span>
          </button>
          <div class="flt-dropdown" id="dd-class">
            <div class="flt-option {{ !$fClass ? 'selected' : '' }}" onclick="applyFilter('classification','')">All</div>
            <div class="flt-option {{ $fClass==='senior' ? 'selected' : '' }}" onclick="applyFilter('classification','senior')">Senior Citizen</div>
            <div class="flt-option {{ $fClass==='pwd' ? 'selected' : '' }}" onclick="applyFilter('classification','pwd')">PWD</div>
            <div class="flt-option {{ $fClass==='voter' ? 'selected' : '' }}" onclick="applyFilter('classification','voter')">Registered Voter</div>
            <div class="flt-option {{ $fClass==='solo_parent' ? 'selected' : '' }}" onclick="applyFilter('classification','solo_parent')">Solo Parent</div>
          </div>
        </div>

        <!-- Age Range -->
        <div class="flt-wrap" id="wrap-age">
          <button class="flt-btn {{ ($fAgeMin || $fAgeMax) ? 'active' : '' }}" id="ageFilterBtn" onclick="toggleAgePopup()">
            <i class="fas fa-sliders-h"></i>
            <span id="lbl-age">{{ $ageLabel }}</span>
            <i class="fas fa-chevron-down flt-caret" id="caret-age" style="{{ ($fAgeMin || $fAgeMax) ? 'display:none' : '' }}"></i>
            <span class="flt-x" id="x-age" style="{{ ($fAgeMin || $fAgeMax) ? '' : 'display:none' }}" onclick="event.stopPropagation();applyFilter('age_min','');applyFilter('age_max','')">×</span>
          </button>
          <div class="age-popup" id="agePopup">
            <div class="age-popup-title">Filter by Age Range</div>
            <div class="age-range-row">
              <input type="number" id="ageMin" placeholder="Min" min="0" max="150" value="{{ $fAgeMin ?? '' }}">
              <span>to</span>
              <input type="number" id="ageMax" placeholder="Max" min="0" max="150" value="{{ $fAgeMax ?? '' }}">
              <span>yrs</span>
            </div>
            <div class="age-popup-actions">
              <button class="btn-age-clear" onclick="clearAge()">Clear</button>
              <button class="btn-age-apply" onclick="applyAge()">Apply</button>
            </div>
          </div>
        </div>

        @if($fGender || $fCivil || $fPurok || $fClass || $fAgeMin || $fAgeMax || $fSearch)
        <a href="{{ route('residents.index') }}" class="flt-btn" style="margin-left:auto;text-decoration:none;color:var(--muted);border-color:var(--border);white-space:nowrap;">
          <i class="fas fa-times"></i> Clear Filters
        </a>
        @endif

      </div>
    </div>

    @if(auth()->user()->role == 'admin')
    <div id="selectAllBanner" style="display:none;padding:8px 16px;background:#eff6ff;border-bottom:1px solid #bfdbfe;font-size:13px;color:#1e40af;text-align:center">
      All <strong id="bannerPageCount">{{ $residents->perPage() }}</strong> residents on this page are selected.
      <a href="#" onclick="selectAllRecords(); return false;" style="font-weight:700;color:#1d4ed8;text-decoration:underline">Select all <strong>{{ $residents->total() }}</strong> residents</a>
      &nbsp;&middot;&nbsp;<a href="#" onclick="clearSelectAll(); return false;" style="color:#64748b;text-decoration:underline">Clear</a>
    </div>
    @endif
    <div class="table-wrap">
      <table id="residentsTable">
        <thead>
          <tr>
            @if(auth()->user()->role == 'admin')
            <th style="width:40px"><input type="checkbox" id="selectAll" onchange="toggleAll(this)" style="width:16px;height:16px;cursor:pointer" title="Select All"></th>
            @endif
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
          @php
            $trSitio = strtolower($resident->household?->sitio ?? '');
            if (! $trSitio) {
                foreach (['Chrysanthemum','Dahlia','Dama de Noche','Ilang-Ilang','Jasmin','Rosal','Sampaguita'] as $_pn) {
                    if (stripos($resident->address ?? '', $_pn) === 0) { $trSitio = strtolower($_pn); break; }
                }
            }
          @endphp
          <tr ondblclick='openResidentModal(@json($resident))' data-age="{{ $resident->age ?? '' }}" data-civil="{{ strtolower($resident->civil_status ?? '') }}" data-sitio="{{ $trSitio }}">

            @if(auth()->user()->role == 'admin')
            <td onclick="event.stopPropagation()">
              <input type="checkbox" class="row-check" value="{{ $resident->id }}"
                     style="width:16px;height:16px;cursor:pointer">
            </td>
            @endif
            <td style="color:var(--muted);font-size:12px">{{ $residents->firstItem() + $loop->index }}</td>

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
              @if($resident->is_solo_parent)<span class="badge" style="background:#fef9c3;color:#854d0e">Solo Parent</span>@endif
              @if(!$resident->is_senior && !$resident->is_pwd && !$resident->is_voter && !$resident->is_solo_parent)
              <span style="color:var(--muted);font-size:12px">—</span>
              @endif
            </td>

            <td>
              <div class="action-btns">

                <button onclick='event.stopPropagation();openResidentModal(@json($resident))' class="btn btn-sm btn-view">
                  <i class="fas fa-eye"></i> View
                </button>

                <a href="{{ route('residents.edit', $resident->id) }}" class="btn btn-sm btn-edit" onclick="event.stopPropagation()">
                  <i class="fas fa-edit"></i> Edit
                </a>

                @if(auth()->user()->role == 'admin')
                <form method="POST" action="{{ route('residents.destroy', $resident->id) }}" style="display:inline" onsubmit="return confirmDelete(this,'Delete {{ addslashes($resident->first_name) }} {{ addslashes($resident->last_name) }}\'s resident record? This cannot be undone.')" onclick="event.stopPropagation()">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-delete">
                    <i class="fas fa-trash"></i> Delete
                  </button>
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

    @if($residents->hasPages())
    <div class="pg-bar">
      <span class="pg-info">
        Showing {{ $residents->firstItem() }}–{{ $residents->lastItem() }} of {{ $residents->total() }} residents
      </span>
      <div class="pg-controls">
        {{-- Previous --}}
        @if($residents->onFirstPage())
          <button class="pg-btn" disabled><i class="fas fa-chevron-left"></i></button>
        @else
          <a href="{{ $residents->previousPageUrl() }}" class="pg-btn"><i class="fas fa-chevron-left"></i></a>
        @endif

        {{-- Page numbers (window of 5 around current) --}}
        @php
          $start = max(1, $residents->currentPage() - 2);
          $end   = min($residents->lastPage(), $residents->currentPage() + 2);
        @endphp
        @if($start > 1)
          <a href="{{ $residents->url(1) }}" class="pg-btn">1</a>
          @if($start > 2)<span class="pg-btn" style="border:none;cursor:default">…</span>@endif
        @endif
        @for($p = $start; $p <= $end; $p++)
          <a href="{{ $residents->url($p) }}" class="pg-btn {{ $p == $residents->currentPage() ? 'active' : '' }}">{{ $p }}</a>
        @endfor
        @if($end < $residents->lastPage())
          @if($end < $residents->lastPage() - 1)<span class="pg-btn" style="border:none;cursor:default">…</span>@endif
          <a href="{{ $residents->url($residents->lastPage()) }}" class="pg-btn">{{ $residents->lastPage() }}</a>
        @endif

        {{-- Next --}}
        @if($residents->hasMorePages())
          <a href="{{ $residents->nextPageUrl() }}" class="pg-btn"><i class="fas fa-chevron-right"></i></a>
        @else
          <button class="pg-btn" disabled><i class="fas fa-chevron-right"></i></button>
        @endif
      </div>
    </div>
    @endif

  </div>
</div>

<!-- Reject Confirm Modal -->
<div id="rejectBackdrop" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:600;align-items:center;justify-content:center">
  <div style="background:#fff;border-radius:18px;padding:32px 28px;max-width:380px;width:90%;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,.25);position:relative;z-index:601">
    <div style="width:64px;height:64px;border-radius:50%;background:#fff1f2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;border:2px solid #fecdd3">
      <i class="fas fa-ban" style="font-size:26px;color:#be123c"></i>
    </div>
    <div style="font-size:17px;font-weight:700;color:var(--primary);margin-bottom:8px">Reject this record?</div>
    <p id="rejectMsg" style="font-size:13px;color:var(--muted);margin:0 0 24px;line-height:1.5"></p>
    <div style="display:flex;gap:10px;justify-content:center">
      <button id="rejectCancel" style="flex:1;padding:10px 16px;border-radius:8px;border:1.5px solid var(--border);background:#fff;color:var(--text);font-size:14px;font-weight:600;cursor:pointer;font-family:inherit">
        <i class="fas fa-times"></i> Cancel
      </button>
      <button id="rejectOk" style="flex:1;padding:10px 16px;border-radius:8px;border:none;background:#be123c;color:#fff;font-size:14px;font-weight:600;cursor:pointer;font-family:inherit">
        <i class="fas fa-ban"></i> Reject
      </button>
    </div>
  </div>
</div>

<!-- RBI Print Frame (hidden; only visible on print) -->
<div id="rbi-print-frame">
  <div class="rp-formid">RBI FORM B</div>
  <div class="rp-title">Individual Record of Barangay Inhabitant</div>
  <div class="rp-top-grid">
    <div class="rp-top-cell"><span class="rp-top-lbl">REGION:</span><span class="rp-top-val">VIII (Eastern Visayas)</span></div>
    <div class="rp-top-cell"><span class="rp-top-lbl">CITY / MUN.:</span><span id="rp-city" class="rp-top-val"></span></div>
    <div class="rp-top-cell"><span class="rp-top-lbl">PROVINCE:</span><span id="rp-prov" class="rp-top-val"></span></div>
    <div class="rp-top-cell"><span class="rp-top-lbl">BARANGAY:</span><span id="rp-brgy" class="rp-top-val"></span></div>
  </div>
  <div class="rp-section-box">
    <div class="rp-section-title">I. Personal Information</div>
    <!-- Name -->
    <div class="rp-field-row">
      <span class="rp-field-lbl">NAME:</span>
      <div class="rp-name-cols">
        <div class="rp-named-col"><div id="rp-last" class="rp-uline"></div><div class="rp-col-sub">Last Name</div></div>
        <div class="rp-named-col"><div id="rp-first" class="rp-uline"></div><div class="rp-col-sub">First Name</div></div>
        <div class="rp-named-col"><div id="rp-middle" class="rp-uline"></div><div class="rp-col-sub">Middle Name</div></div>
        <div class="rp-named-col"><div class="rp-uline"> </div><div class="rp-col-sub">Ext.</div></div>
      </div>
    </div>
    <!-- DOB + Age -->
    <div class="rp-field-row">
      <span class="rp-field-lbl">DATE OF BIRTH:</span>
      <div class="rp-dob-cols">
        <div class="rp-dob-box"><div id="rp-dob-mm" class="rp-uline" style="width:40px;text-align:center"></div><div class="rp-col-sub" style="width:40px">MM</div></div>
        <div class="rp-dob-box"><div id="rp-dob-dd" class="rp-uline" style="width:40px;text-align:center"></div><div class="rp-col-sub" style="width:40px">DD</div></div>
        <div class="rp-dob-box"><div id="rp-dob-yy" class="rp-uline" style="width:65px;text-align:center"></div><div class="rp-col-sub" style="width:65px">YYYY</div></div>
      </div>
      <span class="rp-field-lbl" style="margin-left:24px">AGE:</span>
      <div id="rp-age" class="rp-uline sm"></div>
    </div>
    <!-- Sex + Civil Status -->
    <div class="rp-field-row" style="align-items:flex-start">
      <div style="display:flex;align-items:center;gap:10px;flex:1">
        <span class="rp-field-lbl">SEX:</span>
        <div class="rp-check-row">
          <span class="rp-check-item"><span id="rp-sex-m" class="rp-cb"></span> Male</span>
          <span class="rp-check-item"><span id="rp-sex-f" class="rp-cb"></span> Female</span>
          <span class="rp-check-item"><span id="rp-sex-o" class="rp-cb"></span> Other</span>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:10px;flex:2">
        <span class="rp-field-lbl">CIVIL STATUS:</span>
        <div class="rp-check-row">
          <span class="rp-check-item"><span id="rp-cs-single" class="rp-cb"></span> Single</span>
          <span class="rp-check-item"><span id="rp-cs-married" class="rp-cb"></span> Married</span>
          <span class="rp-check-item"><span id="rp-cs-widow" class="rp-cb"></span> Widow/er</span>
          <span class="rp-check-item"><span id="rp-cs-sep" class="rp-cb"></span> Separated</span>
        </div>
      </div>
    </div>
    <!-- Citizenship + Type of Resident -->
    <div class="rp-field-row">
      <span class="rp-field-lbl">CITIZENSHIP:</span>
      <div id="rp-nat" class="rp-uline md"></div>
      <span class="rp-field-lbl" style="margin-left:20px">TYPE:</span>
      <div id="rp-restype" class="rp-uline md"></div>
    </div>
    <!-- Religion -->
    <div class="rp-field-row">
      <span class="rp-field-lbl">RELIGION:</span>
      <div id="rp-rel" class="rp-uline"></div>
    </div>
    <!-- Occupation -->
    <div class="rp-field-row">
      <span class="rp-field-lbl">OCCUPATION:</span>
      <div id="rp-occ" class="rp-uline"></div>
    </div>
    <!-- Employer + Income -->
    <div class="rp-field-row">
      <span class="rp-field-lbl">EMPLOYER:</span>
      <div id="rp-emp" class="rp-uline"></div>
      <span class="rp-field-lbl" style="margin-left:20px;white-space:nowrap">MONTHLY INCOME:</span>
      <div class="rp-income-row"><span style="font-size:9pt;padding-bottom:2px">₱</span><div id="rp-inc" class="rp-uline md"></div></div>
    </div>
    <!-- Education -->
    <div class="rp-field-row">
      <span class="rp-field-lbl">EDUCATION:</span>
      <div id="rp-edu" class="rp-uline"></div>
    </div>
    <!-- Contact + Email -->
    <div class="rp-field-row">
      <span class="rp-field-lbl">CONTACT NO.:</span>
      <div id="rp-contact" class="rp-uline md"></div>
      <span class="rp-field-lbl" style="margin-left:20px">EMAIL:</span>
      <div id="rp-email" class="rp-uline"></div>
    </div>
    <div class="rp-field-row">
      <span class="rp-field-lbl">PHILSYS NO.:</span>
      <div id="rp-philsys" class="rp-uline md"></div>
    </div>
    <!-- Address -->
    <div class="rp-field-row" style="align-items:flex-start">
      <span class="rp-field-lbl" style="margin-top:2px">RESIDENCE<br>ADDRESS:</span>
      <div style="flex:1;display:flex;flex-direction:column;gap:6px">
        <div><div id="rp-addr" class="rp-uline"></div><div class="rp-col-sub" style="text-align:left">Purok / Sitio / Street / House No.</div></div>
        <div style="display:flex;gap:10px">
          <div style="flex:1"><div id="rp-brgy2" class="rp-uline"></div><div class="rp-col-sub" style="text-align:left">Barangay</div></div>
          <div style="flex:1"><div id="rp-city2" class="rp-uline"></div><div class="rp-col-sub" style="text-align:left">City / Municipality</div></div>
          <div style="flex:1"><div id="rp-prov2" class="rp-uline"></div><div class="rp-col-sub" style="text-align:left">Province</div></div>
        </div>
      </div>
    </div>
  </div>
  <!-- Section II -->
  <div class="rp-section-box">
    <div class="rp-section-title">II. Special Classifications</div>
    <div style="display:flex;gap:20px;flex-wrap:wrap">
      <span class="rp-check-item"><span id="rp-senior" class="rp-cb"></span> Senior Citizen (60+)</span>
      <span class="rp-check-item"><span id="rp-pwd" class="rp-cb"></span> Person with Disability (PWD)</span>
      <span class="rp-check-item"><span id="rp-voter" class="rp-cb"></span> Registered Voter</span>
      <span class="rp-check-item"><span id="rp-solo-parent" class="rp-cb"></span> Solo Parent</span>
      <span class="rp-check-item"><span id="rp-deceased" class="rp-cb"></span> Deceased</span>
    </div>
  </div>
  <!-- Certification -->
  <div class="rp-section-box">
    <p style="font-size:8.5pt;font-style:italic;margin:0 0 14px">I hereby certify that the above information is true and correct to the best of my knowledge.</p>
    <div class="rp-sign-row">
      <div>
        <div style="display:flex;flex-direction:column;gap:18px">
          <div><div id="rp-today" class="rp-uline"></div><div class="rp-sign-lbl">Date Accomplished</div></div>
          <div><div class="rp-uline"> </div><div class="rp-sign-lbl">Signature of Resident / Person Accomplishing the Form</div></div>
        </div>
      </div>
      <div>
        <div style="font-size:8pt;text-align:center;margin-bottom:6px">Thumbprints</div>
        <div class="rp-thumb-area">
          <div class="rp-thumb-box"><div class="rp-thumb-rect"></div><div class="rp-thumb-lbl">Left Thumbmark</div></div>
          <div class="rp-thumb-box"><div class="rp-thumb-rect"></div><div class="rp-thumb-lbl">Right Thumbmark</div></div>
        </div>
      </div>
    </div>
    <hr style="border:none;border-top:1px solid #000;margin:8px 0">
    <div class="rp-attest-row">
      <span style="font-size:8.5pt;font-weight:bold">Attested by:</span>
      <div style="flex:1"><div class="rp-uline"> </div><div class="rp-sign-lbl">Barangay Secretary</div></div>
      <div style="display:flex;flex-direction:column;align-items:center">
        <div id="rp-hhno" class="rp-hh-box"></div>
        <div style="font-size:7.5pt;margin-top:3px;text-align:center">Household Number</div>
      </div>
    </div>
  </div>
  <div class="rp-note">Note: The Household No. shall be filled up by the Barangay Secretary. &nbsp;|&nbsp; Resident ID #<span id="rp-rid"></span> &nbsp;|&nbsp; Printed: <span id="rp-today2"></span></div>
</div>

<!-- Resident View Modal -->
<div id="residentModal" class="modal-backdrop">
  <div class="modal">
    <div class="modal-header" id="rm-header">
      <h2 id="rm-title"><i class="fas fa-user" style="margin-right:8px"></i>Resident Profile</h2>
      <button class="modal-close" onclick="closeResidentModal()">×</button>
    </div>
    <div id="rm-pending-banner" style="display:none;padding:10px 24px;background:#fffbeb;border-bottom:1px solid #fcd34d;font-size:12px;font-weight:600;color:#92400e;display:none;align-items:center;gap:8px">
      <i class="fas fa-clock"></i>
      <span id="rm-pending-text"></span>
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
          <div class="mi"><span class="ml">Citizenship</span><span class="mv" id="rm-nat"></span></div>
          <div class="mi"><span class="ml">Type of Resident</span><span class="mv" id="rm-restype"></span></div>
          <div class="mi"><span class="ml">Religion</span><span class="mv" id="rm-rel"></span></div>
        </div>
      </div>

      <div class="modal-section">
        <div class="modal-section-title"><i class="fas fa-phone"></i> Contact Information</div>
        <div class="mgrid">
          <div class="mi"><span class="ml">Contact Number</span><span class="mv" id="rm-contact"></span></div>
          <div class="mi"><span class="ml">Email</span><span class="mv" id="rm-email"></span></div>
          <div class="mi"><span class="ml">PhilSys Card No.</span><span class="mv" id="rm-philsys"></span></div>
        </div>
      </div>

      <div class="modal-section">
        <div class="modal-section-title"><i class="fas fa-map-marker-alt"></i> Address</div>
        <div class="mgrid">
          <div class="mi"><span class="ml">Province</span><span class="mv" id="rm-prov"></span></div>
          <div class="mi"><span class="ml">City / Municipality</span><span class="mv" id="rm-city"></span></div>
          <div class="mi"><span class="ml">Barangay</span><span class="mv" id="rm-brgy"></span></div>
          <div class="mi"><span class="ml">Purok</span><span class="mv" id="rm-purok"></span></div>
          <div class="mi"><span class="ml">Street / House No.</span><span class="mv" id="rm-street"></span></div>
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
      <button id="rm-print-btn" type="button" onclick="printRBIForm()" class="btn btn-sm" style="background:#fff;color:#374151;border:1.5px solid #d1d5db;display:none">
        <i class="fas fa-print"></i> Print RBI Form
      </button>
      <button onclick="closeResidentModal()" class="btn btn-sm" style="background:#f1f5f9;color:var(--muted);border:1px solid var(--border)">
        <i class="fas fa-times"></i> Close
      </button>
    </div>
  </div>
</div>

<script>
var _rbiResident = null;

const PUROK_NAMES = ['Chrysanthemum','Dahlia','Dama de Noche','Ilang-Ilang','Jasmin','Rosal','Sampaguita'];
function extractPurok(r) {
  if (r.household && r.household.sitio) return r.household.sitio;
  const addr = (r.address || '').toLowerCase();
  for (const p of PUROK_NAMES) {
    if (addr.startsWith(p.toLowerCase())) return p;
  }
  return null;
}
function extractStreet(r) {
  const addr = (r.address || '').trim();
  const purok = extractPurok(r);
  if (purok && addr.toLowerCase().startsWith(purok.toLowerCase())) {
    return addr.slice(purok.length).trim() || null;
  }
  return addr || null;
}

function printRBIForm() {
  var r = _rbiResident;
  if (!r) return;

  // Parse DOB
  var dobMM = '', dobDD = '', dobYYYY = '';
  if (r.birthdate) {
    var d = new Date(r.birthdate);
    dobMM   = String(d.getMonth()+1).padStart(2,'0');
    dobDD   = String(d.getDate()).padStart(2,'0');
    dobYYYY = d.getFullYear();
  }
  var cs = (r.civil_status || '').toLowerCase().trim();
  var today = new Date().toLocaleDateString('en-US',{month:'long',day:'2-digit',year:'numeric'});
  var hhNo  = (r.household && r.household.household_no) ? r.household.household_no : '';

  // Text fields
  function t(id, val) { var e = document.getElementById(id); if(e) e.textContent = val || ''; }
  t('rp-last',    r.last_name);
  t('rp-first',   r.first_name);
  t('rp-middle',  r.middle_name);
  t('rp-dob-mm',  dobMM);
  t('rp-dob-dd',  dobDD);
  t('rp-dob-yy',  dobYYYY);
  t('rp-age',     r.age || '');
  t('rp-nat',     r.nationality);
  t('rp-restype', r.resident_type);
  t('rp-rel',     r.religion);
  t('rp-occ',     r.occupation);
  t('rp-emp',     r.employer);
  t('rp-inc',     r.monthly_income ? parseFloat(r.monthly_income).toLocaleString('en-PH',{minimumFractionDigits:2}) : '');
  t('rp-edu',     r.education_level);
  t('rp-contact', r.contact_number);
  t('rp-email',   r.email);
  t('rp-philsys', r.philsys_number);
  t('rp-addr',    r.address);
  t('rp-brgy',    r.barangay);
  t('rp-city',    r.city);
  t('rp-prov',    r.province);
  t('rp-brgy2',   r.barangay);
  t('rp-city2',   r.city);
  t('rp-prov2',   r.province);
  t('rp-hhno',    hhNo);
  t('rp-today',   today);
  t('rp-rid',     r.id);
  t('rp-today2',  today);

  // Checkboxes
  function cb(id, on) {
    var e = document.getElementById(id);
    if (e) { if(on) e.classList.add('on'); else e.classList.remove('on'); }
  }
  cb('rp-sex-m',   r.gender === 'Male');
  cb('rp-sex-f',   r.gender === 'Female');
  cb('rp-sex-o',   r.gender === 'Other');
  cb('rp-cs-single', cs === 'single');
  cb('rp-cs-married', cs === 'married');
  cb('rp-cs-widow', cs === 'widow' || cs === 'widower' || cs === 'widow/er');
  cb('rp-cs-sep',  cs === 'separated');
  cb('rp-senior',  !!r.is_senior);
  cb('rp-pwd',     !!r.is_pwd);
  cb('rp-voter',       !!r.is_voter);
  cb('rp-solo-parent', !!r.is_solo_parent);
  cb('rp-deceased',    !!r.is_deceased);

  window.print();
}

function toggleEditDiff(id) {
  const el = document.getElementById('edit-diff-' + id);
  el.style.display = el.style.display === 'none' ? 'block' : 'none';
}

function openResidentModal(r, pendingStatus) {
  document.getElementById('residentModal').classList.add('open');
  const header  = document.getElementById('rm-header');
  const banner  = document.getElementById('rm-pending-banner');
  const bannerText = document.getElementById('rm-pending-text');
  const title   = document.getElementById('rm-title');

  if (pendingStatus === 'new') {
    header.style.background = '#fffbeb';
    header.style.borderBottomColor = '#fcd34d';
    title.innerHTML = '<i class="fas fa-user-clock" style="margin-right:8px;color:#d97706"></i>Resident Profile <span style="font-size:11px;font-weight:600;background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:20px;margin-left:6px;vertical-align:middle">Pending Verification</span>';
    banner.style.display = 'flex';
    bannerText.textContent = 'This is a new resident record awaiting admin verification. It has not been officially added to the system yet.';
  } else if (pendingStatus === 'edit') {
    header.style.background = '#f5f3ff';
    header.style.borderBottomColor = '#ddd6fe';
    title.innerHTML = '<i class="fas fa-user-edit" style="margin-right:8px;color:#6d28d9"></i>Resident Profile <span style="font-size:11px;font-weight:600;background:#ede9fe;color:#6d28d9;padding:2px 8px;border-radius:20px;margin-left:6px;vertical-align:middle">Pending Edit Request</span>';
    banner.style.display = 'flex';
    banner.style.background = '#f5f3ff';
    banner.style.borderBottomColor = '#ddd6fe';
    bannerText.style.color = '#6d28d9';
    bannerText.innerHTML = '<i class="fas fa-info-circle" style="margin-right:4px"></i>Showing current data on record. Use "View proposed changes" in the table to see what will change upon approval.';
  } else {
    header.style.background = '';
    header.style.borderBottomColor = '';
    title.innerHTML = '<i class="fas fa-user" style="margin-right:8px"></i>Resident Profile';
    banner.style.display = 'none';
  }
  _rbiResident = r;
  document.getElementById('rm-print-btn').style.display = (!pendingStatus && r.id) ? 'inline-flex' : 'none';
  document.getElementById('rm-last').textContent    = r.last_name   || '—';
  document.getElementById('rm-first').textContent   = r.first_name  || '—';
  document.getElementById('rm-middle').textContent  = r.middle_name || '—';
  document.getElementById('rm-gender').textContent  = r.gender      || '—';
  document.getElementById('rm-birth').textContent   = r.birthdate   || '—';
  document.getElementById('rm-age').textContent     = r.age ? r.age + ' yrs' : '—';
  document.getElementById('rm-civil').textContent   = r.civil_status  || '—';
  document.getElementById('rm-nat').textContent     = r.nationality    || '—';
  document.getElementById('rm-restype').textContent = r.resident_type  || '—';
  document.getElementById('rm-rel').textContent     = r.religion      || '—';
  document.getElementById('rm-contact').textContent  = r.contact_number  || '—';
  document.getElementById('rm-email').textContent    = r.email           || '—';
  document.getElementById('rm-philsys').textContent  = r.philsys_number  || '—';
  document.getElementById('rm-prov').textContent    = r.province  || '—';
  document.getElementById('rm-city').textContent    = r.city      || '—';
  document.getElementById('rm-brgy').textContent    = r.barangay  || '—';
  document.getElementById('rm-purok').textContent   = extractPurok(r) || '—';
  document.getElementById('rm-street').textContent  = extractStreet(r) || '—';
  document.getElementById('rm-addr').textContent    = r.address   || '—';
  document.getElementById('rm-occ').textContent     = r.occupation      || '—';
  document.getElementById('rm-emp').textContent     = r.employer        || '—';
  document.getElementById('rm-inc').textContent     = r.monthly_income ? '₱' + parseFloat(r.monthly_income).toLocaleString() : '—';
  document.getElementById('rm-edu').textContent     = r.education_level || '—';
  let badges = '';
  if (r.is_deceased) badges += '<span class="badge" style="background:#fee2e2;color:#be123c">Deceased</span> ';
  if (r.is_senior)   badges += '<span class="badge badge-senior">Senior Citizen</span> ';
  if (r.is_pwd)      badges += '<span class="badge badge-pwd">PWD</span> ';
  if (r.is_voter)       badges += '<span class="badge" style="background:#f3e8ff;color:#6b21a8">Registered Voter</span> ';
  if (r.is_solo_parent)         badges += '<span class="badge" style="background:#fef9c3;color:#854d0e">Solo Parent</span> ';
  if (r.is_labor_force)         badges += '<span class="badge" style="background:#e0f2fe;color:#075985">Labor Force</span> ';
  if (r.is_unemployed)          badges += '<span class="badge" style="background:#fee2e2;color:#991b1b">Unemployed</span> ';
  if (r.is_ofw)                 badges += '<span class="badge" style="background:#d1fae5;color:#065f46">OFW</span> ';
  if (r.is_indigenous)          badges += '<span class="badge" style="background:#fdf4ff;color:#6b21a8">Indigenous</span> ';
  if (r.is_out_of_school_child) badges += '<span class="badge" style="background:#fff7ed;color:#9a3412">Out of School Child</span> ';
  if (r.is_out_of_school_youth) badges += '<span class="badge" style="background:#fff7ed;color:#9a3412">Out of School Youth</span> ';
  if (r.is_student)             badges += '<span class="badge" style="background:#eff6ff;color:#1e40af">Student</span> ';
  document.getElementById('rm-badges').innerHTML = badges;
}
function closeResidentModal() {
  document.getElementById('residentModal').classList.remove('open');
}
document.getElementById('residentModal').addEventListener('click', function(e) {
  if (e.target === this) closeResidentModal();
});

// Filter state
const fltKeys = ['gender', 'civil', 'sitio', 'class'];

function positionDropdown(el, btn) {
  const r = btn.getBoundingClientRect();
  el.style.top  = (r.bottom + 6) + 'px';
  el.style.left = r.left + 'px';
  // Prevent going off-screen right
  requestAnimationFrame(function() {
    if (el.offsetWidth && r.left + el.offsetWidth > window.innerWidth - 8)
      el.style.left = Math.max(8, window.innerWidth - el.offsetWidth - 8) + 'px';
  });
}
function toggleFlt(key) {
  const isOpen = document.getElementById('dd-' + key).classList.contains('open');
  fltKeys.forEach(k => document.getElementById('dd-' + k).classList.remove('open'));
  document.getElementById('agePopup').classList.remove('open');
  if (!isOpen) {
    const dd = document.getElementById('dd-' + key);
    positionDropdown(dd, document.getElementById('btn-' + key));
    dd.classList.add('open');
  }
}

function navigate(url) {
  window.location = url.toString();
}

// Navigate URL with a filter param change, resetting pagination
function applyFilter(key, value) {
  const url = new URL(window.location);
  if (value) { url.searchParams.set(key, value); }
  else { url.searchParams.delete(key); }
  url.searchParams.delete('page');
  navigate(url);
}

function toggleAgePopup() {
  const isOpen = document.getElementById('agePopup').classList.contains('open');
  fltKeys.forEach(k => document.getElementById('dd-' + k).classList.remove('open'));
  if (!isOpen) {
    const popup = document.getElementById('agePopup');
    positionDropdown(popup, document.getElementById('ageFilterBtn'));
    popup.classList.add('open');
  } else {
    document.getElementById('agePopup').classList.remove('open');
  }
}
function applyAge() {
  const min = document.getElementById('ageMin').value;
  const max = document.getElementById('ageMax').value;
  const url = new URL(window.location);
  if (min) { url.searchParams.set('age_min', min); } else { url.searchParams.delete('age_min'); }
  if (max) { url.searchParams.set('age_max', max); } else { url.searchParams.delete('age_max'); }
  url.searchParams.delete('page');
  navigate(url);
}
function clearAge() {
  const url = new URL(window.location);
  url.searchParams.delete('age_min');
  url.searchParams.delete('age_max');
  url.searchParams.delete('page');
  navigate(url);
}

// Close all popups when clicking outside
document.addEventListener('click', function(e) {
  fltKeys.forEach(key => {
    const wrap = document.getElementById('wrap-' + key);
    if (wrap && !wrap.contains(e.target)) document.getElementById('dd-' + key).classList.remove('open');
  });
  const ageWrap = document.getElementById('wrap-age');
  if (ageWrap && !ageWrap.contains(e.target)) document.getElementById('agePopup').classList.remove('open');
});

// Search submits on Enter
document.getElementById('searchInput').addEventListener('keydown', function(e) {
  if (e.key === 'Enter') {
    const url = new URL(window.location);
    const val = this.value.trim();
    if (val) { url.searchParams.set('search', val); } else { url.searchParams.delete('search'); }
    url.searchParams.delete('page');
    navigate(url);
  }
});

// Auto-update when search is cleared
document.getElementById('searchInput').addEventListener('input', function() {
  if (this.value === '') {
    const url = new URL(window.location);
    url.searchParams.delete('search');
    url.searchParams.delete('page');
    navigate(url);
  }
});


// ── REJECT CONFIRM ──
var _rejectForm = null;
function confirmReject(form, msg) {
  _rejectForm = form;
  document.getElementById('rejectMsg').textContent = msg;
  const backdrop = document.getElementById('rejectBackdrop');
  backdrop.style.display = 'flex';
  return false;
}
document.getElementById('rejectOk').addEventListener('click', function() {
  if (_rejectForm) _rejectForm.submit();
  document.getElementById('rejectBackdrop').style.display = 'none';
});
document.getElementById('rejectCancel').addEventListener('click', function() {
  document.getElementById('rejectBackdrop').style.display = 'none';
  _rejectForm = null;
});
document.getElementById('rejectBackdrop').addEventListener('click', function(e) {
  if (e.target === this) { this.style.display = 'none'; _rejectForm = null; }
});
</script>

<form id="bulkForm" method="POST" action="{{ route('residents.bulkDestroy') }}" style="display:none">
  @csrf
  @method('DELETE')
</form>

<script>
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
    document.getElementById('selectedCount').textContent = selectAllMode ? '{{ $residents->total() }}' : checked.length;
    btn.style.display = (checked.length > 0 || selectAllMode) ? 'inline-flex' : 'none';
}
function selectAllRecords() {
    selectAllMode = true;
    document.getElementById('selectAllBanner').innerHTML =
        'All <strong>{{ $residents->total() }}</strong> residents are selected. ' +
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
        confirmDelete(form, 'Delete ALL {{ $residents->total() }} residents? This cannot be undone.');
    } else {
        const checked = document.querySelectorAll('.row-check:checked');
        if (!checked.length) return;
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden'; input.name = 'ids[]'; input.value = cb.value;
            form.appendChild(input);
        });
        confirmDelete(form, 'Delete ' + checked.length + ' selected resident(s)? This cannot be undone.');
    }
}
</script>

@endsection