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
  <title>Manajemen Pendaftar — Admin UKM SIGMA</title>
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
    .sidebar { width: var(--sidebar-w); background: var(--navy); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; bottom: 0; z-index: 200; transition: transform var(--transition); }
    .sidebar::before { content: ''; position: absolute; top: -80px; right: -80px; width: 220px; height: 220px; background: radial-gradient(circle, rgba(79,106,240,0.18) 0%, transparent 70%); pointer-events: none; }
    .sidebar-brand { padding: 0 24px; height: var(--topbar-h); display: flex; align-items: center; gap: 12px; border-bottom: 1px solid rgba(255,255,255,0.06); flex-shrink: 0; }
    .brand-icon { width: 36px; height: 36px; background: linear-gradient(135deg, var(--accent), #7C3AED); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #fff; flex-shrink: 0; box-shadow: 0 4px 12px rgba(79,106,240,0.4); }
    .brand-text h2 { font-size: 16px; font-weight: 800; color: #fff; letter-spacing: 0.5px; line-height: 1.2; }
    .brand-text span { font-size: 11px; color: rgba(255,255,255,0.45); }
    .sidebar-ukm-card { margin: 16px 16px 8px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 14px 16px; display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
    .ukm-avatar { width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, var(--teal), #0284C7); display: flex; align-items: center; justify-content: center; font-size: 18px; color: #fff; flex-shrink: 0; }
    .ukm-info h3 { font-size: 13px; font-weight: 700; color: #fff; line-height: 1.3; }
    .ukm-info span { font-size: 11px; color: rgba(255,255,255,0.45); }
    .sidebar-section-label { padding: 16px 24px 6px; font-size: 10px; font-weight: 700; letter-spacing: 1.5px; color: rgba(255,255,255,0.25); text-transform: uppercase; flex-shrink: 0; }
    .sidebar-nav { flex: 1; overflow-y: auto; padding: 0 12px 12px; scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.1) transparent; }
    .nav-item { display: flex; align-items: center; gap: 12px; padding: 10px 14px; border-radius: 10px; cursor: pointer; transition: all var(--transition); text-decoration: none; color: rgba(255,255,255,0.55); font-size: 14px; font-weight: 500; position: relative; margin-bottom: 2px; }
    .nav-item:hover { background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.9); }
    .nav-item.active { background: linear-gradient(135deg, rgba(79,106,240,0.25), rgba(79,106,240,0.12)); color: #fff; border: 1px solid rgba(79,106,240,0.3); }
    .nav-item.active::before { content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%); width: 3px; height: 20px; background: var(--accent); border-radius: 0 3px 3px 0; }
    .nav-icon { width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; background: rgba(255,255,255,0.07); flex-shrink: 0; transition: all var(--transition); }
    .nav-item.active .nav-icon { background: var(--accent); color: #fff; box-shadow: 0 4px 12px rgba(79,106,240,0.35); }
    .nav-item:hover .nav-icon { background: rgba(255,255,255,0.12); color: #fff; }
    .sidebar-footer { padding: 12px; border-top: 1px solid rgba(255,255,255,0.06); flex-shrink: 0; }
    .logout-btn { display: flex; align-items: center; gap: 12px; width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid rgba(244,63,94,0.2); background: rgba(244,63,94,0.07); color: rgba(255,100,120,0.8); cursor: pointer; font-size: 14px; font-weight: 500; transition: all var(--transition); font-family: inherit; }
    .logout-btn:hover { background: rgba(244,63,94,0.15); color: #F43F5E; border-color: rgba(244,63,94,0.35); }
    .logout-btn .nav-icon { background: rgba(244,63,94,0.12); }

    /* ── MAIN ── */
    .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
    .topbar { height: var(--topbar-h); background: var(--card); border-bottom: 1px solid var(--border); display: flex; align-items: center; padding: 0 28px; gap: 16px; position: sticky; top: 0; z-index: 100; box-shadow: var(--shadow-sm); }
    .topbar-toggle { display: none; width: 36px; height: 36px; border-radius: 8px; background: var(--surface); border: 1px solid var(--border); cursor: pointer; align-items: center; justify-content: center; color: var(--text-mid); font-size: 14px; }
    .topbar-breadcrumb { flex: 1; }
    .topbar-breadcrumb h1 { font-size: 18px; font-weight: 700; color: var(--text-main); line-height: 1.2; }
    .topbar-breadcrumb p { font-size: 12px; color: var(--text-soft); }
    .topbar-actions { display: flex; align-items: center; gap: 10px; }
    .topbar-btn { width: 38px; height: 38px; border-radius: 10px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-mid); font-size: 15px; transition: all var(--transition); }
    .topbar-btn:hover { background: var(--accent-soft); border-color: var(--accent); color: var(--accent); }
    .topbar-admin { display: flex; align-items: center; gap: 10px; padding: 6px 12px 6px 6px; border-radius: 12px; background: var(--surface); border: 1px solid var(--border); cursor: pointer; transition: all var(--transition); }
    .topbar-admin:hover { border-color: var(--accent); }
    .admin-avatar { width: 30px; height: 30px; border-radius: 8px; background: linear-gradient(135deg, var(--accent), #7C3AED); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 13px; font-weight: 700; }
    .admin-name { font-size: 13px; font-weight: 600; color: var(--text-main); }
    .content { flex: 1; padding: 28px; }

    /* ── STATS ── */
    .stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 24px; }
    .mini-stat { background: var(--card); border: 1px solid var(--border); border-radius: var(--radius); padding: 18px 20px; display: flex; align-items: center; gap: 14px; box-shadow: var(--shadow-sm); transition: all var(--transition); animation: fadeUp 0.4s ease both; position: relative; overflow: hidden; }
    .mini-stat::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 2px; }
    .mini-stat.total::after   { background: var(--accent); }
    .mini-stat.pending::after { background: var(--amber); }
    .mini-stat.acc::after     { background: var(--green); }
    .mini-stat.reject::after  { background: var(--rose); }
    .mini-stat:nth-child(1){animation-delay:.05s} .mini-stat:nth-child(2){animation-delay:.10s} .mini-stat:nth-child(3){animation-delay:.15s} .mini-stat:nth-child(4){animation-delay:.20s}
    @keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
    .mini-stat:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
    .mini-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
    .mini-icon.blue  { background: #EEF1FF; color: var(--accent); }
    .mini-icon.green { background: #ECFDF5; color: var(--green); }
    .mini-icon.amber { background: #FFFBEB; color: var(--amber); }
    .mini-icon.rose  { background: #FFF1F2; color: var(--rose); }
    .mini-body h2 { font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px; line-height: 1; }
    .mini-body p  { font-size: 12px; color: var(--text-soft); margin-top: 4px; font-weight: 500; }

    /* ── TAB CARD ── */
    .tab-card { background: var(--card); border: 1px solid var(--border); border-radius: var(--radius); box-shadow: var(--shadow-sm); overflow: hidden; animation: fadeUp 0.4s ease 0.22s both; }
    .tab-header { display: flex; align-items: stretch; border-bottom: 1px solid var(--border); background: var(--surface); padding: 0 24px; gap: 4px; }
    .tab-btn { display: flex; align-items: center; gap: 10px; padding: 18px 20px; border: none; background: none; font-family: inherit; font-size: 14px; font-weight: 600; color: var(--text-soft); cursor: pointer; position: relative; transition: color var(--transition); white-space: nowrap; }
    .tab-btn::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 2px; border-radius: 2px 2px 0 0; background: var(--accent); transform: scaleX(0); transition: transform var(--transition); }
    .tab-btn.active { color: var(--accent); }
    .tab-btn.active::after { transform: scaleX(1); }
    .tab-btn:hover { color: var(--text-main); }
    .tab-step { width: 26px; height: 26px; border-radius: 50%; background: var(--border); color: var(--text-soft); font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; transition: all var(--transition); }
    .tab-btn.active .tab-step { background: var(--accent); color: #fff; box-shadow: 0 2px 8px rgba(79,106,240,0.35); }
    .tab-count { padding: 2px 8px; border-radius: 20px; font-size: 11px; font-weight: 700; background: var(--surface); color: var(--text-soft); border: 1px solid var(--border); }
    .tab-btn.active .tab-count { background: var(--accent-soft); color: var(--accent); border-color: var(--accent); }
    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

    /* ── TOOLBAR ── */
    .panel-toolbar { padding: 16px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
    .search-wrap { position: relative; flex: 1; min-width: 200px; }
    .search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-soft); font-size: 13px; }
    .search-input { width: 100%; padding: 9px 12px 9px 34px; border: 1px solid var(--border); border-radius: 9px; font-family: inherit; font-size: 13px; color: var(--text-main); background: var(--card); outline: none; transition: all var(--transition); }
    .search-input:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(79,106,240,0.10); }
    .filter-chips { display: flex; gap: 6px; flex-wrap: wrap; }
    .chip { padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; border: 1.5px solid var(--border); background: var(--card); color: var(--text-mid); cursor: pointer; transition: all var(--transition); }
    .chip:hover { border-color: var(--accent); color: var(--accent); }
    .chip.active-chip { background: var(--accent); border-color: var(--accent); color: #fff; }

    /* ── TABLE ── */
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
    thead tr { background: var(--surface); border-bottom: 2px solid var(--border); }
    thead th { padding: 12px 20px; text-align: left; font-size: 11px; font-weight: 700; color: var(--text-soft); letter-spacing: 0.8px; text-transform: uppercase; white-space: nowrap; }
    tbody tr { border-bottom: 1px solid var(--border); transition: background var(--transition); }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: #F8FAFF; }
    tbody td { padding: 14px 20px; vertical-align: middle; }
    .td-nim { font-weight: 600; font-size: 12px; color: var(--text-mid); font-family: monospace; }
    .td-name { font-weight: 600; color: var(--text-main); }
    .badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
    .badge-pending { background: #FFFBEB; color: var(--amber); }
    .badge-acc     { background: #ECFDF5; color: var(--green); }
    .badge-reject  { background: #FFF1F2; color: var(--rose); }
    .td-motivasi { max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--text-mid); font-size: 13px; }
    .doc-link { display: inline-flex; align-items: center; gap: 6px; padding: 5px 10px; border-radius: 8px; background: var(--accent-soft); color: var(--accent); font-size: 12px; font-weight: 600; text-decoration: none; transition: all var(--transition); }
    .doc-link:hover { background: var(--accent); color: #fff; }
    .btn-actions { display: flex; gap: 6px; }
    .btn-icon { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 8px; border: none; cursor: pointer; font-size: 12px; font-weight: 600; font-family: inherit; transition: all var(--transition); }
    .btn-acc    { background: #ECFDF5; color: var(--green); }
    .btn-reject { background: #FFF1F2; color: var(--rose); }
    .btn-review { background: var(--accent-soft); color: var(--accent); }
    .btn-acc:hover    { background: var(--green);  color: #fff; transform: translateY(-1px); }
    .btn-reject:hover { background: var(--rose);   color: #fff; transform: translateY(-1px); }
    .btn-review:hover { background: var(--accent); color: #fff; transform: translateY(-1px); }
    .empty-state { text-align: center; padding: 52px 0; color: var(--text-soft); }
    .empty-state .empty-icon { width: 64px; height: 64px; border-radius: 16px; background: var(--surface); margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; font-size: 28px; }
    .empty-state h3 { font-size: 15px; font-weight: 600; color: var(--text-mid); margin-bottom: 6px; }
    .empty-state p { font-size: 13px; }
    .table-footer { padding: 14px 24px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
    .table-info { font-size: 13px; color: var(--text-soft); }

    /* ── MODAL ── */
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,27,76,0.45); z-index: 500; backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 20px; }
    .modal-overlay.open { display: flex; }
    .modal-box { background: var(--card); border-radius: 20px; width: 100%; max-width: 500px; box-shadow: var(--shadow-lg); animation: modalIn 0.25s cubic-bezier(0.34,1.56,0.64,1); }
    @keyframes modalIn { from{opacity:0;transform:scale(0.92) translateY(20px)} to{opacity:1;transform:scale(1) translateY(0)} }
    .modal-header { padding: 24px 28px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
    .modal-header-left { display: flex; align-items: center; gap: 14px; }
    .modal-header-icon { width: 42px; height: 42px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; background: var(--accent-soft); color: var(--accent); }
    .modal-title { font-size: 16px; font-weight: 700; color: var(--text-main); }
    .modal-subtitle { font-size: 12px; color: var(--text-soft); margin-top: 2px; }
    .modal-close { width: 32px; height: 32px; border-radius: 8px; background: var(--surface); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-soft); font-size: 16px; transition: all var(--transition); }
    .modal-close:hover { background: #FFF1F2; color: var(--rose); border-color: var(--rose); }
    .modal-body { padding: 24px 28px; display: flex; flex-direction: column; gap: 18px; }
    .modal-footer { padding: 16px 28px 24px; display: flex; gap: 10px; justify-content: flex-end; }
    .applicant-card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 16px; display: flex; align-items: center; gap: 14px; }
    .applicant-avatar { width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, var(--accent), #7C3AED); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 16px; font-weight: 700; flex-shrink: 0; }
    .applicant-info h4 { font-size: 14px; font-weight: 700; color: var(--text-main); }
    .applicant-info p  { font-size: 12px; color: var(--text-soft); margin-top: 3px; }
    .form-field { display: flex; flex-direction: column; gap: 6px; }
    .form-label { font-size: 12px; font-weight: 700; color: var(--text-mid); letter-spacing: 0.4px; text-transform: uppercase; }
    .form-label span { color: var(--rose); margin-left: 2px; }
    .form-control { padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 10px; font-size: 14px; font-family: inherit; color: var(--text-main); background: var(--surface); transition: all var(--transition); outline: none; }
    .form-control:focus { border-color: var(--accent); background: var(--card); box-shadow: 0 0 0 3px rgba(79,106,240,0.12); }
    .status-options { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .status-option { padding: 14px; border-radius: 12px; border: 2px solid var(--border); cursor: pointer; transition: all var(--transition); text-align: center; }
    .status-option.acc-opt:hover, .status-option.selected-acc { border-color: var(--green); background: #F0FDF4; }
    .status-option.rej-opt:hover, .status-option.selected-rej { border-color: var(--rose);  background: #FFF1F2; }
    .status-option .opt-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; margin: 0 auto 8px; }
    .acc-opt .opt-icon { background: #ECFDF5; color: var(--green); }
    .rej-opt .opt-icon { background: #FFF1F2; color: var(--rose); }
    .status-option p     { font-size: 13px; font-weight: 700; color: var(--text-main); }
    .status-option small { font-size: 11px; color: var(--text-soft); }
    .btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 9px 20px; background: var(--accent); color: #fff; border: none; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; font-family: inherit; transition: all var(--transition); box-shadow: 0 4px 12px rgba(79,106,240,0.3); }
    .btn-primary:hover    { background: #3d59e0; transform: translateY(-1px); }
    .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
    .btn-secondary { display: inline-flex; align-items: center; gap: 8px; padding: 9px 20px; background: var(--surface); color: var(--text-mid); border: 1px solid var(--border); border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; font-family: inherit; transition: all var(--transition); }
    .btn-secondary:hover { background: var(--border); }
    .overlay-mob { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 199; backdrop-filter: blur(2px); }

    @media (max-width: 1100px) { .stats-row { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 768px) {
      .sidebar { transform: translateX(-100%); }
      .sidebar.open { transform: translateX(0); }
      .overlay-mob.open { display: block; }
      .main { margin-left: 0; }
      .topbar-toggle { display: flex; }
      .content { padding: 20px 16px; }
      .stats-row { grid-template-columns: 1fr 1fr; gap: 12px; }
      .tab-header { padding: 0 16px; overflow-x: auto; }
      .panel-toolbar { flex-direction: column; align-items: stretch; }
    }
    @media (max-width: 480px) {
      .stats-row { grid-template-columns: 1fr; }
      .topbar { padding: 0 16px; }
      .admin-name { display: none; }
      .btn-icon span { display: none; }
    }
  </style>
</head>
<body>

<div class="overlay-mob" id="overlay" onclick="closeSidebar()"></div>

<!-- ════════ SIDEBAR ════════ -->
<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="brand-icon"><i class="fas fa-graduation-cap"></i></div>
    <div class="brand-text"><h2>SIGMA</h2><span>Admin Panel</span></div>
  </div>
  <div class="sidebar-ukm-card">
    <div class="ukm-avatar"><i class="fas fa-users"></i></div>
    <div class="ukm-info" id="sidebar-ukm-info"><h3>—</h3><span>Periode 2024–2025</span></div>
  </div>
  <div class="sidebar-section-label">Menu Utama</div>
  <nav class="sidebar-nav">
    <a href="dashboard.php"           class="nav-item"><span class="nav-icon"><i class="fas fa-chart-pie"></i></span>Dashboard</a>
    <a href="profile-ukm.php"         class="nav-item"><span class="nav-icon"><i class="fas fa-id-card"></i></span>Profil UKM</a>
    <a href="struktur_organisasi.php" class="nav-item"><span class="nav-icon"><i class="fas fa-sitemap"></i></span>Struktur Organisasi</a>
    <a href="timeline.php"            class="nav-item"><span class="nav-icon"><i class="fas fa-calendar-alt"></i></span>Timeline Kegiatan</a>
    <a href="keanggotaan.php"         class="nav-item"><span class="nav-icon"><i class="fas fa-user-group"></i></span>Keanggotaan</a>
    <a href="rapat.php"               class="nav-item"><span class="nav-icon"><i class="fas fa-comments"></i></span>Rapat</a>
    <div class="sidebar-section-label" style="padding-top:12px;">Pendaftaran</div>
    <a href="manajemen-periode.php"   class="nav-item"><span class="nav-icon"><i class="fas fa-clock-rotate-left"></i></span>Periode Pendaftaran</a>
    <a href="manajemen-pendaftar.php" class="nav-item active"><span class="nav-icon"><i class="fas fa-clipboard-list"></i></span>Manajemen Pendaftar</a>
  </nav>
  <div class="sidebar-footer">
    <button class="logout-btn" onclick="logout()">
      <span class="nav-icon"><i class="fas fa-arrow-right-from-bracket"></i></span>Keluar
    </button>
  </div>
</aside>

<!-- ════════ MAIN ════════ -->
<div class="main">
  <header class="topbar">
    <button class="topbar-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
    <div class="topbar-breadcrumb">
      <h1>Manajemen Pendaftar</h1>
      <p>Review dan kelola proses pendaftaran mahasiswa</p>
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
      <div class="mini-stat total"><div class="mini-icon blue"><i class="fas fa-users"></i></div><div class="mini-body"><h2 id="stat-total">—</h2><p>Total Pendaftar</p></div></div>
      <div class="mini-stat pending"><div class="mini-icon amber"><i class="fas fa-clock"></i></div><div class="mini-body"><h2 id="stat-pending">—</h2><p>Menunggu Review</p></div></div>
      <div class="mini-stat acc"><div class="mini-icon green"><i class="fas fa-circle-check"></i></div><div class="mini-body"><h2 id="stat-acc">—</h2><p>Diterima</p></div></div>
      <div class="mini-stat reject"><div class="mini-icon rose"><i class="fas fa-circle-xmark"></i></div><div class="mini-body"><h2 id="stat-reject">—</h2><p>Ditolak</p></div></div>
    </div>

    <!-- Tab Card -->
    <div class="tab-card">
      <div class="tab-header">
        <button class="tab-btn active" onclick="switchTab(1)" id="tab-btn-1"><span class="tab-step">1</span>Tahap 1<span class="tab-count" id="count-1">0</span></button>
        <button class="tab-btn"        onclick="switchTab(2)" id="tab-btn-2"><span class="tab-step">2</span>Tahap 2<span class="tab-count" id="count-2">0</span></button>
        <button class="tab-btn"        onclick="switchTab(3)" id="tab-btn-3"><span class="tab-step">3</span>Tahap 3<span class="tab-count" id="count-3">0</span></button>
      </div>

      <!-- Tahap 1 -->
      <div class="tab-panel active" id="panel-1">
        <div class="panel-toolbar">
          <div class="search-wrap"><i class="fas fa-search"></i><input type="text" class="search-input" placeholder="Cari nama atau NIM…" oninput="filterTable(1,this.value)"></div>
          <div class="filter-chips">
            <button class="chip active-chip" onclick="filterStatus(1,'',event)">Semua</button>
            <button class="chip" onclick="filterStatus(1,'pending',event)">Pending</button>
            <button class="chip" onclick="filterStatus(1,'acc',event)">Diterima</button>
            <button class="chip" onclick="filterStatus(1,'reject',event)">Ditolak</button>
          </div>
        </div>
        <div class="table-wrap"><table><thead><tr><th>#</th><th>NIM</th><th>Nama</th><th>Motivasi</th><th>Status</th><th>Aksi</th></tr></thead>
          <tbody id="tbody-1"><tr><td colspan="6"><div class="empty-state"><div class="empty-icon">⏳</div><h3>Memuat data…</h3></div></td></tr></tbody>
        </table></div>
        <div class="table-footer"><span class="table-info" id="info-1">Memuat…</span></div>
      </div>

      <!-- Tahap 2 -->
      <div class="tab-panel" id="panel-2">
        <div class="panel-toolbar">
          <div class="search-wrap"><i class="fas fa-search"></i><input type="text" class="search-input" placeholder="Cari nama atau NIM…" oninput="filterTable(2,this.value)"></div>
          <div class="filter-chips">
            <button class="chip active-chip" onclick="filterStatus(2,'',event)">Semua</button>
            <button class="chip" onclick="filterStatus(2,'pending',event)">Pending</button>
            <button class="chip" onclick="filterStatus(2,'acc',event)">Diterima</button>
            <button class="chip" onclick="filterStatus(2,'reject',event)">Ditolak</button>
          </div>
        </div>
        <div class="table-wrap"><table><thead><tr><th>#</th><th>NIM</th><th>Nama</th><th>Divisi Pilihan</th><th>Dokumen</th><th>Status</th><th>Aksi</th></tr></thead>
          <tbody id="tbody-2"><tr><td colspan="7"><div class="empty-state"><div class="empty-icon">⏳</div><h3>Memuat data…</h3></div></td></tr></tbody>
        </table></div>
        <div class="table-footer"><span class="table-info" id="info-2">Memuat…</span></div>
      </div>

      <!-- Tahap 3 -->
      <div class="tab-panel" id="panel-3">
        <div class="panel-toolbar">
          <div class="search-wrap"><i class="fas fa-search"></i><input type="text" class="search-input" placeholder="Cari nama atau NIM…" oninput="filterTable(3,this.value)"></div>
          <div class="filter-chips">
            <button class="chip active-chip" onclick="filterStatus(3,'',event)">Semua</button>
            <button class="chip" onclick="filterStatus(3,'pending',event)">Pending</button>
            <button class="chip" onclick="filterStatus(3,'acc',event)">Diterima</button>
            <button class="chip" onclick="filterStatus(3,'reject',event)">Ditolak</button>
          </div>
        </div>
        <div class="table-wrap"><table><thead><tr><th>#</th><th>NIM</th><th>Nama</th><th>CV</th><th>Surat Motivasi</th><th>Status</th><th>Aksi</th></tr></thead>
          <tbody id="tbody-3"><tr><td colspan="7"><div class="empty-state"><div class="empty-icon">⏳</div><h3>Memuat data…</h3></div></td></tr></tbody>
        </table></div>
        <div class="table-footer"><span class="table-info" id="info-3">Memuat…</span></div>
      </div>
    </div>
  </main>
</div>

<!-- ════════ MODAL ════════ -->
<div class="modal-overlay" id="modal-overlay">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-header-icon"><i class="fas fa-clipboard-check"></i></div>
        <div><div class="modal-title">Review Pendaftaran</div><div class="modal-subtitle" id="modal-subtitle-review">Tahap —</div></div>
      </div>
      <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="review_id">
      <input type="hidden" id="review_tahap">
      <div class="applicant-card">
        <div class="applicant-avatar" id="review-initial">A</div>
        <div class="applicant-info"><h4 id="review-nama">—</h4><p id="review-nim">—</p></div>
      </div>
      <div class="form-field">
        <label class="form-label">Keputusan <span>*</span></label>
        <div class="status-options">
          <div class="status-option acc-opt" id="opt-acc" onclick="selectStatus('acc')">
            <div class="opt-icon"><i class="fas fa-check"></i></div><p>Terima</p><small>Lanjut ke tahap berikutnya</small>
          </div>
          <div class="status-option rej-opt" id="opt-reject" onclick="selectStatus('reject')">
            <div class="opt-icon"><i class="fas fa-times"></i></div><p>Tolak</p><small>Pendaftaran dihentikan</small>
          </div>
        </div>
        <input type="hidden" id="review_status">
      </div>
      <div class="form-field">
        <label class="form-label">Catatan <span style="color:var(--text-soft);font-weight:400;">(opsional)</span></label>
        <textarea class="form-control" id="review_catatan" rows="3" placeholder="Tulis catatan untuk pendaftar…" style="resize:vertical;"></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-secondary" onclick="closeModal()">Batal</button>
      <button class="btn-primary" id="btn-submit-review" onclick="submitReview()">
        <i class="fas fa-paper-plane"></i> <span>Kirim Keputusan</span>
      </button>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
  const id_ukm = <?= $id_ukm ?>;

  // ═══════════════════════════════════════════════════════════════
  // ENDPOINT — sesuai pendaftaran.js lama: ?action=get_tahap1/2/3
  // Jika backend kamu pakai format berbeda, ubah fungsi ini saja.
  // ═══════════════════════════════════════════════════════════════
  const BASE_URL = '/backend/controllers/admin-ukm/pendaftaran.php';
  const urlTahap  = n => `${BASE_URL}?action=get_tahap${n}`;
  const urlReview = BASE_URL; // POST, body action=review

  /* ── SIDEBAR ── */
  function toggleSidebar() { document.getElementById('sidebar').classList.toggle('open'); document.getElementById('overlay').classList.toggle('open'); }
  function closeSidebar()  { document.getElementById('sidebar').classList.remove('open'); document.getElementById('overlay').classList.remove('open'); }

  /* ── UKM NAME ── */
  function updateSidebarUkmName(nama) {
    const el = document.getElementById('sidebar-ukm-info');
    if (el) el.querySelector('h3').textContent = nama || '—';
  }
  function loadUkmProfile() {
    fetch(`/backend/controllers/admin-ukm/profile.php?id_ukm=${id_ukm}`)
      .then(r => r.json()).then(d => updateSidebarUkmName(d.nama_ukm)).catch(() => {});
  }

  /* ── TAB ── */
  function switchTab(n) {
    [1,2,3].forEach(i => {
      document.getElementById(`tab-btn-${i}`).classList.toggle('active', i===n);
      document.getElementById(`panel-${i}`).classList.toggle('active', i===n);
    });
  }

  /* ── DATA STATE ── */
  const rawData     = {1:[],2:[],3:[]};
  const filterState = {1:{search:'',status:''},2:{search:'',status:''},3:{search:'',status:''}};

  /* ── LOAD ── */
  function loadAll() { [1,2,3].forEach(loadTahap); }

  function loadTahap(tahap) {
    fetch(urlTahap(tahap))
      .then(r => r.json())
      .then(res => {
        console.log(`[Tahap ${tahap}] response:`, res); // cek di console jika data kosong

        // Backend bisa return: res.data ATAU res.pendaftar ATAU array langsung
        let data = res.data ?? res.pendaftar ?? res.result ?? res;
        if (!Array.isArray(data)) data = [];

        rawData[tahap] = data;
        document.getElementById(`count-${tahap}`).textContent = data.length;
        renderTahap(tahap);
        updateStats();
      })
      .catch(err => {
        console.error(`[Tahap ${tahap}] Gagal:`, err);
        renderEmpty(tahap, 'Gagal memuat data. Cek console.');
      });
  }

  function updateStats() {
    const all = [...rawData[1],...rawData[2],...rawData[3]];
    // Support field status lama (status_tahap1) dan baru (status)
    const s = (d,t) => (d.status ?? d[`status_tahap${t}`] ?? '').toLowerCase();
    document.getElementById('stat-total').textContent   = rawData[1].length + rawData[2].length + rawData[3].length;
    document.getElementById('stat-pending').textContent =
      rawData[1].filter(d=>s(d,1).includes('pending')).length +
      rawData[2].filter(d=>s(d,2).includes('pending')).length +
      rawData[3].filter(d=>s(d,3).includes('pending')).length;
    document.getElementById('stat-acc').textContent =
      rawData[1].filter(d=>s(d,1).includes('acc')).length +
      rawData[2].filter(d=>s(d,2).includes('acc')).length +
      rawData[3].filter(d=>s(d,3).includes('acc')).length;
    document.getElementById('stat-reject').textContent =
      rawData[1].filter(d=>['reject','ditolak'].includes(s(d,1))).length +
      rawData[2].filter(d=>['reject','ditolak'].includes(s(d,2))).length +
      rawData[3].filter(d=>['reject','ditolak'].includes(s(d,3))).length;
  }

  /* ── RENDER ── */
  // Baca status: support field lama (status_tahap1/2/3) dan baru (status)
  const getStatus = (d, tahap) => (d.status ?? d[`status_tahap${tahap}`] ?? '').toLowerCase();

  function getFiltered(tahap) {
    const {search, status} = filterState[tahap];
    return rawData[tahap].filter(d => {
      const matchSearch = !search ||
        (d.nama_lengkap??'').toLowerCase().includes(search.toLowerCase()) ||
        (d.nim??'').toLowerCase().includes(search.toLowerCase());
      const matchStatus = !status || getStatus(d,tahap).includes(status);
      return matchSearch && matchStatus;
    });
  }

  function renderTahap(tahap) {
    const data  = getFiltered(tahap);
    const tbody = document.getElementById(`tbody-${tahap}`);
    const info  = document.getElementById(`info-${tahap}`);
    const cols  = tahap === 1 ? 6 : 7;

    if (!data.length) {
      tbody.innerHTML = `<tr><td colspan="${cols}"><div class="empty-state"><div class="empty-icon">📋</div><h3>Tidak ada data</h3><p>Belum ada pendaftar pada tahap ini</p></div></td></tr>`;
      info.textContent = '0 data';
      return;
    }
    info.textContent = `${data.length} pendaftar`;

    if (tahap === 1) {
      tbody.innerHTML = data.map((d,i) => `
        <tr>
          <td style="color:var(--text-soft);font-size:12px;">${i+1}</td>
          <td class="td-nim">${d.nim??'—'}</td>
          <td class="td-name">${d.nama_lengkap??'—'}</td>
          <td class="td-motivasi" title="${escHtml(d.motivasi??'')}">${d.motivasi??'—'}</td>
          <td>${badge(getStatus(d,1))}</td>
          <td><div class="btn-actions">${btns(d,1)}</div></td>
        </tr>`).join('');
    }

    if (tahap === 2) {
      tbody.innerHTML = data.map((d,i) => `
        <tr>
          <td style="color:var(--text-soft);font-size:12px;">${i+1}</td>
          <td class="td-nim">${d.nim??'—'}</td>
          <td class="td-name">${d.nama_lengkap??'—'}</td>
          <td>${d.divisi_pilihan ?? d.nama_divisi ?? '—'}</td>
          <td>${fileLink(d.izin_ortu_path, d.dokumen, 'Izin Ortu')}</td>
          <td>${badge(getStatus(d,2))}</td>
          <td><div class="btn-actions">${btns(d,2)}</div></td>
        </tr>`).join('');
    }

    if (tahap === 3) {
      tbody.innerHTML = data.map((d,i) => `
        <tr>
          <td style="color:var(--text-soft);font-size:12px;">${i+1}</td>
          <td class="td-nim">${d.nim??'—'}</td>
          <td class="td-name">${d.nama_lengkap??'—'}</td>
          <td>${fileLink(d.cv_path, d.cv, 'CV', 'fas fa-file-pdf')}</td>
          <td>${fileLink(d.motivation_letter_path, d.surat_motivasi, 'Surat Motivasi', 'fas fa-envelope')}</td>
          <td>${badge(getStatus(d,3))}</td>
          <td><div class="btn-actions">${btns(d,3)}</div></td>
        </tr>`).join('');
    }
  }

  // Helper: buat link dokumen — support path lama (/public/uploads/...) & URL langsung
  function fileLink(pathLama, urlBaru, label, icon='fas fa-file') {
    if (pathLama) return `<a href="/public/uploads/dokumen_pendaftaran/${pathLama}" target="_blank" class="doc-link"><i class="${icon}"></i> ${label}</a>`;
    if (urlBaru)  return `<a href="${urlBaru}" target="_blank" class="doc-link"><i class="${icon}"></i> ${label}</a>`;
    return `<span style="color:var(--text-soft);font-size:12px;">—</span>`;
  }

  function badge(s) {
    if (!s) return '<span class="badge badge-pending">—</span>';
    if (s.includes('pending')) return `<span class="badge badge-pending"><i class="fas fa-clock" style="font-size:9px;"></i> Pending</span>`;
    if (s.includes('acc'))     return `<span class="badge badge-acc"><i class="fas fa-check" style="font-size:9px;"></i> Diterima</span>`;
    if (s==='reject'||s==='ditolak') return `<span class="badge badge-reject"><i class="fas fa-times" style="font-size:9px;"></i> Ditolak</span>`;
    return `<span class="badge badge-pending">${s}</span>`;
  }

  function btns(d, tahap) {
    const s = getStatus(d, tahap);
    const isPending = s.includes('pending') || s === '';
    const nama = escAttr(d.nama_lengkap ?? '');
    const nim  = escAttr(d.nim ?? '');
    const id   = d.id_pendaftaran;
    if (isPending) {
      return `
        <button class="btn-icon btn-acc"    onclick="openReview(${id},${tahap},'${nama}','${nim}','acc')"><i class="fas fa-check"></i><span> Terima</span></button>
        <button class="btn-icon btn-reject" onclick="openReview(${id},${tahap},'${nama}','${nim}','reject')"><i class="fas fa-times"></i><span> Tolak</span></button>`;
    }
    return `<button class="btn-icon btn-review" onclick="openReview(${id},${tahap},'${nama}','${nim}','')"><i class="fas fa-pen"></i><span> Ubah</span></button>`;
  }

  function renderEmpty(tahap, msg) {
    const cols = tahap===1 ? 6 : 7;
    document.getElementById(`tbody-${tahap}`).innerHTML =
      `<tr><td colspan="${cols}"><div class="empty-state"><div class="empty-icon">⚠️</div><h3>${msg}</h3><p>Coba refresh halaman</p></div></td></tr>`;
    document.getElementById(`info-${tahap}`).textContent = '—';
  }

  // Escape helpers
  const escHtml  = s => s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  const escAttr  = s => s.replace(/'/g,"\\'");

  /* ── FILTER ── */
  function filterTable(tahap, val) { filterState[tahap].search = val; renderTahap(tahap); }
  function filterStatus(tahap, val, e) {
    filterState[tahap].status = val;
    document.querySelectorAll(`#panel-${tahap} .chip`).forEach(c => c.classList.remove('active-chip'));
    if (e?.target) e.target.classList.add('active-chip');
    renderTahap(tahap);
  }

  /* ── MODAL ── */
  let selectedStatus = '';

  function openReview(id, tahap, nama, nim, preselect) {
    document.getElementById('review_id').value    = id;
    document.getElementById('review_tahap').value = tahap;
    document.getElementById('modal-subtitle-review').textContent = `Tahap ${tahap}`;
    document.getElementById('review-nama').textContent  = nama || '—';
    document.getElementById('review-nim').textContent   = nim  || '—';
    document.getElementById('review-initial').textContent = (nama||'A')[0].toUpperCase();
    document.getElementById('review_catatan').value = '';
    document.getElementById('review_status').value  = '';
    document.getElementById('opt-acc').classList.remove('selected-acc');
    document.getElementById('opt-reject').classList.remove('selected-rej');
    selectedStatus = '';
    if (preselect) selectStatus(preselect);
    document.getElementById('modal-overlay').classList.add('open');
  }

  function closeModal() { document.getElementById('modal-overlay').classList.remove('open'); selectedStatus = ''; }

  document.getElementById('modal-overlay').addEventListener('click', function(e) { if (e.target===this) closeModal(); });

  function selectStatus(val) {
    selectedStatus = val;
    document.getElementById('review_status').value = val;
    document.getElementById('opt-acc').classList.toggle('selected-acc', val==='acc');
    document.getElementById('opt-reject').classList.toggle('selected-rej', val==='reject');
  }

  function submitReview() {
    if (!selectedStatus) {
      Swal.fire({icon:'warning',title:'Pilih keputusan',text:'Silakan pilih Terima atau Tolak terlebih dahulu.',confirmButtonColor:'#4F6AF0'});
      return;
    }
    const btn = document.getElementById('btn-submit-review');
    btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim…';

    const body = new FormData();
    body.append('action',        'review'); // format lama
    body.append('id_pendaftaran', document.getElementById('review_id').value);
    body.append('tahap',          document.getElementById('review_tahap').value);
    body.append('status',         selectedStatus);
    body.append('catatan',        document.getElementById('review_catatan').value);

    fetch(urlReview, {method:'POST', body})
      .then(r => r.json())
      .then(res => {
        if (res.status === 'success') {
          Swal.fire({
            icon: selectedStatus==='acc' ? 'success' : 'info',
            title: selectedStatus==='acc' ? 'Pendaftar Diterima!' : 'Pendaftar Ditolak',
            text: res.message, timer:1800, showConfirmButton:false
          });
          closeModal();
          loadTahap(parseInt(document.getElementById('review_tahap').value));
        } else {
          Swal.fire({icon:'error', title:'Gagal!', text:res.message});
        }
      })
      .catch(() => Swal.fire({icon:'error',title:'Error',text:'Terjadi kesalahan jaringan'}))
      .finally(() => { btn.disabled=false; btn.innerHTML='<i class="fas fa-paper-plane"></i> <span>Kirim Keputusan</span>'; });
  }

  /* ── LOGOUT ── */
  function logout() {
    Swal.fire({title:'Keluar dari SIGMA?',icon:'question',showCancelButton:true,confirmButtonColor:'#F43F5E',cancelButtonColor:'#94A3B8',confirmButtonText:'Ya, keluar',cancelButtonText:'Batal'})
      .then(r => { if (r.isConfirmed) fetch('/backend/controllers/logout.php').then(()=>location.href='/index.html').catch(()=>location.href='/index.html'); });
  }

  /* ── INIT ── */
  document.addEventListener('DOMContentLoaded', () => {
    loadUkmProfile();
    loadAll();
  });
</script>
</body>
</html>