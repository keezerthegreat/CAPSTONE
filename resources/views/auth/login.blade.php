@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="w-full max-w-md bg-white border border-gray-200 rounded-xl p-8 shadow-sm">
        
        <h2 class="text-2xl font-semibold text-center text-slate-800 mb-6">
            Barangay Management System
        </h2>

        {{-- Error Message --}}
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-400">
            </div>

            {{-- Password --}}
            <div class="mb-4 relative">
                <label class="block text-sm text-gray-600 mb-1">Password</label>

                <input type="password"
                       name="password"
                       id="password"
                       required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-slate-400 pr-10">

                {{-- Eye Icon --}}
                <span onclick="togglePassword()"
                      class="absolute right-3 top-9 cursor-pointer text-gray-500 hover:text-gray-700">
                    👁
                </span>
            </div>

            {{-- OPTIONAL OTP FIELD (hide for now, use if needed) --}}
            {{--
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">OTP Code</label>
                <input type="text"
                       name="otp"
                       placeholder="Enter 6-digit code"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            --}}

            <button type="submit"
                    class="w-full bg-slate-800 text-white py-2 rounded-md hover:bg-slate-700 transition duration-200">
                Login
            </button>
        </form>
    </div>

</div>

{{-- Show / Hide Password Script --}}
<script>
function togglePassword() {
    const password = document.getElementById('password');
    if (password.type === "password") {
        password.type = "text";
    } else {
        password.type = "password";
    }
}
</script>

@endsection