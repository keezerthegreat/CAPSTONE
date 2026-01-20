<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Barangay System</title>

    <!-- USE VITE (Tailwind / app.css) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <div class="w-64 bg-slate-800 text-white p-5 flex flex-col">
        <h2 class="text-xl font-bold mb-6">BARANGAY COGON</h2>

        <a href="/barangay-update" class="mb-3 hover:text-blue-400">Barangay Update</a>
        <a href="{{ route('clearance.index') }}" class="mb-3 hover:text-blue-400">Clearance Form</a>
        <a href="/certificate" class="mb-3 hover:text-blue-400">Certificate Form</a>
        <a href="/residents/create" class="mb-3 hover:text-blue-400">Resident Information</a>
        <a href="/residents" class="mb-3 hover:text-blue-400">View Residents</a>
        <a href="{{ route('residents.location') }}" class="mb-3 hover:text-blue-400">Resident Location</a>
        <a href="/read-message" class="mb-3 hover:text-blue-400">Read Message</a>
        <a href="/worker-info" class="mb-6 hover:text-blue-400">Worker Information</a>

        <!-- LOGOUT -->
        <form method="POST" action="{{ route('logout') }}" class="mt-auto">
            @csrf
            <button type="submit" class="logout-btn">
    Log-out
</button>

        </form>
    </div>

    <!-- MAIN CONTENT -->
    <div class="flex-1 p-8">
        @yield('content')
    </div>

</div>

</body>
</html>
