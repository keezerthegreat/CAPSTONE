@extends('layouts.app')

@section('content')
<div class="card">
    <h2>Certificate Form</h2>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- ISSUE CERTIFICATE FORM --}}
    <form method="POST" action="{{ route('certificate.store') }}">
        @csrf

        <div class="form-group">
            <label>Resident Name</label>
            <input type="text" name="resident_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Certificate Type</label>
            <select name="certificate_type" class="form-control" required>
                <option value="">Select Certificate Type</option>
                <option value="Good Moral Character Clearance">Good Moral Character Clearance</option>
                <option value="Residency Certificate">Residency Certificate</option>
                <option value="Indigency Certificate">Indigency Certificate</option>
                <option value="Business Operation">Business Operation</option>
            </select>
        </div>

        <div class="form-group">
            <label>Purpose</label>
            <textarea name="purpose" rows="3" class="form-control" required></textarea>
        </div>

        <button class="btn btn-primary">Issue Certificate</button>
    </form>

    <hr>

    {{-- ISSUED CERTIFICATES --}}
    <h3>Issued Certificates</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Cert No</th>
                <th>Resident</th>
                <th>Type</th>
                <th>Date Issued</th>
                <th width="230">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($certificates as $cert)
                <tr>
                    <td>{{ $cert->certificate_no }}</td>
                    <td>{{ $cert->resident_name }}</td>
                    <td>{{ $cert->certificate_type }}</td>
                    <td>{{ \Carbon\Carbon::parse($cert->issued_date)->format('F j, Y') }}</td>
                    <td>
                        <a href="{{ route('certificate.print', $cert->id) }}"
                           class="btn btn-primary btn-sm"
                           target="_blank">
                            Print
                        </a>

                        <a href="{{ route('certificate.edit', $cert->id) }}"
                           class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('certificate.destroy', $cert->id) }}"
                              method="POST"
                              style="display:inline;"
                              onsubmit="return confirm('Are you sure you want to delete this certificate?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
