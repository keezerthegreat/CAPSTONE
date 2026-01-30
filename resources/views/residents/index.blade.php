@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Residents List</h2>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <!-- Top bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <a href="{{ route('residents.create') }}" class="add-btn">+ Add Resident</a>

        <!-- 🔍 Search Input (Top Right) -->
        <input
            type="text"
            id="searchInput"
            placeholder="Search resident name..."
            style="padding: 6px; width: 250px;"
        >
    </div>

    <table id="residentsTable" border="1" width="100%" cellpadding="5">
        <thead>
            <tr>
                <th>Name</th>
                <th>Gender</th>
                <th>Age</th>
                <th>Address</th>
            </tr>
        </thead>

        <tbody>
            @foreach($residents as $resident)
            <tr>
                <td>{{ $resident->last_name }}, {{ $resident->first_name }}</td>
                <td>{{ $resident->gender }}</td>
                <td>{{ $resident->age }}</td>
                <td>{{ $resident->barangay }}, {{ $resident->city }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- 🔎 Live Search Script -->
<script>
document.getElementById('searchInput').addEventListener('keyup', function () {
    let searchValue = this.value.toLowerCase();
    let rows = document.querySelectorAll('#residentsTable tbody tr');

    rows.forEach(row => {
        let nameCell = row.cells[0].textContent.toLowerCase();
        row.style.display = nameCell.includes(searchValue) ? '' : 'none';
    });
});
</script>
@endsection
