@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <h2>Barangay Clearance Form</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('clearance.store') }}" method="POST" class="form-card">
        @csrf
        <label>Resident Name</label>
        <input type="text" name="resident_name" required>

        <label>Purpose</label>
        <input type="text" name="purpose" required>

        <button type="submit" class="btn-primary">Issue Clearance</button>
    </form>

    <hr>

    <h3>Issued Clearances</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Clearance No</th>
                <th>Resident</th>
                <th>Purpose</th>
                <th>Date Issued</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clearances as $clearance)
            <tr>
                <td>{{ $clearance->clearance_no }}</td>
                <td>{{ $clearance->resident_name }}</td>
                <td>{{ $clearance->purpose }}</td>
                <td>{{ $clearance->date_issued }}</td>
                <td>
                    <a href="{{ route('clearance.print', $clearance->id) }}" class="btn-print">Print</a>

                    <form action="{{ route('clearance.destroy', $clearance->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn-delete">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
