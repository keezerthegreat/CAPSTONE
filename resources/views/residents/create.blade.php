@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Barangay Resident Information Form</h2>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div style="color:green; margin-bottom:10px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR MESSAGE --}}
    @if($errors->any())
        <div style="color:red; margin-bottom:10px;">
            Please complete all required fields.
        </div>
    @endif

    <form method="POST" action="{{ route('residents.store') }}">
        @csrf

        {{-- ================= PERSONAL INFORMATION ================= --}}
        <h4>Personal Information</h4>

        <input type="text" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}" required>
        <input type="text" name="first_name" placeholder="First Name" value="{{ old('first_name') }}" required>
        <input type="text" name="middle_name" placeholder="Middle Name (Optional)" value="{{ old('middle_name') }}">

        <select name="gender" required>
            <option value="">Sex</option>
            <option value="Male" {{ old('gender')=='Male'?'selected':'' }}>Male</option>
            <option value="Female" {{ old('gender')=='Female'?'selected':'' }}>Female</option>
            <option value="Other" {{ old('gender')=='Other'?'selected':'' }}>Other</option>
        </select>

        <input type="date" name="birthdate" value="{{ old('birthdate') }}" required>
        <input type="number" name="age" placeholder="Age" value="{{ old('age') }}" required>

        <input type="text" name="civil_status" placeholder="Civil Status (Single / Married / Widowed)">
        <input type="text" name="nationality" placeholder="Nationality" value="Filipino">
        <input type="text" name="religion" placeholder="Religion">

        {{-- ================= CONTACT INFORMATION ================= --}}
        <h4>Contact Information</h4>

        <input type="text" name="contact_number" placeholder="Contact Number (09xxxxxxxxx)">
        <input type="email" name="email" placeholder="Email Address (Optional)">

        {{-- ================= ADDRESS INFORMATION ================= --}}
        <h4>Complete Address</h4>

        <input type="text" name="province" placeholder="Province" value="{{ old('province') }}" required>
        <input type="text" name="city" placeholder="City / Municipality" value="{{ old('city') }}" required>
        <input type="text" name="barangay" placeholder="Barangay" value="{{ old('barangay') }}" required>
        <textarea name="address" placeholder="House No., Street / Purok / Sitio" required>{{ old('address') }}</textarea>

        {{-- ================= SOCIO-ECONOMIC DATA ================= --}}
        <h4>Socio-Economic Information</h4>

        <input type="text" name="occupation" placeholder="Occupation">
        <input type="text" name="employer" placeholder="Employer / Workplace">
        <input type="number" name="monthly_income" placeholder="Estimated Monthly Income">

        <select name="education_level">
            <option value="">Highest Educational Attainment</option>
            <option>Elementary</option>
            <option>High School</option>
            <option>Senior High</option>
            <option>College</option>
            <option>Vocational</option>
            <option>Post Graduate</option>
        </select>

        {{-- ================= SPECIAL CATEGORIES ================= --}}
        <h4>Special Classification</h4>

        <select name="is_senior">
            <option value="">Senior Citizen?</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>

        <select name="is_pwd">
            <option value="">Person With Disability (PWD)?</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>

        <select name="is_voter">
            <option value="">Registered Voter?</option>
            <option value="1">Yes</option>
            <option value="0">No</option>
        </select>

        {{-- ================= GEOLOCATION ================= --}}
        <h4>House Location (Pin on Map)</h4>

        <input id="latitude" name="latitude" placeholder="Latitude (Click on map)" value="{{ old('latitude') }}" readonly required>
        <input id="longitude" name="longitude" placeholder="Longitude (Click on map)" value="{{ old('longitude') }}" readonly required>

        <div id="map" style="height:350px; margin-top:10px;"></div>

        <br>
        <button type="submit">Save Resident Record</button>
    </form>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
const map = L.map('map').setView([14.5995, 120.9842], 15);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

let marker;

map.on('click', function(e) {
    document.getElementById('latitude').value = e.latlng.lat;
    document.getElementById('longitude').value = e.latlng.lng;

    if (marker) map.removeLayer(marker);
    marker = L.marker(e.latlng).addTo(map);
});
</script>
@endsection
