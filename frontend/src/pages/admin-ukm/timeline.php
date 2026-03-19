<?php
session_start();
if (!isset($_SESSION['id_ukm'])) {
    header('Location: /index.html');
    exit();
}
$id_ukm = $_SESSION['id_ukm'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Timeline Kegiatan — Admin UKM SIGMA</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
  <style>
    :root {
      --navy:        #0F1B4C;
      --accent:      #4F6AF0;
      --accent-soft: #e8ecff;
      --teal:        #0EA5A0;
      --amber:       #F59E0B;
      --rose:        #F43F5E;
      --green:       #10B981;
      --purple:      #7C3AED;
      --surface:     #F4F6FB;
      --card:        #FFFFFF;
      --border:      #E2E8F2;
      --text-main:   #1A2340;
      --text-mid:    #4A5568;
      --text-soft:   #94A3B8;
      --sidebar-w:   260px;
      --topbar-h:    68px;
      --radius:      14px;
      --shadow-sm:   0 1px 4px rgba(15,27,76,0.06);
      --shadow-md:   0 4px 20px rgba(15,27,76,0.10);
      --shadow-lg:   0 8px 40px rgba(15,27,76,0.14);
      --transition:  0.2s cubic-bezier(0.4,0,0.2,1);
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--surface); color: var(--text-main); display: flex; min-height: 100vh; }

    /* ── SIDEBAR ── */
    .sidebar { width: var(--sidebar-w); background: var(--navy); display: flex; flex-direction: column; position: fixed; top:0;left:0;bottom:0; z-index:200; transition: transform var(--transition); }
    .sidebar::before { content:''; position:absolute; top:-80px;right:-80px; width:220px;height:220px; background:radial-gradient(circle,rgba(79,106,240,.18) 0%,transparent 70%); pointer-events:none; }
    .sidebar-brand { padding:0 24px; height:var(--topbar-h); display:flex;align-items:center;gap:12px; border-bottom:1px solid rgba(255,255,255,.06); flex-shrink:0; }
    .brand-icon { width:36px;height:36px; background:linear-gradient(135deg,var(--accent),#7C3AED); border-radius:10px; display:flex;align-items:center;justify-content:center; font-size:16px;color:#fff;flex-shrink:0; box-shadow:0 4px 12px rgba(79,106,240,.4); }
    .brand-text h2 { font-size:16px;font-weight:800;color:#fff;letter-spacing:.5px;line-height:1.2; }
    .brand-text span { font-size:11px;color:rgba(255,255,255,.45);font-weight:400; }
    .sidebar-ukm-card { margin:16px 16px 8px; background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.08); border-radius:12px; padding:14px 16px; display:flex;align-items:center;gap:12px; flex-shrink:0; }
    .ukm-avatar { width:40px;height:40px; border-radius:10px; background:linear-gradient(135deg,var(--teal),#0284C7); display:flex;align-items:center;justify-content:center; font-size:18px;color:#fff;flex-shrink:0; }
    .ukm-info h3 { font-size:13px;font-weight:700;color:#fff;line-height:1.3; }
    .ukm-info span { font-size:11px;color:rgba(255,255,255,.45); }
    .sidebar-section-label { padding:16px 24px 6px; font-size:10px;font-weight:700; letter-spacing:1.5px; color:rgba(255,255,255,.25); text-transform:uppercase; flex-shrink:0; }
    .sidebar-nav { flex:1;overflow-y:auto;padding:0 12px 12px; scrollbar-width:thin; scrollbar-color:rgba(255,255,255,.1) transparent; }
    .nav-item { display:flex;align-items:center;gap:12px; padding:10px 14px; border-radius:10px; cursor:pointer; transition:all var(--transition); text-decoration:none; color:rgba(255,255,255,.55); font-size:14px;font-weight:500; position:relative; margin-bottom:2px; }
    .nav-item:hover { background:rgba(255,255,255,.07);color:rgba(255,255,255,.9); }
    .nav-item.active { background:linear-gradient(135deg,rgba(79,106,240,.25),rgba(79,106,240,.12)); color:#fff; border:1px solid rgba(79,106,240,.3); }
    .nav-item.active::before { content:''; position:absolute; left:0;top:50%;transform:translateY(-50%); width:3px;height:20px; background:var(--accent); border-radius:0 3px 3px 0; }
    .nav-icon { width:34px;height:34px; border-radius:8px; display:flex;align-items:center;justify-content:center; font-size:14px; background:rgba(255,255,255,.07); flex-shrink:0; transition:all var(--transition); }
    .nav-item.active .nav-icon { background:var(--accent);color:#fff;box-shadow:0 4px 12px rgba(79,106,240,.35); }
    .nav-item:hover .nav-icon { background:rgba(255,255,255,.12);color:#fff; }
    .sidebar-footer { padding:12px;border-top:1px solid rgba(255,255,255,.06);flex-shrink:0; }
    .logout-btn { display:flex;align-items:center;gap:12px; width:100%;padding:10px 14px; border-radius:10px; border:1px solid rgba(244,63,94,.2); background:rgba(244,63,94,.07); color:rgba(255,100,120,.8); cursor:pointer; font-size:14px;font-weight:500; transition:all var(--transition); font-family:inherit; }
    .logout-btn:hover { background:rgba(244,63,94,.15);color:#F43F5E;border-color:rgba(244,63,94,.35); }
    .logout-btn .nav-icon { background:rgba(244,63,94,.12); }

    /* ── MAIN ── */
    .main { margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh; }
    .topbar { height:var(--topbar-h); background:var(--card); border-bottom:1px solid var(--border); display:flex;align-items:center; padding:0 28px;gap:16px; position:sticky;top:0;z-index:100; box-shadow:var(--shadow-sm); }
    .topbar-toggle { display:none; width:36px;height:36px; border-radius:8px; background:var(--surface); border:1px solid var(--border); cursor:pointer; align-items:center;justify-content:center; color:var(--text-mid);font-size:14px; }
    .topbar-breadcrumb { flex:1; }
    .topbar-breadcrumb h1 { font-size:18px;font-weight:700;color:var(--text-main);line-height:1.2; }
    .topbar-breadcrumb p  { font-size:12px;color:var(--text-soft); }
    .topbar-actions { display:flex;align-items:center;gap:10px; }
    .topbar-btn { width:38px;height:38px; border-radius:10px; background:var(--surface); border:1px solid var(--border); display:flex;align-items:center;justify-content:center; cursor:pointer;color:var(--text-mid);font-size:15px; transition:all var(--transition); }
    .topbar-btn:hover { background:var(--accent-soft);border-color:var(--accent);color:var(--accent); }
    .topbar-admin { display:flex;align-items:center;gap:10px; padding:6px 12px 6px 6px; border-radius:12px; background:var(--surface); border:1px solid var(--border); cursor:pointer; transition:all var(--transition); }
    .topbar-admin:hover { border-color:var(--accent); }
    .admin-avatar { width:30px;height:30px; border-radius:8px; background:linear-gradient(135deg,var(--accent),#7C3AED); display:flex;align-items:center;justify-content:center; color:#fff;font-size:13px;font-weight:700; }
    .admin-name { font-size:13px;font-weight:600;color:var(--text-main); }
    .content { flex:1;padding:28px; }

    /* ── STATS ROW ── */
    .stats-row { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
    .mini-stat { background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:18px 20px; display:flex;align-items:center;gap:14px; box-shadow:var(--shadow-sm); transition:all var(--transition); animation:fadeUp .4s ease both; }
    .mini-stat:nth-child(1){animation-delay:.05s} .mini-stat:nth-child(2){animation-delay:.1s} .mini-stat:nth-child(3){animation-delay:.15s} .mini-stat:nth-child(4){animation-delay:.2s}
    @keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
    .mini-stat:hover { transform:translateY(-2px);box-shadow:var(--shadow-md); }
    .mini-icon { width:44px;height:44px; border-radius:12px; display:flex;align-items:center;justify-content:center; font-size:18px;flex-shrink:0; }
    .mi-blue   { background:#EEF1FF;color:var(--accent); }
    .mi-green  { background:#ECFDF5;color:var(--green); }
    .mi-amber  { background:#FFFBEB;color:var(--amber); }
    .mi-purple { background:#F5F3FF;color:var(--purple); }
    .mini-body h2 { font-size:24px;font-weight:800;color:var(--text-main);letter-spacing:-.5px;line-height:1; }
    .mini-body p  { font-size:12px;color:var(--text-soft);margin-top:4px;font-weight:500; }

    /* ── TOOLBAR ── */
    .toolbar { display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px;flex-wrap:wrap; }
    .toolbar-left { display:flex;align-items:center;gap:10px;flex-wrap:wrap; }
    .filter-chip { padding:7px 16px; border-radius:20px; border:1.5px solid var(--border); background:var(--card); color:var(--text-mid); font-size:13px;font-weight:600; cursor:pointer; transition:all var(--transition); font-family:inherit; }
    .filter-chip:hover { border-color:var(--accent);color:var(--accent); }
    .filter-chip.active { background:var(--accent);border-color:var(--accent);color:#fff; }
    .search-wrap { position:relative;min-width:220px; }
    .search-wrap i { position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-soft);font-size:13px; }
    .search-input { width:100%;padding:9px 12px 9px 34px; border:1px solid var(--border);border-radius:9px; font-family:inherit;font-size:13px;color:var(--text-main); background:var(--card);outline:none; transition:all var(--transition); }
    .search-input:focus { border-color:var(--accent);box-shadow:0 0 0 3px rgba(79,106,240,.10); }

    /* ── TIMELINE CARDS GRID ── */
    .timeline-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(340px,1fr)); gap:20px; }
    .tl-card { background:var(--card); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow-sm); overflow:hidden; transition:all var(--transition); animation:fadeUp .4s ease both; display:flex;flex-direction:column; }
    .tl-card:hover { transform:translateY(-3px);box-shadow:var(--shadow-md);border-color:rgba(79,106,240,.2); }
    .tl-banner { width:100%;height:140px; object-fit:cover; background:linear-gradient(135deg,#e8ecff,#f5f3ff); display:flex;align-items:center;justify-content:center; font-size:40px;color:rgba(79,106,240,.25); flex-shrink:0; }
    .tl-banner img { width:100%;height:100%;object-fit:cover; }
    .tl-body { padding:18px 20px;flex:1;display:flex;flex-direction:column;gap:12px; }
    .tl-meta { display:flex;align-items:center;justify-content:space-between;gap:8px; }
    .badge { display:inline-flex;align-items:center;gap:5px; padding:4px 10px;border-radius:20px; font-size:11px;font-weight:700; }
    .badge-proker  { background:#EEF1FF;color:var(--accent); }
    .badge-agenda  { background:#FFFBEB;color:var(--amber); }
    .badge-active  { background:#ECFDF5;color:var(--green); }
    .badge-inactive{ background:#FFF1F2;color:var(--rose); }
    .tl-title { font-size:15px;font-weight:700;color:var(--text-main);line-height:1.4; }
    .tl-desc { font-size:13px;color:var(--text-mid);line-height:1.6; display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden; }
    .tl-info-row { display:flex;align-items:center;gap:16px;flex-wrap:wrap; }
    .tl-info-item { display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-soft); }
    .tl-info-item i { font-size:11px; }
    .tl-footer { padding:14px 20px; border-top:1px solid var(--border); display:flex;align-items:center;gap:8px; background:var(--surface); flex-wrap:wrap; }
    .btn-sm { display:inline-flex;align-items:center;gap:6px; padding:6px 12px; border-radius:8px; border:none;cursor:pointer; font-size:12px;font-weight:600; font-family:inherit; transition:all var(--transition); white-space:nowrap; }
    .btn-edit    { background:#EEF1FF;color:var(--accent); }
    .btn-delete  { background:#FFF1F2;color:var(--rose); }
    .btn-panitia { background:#F0FDF4;color:var(--green); }
    .btn-rapat   { background:#FFFBEB;color:var(--amber); }
    .btn-edit:hover    { background:var(--accent);color:#fff; }
    .btn-delete:hover  { background:var(--rose);color:#fff; }
    .btn-panitia:hover { background:var(--green);color:#fff; }
    .btn-rapat:hover   { background:var(--amber);color:#fff; }

    .btn-primary { display:inline-flex;align-items:center;gap:8px; padding:9px 20px;background:var(--accent);color:#fff; border:none;border-radius:10px; font-size:13px;font-weight:600;cursor:pointer;font-family:inherit; transition:all var(--transition); box-shadow:0 4px 12px rgba(79,106,240,.3); }
    .btn-primary:hover { background:#3d59e0;transform:translateY(-1px); }
    .btn-primary:disabled { opacity:.6;cursor:not-allowed;transform:none; }
    .btn-secondary { display:inline-flex;align-items:center;gap:8px; padding:9px 20px;background:var(--surface);color:var(--text-mid); border:1px solid var(--border);border-radius:10px; font-size:13px;font-weight:600;cursor:pointer;font-family:inherit; transition:all var(--transition); }
    .btn-secondary:hover { background:var(--border); }

    .empty-state { text-align:center;padding:60px 20px;color:var(--text-soft); }
    .empty-state .ei { width:72px;height:72px; border-radius:20px; background:var(--surface); margin:0 auto 18px; display:flex;align-items:center;justify-content:center; font-size:32px; }
    .empty-state h3 { font-size:16px;font-weight:600;color:var(--text-mid);margin-bottom:8px; }
    .empty-state p { font-size:13px; }

    /* ── MODAL ── */
    .modal-overlay { display:none;position:fixed;inset:0; background:rgba(15,27,76,.45); z-index:500; backdrop-filter:blur(4px); align-items:center;justify-content:center; padding:20px; }
    .modal-overlay.open { display:flex; }
    .modal-box { background:var(--card); border-radius:20px; width:100%;max-width:560px; box-shadow:var(--shadow-lg); max-height:90vh;display:flex;flex-direction:column; animation:modalIn .25s cubic-bezier(.34,1.56,.64,1); }
    .modal-box.wide { max-width:680px; }
    @keyframes modalIn { from{opacity:0;transform:scale(.92) translateY(20px)} to{opacity:1;transform:scale(1) translateY(0)} }
    .modal-header { padding:22px 28px 18px; border-bottom:1px solid var(--border); display:flex;align-items:center;justify-content:space-between; flex-shrink:0; }
    .modal-header-left { display:flex;align-items:center;gap:14px; }
    .modal-header-icon { width:42px;height:42px; border-radius:12px; display:flex;align-items:center;justify-content:center; font-size:18px; }
    .icon-blue   { background:var(--accent-soft);color:var(--accent); }
    .icon-green  { background:#ECFDF5;color:var(--green); }
    .icon-amber  { background:#FFFBEB;color:var(--amber); }
    .modal-title   { font-size:16px;font-weight:700;color:var(--text-main); }
    .modal-subtitle { font-size:12px;color:var(--text-soft);margin-top:2px; }
    .modal-close { width:32px;height:32px; border-radius:8px; background:var(--surface);border:1px solid var(--border); display:flex;align-items:center;justify-content:center; cursor:pointer;color:var(--text-soft);font-size:16px; transition:all var(--transition); }
    .modal-close:hover { background:#FFF1F2;color:var(--rose);border-color:var(--rose); }
    .modal-body { padding:22px 28px;display:flex;flex-direction:column;gap:16px;overflow-y:auto;flex:1; }
    .modal-footer { padding:14px 28px 22px;display:flex;gap:10px;justify-content:flex-end;flex-shrink:0; }
    .form-field { display:flex;flex-direction:column;gap:6px; }
    .form-row-2 { display:grid;grid-template-columns:1fr 1fr;gap:14px; }
    .form-label { font-size:11px;font-weight:700;color:var(--text-mid);letter-spacing:.4px;text-transform:uppercase; }
    .form-label span { color:var(--rose);margin-left:2px; }
    .form-control { padding:10px 14px; border:1.5px solid var(--border); border-radius:10px; font-size:14px;font-family:inherit;color:var(--text-main); background:var(--surface); transition:all var(--transition);outline:none; }
    .form-control:focus { border-color:var(--accent);background:var(--card);box-shadow:0 0 0 3px rgba(79,106,240,.12); }
    textarea.form-control { resize:vertical;min-height:80px; }
    .img-preview { width:100%;height:160px;border-radius:12px;border:2px dashed var(--border); display:flex;align-items:center;justify-content:center; overflow:hidden;cursor:pointer;transition:border-color var(--transition);background:var(--surface);position:relative; }
    .img-preview:hover { border-color:var(--accent); }
    .img-preview img { width:100%;height:100%;object-fit:cover; }
    .img-placeholder { text-align:center;color:var(--text-soft); }
    .img-placeholder i { font-size:28px;margin-bottom:8px;display:block; }
    .img-placeholder p { font-size:12px; }
    .toggle-wrap { display:flex;align-items:center;gap:12px; }
    .toggle { position:relative;width:44px;height:24px; }
    .toggle input { opacity:0;width:0;height:0; }
    .toggle-slider { position:absolute;inset:0;background:var(--border);border-radius:24px;transition:all var(--transition);cursor:pointer; }
    .toggle-slider::before { content:'';position:absolute;width:18px;height:18px;left:3px;top:3px;background:#fff;border-radius:50%;transition:all var(--transition);box-shadow:0 1px 4px rgba(0,0,0,.2); }
    .toggle input:checked + .toggle-slider { background:var(--green); }
    .toggle input:checked + .toggle-slider::before { transform:translateX(20px); }
    .toggle-label { font-size:13px;font-weight:600;color:var(--text-mid); }
    .inner-table { width:100%;border-collapse:collapse;font-size:13px; }
    .inner-table thead tr { background:var(--surface);border-bottom:2px solid var(--border); }
    .inner-table th { padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:var(--text-soft);letter-spacing:.8px;text-transform:uppercase; }
    .inner-table tbody tr { border-bottom:1px solid var(--border);transition:background var(--transition); }
    .inner-table tbody tr:hover { background:#F8FAFF; }
    .inner-table td { padding:11px 14px;vertical-align:middle; }
    .overlay-mob { display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:199;backdrop-filter:blur(2px); }

    @media (max-width:1200px) { .stats-row{grid-template-columns:repeat(2,1fr)} }
    @media (max-width:768px) {
      .sidebar{transform:translateX(-100%)} .sidebar.open{transform:translateX(0)}
      .overlay-mob.open{display:block} .main{margin-left:0}
      .topbar-toggle{display:flex} .content{padding:20px 16px}
      .stats-row{grid-template-columns:1fr 1fr;gap:12px}
      .timeline-grid{grid-template-columns:1fr}
      .toolbar{flex-direction:column;align-items:stretch}
      .form-row-2{grid-template-columns:1fr}
    }
    @media (max-width:480px) { .stats-row{grid-template-columns:1fr} .topbar{padding:0 16px} .admin-name{display:none} }
  </style>
</head>
<body>

<div class="overlay-mob" id="overlay" onclick="closeSidebar()"></div>

<!-- ════════════ SIDEBAR ════════════ -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="brand-icon"><i class="fas fa-graduation-cap"></i></div>
    <div class="brand-text"><h2>SIGMA</h2><span>Admin Panel</span></div>
  </div>
  <div class="sidebar-ukm-card">
    <div class="ukm-avatar"><i class="fas fa-users"></i></div>
    <!-- FIX: id ditambahkan, diisi JS -->
    <div class="ukm-info" id="sidebar-ukm-info">
      <h3>—</h3>
      <span>Periode 2024–2025</span>
    </div>
  </div>
  <div class="sidebar-section-label">Menu Utama</div>
  <nav class="sidebar-nav">
    <a href="dashboard.php"              class="nav-item"><span class="nav-icon"><i class="fas fa-chart-pie"></i></span>Dashboard</a>
    <a href="profile-ukm.php"            class="nav-item"><span class="nav-icon"><i class="fas fa-id-card"></i></span>Profil UKM</a>
    <a href="struktur_organisasi.php"    class="nav-item"><span class="nav-icon"><i class="fas fa-sitemap"></i></span>Struktur Organisasi</a>
    <a href="timeline.php"               class="nav-item active"><span class="nav-icon"><i class="fas fa-calendar-alt"></i></span>Timeline Kegiatan</a>
    <a href="keanggotaan.php"            class="nav-item"><span class="nav-icon"><i class="fas fa-user-group"></i></span>Keanggotaan</a>
    <a href="rapat.php"                  class="nav-item"><span class="nav-icon"><i class="fas fa-comments"></i></span>Rapat</a>
    <div class="sidebar-section-label" style="padding-top:12px;">Pendaftaran</div>
    <a href="manajemen-periode.php"      class="nav-item"><span class="nav-icon"><i class="fas fa-clock-rotate-left"></i></span>Periode Pendaftaran</a>
    <a href="manajemen-pendaftar.php"    class="nav-item"><span class="nav-icon"><i class="fas fa-clipboard-list"></i></span>Manajemen Pendaftar</a>
  </nav>
  <div class="sidebar-footer">
    <button class="logout-btn" onclick="logout()"><span class="nav-icon"><i class="fas fa-arrow-right-from-bracket"></i></span>Keluar</button>
  </div>
</aside>

<!-- ════════════ MAIN ════════════ -->
<div class="main">
  <header class="topbar">
    <button class="topbar-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
    <div class="topbar-breadcrumb">
      <h1>Timeline Kegiatan</h1>
      <p>Kelola jadwal dan kegiatan UKM</p>
    </div>
    <div class="topbar-actions">
      <div class="topbar-btn"><i class="fas fa-bell"></i></div>
      <div class="topbar-admin">
        <div class="admin-avatar">A</div>
        <span class="admin-name">Admin</span>
        <i class="fas fa-chevron-down" style="font-size:10px;color:var(--text-soft);"></i>
      </div>
    </div>
  </header>

  <main class="content">
    <!-- Stats -->
    <div class="stats-row">
      <div class="mini-stat"><div class="mini-icon mi-blue"><i class="fas fa-calendar-days"></i></div><div class="mini-body"><h2 id="stat-total">—</h2><p>Total Kegiatan</p></div></div>
      <div class="mini-stat"><div class="mini-icon mi-green"><i class="fas fa-circle-check"></i></div><div class="mini-body"><h2 id="stat-active">—</h2><p>Aktif</p></div></div>
      <div class="mini-stat"><div class="mini-icon mi-amber"><i class="fas fa-briefcase"></i></div><div class="mini-body"><h2 id="stat-proker">—</h2><p>Program Kerja</p></div></div>
      <div class="mini-stat"><div class="mini-icon mi-purple"><i class="fas fa-calendar-check"></i></div><div class="mini-body"><h2 id="stat-agenda">—</h2><p>Agenda</p></div></div>
    </div>

    <!-- Toolbar -->
    <div class="toolbar">
      <div class="toolbar-left">
        <button class="filter-chip active" onclick="setFilter('all',this)">Semua</button>
        <button class="filter-chip" onclick="setFilter('proker',this)">Program Kerja</button>
        <button class="filter-chip" onclick="setFilter('agenda',this)">Agenda</button>
        <button class="filter-chip" onclick="setFilter('active',this)">Aktif</button>
        <div class="search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" class="search-input" placeholder="Cari kegiatan…" oninput="searchTimeline(this.value)">
        </div>
      </div>
      <button class="btn-primary" onclick="openModal('add')">
        <i class="fas fa-plus"></i> Tambah Kegiatan
      </button>
    </div>

    <!-- Timeline Grid -->
    <div class="timeline-grid" id="timeline-grid">
      <div style="background:var(--card);border-radius:var(--radius);border:1px solid var(--border);height:300px;animation:fadeUp .4s ease;"></div>
      <div style="background:var(--card);border-radius:var(--radius);border:1px solid var(--border);height:300px;animation:fadeUp .4s ease .05s both;"></div>
      <div style="background:var(--card);border-radius:var(--radius);border:1px solid var(--border);height:300px;animation:fadeUp .4s ease .1s both;"></div>
    </div>
  </main>
</div>

<!-- ════════ MODAL: TAMBAH / EDIT KEGIATAN ════════ -->
<div class="modal-overlay" id="modal-kegiatan">
  <div class="modal-box wide">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-header-icon icon-blue"><i class="fas fa-calendar-plus" id="modal-icon-kegiatan"></i></div>
        <div>
          <div class="modal-title" id="modal-title-kegiatan">Tambah Kegiatan</div>
          <div class="modal-subtitle">Isi informasi kegiatan UKM</div>
        </div>
      </div>
      <button class="modal-close" onclick="closeModal('modal-kegiatan')"><i class="fas fa-times"></i></button>
    </div>
    <form id="form-timeline" enctype="multipart/form-data">
      <input type="hidden" id="id_timeline" name="id_timeline">
      <div class="modal-body">
        <div class="form-field">
          <label class="form-label">Foto / Banner</label>
          <div class="img-preview" onclick="document.getElementById('image').click()">
            <div class="img-placeholder" id="img-placeholder">
              <i class="fas fa-cloud-arrow-up"></i>
              <p>Klik untuk upload gambar</p>
            </div>
            <img id="img-preview-src" src="" alt="" style="display:none;">
          </div>
          <input type="file" id="image" name="image" accept="image/*" style="display:none;" onchange="previewImage(this)">
        </div>
        <div class="form-field">
          <label class="form-label">Judul Kegiatan <span>*</span></label>
          <input type="text" class="form-control" id="judul_kegiatan" name="judul_kegiatan" placeholder="Nama kegiatan…" required>
        </div>
        <div class="form-field">
          <label class="form-label">Deskripsi <span>*</span></label>
          <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Deskripsi singkat kegiatan…" required></textarea>
        </div>
        <div class="form-row-2">
          <div class="form-field">
            <label class="form-label">Jenis Kegiatan <span>*</span></label>
            <select class="form-control" id="jenis" name="jenis" required>
              <option value="">Pilih Jenis</option>
              <option value="proker">Program Kerja</option>
              <option value="agenda">Agenda</option>
            </select>
          </div>
          <div class="form-field">
            <label class="form-label">Tanggal Kegiatan <span>*</span></label>
            <input type="date" class="form-control" id="tanggal_kegiatan" name="tanggal_kegiatan" required>
          </div>
        </div>
        <div class="form-row-2">
          <div class="form-field">
            <label class="form-label">Waktu Mulai <span>*</span></label>
            <input type="time" class="form-control" id="waktu_mulai" name="waktu_mulai" required>
          </div>
          <div class="form-field">
            <label class="form-label">Waktu Selesai <span>*</span></label>
            <input type="time" class="form-control" id="waktu_selesai" name="waktu_selesai" required>
          </div>
        </div>
        <div class="form-field">
          <label class="form-label">Status</label>
          <div class="toggle-wrap">
            <label class="toggle">
              <input type="checkbox" id="status" name="status" checked>
              <span class="toggle-slider"></span>
            </label>
            <span class="toggle-label">Aktif</span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-secondary" onclick="closeModal('modal-kegiatan')">Batal</button>
        <button type="submit" class="btn-primary" id="btn-submit-kegiatan">
          <i class="fas fa-save"></i> Simpan Kegiatan
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ════════ MODAL: PANITIA ════════ -->
<div class="modal-overlay" id="modal-panitia">
  <div class="modal-box wide">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-header-icon icon-green"><i class="fas fa-users-gear"></i></div>
        <div>
          <div class="modal-title">Kelola Panitia</div>
          <div class="modal-subtitle" id="panitia-subtitle">Kegiatan —</div>
        </div>
      </div>
      <button class="modal-close" onclick="closeModal('modal-panitia')"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="id_timeline_panitia">
      <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:16px;display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap;">
        <div class="form-field" style="flex:1;min-width:160px;">
          <label class="form-label">Mahasiswa</label>
          <select class="form-control" id="nim_panitia">
            <option value="">Pilih Mahasiswa</option>
          </select>
        </div>
        <div class="form-field" style="flex:1;min-width:140px;">
          <label class="form-label">Jabatan</label>
          <select class="form-control" id="jabatan_panitia">
            <option value="">Pilih Jabatan</option>
          </select>
        </div>
        <button class="btn-primary" onclick="tambahPanitia()" style="height:42px;padding:0 18px;flex-shrink:0;">
          <i class="fas fa-plus"></i> Tambah
        </button>
      </div>
      <div style="overflow-x:auto;margin-top:4px;">
        <table class="inner-table">
          <thead><tr><th>#</th><th>NIM</th><th>Nama</th><th>Jabatan</th><th>Aksi</th></tr></thead>
          <tbody id="tbody-panitia">
            <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--text-soft);">Belum ada panitia</td></tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="modal-footer"><button class="btn-secondary" onclick="closeModal('modal-panitia')">Tutup</button></div>
  </div>
</div>

<!-- ════════ MODAL: RAPAT ════════ -->
<div class="modal-overlay" id="modal-rapat">
  <div class="modal-box wide">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-header-icon icon-amber"><i class="fas fa-comments"></i></div>
        <div>
          <div class="modal-title">Kelola Rapat</div>
          <div class="modal-subtitle" id="rapat-subtitle">Kegiatan —</div>
        </div>
      </div>
      <button class="modal-close" onclick="closeModal('modal-rapat')"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="id_timeline_rapat">
      <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:16px;display:flex;flex-direction:column;gap:12px;">
        <div class="form-row-2">
          <div class="form-field">
            <label class="form-label">Judul Rapat <span>*</span></label>
            <input type="text" class="form-control" id="judul_rapat" placeholder="Judul rapat…">
          </div>
          <div class="form-field">
            <label class="form-label">Tanggal <span>*</span></label>
            <input type="date" class="form-control" id="tanggal_rapat">
          </div>
        </div>
        <div class="form-row-2">
          <div class="form-field">
            <label class="form-label">File Notulensi (PDF)</label>
            <input type="file" class="form-control" id="notulensi" accept=".pdf" style="padding:7px 14px;">
          </div>
          <div class="form-field">
            <label class="form-label">Foto Dokumentasi</label>
            <input type="file" class="form-control" id="dokumentasi" accept="image/*" multiple style="padding:7px 14px;">
          </div>
        </div>
        <div>
          <button class="btn-primary" onclick="tambahRapat()" style="height:40px;padding:0 20px;">
            <i class="fas fa-plus"></i> Tambah Rapat
          </button>
        </div>
      </div>
      <div style="overflow-x:auto;margin-top:4px;">
        <table class="inner-table">
          <thead><tr><th>#</th><th>Judul Rapat</th><th>Tanggal</th><th>Notulensi</th><th>Aksi</th></tr></thead>
          <tbody id="tbody-rapat">
            <tr><td colspan="5" style="text-align:center;padding:24px;color:var(--text-soft);">Belum ada rapat</td></tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="modal-footer"><button class="btn-secondary" onclick="closeModal('modal-rapat')">Tutup</button></div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
  const id_ukm = <?= $id_ukm ?>;

  /* ── SIDEBAR ── */
  function toggleSidebar(){document.getElementById('sidebar').classList.toggle('open');document.getElementById('overlay').classList.toggle('open');}
  function closeSidebar() {document.getElementById('sidebar').classList.remove('open');document.getElementById('overlay').classList.remove('open');}

  /* ── NAMA UKM DI SIDEBAR ── */
  function updateSidebarUkmName(nama){
    const el=document.getElementById('sidebar-ukm-info');
    if(el) el.querySelector('h3').textContent=nama||'—';
  }
  function loadUkmProfile(){
    fetch(`/backend/controllers/admin-ukm/profile.php?id_ukm=${id_ukm}`)
      .then(r=>r.json()).then(d=>updateSidebarUkmName(d.nama_ukm)).catch(()=>{});
  }

  /* ── MODAL ── */
  function closeModal(id){document.getElementById(id).classList.remove('open');}
  document.querySelectorAll('.modal-overlay').forEach(el=>{
    el.addEventListener('click',e=>{if(e.target===el)el.classList.remove('open');});
  });

  /* ── DATA STATE ── */
  let allTimeline=[];
  let activeFilter='all';
  let searchQuery='';

  /* ── LOAD TIMELINE ── */
  function loadTimeline(){
    fetch(`/backend/controllers/admin-ukm/timeline.php?id_ukm=${id_ukm}`)
      .then(r=>r.json())
      .then(data=>{
        allTimeline=Array.isArray(data)?data:[];
        updateStats();
        renderGrid();
      })
      .catch(()=>{
        allTimeline=[
          {id_timeline:1,judul_kegiatan:'WIBU MEET 2025',deskripsi:'Acara gathering para pecinta budaya Jepang di lingkungan kampus.',jenis:'proker',tanggal_kegiatan:'2025-03-15',waktu_mulai:'08:00:00',waktu_selesai:'17:00:00',status:'active',image_path:'',jumlah_panitia:8,jumlah_rapat:3},
          {id_timeline:2,judul_kegiatan:'Open House UKM',deskripsi:'Pengenalan UKM kepada mahasiswa baru angkatan 2025.',jenis:'agenda',tanggal_kegiatan:'2025-03-22',waktu_mulai:'09:00:00',waktu_selesai:'14:00:00',status:'active',image_path:'',jumlah_panitia:5,jumlah_rapat:2},
          {id_timeline:3,judul_kegiatan:'PCC Class Batch 3',deskripsi:'Kelas pelatihan intensif teknologi informasi dan komunikasi.',jenis:'proker',tanggal_kegiatan:'2025-04-01',waktu_mulai:'08:00:00',waktu_selesai:'16:00:00',status:'inactive',image_path:'',jumlah_panitia:12,jumlah_rapat:4},
        ];
        updateStats();
        renderGrid();
      });
  }

  function updateStats(){
    document.getElementById('stat-total').textContent  = allTimeline.length;
    document.getElementById('stat-active').textContent = allTimeline.filter(d=>d.status==='active').length;
    document.getElementById('stat-proker').textContent = allTimeline.filter(d=>d.jenis==='proker').length;
    document.getElementById('stat-agenda').textContent = allTimeline.filter(d=>d.jenis==='agenda').length;
  }

  /* ── FILTER & SEARCH ── */
  function setFilter(val,el){
    activeFilter=val;
    document.querySelectorAll('.filter-chip').forEach(c=>c.classList.remove('active'));
    el.classList.add('active');
    renderGrid();
  }
  function searchTimeline(val){searchQuery=val.toLowerCase();renderGrid();}
  function getFiltered(){
    return allTimeline.filter(d=>{
      const matchFilter=activeFilter==='all'||(activeFilter==='active'&&d.status==='active')||d.jenis===activeFilter;
      const matchSearch=!searchQuery||d.judul_kegiatan?.toLowerCase().includes(searchQuery)||d.deskripsi?.toLowerCase().includes(searchQuery);
      return matchFilter&&matchSearch;
    });
  }

  /* ── RENDER GRID ── */
  function renderGrid(){
    const data=getFiltered();
    const grid=document.getElementById('timeline-grid');
    if(!data.length){
      grid.innerHTML=`<div class="empty-state" style="grid-column:1/-1;"><div class="ei">📅</div><h3>Tidak ada kegiatan</h3><p>Coba ubah filter atau klik "Tambah Kegiatan"</p></div>`;
      return;
    }
    grid.innerHTML=data.map((d,i)=>{
      const tanggal=formatDate(d.tanggal_kegiatan);
      const waktu=`${fmtTime(d.waktu_mulai)} – ${fmtTime(d.waktu_selesai)}`;
      const jenisLabel=d.jenis==='proker'?'Program Kerja':'Agenda';
      const imgSrc=d.image_path?`/frontend/public/assets/${d.image_path}`:'';
      return `
        <div class="tl-card" style="animation-delay:${i*0.04}s;">
          <div class="tl-banner">
            ${imgSrc?`<img src="${imgSrc}" alt="${d.judul_kegiatan}" onerror="this.parentElement.innerHTML='<i class=\\'fas fa-calendar-days\\'></i>'">`: '<i class="fas fa-calendar-days"></i>'}
          </div>
          <div class="tl-body">
            <div class="tl-meta">
              <span class="badge badge-${d.jenis}">${jenisLabel}</span>
              <span class="badge badge-${d.status}">${d.status==='active'?'Aktif':'Nonaktif'}</span>
            </div>
            <div class="tl-title">${d.judul_kegiatan}</div>
            <div class="tl-desc">${d.deskripsi||'—'}</div>
            <div class="tl-info-row">
              <div class="tl-info-item"><i class="fas fa-calendar"></i>${tanggal}</div>
              <div class="tl-info-item"><i class="fas fa-clock"></i>${waktu}</div>
            </div>
          </div>
          <div class="tl-footer">
            <button class="btn-sm btn-panitia" onclick="openPanitia(${d.id_timeline},'${esc(d.judul_kegiatan)}')"><i class="fas fa-users"></i> Panitia (${d.jumlah_panitia||0})</button>
            <button class="btn-sm btn-rapat" onclick="openRapat(${d.id_timeline},'${esc(d.judul_kegiatan)}')"><i class="fas fa-comments"></i> Rapat (${d.jumlah_rapat||0})</button>
            <button class="btn-sm btn-edit" onclick="editTimeline(${d.id_timeline})"><i class="fas fa-pen"></i></button>
            <button class="btn-sm btn-delete" onclick="deleteTimeline(${d.id_timeline})"><i class="fas fa-trash"></i></button>
          </div>
        </div>`;
    }).join('');
  }

  /* ── ADD / EDIT MODAL ── */
  function openModal(mode){
    document.getElementById('id_timeline').value='';
    document.getElementById('form-timeline').reset();
    resetImgPreview();
    if(mode==='add'){
      document.getElementById('modal-title-kegiatan').textContent='Tambah Kegiatan';
      document.getElementById('modal-icon-kegiatan').className='fas fa-calendar-plus';
    }
    document.getElementById('modal-kegiatan').classList.add('open');
  }

  function editTimeline(id){
    fetch(`/backend/controllers/admin-ukm/timeline.php?id_timeline=${id}`)
      .then(r=>r.json())
      .then(d=>{
        document.getElementById('id_timeline').value      = d.id_timeline;
        document.getElementById('judul_kegiatan').value   = d.judul_kegiatan||'';
        document.getElementById('deskripsi').value        = d.deskripsi||'';
        document.getElementById('jenis').value            = d.jenis||'';
        document.getElementById('tanggal_kegiatan').value = d.tanggal_kegiatan||'';
        document.getElementById('waktu_mulai').value      = d.waktu_mulai||'';
        document.getElementById('waktu_selesai').value    = d.waktu_selesai||'';
        document.getElementById('status').checked         = d.status==='active';
        if(d.image_path){
          const preview=document.getElementById('img-preview-src');
          preview.src=`/frontend/public/assets/${d.image_path}`;
          preview.style.display='block';
          document.getElementById('img-placeholder').style.display='none';
        } else { resetImgPreview(); }
        document.getElementById('modal-title-kegiatan').textContent='Edit Kegiatan';
        document.getElementById('modal-icon-kegiatan').className='fas fa-calendar-pen';
        document.getElementById('modal-kegiatan').classList.add('open');
      });
  }

  document.getElementById('form-timeline').addEventListener('submit',function(e){
    e.preventDefault();
    const btn=document.getElementById('btn-submit-kegiatan');
    btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Menyimpan…';
    const fd=new FormData(this);
    fd.append('id_ukm',id_ukm);
    fd.set('status',document.getElementById('status').checked?'active':'inactive');
    fetch('/backend/controllers/admin-ukm/timeline.php',{method:'POST',body:fd})
      .then(r=>r.json())
      .then(res=>{
        if(res.status==='success'){Swal.fire({icon:'success',title:'Berhasil!',text:'Kegiatan berhasil disimpan',timer:1500,showConfirmButton:false});closeModal('modal-kegiatan');loadTimeline();}
        else Swal.fire({icon:'error',title:'Gagal!',text:res.message});
      })
      .catch(()=>Swal.fire({icon:'error',title:'Error',text:'Terjadi kesalahan jaringan'}))
      .finally(()=>{btn.disabled=false;btn.innerHTML='<i class="fas fa-save"></i> Simpan Kegiatan';});
  });

  function deleteTimeline(id){
    Swal.fire({title:'Hapus kegiatan ini?',text:'Semua data panitia dan rapat terkait juga akan dihapus!',icon:'warning',showCancelButton:true,confirmButtonColor:'#F43F5E',cancelButtonColor:'#94A3B8',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'})
      .then(r=>{
        if(r.isConfirmed){
          fetch(`/backend/controllers/admin-ukm/timeline.php?id_timeline=${id}`,{method:'DELETE'})
            .then(r=>r.json())
            .then(res=>{
              if(res.status==='success'){Swal.fire({icon:'success',title:'Terhapus!',timer:1500,showConfirmButton:false});loadTimeline();}
              else Swal.fire({icon:'error',title:'Gagal!',text:res.message});
            });
        }
      });
  }

  /* ── IMAGE PREVIEW ── */
  function previewImage(input){
    if(input.files&&input.files[0]){
      const reader=new FileReader();
      reader.onload=e=>{
        const img=document.getElementById('img-preview-src');
        img.src=e.target.result;img.style.display='block';
        document.getElementById('img-placeholder').style.display='none';
      };
      reader.readAsDataURL(input.files[0]);
    }
  }
  function resetImgPreview(){
    const img=document.getElementById('img-preview-src');
    img.src='';img.style.display='none';
    document.getElementById('img-placeholder').style.display='block';
  }

  /* ── PANITIA ── */
  function openPanitia(id,judul){
    document.getElementById('id_timeline_panitia').value=id;
    document.getElementById('panitia-subtitle').textContent=judul;
    loadPanitia(id);loadMahasiswaDropdown();loadJabatanDropdown();
    document.getElementById('modal-panitia').classList.add('open');
  }
  function loadPanitia(id){
    fetch(`/backend/controllers/admin-ukm/panitia.php?id_timeline=${id}`)
      .then(r=>r.json())
      .then(data=>{
        const tbody=document.getElementById('tbody-panitia');
        if(!data||!data.length){tbody.innerHTML='<tr><td colspan="5" style="text-align:center;padding:24px;color:var(--text-soft);">Belum ada panitia</td></tr>';return;}
        tbody.innerHTML=data.map((p,i)=>`<tr>
          <td style="color:var(--text-soft);font-size:12px;">${i+1}</td>
          <td style="font-family:monospace;font-size:12px;">${p.nim}</td>
          <td style="font-weight:600;">${p.nama_lengkap}</td>
          <td>${p.nama_jabatan}</td>
          <td><button class="btn-sm btn-delete" onclick="deletePanitia(${p.id_panitia})"><i class="fas fa-trash"></i></button></td>
        </tr>`).join('');
      });
  }
  function loadMahasiswaDropdown(){
    fetch(`/backend/controllers/admin-ukm/panitia.php?action=get_mahasiswa&id_ukm=${id_ukm}`)
      .then(r=>r.json()).then(data=>{
        document.getElementById('nim_panitia').innerHTML='<option value="">Pilih Mahasiswa</option>'+(data||[]).map(m=>`<option value="${m.nim}">${m.nim} — ${m.nama_lengkap}</option>`).join('');
      });
  }
  function loadJabatanDropdown(){
    fetch('/backend/controllers/admin-ukm/panitia.php?action=get_jabatan')
      .then(r=>r.json()).then(data=>{
        document.getElementById('jabatan_panitia').innerHTML='<option value="">Pilih Jabatan</option>'+(data||[]).map(j=>`<option value="${j.id_jabatan_panitia}">${j.nama_jabatan}</option>`).join('');
      });
  }
  function tambahPanitia(){
    const nim=document.getElementById('nim_panitia').value;
    const jabatan=document.getElementById('jabatan_panitia').value;
    const id_tl=document.getElementById('id_timeline_panitia').value;
    if(!nim||!jabatan){Swal.fire({icon:'warning',title:'Lengkapi data',text:'Pilih mahasiswa dan jabatan terlebih dahulu.'});return;}
    const fd=new FormData();fd.append('nim',nim);fd.append('id_jabatan_panitia',jabatan);fd.append('id_timeline',id_tl);
    fetch('/backend/controllers/admin-ukm/panitia.php',{method:'POST',body:fd})
      .then(r=>r.json()).then(res=>{
        if(res.status==='success'){loadPanitia(id_tl);loadTimeline();}
        else Swal.fire({icon:'error',title:'Gagal!',text:res.message});
      });
  }
  function deletePanitia(id){
    fetch(`/backend/controllers/admin-ukm/panitia.php?id_panitia=${id}`,{method:'DELETE'})
      .then(r=>r.json()).then(res=>{
        if(res.status==='success'){const id_tl=document.getElementById('id_timeline_panitia').value;loadPanitia(id_tl);loadTimeline();}
      });
  }

  /* ── RAPAT ── */
  function openRapat(id,judul){
    document.getElementById('id_timeline_rapat').value=id;
    document.getElementById('rapat-subtitle').textContent=judul;
    loadRapat(id);
    document.getElementById('modal-rapat').classList.add('open');
  }
  function loadRapat(id){
    fetch(`/backend/controllers/admin-ukm/rapat.php?id_timeline=${id}`)
      .then(r=>r.json()).then(data=>{
        const tbody=document.getElementById('tbody-rapat');
        const list=Array.isArray(data)?data:(data.data||[]);
        if(!list||!list.length){tbody.innerHTML='<tr><td colspan="5" style="text-align:center;padding:24px;color:var(--text-soft);">Belum ada rapat</td></tr>';return;}
        tbody.innerHTML=list.map((r,i)=>`<tr>
          <td style="color:var(--text-soft);font-size:12px;">${i+1}</td>
          <td style="font-weight:600;">${r.judul}</td>
          <td>${formatDate(r.tanggal)}</td>
          <td>${r.notulensi?`<a href="${r.notulensi}" target="_blank" style="color:var(--accent);font-size:12px;font-weight:600;"><i class="fas fa-file-pdf"></i> Lihat</a>`:'—'}</td>
          <td><button class="btn-sm btn-delete" onclick="deleteRapat(${r.id_rapat})"><i class="fas fa-trash"></i></button></td>
        </tr>`).join('');
      });
  }
  function tambahRapat(){
    const judul=document.getElementById('judul_rapat').value;
    const tanggal=document.getElementById('tanggal_rapat').value;
    const id_tl=document.getElementById('id_timeline_rapat').value;
    if(!judul||!tanggal){Swal.fire({icon:'warning',title:'Lengkapi data',text:'Judul dan tanggal wajib diisi.'});return;}
    const fd=new FormData();fd.append('judul',judul);fd.append('tanggal',tanggal);fd.append('id_timeline',id_tl);
    const notulensi=document.getElementById('notulensi').files[0];
    const dok=document.getElementById('dokumentasi').files;
    if(notulensi) fd.append('notulensi',notulensi);
    for(let f of dok) fd.append('dokumentasi[]',f);
    fetch('/backend/controllers/admin-ukm/rapat.php',{method:'POST',body:fd})
      .then(r=>r.json()).then(res=>{
        if(res.status==='success'){
          document.getElementById('judul_rapat').value='';document.getElementById('tanggal_rapat').value='';
          document.getElementById('notulensi').value='';document.getElementById('dokumentasi').value='';
          loadRapat(id_tl);loadTimeline();
        } else Swal.fire({icon:'error',title:'Gagal!',text:res.message});
      });
  }
  function deleteRapat(id){
    fetch(`/backend/controllers/admin-ukm/rapat.php?id_rapat=${id}`,{method:'DELETE'})
      .then(r=>r.json()).then(res=>{
        if(res.status==='success'){const id_tl=document.getElementById('id_timeline_rapat').value;loadRapat(id_tl);loadTimeline();}
      });
  }

  /* ── HELPERS ── */
  function formatDate(str){if(!str)return'—';return new Date(str).toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'});}
  function fmtTime(t){if(!t)return'—';const p=t.split(':');return`${p[0]}:${p[1]}`;}
  function esc(s){return(s||'').replace(/'/g,"\\'");}

  function logout(){
    Swal.fire({title:'Keluar dari SIGMA?',icon:'question',showCancelButton:true,confirmButtonColor:'#F43F5E',cancelButtonColor:'#94A3B8',confirmButtonText:'Ya, keluar',cancelButtonText:'Batal'})
      .then(r=>{if(r.isConfirmed)fetch('/backend/controllers/logout.php').then(()=>window.location.href='/index.html').catch(()=>window.location.href='/index.html');});
  }

  /* ── INIT ── */
  document.addEventListener('DOMContentLoaded', () => {
    loadUkmProfile(); // ← nama UKM → sidebar
    loadTimeline();
  });
</script>
</body>
</html>