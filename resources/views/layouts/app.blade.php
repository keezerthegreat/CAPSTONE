<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Barangay Cogon — BIDB</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    /* ── THEME VARIABLES ── */
    :root {
      --bg:            #f1f5f9;
      --card:          #ffffff;
      --text:          #0f172a;
      --muted:         #64748b;
      --border:        #e2e8f0;
      --primary:       #1a3a6b;
      --primary-light: #2554a0;
      --topbar-bg:     #ffffff;
      --topbar-border: #e8edf4;
      --topbar-text:   #0f172a;
      --header-bg:     #f8fafc;
      --input-bg:      #ffffff;
      --hover-bg:      #f8fafc;
      --sidebar-w:     248px;
    }
    /* ── DARK MODE ── */
    [data-theme="dark"] {
      --bg:            #0d1117;
      --card:          #161b27;
      --text:          #d1d9e6;
      --muted:         #6b7a99;
      --border:        #1e2740;
      --primary:       #5b8dee;
      --primary-light: #7ba5f5;
      --topbar-bg:     #111827;
      --topbar-border: #1a2438;
      --topbar-text:   #d1d9e6;
      --header-bg:     #111827;
      --input-bg:      #1a2236;
      --hover-bg:      #1a2236;
    }

    /* ── GLOBAL DARK MODE OVERRIDES ── */
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
    [data-theme="dark"] thead { background: var(--header-bg) !important; }
    [data-theme="dark"] tr:hover td { background: var(--hover-bg) !important; }
    [data-theme="dark"] .stat-card,
    [data-theme="dark"] .res-stat,
    [data-theme="dark"] .fam-stat { background: var(--card) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .sc-expand { background: var(--header-bg) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .expand-item { background: var(--input-bg) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .ei-val { color: var(--primary) !important; }
    [data-theme="dark"] .ei-label,
    [data-theme="dark"] .ei-pct { color: var(--muted) !important; }
    [data-theme="dark"] .filter-select,
    [data-theme="dark"] .search-wrap input { background: var(--input-bg) !important; color: var(--text) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .slabel,
    [data-theme="dark"] .sc-label { color: var(--muted) !important; }
    [data-theme="dark"] .svalue,
    [data-theme="dark"] .sc-value { color: var(--primary) !important; }
    [data-theme="dark"] .breadcrumb span,
    [data-theme="dark"] .breadcrumb a { color: var(--primary) !important; }
    /* Buttons */
    [data-theme="dark"] .btn-primary { background: var(--primary) !important; color: #fff !important; }
    [data-theme="dark"] .btn-primary:hover { background: var(--primary-light) !important; }
    [data-theme="dark"] .btn-outline { background: transparent !important; color: var(--primary) !important; border-color: var(--primary) !important; }
    [data-theme="dark"] .btn-view { background: rgba(91,141,238,.12) !important; color: var(--primary) !important; border-color: rgba(91,141,238,.3) !important; }
    [data-theme="dark"] .btn-edit { background: rgba(22,163,74,.1) !important; color: #4ade80 !important; border-color: rgba(22,163,74,.25) !important; }
    [data-theme="dark"] .btn-delete { background: rgba(220,38,38,.1) !important; color: #f87171 !important; border-color: rgba(220,38,38,.25) !important; }
    [data-theme="dark"] .btn-reset { background: var(--input-bg) !important; color: var(--muted) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .btn-reset:hover { background: var(--hover-bg) !important; }
    [data-theme="dark"] .btn-filter { background: var(--primary) !important; }
    /* Nav active */
    [data-theme="dark"] .nav-item.active { background: rgba(91,141,238,.15) !important; }
    [data-theme="dark"] .nav-item.active::before { background: var(--primary) !important; }
    /* Modals */
    [data-theme="dark"] .modal-section-title { color: var(--muted) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .modal-close { color: var(--muted) !important; }
    [data-theme="dark"] .modal-close:hover { color: var(--text) !important; }
    [data-theme="dark"] .modal { background: var(--card) !important; }
    [data-theme="dark"] .modal-header,
    [data-theme="dark"] .modal-footer { border-color: var(--border) !important; }
    [data-theme="dark"] .modal-header h2 { color: var(--primary) !important; }
    [data-theme="dark"] .mi .mv { background: var(--input-bg) !important; border-color: var(--border) !important; color: var(--text) !important; }
    [data-theme="dark"] .ivalue { background: var(--input-bg) !important; border-color: var(--border) !important; color: var(--text) !important; }
    [data-theme="dark"] .mem-table th { background: var(--header-bg) !important; color: var(--muted) !important; }
    [data-theme="dark"] .mem-table td { border-color: var(--border) !important; color: var(--text) !important; }
    [data-theme="dark"] .badge-head { background: rgba(251,191,36,.12) !important; color: #fbbf24 !important; }
    /* Filter row */
    [data-theme="dark"] .filter-row,
    [data-theme="dark"] .filter-area { background: var(--card) !important; }
    /* Settings */
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
    /* Tailwind overrides */
    [data-theme="dark"] table.w-full { background: var(--card) !important; color: var(--text) !important; }
    [data-theme="dark"] thead.bg-slate-200 { background: var(--header-bg) !important; color: var(--muted) !important; }
    [data-theme="dark"] tr.border-t { border-color: var(--border) !important; color: var(--text) !important; }
    [data-theme="dark"] tr.border-t td { color: var(--text) !important; }
    [data-theme="dark"] tr.border-t:hover { background: var(--hover-bg) !important; }
    [data-theme="dark"] div.bg-white { background: var(--card) !important; color: var(--text) !important; }
    /* Table headers via class */
    [data-theme="dark"] .recent-table th,
    [data-theme="dark"] .list-table th { background: var(--header-bg) !important; color: var(--muted) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .recent-table td,
    [data-theme="dark"] .list-table td { color: var(--text) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .recent-table tbody tr:hover,
    [data-theme="dark"] .list-table tbody tr:hover { background: var(--hover-bg) !important; }
    /* Tabs */
    [data-theme="dark"] .tabs { background: var(--header-bg) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .tab { color: var(--muted) !important; border-color: transparent !important; }
    [data-theme="dark"] .tab.active { background: var(--card) !important; color: var(--primary) !important; border-color: var(--border) !important; }
    /* Misc */
    [data-theme="dark"] .prog-bar { background: var(--border) !important; }
    [data-theme="dark"] .qa-btn { background: var(--card) !important; color: var(--text) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .qa-btn:hover { border-color: var(--primary) !important; color: var(--primary) !important; background: rgba(91,141,238,.06) !important; }
    [data-theme="dark"] .donut-card { background: var(--card) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .dc-title { color: var(--primary) !important; }
    [data-theme="dark"] .legend-item { color: var(--text) !important; }
    [data-theme="dark"] .card-body { background: var(--card) !important; }
    [data-theme="dark"] .search-input { background: var(--input-bg) !important; color: var(--text) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .search-input::placeholder { color: var(--muted) !important; }
    [data-theme="dark"] .mod { background: var(--input-bg) !important; color: var(--muted) !important; }
    [data-theme="dark"] .fullscreen-btn { background: var(--card) !important; color: var(--primary) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .fullscreen-btn:hover { background: var(--hover-bg) !important; }
    /* Pagination */
    [data-theme="dark"] .pagination-links a,
    [data-theme="dark"] .pagination-links span { background: var(--card) !important; border-color: var(--border) !important; color: var(--primary) !important; }
    [data-theme="dark"] .pagination-links a:hover { background: var(--hover-bg) !important; }
    [data-theme="dark"] .pagination-links span.current { background: var(--primary) !important; color: #fff !important; border-color: var(--primary) !important; }
    /* Confirm modal */
    [data-theme="dark"] .confirm-box { background: #161b27 !important; }
    [data-theme="dark"] .confirm-icon { background: rgba(220,38,38,.15) !important; border-color: rgba(220,38,38,.3) !important; }
    [data-theme="dark"] .confirm-title { color: var(--text) !important; }
    [data-theme="dark"] .confirm-cancel { background: var(--input-bg) !important; color: var(--muted) !important; border-color: var(--border) !important; }
    /* Toast */
    [data-theme="dark"] .toast { background: #1a2236 !important; border-color: var(--border) !important; }
    [data-theme="dark"] .toast-info::before { background: var(--primary) !important; }
    [data-theme="dark"] .toast-info .toast-icon { color: var(--primary) !important; }
    /* Topbar */
    [data-theme="dark"] .topbar { background: var(--topbar-bg) !important; border-color: var(--topbar-border) !important; }
    [data-theme="dark"] .topbar-title { color: var(--primary) !important; }
    [data-theme="dark"] .topbar-user-pill { background: var(--card) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .topbar-user-name { color: var(--text) !important; }
    [data-theme="dark"] .topbar-date { color: var(--muted) !important; }
    /* Filter pills */
    [data-theme="dark"] .flt-btn { background: var(--input-bg) !important; color: var(--text) !important; border-color: var(--border) !important; }
    [data-theme="dark"] .flt-btn:hover { border-color: var(--primary) !important; color: var(--primary) !important; }
    [data-theme="dark"] .flt-btn.active { background: var(--primary) !important; color: #fff !important; border-color: var(--primary) !important; }
    [data-theme="dark"] .flt-dropdown,
    [data-theme="dark"] .age-popup { background: #1a2236 !important; border-color: var(--border) !important; box-shadow: 0 12px 40px rgba(0,0,0,.6) !important; }
    [data-theme="dark"] .flt-option { color: var(--text) !important; }
    [data-theme="dark"] .flt-option:hover { background: rgba(91,141,238,.12) !important; color: var(--primary) !important; }
    [data-theme="dark"] .flt-option.selected { background: rgba(91,141,238,.18) !important; color: var(--primary) !important; }

    /* ── RESET + BASE ── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Plus Jakarta Sans', 'Segoe UI', system-ui, sans-serif;
      background: var(--bg);
      color: var(--text);
      font-size: 13.5px;
      line-height: 1.5;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      transition: background .25s, color .25s;
    }

    /* ── SIDEBAR ── */
    .sidebar {
      position: fixed; top: 0; left: 0;
      width: var(--sidebar-w); height: 100vh;
      background: linear-gradient(175deg, #0a1628 0%, #0d1e3a 60%, #0f1e3d 100%);
      display: flex; flex-direction: column;
      z-index: 100; overflow: hidden;
      border-right: 1px solid rgba(255,255,255,.04);
    }

    .sidebar-brand {
      padding: 20px 0 18px;
      border-bottom: 1px solid rgba(255,255,255,.06);
      display: flex; flex-direction: column; align-items: center; justify-content: center;
      gap: 10px; text-decoration: none; flex-shrink: 0;
      width: 100%; box-sizing: border-box;
    }
    .brand-logo {
      width: 72px; height: 72px;
      background: #fff;
      border-radius: 50%;
      padding: 6px;
      box-sizing: border-box;
      flex-shrink: 0;
      box-shadow: 0 2px 12px rgba(0,0,0,.3);
      margin: 0 auto;
    }
    .brand-logo img {
      width: 100%; height: 100%;
      object-fit: contain; display: block;
      border-radius: 50%;
    }
    .brand-text {
      text-align: center;
      width: 100%;
    }
    .brand-text .name {
      font-size: 12.5px; font-weight: 700; color: #fff;
      letter-spacing: .01em; line-height: 1.2;
    }
    .brand-text .sub {
      font-size: 10px; color: rgba(255,255,255,.35);
      margin-top: 2px; letter-spacing: .02em;
    }

    .sidebar-nav { flex: 1; padding: 12px 8px; overflow-y: auto; }

    .nav-section {
      font-size: 9px; font-weight: 700; letter-spacing: .13em;
      text-transform: uppercase; color: rgba(255,255,255,.22);
      padding: 0 10px; margin: 18px 0 4px;
    }
    .nav-section:first-child { margin-top: 2px; }

    .nav-item {
      display: flex; align-items: center; gap: 9px;
      padding: 8px 10px; border-radius: 7px;
      font-size: 12.5px; font-weight: 500; color: rgba(255,255,255,.55);
      text-decoration: none; transition: background .12s, color .12s;
      margin-bottom: 1px; position: relative; overflow: hidden;
    }
    .nav-item:hover {
      background: rgba(255,255,255,.07);
      color: rgba(255,255,255,.88);
    }
    .nav-item.active {
      background: rgba(91,141,238,.14);
      color: #93bbf7;
      font-weight: 600;
    }
    .nav-item.active::before {
      content: '';
      position: absolute; left: 0; top: 20%; bottom: 20%;
      width: 3px; background: #5b8dee;
      border-radius: 0 3px 3px 0;
    }
    .nav-item i {
      width: 14px; text-align: center;
      font-size: 12px; flex-shrink: 0;
      opacity: .65; transition: opacity .12s;
    }
    .nav-item:hover i { opacity: .9; }
    .nav-item.active i { opacity: 1; color: #93bbf7; }

    .sidebar-footer {
      padding: 10px 8px 12px;
      border-top: 1px solid rgba(255,255,255,.06);
      flex-shrink: 0;
    }
    .sidebar-user {
      display: flex; align-items: center; gap: 9px;
      padding: 8px 10px; border-radius: 7px;
      background: rgba(255,255,255,.04);
      margin-bottom: 6px;
    }
    .sidebar-avatar {
      width: 28px; height: 28px; border-radius: 7px;
      background: linear-gradient(135deg, #2a5298, #1a3a6b);
      display: flex; align-items: center; justify-content: center;
      font-size: 11px; font-weight: 700; color: #fff; flex-shrink: 0;
      border: 1px solid rgba(255,255,255,.12);
    }
    .sidebar-user-name {
      font-size: 12px; font-weight: 600;
      color: rgba(255,255,255,.8); line-height: 1.2;
    }
    .sidebar-user-role {
      font-size: 10px; color: rgba(255,255,255,.3);
      margin-top: 1px; letter-spacing: .02em;
    }
    .btn-logout {
      display: flex; align-items: center; gap: 8px;
      width: 100%; padding: 7px 10px; border-radius: 7px;
      background: transparent; border: none; cursor: pointer;
      font-family: inherit; font-size: 12px; font-weight: 500;
      color: rgba(255,255,255,.32); transition: all .15s;
    }
    .btn-logout i { font-size: 11px; }
    .btn-logout:hover { background: rgba(220,38,38,.15); color: #fca5a5; }
    .theme-toggle-row {
      display: flex; align-items: center; justify-content: space-between;
      padding: 6px 10px; margin-bottom: 4px;
    }
    .theme-toggle-label {
      font-size: 11px; font-weight: 500; color: rgba(255,255,255,.3);
      display: flex; align-items: center; gap: 6px;
    }
    .theme-toggle-label i { font-size: 10px; }
    .theme-toggle-btn {
      position: relative; width: 40px; height: 20px;
      background: rgba(255,255,255,.1); border-radius: 20px;
      border: 1px solid rgba(255,255,255,.12); cursor: pointer;
      transition: background .2s, border-color .2s; flex-shrink: 0;
      padding: 0;
    }
    .theme-toggle-btn.dark { background: rgba(91,141,238,.35); border-color: rgba(91,141,238,.5); }
    .theme-toggle-knob {
      position: absolute; top: 2px; left: 2px;
      width: 14px; height: 14px; border-radius: 50%;
      background: rgba(255,255,255,.5);
      display: flex; align-items: center; justify-content: center;
      font-size: 7px; transition: transform .2s, background .2s;
      pointer-events: none;
    }
    .theme-toggle-btn.dark .theme-toggle-knob {
      transform: translateX(20px);
      background: #93bbf7;
    }

    /* ── MAIN CONTENT ── */
    .main-content { margin-left: var(--sidebar-w); min-height: 100vh; }

    /* ── TOP BAR ── */
    .topbar {
      height: 52px; background: var(--topbar-bg);
      border-bottom: 1px solid var(--topbar-border);
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 22px; position: sticky; top: 0; z-index: 50;
      transition: background .25s, border-color .25s;
    }
    .topbar-left { display: flex; align-items: center; gap: 0; }
    .topbar-brand {
      font-size: 11px; font-weight: 700; color: var(--muted);
      text-transform: uppercase; letter-spacing: .08em;
      border-right: 1px solid var(--border);
      padding-right: 14px; margin-right: 14px;
      white-space: nowrap;
    }
    .topbar-title {
      font-size: 13.5px; font-weight: 600;
      color: var(--topbar-text); letter-spacing: .01em;
    }
    .topbar-right { display: flex; align-items: center; gap: 8px; }
    .topbar-date {
      font-size: 11px; color: var(--muted); font-weight: 500;
      background: var(--header-bg); border: 1px solid var(--border);
      padding: 4px 10px; border-radius: 20px; white-space: nowrap;
    }
    .topbar-user-pill {
      display: flex; align-items: center; gap: 7px;
      padding: 4px 12px 4px 6px;
      border-radius: 20px; background: var(--header-bg);
      border: 1px solid var(--border); cursor: default;
      transition: border-color .15s;
    }
    .topbar-user-pill:hover { border-color: var(--primary); }
    .topbar-avatar {
      width: 24px; height: 24px; border-radius: 50%;
      background: var(--primary); color: #fff;
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; font-size: 9.5px; flex-shrink: 0;
    }
    .topbar-user-name {
      font-size: 12px; font-weight: 600;
      color: var(--text); white-space: nowrap;
    }
    .topbar-role {
      font-size: 10px; color: var(--muted); font-weight: 500;
      text-transform: capitalize;
    }

    .page-content { padding: 0; }

    /* ── PAGE LOADER ── */
    .page-loader { position:fixed; top:0; left:0; right:0; height:2px; z-index:9999; pointer-events:none; opacity:0; transition:opacity .2s; }
    .page-loader.visible { opacity:1; }
    .page-loader .bar { height:100%; width:0%; background:linear-gradient(90deg,var(--primary) 0%,var(--primary-light) 50%,var(--primary) 100%); background-size:200% 100%; animation:ldrShimmer 1.4s linear infinite; transition:width .35s ease; }
    @keyframes ldrShimmer { 0%{background-position:200% 0} 100%{background-position:-200% 0} }

    /* ── TOAST NOTIFICATIONS ── */
    .toast-container { position:fixed; bottom:20px; right:20px; z-index:9998; display:flex; flex-direction:column-reverse; gap:7px; pointer-events:none; }
    .toast { background:var(--card); border-radius:10px; padding:12px 14px; box-shadow:0 8px 30px rgba(0,0,0,.14); border:1px solid var(--border); display:flex; align-items:center; gap:10px; font-size:12.5px; font-weight:500; color:var(--text); pointer-events:all; min-width:260px; max-width:340px; transform:translateX(calc(100% + 24px)); transition:transform .32s cubic-bezier(.34,1.46,.64,1); position:relative; overflow:hidden; }
    .toast.show { transform:translateX(0); }
    .toast::before { content:''; position:absolute; left:0; top:0; bottom:0; width:3px; border-radius:10px 0 0 10px; }
    .toast-success::before { background:#16a34a; }
    .toast-error::before   { background:#dc2626; }
    .toast-info::before    { background:var(--primary); }
    .toast-icon { font-size:14px; flex-shrink:0; }
    .toast-success .toast-icon { color:#16a34a; }
    .toast-error   .toast-icon { color:#dc2626; }
    .toast-info    .toast-icon { color:var(--primary); }
    .toast-body { flex:1; line-height:1.4; }
    .toast-close { background:none; border:none; cursor:pointer; color:var(--muted); font-size:16px; line-height:1; padding:2px; flex-shrink:0; border-radius:4px; transition:background .1s; }
    .toast-close:hover { background:rgba(0,0,0,.06); }
    .toast-prog { position:absolute; bottom:0; left:0; height:2px; background:currentColor; opacity:.15; animation:toastProg 4.5s linear forwards; }
    @keyframes toastProg { from{width:100%} to{width:0%} }

    /* ── DELETE CONFIRM MODAL ── */
    .confirm-backdrop { display:none; position:fixed; inset:0; background:rgba(0,0,0,.4); z-index:2000; align-items:center; justify-content:center; backdrop-filter:blur(4px); }
    .confirm-backdrop.open { display:flex; }
    .confirm-box { background:var(--card); border-radius:18px; padding:32px 28px 24px; width:380px; max-width:92vw; box-shadow:0 24px 64px rgba(0,0,0,.22); text-align:center; transform:scale(.9) translateY(16px); opacity:0; transition:transform .26s cubic-bezier(.34,1.46,.64,1),opacity .18s ease; border:1px solid var(--border); }
    .confirm-backdrop.open .confirm-box { transform:scale(1) translateY(0); opacity:1; }
    .confirm-icon { width:56px; height:56px; border-radius:50%; background:#fff1f2; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; font-size:22px; color:#dc2626; border:2px solid #fecdd3; }
    .confirm-title { font-size:16px; font-weight:700; color:var(--text); margin-bottom:7px; }
    .confirm-msg { font-size:12.5px; color:var(--muted); margin-bottom:24px; line-height:1.6; }
    .confirm-actions { display:flex; gap:9px; justify-content:center; }
    .confirm-cancel { background:var(--header-bg); color:var(--muted); border:1.5px solid var(--border); padding:9px 20px; border-radius:9px; font-size:12.5px; font-weight:600; cursor:pointer; font-family:inherit; transition:all .15s; }
    .confirm-cancel:hover { border-color:var(--muted); }
    .confirm-ok { background:#dc2626; color:#fff; border:none; padding:9px 20px; border-radius:9px; font-size:12.5px; font-weight:600; cursor:pointer; font-family:inherit; transition:background .15s; display:inline-flex; align-items:center; gap:6px; }
    .confirm-ok:hover { background:#b91c1c; }

    /* ── GLOBAL FILTER PILLS ── */
    .flt-wrap { position:relative; }
    .flt-btn { background:var(--card); color:var(--text); border:1.5px solid var(--border); padding:5px 11px; border-radius:20px; font-size:11.5px; font-weight:500; cursor:pointer; font-family:inherit; display:inline-flex; align-items:center; gap:5px; white-space:nowrap; transition:border-color .15s,color .15s,background .15s; }
    .flt-btn:hover { border-color:var(--primary); color:var(--primary); }
    .flt-btn.active { background:var(--primary); color:#fff; border-color:var(--primary); font-weight:600; }
    .flt-btn .flt-caret { font-size:7px; opacity:.5; }
    .flt-btn .flt-x { font-size:13px; line-height:1; margin-left:1px; opacity:.7; }
    .flt-btn .flt-x:hover { opacity:1; }
    .flt-dropdown { display:none; position:fixed; top:0; left:0; background:var(--card); border:1.5px solid var(--border); border-radius:10px; padding:4px; z-index:9000; box-shadow:0 8px 28px rgba(0,0,0,.12); min-width:160px; }
    .flt-dropdown.open { display:block; animation:fltIn .15s ease; }
    @keyframes fltIn { from{opacity:0;transform:translateY(-4px)} to{opacity:1;transform:translateY(0)} }
    .flt-option { padding:7px 10px; border-radius:6px; font-size:12px; font-weight:500; cursor:pointer; color:var(--text); transition:background .1s; }
    .flt-option:hover { background:#f0f7ff; color:var(--primary); }
    .flt-option.selected { background:#eff6ff; color:var(--primary); font-weight:600; }
  </style>
</head>
<body>

<div style="display:flex">

  <!-- SIDEBAR -->
  <aside class="sidebar">

    <!-- Brand -->
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
      <div class="brand-logo">
        <img src="{{ asset('images/cogon.png') }}" alt="Barangay Cogon Logo">
      </div>
      <div class="brand-text">
        <div class="name">Barangay Cogon</div>
        <div class="sub">BIDB &middot; Ormoc City, Leyte</div>
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

      <a href="{{ route('families.index') }}"
         class="nav-item {{ request()->routeIs('families.*') ? 'active' : '' }}">
        <i class="fas fa-people-roof"></i> Families
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

      <div class="nav-section">Analytics</div>

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

    <!-- Footer -->
    <div class="sidebar-footer">
      <div class="sidebar-user">
        <div class="sidebar-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
        <div>
          <div class="sidebar-user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
          <div class="sidebar-user-role">{{ ucfirst(auth()->user()->role ?? 'staff') }}</div>
        </div>
      </div>
      <div class="theme-toggle-row">
        <span class="theme-toggle-label" id="themeLabel">
          <i class="fas fa-sun"></i> Light Mode
        </span>
        <button class="theme-toggle-btn" id="themeToggleBtn" title="Toggle dark/light mode">
          <span class="theme-toggle-knob">
            <i class="fas fa-sun" id="themeKnobIcon"></i>
          </span>
        </button>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout">
          <i class="fas fa-sign-out-alt"></i> Sign out
        </button>
      </form>
    </div>

  </aside>

  <!-- MAIN CONTENT -->
  <div class="main-content" style="flex:1">

    <!-- Top Bar -->
    <div class="topbar">
      <div class="topbar-left">
        <span class="topbar-brand">Brgy. Cogon</span>
        <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
      </div>
      <div class="topbar-right">
        <span class="topbar-date" id="topbar-date"></span>
        <div class="topbar-user-pill">
          <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
          <span class="topbar-user-name">{{ auth()->user()->name ?? 'Admin' }}</span>
        </div>
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
  (function() {
    const theme = localStorage.getItem('theme') || '{{ session("theme", "light") }}';
    document.documentElement.setAttribute('data-theme', theme);
  })();

  function applyThemeUI(theme) {
    const btn  = document.getElementById('themeToggleBtn');
    const lbl  = document.getElementById('themeLabel');
    const icon = document.getElementById('themeKnobIcon');
    if (!btn) return;
    if (theme === 'dark') {
      btn.classList.add('dark');
      lbl.innerHTML = '<i class="fas fa-moon"></i> Dark Mode';
      icon.className = 'fas fa-moon';
    } else {
      btn.classList.remove('dark');
      lbl.innerHTML = '<i class="fas fa-sun"></i> Light Mode';
      icon.className = 'fas fa-sun';
    }
  }

  document.addEventListener('DOMContentLoaded', function() {
    const current = document.documentElement.getAttribute('data-theme') || 'light';
    applyThemeUI(current);

    document.getElementById('themeToggleBtn').addEventListener('click', function() {
      const next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      document.documentElement.setAttribute('data-theme', next);
      localStorage.setItem('theme', next);
      applyThemeUI(next);

      // Sync with server session (best-effort, no page reload)
      fetch('/settings/theme', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]') ? document.querySelector('meta[name=csrf-token]').content : '{{ csrf_token() }}' },
        body: JSON.stringify({ theme: next })
      }).catch(function(){});
    });
  });

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
      setTimeout(function() { loader.classList.remove('visible'); }, 300);
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
    setTimeout(function() { t.classList.remove('show'); setTimeout(function() { t.remove(); }, 380); }, 5000);
  }

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
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { document.getElementById('confirmBackdrop').classList.remove('open'); _delForm = null; }
  });
</script>

</body>
</html>
