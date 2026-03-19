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
  <title>Periode Pendaftaran — Admin UKM SIGMA</title>
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
    .content { flex: 1; padding: 28px; display: flex; flex-direction: column; gap: 24px; }

    @keyframes fadeUp { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }

    /* ── PAGE GRID ── */
    .page-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }

    /* ── CARD ── */
    .card {
      background: var(--card); border: 1px solid var(--border);
      border-radius: var(--radius); box-shadow: var(--shadow-sm);
      overflow: hidden; animation: fadeUp 0.4s ease both;
    }
    .card:nth-child(1){animation-delay:.05s} .card:nth-child(2){animation-delay:.10s}

    .card-head {
      padding: 20px 24px 18px;
      border-bottom: 1px solid var(--border);
      display: flex; align-items: center; gap: 14px;
    }
    .card-head-icon {
      width: 38px; height: 38px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      font-size: 16px; flex-shrink: 0;
    }
    .icon-blue   { background: var(--accent-soft); color: var(--accent); }
    .icon-teal   { background: #CCFBF1; color: var(--teal); }
    .icon-amber  { background: #FFFBEB; color: var(--amber); }
    .icon-green  { background: #ECFDF5; color: var(--green); }
    .icon-purple { background: #EDE9FE; color: var(--purple); }

    .card-head-text h3 { font-size: 15px; font-weight: 700; color: var(--text-main); }
    .card-head-text p  { font-size: 12px; color: var(--text-soft); margin-top: 2px; }

    .card-body { padding: 24px; }

    /* ── FORM ── */
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
    .form-field { display: flex; flex-direction: column; gap: 6px; }
    .form-field.span-2 { grid-column: span 2; }

    .form-label { font-size: 12px; font-weight: 700; color: var(--text-mid); letter-spacing: 0.4px; text-transform: uppercase; }
    .form-label span { color: var(--rose); margin-left: 2px; }

    .form-control {
      padding: 10px 14px; border: 1.5px solid var(--border); border-radius: 10px;
      font-size: 14px; font-family: inherit; color: var(--text-main);
      background: var(--surface); transition: all var(--transition); outline: none;
    }
    .form-control:focus { border-color: var(--accent); background: var(--card); box-shadow: 0 0 0 3px rgba(79,106,240,0.12); }

    /* Duration input with unit label */
    .input-wrap { position: relative; }
    .input-unit {
      position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
      font-size: 12px; font-weight: 600; color: var(--text-soft);
      pointer-events: none;
    }
    .input-wrap .form-control { padding-right: 44px; }

    .form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 4px; }

    .btn-primary {
      display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px;
      background: var(--accent); color: #fff; border: none; border-radius: 10px;
      font-size: 13px; font-weight: 600; cursor: pointer; font-family: inherit;
      transition: all var(--transition); box-shadow: 0 4px 12px rgba(79,106,240,0.3);
    }
    .btn-primary:hover { background: #3d59e0; transform: translateY(-1px); }
    .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

    .btn-secondary {
      display: inline-flex; align-items: center; gap: 8px; padding: 10px 22px;
      background: var(--surface); color: var(--text-mid);
      border: 1px solid var(--border); border-radius: 10px;
      font-size: 13px; font-weight: 600; cursor: pointer; font-family: inherit;
      transition: all var(--transition);
    }
    .btn-secondary:hover { background: var(--border); }

    /* ── PERIODE STATUS CARD ── */
    .status-empty {
      text-align: center; padding: 40px 0; color: var(--text-soft);
    }
    .status-empty .empty-icon { width: 60px; height: 60px; border-radius: 16px; background: var(--surface); margin: 0 auto 14px; display: flex; align-items: center; justify-content: center; font-size: 26px; }
    .status-empty h3 { font-size: 15px; font-weight: 600; color: var(--text-mid); margin-bottom: 6px; }
    .status-empty p  { font-size: 13px; }

    /* Phase cards */
    .phase-row { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; margin-bottom: 24px; }

    .phase-card {
      border-radius: 12px; padding: 16px;
      border: 1.5px solid var(--border);
      position: relative; overflow: hidden;
    }
    .phase-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; }
    .phase-card.p1::before { background: var(--accent); }
    .phase-card.p2::before { background: var(--amber); }
    .phase-card.p3::before { background: var(--green); }

    .phase-card .phase-label { font-size: 11px; font-weight: 700; letter-spacing: 0.8px; text-transform: uppercase; margin-bottom: 10px; }
    .phase-card.p1 .phase-label { color: var(--accent); }
    .phase-card.p2 .phase-label { color: var(--amber); }
    .phase-card.p3 .phase-label { color: var(--green); }

    .phase-dates { font-size: 13px; color: var(--text-main); font-weight: 600; line-height: 1.6; }
    .phase-dates small { display: block; font-size: 11px; color: var(--text-soft); font-weight: 400; }

    .phase-badge {
      display: inline-flex; align-items: center; gap: 5px;
      margin-top: 10px; padding: 3px 10px; border-radius: 20px;
      font-size: 11px; font-weight: 700;
    }
    .badge-active  { background: #ECFDF5; color: var(--green); }
    .badge-waiting { background: #FFFBEB; color: var(--amber); }
    .badge-done    { background: var(--surface); color: var(--text-soft); }

    /* ── TIMELINE ── */
    .timeline-wrap { position: relative; padding-left: 28px; }

    .timeline-wrap::before {
      content: ''; position: absolute; left: 10px; top: 8px; bottom: 8px;
      width: 2px; background: var(--border); border-radius: 2px;
    }

    .tl-item { position: relative; margin-bottom: 20px; }
    .tl-item:last-child { margin-bottom: 0; }

    .tl-dot {
      position: absolute; left: -23px; top: 4px;
      width: 12px; height: 12px; border-radius: 50%;
      border: 2px solid var(--card);
      box-shadow: 0 0 0 2px var(--border);
    }
    .tl-dot.blue   { background: var(--accent); box-shadow: 0 0 0 2px rgba(79,106,240,0.3); }
    .tl-dot.amber  { background: var(--amber);  box-shadow: 0 0 0 2px rgba(245,158,11,0.3); }
    .tl-dot.green  { background: var(--green);  box-shadow: 0 0 0 2px rgba(16,185,129,0.3); }
    .tl-dot.rose   { background: var(--rose);   box-shadow: 0 0 0 2px rgba(244,63,94,0.3); }

    .tl-content { background: var(--surface); border: 1px solid var(--border); border-radius: 10px; padding: 12px 16px; }
    .tl-title   { font-size: 13px; font-weight: 700; color: var(--text-main); margin-bottom: 3px; }
    .tl-date    { font-size: 12px; color: var(--text-soft); }
    .tl-detail  { font-size: 12px; color: var(--text-mid); margin-top: 6px; display: flex; gap: 14px; flex-wrap: wrap; }
    .tl-detail span { display: flex; align-items: center; gap: 4px; }

    /* ── MOBILE OVERLAY ── */
    .overlay-mob { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.45); z-index: 199; backdrop-filter: blur(2px); }

    /* ── RESPONSIVE ── */
    @media (max-width: 1024px) { .page-grid { grid-template-columns: 1fr; } }
    @media (max-width: 768px) {
      .sidebar { transform: translateX(-100%); }
      .sidebar.open { transform: translateX(0); }
      .overlay-mob.open { display: block; }
      .main { margin-left: 0; }
      .topbar-toggle { display: flex; }
      .content { padding: 20px 16px; }
      .form-grid-2, .form-grid-3 { grid-template-columns: 1fr; }
      .phase-row { grid-template-columns: 1fr; }
      .form-field.span-2 { grid-column: span 1; }
    }
    @media (max-width: 480px) { .topbar { padding: 0 16px; } .admin-name { display: none; } }
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
    <a href="manajemen-periode.php"   class="nav-item active"><span class="nav-icon"><i class="fas fa-clock-rotate-left"></i></span>Periode Pendaftaran</a>
    <a href="manajemen-pendaftar.php" class="nav-item"><span class="nav-icon"><i class="fas fa-clipboard-list"></i></span>Manajemen Pendaftar</a>
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
      <h1>Periode Pendaftaran</h1>
      <p>Atur jadwal dan durasi setiap tahap pendaftaran</p>
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
    <div class="page-grid">

      <!-- ── FORM CARD ── -->
      <div class="card">
        <div class="card-head">
          <div class="card-head-icon icon-blue"><i class="fas fa-calendar-plus"></i></div>
          <div class="card-head-text">
            <h3>Atur Periode Baru</h3>
            <p>Tentukan tanggal buka dan durasi tiap tahap</p>
          </div>
        </div>
        <div class="card-body">
          <form id="form-periode" onsubmit="submitPeriode(event)">
            <div style="display:flex;flex-direction:column;gap:16px;">

              <!-- Tanggal buka & tutup -->
              <div class="form-grid-2">
                <div class="form-field">
                  <label class="form-label">Tanggal Buka <span>*</span></label>
                  <input type="datetime-local" class="form-control" name="tanggal_buka" id="tanggal_buka" required>
                </div>
                <div class="form-field">
                  <label class="form-label">Tanggal Tutup <span>*</span></label>
                  <input type="datetime-local" class="form-control" name="tanggal_tutup" id="tanggal_tutup" required>
                </div>
              </div>

              <!-- Durasi tiap tahap -->
              <div>
                <div style="font-size:12px;font-weight:700;color:var(--text-mid);letter-spacing:0.4px;text-transform:uppercase;margin-bottom:10px;">
                  Durasi Tiap Tahap
                </div>
                <div class="form-grid-3">
                  <div class="form-field">
                    <label class="form-label" style="color:var(--accent);">Tahap 1</label>
                    <div class="input-wrap">
                      <input type="number" class="form-control" name="batas_waktu_tahap1" id="durasi_1" min="1" required placeholder="7">
                      <span class="input-unit">hari</span>
                    </div>
                  </div>
                  <div class="form-field">
                    <label class="form-label" style="color:var(--amber);">Tahap 2</label>
                    <div class="input-wrap">
                      <input type="number" class="form-control" name="batas_waktu_tahap2" id="durasi_2" min="1" required placeholder="7">
                      <span class="input-unit">hari</span>
                    </div>
                  </div>
                  <div class="form-field">
                    <label class="form-label" style="color:var(--green);">Tahap 3</label>
                    <div class="input-wrap">
                      <input type="number" class="form-control" name="batas_waktu_tahap3" id="durasi_3" min="1" required placeholder="7">
                      <span class="input-unit">hari</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Preview tanggal -->
              <div id="preview-box" style="display:none; background:var(--surface); border:1px solid var(--border); border-radius:12px; padding:16px;">
                <div style="font-size:12px;font-weight:700;color:var(--text-soft);letter-spacing:0.8px;text-transform:uppercase;margin-bottom:12px;">
                  <i class="fas fa-eye" style="margin-right:6px;"></i>Preview Jadwal
                </div>
                <div style="display:flex;flex-direction:column;gap:8px;" id="preview-rows"></div>
              </div>

              <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="resetForm()"><i class="fas fa-rotate-left"></i> Reset</button>
                <button type="submit" class="btn-primary" id="btn-simpan"><i class="fas fa-floppy-disk"></i> Simpan Periode</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- ── INFO PERIODE AKTIF ── -->
      <div class="card">
        <div class="card-head">
          <div class="card-head-icon icon-teal"><i class="fas fa-circle-info"></i></div>
          <div class="card-head-text">
            <h3>Periode Aktif</h3>
            <p>Informasi jadwal pendaftaran yang sedang berjalan</p>
          </div>
        </div>
        <div class="card-body" id="periode-card-body">
          <!-- isi dari JS -->
          <div class="status-empty">
            <div class="empty-icon">⏳</div>
            <h3>Memuat data…</h3>
            <p>Mengambil informasi periode</p>
          </div>
        </div>
      </div>

    </div><!-- /page-grid -->
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
  const id_ukm = <?= $id_ukm ?>;
  const BASE   = '/backend/controllers/admin-ukm/pendaftaran.php';

  /* ── SIDEBAR ── */
  function toggleSidebar(){ document.getElementById('sidebar').classList.toggle('open'); document.getElementById('overlay').classList.toggle('open'); }
  function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('overlay').classList.remove('open'); }

  /* ── NAMA UKM ── */
  function loadUkmProfile(){
    fetch(`/backend/controllers/admin-ukm/profile.php?id_ukm=${id_ukm}`)
      .then(r=>r.json()).then(d=>{
        const el=document.getElementById('sidebar-ukm-info');
        if(el&&d.nama_ukm) el.querySelector('h3').textContent=d.nama_ukm;
      }).catch(()=>{});
  }

  /* ── DATE HELPERS ── */
  function fmtDate(d){ return new Date(d).toLocaleDateString('id-ID',{day:'numeric',month:'long',year:'numeric'}); }
  function fmtDateTime(d){ return new Date(d).toLocaleString('id-ID',{day:'numeric',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit'}); }
  function addDays(d,n){ const r=new Date(d); r.setDate(r.getDate()+n); return r; }

  function calcPhases(start, d1, d2, d3){
    const t1s = new Date(start);
    const t1e = addDays(t1s, d1);
    const t2s = new Date(t1e);
    const t2e = addDays(t2s, d2);
    const t3s = new Date(t2e);
    const t3e = addDays(t3s, d3);
    return { t1s,t1e, t2s,t2e, t3s,t3e };
  }

  /* ── LIVE PREVIEW ── */
  function updatePreview(){
    const buka  = document.getElementById('tanggal_buka').value;
    const d1    = parseInt(document.getElementById('durasi_1').value)||0;
    const d2    = parseInt(document.getElementById('durasi_2').value)||0;
    const d3    = parseInt(document.getElementById('durasi_3').value)||0;
    const box   = document.getElementById('preview-box');
    const rows  = document.getElementById('preview-rows');

    if(!buka||!d1||!d2||!d3){ box.style.display='none'; return; }

    const {t1s,t1e,t2s,t2e,t3s,t3e} = calcPhases(buka,d1,d2,d3);

    const phases = [
      {label:'Tahap 1', color:'var(--accent)', s:t1s, e:t1e, dur:d1},
      {label:'Tahap 2', color:'var(--amber)',  s:t2s, e:t2e, dur:d2},
      {label:'Tahap 3', color:'var(--green)',  s:t3s, e:t3e, dur:d3},
    ];

    rows.innerHTML = phases.map(p=>`
      <div style="display:flex;align-items:center;gap:12px;padding:8px 0;border-bottom:1px solid var(--border);">
        <div style="width:8px;height:8px;border-radius:50%;background:${p.color};flex-shrink:0;"></div>
        <div style="flex:1;">
          <span style="font-size:12px;font-weight:700;color:var(--text-main);">${p.label}</span>
          <span style="font-size:12px;color:var(--text-soft);margin-left:8px;">${fmtDate(p.s)} — ${fmtDate(p.e)}</span>
        </div>
        <span style="font-size:11px;font-weight:600;color:${p.color};">${p.dur} hari</span>
      </div>`).join('');

    box.style.display='block';
  }

  // Pasang event listener pada semua input form
  ['tanggal_buka','tanggal_tutup','durasi_1','durasi_2','durasi_3'].forEach(id=>{
    const el=document.getElementById(id);
    if(el) el.addEventListener('input',updatePreview);
  });

  /* ── LOAD PERIODE AKTIF ── */
  function loadPeriode(){
    fetch(`${BASE}?action=get_periode`)
      .then(r=>r.json())
      .then(res=>{
        if(res.status==='success'&&res.data){
          renderPeriodeAktif(res.data);
          prefillForm(res.data);
        } else {
          renderPeriodeKosong();
        }
      })
      .catch(()=>renderPeriodeKosong('Gagal memuat data'));
  }

  function prefillForm(data){
    if(data.tanggal_buka)       document.getElementById('tanggal_buka').value    = data.tanggal_buka.replace(' ','T').slice(0,16);
    if(data.tanggal_tutup)      document.getElementById('tanggal_tutup').value   = data.tanggal_tutup.replace(' ','T').slice(0,16);
    if(data.batas_waktu_tahap1) document.getElementById('durasi_1').value = data.batas_waktu_tahap1;
    if(data.batas_waktu_tahap2) document.getElementById('durasi_2').value = data.batas_waktu_tahap2;
    if(data.batas_waktu_tahap3) document.getElementById('durasi_3').value = data.batas_waktu_tahap3;
    updatePreview();
  }

  function renderPeriodeKosong(msg='Belum ada periode aktif'){
    document.getElementById('periode-card-body').innerHTML=`
      <div class="status-empty">
        <div class="empty-icon">📅</div>
        <h3>${msg}</h3>
        <p>Isi form di sebelah kiri untuk membuat periode baru</p>
      </div>`;
  }

  function phaseStatus(start, end){
    const now=new Date(), s=new Date(start), e=new Date(end);
    if(now<s) return {label:'Belum Mulai', cls:'badge-waiting'};
    if(now>e) return {label:'Selesai',     cls:'badge-done'};
    return              {label:'Aktif',    cls:'badge-active'};
  }

  function renderPeriodeAktif(d){
    const buka = d.tanggal_buka ?? d.tahap1_start;
    const dur1 = parseInt(d.batas_waktu_tahap1)||0;
    const dur2 = parseInt(d.batas_waktu_tahap2)||0;
    const dur3 = parseInt(d.batas_waktu_tahap3)||0;
    const {t1s,t1e,t2s,t2e,t3s,t3e} = calcPhases(buka,dur1,dur2,dur3);

    const phases=[
      {label:'Tahap 1',cls:'p1',s:t1s,e:t1e,dur:dur1,desc:'Seleksi Administrasi'},
      {label:'Tahap 2',cls:'p2',s:t2s,e:t2e,dur:dur2,desc:'Verifikasi Dokumen'},
      {label:'Tahap 3',cls:'p3',s:t3s,e:t3e,dur:dur3,desc:'Wawancara & Seleksi Akhir'},
    ];

    const tlColors = ['blue','amber','green','rose'];

    document.getElementById('periode-card-body').innerHTML=`
      <div class="phase-row">
        ${phases.map(p=>{
          const st=phaseStatus(p.s,p.e);
          return `
          <div class="phase-card ${p.cls}">
            <div class="phase-label">${p.label}</div>
            <div class="phase-dates">
              ${fmtDate(p.s)}
              <small>sampai ${fmtDate(p.e)}</small>
            </div>
            <div class="phase-badge ${st.cls}">
              <i class="fas fa-circle" style="font-size:7px;"></i> ${st.label} · ${p.dur} hari
            </div>
          </div>`;
        }).join('')}
      </div>

      <div style="font-size:12px;font-weight:700;color:var(--text-soft);letter-spacing:0.8px;text-transform:uppercase;margin-bottom:14px;">
        <i class="fas fa-timeline" style="margin-right:6px;"></i>Timeline
      </div>
      <div class="timeline-wrap">
        ${[
          {title:'Pendaftaran Dibuka', date:t1s, color:'blue', detail:`Tahap 1 dimulai · ${dur1} hari`},
          {title:'Tahap 2 Dimulai',   date:t2s, color:'amber', detail:`Verifikasi Dokumen · ${dur2} hari`},
          {title:'Tahap 3 Dimulai',   date:t3s, color:'green', detail:`Wawancara · ${dur3} hari`},
          {title:'Pendaftaran Ditutup',date:t3e, color:'rose',  detail:`Total ${dur1+dur2+dur3} hari`},
        ].map(item=>`
          <div class="tl-item">
            <div class="tl-dot ${item.color}"></div>
            <div class="tl-content">
              <div class="tl-title">${item.title}</div>
              <div class="tl-date">${fmtDateTime(item.date)}</div>
              <div class="tl-detail">
                <span><i class="fas fa-clock" style="font-size:10px;color:var(--text-soft);"></i> ${item.detail}</span>
              </div>
            </div>
          </div>`).join('')}
      </div>`;
  }

  /* ── SUBMIT ── */
  function submitPeriode(e){
    e.preventDefault();

    const buka   = document.getElementById('tanggal_buka').value;
    const tutup  = document.getElementById('tanggal_tutup').value;
    const d1     = parseInt(document.getElementById('durasi_1').value)||0;
    const d2     = parseInt(document.getElementById('durasi_2').value)||0;
    const d3     = parseInt(document.getElementById('durasi_3').value)||0;

    if(new Date(buka)<new Date()){
      Swal.fire({icon:'error',title:'Tanggal tidak valid',text:'Tanggal buka harus lebih besar dari sekarang.',confirmButtonColor:'#4F6AF0'});
      return;
    }
    if(!d1||!d2||!d3){
      Swal.fire({icon:'warning',title:'Durasi tidak valid',text:'Semua durasi tahap harus diisi dengan angka positif.',confirmButtonColor:'#4F6AF0'});
      return;
    }

    const {t1s,t1e,t2s,t2e,t3s,t3e} = calcPhases(buka,d1,d2,d3);

    Swal.fire({
      title: 'Konfirmasi Periode',
      icon: 'question',
      html: `
        <div style="text-align:left;font-size:14px;line-height:1.8;">
          <b>Tahap 1:</b> ${fmtDate(t1s)} – ${fmtDate(t1e)}<br>
          <b>Tahap 2:</b> ${fmtDate(t2s)} – ${fmtDate(t2e)}<br>
          <b>Tahap 3:</b> ${fmtDate(t3s)} – ${fmtDate(t3e)}<br>
          <b>Total Durasi:</b> ${d1+d2+d3} hari
        </div>`,
      showCancelButton: true,
      confirmButtonColor: '#4F6AF0', cancelButtonColor: '#94A3B8',
      confirmButtonText: 'Simpan', cancelButtonText: 'Batal'
    }).then(r=>{
      if(!r.isConfirmed) return;

      const btn=document.getElementById('btn-simpan');
      btn.disabled=true; btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Menyimpan…';

      const body=new FormData(document.getElementById('form-periode'));
      // Kirim action sesuai format backend lama
      body.append('action','update_periode');

      fetch(BASE, {method:'POST', body})
        .then(r=>r.json())
        .then(res=>{
          if(res.status==='success'){
            Swal.fire({icon:'success',title:'Berhasil!',text:res.message,timer:2000,showConfirmButton:false});
            loadPeriode();
          } else {
            Swal.fire({icon:'error',title:'Gagal!',text:res.message,confirmButtonColor:'#4F6AF0'});
          }
        })
        .catch(()=>Swal.fire({icon:'error',title:'Error',text:'Terjadi kesalahan jaringan',confirmButtonColor:'#4F6AF0'}))
        .finally(()=>{ btn.disabled=false; btn.innerHTML='<i class="fas fa-floppy-disk"></i> Simpan Periode'; });
    });
  }

  function resetForm(){
    document.getElementById('form-periode').reset();
    document.getElementById('preview-box').style.display='none';
  }

  /* ── LOGOUT ── */
  function logout(){
    Swal.fire({title:'Keluar dari SIGMA?',icon:'question',showCancelButton:true,confirmButtonColor:'#F43F5E',cancelButtonColor:'#94A3B8',confirmButtonText:'Ya, keluar',cancelButtonText:'Batal'})
      .then(r=>{if(r.isConfirmed)fetch('/backend/controllers/logout.php').then(()=>location.href='/index.html').catch(()=>location.href='/index.html');});
  }

  /* ── INIT ── */
  document.addEventListener('DOMContentLoaded',()=>{
    loadUkmProfile();
    loadPeriode();
  });
</script>
</body>
</html>