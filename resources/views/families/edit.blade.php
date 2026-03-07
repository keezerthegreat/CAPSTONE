@extends('layouts.app')

@section('page-title', 'Edit Family')

@section('content')
<style>
:root { --primary:#1a3a6b; --primary-light:#2554a0; --bg:#f0f4f8; --card:#fff; --text:#1e293b; --muted:#64748b; --border:#e2e8f0; }
.bidb-wrap { background:var(--bg); min-height:100vh; padding:28px; }
.page-hdr { display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px; }
.page-hdr h1 { font-size:22px; font-weight:700; color:var(--primary); margin:0; }
.breadcrumb { font-size:13px; color:var(--muted); margin-top:2px; }
.breadcrumb a { color:var(--primary); text-decoration:none; }
.breadcrumb span { color:var(--primary); font-weight:500; }
.card { background:var(--card); border-radius:14px; border:1px solid var(--border); box-shadow:0 1px 6px rgba(0,0,0,.06); margin-bottom:20px; overflow:hidden; }
.card-header { padding:16px 20px; border-bottom:1px solid var(--border); }
.card-title { font-weight:700; color:var(--primary); font-size:14px; display:flex; align-items:center; gap:8px; }
.card-body { padding:24px; }
.form-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:20px; }
.form-group { display:flex; flex-direction:column; gap:6px; }
.form-group.full { grid-column:span 3; }
.form-group.half { grid-column:span 2; }
.form-group label { font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.05em; color:var(--muted); }
.form-group input, .form-group select, .form-group textarea { padding:10px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:14px; font-family:inherit; color:var(--text); outline:none; transition:border .15s; }
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { border-color:var(--primary); }
.req { color:#e11d48; }
.btn { display:inline-flex; align-items:center; gap:6px; padding:10px 20px; border-radius:8px; border:none; cursor:pointer; font-family:inherit; font-size:14px; font-weight:600; transition:all .15s; text-decoration:none; }
.btn-primary { background:var(--primary); color:#fff; }
.btn-primary:hover { background:var(--primary-light); }
.btn-outline { background:#fff; color:var(--primary); border:1.5px solid var(--primary); }
.btn-outline:hover { background:#f0f4f8; }
.form-actions { display:flex; gap:10px; justify-content:flex-end; margin-top:8px; }
.alert-error { background:#fff1f2; border:1px solid #fecdd3; color:#be123c; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px; }
</style>

<div class="bidb-wrap">
  <div class="page-hdr">
    <div>
      <h1><i class="fas fa-edit" style="margin-right:8px"></i>Edit Family</h1>
      <div class="breadcrumb">Home › <a href="{{ route('families.index') }}">Families</a> › <span>Edit</span></div>
    </div>
  </div>

  @if($errors->any())
    <div class="alert-error">
      <i class="fas fa-exclamation-circle"></i>
      <ul style="margin:4px 0 0 16px;padding:0">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('families.update', $family->id) }}">
    @csrf
    @method('PUT')

    <div class="card">
      <div class="card-header">
        <div class="card-title"><i class="fas fa-people-roof"></i> Family Information</div>
      </div>
      <div class="card-body">
        <div class="form-grid">
          <div class="form-group full">
            <label>Family Name <span class="req">*</span></label>
            <input type="text" name="family_name" value="{{ old('family_name', $family->family_name) }}" placeholder="e.g. Dela Cruz Family" required>
          </div>
          <div class="form-group">
            <label>Head Last Name <span class="req">*</span></label>
            <input type="text" name="head_last_name" value="{{ old('head_last_name', $family->head_last_name) }}" placeholder="e.g. Dela Cruz" required>
          </div>
          <div class="form-group">
            <label>Head First Name <span class="req">*</span></label>
            <input type="text" name="head_first_name" value="{{ old('head_first_name', $family->head_first_name) }}" placeholder="e.g. Juan" required>
          </div>
          <div class="form-group">
            <label>Head Middle Name</label>
            <input type="text" name="head_middle_name" value="{{ old('head_middle_name', $family->head_middle_name) }}" placeholder="e.g. Santos">
          </div>
          <div class="form-group">
            <label>Number of Members <span class="req">*</span></label>
            <input type="number" name="member_count" value="{{ old('member_count', $family->member_count) }}" min="1" required>
          </div>
          <div class="form-group half">
            <label>Linked Household</label>
            <select name="household_id">
              <option value="">— Not linked —</option>
              @foreach($households as $household)
                <option value="{{ $household->id }}" {{ old('household_id', $family->household_id) == $household->id ? 'selected' : '' }}>
                  HH #{{ $household->household_number }} — {{ $household->head_last_name }}, {{ $household->head_first_name }} ({{ $household->sitio }})
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group full">
            <label>Notes</label>
            <textarea name="notes" rows="3" placeholder="Optional notes...">{{ old('notes', $family->notes) }}</textarea>
          </div>
        </div>
      </div>
    </div>

    <div class="form-actions">
      <a href="{{ route('families.show', $family->id) }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Cancel</a>
      <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Family</button>
    </div>

  </form>
</div>
@endsection