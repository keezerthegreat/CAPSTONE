<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Barangay Cogon BIDB</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    /* ── THEME VARIABLES ── */
    :root {
      --body-bg: #f0f4f8;
      --card-bg: #ffffff;
      --header-bg: #f8fafc;
      --input-bg: #ffffff;
      --text-color: #1e293b;
      --muted-color: #64748b;
      --border-color: #e2e8f0;
      --hover-bg: #f8fafc;
      --topbar-bg: #ffffff;
      --topbar-border: #e2e8f0;
      --topbar-text: #1a3a6b;
      /* Aliases used by individual page styles */
      --bg: #f0f4f8;
      --card: #ffffff;
      --text: #1e293b;
      --muted: #64748b;
      --border: #e2e8f0;
    }
    [data-theme="dark"] {
      --body-bg: #0d1527;
      --card-bg: #1e293b;
      --header-bg: #162032;
      --input-bg: #0f1e3d;
      --text-color: #e2e8f0;
      --muted-color: #94a3b8;
      --border-color: #334155;
      --hover-bg: #162032;
      --topbar-bg: #1e293b;
      --topbar-border: #334155;
      --topbar-text: #e2e8f0;
      /* Aliases used by individual page styles */
      --bg: #0d1527;
      --card: #1e293b;
      --text: #e2e8f0;
      --muted: #94a3b8;
      --border: #334155;
    }
    /* Global dark mode overrides for hardcoded colors in pages */
    [data-theme="dark"] .card-header,
    [data-theme="dark"] .s-card-header { background: #162032 !important; }
    [data-theme="dark"] input,
    [data-theme="dark"] select,
    [data-theme="dark"] textarea {
      background: #0f1e3d !important;
      color: #e2e8f0 !important;
      border-color: #334155 !important;
    }
    [data-theme="dark"] .expand-item,
    [data-theme="dark"] .sc-expand { background: #162032 !important; border-color: #334155 !important; }
    [data-theme="dark"] tbody tr:hover { background: #162032 !important; }
    [data-theme="dark"] thead tr { background: #162032 !important; }
    [data-theme="dark"] .filter-select { background: #0f1e3d !important; color: #e2e8f0 !important; border-color: #334155 !important; }
    [data-theme="dark"] .search-wrap input { background: #0f1e3d !important; color: #e2e8f0 !important; }
    [data-theme="dark"] .stat-card,
    [data-theme="dark"] .res-stat,
    [data-theme="dark"] .fam-stat { background: #1e293b !important; border-color: #334155 !important; }
    [data-theme="dark"] .dash-wrap { background: var(--bg) !important; }
    /* Workers page (Tailwind-based) dark overrides */
    [data-theme="dark"] table.w-full { background: #1e293b !important; color: #e2e8f0 !important; }
    [data-theme="dark"] thead.bg-slate-200 { background: #162032 !important; color: #94a3b8 !important; }
    [data-theme="dark"] tr.border-t { border-color: #334155 !important; }
    [data-theme="dark"] tr.border-t:hover { background: #162032 !important; }
    [data-theme="dark"] div.bg-white { background: #1e293b !important; color: #e2e8f0 !important; }
    [data-theme="dark"] .hover\:bg-slate-50:hover { background: #162032 !important; }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', sans-serif; background: var(--body-bg); transition: background .3s, color .3s; }

    /* ── SIDEBAR ── */
    .sidebar {
      position: fixed; top: 0; left: 0;
      width: 260px; height: 100vh;
      background: #0f1e3d;
      display: flex; flex-direction: column;
      z-index: 100; overflow-y: auto;
    }

    .sidebar-brand {
      padding: 18px 16px;
      border-bottom: 1px solid rgba(255,255,255,.08);
      display: flex; align-items: center; gap: 10px;
      text-decoration: none;
    }
    .brand-icon {
      width: 38px; height: 38px; border-radius: 10px;
      background: #f0a500;
      display: flex; align-items: center; justify-content: center;
      font-weight: 800; font-size: 13px; color: #0f1e3d;
      flex-shrink: 0;
    }
    .brand-text .name { font-size: 13px; font-weight: 700; color: #fff; }
    .brand-text .sub  { font-size: 11px; color: rgba(255,255,255,.45); margin-top: 1px; }

    .sidebar-nav { flex: 1; padding: 16px 10px; }

    .nav-section {
      font-size: 10px; font-weight: 700; letter-spacing: .1em;
      text-transform: uppercase; color: rgba(255,255,255,.3);
      padding: 0 8px; margin: 18px 0 6px;
    }
    .nav-section:first-child { margin-top: 0; }

    .nav-item {
      display: flex; align-items: center; gap: 10px;
      padding: 9px 12px; border-radius: 8px;
      font-size: 13px; font-weight: 500; color: rgba(255,255,255,.65);
      text-decoration: none; transition: all .15s; margin-bottom: 2px;
    }
    .nav-item:hover { background: rgba(255,255,255,.08); color: #fff; }
    .nav-item.active { background: #1a3a6b; color: #fff; font-weight: 600; }
    .nav-item i { width: 16px; text-align: center; font-size: 13px; opacity: .8; }
    .nav-item.active i { opacity: 1; }

    .sidebar-footer {
      padding: 12px 10px;
      border-top: 1px solid rgba(255,255,255,.08);
    }
    .sidebar-footer .user-info {
      padding: 8px 12px; margin-bottom: 6px;
      font-size: 12px; color: rgba(255,255,255,.5);
    }
    .sidebar-footer .user-info strong { display: block; color: rgba(255,255,255,.85); font-size: 13px; }
    .btn-logout {
      display: flex; align-items: center; gap: 8px;
      width: 100%; padding: 9px 12px; border-radius: 8px;
      background: transparent; border: none; cursor: pointer;
      font-family: inherit; font-size: 13px; font-weight: 500;
      color: rgba(255,255,255,.5); transition: all .15s;
    }
    .btn-logout:hover { background: rgba(220,38,38,.2); color: #fca5a5; }

    /* ── MAIN CONTENT ── */
    .main-content { margin-left: 260px; min-height: 100vh; }

    /* ── TOP BAR ── */
    .topbar {
      height: 56px; background: var(--topbar-bg);
      border-bottom: 1px solid var(--topbar-border);
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 24px; position: sticky; top: 0; z-index: 50;
      transition: background .3s, border-color .3s;
    }
    .topbar-title { font-size: 15px; font-weight: 700; color: var(--topbar-text); }
    .topbar-right { display: flex; align-items: center; gap: 12px; font-size: 13px; color: #64748b; }
    .topbar-date { font-size: 12px; color: #94a3b8; }
    .topbar-user {
      width: 32px; height: 32px; border-radius: 50%;
      background: #1a3a6b; color: #fff;
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; font-size: 13px;
    }

    .page-content { padding: 0; }
  </style>
</head>
<body>

<div style="display:flex">

  <!-- SIDEBAR -->
  <aside class="sidebar">

    <!-- Brand -->
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
      <div class="brand-icon">BC</div>
      <div class="brand-text">
        <div class="name">Barangay Cogon BIDB</div>
        <div class="sub">Ormoc City, Leyte</div>
      </div>
    </a>

    <!-- Navigation -->
    <nav class="sidebar-nav">

      <div class="nav-section">Main</div>

      <a href="{{ route('dashboard') }}"
         class="nav-item {{ Route::currentRouteName() === 'dashboard' ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i> Dashboard
      </a>

      <a href="{{ route('households.index') }}"
          class="nav-item {{ request()->is('households*') ? 'active' : '' }}">
          <i class="fas fa-home"></i> Households
      </a>

      <a href="{{ route('families.index') }}" class="nav-link {{ request()->routeIs('families.*') ? 'active' : '' }}">
        <i class="fas fa-people-roof"></i>
        <span>Families</span>
      </a>

      <a href="{{ route('residents.index') }}"
         class="nav-item {{ request()->is('residents*') ? 'active' : '' }}">
        <i class="fas fa-users"></i> Residents
      </a>

      <div class="nav-section">Documents</div>

      <a href="{{ route('clearance.index') }}"
         class="nav-item {{ request()->is('clearance*') ? 'active' : '' }}">
        <i class="fas fa-file-alt"></i> Clearance Forms
      </a>

      <a href="{{ route('certificate.index') }}"
         class="nav-item {{ request()->is('certificate*') ? 'active' : '' }}">
        <i class="fas fa-certificate"></i> Certificates
      </a>

      <div class="nav-section">Reports</div>

      <a href="{{ route('reports.index') }}"
         class="nav-item {{ request()->is('reports*') ? 'active' : '' }}">
        <i class="fas fa-chart-bar"></i> Reports
      </a>

      <div class="nav-section">Administration</div>

      <a href="{{ route('workers.index') }}"
         class="nav-item {{ request()->is('workers*') ? 'active' : '' }}">
        <i class="fas fa-user-tie"></i> Worker Information
      </a>

      <a href="/barangay-update"
         class="nav-item {{ request()->is('barangay-update*') ? 'active' : '' }}">
        <i class="fas fa-bullhorn"></i> Barangay Update
      </a>

      <a href="/read-message"
         class="nav-item {{ request()->is('read-message*') ? 'active' : '' }}">
        <i class="fas fa-envelope"></i> Read Message
      </a>

      @if(auth()->user()->role === 'admin')
      <a href="{{ route('settings.index') }}"
         class="nav-item {{ request()->is('settings*') ? 'active' : '' }}">
        <i class="fas fa-cog"></i> Settings
      </a>
      @endif

    </nav>

    <!-- Footer / Logout -->
    <div class="sidebar-footer">
      <div class="user-info">
        <strong>{{ auth()->user()->name ?? 'Admin' }}</strong>
        Barangay Staff
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout">
          <i class="fas fa-sign-out-alt"></i> Log out
        </button>
      </form>
    </div>

  </aside>

  <!-- MAIN CONTENT -->
  <div class="main-content" style="flex:1">

    <!-- Top Bar -->
    <div class="topbar">
      <div class="topbar-title">@yield('page-title', 'Barangay Cogon Information System')</div>
      <div class="topbar-right">
        <span class="topbar-date" id="topbar-date"></span>
        <span>{{ auth()->user()->name ?? 'Admin' }}</span>
        <div class="topbar-user">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
      </div>
    </div>

    <!-- Page Content -->
    <div class="page-content">
      @yield('content')
    </div>

  </div>

</div>

<script>
  // Apply saved theme on load
  const savedTheme = localStorage.getItem('theme') || '{{ session("theme", "light") }}';
  document.documentElement.setAttribute('data-theme', savedTheme);

  // Live date in topbar
  const d = new Date();
  document.getElementById('topbar-date').textContent = d.toLocaleDateString('en-PH', {weekday:'short', year:'numeric', month:'short', day:'numeric'});
</script>

</body>
</html>