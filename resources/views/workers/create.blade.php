@extends('layouts.app')

@section('content')

<a href="{{ url()->previous() }}"
   style="
        display: inline-block;
        margin-bottom: 12px;
        padding: 6px 12px;
        background-color: #2c3e50;
        color: #ffffff;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
   ">
    ← Back
</a>

<h2 class="text-xl font-semibold mb-6">Add Worker</h2>

<form action="{{ route('workers.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow space-y-4">
    @csrf

    <div>
        <label class="block text-sm">Full Name</label>
        <input type="text" required name="full_name" class="w-full border rounded p-2">
    </div>

    <div>
        <label class="block text-sm" >Gender</label>
        <select name="gender" required class="w-full border rounded p-2">
            <option value="">Select</option>
            <option>Male</option>
            <option>Female</option>
        </select>
    </div>

    <div>
        <label class="block text-sm">Contact Number</label>
        <input type="text" required name="contact_number" class="w-full border rounded p-2">
    </div>

    <div>
        <label class="block text-sm">Address</label>
        <input type="text" required name="address" class="w-full border rounded p-2">
    </div>

    <div>
        <label class="block text-sm" >Position</label>
        <select name="position" required class="w-full border rounded p-2">
            <option value="">-- Select Position --</option>
            <option>Barangay Captain</option>
            <option>Kagawad</option>
            <option>Secretary</option>
            <option>Treasurer</option>
            <option>Tanod</option>
            <option>Health Worker</option>
            <option>Utility Worker</option>
        </select>
    </div>

    <div>
        <label class="block text-sm">Status</label>
        <select name="status" class="w-full border rounded p-2">
            <option>Active</option>
            <option>Inactive</option>
        </select>
    </div>

    <button class="bg-slate-900 text-white px-4 py-2 rounded">
        Save Worker
    </button>
    
            <a href="{{ route('workers.index') }}"
                class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
               Cancel
            </a>
</form>

<script>
function checkForm() {

    let inputs = document.querySelectorAll('form input, form select');
    let hasValue = false;

    inputs.forEach(function(input) {
        if (input.value.trim() !== '') {
            hasValue = true;
        }
    });

    if (!hasValue) {
        alert("Nothing has changed. Please fill up the form.");
        return false; // stop submit
    }

    return true; // allow submit
}
</script>

@endsection