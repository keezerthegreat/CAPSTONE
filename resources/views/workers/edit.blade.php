@extends('layouts.app')

@section('page-title', 'Edit Worker')

@section('content')

<style>
.form-container {
    max-width: 850px;
    margin: 40px auto;
    background: #fff;
    padding: 40px;
    border: 1px solid #ccc;
    box-shadow: 0 2px 8px rgba(0,0,0,.05);
}

.form-header {
    text-align: center;
    margin-bottom: 30px;
}

.form-header h2 {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 5px;
}

.form-header p {
    font-size: 13px;
    color: #555;
}

.section-title {
    margin-top: 25px;
    margin-bottom: 10px;
    font-weight: 700;
    font-size: 14px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 4px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 4px;
}

.form-group input,
.form-group select {
    padding: 8px;
    border: 1px solid #bbb;
    border-radius: 4px;
    font-size: 13px;
}

.full-width {
    grid-column: span 2;
}

.button-group {
    margin-top: 30px;
    display: flex;
    gap: 10px;
}

.submit-btn {
    padding: 10px 18px;
    background: #1a3a6b;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}

.submit-btn:hover {
    background: #2554a0;
}

.cancel-btn {
    padding: 10px 18px;
    background: #e2e8f0;
    color: #334155;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
}

.cancel-btn:hover {
    background: #cbd5e1;
}
</style>

<div class="form-container">

    <div class="form-header">
        <h2>BARANGAY COGON</h2>
        <p>Ormoc City, Leyte</p>
        <h3>EDIT WORKER INFORMATION</h3>
    </div>

    @if ($errors->any())
        <div style="background:#fee2e2;border:1px solid #fecaca;color:#991b1b;padding:12px 16px;border-radius:6px;margin-bottom:16px;font-size:13px;">
            <ul style="margin:0;padding-left:16px">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('workers.update', $worker->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="section-title">PERSONAL INFORMATION</div>

        <div class="form-group">
            <label>Worker Photo</label>
            <input type="file" name="photo" accept="image/*">
            @if($worker->photo)
                <small style="margin-top:4px;color:#64748b">Current photo on file. Upload a new one to replace it.</small>
            @endif
        </div>

        <div class="form-grid" style="margin-top:15px">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" value="{{ old('first_name', $worker->first_name) }}" required>
            </div>

            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" value="{{ old('last_name', $worker->last_name) }}" required>
            </div>

            <div class="form-group">
                <label>Middle Name</label>
                <input type="text" name="middle_name" value="{{ old('middle_name', $worker->middle_name) }}">
            </div>

            <div class="form-group">
                <label>Birthdate</label>
                <input type="date" name="birthdate" value="{{ old('birthdate', $worker->birthdate) }}">
            </div>

            <div class="form-group">
                <label>Sex</label>
                <select name="gender">
                    <option value="">Select Sex</option>
                    <option {{ old('gender', $worker->gender) == 'Male'   ? 'selected' : '' }}>Male</option>
                    <option {{ old('gender', $worker->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>

            <div class="form-group">
                <label>Civil Status</label>
                <select name="civil_status">
                    <option value="">Select Status</option>
                    <option {{ old('civil_status', $worker->civil_status) == 'Single'  ? 'selected' : '' }}>Single</option>
                    <option {{ old('civil_status', $worker->civil_status) == 'Married' ? 'selected' : '' }}>Married</option>
                    <option {{ old('civil_status', $worker->civil_status) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                </select>
            </div>
        </div>

        <div class="section-title">CONTACT INFORMATION</div>

        <div class="form-grid">
            <div class="form-group full-width">
                <label>Complete Address</label>
                <input type="text" name="address" value="{{ old('address', $worker->address) }}">
            </div>

            <div class="form-group">
                <label>Contact Number</label>
                <input type="text" name="contact_number" value="{{ old('contact_number', $worker->contact_number) }}">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email', $worker->email) }}">
            </div>
        </div>

        <div class="section-title">BARANGAY DETAILS</div>

        <div class="form-grid">
            <div class="form-group">
                <label>Position</label>
                <select name="position" required>
                    <option value="">Select Position</option>
                    @foreach(['Barangay Captain','Kagawad','Secretary','Treasurer','Tanod','Health Worker','Utility Worker'] as $pos)
                        <option {{ old('position', $worker->position) == $pos ? 'selected' : '' }}>{{ $pos }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Date Hired</label>
                <input type="date" name="date_hired" value="{{ old('date_hired', $worker->date_hired) }}">
            </div>

            <div class="form-group">
                <label>Employment Status</label>
                <select name="employment_status">
                    <option value="">Select Status</option>
                    @foreach(['Regular','Job Order','Volunteer'] as $status)
                        <option {{ old('employment_status', $worker->employment_status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="submit-btn">Update Worker Information</button>
            <a href="{{ route('workers.index') }}" class="cancel-btn">Discard Changes</a>
        </div>

    </form>

</div>

@endsection
