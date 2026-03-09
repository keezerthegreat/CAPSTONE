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
      --bg: #f0f4f8;
      --card: #ffffff;
      --text: #1e293b;
      --muted: #64748b;
      --border: #e2e8f0;
      --primary: #1a3a6b;
      --primary-light: #2554a0;
      --topbar-bg: #ffffff;
      --topbar-border: #e2e8f0;
      --topbar-text: #1a3a6b;
      --header-bg: #f8fafc;
      --input-bg: #ffffff;
      --hover-bg: #f8fafc;
    }
    /* Facebook-style dark mode */
    [data-theme="dark"] {
      --bg: #1c1e21;
      --card: #242526;
      --text: #e4e6eb;
      --muted: #b0b3b8;
      --border: #3e4042;
      --primary: #e4e6eb;
      --primary-light: #e4e6eb;
      --topbar-bg: #242526;
      --topbar-border: #3e4042;
      --topbar-text: #e4e6eb;
      --header-bg: #2d2f31;
      --input-bg: #3a3b3c;
      --hover-bg: #2d2f31;
    }
    /* Global dark mode overrides */
    [data-theme="dark"] body { background: var(--bg); color: var(--text); }
    [data-theme="dark"] .bidb-wrap,
    [data-theme="dark"] .dash-wrap { background: var(--bg) !important; }
    [data-theme="dark"] .card,
    [data-theme="dark"] .s-card { background: var(--card) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .card-header,
    [data-theme="dark"] .s-card-header { background: var(--header-bg) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .card-title,
    [data-theme="dark"] .page-hdr h1,
    [data-theme="dark"] td,
    [data-theme="dark"] .breadcrumb,
    [data-theme="dark"] label { color: var(--text) !important; }
    [data-theme="dark"] th { color: var(--muted) !important; }
    [data-theme="dark"] input,
    [data-theme="dark"] select,
    [data-theme="dark"] textarea {
      background: var(--input-bg) !important;
      color: var(--text) !important;
      border-color: var(--border) !important;
    }
    [data-theme="dark"] input::placeholder,
    [data-theme="dark"] textarea::placeholder { color: var(--muted) !important; }
    [data-theme="dark"] tbody tr { background: var(--card) !important; border-color: var(--border) !important; }
    [data-theme="dark"] tbody tr:hover { background: var(--hover-bg) !important; }
    [data-theme="dark"] tbody td { border-color: var(--border) !important; }
    [data-theme="dark"] thead tr { background: var(--header-bg) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .stat-card,
    [data-theme="dark"] .res-stat,
    [data-theme="dark"] .fam-stat,
    [data-theme="dark"] .expand-item { background: var(--card) !important; border-color: var(--border) !important; color: var(--text) !important; }
    [data-theme="dark"] .sc-expand { background: var(--header-bg) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .filter-select,
    [data-theme="dark"] .search-wrap input { background: var(--input-bg) !important; color: var(--text) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .slabel,
    [data-theme="dark"] .svalue,
    [data-theme="dark"] .sc-label { color: var(--text) !important; }
    [data-theme="dark"] .svalue,
    [data-theme="dark"] .sc-value { color: var(--primary) !important; }
    /* Workers Tailwind overrides */
    [data-theme="dark"] table.w-full { background: var(--card) !important; color: var(--text) !important; }
    [data-theme="dark"] thead.bg-slate-200 { background: var(--header-bg) !important; color: var(--muted) !important; }
    [data-theme="dark"] tr.border-t { border-color: var(--border) !important; color: var(--text) !important; }
    [data-theme="dark"] tr.border-t td { color: var(--text) !important; }
    [data-theme="dark"] tr.border-t:hover { background: var(--hover-bg) !important; }
    [data-theme="dark"] div.bg-white { background: var(--card) !important; color: var(--text) !important; }

    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', sans-serif; background: var(--bg); transition: background .3s, color .3s; }

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

@if(auth()->user()?->isAdmin())
      <a href="{{ route('audit.index') }}"
         class="nav-item {{ request()->is('audit-log*') ? 'active' : '' }}">
        <i class="fas fa-history"></i> Audit Log
      </a>
      @endif

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

<!-- ── GLOBAL DELETE CONFIRMATION MODAL ── -->
<div id="delete-modal" style="
  display:none; position:fixed; inset:0; z-index:9999;
  align-items:center; justify-content:center;
">
  <!-- Backdrop -->
  <div id="delete-backdrop" style="
    position:absolute; inset:0;
    background:rgba(0,0,0,0.45);
    backdrop-filter:blur(2px);
  "></div>

  <!-- Modal Box -->
  <div style="
    position:relative; z-index:1;
    background:var(--card,#fff);
    border:1px solid var(--border,#e2e8f0);
    border-radius:16px;
    padding:28px 32px;
    width:100%; max-width:380px;
    box-shadow:0 20px 60px rgba(0,0,0,0.2);
    text-align:center;
    animation: modalIn .18s ease;
  ">
    <!-- Icon -->
    <div style="
      width:56px; height:56px; border-radius:50%;
      background:#fff1f2; border:2px solid #fecdd3;
      display:flex; align-items:center; justify-content:center;
      margin:0 auto 16px; font-size:22px;
    ">🗑️</div>

    <h3 style="font-size:16px; font-weight:700; color:var(--text,#1e293b); margin:0 0 8px;">Delete this record?</h3>
    <p style="font-size:13px; color:var(--muted,#64748b); margin:0 0 24px; line-height:1.5;">
      This action <strong>cannot be undone</strong>. The record will be permanently removed.
    </p>

    <div style="display:flex; gap:10px; justify-content:center;">
      <button id="delete-cancel" style="
        flex:1; padding:10px; border-radius:9px;
        border:1.5px solid var(--border,#e2e8f0);
        background:var(--card,#fff); color:var(--text,#1e293b);
        font-size:13px; font-weight:600; cursor:pointer;
        font-family:inherit; transition:background .15s;
      ">Cancel</button>

      <button id="delete-confirm" style="
        flex:1; padding:10px; border-radius:9px;
        border:none; background:#dc2626; color:#fff;
        font-size:13px; font-weight:600; cursor:pointer;
        font-family:inherit; transition:opacity .15s;
      ">Yes, Delete</button>
    </div>
  </div>
</div>

<style>
@keyframes modalIn {
  from { opacity:0; transform:scale(.92) translateY(10px); }
  to   { opacity:1; transform:scale(1) translateY(0); }
}
[data-theme="dark"] #delete-modal > div:last-child { /* modal box already uses var(--card) */ }
</style>

<script>
  // Apply saved theme immediately on load (before paint)
  (function() {
    const theme = localStorage.getItem('theme') || '{{ session("theme", "light") }}';
    document.documentElement.setAttribute('data-theme', theme);
  })();

  // Live date in topbar
  const d = new Date();
  document.getElementById('topbar-date').textContent = d.toLocaleDateString('en-PH', {weekday:'short', year:'numeric', month:'short', day:'numeric'});

  // ── DELETE MODAL LOGIC ──
  let pendingDeleteForm = null;

  // Intercept ALL delete forms on the page
  document.addEventListener('submit', function(e) {
    const form = e.target;
    // Only intercept forms that contain a DELETE method spoofing input
    const method = form.querySelector('input[name="_method"]');
    if (method && method.value === 'DELETE') {
      e.preventDefault();
      pendingDeleteForm = form;
      const modal = document.getElementById('delete-modal');
      modal.style.display = 'flex';
    }
  });

  // Confirm delete
  document.getElementById('delete-confirm').addEventListener('click', function() {
    if (pendingDeleteForm) {
      document.getElementById('delete-modal').style.display = 'none';
      pendingDeleteForm.submit();
      pendingDeleteForm = null;
    }
  });

  // Cancel
  function closeDeleteModal() {
    document.getElementById('delete-modal').style.display = 'none';
    pendingDeleteForm = null;
  }
  document.getElementById('delete-cancel').addEventListener('click', closeDeleteModal);
  document.getElementById('delete-backdrop').addEventListener('click', closeDeleteModal);

  // Close on Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteModal();
  });
</script>

</body>
</html>