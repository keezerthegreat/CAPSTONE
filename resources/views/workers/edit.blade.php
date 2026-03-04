@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">

    <h2 class="text-2xl font-bold mb-6">Edit Worker</h2>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('workers.update', $worker->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Full Name -->
        <div class="mb-4">
            <label class="block text-sm">Full Name</label>
            <input type="text" name="full_name"
                   value="{{ old('full_name', $worker->full_name) }}"
                   class="w-full border rounded p-2" required>
        </div>

        <!-- Gender -->
        <div class="mb-4">
            <label class="block text-sm">Gender</label>
            <select name="gender" class="w-full border rounded p-2">
                <option value="" disabled {{ old('gender', $worker->gender) ? '' : 'selected' }}>Select</option>
                <option value="Male" {{ old('gender', $worker->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender', $worker->gender) == 'Female' ? 'selected' : '' }}>Female</option>
            </select>
        </div>

        <!-- Contact Number -->
        <div class="mb-4">
            <label class="block text-sm">Contact Number</label>
            <input type="text" name="contact_number"
                   value="{{ old('contact_number', $worker->contact_number) }}"
                   class="w-full border rounded p-2">
        </div>

        <!-- Address -->
        <div class="mb-4">
            <label class="block text-sm">Address</label>
            <input type="text" name="address"
                   value="{{ old('address', $worker->address) }}"
                   class="w-full border rounded p-2">
        </div>

        <!-- Position -->
        <div class="mb-4">
            <label class="block text-sm">Position</label>
            <select name="position" class="w-full border rounded p-2" required>
                <option value="" disabled {{ old('position', $worker->position) ? '' : 'selected' }}>
                    -- Select Position --
                </option>

                <option value="Barangay Captain" {{ old('position', $worker->position) == 'Barangay Captain' ? 'selected' : '' }}>Barangay Captain</option>
                <option value="Kagawad" {{ old('position', $worker->position) == 'Kagawad' ? 'selected' : '' }}>Kagawad</option>
                <option value="Secretary" {{ old('position', $worker->position) == 'Secretary' ? 'selected' : '' }}>Secretary</option>
                <option value="Treasurer" {{ old('position', $worker->position) == 'Treasurer' ? 'selected' : '' }}>Treasurer</option>
                <option value="Tanod" {{ old('position', $worker->position) == 'Tanod' ? 'selected' : '' }}>Tanod</option>
                <option value="Health Worker" {{ old('position', $worker->position) == 'Health Worker' ? 'selected' : '' }}>Health Worker</option>
                <option value="Utility Worker" {{ old('position', $worker->position) == 'Utility Worker' ? 'selected' : '' }}>Utility Worker</option>
            </select>
        </div>

        <!-- Status -->
        <div class="mb-4">
            <label class="block text-sm">Status</label>
            <select name="status" class="w-full border rounded p-2" required>
                <option value="Active" {{ old('status', $worker->status) == 'Active' ? 'selected' : '' }}>Active</option>
                <option value="Inactive" {{ old('status', $worker->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex gap-4 mt-6">
            <button type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700">
                Update Worker
            </button>

            <a href="{{ route('workers.index') }}"
               class="bg-gray-400 text-white px-5 py-2 rounded hover:bg-gray-500">
               Discard Changes
            </a>
        </div>

    </form>
</div>
@endsection