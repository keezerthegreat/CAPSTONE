@extends('layouts.app')

@section('page-title', 'Edit Barangay Clearance')

@section('content')

<div class="bidb-wrap">

<div class="card">
<div class="card-header">
<div class="card-title">
<i class="fas fa-edit"></i> Edit Clearance
</div>
</div>

<div class="card-body">

<form action="{{ route('clearance.update', $clearance->id) }}" method="POST">
@csrf
@method('PUT')

<div class="form-group">
<label>Resident Name</label>
<input type="text" name="resident_name" value="{{ $clearance->resident_name }}" required>
</div>

<div class="form-group">
<label>Certificate Type</label>
<select name="certificate_type" required>

<option value="Good Moral Character Clearance"
{{ $clearance->certificate_type == 'Good Moral Character Clearance' ? 'selected' : '' }}>
Good Moral Character
</option>

<option value="Residency Certificate"
{{ $clearance->certificate_type == 'Residency Certificate' ? 'selected' : '' }}>
Residency Certificate
</option>

<option value="Indigency Certificate"
{{ $clearance->certificate_type == 'Indigency Certificate' ? 'selected' : '' }}>
Indigency Certificate
</option>

<option value="Business Operation"
{{ $clearance->certificate_type == 'Business Operation' ? 'selected' : '' }}>
Business Operation
</option>

</select>
</div>

<div class="form-group">
<label>Purpose</label>
<input type="text" name="purpose" value="{{ $clearance->purpose }}" required>
</div>

<button type="submit" class="btn btn-primary">
<i class="fas fa-save"></i> Update Clearance
</button>

<a href="{{ route('clearance.index') }}" class="btn btn-secondary">
<i class="fas fa-times"></i> Discard Changes
</a>

</form>

</div>
</div>

</div>

@endsection