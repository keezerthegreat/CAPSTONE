@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="w-full max-w-md bg-white border border-gray-300 rounded-lg p-8">
        <h2 class="text-2xl font-semibold text-center text-slate-800 mb-6">
            Barangay Management System
        </h2>

        @if ($errors->any())
            <div class="alert-success mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- IMPORTANT: ACTION IS /login --}}
       <form method="POST" action="{{ route('login.submit') }}">

            @csrf

            <div class="form-group">
                <label>Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password"
                       name="password"
                       required>
            </div>

            <button type="submit" class="btn-primary w-full mt-4">
                Login
            </button>
        </form>
    </div>

</div>
@endsection
