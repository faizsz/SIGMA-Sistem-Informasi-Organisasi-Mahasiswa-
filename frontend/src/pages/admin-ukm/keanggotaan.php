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
  <title>Keanggotaan — Admin UKM SIGMA</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
  <style>
    :root {
      --navy:        #0F1B4C;
      --navy-mid:    #161D6F;
      --accent:      #4F6AF0;
      --accent-soft: #e8ecff;
      --teal:        #0EA5A0;
      --amber:       #F59E0B;
      --rose:        #F43F5E;
      --green:       #10B981;
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

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--surface);
      color: var(--text-main);
      display: flex;
      min-height: 100vh;
    }

    /* ── SIDEBAR ── */
    .sidebar {
      width: var(--sidebar-w);
      background: var(--navy);
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0; left: 0; bottom: 0;
      z-index: 200;
      transition: transform var(--transition);
    }

    .sidebar::before {
      content: '';
      position: absolute;
      top: -80px; right: -80px;
      width: 220px; height: 220px;
      background: radial-gradient(circle, rgba(79,106,240,0.18) 0%, transparent 70%);
      pointer-events: none;
    }

    .sidebar-brand {
      padding: 0 24px;
      height: var(--topbar-h);
      display: flex; align-items: center; gap: 12px;
      border-bottom: 1px solid rgba(255,255,255,0.06);
      flex-shrink: 0;
    }

    .brand-icon {
      width: 36px; height: 36px;
      background: linear-gradient(135deg, var(--accent), #7C3AED);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 16px; color: #fff;
      flex-shrink: 0;
      box-shadow: 0 4px 12px rgba(79,106,240,0.4);
    }

    .brand-text h2  { font-size: 16px; font-weight: 800; color: #fff; letter-spacing: 0.5px; line-height: 1.2; }
    .brand-text span{ font-size: 11px; color: rgba(255,255,255,0.45); font-weight: 400; }

    .sidebar-ukm-card {
      margin: 16px 16px 8px;
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 12px;
      padding: 14px 16px;
      display: flex; align-items: center; gap: 12px;
      flex-shrink: 0;
    }

    .ukm-avatar {
      width: 40px; height: 40px;
      border-radius: 10px;
      background: linear-gradient(135deg, var(--teal), #0284C7);
      display: flex; align-items: center; justify-content: center;
      font-size: 18px; color: #fff;
      flex-shrink: 0;
    }

    .ukm-info h3  { font-size: 13px; font-weight: 700; color: #fff; line-height: 1.3; }
    .ukm-info span{ font-size: 11px; color: rgba(255,255,255,0.45); }

    .sidebar-section-label {
      padding: 16px 24px 6px;
      font-size: 10px; font-weight: 700;
      letter-spacing: 1.5px;
      color: rgba(255,255,255,0.25);
      text-transform: uppercase;
      flex-shrink: 0;
    }

    .sidebar-nav {
      flex: 1;
      overflow-y: auto;
      padding: 0 12px 12px;
      scrollbar-width: thin;
      scrollbar-color: rgba(255,255,255,0.1) transparent;
    }

    .nav-item {
      display: flex; align-items: center; gap: 12px;
      padding: 10px 14px;
      border-radius: 10px;
      cursor: pointer;
      transition: all var(--transition);
      text-decoration: none;
      color: rgba(255,255,255,0.55);
      font-size: 14px; font-weight: 500;
      position: relative;
      margin-bottom: 2px;
    }

    .nav-item:hover { background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.9); }

    .nav-item.active {
      background: linear-gradient(135deg, rgba(79,106,240,0.25), rgba(79,106,240,0.12));
      color: #fff;
      border: 1px solid rgba(79,106,240,0.3);
    }

    .nav-item.active::before {
      content: '';
      position: absolute;
      left: 0; top: 50%; transform: translateY(-50%);
      width: 3px; height: 20px;
      background: var(--accent);
      border-radius: 0 3px 3px 0;
    }

    .nav-icon {
      width: 34px; height: 34px;
      border-radius: 8px;
      display: flex; align-items: center; justify-content: center;
      font-size: 14px;
      background: rgba(255,255,255,0.07);
      flex-shrink: 0;
      transition: all var(--transition);
    }

    .nav-item.active .nav-icon { background: var(--accent); color: #fff; box-shadow: 0 4px 12px rgba(79,106,240,0.35); }
    .nav-item:hover  .nav-icon { background: rgba(255,255,255,0.12); color: #fff; }

    .sidebar-footer { padding: 12px; border-top: 1px solid rgba(255,255,255,0.06); flex-shrink: 0; }

    .logout-btn {
      display: flex; align-items: center; gap: 12px;
      width: 100%; padding: 10px 14px;
      border-radius: 10px;
      border: 1px solid rgba(244,63,94,0.2);
      background: rgba(244,63,94,0.07);
      color: rgba(255,100,120,0.8);
      cursor: pointer; font-size: 14px; font-weight: 500;
      transition: all var(--transition);
      font-family: inherit;
    }

    .logout-btn:hover { background: rgba(244,63,94,0.15); color: #F43F5E; border-color: rgba(244,63,94,0.35); }
    .logout-btn .nav-icon { background: rgba(244,63,94,0.12); }

    /* ── MAIN ── */
    .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

    /* ── TOPBAR ── */
    .topbar {
      height: var(--topbar-h);
      background: var(--card);
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center;
      padding: 0 28px; gap: 16px;
      position: sticky; top: 0; z-index: 100;
      box-shadow: var(--shadow-sm);
    }

    .topbar-toggle {
      display: none;
      width: 36px; height: 36px;
      border-radius: 8px;
      background: var(--surface); border: 1px solid var(--border);
      cursor: pointer;
      align-items: center; justify-content: center;
      color: var(--text-mid); font-size: 14px;
    }

    .topbar-breadcrumb { flex: 1; }
    .topbar-breadcrumb h1 { font-size: 18px; font-weight: 700; color: var(--text-main); line-height: 1.2; }
    .topbar-breadcrumb p  { font-size: 12px; color: var(--text-soft); }

    .topbar-actions { display: flex; align-items: center; gap: 10px; }

    .topbar-btn {
      width: 38px; height: 38px;
      border-radius: 10px;
      background: var(--surface); border: 1px solid var(--border);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: var(--text-mid); font-size: 15px;
      transition: all var(--transition);
    }

    .topbar-btn:hover { background: var(--accent-soft); border-color: var(--accent); color: var(--accent); }

    .topbar-admin {
      display: flex; align-items: center; gap: 10px;
      padding: 6px 12px 6px 6px;
      border-radius: 12px;
      background: var(--surface); border: 1px solid var(--border);
      cursor: pointer; transition: all var(--transition);
    }

    .topbar-admin:hover { border-color: var(--accent); }

    .admin-avatar {
      width: 30px; height: 30px; border-radius: 8px;
      background: linear-gradient(135deg, var(--accent), #7C3AED);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 13px; font-weight: 700;
    }

    .admin-name { font-size: 13px; font-weight: 600; color: var(--text-main); }

    /* ── CONTENT ── */
    .content { flex: 1; padding: 28px; }

    /* ── MINI STATS ── */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
      margin-bottom: 24px;
    }

    .mini-stat {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 20px 24px;
      display: flex; align-items: center; gap: 16px;
      box-shadow: var(--shadow-sm);
      transition: all var(--transition);
      animation: fadeUp 0.4s ease both;
    }

    .mini-stat:nth-child(1) { animation-delay: 0.05s; }
    .mini-stat:nth-child(2) { animation-delay: 0.10s; }
    .mini-stat:nth-child(3) { animation-delay: 0.15s; }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(14px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .mini-stat:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }

    .mini-icon {
      width: 48px; height: 48px;
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 20px; flex-shrink: 0;
    }

    .mini-icon.blue  { background: #EEF1FF; color: var(--accent); }
    .mini-icon.green { background: #ECFDF5; color: var(--green); }
    .mini-icon.amber { background: #FFFBEB; color: var(--amber); }

    .mini-body h2 { font-size: 26px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px; line-height: 1; }
    .mini-body p  { font-size: 13px; color: var(--text-soft); margin-top: 4px; font-weight: 500; }

    /* ── CARD ── */
    .card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow-sm);
      overflow: hidden;
      animation: fadeUp 0.4s ease 0.2s both;
    }

    .card-header {
      padding: 20px 24px;
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
      gap: 16px; flex-wrap: wrap;
    }

    .card-title    { font-size: 16px; font-weight: 700; color: var(--text-main); }
    .card-subtitle { font-size: 12px; color: var(--text-soft); margin-top: 2px; }
    .card-actions  { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

    /* ── FILTER BAR ── */
    .filter-bar {
      padding: 16px 24px;
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      display: flex; gap: 12px; align-items: center; flex-wrap: wrap;
    }

    .filter-group { display: flex; flex-direction: column; gap: 4px; }
    .filter-label { font-size: 11px; font-weight: 600; color: var(--text-soft); letter-spacing: 0.5px; text-transform: uppercase; }

    .filter-select, .filter-input {
      padding: 8px 12px;
      border: 1px solid var(--border);
      border-radius: 8px;
      font-size: 13px; font-family: inherit;
      color: var(--text-main); background: var(--card);
      transition: all var(--transition);
      min-width: 150px; outline: none;
    }

    .filter-select:focus, .filter-input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 0 3px rgba(79,106,240,0.10);
    }

    .search-wrapper { position: relative; flex: 1; min-width: 200px; }
    .search-wrapper i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-soft); font-size: 13px; }
    .search-wrapper .filter-input { padding-left: 34px; width: 100%; }

    /* ── TABLE ── */
    .table-wrapper { overflow-x: auto; }

    table { width: 100%; border-collapse: collapse; font-size: 14px; }

    thead tr { background: var(--surface); border-bottom: 2px solid var(--border); }

    thead th {
      padding: 13px 20px;
      text-align: left;
      font-size: 11px; font-weight: 700;
      color: var(--text-soft);
      letter-spacing: 0.8px; text-transform: uppercase;
      white-space: nowrap;
    }

    tbody tr { border-bottom: 1px solid var(--border); transition: background var(--transition); }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #F8FAFF; }

    tbody td { padding: 14px 20px; color: var(--text-main); vertical-align: middle; }

    .td-nim  { font-weight: 600; font-size: 13px; color: var(--text-mid); font-family: monospace; }
    .td-name { font-weight: 600; }
    .td-prodi{ font-size: 13px; color: var(--text-mid); }
    .td-no   { font-size: 12px; color: var(--text-soft); font-weight: 500; width: 48px; }

    .badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .badge-pengurus { background: #EEF1FF; color: var(--accent); }
    .badge-anggota  { background: #ECFDF5; color: var(--green); }

    .btn-actions { display: flex; gap: 8px; }

    .btn-icon {
      width: 32px; height: 32px;
      border-radius: 8px; border: none;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; font-size: 13px;
      transition: all var(--transition);
    }

    .btn-edit  { background: #EEF1FF; color: var(--accent); }
    .btn-delete{ background: #FFF1F2; color: var(--rose); }
    .btn-edit:hover  { background: var(--accent); color: #fff; transform: scale(1.05); }
    .btn-delete:hover{ background: var(--rose);   color: #fff; transform: scale(1.05); }

    .btn-primary {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 9px 18px;
      background: var(--accent); color: #fff;
      border: none; border-radius: 10px;
      font-size: 13px; font-weight: 600;
      cursor: pointer; font-family: inherit;
      transition: all var(--transition);
      box-shadow: 0 4px 12px rgba(79,106,240,0.3);
    }

    .btn-primary:hover { background: #3d59e0; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79,106,240,0.4); }

    .btn-secondary {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 9px 18px;
      background: var(--surface); color: var(--text-mid);
      border: 1px solid var(--border); border-radius: 10px;
      font-size: 13px; font-weight: 600;
      cursor: pointer; font-family: inherit;
      transition: all var(--transition);
    }

    .btn-secondary:hover { background: var(--border); }

    .empty-state { text-align: center; padding: 52px 0; color: var(--text-soft); }
    .empty-state i  { font-size: 40px; margin-bottom: 14px; display: block; opacity: 0.3; }
    .empty-state h3 { font-size: 16px; font-weight: 600; color: var(--text-mid); margin-bottom: 6px; }
    .empty-state p  { font-size: 13px; }

    /* ── PAGINATION ── */
    .table-footer {
      padding: 14px 24px;
      border-top: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
      flex-wrap: wrap; gap: 12px;
    }

    .table-info { font-size: 13px; color: var(--text-soft); }
    .pagination { display: flex; gap: 6px; }

    .page-btn {
      width: 32px; height: 32px;
      border-radius: 8px; border: 1px solid var(--border);
      background: var(--card); color: var(--text-mid);
      font-size: 13px; font-weight: 600;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      transition: all var(--transition); font-family: inherit;
    }

    .page-btn:hover  { border-color: var(--accent); color: var(--accent); background: var(--accent-soft); }
    .page-btn.active { background: var(--accent); color: #fff; border-color: var(--accent); box-shadow: 0 2px 8px rgba(79,106,240,0.35); }
    .page-btn:disabled { opacity: 0.35; cursor: not-allowed; }

    /* ── MODAL ── */
    .modal-overlay {
      display: none; position: fixed; inset: 0;
      background: rgba(15,27,76,0.45);
      z-index: 500; backdrop-filter: blur(4px);
      align-items: center; justify-content: center; padding: 20px;
    }

    .modal-overlay.open { display: flex; }

    .modal-box {
      background: var(--card);
      border-radius: 20px;
      width: 100%; max-width: 480px;
      box-shadow: var(--shadow-lg);
      animation: modalIn 0.25s cubic-bezier(0.34,1.56,0.64,1);
    }

    @keyframes modalIn {
      from { opacity: 0; transform: scale(0.92) translateY(20px); }
      to   { opacity: 1; transform: scale(1) translateY(0); }
    }

    .modal-header {
      padding: 24px 28px 20px;
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center; justify-content: space-between;
    }

    .modal-header-left { display: flex; align-items: center; gap: 14px; }

    .modal-header-icon {
      width: 42px; height: 42px; border-radius: 12px;
      background: var(--accent-soft);
      display: flex; align-items: center; justify-content: center;
      font-size: 18px; color: var(--accent);
    }

    .modal-title    { font-size: 16px; font-weight: 700; color: var(--text-main); }
    .modal-subtitle { font-size: 12px; color: var(--text-soft); margin-top: 2px; }

    .modal-close {
      width: 32px; height: 32px; border-radius: 8px;
      background: var(--surface); border: 1px solid var(--border);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: var(--text-soft); font-size: 16px;
      transition: all var(--transition);
    }

    .modal-close:hover { background: #FFF1F2; color: var(--rose); border-color: var(--rose); }

    .modal-body   { padding: 24px 28px; display: flex; flex-direction: column; gap: 18px; }
    .modal-footer { padding: 16px 28px 24px; display: flex; gap: 10px; justify-content: flex-end; }

    .form-field { display: flex; flex-direction: column; gap: 6px; }

    .form-label {
      font-size: 12px; font-weight: 700;
      color: var(--text-mid);
      letter-spacing: 0.4px; text-transform: uppercase;
    }

    .form-label span { color: var(--rose); margin-left: 2px; }

    .form-control {
      padding: 10px 14px;
      border: 1.5px solid var(--border); border-radius: 10px;
      font-size: 14px; font-family: inherit;
      color: var(--text-main); background: var(--surface);
      transition: all var(--transition); outline: none;
    }

    .form-control:focus {
      border-color: var(--accent); background: var(--card);
      box-shadow: 0 0 0 3px rgba(79,106,240,0.12);
    }

    .form-control:disabled { opacity: 0.6; cursor: not-allowed; }

    /* ── RESPONSIVE ── */
    .overlay-mob {
      display: none; position: fixed; inset: 0;
      background: rgba(0,0,0,0.45);
      z-index: 199; backdrop-filter: blur(2px);
    }

    @media (max-width: 768px) {
      .sidebar { transform: translateX(-100%); }
      .sidebar.open { transform: translateX(0); }
      .overlay-mob.open { display: block; }
      .main { margin-left: 0; }
      .topbar-toggle { display: flex; }
      .content { padding: 20px 16px; }
      .stats-row { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 480px) {
      .stats-row { grid-template-columns: 1fr; }
      .topbar { padding: 0 16px; }
      .admin-name { display: none; }
      .card-header { flex-direction: column; align-items: flex-start; }
    }
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
    <!-- id diganti ke sidebar-ukm-info agar konsisten dengan halaman lain -->
    <div class="ukm-info" id="sidebar-ukm-info">
      <h3>—</h3>
      <span>Periode 2024–2025</span>
    </div>
  </div>

  <div class="sidebar-section-label">Menu Utama</div>
  <nav class="sidebar-nav">
    <a href="dashboard.php"           class="nav-item"><span class="nav-icon"><i class="fas fa-chart-pie"></i></span>Dashboard</a>
    <a href="profile-ukm.php"         class="nav-item"><span class="nav-icon"><i class="fas fa-id-card"></i></span>Profil UKM</a>
    <a href="struktur_organisasi.php" class="nav-item"><span class="nav-icon"><i class="fas fa-sitemap"></i></span>Struktur Organisasi</a>
    <a href="timeline.php"            class="nav-item"><span class="nav-icon"><i class="fas fa-calendar-alt"></i></span>Timeline Kegiatan</a>
    <a href="keanggotaan.php"         class="nav-item active"><span class="nav-icon"><i class="fas fa-user-group"></i></span>Keanggotaan</a>
    <a href="rapat.php"               class="nav-item"><span class="nav-icon"><i class="fas fa-comments"></i></span>Rapat</a>
    <div class="sidebar-section-label" style="padding-top:12px;">Pendaftaran</div>
    <a href="manajemen-periode.php"   class="nav-item"><span class="nav-icon"><i class="fas fa-clock-rotate-left"></i></span>Periode Pendaftaran</a>
    <a href="manajemen-pendaftar.php" class="nav-item"><span class="nav-icon"><i class="fas fa-clipboard-list"></i></span>Manajemen Pendaftar</a>
  </nav>

  <div class="sidebar-footer">
    <button class="logout-btn" onclick="logout()">
      <span class="nav-icon"><i class="fas fa-arrow-right-from-bracket"></i></span>Keluar
    </button>
  </div>
</aside>

<!-- ════════════ MAIN ════════════ -->
<div class="main">
  <header class="topbar">
    <button class="topbar-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
    <div class="topbar-breadcrumb">
      <h1>Keanggotaan</h1>
      <p>Kelola data anggota dan pengurus UKM</p>
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

    <!-- Mini Stats -->
    <div class="stats-row">
      <div class="mini-stat">
        <div class="mini-icon blue"><i class="fas fa-users"></i></div>
        <div class="mini-body"><h2 id="stat-total">—</h2><p>Total Anggota</p></div>
      </div>
      <div class="mini-stat">
        <div class="mini-icon green"><i class="fas fa-user-tie"></i></div>
        <div class="mini-body"><h2 id="stat-pengurus">—</h2><p>Pengurus</p></div>
      </div>
      <div class="mini-stat">
        <div class="mini-icon amber"><i class="fas fa-user-check"></i></div>
        <div class="mini-body"><h2 id="stat-anggota">—</h2><p>Anggota Aktif</p></div>
      </div>
    </div>

    <!-- Main Table Card -->
    <div class="card">
      <div class="card-header">
        <div>
          <div class="card-title">Data Anggota UKM</div>
          <div class="card-subtitle">Semua anggota dan pengurus yang terdaftar</div>
        </div>
        <div class="card-actions">
          <button class="btn-primary" onclick="openModal()">
            <i class="fas fa-plus"></i> Tambah Anggota
          </button>
        </div>
      </div>

      <!-- Filter Bar -->
      <div class="filter-bar">
        <div class="filter-group">
          <span class="filter-label">Status</span>
          <select class="filter-select" id="filter-status">
            <option value="">Semua Status</option>
            <option value="anggota">Anggota</option>
            <option value="pengurus">Pengurus</option>
          </select>
        </div>
        <div class="filter-group">
          <span class="filter-label">Periode</span>
          <select class="filter-select" id="filter-periode">
            <option value="">Semua Periode</option>
          </select>
        </div>
        <div class="filter-group" style="flex:1;">
          <span class="filter-label">Cari</span>
          <div class="search-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" class="filter-input" id="search-box" placeholder="Cari NIM atau nama...">
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th class="td-no">#</th>
              <th>NIM</th>
              <th>Nama Lengkap</th>
              <th>Program Studi</th>
              <th>Status</th>
              <th>Periode</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="table-body">
            <tr>
              <td colspan="7">
                <div class="empty-state">
                  <i class="fas fa-spinner fa-spin"></i>
                  <h3>Memuat data…</h3>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="table-footer">
        <span class="table-info" id="table-info">Memuat…</span>
        <div class="pagination" id="pagination"></div>
      </div>
    </div>

  </main>
</div>

<!-- ════════════ MODAL ════════════ -->
<div class="modal-overlay" id="modal-overlay">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-header-icon"><i class="fas fa-user-plus" id="modal-icon"></i></div>
        <div>
          <div class="modal-title" id="modal-title">Tambah Anggota</div>
          <div class="modal-subtitle" id="modal-subtitle">Isi data anggota baru</div>
        </div>
      </div>
      <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
    </div>

    <form id="form-keanggotaan">
      <input type="hidden" id="id_keanggotaan" name="id_keanggotaan">
      <input type="hidden" name="id_ukm" value="<?= $id_ukm ?>">

      <div class="modal-body">
        <div class="form-field">
          <label class="form-label">Mahasiswa <span>*</span></label>
          <select class="form-control" id="nim" name="nim" required>
            <option value="">Pilih Mahasiswa</option>
          </select>
        </div>
        <div class="form-field">
          <label class="form-label">Status <span>*</span></label>
          <select class="form-control" id="status" name="status" required>
            <option value="anggota">Anggota</option>
            <option value="pengurus">Pengurus</option>
          </select>
        </div>
        <div class="form-field">
          <label class="form-label">Periode <span>*</span></label>
          <select class="form-control" id="id_periode" name="id_periode" required>
            <option value="">Pilih Periode</option>
          </select>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
        <button type="submit" class="btn-primary" id="btn-submit">
          <i class="fas fa-save"></i> <span id="btn-submit-text">Simpan</span>
        </button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
  const id_ukm = <?= $id_ukm ?>;

  /* ── SIDEBAR ── */
  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('open');
  }
  function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('overlay').classList.remove('open');
  }

  /* ── UPDATE NAMA UKM DI SIDEBAR ── */
  function updateSidebarUkmName(nama) {
    const el = document.getElementById('sidebar-ukm-info');
    if (el) el.querySelector('h3').textContent = nama || '—';
  }

  /* ── FETCH NAMA UKM DARI PROFILE ── */
  function loadUkmProfile() {
    fetch(`/backend/controllers/admin-ukm/profile.php?id_ukm=${id_ukm}`)
      .then(r => r.json())
      .then(data => updateSidebarUkmName(data.nama_ukm))
      .catch(() => updateSidebarUkmName('UKM'));
  }

  /* ── MODAL ── */
  function openModal(mode = 'add') {
    document.getElementById('modal-overlay').classList.add('open');
    if (mode === 'add') {
      document.getElementById('modal-title').textContent    = 'Tambah Anggota';
      document.getElementById('modal-subtitle').textContent = 'Isi data anggota baru';
      document.getElementById('modal-icon').className       = 'fas fa-user-plus';
      document.getElementById('btn-submit-text').textContent= 'Simpan';
      resetForm();
      loadMahasiswaDropdown();
    }
  }

  function closeModal() {
    document.getElementById('modal-overlay').classList.remove('open');
    resetForm();
  }

  document.getElementById('modal-overlay').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
  });

  /* ── TABLE ── */
  let allData = [];
  let currentPage = 1;
  const perPage = 10;

  function renderTable(data) {
    allData = data;
    currentPage = 1;
    updateStats(data);
    renderPage();
  }

  function updateStats(data) {
    document.getElementById('stat-total').textContent    = data.length;
    document.getElementById('stat-pengurus').textContent = data.filter(d => d.status === 'pengurus').length;
    document.getElementById('stat-anggota').textContent  = data.filter(d => d.status === 'anggota').length;
  }

  function renderPage() {
    const tbody = document.getElementById('table-body');
    const start = (currentPage - 1) * perPage;
    const slice = allData.slice(start, start + perPage);

    if (allData.length === 0) {
      tbody.innerHTML = `
        <tr><td colspan="7">
          <div class="empty-state">
            <i class="fas fa-users-slash"></i>
            <h3>Belum ada anggota</h3>
            <p>Klik tombol "Tambah Anggota" untuk menambahkan</p>
          </div>
        </td></tr>`;
      document.getElementById('table-info').textContent = '0 data ditemukan';
      document.getElementById('pagination').innerHTML = '';
      return;
    }

    tbody.innerHTML = slice.map((item, i) => `
      <tr>
        <td class="td-no">${start + i + 1}</td>
        <td class="td-nim">${item.nim || '—'}</td>
        <td class="td-name">${item.nama_lengkap || '—'}</td>
        <td class="td-prodi">${item.nama_program_studi || '—'}</td>
        <td>
          <span class="badge badge-${item.status === 'pengurus' ? 'pengurus' : 'anggota'}">
            <i class="fas fa-${item.status === 'pengurus' ? 'star' : 'circle-check'}" style="font-size:10px;"></i>
            ${item.status}
          </span>
        </td>
        <td>${item.periode || '—'}</td>
        <td>
          <div class="btn-actions">
            <button class="btn-icon btn-edit"   onclick="editAnggota(${item.id_keanggotaan})"   title="Edit"><i class="fas fa-pen"></i></button>
            <button class="btn-icon btn-delete" onclick="deleteAnggota(${item.id_keanggotaan})" title="Hapus"><i class="fas fa-trash"></i></button>
          </div>
        </td>
      </tr>
    `).join('');

    const totalPages = Math.ceil(allData.length / perPage);
    const endNum     = Math.min(start + perPage, allData.length);
    document.getElementById('table-info').textContent = `Menampilkan ${start + 1}–${endNum} dari ${allData.length} data`;
    renderPagination(totalPages);
  }

  function renderPagination(total) {
    const el = document.getElementById('pagination');
    if (total <= 1) { el.innerHTML = ''; return; }
    let html = `<button class="page-btn" onclick="goPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}><i class="fas fa-chevron-left" style="font-size:11px;"></i></button>`;
    for (let p = 1; p <= total; p++) {
      html += `<button class="page-btn ${p === currentPage ? 'active' : ''}" onclick="goPage(${p})">${p}</button>`;
    }
    html += `<button class="page-btn" onclick="goPage(${currentPage + 1})" ${currentPage === total ? 'disabled' : ''}><i class="fas fa-chevron-right" style="font-size:11px;"></i></button>`;
    el.innerHTML = html;
  }

  function goPage(p) {
    const total = Math.ceil(allData.length / perPage);
    if (p < 1 || p > total) return;
    currentPage = p;
    renderPage();
  }

  /* ── FETCH DATA ── */
  function loadAnggota(status = '', periode = '', search = '') {
    const url = `/backend/controllers/admin-ukm/keanggotaan.php?status=${status}&periode=${periode}&search=${encodeURIComponent(search)}`;
    fetch(url)
      .then(r => r.json())
      .then(res => {
        if (res.status === 'success') renderTable(res.data);
        else showError(res.message);
      })
      .catch(() => showError('Gagal memuat data anggota'));
  }

  function loadPeriodeDropdown() {
    fetch('/backend/controllers/admin-ukm/keanggotaan.php?action=get_periode')
      .then(r => r.json())
      .then(res => {
        if (res.status === 'success') {
          const opts = res.data.map(p => `<option value="${p.id_periode}">${p.tahun_mulai} – ${p.tahun_selesai}</option>`).join('');
          document.getElementById('filter-periode').innerHTML = '<option value="">Semua Periode</option>' + opts;
          document.getElementById('id_periode').innerHTML     = '<option value="">Pilih Periode</option>'  + opts;
        }
      });
  }

  function loadMahasiswaDropdown() {
    fetch('/backend/controllers/admin-ukm/keanggotaan.php?action=get_mahasiswa')
      .then(r => r.json())
      .then(res => {
        if (res.status === 'success') {
          const opts = res.data.map(m => `<option value="${m.nim}">${m.nim} — ${m.nama_lengkap}</option>`).join('');
          document.getElementById('nim').innerHTML = '<option value="">Pilih Mahasiswa</option>' + opts;
          document.getElementById('nim').disabled  = false;
        }
      });
  }

  /* ── EDIT ── */
  function editAnggota(id) {
    fetch(`/backend/controllers/admin-ukm/keanggotaan.php?id_keanggotaan=${id}`)
      .then(r => r.json())
      .then(res => {
        if (res.status === 'success') {
          const d = res.data;
          document.getElementById('id_keanggotaan').value = d.id_keanggotaan;
          document.getElementById('status').value         = d.status;
          document.getElementById('id_periode').value     = d.id_periode;
          const nimSel = document.getElementById('nim');
          nimSel.innerHTML = `<option value="${d.nim}">${d.nim} — ${d.nama_lengkap}</option>`;
          nimSel.disabled  = true;
          document.getElementById('modal-title').textContent    = 'Edit Anggota';
          document.getElementById('modal-subtitle').textContent = 'Ubah data anggota';
          document.getElementById('modal-icon').className       = 'fas fa-user-pen';
          document.getElementById('btn-submit-text').textContent= 'Update';
          document.getElementById('modal-overlay').classList.add('open');
        }
      });
  }

  /* ── DELETE ── */
  function deleteAnggota(id) {
    Swal.fire({
      title: 'Hapus anggota ini?',
      text: 'Data akan dihapus secara permanen.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#F43F5E',
      cancelButtonColor: '#94A3B8',
      confirmButtonText: 'Ya, hapus',
      cancelButtonText: 'Batal'
    }).then(result => {
      if (result.isConfirmed) {
        fetch(`/backend/controllers/admin-ukm/keanggotaan.php?id_keanggotaan=${id}`, { method: 'DELETE' })
          .then(r => r.json())
          .then(res => {
            if (res.status === 'success') {
              Swal.fire({ icon: 'success', title: 'Terhapus!', text: res.message, timer: 1500, showConfirmButton: false });
              loadAnggota();
            } else {
              showError(res.message);
            }
          });
      }
    });
  }

  /* ── FORM SUBMIT ── */
  document.getElementById('form-keanggotaan').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-submit');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan…';

    const formData = new FormData(this);
    formData.append('id_ukm', id_ukm);

    fetch('/backend/controllers/admin-ukm/keanggotaan.php', { method: 'POST', body: formData })
      .then(r => r.json())
      .then(res => {
        if (res.status === 'success') {
          Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, timer: 1500, showConfirmButton: false });
          closeModal();
          loadAnggota();
        } else {
          showError(res.message);
        }
      })
      .catch(() => showError('Terjadi kesalahan jaringan'))
      .finally(() => {
        btn.disabled  = false;
        btn.innerHTML = '<i class="fas fa-save"></i> <span id="btn-submit-text">Simpan</span>';
      });
  });

  /* ── FILTERS ── */
  let filterTimeout;
  function applyFilters() {
    clearTimeout(filterTimeout);
    filterTimeout = setTimeout(() => {
      loadAnggota(
        document.getElementById('filter-status').value,
        document.getElementById('filter-periode').value,
        document.getElementById('search-box').value
      );
    }, 300);
  }

  document.getElementById('filter-status').addEventListener('change', applyFilters);
  document.getElementById('filter-periode').addEventListener('change', applyFilters);
  document.getElementById('search-box').addEventListener('input', applyFilters);

  /* ── UTILS ── */
  function resetForm() {
    document.getElementById('form-keanggotaan').reset();
    document.getElementById('id_keanggotaan').value = '';
    document.getElementById('nim').disabled = false;
  }

  function showError(msg) {
    Swal.fire({ icon: 'error', title: 'Gagal!', text: msg || 'Terjadi kesalahan' });
  }

  function logout() {
    Swal.fire({
      title: 'Keluar dari SIGMA?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#F43F5E',
      cancelButtonColor: '#94A3B8',
      confirmButtonText: 'Ya, keluar',
      cancelButtonText: 'Batal'
    }).then(r => {
      if (r.isConfirmed) {
        fetch('/backend/controllers/logout.php')
          .then(() => window.location.href = '/index.html')
          .catch(() => window.location.href = '/index.html');
      }
    });
  }

  /* ── INIT ── */
  document.addEventListener('DOMContentLoaded', () => {
    loadUkmProfile();      // ← ambil nama UKM → tampil di sidebar
    loadAnggota();
    loadPeriodeDropdown();
  });
</script>
</body>
</html>