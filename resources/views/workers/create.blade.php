@extends('layouts.app')

@section('page-title', 'Add Worker')

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
        <h3>WORKER INFORMATION FORM</h3>
    </div>
<form method="POST" action="{{ route('workers.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="section-title">PERSONAL INFORMATION</div>

<div class="form-group">
<label>Worker Photo</label>
<input type="file" name="photo" accept="image/*">
</div>
        <div class="form-grid">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" required>
            </div>

            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" required>
            </div>

            <div class="form-group">
                <label>Middle Name</label>
                <input type="text" name="middle_name">
            </div>

            <div class="form-group">
                <label>Birthdate</label>
                <input type="date" name="birthdate">
            </div>

            <div class="form-group">
                <label>Sex</label>
                <select name="gender">
                    <option value="">Select Sex</option>
                    <option>Male</option>
                    <option>Female</option>
                </select>
            </div>

            <div class="form-group">
                <label>Civil Status</label>
                <select name="civil_status">
                    <option value="">Select Status</option>
                    <option>Single</option>
                    <option>Married</option>
                    <option>Widowed</option>
                </select>
            </div>
        </div>

        <div class="section-title">CONTACT INFORMATION</div>

        <div class="form-grid">
            <div class="form-group full-width">
                <label>Complete Address</label>
                <input type="text" name="address">
            </div>

            <div class="form-group">
                <label>Contact Number</label>
                <input type="text" name="contact_number">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email">
            </div>
        </div>

        <div class="section-title">BARANGAY DETAILS</div>

        <div class="form-grid">
            <div class="form-group">
                <label>Position</label>
                <select name="position" required>
                    <option value="">Select Position</option>
                    <option>Punong Barangay</option>
                    <option>Sangguniang Barangay Member</option>
                    <option>Secretary</option>
                    <option>Treasurer</option>
                    <option>Clerk 1</option>
                    <option>Clerk 2</option>
                    <option>Clerk 3</option>
                    <option>Lupon Member</option>
                    <option>Tanod</option>
                    <option>Child Development Worker</option>
                    <option>Barangay Nutrition Scholar</option>
                    <option>Driver</option>
                    <option>SK Chairperson</option>
                    <option>SK Member</option>
                    <option>Utility</option>
                </select>
            </div>

            <div class="form-group">
                <label>Date Hired</label>
                <input type="date" name="date_hired">
            </div>

            <div class="form-group">
                <label>Employment Status</label>
                <select name="employment_status">
                    <option value="">Select Status</option>
                    <option>Appointed Official</option>
                    <option>Elected Official</option>
                </select>
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="submit-btn">
                Save Worker Information
            </button>

            <a href="{{ route('workers.index') }}"
               class="cancel-btn"
               onclick="return confirm('Are you sure you want to cancel? Unsaved data will be lost.')">
                Cancel
            </a>
        </div>

    </form>

</div>

@endsection