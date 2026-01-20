@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Residents List</h2>

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

<a href="{{ route('residents.create') }}" class="add-btn">+ Add Resident</a>


    <table border="1" width="100%" cellpadding="5">
        <tr>
            <th>Name</th>
            <th>Gender</th>
            <th>Age</th>
            <th>Address</th>
        </tr>

        @foreach($residents as $resident)
        <tr>
            <td>{{ $resident->last_name }}, {{ $resident->first_name }}</td>
            <td>{{ $resident->gender }}</td>
            <td>{{ $resident->age }}</td>
            <td>{{ $resident->barangay }}, {{ $resident->city }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
