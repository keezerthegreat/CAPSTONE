<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Barangay System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-100 font-sans">

<div class="flex min-h-screen">

    <!-- ================= SIDEBAR ================= -->
    <aside class="w-64 bg-slate-900 text-slate-200 flex flex-col fixed h-full">

        <!-- Logo (Clickable) -->
        <a href="{{ route('dashboard') }}"
           class="px-6 py-6 border-b border-slate-800 block hover:bg-slate-800 transition cursor-pointer">

            <div class="flex items-center gap-3">
                <div class="bg-yellow-400 text-slate-900 font-bold w-9 h-9 flex items-center justify-center rounded-lg">
                    BC
                </div>
                <div>
                    <h2 class="text-sm font-semibold">Barangay Cogon</h2>
                    <p class="text-xs text-slate-400">Information System</p>
                </div>
            </div>

        </a>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2 text-sm">

            <a href="/barangay-update" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition">
                Barangay Update
            </a>

            <a href="{{ route('clearance.index') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition">
                Clearance Form
            </a>

            <a href="/certificate" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition">
                Certificate Form
            </a>

            <a href="/residents/create" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition">
                Resident Information
            </a>

            <a href="/residents" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition">
                View Residents
            </a>

            <a href="{{ route('residents.location') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition">
                Resident Location
            </a>

            <a href="/read-message" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition">
                Read Message
            </a>

            <!-- ✅ FIXED WORKER LINK -->
            <a href="{{ route('workers.index') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-800 hover:text-white transition">
                Worker Information
            </a>

        </nav>

        <!-- Logout -->
        <div class="p-4 border-t border-slate-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full bg-slate-800 hover:bg-red-600 transition rounded-lg py-2 text-sm font-semibold">
                    Log out
                </button>
            </form>
        </div>

    </aside>


    <!-- ================= RIGHT SIDE ================= -->
    <div class="flex-1 ml-64">

        <!-- Top Navbar (Visible ONLY on Dashboard) -->
        @if(Route::currentRouteName() === 'dashboard')
        <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8">
            <h1 class="text-lg font-semibold text-slate-700">
                @yield('page-title', 'Dashboard')
            </h1>

            <div class="text-sm text-slate-600">
                Welcome, <span class="font-semibold">{{ auth()->user()->name }}</span>
            </div>
        </header>
        @endif


        <!-- Main Content -->
        <main class="p-8">
            @yield('content')
        </main>

    </div>

</div>

</body>
</html>