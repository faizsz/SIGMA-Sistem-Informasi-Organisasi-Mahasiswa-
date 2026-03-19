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
  <title>Dashboard — Admin UKM SIGMA</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
  <style>
    :root {
      --navy:        #0F1B4C;
      --navy-mid:    #161D6F;
      --navy-light:  #1e2d8f;
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
      overflow-x: hidden;
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
      overflow: hidden;
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
      display: flex;
      align-items: center;
      gap: 12px;
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

    .brand-text h2 {
      font-size: 16px; font-weight: 800;
      color: #fff; letter-spacing: 0.5px; line-height: 1.2;
    }
    .brand-text span {
      font-size: 11px; color: rgba(255,255,255,0.45); font-weight: 400;
    }

    .sidebar-ukm-card {
      margin: 16px 16px 8px;
      background: rgba(255,255,255,0.06);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 12px;
      padding: 14px 16px;
      display: flex;
      align-items: center;
      gap: 12px;
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

    .ukm-info h3 { font-size: 13px; font-weight: 700; color: #fff; line-height: 1.3; }
    .ukm-info span { font-size: 11px; color: rgba(255,255,255,0.45); }

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
      display: flex;
      align-items: center;
      gap: 12px;
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
      left: 0; top: 50%;
      transform: translateY(-50%);
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
    .nav-item:hover .nav-icon  { background: rgba(255,255,255,0.12); color: #fff; }

    .sidebar-footer {
      padding: 12px;
      border-top: 1px solid rgba(255,255,255,0.06);
      flex-shrink: 0;
    }

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
      background: var(--surface);
      border: 1px solid var(--border);
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
      background: var(--surface);
      border: 1px solid var(--border);
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; color: var(--text-mid); font-size: 15px;
      transition: all var(--transition);
      position: relative;
    }
    .topbar-btn:hover { background: var(--accent-soft); border-color: var(--accent); color: var(--accent); }

    .notif-badge {
      position: absolute; top: -4px; right: -4px;
      width: 16px; height: 16px;
      background: var(--rose); border-radius: 50%;
      font-size: 9px; color: #fff;
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; border: 2px solid var(--card);
    }

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

    /* ── STAT CARDS ── */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 20px;
      margin-bottom: 28px;
    }

    .stat-card {
      background: var(--card);
      border-radius: var(--radius);
      padding: 24px;
      border: 1px solid var(--border);
      box-shadow: var(--shadow-sm);
      position: relative; overflow: hidden;
      transition: all var(--transition);
      animation: fadeUp 0.5s ease both;
    }

    .stat-card:nth-child(1) { animation-delay: 0.05s; }
    .stat-card:nth-child(2) { animation-delay: 0.10s; }
    .stat-card:nth-child(3) { animation-delay: 0.15s; }
    .stat-card:nth-child(4) { animation-delay: 0.20s; }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(16px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); border-color: transparent; }

    .stat-card::after {
      content: ''; position: absolute;
      top: 0; left: 0; right: 0; height: 3px;
      border-radius: var(--radius) var(--radius) 0 0;
    }
    .stat-card.blue::after  { background: linear-gradient(90deg, var(--accent), #7C3AED); }
    .stat-card.green::after { background: linear-gradient(90deg, var(--green), #0EA5E9); }
    .stat-card.amber::after { background: linear-gradient(90deg, var(--amber), #F97316); }
    .stat-card.rose::after  { background: linear-gradient(90deg, var(--rose), #EC4899); }

    .stat-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px; }

    .stat-icon { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
    .stat-card.blue  .stat-icon { background: #EEF1FF; color: var(--accent); }
    .stat-card.green .stat-icon { background: #ECFDF5; color: var(--green); }
    .stat-card.amber .stat-icon { background: #FFFBEB; color: var(--amber); }
    .stat-card.rose  .stat-icon { background: #FFF1F2; color: var(--rose); }

    .stat-trend {
      display: flex; align-items: center; gap: 4px;
      font-size: 12px; font-weight: 600;
      padding: 4px 8px; border-radius: 20px;
    }
    .trend-up   { background: #ECFDF5; color: var(--green); }
    .trend-down { background: #FFF1F2; color: var(--rose); }

    .stat-number { font-size: 32px; font-weight: 800; color: var(--text-main); line-height: 1; margin-bottom: 6px; letter-spacing: -1px; }
    .stat-label  { font-size: 13px; font-weight: 500; color: var(--text-soft); }

    /* ── BOTTOM GRID ── */
    .bottom-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

    .panel {
      background: var(--card);
      border-radius: var(--radius);
      border: 1px solid var(--border);
      box-shadow: var(--shadow-sm);
      overflow: hidden;
      animation: fadeUp 0.5s ease 0.25s both;
    }

    .panel-header {
      padding: 20px 24px 16px;
      display: flex; align-items: center; justify-content: space-between;
      border-bottom: 1px solid var(--border);
    }

    .panel-title    { font-size: 15px; font-weight: 700; color: var(--text-main); }
    .panel-subtitle { font-size: 12px; color: var(--text-soft); font-weight: 400; margin-top: 2px; }
    .panel-badge    { font-size: 12px; font-weight: 600; padding: 4px 12px; border-radius: 20px; background: var(--accent-soft); color: var(--accent); }

    .panel-body { padding: 16px 24px; }

    /* Event list */
    .event-item {
      display: flex; align-items: flex-start; gap: 14px;
      padding: 14px 0; border-bottom: 1px solid var(--border);
      transition: background var(--transition);
    }
    .event-item:last-child  { border-bottom: none; padding-bottom: 0; }
    .event-item:first-child { padding-top: 0; }

    .event-dot { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; margin-top: 2px; }
    .dot-blue  { background: #EEF1FF; color: var(--accent); }
    .dot-teal  { background: #F0FDFD; color: var(--teal); }
    .dot-amber { background: #FFFBEB; color: var(--amber); }

    .event-info h4 { font-size: 14px; font-weight: 600; color: var(--text-main); margin-bottom: 4px; line-height: 1.4; }
    .event-info p  { font-size: 12px; color: var(--text-soft); display: flex; align-items: center; gap: 6px; }

    .event-date { margin-left: auto; text-align: right; flex-shrink: 0; }
    .date-badge { display: inline-block; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; background: var(--surface); color: var(--text-mid); border: 1px solid var(--border); }

    /* Meeting list */
    .meeting-item { display: flex; align-items: center; gap: 14px; padding: 12px 0; border-bottom: 1px solid var(--border); }
    .meeting-item:last-child  { border-bottom: none; padding-bottom: 0; }
    .meeting-item:first-child { padding-top: 0; }

    .meeting-num { width: 28px; height: 28px; border-radius: 8px; background: var(--accent-soft); color: var(--accent); font-size: 12px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .meeting-info { flex: 1; }
    .meeting-info h4 { font-size: 13px; font-weight: 600; color: var(--text-main); margin-bottom: 3px; }
    .meeting-info p  { font-size: 11px; color: var(--text-soft); }
    .meeting-status  { font-size: 11px; font-weight: 600; padding: 3px 10px; border-radius: 20px; }
    .status-done     { background: #ECFDF5; color: var(--green); }

    /* Empty state */
    .empty-state { text-align: center; padding: 32px 0; color: var(--text-soft); }
    .empty-state i { font-size: 36px; margin-bottom: 12px; opacity: 0.35; display: block; }
    .empty-state p { font-size: 13px; }

    /* Shimmer */
    .shimmer {
      background: linear-gradient(90deg, var(--surface) 25%, var(--border) 50%, var(--surface) 75%);
      background-size: 200% 100%;
      animation: shimmer 1.5s infinite;
      border-radius: 8px; height: 16px;
    }
    @keyframes shimmer {
      0%   { background-position: 200% 0; }
      100% { background-position: -200% 0; }
    }

    /* Overlay mobile */
    .sidebar-overlay {
      display: none; position: fixed; inset: 0;
      background: rgba(0,0,0,0.45);
      z-index: 199; backdrop-filter: blur(2px);
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 900px)  { .bottom-grid { grid-template-columns: 1fr; } }
    @media (max-width: 768px) {
      .sidebar { transform: translateX(-100%); }
      .sidebar.open { transform: translateX(0); }
      .sidebar-overlay.open { display: block; }
      .main { margin-left: 0; }
      .topbar-toggle { display: flex; }
      .content { padding: 20px 16px; }
      .stats-grid { grid-template-columns: 1fr 1fr; gap: 14px; }
      .stat-number { font-size: 26px; }
    }
    @media (max-width: 480px) {
      .stats-grid { grid-template-columns: 1fr; }
      .topbar { padding: 0 16px; }
      .admin-name { display: none; }
    }
  </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- ════════════ SIDEBAR ════════════ -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="brand-icon"><i class="fas fa-graduation-cap"></i></div>
    <div class="brand-text"><h2>SIGMA</h2><span>Admin Panel</span></div>
  </div>

  <div class="sidebar-ukm-card">
    <div class="ukm-avatar"><i class="fas fa-users"></i></div>
    <div class="ukm-info" id="sidebar-ukm-info">
      <h3>—</h3>
      <span>Periode 2024–2025</span>
    </div>
  </div>

  <div class="sidebar-section-label">Menu Utama</div>
  <nav class="sidebar-nav">
    <a href="dashboard.php"           class="nav-item active"><span class="nav-icon"><i class="fas fa-chart-pie"></i></span>Dashboard</a>
    <a href="profile-ukm.php"         class="nav-item"><span class="nav-icon"><i class="fas fa-id-card"></i></span>Profil UKM</a>
    <a href="struktur_organisasi.php" class="nav-item"><span class="nav-icon"><i class="fas fa-sitemap"></i></span>Struktur Organisasi</a>
    <a href="timeline.php"            class="nav-item"><span class="nav-icon"><i class="fas fa-calendar-alt"></i></span>Timeline Kegiatan</a>
    <a href="keanggotaan.php"         class="nav-item"><span class="nav-icon"><i class="fas fa-user-group"></i></span>Keanggotaan</a>
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
      <h1>Dashboard</h1>
      <p>Selamat datang kembali 👋</p>
    </div>
    <div class="topbar-actions">
      <div class="topbar-btn" title="Notifikasi">
        <i class="fas fa-bell"></i>
        <span class="notif-badge">3</span>
      </div>
      <div class="topbar-admin">
        <div class="admin-avatar" id="admin-initial">A</div>
        <span class="admin-name" id="admin-nama">Admin</span>
        <i class="fas fa-chevron-down" style="font-size:10px;color:var(--text-soft);"></i>
      </div>
    </div>
  </header>

  <main class="content">

    <div class="stats-grid">
      <div class="stat-card blue">
        <div class="stat-top">
          <div class="stat-icon"><i class="fas fa-users"></i></div>
          <div class="stat-trend trend-up"><i class="fas fa-arrow-up"></i> 12%</div>
        </div>
        <div class="stat-number" id="total-anggota">—</div>
        <div class="stat-label">Total Anggota</div>
      </div>
      <div class="stat-card green">
        <div class="stat-top">
          <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
          <div class="stat-trend trend-up"><i class="fas fa-arrow-up"></i> 5%</div>
        </div>
        <div class="stat-number" id="total-kegiatan">—</div>
        <div class="stat-label">Kegiatan Aktif</div>
      </div>
      <div class="stat-card amber">
        <div class="stat-top">
          <div class="stat-icon"><i class="fas fa-user-plus"></i></div>
          <div class="stat-trend trend-up"><i class="fas fa-arrow-up"></i> 8%</div>
        </div>
        <div class="stat-number" id="total-pendaftar">—</div>
        <div class="stat-label">Pendaftar Baru</div>
      </div>
      <div class="stat-card rose">
        <div class="stat-top">
          <div class="stat-icon"><i class="fas fa-comments"></i></div>
          <div class="stat-trend trend-down"><i class="fas fa-arrow-down"></i> 2%</div>
        </div>
        <div class="stat-number" id="total-rapat">—</div>
        <div class="stat-label">Total Rapat</div>
      </div>
    </div>

    <div class="bottom-grid">
      <div class="panel">
        <div class="panel-header">
          <div>
            <div class="panel-title">Kegiatan Mendatang</div>
            <div class="panel-subtitle">Jadwal kegiatan yang akan datang</div>
          </div>
          <span class="panel-badge" id="event-count">Loading…</span>
        </div>
        <div class="panel-body" id="upcoming-events">
          <div class="shimmer" style="margin-bottom:12px;"></div>
          <div class="shimmer" style="width:80%;margin-bottom:12px;"></div>
          <div class="shimmer" style="width:60%;"></div>
        </div>
      </div>

      <div class="panel">
        <div class="panel-header">
          <div>
            <div class="panel-title">Rapat Dilaksanakan</div>
            <div class="panel-subtitle">Rapat yang sudah selesai</div>
          </div>
          <span class="panel-badge" id="rapat-count">Loading…</span>
        </div>
        <div class="panel-body" id="latest-meetings">
          <div class="shimmer" style="margin-bottom:12px;"></div>
          <div class="shimmer" style="width:80%;margin-bottom:12px;"></div>
          <div class="shimmer" style="width:60%;"></div>
        </div>
      </div>
    </div>

  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
  // ── id_ukm dari PHP session ──
  const id_ukm = <?= $id_ukm ?>;

  /* ── SIDEBAR TOGGLE ── */
  function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('open');
  }
  function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('open');
  }

  /* ── ANIMATE NUMBER ── */
  function animateCount(el, target) {
    if (isNaN(target)) { el.textContent = target; return; }
    let start = 0;
    const duration = 800;
    const step = (timestamp) => {
      if (!start) start = timestamp;
      const progress = Math.min((timestamp - start) / duration, 1);
      el.textContent = Math.floor(progress * target);
      if (progress < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  }

  /* ── RENDER EVENTS ── */
  function renderEvents(data) {
    const el = document.getElementById('upcoming-events');
    const icons  = ['dot-blue', 'dot-teal', 'dot-amber'];
    const emojis = ['📅', '🎯', '⭐'];
    if (!data || data.length === 0) {
      el.innerHTML = `<div class="empty-state"><i class="fas fa-calendar-xmark"></i><p>Tidak ada kegiatan mendatang</p></div>`;
      document.getElementById('event-count').textContent = '0 kegiatan';
      return;
    }
    document.getElementById('event-count').textContent = `${data.length} kegiatan`;
    el.innerHTML = data.map((e, i) => `
      <div class="event-item">
        <div class="event-dot ${icons[i % 3]}">${emojis[i % 3]}</div>
        <div class="event-info">
          <h4>${e.judul_kegiatan}</h4>
          <p><i class="fas fa-calendar-day" style="font-size:10px;"></i> ${e.tanggal_kegiatan}</p>
        </div>
        <div class="event-date">
          <span class="date-badge">${formatDate(e.tanggal_kegiatan)}</span>
        </div>
      </div>
    `).join('');
  }

  /* ── RENDER MEETINGS ── */
  function renderMeetings(data) {
    const el = document.getElementById('latest-meetings');
    if (!data || data.length === 0) {
      el.innerHTML = `<div class="empty-state"><i class="fas fa-comment-slash"></i><p>Tidak ada rapat yang sudah dilaksanakan</p></div>`;
      document.getElementById('rapat-count').textContent = '0 rapat';
      return;
    }
    document.getElementById('rapat-count').textContent = `${data.length} rapat`;
    el.innerHTML = data.map((m, i) => `
      <div class="meeting-item">
        <div class="meeting-num">${i + 1}</div>
        <div class="meeting-info">
          <h4>${m.judul}</h4>
          <p>${m.timeline_judul} · ${m.tanggal}</p>
        </div>
        <span class="meeting-status status-done">Selesai</span>
      </div>
    `).join('');
  }

  function formatDate(dateStr) {
    if (!dateStr) return '—';
    return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
  }

  /* ── UPDATE SIDEBAR UKM NAME ── */
  function updateSidebarUkmName(nama) {
    const el = document.getElementById('sidebar-ukm-info');
    if (el) el.querySelector('h3').textContent = nama || '—';
  }

  /* ── LOAD UKM PROFILE (untuk nama di sidebar) ── */
  function loadUkmProfile() {
    fetch(`/backend/controllers/admin-ukm/profile.php?id_ukm=${id_ukm}`)
      .then(r => r.json())
      .then(data => {
        updateSidebarUkmName(data.nama_ukm);
      })
      .catch(() => {
        // Fallback demo
        updateSidebarUkmName('UKM PCC Polines');
      });
  }

  /* ── LOAD DASHBOARD DATA ── */
  function loadDashboard() {
    fetch('/backend/controllers/admin-ukm/get-dashboard.php')
      .then(r => r.json())
      .then(data => {
        animateCount(document.getElementById('total-anggota'),   data.totalAnggota   || 0);
        animateCount(document.getElementById('total-kegiatan'),  data.totalKegiatan  || 0);
        animateCount(document.getElementById('total-pendaftar'), data.totalPendaftar || 0);
        animateCount(document.getElementById('total-rapat'),     data.totalRapat     || 0);
        renderEvents(data.timelines);
        renderMeetings(data.rapatDilaksanakan);

        // Jika backend get-dashboard juga mengembalikan nama_ukm, pakai itu
        if (data.nama_ukm) updateSidebarUkmName(data.nama_ukm);
      })
      .catch(() => {
        // Demo fallback
        animateCount(document.getElementById('total-anggota'),   47);
        animateCount(document.getElementById('total-kegiatan'),  8);
        animateCount(document.getElementById('total-pendaftar'), 12);
        animateCount(document.getElementById('total-rapat'),     23);
        renderEvents([
          { judul_kegiatan: 'WIBU MEET 2025',   tanggal_kegiatan: '2025-03-15' },
          { judul_kegiatan: 'Open House UKM',   tanggal_kegiatan: '2025-03-22' },
          { judul_kegiatan: 'PCC Class Batch 3',tanggal_kegiatan: '2025-04-01' },
        ]);
        renderMeetings([
          { judul: 'Rapat Koordinasi Bulanan', timeline_judul: 'WIBU MEET',  tanggal: '2025-02-10' },
          { judul: 'Evaluasi Kegiatan',        timeline_judul: 'Open House', tanggal: '2025-02-18' },
          { judul: 'Rapat Persiapan PCC',      timeline_judul: 'PCC Class',  tanggal: '2025-03-05' },
        ]);
      });
  }

  /* ── LOGOUT ── */
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
    loadUkmProfile();   // fetch nama UKM → tampil di sidebar
    loadDashboard();    // fetch statistik & panel
  });
</script>
</body>
</html>