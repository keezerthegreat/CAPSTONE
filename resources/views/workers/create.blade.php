@extends('layouts.app')

@section('content')

<h2 class="text-xl font-semibold mb-6">Add Worker</h2>

<form action="{{ route('workers.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow space-y-4">
    @csrf

    <div>
        <label class="block text-sm">Full Name</label>
        <input type="text" name="full_name" class="w-full border rounded p-2">
    </div>

    <div>
        <label class="block text-sm">Gender</label>
        <select name="gender" class="w-full border rounded p-2">
            <option value="">Select</option>
            <option>Male</option>
            <option>Female</option>
        </select>
    </div>

    <div>
        <label class="block text-sm">Contact Number</label>
        <input type="text" name="contact_number" class="w-full border rounded p-2">
    </div>

    <div>
        <label class="block text-sm">Address</label>
        <input type="text" name="address" class="w-full border rounded p-2">
    </div>

    <div>
        <label class="block text-sm">Position</label>
        <select name="position" class="w-full border rounded p-2">
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
</form>

@endsection