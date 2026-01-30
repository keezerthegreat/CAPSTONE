@extends('layouts.app')

@section('content')
<div class="main-content">

    <div class="page-header">
        <h1>Certificate Management</h1>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded text-green-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- ISSUE CERTIFICATE --}}
    <div class="table-container p-6 mb-8">
        <h2 class="text-lg font-semibold mb-4">Issue Certificate</h2>

        <form method="POST" action="{{ route('certificate.store') }}">
            @csrf

            <div class="form-group">
                <label>Resident Name</label>
                <input type="text" name="resident_name" required>
            </div>

            <div class="form-group">
                <label>Certificate Type</label>
                <select name="certificate_type" required>
                    <option value="">Select Certificate Type</option>
                    <option value="Good Moral Character Clearance">Good Moral Character Clearance</option>
                    <option value="Residency Certificate">Residency Certificate</option>
                    <option value="Indigency Certificate">Indigency Certificate</option>
                    <option value="Business Operation">Business Operation</option>
                </select>
            </div>

            <div class="form-group">
                <label>Purpose</label>
                <textarea name="purpose" rows="3" required
                          class="w-full border border-gray-300 rounded px-3 py-2 text-sm
                          focus:outline-none focus:ring-2 focus:ring-slate-400"></textarea>
            </div>

            <button type="submit" class="btn-primary">
                Issue Certificate
            </button>
        </form>
    </div>

    {{-- ISSUED CERTIFICATES --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Cert No</th>
                    <th>Resident</th>
                    <th>Type</th>
                    <th>Date Issued</th>
                    <th style="width:230px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($certificates as $cert)
                <tr>
                    <td>{{ $cert->certificate_no }}</td>
                    <td>{{ $cert->resident_name }}</td>
                    <td>{{ $cert->certificate_type }}</td>
                    <td>{{ \Carbon\Carbon::parse($cert->issued_date)->format('F j, Y') }}</td>
                    <td class="flex gap-2">
                        <a href="{{ route('certificate.print', $cert->id) }}"
                           target="_blank"
                           class="btn-print">
                            Print
                        </a>

                        <a href="{{ route('certificate.edit', $cert->id) }}"
                           class="btn-edit">
                            Edit
                        </a>

                        <form action="{{ route('certificate.destroy', $cert->id) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this certificate?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">
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
