@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Edit Certificate</h2>

    <form method="POST" action="{{ route('certificate.update', $certificate->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Resident Name</label>
            <input type="text"
                   name="resident_name"
                   value="{{ $certificate->resident_name }}"
                   class="form-control"
                   required>
        </div>

        <div class="form-group">
            <label>Certificate Type</label>
            <select name="certificate_type" class="form-control" required>

                <option value="Good Moral Character Clearance"
                    {{ $certificate->certificate_type == 'Good Moral Character Clearance' ? 'selected' : '' }}>
                    Good Moral Character Clearance
                </option>

                <option value="Residency Certificate"
                    {{ $certificate->certificate_type == 'Residency Certificate' ? 'selected' : '' }}>
                    Residency Certificate
                </option>

                <option value="Indigency Certificate"
                    {{ $certificate->certificate_type == 'Indigency Certificate' ? 'selected' : '' }}>
                    Indigency Certificate
                </option>

                <option value="Business Operation"
                    {{ $certificate->certificate_type == 'Business Operation' ? 'selected' : '' }}>
                    Business Operation
                </option>

            </select>
        </div>

        <div class="form-group">
            <label>Purpose</label>
            <textarea name="purpose"
                      rows="3"
                      class="form-control"
                      required>{{ $certificate->purpose }}</textarea>
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('certificate.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
