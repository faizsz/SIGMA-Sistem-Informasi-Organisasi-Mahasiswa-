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
  <title>Rapat — Admin UKM SIGMA</title>
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

    .sidebar { width:var(--sidebar-w); background:var(--navy); display:flex; flex-direction:column; position:fixed; top:0;left:0;bottom:0; z-index:200; transition:transform var(--transition); }
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

    .stats-row { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px; }
    .mini-stat { background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:18px 20px; display:flex;align-items:center;gap:14px; box-shadow:var(--shadow-sm); transition:all var(--transition); animation:fadeUp .4s ease both; }
    .mini-stat:nth-child(1){animation-delay:.05s}.mini-stat:nth-child(2){animation-delay:.1s}.mini-stat:nth-child(3){animation-delay:.15s}.mini-stat:nth-child(4){animation-delay:.2s}
    @keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }
    .mini-stat:hover { transform:translateY(-2px);box-shadow:var(--shadow-md); }
    .mini-icon { width:44px;height:44px; border-radius:12px; display:flex;align-items:center;justify-content:center; font-size:18px;flex-shrink:0; }
    .mi-blue   { background:#EEF1FF;color:var(--accent); }
    .mi-amber  { background:#FFFBEB;color:var(--amber); }
    .mi-green  { background:#ECFDF5;color:var(--green); }
    .mi-purple { background:#F5F3FF;color:var(--purple); }
    .mini-body h2 { font-size:24px;font-weight:800;color:var(--text-main);letter-spacing:-.5px;line-height:1; }
    .mini-body p  { font-size:12px;color:var(--text-soft);margin-top:4px;font-weight:500; }

    .toolbar { display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:20px;flex-wrap:wrap; }
    .search-wrap { position:relative;min-width:240px; }
    .search-wrap i { position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-soft);font-size:13px; }
    .search-input { width:100%;padding:9px 12px 9px 34px; border:1px solid var(--border);border-radius:9px; font-family:inherit;font-size:13px;color:var(--text-main); background:var(--card);outline:none; transition:all var(--transition); }
    .search-input:focus { border-color:var(--accent);box-shadow:0 0 0 3px rgba(79,106,240,.10); }
    .toolbar-right { display:flex;align-items:center;gap:10px; }

    .btn-primary { display:inline-flex;align-items:center;gap:8px; padding:9px 20px;background:var(--accent);color:#fff; border:none;border-radius:10px; font-size:13px;font-weight:600;cursor:pointer;font-family:inherit; transition:all var(--transition); box-shadow:0 4px 12px rgba(79,106,240,.3); }
    .btn-primary:hover { background:#3d59e0;transform:translateY(-1px); }
    .btn-primary:disabled { opacity:.6;cursor:not-allowed;transform:none; }
    .btn-secondary { display:inline-flex;align-items:center;gap:8px; padding:9px 20px;background:var(--surface);color:var(--text-mid); border:1px solid var(--border);border-radius:10px; font-size:13px;font-weight:600;cursor:pointer;font-family:inherit; transition:all var(--transition); }
    .btn-secondary:hover { background:var(--border); }

    .rapat-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(360px,1fr)); gap:20px; }
    .rapat-card { background:var(--card); border:1px solid var(--border); border-radius:var(--radius); box-shadow:var(--shadow-sm); overflow:hidden; transition:all var(--transition); animation:fadeUp .4s ease both; display:flex;flex-direction:column; }
    .rapat-card:hover { transform:translateY(-3px);box-shadow:var(--shadow-md);border-color:rgba(79,106,240,.2); }
    .rapat-card-inner { display:flex; height:100%; }
    .rapat-stripe { width:4px;flex-shrink:0; }
    .stripe-blue   { background:linear-gradient(180deg,var(--accent),var(--purple)); }
    .stripe-amber  { background:linear-gradient(180deg,var(--amber),#F97316); }
    .stripe-green  { background:linear-gradient(180deg,var(--green),var(--teal)); }
    .rapat-body { padding:20px;flex:1;display:flex;flex-direction:column;gap:14px; }
    .rapat-head { display:flex;align-items:flex-start;justify-content:space-between;gap:12px; }
    .rapat-title { font-size:15px;font-weight:700;color:var(--text-main);line-height:1.4;flex:1; }
    .rapat-date-badge { display:flex;flex-direction:column;align-items:center;background:var(--surface);border:1px solid var(--border); border-radius:10px;padding:8px 12px;flex-shrink:0;min-width:54px; }
    .rapat-date-badge .day   { font-size:20px;font-weight:800;color:var(--accent);line-height:1; }
    .rapat-date-badge .month { font-size:10px;font-weight:700;color:var(--text-soft);text-transform:uppercase;letter-spacing:.5px; }
    .rapat-kegiatan { display:flex;align-items:center;gap:8px;font-size:12px;color:var(--text-mid); }
    .rapat-kegiatan i { font-size:11px;color:var(--text-soft); }
    .rapat-kegiatan span { font-weight:600; }
    .rapat-files { display:flex;gap:8px;flex-wrap:wrap; }
    .file-chip { display:inline-flex;align-items:center;gap:6px; padding:6px 12px;border-radius:8px; font-size:12px;font-weight:600; text-decoration:none;transition:all var(--transition); }
    .chip-pdf   { background:#FFF1F2;color:var(--rose); }
    .chip-foto  { background:#EEF1FF;color:var(--accent); cursor:pointer;border:none;font-family:inherit; }
    .chip-none  { background:var(--surface);color:var(--text-soft); font-style:italic; }
    .chip-pdf:hover  { background:var(--rose);color:#fff; }
    .chip-foto:hover { background:var(--accent);color:#fff; }
    .rapat-footer { padding:14px 20px; border-top:1px solid var(--border); background:var(--surface); display:flex;align-items:center;gap:8px; }
    .btn-sm { display:inline-flex;align-items:center;gap:6px; padding:6px 12px; border-radius:8px; border:none;cursor:pointer; font-size:12px;font-weight:600; font-family:inherit; transition:all var(--transition); }
    .btn-edit   { background:#EEF1FF;color:var(--accent); }
    .btn-delete { background:#FFF1F2;color:var(--rose); }
    .btn-edit:hover   { background:var(--accent);color:#fff; }
    .btn-delete:hover { background:var(--rose);color:#fff; }

    .empty-state { text-align:center;padding:64px 20px;color:var(--text-soft); grid-column:1/-1; }
    .empty-state .ei { width:72px;height:72px; border-radius:20px; background:var(--surface); margin:0 auto 18px; display:flex;align-items:center;justify-content:center; font-size:32px; }
    .empty-state h3 { font-size:16px;font-weight:600;color:var(--text-mid);margin-bottom:8px; }
    .empty-state p  { font-size:13px; }

    .modal-overlay { display:none;position:fixed;inset:0; background:rgba(15,27,76,.45); z-index:500; backdrop-filter:blur(4px); align-items:center;justify-content:center; padding:20px; }
    .modal-overlay.open { display:flex; }
    .modal-box { background:var(--card); border-radius:20px; width:100%;max-width:560px; box-shadow:var(--shadow-lg); max-height:90vh;display:flex;flex-direction:column; animation:modalIn .25s cubic-bezier(.34,1.56,.64,1); }
    .modal-box.wide { max-width:680px; }
    @keyframes modalIn { from{opacity:0;transform:scale(.92) translateY(20px)} to{opacity:1;transform:scale(1) translateY(0)} }
    .modal-header { padding:22px 28px 18px; border-bottom:1px solid var(--border); display:flex;align-items:center;justify-content:space-between; flex-shrink:0; }
    .modal-header-left { display:flex;align-items:center;gap:14px; }
    .modal-header-icon { width:42px;height:42px; border-radius:12px; display:flex;align-items:center;justify-content:center; font-size:18px; }
    .icon-blue  { background:var(--accent-soft);color:var(--accent); }
    .icon-amber { background:#FFFBEB;color:var(--amber); }
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
    .form-label small { font-weight:400;color:var(--text-soft);text-transform:none;font-size:10px;margin-left:6px; }
    .form-control { padding:10px 14px; border:1.5px solid var(--border); border-radius:10px; font-size:14px;font-family:inherit;color:var(--text-main); background:var(--surface); transition:all var(--transition);outline:none; }
    .form-control:focus { border-color:var(--accent);background:var(--card);box-shadow:0 0 0 3px rgba(79,106,240,.12); }
    .notulensi-strip { display:flex;align-items:center;gap:12px; padding:12px 14px; background:var(--surface); border:1px solid var(--border); border-radius:10px; }
    .notulensi-strip i { font-size:24px;color:var(--rose); }
    .notulensi-strip a { font-size:13px;font-weight:600;color:var(--accent);text-decoration:none; flex:1; }
    .notulensi-strip a:hover { text-decoration:underline; }
    .dok-upload-area { border:2px dashed var(--border);border-radius:12px;padding:20px;text-align:center;cursor:pointer;transition:border-color var(--transition); }
    .dok-upload-area:hover { border-color:var(--accent); }
    .dok-upload-area i { font-size:28px;color:var(--text-soft);margin-bottom:8px;display:block; }
    .dok-upload-area p { font-size:13px;color:var(--text-soft); }
    .dok-preview-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(80px,1fr));gap:8px;margin-top:12px; }
    .dok-preview-item { width:100%;aspect-ratio:1;border-radius:8px;overflow:hidden; }
    .dok-preview-item img { width:100%;height:100%;object-fit:cover; }
    .gallery-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:12px; }
    .gallery-item { aspect-ratio:1;border-radius:10px;overflow:hidden;cursor:pointer;transition:transform var(--transition); }
    .gallery-item:hover { transform:scale(1.03); }
    .gallery-item img { width:100%;height:100%;object-fit:cover; }
    .lightbox { display:none;position:fixed;inset:0;background:rgba(0,0,0,.9);z-index:1000;align-items:center;justify-content:center; }
    .lightbox.open { display:flex; }
    .lightbox img { max-width:90vw;max-height:90vh;border-radius:12px;box-shadow:0 8px 40px rgba(0,0,0,.4); }
    .lightbox-close { position:fixed;top:20px;right:24px;width:40px;height:40px;border-radius:10px;background:rgba(255,255,255,.15);backdrop-filter:blur(8px);border:none;color:#fff;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center; }
    .overlay-mob { display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:199;backdrop-filter:blur(2px); }

    @media (max-width:1200px) { .stats-row{grid-template-columns:repeat(2,1fr)} }
    @media (max-width:768px) {
      .sidebar{transform:translateX(-100%)} .sidebar.open{transform:translateX(0)}
      .overlay-mob.open{display:block} .main{margin-left:0}
      .topbar-toggle{display:flex} .content{padding:20px 16px}
      .stats-row{grid-template-columns:1fr 1fr;gap:12px}
      .rapat-grid{grid-template-columns:1fr}
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
    <a href="dashboard.php"           class="nav-item"><span class="nav-icon"><i class="fas fa-chart-pie"></i></span>Dashboard</a>
    <a href="profile-ukm.php"         class="nav-item"><span class="nav-icon"><i class="fas fa-id-card"></i></span>Profil UKM</a>
    <a href="struktur_organisasi.php" class="nav-item"><span class="nav-icon"><i class="fas fa-sitemap"></i></span>Struktur Organisasi</a>
    <a href="timeline.php"            class="nav-item"><span class="nav-icon"><i class="fas fa-calendar-alt"></i></span>Timeline Kegiatan</a>
    <a href="keanggotaan.php"         class="nav-item"><span class="nav-icon"><i class="fas fa-user-group"></i></span>Keanggotaan</a>
    <a href="rapat.php"               class="nav-item active"><span class="nav-icon"><i class="fas fa-comments"></i></span>Rapat</a>
    <div class="sidebar-section-label" style="padding-top:12px;">Pendaftaran</div>
    <a href="manajemen-periode.php"   class="nav-item"><span class="nav-icon"><i class="fas fa-clock-rotate-left"></i></span>Periode Pendaftaran</a>
    <a href="manajemen-pendaftar.php" class="nav-item"><span class="nav-icon"><i class="fas fa-clipboard-list"></i></span>Manajemen Pendaftar</a>
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
      <h1>Manajemen Rapat</h1>
      <p>Catat dan kelola rapat kegiatan UKM</p>
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
      <div class="mini-stat"><div class="mini-icon mi-blue"><i class="fas fa-comments"></i></div><div class="mini-body"><h2 id="stat-total">—</h2><p>Total Rapat</p></div></div>
      <div class="mini-stat"><div class="mini-icon mi-amber"><i class="fas fa-calendar-week"></i></div><div class="mini-body"><h2 id="stat-bulan">—</h2><p>Bulan Ini</p></div></div>
      <div class="mini-stat"><div class="mini-icon mi-green"><i class="fas fa-file-lines"></i></div><div class="mini-body"><h2 id="stat-notulensi">—</h2><p>Ada Notulensi</p></div></div>
      <div class="mini-stat"><div class="mini-icon mi-purple"><i class="fas fa-images"></i></div><div class="mini-body"><h2 id="stat-foto">—</h2><p>Ada Dokumentasi</p></div></div>
    </div>

    <!-- Toolbar -->
    <div class="toolbar">
      <div class="search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" class="search-input" placeholder="Cari rapat atau kegiatan terkait…" oninput="searchRapat(this.value)">
      </div>
      <div class="toolbar-right">
        <button class="btn-primary" onclick="openModalTambah()"><i class="fas fa-plus"></i> Tambah Rapat</button>
      </div>
    </div>

    <!-- Rapat Grid -->
    <div class="rapat-grid" id="rapat-grid">
      <div style="background:var(--card);border-radius:var(--radius);border:1px solid var(--border);height:220px;animation:fadeUp .4s ease;"></div>
      <div style="background:var(--card);border-radius:var(--radius);border:1px solid var(--border);height:220px;animation:fadeUp .4s ease .05s both;"></div>
      <div style="background:var(--card);border-radius:var(--radius);border:1px solid var(--border);height:220px;animation:fadeUp .4s ease .10s both;"></div>
    </div>
  </main>
</div>

<!-- ════════ MODAL: TAMBAH / EDIT RAPAT ════════ -->
<div class="modal-overlay" id="modal-form">
  <div class="modal-box wide">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-header-icon icon-blue"><i class="fas fa-comment-dots" id="modal-icon"></i></div>
        <div>
          <div class="modal-title" id="modal-title">Tambah Rapat</div>
          <div class="modal-subtitle" id="modal-subtitle">Isi informasi rapat baru</div>
        </div>
      </div>
      <button class="modal-close" onclick="closeModal('modal-form')"><i class="fas fa-times"></i></button>
    </div>
    <form id="form-rapat" enctype="multipart/form-data">
      <input type="hidden" id="id_rapat" name="id_rapat">
      <div class="modal-body">
        <div class="form-row-2">
          <div class="form-field">
            <label class="form-label">Judul Rapat <span>*</span></label>
            <input type="text" class="form-control" id="judul" name="judul" placeholder="Judul rapat…" required>
          </div>
          <div class="form-field">
            <label class="form-label">Tanggal Rapat <span>*</span></label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
          </div>
        </div>
        <div class="form-field">
          <label class="form-label">Kegiatan Terkait <small>(opsional)</small></label>
          <select class="form-control" id="id_timeline" name="id_timeline">
            <option value="">Tidak terkait kegiatan tertentu</option>
          </select>
        </div>
        <div class="form-field">
          <label class="form-label">File Notulensi <small>(PDF, DOC, DOCX — maks 5MB)</small></label>
          <input type="file" class="form-control" id="notulensi" name="notulensi" accept=".pdf,.doc,.docx" style="padding:8px 14px;">
          <div id="notulensi-existing" style="display:none;margin-top:8px;"></div>
        </div>
        <div class="form-field">
          <label class="form-label">Dokumentasi Foto <small>(JPG/PNG, maks 2MB/foto)</small></label>
          <div class="dok-upload-area" onclick="document.getElementById('dokumentasi').click()">
            <i class="fas fa-images"></i>
            <p>Klik untuk pilih foto dokumentasi</p>
          </div>
          <input type="file" id="dokumentasi" name="dokumentasi[]" accept="image/*" multiple style="display:none;" onchange="previewDok(this)">
          <div class="dok-preview-grid" id="dok-preview"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-secondary" onclick="closeModal('modal-form')">Batal</button>
        <button type="submit" class="btn-primary" id="btn-submit">
          <i class="fas fa-save"></i> <span id="btn-submit-text">Simpan Rapat</span>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ════════ MODAL: GALLERY ════════ -->
<div class="modal-overlay" id="modal-gallery">
  <div class="modal-box wide">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-header-icon icon-blue"><i class="fas fa-images"></i></div>
        <div>
          <div class="modal-title">Dokumentasi Rapat</div>
          <div class="modal-subtitle" id="gallery-subtitle">—</div>
        </div>
      </div>
      <button class="modal-close" onclick="closeModal('modal-gallery')"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <div class="gallery-grid" id="gallery-grid"><p style="color:var(--text-soft);font-size:13px;">Memuat foto…</p></div>
    </div>
    <div class="modal-footer"><button class="btn-secondary" onclick="closeModal('modal-gallery')">Tutup</button></div>
  </div>
</div>

<!-- Lightbox -->
<div class="lightbox" id="lightbox" onclick="closeLightbox()">
  <button class="lightbox-close"><i class="fas fa-times"></i></button>
  <img id="lightbox-img" src="" alt="">
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
  document.querySelectorAll('.modal-overlay').forEach(el=>el.addEventListener('click',e=>{if(e.target===el)el.classList.remove('open');}));

  /* ── DATA ── */
  let allRapat=[];
  let searchQ='';

  /* ── LOAD ── */
  function loadRapat(){
    fetch(`/backend/controllers/admin-ukm/rapat.php?id_ukm=${id_ukm}`)
      .then(r=>r.json())
      .then(res=>{
        const data=res.status==='success'?res.data:(Array.isArray(res)?res:[]);
        allRapat=data;updateStats();renderGrid();
      })
      .catch(()=>{
        allRapat=[
          {id_rapat:1,judul:'Rapat Koordinasi Bulanan',tanggal:'2025-02-10',judul_kegiatan:'WIBU MEET 2025',notulensi_path:'notulensi_1.pdf',jumlah_foto:4},
          {id_rapat:2,judul:'Evaluasi Kegiatan Open House',tanggal:'2025-02-18',judul_kegiatan:'Open House UKM',notulensi_path:'',jumlah_foto:6},
          {id_rapat:3,judul:'Rapat Persiapan PCC Class',tanggal:'2025-03-05',judul_kegiatan:'PCC Class Batch 3',notulensi_path:'notulensi_3.pdf',jumlah_foto:0},
          {id_rapat:4,judul:'Rapat Divisi Internal',tanggal:'2025-03-12',judul_kegiatan:null,notulensi_path:'',jumlah_foto:2},
        ];
        updateStats();renderGrid();
      });
  }

  function updateStats(){
    const now=new Date(),month=now.getMonth(),year=now.getFullYear();
    document.getElementById('stat-total').textContent     = allRapat.length;
    document.getElementById('stat-bulan').textContent     = allRapat.filter(d=>{const t=new Date(d.tanggal);return t.getMonth()===month&&t.getFullYear()===year;}).length;
    document.getElementById('stat-notulensi').textContent = allRapat.filter(d=>d.notulensi_path).length;
    document.getElementById('stat-foto').textContent      = allRapat.filter(d=>d.jumlah_foto>0).length;
  }

  /* ── FILTER ── */
  function searchRapat(v){searchQ=v.toLowerCase();renderGrid();}
  function getFiltered(){return allRapat.filter(d=>!searchQ||d.judul?.toLowerCase().includes(searchQ)||d.judul_kegiatan?.toLowerCase().includes(searchQ));}

  /* ── RENDER ── */
  const stripes=['stripe-blue','stripe-amber','stripe-green'];

  function renderGrid(){
    const data=getFiltered();
    const grid=document.getElementById('rapat-grid');
    if(!data.length){
      grid.innerHTML=`<div class="empty-state"><div class="ei">💬</div><h3>Belum ada rapat</h3><p>Klik "Tambah Rapat" untuk mencatat rapat baru</p></div>`;
      return;
    }
    grid.innerHTML=data.map((d,i)=>{
      const tgl=new Date(d.tanggal);
      const day=tgl.toLocaleDateString('id-ID',{day:'2-digit'});
      const month=tgl.toLocaleDateString('id-ID',{month:'short'}).toUpperCase();
      const stripe=stripes[i%3];
      const notulensiChip=d.notulensi_path
        ?`<a class="file-chip chip-pdf" href="/frontend/public/assets/notulensi/${d.notulensi_path}" target="_blank"><i class="fas fa-file-pdf"></i> Notulensi</a>`
        :`<span class="file-chip chip-none"><i class="fas fa-file-slash"></i> Belum ada notulensi</span>`;
      const fotoChip=d.jumlah_foto>0
        ?`<button class="file-chip chip-foto" onclick="openGallery(${d.id_rapat},'${esc(d.judul)}')"><i class="fas fa-images"></i> ${d.jumlah_foto} Foto</button>`
        :`<span class="file-chip chip-none"><i class="fas fa-image-slash"></i> Belum ada foto</span>`;
      return `
        <div class="rapat-card" style="animation-delay:${i*0.04}s;">
          <div class="rapat-card-inner">
            <div class="rapat-stripe ${stripe}"></div>
            <div style="flex:1;display:flex;flex-direction:column;">
              <div class="rapat-body">
                <div class="rapat-head">
                  <div class="rapat-title">${d.judul}</div>
                  <div class="rapat-date-badge"><span class="day">${day}</span><span class="month">${month}</span></div>
                </div>
                ${d.judul_kegiatan
                  ?`<div class="rapat-kegiatan"><i class="fas fa-calendar-days"></i><span>Kegiatan:</span>${d.judul_kegiatan}</div>`
                  :`<div class="rapat-kegiatan" style="color:var(--text-soft);"><i class="fas fa-calendar-xmark"></i> Tidak terkait kegiatan</div>`}
                <div class="rapat-files">${notulensiChip}${fotoChip}</div>
              </div>
              <div class="rapat-footer">
                <button class="btn-sm btn-edit" onclick="editRapat(${d.id_rapat})"><i class="fas fa-pen"></i> Edit</button>
                <button class="btn-sm btn-delete" onclick="deleteRapat(${d.id_rapat})"><i class="fas fa-trash"></i> Hapus</button>
              </div>
            </div>
          </div>
        </div>`;
    }).join('');
  }

  /* ── KEGIATAN DROPDOWN ── */
  function loadKegiatanDropdown(){
    return fetch(`/backend/controllers/admin-ukm/timeline.php?id_ukm=${id_ukm}`)
      .then(r=>r.json())
      .then(data=>{
        const sel=document.getElementById('id_timeline');
        sel.innerHTML='<option value="">Tidak terkait kegiatan tertentu</option>'+
          (Array.isArray(data)?data:[]).map(k=>`<option value="${k.id_timeline}">${k.judul_kegiatan} (${fmtDate(k.tanggal_kegiatan)})</option>`).join('');
      });
  }

  /* ── OPEN TAMBAH ── */
  function openModalTambah(){
    document.getElementById('form-rapat').reset();
    document.getElementById('id_rapat').value='';
    document.getElementById('dok-preview').innerHTML='';
    document.getElementById('notulensi-existing').style.display='none';
    document.getElementById('modal-title').textContent='Tambah Rapat';
    document.getElementById('modal-subtitle').textContent='Isi informasi rapat baru';
    document.getElementById('modal-icon').className='fas fa-comment-dots';
    document.getElementById('btn-submit-text').textContent='Simpan Rapat';
    loadKegiatanDropdown();
    document.getElementById('modal-form').classList.add('open');
  }

  /* ── EDIT ── */
  function editRapat(id){
    fetch(`/backend/controllers/admin-ukm/rapat.php?id_rapat=${id}`)
      .then(r=>r.json())
      .then(res=>{
        const d=res.status==='success'?res.data:res;
        document.getElementById('id_rapat').value=d.id_rapat;
        document.getElementById('judul').value=d.judul||'';
        document.getElementById('tanggal').value=d.tanggal||'';
        document.getElementById('dok-preview').innerHTML='';
        if(d.notulensi_path){
          const strip=document.getElementById('notulensi-existing');
          strip.style.display='flex';
          strip.innerHTML=`<div class="notulensi-strip"><i class="fas fa-file-pdf"></i><a href="/frontend/public/assets/notulensi/${d.notulensi_path}" target="_blank">Lihat notulensi saat ini</a><span style="font-size:11px;color:var(--text-soft);">Upload baru untuk mengganti</span></div>`;
        } else { document.getElementById('notulensi-existing').style.display='none'; }
        document.getElementById('modal-title').textContent='Edit Rapat';
        document.getElementById('modal-subtitle').textContent='Ubah data rapat';
        document.getElementById('modal-icon').className='fas fa-comment-edit';
        document.getElementById('btn-submit-text').textContent='Update Rapat';
        loadKegiatanDropdown().then(()=>{document.getElementById('id_timeline').value=d.id_timeline||'';});
        document.getElementById('modal-form').classList.add('open');
      });
  }

  /* ── SUBMIT ── */
  document.getElementById('form-rapat').addEventListener('submit',function(e){
    e.preventDefault();
    const btn=document.getElementById('btn-submit');
    btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Menyimpan…';
    fetch('/backend/controllers/admin-ukm/rapat.php',{method:'POST',body:new FormData(this)})
      .then(r=>r.json())
      .then(res=>{
        if(res.status==='success'){Swal.fire({icon:'success',title:'Berhasil!',text:'Rapat berhasil disimpan',timer:1500,showConfirmButton:false});closeModal('modal-form');loadRapat();}
        else Swal.fire({icon:'error',title:'Gagal!',text:res.message});
      })
      .catch(()=>Swal.fire({icon:'error',title:'Error',text:'Terjadi kesalahan jaringan'}))
      .finally(()=>{btn.disabled=false;btn.innerHTML='<i class="fas fa-save"></i> <span id="btn-submit-text">Simpan Rapat</span>';});
  });

  /* ── DELETE ── */
  function deleteRapat(id){
    Swal.fire({title:'Hapus rapat ini?',text:'Data rapat akan dihapus permanen.',icon:'warning',showCancelButton:true,confirmButtonColor:'#F43F5E',cancelButtonColor:'#94A3B8',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'})
      .then(r=>{
        if(r.isConfirmed){
          fetch(`/backend/controllers/admin-ukm/rapat.php?id_rapat=${id}`,{method:'DELETE'})
            .then(r=>r.json()).then(res=>{
              if(res.status==='success'){Swal.fire({icon:'success',title:'Terhapus!',timer:1500,showConfirmButton:false});loadRapat();}
              else Swal.fire({icon:'error',title:'Gagal!',text:res.message});
            });
        }
      });
  }

  /* ── PREVIEW DOK ── */
  function previewDok(input){
    const container=document.getElementById('dok-preview');
    container.innerHTML='';
    [...input.files].forEach(file=>{
      const reader=new FileReader();
      reader.onload=e=>{
        const div=document.createElement('div');div.className='dok-preview-item';
        div.innerHTML=`<img src="${e.target.result}" alt="">`;container.appendChild(div);
      };
      reader.readAsDataURL(file);
    });
  }

  /* ── GALLERY ── */
  function openGallery(id,judul){
    document.getElementById('gallery-subtitle').textContent=judul;
    document.getElementById('gallery-grid').innerHTML='<p style="color:var(--text-soft);font-size:13px;">Memuat foto…</p>';
    document.getElementById('modal-gallery').classList.add('open');
    fetch(`/backend/controllers/admin-ukm/rapat.php?id_rapat=${id}`)
      .then(r=>r.json())
      .then(res=>{
        const dok=(res.status==='success'?res.data?.dokumentasi:null)||[];
        const grid=document.getElementById('gallery-grid');
        if(!dok.length){grid.innerHTML='<p style="color:var(--text-soft);font-size:13px;">Tidak ada foto dokumentasi</p>';return;}
        grid.innerHTML=dok.map(f=>`<div class="gallery-item" onclick="openLightbox('/frontend/public/assets/dokumentasi/${f.foto_path}')"><img src="/frontend/public/assets/dokumentasi/${f.foto_path}" alt="" loading="lazy"></div>`).join('');
      });
  }

  /* ── LIGHTBOX ── */
  function openLightbox(src){document.getElementById('lightbox-img').src=src;document.getElementById('lightbox').classList.add('open');}
  function closeLightbox(){document.getElementById('lightbox').classList.remove('open');}

  /* ── HELPERS ── */
  function fmtDate(str){if(!str)return'—';return new Date(str).toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'});}
  function esc(s){return(s||'').replace(/'/g,"\\'");}

  function logout(){
    Swal.fire({title:'Keluar dari SIGMA?',icon:'question',showCancelButton:true,confirmButtonColor:'#F43F5E',cancelButtonColor:'#94A3B8',confirmButtonText:'Ya, keluar',cancelButtonText:'Batal'})
      .then(r=>{if(r.isConfirmed)fetch('/backend/controllers/logout.php').then(()=>window.location.href='/index.html').catch(()=>window.location.href='/index.html');});
  }

  /* ── INIT ── */
  document.addEventListener('DOMContentLoaded', () => {
    loadUkmProfile(); // ← nama UKM → sidebar
    loadRapat();
    loadKegiatanDropdown();
  });
</script>
</body>
</html>