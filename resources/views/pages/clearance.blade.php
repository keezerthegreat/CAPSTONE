@extends('layouts.app')

@section('content')
<div class="main-content">

    <div class="page-header">
        <h1>Barangay Clearance</h1>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- ISSUE CLEARANCE --}}
    <div class="table-container p-6 mb-8">
        <h2 class="text-lg font-semibold mb-4">Issue Clearance</h2>

        <form action="{{ route('clearance.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Resident Name</label>
                <input type="text" name="resident_name" required>
            </div>

            <div class="form-group">
                <label>Purpose</label>
                <input type="text" name="purpose" required>
            </div>

            <button type="submit" class="btn-primary">
                Issue Clearance
            </button>
        </form>
    </div>

    {{-- ISSUED CLEARANCES --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Clearance No</th>
                    <th>Resident</th>
                    <th>Purpose</th>
                    <th>Date Issued</th>
                    <th style="width:180px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clearances as $clearance)
                <tr>
                    <td>{{ $clearance->clearance_no }}</td>
                    <td>{{ $clearance->resident_name }}</td>
                    <td>{{ $clearance->purpose }}</td>
                    <td>{{ $clearance->date_issued }}</td>
                    <td class="flex gap-2">
                        <a href="{{ route('clearance.print', $clearance->id) }}"
                           target="_blank"
                           class="btn-print">
                            Print
                        </a>

                        <form action="{{ route('clearance.destroy', $clearance->id) }}"
                              method="POST"
                              onsubmit="return confirm('Delete this clearance?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn-delete">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
