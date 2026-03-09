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
    /* ── DARK MODE — deep navy-blue palette ── */
    [data-theme="dark"] {
      --bg:            #0d1117;   /* deep ink background */
      --card:          #161b27;   /* card surface — slightly lifted */
      --text:          #d1d9e6;   /* soft cool white — easy on eyes */
      --muted:         #6b7a99;   /* blue-grey muted text */
      --border:        #1e2740;   /* subtle blue border */
      --primary:       #5b8dee;   /* bright accessible blue — keeps branding */
      --primary-light: #7ba5f5;   /* hover blue */
      --topbar-bg:     #111827;   /* topbar — slightly warmer than bg */
      --topbar-border: #1a2438;
      --topbar-text:   #d1d9e6;
      --header-bg:     #111827;   /* table header */
      --input-bg:      #1a2236;   /* input fields */
      --hover-bg:      #1a2236;   /* row hover */
    }
    /* Global dark mode overrides */
    [data-theme="dark"] body { background: var(--bg); color: var(--text); }
    [data-theme="dark"] .bidb-wrap,
    [data-theme="dark"] .dash-wrap,
    [data-theme="dark"] .wrap { background: var(--bg) !important; }
    [data-theme="dark"] .card,
    [data-theme="dark"] .s-card { background: var(--card) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .card-header,
    [data-theme="dark"] .s-card-header { background: var(--header-bg) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .card-title,
    [data-theme="dark"] .page-hdr h1 { color: var(--primary) !important; }
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
    [data-theme="dark"] input:focus,
    [data-theme="dark"] select:focus,
    [data-theme="dark"] textarea:focus { border-color: var(--primary) !important; box-shadow: 0 0 0 3px rgba(91,141,238,.15) !important; }
    [data-theme="dark"] input::placeholder,
    [data-theme="dark"] textarea::placeholder { color: var(--muted) !important; }
    [data-theme="dark"] tbody tr { background: var(--card) !important; border-color: var(--border) !important; }
    [data-theme="dark"] tbody tr:hover { background: var(--hover-bg) !important; }
    [data-theme="dark"] tbody td { border-color: var(--border) !important; }
    [data-theme="dark"] thead tr { background: var(--header-bg) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .stat-card,
    [data-theme="dark"] .res-stat,
    [data-theme="dark"] .fam-stat,
    [data-theme="dark"] .expand-item { background: var(--card) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .sc-expand { background: var(--header-bg) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .filter-select,
    [data-theme="dark"] .search-wrap input { background: var(--input-bg) !important; color: var(--text) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .slabel,
    [data-theme="dark"] .sc-label { color: var(--muted) !important; }
    [data-theme="dark"] .svalue,
    [data-theme="dark"] .sc-value { color: var(--primary) !important; }
    /* Breadcrumb link */
    [data-theme="dark"] .breadcrumb span,
    [data-theme="dark"] .breadcrumb a { color: var(--primary) !important; }
    /* Buttons */
    [data-theme="dark"] .btn-primary { background: var(--primary) !important; color: #fff !important; }
    [data-theme="dark"] .btn-primary:hover { background: var(--primary-light) !important; }
    [data-theme="dark"] .btn-outline { background: transparent !important; color: var(--primary) !important; border-color: var(--primary) !important; }
    [data-theme="dark"] .btn-view { background: rgba(91,141,238,.12) !important; color: var(--primary) !important; border-color: rgba(91,141,238,.3) !important; }
    [data-theme="dark"] .btn-edit { background: rgba(22,163,74,.1) !important; color: #4ade80 !important; border-color: rgba(22,163,74,.25) !important; }
    [data-theme="dark"] .btn-delete { background: rgba(220,38,38,.1) !important; color: #f87171 !important; border-color: rgba(220,38,38,.25) !important; }
    /* Nav item active in dark mode */
    [data-theme="dark"] .nav-item.active { background: rgba(91,141,238,.2) !important; color: var(--primary) !important; }
    /* Modal */
    [data-theme="dark"] .modal-section-title { color: var(--muted) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .modal-close { color: var(--muted) !important; }
    [data-theme="dark"] .modal-close:hover { color: var(--text) !important; }
    /* Filter row background */
    [data-theme="dark"] .filter-row,
    [data-theme="dark"] .filter-area { background: var(--card) !important; }
    /* Settings page variable aliases */
    [data-theme="dark"] .settings-wrap { background: var(--bg); }
    [data-theme="dark"] .s-card { background: var(--card) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .s-card-header { background: var(--header-bg) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .s-card-header i { color: var(--primary) !important; }
    [data-theme="dark"] .s-card-title { color: var(--text) !important; }
    [data-theme="dark"] .form-label { color: var(--muted) !important; }
    [data-theme="dark"] .form-input { background: var(--input-bg) !important; border-color: var(--border) !important; color: var(--text) !important; }
    [data-theme="dark"] .form-input:focus { border-color: var(--primary) !important; box-shadow: 0 0 0 3px rgba(91,141,238,.15) !important; }
    [data-theme="dark"] .form-input::placeholder { color: var(--muted) !important; }
    [data-theme="dark"] .theme-card { background: var(--card) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .theme-card.active { border-color: var(--primary) !important; background: rgba(91,141,238,.08) !important; }
    [data-theme="dark"] .theme-name { color: var(--text) !important; }
    [data-theme="dark"] .badge-admin { background: rgba(91,141,238,.15) !important; color: var(--primary) !important; }
    [data-theme="dark"] .badge-employee { background: rgba(74,222,128,.1) !important; color: #4ade80 !important; }
    [data-theme="dark"] .btn-danger { background: rgba(220,38,38,.12) !important; color: #f87171 !important; border-color: rgba(220,38,38,.25) !important; }
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

    /* ── PAGE LOADER BAR ── */
    .page-loader { position:fixed; top:0; left:0; right:0; height:3px; z-index:9999; pointer-events:none; opacity:0; transition:opacity .2s; }
    .page-loader.visible { opacity:1; }
    .page-loader .bar { height:100%; width:0%; background:linear-gradient(90deg,#1a3a6b 0%,#4f80d0 50%,#1a3a6b 100%); background-size:200% 100%; animation:ldrShimmer 1.5s linear infinite; border-radius:0 3px 3px 0; transition:width .4s ease; }
    @keyframes ldrShimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

    /* ── TOAST NOTIFICATIONS ── */
    .toast-container { position:fixed; bottom:24px; right:24px; z-index:9998; display:flex; flex-direction:column-reverse; gap:8px; pointer-events:none; }
    .toast { background:var(--card); border-radius:12px; padding:13px 16px; box-shadow:0 8px 32px rgba(0,0,0,.18); border:1px solid var(--border); display:flex; align-items:center; gap:11px; font-size:13px; font-weight:500; color:var(--text); pointer-events:all; min-width:270px; max-width:360px; transform:translateX(calc(100% + 28px)); transition:transform .35s cubic-bezier(.34,1.46,.64,1); position:relative; overflow:hidden; }
    .toast.show { transform:translateX(0); }
    .toast::before { content:''; position:absolute; left:0; top:0; bottom:0; width:4px; }
    .toast-success::before { background:#16a34a; }
    .toast-error::before   { background:#dc2626; }
    .toast-info::before    { background:#1a3a6b; }
    .toast-icon { font-size:15px; flex-shrink:0; }
    .toast-success .toast-icon { color:#16a34a; }
    .toast-error   .toast-icon { color:#dc2626; }
    .toast-info    .toast-icon { color:#1a3a6b; }
    .toast-body { flex:1; line-height:1.4; }
    .toast-close { background:none; border:none; cursor:pointer; color:var(--muted); font-size:17px; line-height:1; padding:2px 3px; margin-left:2px; flex-shrink:0; border-radius:4px; }
    .toast-close:hover { background:rgba(0,0,0,.07); }
    .toast-prog { position:absolute; bottom:0; left:0; height:2px; background:currentColor; opacity:.18; animation:toastProg 4.5s linear forwards; }
    @keyframes toastProg { from{width:100%} to{width:0%} }

    /* ── DELETE CONFIRM MODAL ── */
    .confirm-backdrop { display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:2000; align-items:center; justify-content:center; backdrop-filter:blur(3px); }
    .confirm-backdrop.open { display:flex; }
    .confirm-box { background:var(--card); border-radius:20px; padding:36px 32px 28px; width:400px; max-width:92vw; box-shadow:0 32px 80px rgba(0,0,0,.25); text-align:center; transform:scale(.88) translateY(20px); opacity:0; transition:transform .28s cubic-bezier(.34,1.46,.64,1),opacity .2s ease; border:1px solid var(--border); }
    .confirm-backdrop.open .confirm-box { transform:scale(1) translateY(0); opacity:1; }
    .confirm-icon { width:62px; height:62px; border-radius:50%; background:#fff1f2; display:flex; align-items:center; justify-content:center; margin:0 auto 18px; font-size:24px; color:#dc2626; border:2px solid #fecdd3; }
    .confirm-title { font-size:17px; font-weight:700; color:var(--text); margin-bottom:8px; }
    .confirm-msg { font-size:13px; color:var(--muted); margin-bottom:28px; line-height:1.6; }
    .confirm-actions { display:flex; gap:10px; justify-content:center; }
    .confirm-cancel { background:var(--header-bg); color:var(--muted); border:1.5px solid var(--border); padding:10px 22px; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer; font-family:inherit; transition:all .15s; }
    .confirm-cancel:hover { border-color:var(--muted); }
    .confirm-ok { background:#dc2626; color:#fff; border:none; padding:10px 22px; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer; font-family:inherit; transition:background .15s; display:inline-flex; align-items:center; gap:6px; }
    .confirm-ok:hover { background:#b91c1c; }

    /* ── GLOBAL FILTER PILL BUTTONS (shared across all index pages) ── */
    .flt-wrap { position:relative; }
    .flt-btn { background:var(--card); color:var(--text); border:1.5px solid var(--border); padding:6px 12px; border-radius:20px; font-size:12px; font-weight:500; cursor:pointer; font-family:inherit; display:inline-flex; align-items:center; gap:5px; white-space:nowrap; transition:border-color .15s,color .15s; }
    .flt-btn:hover { border-color:var(--primary); color:var(--primary); }
    .flt-btn.active { background:var(--primary); color:#fff; border-color:var(--primary); font-weight:600; }
    .flt-btn .flt-caret { font-size:8px; opacity:.55; }
    .flt-btn .flt-x { font-size:14px; line-height:1; margin-left:1px; opacity:.75; }
    .flt-btn .flt-x:hover { opacity:1; }
    .flt-dropdown { display:none; position:fixed; top:0; left:0; background:var(--card); border:1.5px solid var(--border); border-radius:10px; padding:5px; z-index:9000; box-shadow:0 8px 28px rgba(0,0,0,.13); min-width:160px; }
    .flt-dropdown.open { display:block; }
    .flt-option { padding:7px 10px; border-radius:6px; font-size:12px; font-weight:500; cursor:pointer; color:var(--text); transition:background .1s; }
    .flt-option:hover { background:#f0f7ff; color:var(--primary); }
    .flt-option.selected { background:#eff6ff; color:var(--primary); font-weight:600; }

    /* ── DARK MODE — new components ── */
    [data-theme="dark"] .flt-btn { background:var(--input-bg) !important; color:var(--text) !important; border-color:var(--border) !important; }
    [data-theme="dark"] .flt-btn:hover { border-color:var(--primary) !important; color:var(--primary) !important; }
    [data-theme="dark"] .flt-btn.active { background:var(--primary) !important; color:#fff !important; border-color:var(--primary) !important; }
    [data-theme="dark"] .flt-dropdown,
    [data-theme="dark"] .age-popup { background:#1a2236 !important; border-color:var(--border) !important; box-shadow:0 12px 40px rgba(0,0,0,.6) !important; }
    [data-theme="dark"] .flt-option { color:var(--text) !important; }
    [data-theme="dark"] .flt-option:hover { background:rgba(91,141,238,.12) !important; color:var(--primary) !important; }
    [data-theme="dark"] .flt-option.selected { background:rgba(91,141,238,.18) !important; color:var(--primary) !important; }
    /* thead tag (not just thead tr) */
    [data-theme="dark"] thead { background: var(--header-bg) !important; }
    /* Hover on any tr:hover td */
    [data-theme="dark"] tr:hover td { background: var(--hover-bg) !important; }
    /* Audit log: filter inputs + reset button */
    [data-theme="dark"] .btn-reset { background: var(--input-bg) !important; color: var(--muted) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .btn-reset:hover { background: var(--hover-bg) !important; }
    [data-theme="dark"] .btn-filter { background: var(--primary) !important; }
    /* Pagination */
    [data-theme="dark"] .pagination-links a,
    [data-theme="dark"] .pagination-links span { background: var(--card) !important; border-color: var(--border) !important; color: var(--primary) !important; }
    [data-theme="dark"] .pagination-links a:hover { background: var(--hover-bg) !important; }
    [data-theme="dark"] .pagination-links span.current { background: var(--primary) !important; color: #fff !important; border-color: var(--primary) !important; }
    /* Workers: modal info value fields */
    [data-theme="dark"] .ivalue { background: var(--input-bg) !important; border-color: var(--border) !important; color: var(--text) !important; }
    /* Location map: fullscreen button */
    [data-theme="dark"] .fullscreen-btn { background: var(--card) !important; color: var(--primary) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .fullscreen-btn:hover { background: var(--hover-bg) !important; }
    /* Table headers via class */
    [data-theme="dark"] .recent-table th,
    [data-theme="dark"] .list-table th { background: var(--header-bg) !important; color: var(--muted) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .recent-table td,
    [data-theme="dark"] .list-table td { color: var(--text) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .recent-table tbody tr:hover,
    [data-theme="dark"] .list-table tbody tr:hover { background: var(--hover-bg) !important; }
    /* Reports/Dashboard: Tabs */
    [data-theme="dark"] .tabs { background: var(--header-bg) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .tab { color: var(--muted) !important; border-color: transparent !important; }
    [data-theme="dark"] .tab.active { background: var(--card) !important; color: var(--primary) !important; border-color: var(--border) !important; }
    /* Reports: progress bar track */
    [data-theme="dark"] .prog-bar { background: var(--border) !important; }
    /* Dashboard: quick action buttons */
    [data-theme="dark"] .qa-btn { background: var(--card) !important; color: var(--text) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .qa-btn:hover { border-color: var(--primary) !important; color: var(--primary) !important; background: rgba(91,141,238,.06) !important; }
    /* Donut chart cards */
    [data-theme="dark"] .donut-card { background: var(--card) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .dc-title { color: var(--primary) !important; }
    [data-theme="dark"] .legend-item { color: var(--text) !important; }
    /* Card body */
    [data-theme="dark"] .card-body { background: var(--card) !important; }
    /* Summary card expand sub-items */
    [data-theme="dark"] .expand-item { background: var(--input-bg) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .ei-val { color: var(--primary) !important; }
    [data-theme="dark"] .ei-label,
    [data-theme="dark"] .ei-pct { color: var(--muted) !important; }
    /* Workers search input */
    [data-theme="dark"] .search-input { background: var(--input-bg) !important; color: var(--text) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .search-input::placeholder { color: var(--muted) !important; }
    /* Module/action badges background in dark */
    [data-theme="dark"] .mod { background: var(--input-bg) !important; color: var(--muted) !important; }
    [data-theme="dark"] .confirm-box { background:#161b27 !important; }
    [data-theme="dark"] .confirm-icon { background:rgba(220,38,38,.15) !important; border-color:rgba(220,38,38,.3) !important; }
    [data-theme="dark"] .confirm-title { color:var(--text) !important; }
    [data-theme="dark"] .confirm-cancel { background:var(--input-bg) !important; color:var(--muted) !important; border-color:var(--border) !important; }
    [data-theme="dark"] .modal { background:var(--card) !important; }
    [data-theme="dark"] .modal-header,
    [data-theme="dark"] .modal-footer { border-color:var(--border) !important; }
    [data-theme="dark"] .modal-header h2 { color:var(--primary) !important; }
    [data-theme="dark"] .mi .mv { background:var(--input-bg) !important; border-color:var(--border) !important; color:var(--text) !important; }
    [data-theme="dark"] .mem-table th { background:var(--header-bg) !important; color:var(--muted) !important; }
    [data-theme="dark"] .mem-table td { border-color:var(--border) !important; color:var(--text) !important; }
    [data-theme="dark"] .badge-head { background:rgba(251,191,36,.12) !important; color:#fbbf24 !important; }
    /* Toast in dark mode */
    [data-theme="dark"] .toast { background:#1a2236 !important; border-color:var(--border) !important; }
    [data-theme="dark"] .toast-info::before { background:var(--primary) !important; }
    [data-theme="dark"] .toast-info .toast-icon { color:var(--primary) !important; }
    /* Page header */
    [data-theme="dark"] .topbar { background:var(--topbar-bg) !important; border-color:var(--topbar-border) !important; }
    [data-theme="dark"] .topbar-title { color:var(--primary) !important; }
    [data-theme="dark"] .topbar-user { background:var(--primary) !important; }
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

@if(auth()->user()->role === 'admin')
      <div class="nav-section">Administration</div>

      <a href="{{ route('workers.index') }}"
         class="nav-item {{ request()->is('workers*') ? 'active' : '' }}">
        <i class="fas fa-user-tie"></i> Worker Information
      </a>

      <a href="{{ route('audit.index') }}"
         class="nav-item {{ request()->is('audit-log*') ? 'active' : '' }}">
        <i class="fas fa-history"></i> Audit Log
      </a>

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

<!-- Page Loader -->
<div class="page-loader" id="pageLoader"><div class="bar" id="loaderBar"></div></div>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<!-- Delete Confirm Modal -->
<div class="confirm-backdrop" id="confirmBackdrop">
  <div class="confirm-box">
    <div class="confirm-icon"><i class="fas fa-trash-alt"></i></div>
    <div class="confirm-title">Delete this record?</div>
    <p class="confirm-msg" id="confirmMsg">This record will be permanently removed and cannot be undone.</p>
    <div class="confirm-actions">
      <button class="confirm-cancel" id="confirmCancel"><i class="fas fa-times"></i> Cancel</button>
      <button class="confirm-ok" id="confirmOk"><i class="fas fa-trash-alt"></i> Delete</button>
    </div>
  </div>
</div>

<script>
  // Apply saved theme immediately on load (before paint)
  (function() {
    const theme = localStorage.getItem('theme') || '{{ session("theme", "light") }}';
    document.documentElement.setAttribute('data-theme', theme);
  })();

  // Live date in topbar
  const d = new Date();
  document.getElementById('topbar-date').textContent = d.toLocaleDateString('en-PH', {weekday:'short', year:'numeric', month:'short', day:'numeric'});

  // ── PAGE LOADER ──
  (function() {
    const loader = document.getElementById('pageLoader');
    const bar    = document.getElementById('loaderBar');
    loader.classList.add('visible');
    bar.style.width = '60%';
    window.addEventListener('load', function() {
      bar.style.width = '100%';
      setTimeout(function() { loader.classList.remove('visible'); }, 350);
    });
    document.addEventListener('click', function(e) {
      var a = e.target.closest('a[href]');
      if (a && !a.getAttribute('href').startsWith('#') && !a.target && a.href !== window.location.href && !a.hasAttribute('onclick')) {
        loader.classList.add('visible');
        bar.style.width = '0%';
        setTimeout(function() { bar.style.width = '65%'; }, 20);
      }
    });
  })();

  // ── TOAST SYSTEM ──
  function showToast(message, type) {
    type = type || 'success';
    var icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', info: 'fa-info-circle' };
    var t = document.createElement('div');
    t.className = 'toast toast-' + type;
    t.innerHTML = '<i class="fas ' + (icons[type]||icons.info) + ' toast-icon"></i>'
      + '<span class="toast-body">' + message + '</span>'
      + '<button class="toast-close" onclick="this.closest(\'.toast\').remove()">×</button>'
      + '<div class="toast-prog"></div>';
    document.getElementById('toastContainer').appendChild(t);
    setTimeout(function() { t.classList.add('show'); }, 15);
    setTimeout(function() { t.classList.remove('show'); setTimeout(function() { t.remove(); }, 400); }, 5000);
  }

  // Auto-convert .alert-success / .alert-error banners → toast
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.alert-success').forEach(function(el) {
      var text = el.textContent.replace(/\s+/g, ' ').trim();
      if (text) showToast(text, 'success');
      el.style.display = 'none';
    });
    document.querySelectorAll('.alert-error, .alert-danger').forEach(function(el) {
      var text = el.textContent.replace(/\s+/g, ' ').trim();
      if (text) showToast(text, 'error');
    });
  });

  // ── DELETE CONFIRM ──
  var _delForm = null;
  function confirmDelete(form, msg) {
    _delForm = form;
    document.getElementById('confirmMsg').textContent = msg || 'This record will be permanently removed and cannot be undone.';
    document.getElementById('confirmBackdrop').classList.add('open');
    return false;
  }
  document.getElementById('confirmOk').addEventListener('click', function() {
    if (_delForm) _delForm.submit();
    document.getElementById('confirmBackdrop').classList.remove('open');
  });
  document.getElementById('confirmCancel').addEventListener('click', function() {
    document.getElementById('confirmBackdrop').classList.remove('open');
    _delForm = null;
  });
  document.getElementById('confirmBackdrop').addEventListener('click', function(e) {
    if (e.target === this) { this.classList.remove('open'); _delForm = null; }
  });
</script>

</body>
</html>