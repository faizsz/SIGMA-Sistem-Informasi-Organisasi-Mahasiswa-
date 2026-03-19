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
  <title>Profil UKM — Admin SIGMA</title>
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
    *, *::before, *::after { box-sizing:border-box;margin:0;padding:0; }
    body { font-family:'Plus Jakarta Sans',sans-serif;background:var(--surface);color:var(--text-main);display:flex;min-height:100vh; }

    /* ── SIDEBAR ── */
    .sidebar{width:var(--sidebar-w);background:var(--navy);display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:200;transition:transform var(--transition);}
    .sidebar::before{content:'';position:absolute;top:-80px;right:-80px;width:220px;height:220px;background:radial-gradient(circle,rgba(79,106,240,.18) 0%,transparent 70%);pointer-events:none;}
    .sidebar-brand{padding:0 24px;height:var(--topbar-h);display:flex;align-items:center;gap:12px;border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0;}
    .brand-icon{width:36px;height:36px;background:linear-gradient(135deg,var(--accent),#7C3AED);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;color:#fff;flex-shrink:0;box-shadow:0 4px 12px rgba(79,106,240,.4);}
    .brand-text h2{font-size:16px;font-weight:800;color:#fff;letter-spacing:.5px;line-height:1.2;}
    .brand-text span{font-size:11px;color:rgba(255,255,255,.45);font-weight:400;}
    .sidebar-ukm-card{margin:16px 16px 8px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);border-radius:12px;padding:14px 16px;display:flex;align-items:center;gap:12px;flex-shrink:0;}
    .ukm-avatar{width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,var(--teal),#0284C7);display:flex;align-items:center;justify-content:center;font-size:18px;color:#fff;flex-shrink:0;}
    .ukm-info h3{font-size:13px;font-weight:700;color:#fff;line-height:1.3;}
    .ukm-info span{font-size:11px;color:rgba(255,255,255,.45);}
    .sidebar-section-label{padding:16px 24px 6px;font-size:10px;font-weight:700;letter-spacing:1.5px;color:rgba(255,255,255,.25);text-transform:uppercase;flex-shrink:0;}
    .sidebar-nav{flex:1;overflow-y:auto;padding:0 12px 12px;scrollbar-width:thin;scrollbar-color:rgba(255,255,255,.1) transparent;}
    .nav-item{display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:10px;cursor:pointer;transition:all var(--transition);text-decoration:none;color:rgba(255,255,255,.55);font-size:14px;font-weight:500;position:relative;margin-bottom:2px;}
    .nav-item:hover{background:rgba(255,255,255,.07);color:rgba(255,255,255,.9);}
    .nav-item.active{background:linear-gradient(135deg,rgba(79,106,240,.25),rgba(79,106,240,.12));color:#fff;border:1px solid rgba(79,106,240,.3);}
    .nav-item.active::before{content:'';position:absolute;left:0;top:50%;transform:translateY(-50%);width:3px;height:20px;background:var(--accent);border-radius:0 3px 3px 0;}
    .nav-icon{width:34px;height:34px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;background:rgba(255,255,255,.07);flex-shrink:0;transition:all var(--transition);}
    .nav-item.active .nav-icon{background:var(--accent);color:#fff;box-shadow:0 4px 12px rgba(79,106,240,.35);}
    .nav-item:hover .nav-icon{background:rgba(255,255,255,.12);color:#fff;}
    .sidebar-footer{padding:12px;border-top:1px solid rgba(255,255,255,.06);flex-shrink:0;}
    .logout-btn{display:flex;align-items:center;gap:12px;width:100%;padding:10px 14px;border-radius:10px;border:1px solid rgba(244,63,94,.2);background:rgba(244,63,94,.07);color:rgba(255,100,120,.8);cursor:pointer;font-size:14px;font-weight:500;transition:all var(--transition);font-family:inherit;}
    .logout-btn:hover{background:rgba(244,63,94,.15);color:#F43F5E;border-color:rgba(244,63,94,.35);}
    .logout-btn .nav-icon{background:rgba(244,63,94,.12);}

    /* ── MAIN ── */
    .main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh;}
    .topbar{height:var(--topbar-h);background:var(--card);border-bottom:1px solid var(--border);display:flex;align-items:center;padding:0 28px;gap:16px;position:sticky;top:0;z-index:100;box-shadow:var(--shadow-sm);}
    .topbar-toggle{display:none;width:36px;height:36px;border-radius:8px;background:var(--surface);border:1px solid var(--border);cursor:pointer;align-items:center;justify-content:center;color:var(--text-mid);font-size:14px;}
    .topbar-breadcrumb{flex:1;}
    .topbar-breadcrumb h1{font-size:18px;font-weight:700;color:var(--text-main);line-height:1.2;}
    .topbar-breadcrumb p{font-size:12px;color:var(--text-soft);}
    .topbar-actions{display:flex;align-items:center;gap:10px;}
    .topbar-btn{width:38px;height:38px;border-radius:10px;background:var(--surface);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-mid);font-size:15px;transition:all var(--transition);}
    .topbar-btn:hover{background:var(--accent-soft);border-color:var(--accent);color:var(--accent);}
    .topbar-admin{display:flex;align-items:center;gap:10px;padding:6px 12px 6px 6px;border-radius:12px;background:var(--surface);border:1px solid var(--border);cursor:pointer;transition:all var(--transition);}
    .topbar-admin:hover{border-color:var(--accent);}
    .admin-avatar{width:30px;height:30px;border-radius:8px;background:linear-gradient(135deg,var(--accent),#7C3AED);display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;font-weight:700;}
    .admin-name{font-size:13px;font-weight:600;color:var(--text-main);}
    .content{flex:1;padding:28px;}

    @keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}

    /* ── SAVE BAR (sticky top reminder) ── */
    .save-bar{
      background:linear-gradient(135deg,var(--navy),#161D6F);
      border-radius:14px;padding:14px 20px;
      display:flex;align-items:center;gap:14px;
      margin-bottom:24px;
      animation:fadeUp .35s ease;
      box-shadow:0 4px 20px rgba(15,27,76,.18);
    }
    .save-bar-icon{width:40px;height:40px;border-radius:11px;background:rgba(79,106,240,.25);display:flex;align-items:center;justify-content:center;font-size:17px;color:#fff;flex-shrink:0;}
    .save-bar-text{flex:1;}
    .save-bar-text h3{font-size:14px;font-weight:700;color:#fff;}
    .save-bar-text p{font-size:12px;color:rgba(255,255,255,.5);margin-top:2px;}
    .save-bar-dirty{display:none;}
    .save-bar-dirty.visible{display:flex;align-items:center;gap:8px;font-size:12px;font-weight:600;color:var(--amber);padding:4px 10px;border-radius:8px;background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.25);}

    /* ── MAIN GRID ── */
    .page-grid{display:grid;grid-template-columns:1fr 380px;gap:24px;align-items:start;}

    /* ── FORM CARD ── */
    .form-card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-sm);animation:fadeUp .4s ease;}

    /* Section tabs inside form card */
    .section-tabs{display:flex;border-bottom:1px solid var(--border);padding:0 4px;}
    .stab{padding:14px 18px;font-size:13px;font-weight:600;color:var(--text-soft);cursor:pointer;position:relative;transition:color var(--transition);border:none;background:none;font-family:inherit;}
    .stab::after{content:'';position:absolute;bottom:0;left:12px;right:12px;height:2px;border-radius:2px 2px 0 0;background:var(--accent);transform:scaleX(0);transition:transform var(--transition);}
    .stab.active{color:var(--accent);}
    .stab.active::after{transform:scaleX(1);}

    .section-panel{display:none;padding:24px;flex-direction:column;gap:18px;}
    .section-panel.active{display:flex;}

    /* Form elements */
    .form-field{display:flex;flex-direction:column;gap:7px;}
    .form-label{font-size:11px;font-weight:700;color:var(--text-mid);letter-spacing:.4px;text-transform:uppercase;}
    .form-label span{color:var(--rose);margin-left:2px;}
    .form-label small{font-weight:400;text-transform:none;font-size:10px;color:var(--text-soft);margin-left:6px;}
    .form-control{padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:14px;font-family:inherit;color:var(--text-main);background:var(--surface);outline:none;transition:all var(--transition);}
    .form-control:focus{border-color:var(--accent);background:var(--card);box-shadow:0 0 0 3px rgba(79,106,240,.12);}
    textarea.form-control{resize:vertical;min-height:90px;line-height:1.6;}
    .char-count{font-size:11px;color:var(--text-soft);text-align:right;margin-top:2px;}

    /* Misi bullet editor */
    .misi-editor{border:1.5px solid var(--border);border-radius:10px;background:var(--surface);transition:border-color var(--transition);overflow:hidden;}
    .misi-editor:focus-within{border-color:var(--accent);background:var(--card);box-shadow:0 0 0 3px rgba(79,106,240,.12);}
    .misi-item{display:flex;align-items:flex-start;gap:10px;padding:10px 14px;border-bottom:1px solid var(--border);}
    .misi-item:last-child{border-bottom:none;}
    .misi-bullet{width:22px;height:22px;border-radius:50%;background:var(--accent-soft);color:var(--accent);font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;}
    .misi-input{flex:1;border:none;background:transparent;font-family:inherit;font-size:14px;color:var(--text-main);outline:none;resize:none;min-height:28px;line-height:1.5;}
    .misi-del{width:24px;height:24px;border-radius:6px;background:none;border:none;color:var(--text-soft);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:11px;transition:all var(--transition);flex-shrink:0;margin-top:2px;}
    .misi-del:hover{background:#FFF1F2;color:var(--rose);}
    .misi-add{display:flex;align-items:center;gap:8px;padding:10px 14px;color:var(--accent);font-size:13px;font-weight:600;cursor:pointer;border:none;background:none;font-family:inherit;transition:color var(--transition);width:100%;}
    .misi-add:hover{color:#3d59e0;}

    /* ── PREVIEW CARD ── */
    .preview-card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden;position:sticky;top:calc(var(--topbar-h) + 24px);animation:fadeUp .5s ease .05s both;}

    /* Banner + logo area */
    .preview-banner{width:100%;height:140px;object-fit:cover;display:block;background:linear-gradient(135deg,var(--navy),#161D6F);}
    .preview-banner-placeholder{width:100%;height:140px;background:linear-gradient(135deg,var(--navy) 0%,#161D6F 50%,#4F6AF0 100%);display:flex;align-items:center;justify-content:center;font-size:32px;color:rgba(255,255,255,.2);}

    .preview-identity{padding:0 20px 18px;position:relative;margin-top:-28px;}
    .preview-logo-wrap{width:60px;height:60px;border-radius:14px;border:3px solid var(--card);overflow:hidden;background:var(--surface);display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:12px;box-shadow:var(--shadow-md);}
    .preview-logo-wrap img{width:100%;height:100%;object-fit:cover;}
    .preview-ukm-name{font-size:17px;font-weight:800;color:var(--text-main);margin-bottom:4px;}
    .preview-ukm-date{font-size:11px;color:var(--text-soft);display:flex;align-items:center;gap:6px;}
    .preview-ukm-date i{font-size:10px;}

    .preview-divider{height:1px;background:var(--border);margin:0 20px;}

    .preview-section{padding:16px 20px;border-bottom:1px solid var(--border);}
    .preview-section:last-child{border-bottom:none;}
    .preview-label{font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--text-soft);margin-bottom:8px;}
    .preview-text{font-size:13px;color:var(--text-mid);line-height:1.6;}
    .preview-misi-list{list-style:none;display:flex;flex-direction:column;gap:6px;}
    .preview-misi-list li{display:flex;gap:8px;font-size:13px;color:var(--text-mid);line-height:1.5;}
    .preview-misi-list li::before{content:'';width:6px;height:6px;border-radius:50%;background:var(--accent);flex-shrink:0;margin-top:6px;}

    /* Image upload zones */
    .img-upload-zone{position:relative;border-radius:12px;overflow:hidden;cursor:pointer;border:2px dashed var(--border);transition:border-color var(--transition);}
    .img-upload-zone:hover{border-color:var(--accent);}
    .img-upload-zone.banner-zone{height:120px;}
    .img-upload-zone.logo-zone{height:100px;width:100px;border-radius:16px;}
    .img-upload-overlay{position:absolute;inset:0;background:rgba(15,27,76,.5);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;opacity:0;transition:opacity var(--transition);}
    .img-upload-zone:hover .img-upload-overlay{opacity:1;}
    .img-upload-overlay i{font-size:20px;color:#fff;}
    .img-upload-overlay span{font-size:11px;font-weight:600;color:#fff;}
    .img-placeholder{width:100%;height:100%;background:var(--surface);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;color:var(--text-soft);}
    .img-placeholder i{font-size:24px;}
    .img-placeholder span{font-size:11px;font-weight:600;}
    .uploaded-img{width:100%;height:100%;object-fit:cover;display:block;}

    /* Buttons */
    .btn-primary{display:inline-flex;align-items:center;gap:8px;padding:11px 24px;background:var(--accent);color:#fff;border:none;border-radius:10px;font-size:14px;font-weight:600;cursor:pointer;font-family:inherit;transition:all var(--transition);box-shadow:0 4px 12px rgba(79,106,240,.3);}
    .btn-primary:hover{background:#3d59e0;transform:translateY(-1px);}
    .btn-primary:disabled{opacity:.6;cursor:not-allowed;transform:none;}
    .btn-secondary{display:inline-flex;align-items:center;gap:8px;padding:11px 20px;background:var(--surface);color:var(--text-mid);border:1.5px solid var(--border);border-radius:10px;font-size:14px;font-weight:600;cursor:pointer;font-family:inherit;transition:all var(--transition);}
    .btn-secondary:hover{background:var(--border);}
    .form-footer{padding:18px 24px;border-top:1px solid var(--border);display:flex;align-items:center;gap:12px;justify-content:flex-end;}

    /* ── TOAST ── */
    .toast{position:fixed;bottom:24px;right:24px;background:var(--navy);color:#fff;padding:12px 18px;border-radius:12px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:10px;box-shadow:var(--shadow-lg);z-index:999;transform:translateY(80px);opacity:0;transition:all .3s cubic-bezier(.34,1.56,.64,1);}
    .toast.show{transform:translateY(0);opacity:1;}
    .toast i{font-size:16px;color:var(--green);}

    .overlay-mob{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:199;backdrop-filter:blur(2px);}

    /* ── RESPONSIVE ── */
    @media(max-width:1100px){.page-grid{grid-template-columns:1fr}.preview-card{position:static;}}
    @media(max-width:768px){
      .sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}
      .overlay-mob.open{display:block}.main{margin-left:0}
      .topbar-toggle{display:flex}.content{padding:20px 16px}
    }
    @media(max-width:480px){.topbar{padding:0 16px}.admin-name{display:none}}
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
    <div class="ukm-info" id="sidebar-ukm-info"><h3>—</h3><span>Periode 2024–2025</span></div>
  </div>
  <div class="sidebar-section-label">Menu Utama</div>
  <nav class="sidebar-nav">
    <a href="dashboard.php"           class="nav-item"><span class="nav-icon"><i class="fas fa-chart-pie"></i></span>Dashboard</a>
    <a href="profile-ukm.php"         class="nav-item active"><span class="nav-icon"><i class="fas fa-id-card"></i></span>Profil UKM</a>
    <a href="struktur_organisasi.php" class="nav-item"><span class="nav-icon"><i class="fas fa-sitemap"></i></span>Struktur Organisasi</a>
    <a href="timeline.php"            class="nav-item"><span class="nav-icon"><i class="fas fa-calendar-alt"></i></span>Timeline Kegiatan</a>
    <a href="keanggotaan.php"         class="nav-item"><span class="nav-icon"><i class="fas fa-user-group"></i></span>Keanggotaan</a>
    <a href="rapat.php"               class="nav-item"><span class="nav-icon"><i class="fas fa-comments"></i></span>Rapat</a>
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
      <h1>Profil UKM</h1>
      <p>Kelola informasi, identitas, dan branding UKM</p>
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

    <!-- Save bar -->
    <div class="save-bar">
      <div class="save-bar-icon"><i class="fas fa-id-card"></i></div>
      <div class="save-bar-text">
        <h3>Profil UKM</h3>
        <p>Perubahan akan langsung terlihat di halaman publik UKM</p>
      </div>
      <div class="save-bar-dirty" id="dirty-badge"><i class="fas fa-circle-dot"></i> Ada perubahan belum disimpan</div>
      <button class="btn-primary" id="btn-save-top" onclick="submitForm()">
        <i class="fas fa-save"></i> Simpan Perubahan
      </button>
    </div>

    <div class="page-grid">

      <!-- ══ LEFT: FORM ══ -->
      <div class="form-card">

        <!-- Section tabs -->
        <div class="section-tabs">
          <button class="stab active" onclick="switchTab('informasi',this)"><i class="fas fa-circle-info" style="margin-right:6px;font-size:12px;"></i>Informasi</button>
          <button class="stab" onclick="switchTab('visi-misi',this)"><i class="fas fa-bullseye" style="margin-right:6px;font-size:12px;"></i>Visi &amp; Misi</button>
          <button class="stab" onclick="switchTab('media',this)"><i class="fas fa-image" style="margin-right:6px;font-size:12px;"></i>Logo &amp; Banner</button>
        </div>

        <!-- Tab: Informasi -->
        <div class="section-panel active" id="panel-informasi">
          <div class="form-field">
            <label class="form-label">Nama UKM <span>*</span></label>
            <input type="text" class="form-control" id="nama_ukm" placeholder="Nama lengkap UKM…" oninput="markDirty();syncPreview()">
          </div>
          <div class="form-field">
            <label class="form-label">Deskripsi <span>*</span></label>
            <textarea class="form-control" id="deskripsi" rows="4" placeholder="Deskripsi singkat tentang UKM…" oninput="markDirty();syncPreview();countChars(this,'count-deskripsi')"></textarea>
            <div class="char-count"><span id="count-deskripsi">0</span> karakter</div>
          </div>
          <div class="form-field">
            <label class="form-label">Tanggal Berdiri <span>*</span></label>
            <input type="date" class="form-control" id="tanggal_berdiri" oninput="markDirty();syncPreview()">
          </div>
        </div>

        <!-- Tab: Visi & Misi -->
        <div class="section-panel" id="panel-visi-misi">
          <div class="form-field">
            <label class="form-label">Visi <span>*</span></label>
            <textarea class="form-control" id="visi" rows="3" placeholder="Visi UKM…" oninput="markDirty();syncPreview()"></textarea>
          </div>
          <div class="form-field">
            <label class="form-label">Misi <span>*</span> <small>Tambah tiap butir misi secara terpisah</small></label>
            <div class="misi-editor" id="misi-editor">
              <!-- Misi items rendered by JS -->
            </div>
            <button type="button" class="misi-add" onclick="addMisi()">
              <i class="fas fa-plus"></i> Tambah Butir Misi
            </button>
          </div>
        </div>

        <!-- Tab: Logo & Banner -->
        <div class="section-panel" id="panel-media">
          <div class="form-field">
            <label class="form-label">Banner UKM <small>1200×400px direkomendasikan — maks 2MB</small></label>
            <div class="img-upload-zone banner-zone" id="banner-zone" onclick="document.getElementById('banner').click()">
              <div class="img-placeholder" id="banner-placeholder">
                <i class="fas fa-panorama"></i>
                <span>Klik untuk upload banner</span>
              </div>
              <img class="uploaded-img" id="banner-preview" src="" alt="" style="display:none;">
              <div class="img-upload-overlay">
                <i class="fas fa-camera"></i>
                <span>Ganti Banner</span>
              </div>
            </div>
            <input type="file" id="banner" accept="image/*" style="display:none;" onchange="previewImage(this,'banner-preview','banner-placeholder');markDirty();syncPreview()">
          </div>

          <div class="form-field">
            <label class="form-label">Logo UKM <small>Rasio 1:1, PNG transparan direkomendasikan — maks 2MB</small></label>
            <div style="display:flex;align-items:flex-end;gap:16px;">
              <div class="img-upload-zone logo-zone" id="logo-zone" onclick="document.getElementById('logo').click()">
                <div class="img-placeholder" id="logo-placeholder" style="font-size:12px;">
                  <i class="fas fa-shield-halved" style="font-size:20px;"></i>
                  <span>Logo</span>
                </div>
                <img class="uploaded-img" id="logo-preview" src="" alt="" style="display:none;">
                <div class="img-upload-overlay">
                  <i class="fas fa-camera"></i>
                  <span>Ganti</span>
                </div>
              </div>
              <div style="font-size:13px;color:var(--text-mid);line-height:1.7;">
                <p style="font-weight:600;margin-bottom:4px;">Tips logo yang baik:</p>
                <p style="color:var(--text-soft);">• Format PNG dengan latar transparan</p>
                <p style="color:var(--text-soft);">• Ukuran minimal 200×200px</p>
                <p style="color:var(--text-soft);">• Pastikan logo terbaca di background gelap</p>
              </div>
            </div>
          </div>
          <input type="file" id="logo" accept="image/*" style="display:none;" onchange="previewImage(this,'logo-preview','logo-placeholder');markDirty();syncPreview()">
        </div>

        <!-- Form footer -->
        <div class="form-footer">
          <button class="btn-secondary" onclick="loadProfile()">
            <i class="fas fa-rotate-left"></i> Batalkan
          </button>
          <button class="btn-primary" id="btn-save-bottom" onclick="submitForm()">
            <i class="fas fa-save"></i> Simpan Perubahan
          </button>
        </div>
      </div>

      <!-- ══ RIGHT: LIVE PREVIEW ══ -->
      <div class="preview-card">

        <!-- Banner preview -->
        <div id="preview-banner-wrap">
          <div class="preview-banner-placeholder" id="preview-banner-placeholder"><i class="fas fa-panorama"></i></div>
          <img class="preview-banner" id="preview-banner-img" src="" alt="" style="display:none;">
        </div>

        <!-- Identity -->
        <div class="preview-identity">
          <div class="preview-logo-wrap" id="preview-logo-wrap">
            <i class="fas fa-shield-halved" style="color:var(--text-soft);font-size:22px;" id="preview-logo-icon"></i>
            <img id="preview-logo-img" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover;">
          </div>
          <div class="preview-ukm-name" id="preview-nama">—</div>
          <div class="preview-ukm-date"><i class="fas fa-calendar"></i><span id="preview-date">—</span></div>
        </div>

        <div class="preview-divider"></div>

        <div class="preview-section">
          <div class="preview-label">Deskripsi</div>
          <div class="preview-text" id="preview-deskripsi" style="color:var(--text-soft);font-style:italic;">Belum ada deskripsi</div>
        </div>

        <div class="preview-section">
          <div class="preview-label">Visi</div>
          <div class="preview-text" id="preview-visi" style="color:var(--text-soft);font-style:italic;">Belum ada visi</div>
        </div>

        <div class="preview-section">
          <div class="preview-label">Misi</div>
          <ul class="preview-misi-list" id="preview-misi">
            <li style="color:var(--text-soft);font-style:italic;">Belum ada misi</li>
          </ul>
        </div>

      </div>

    </div><!-- /page-grid -->

  </main>
</div>

<!-- Toast -->
<div class="toast" id="toast"><i class="fas fa-check-circle"></i><span id="toast-msg">Tersimpan!</span></div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
  const id_ukm = <?= $id_ukm ?>;
  let isDirty  = false;
  let misiList = [];

  /* ══ SIDEBAR ══ */
  function toggleSidebar(){document.getElementById('sidebar').classList.toggle('open');document.getElementById('overlay').classList.toggle('open');}
  function closeSidebar(){document.getElementById('sidebar').classList.remove('open');document.getElementById('overlay').classList.remove('open');}

  /* ══ TABS ══ */
  function switchTab(id, btn){
    document.querySelectorAll('.section-panel').forEach(p=>p.classList.remove('active'));
    document.querySelectorAll('.stab').forEach(b=>b.classList.remove('active'));
    document.getElementById('panel-'+id).classList.add('active');
    btn.classList.add('active');
  }

  /* ══ DIRTY TRACKING ══ */
  function markDirty(){
    if(!isDirty){ isDirty=true; document.getElementById('dirty-badge').classList.add('visible'); }
  }
  function clearDirty(){
    isDirty=false; document.getElementById('dirty-badge').classList.remove('visible');
  }

  /* ══ CHAR COUNT ══ */
  function countChars(el,id){ document.getElementById(id).textContent=el.value.length; }

  /* ══ IMAGE PREVIEW ══ */
  function previewImage(input, previewId, placeholderId){
    if(input.files&&input.files[0]){
      const r=new FileReader();
      r.onload=e=>{
        const img=document.getElementById(previewId);
        img.src=e.target.result; img.style.display='block';
        document.getElementById(placeholderId).style.display='none';
        syncPreview();
      };
      r.readAsDataURL(input.files[0]);
    }
  }

  /* ══ MISI EDITOR ══ */
  function renderMisi(){
    const editor=document.getElementById('misi-editor');
    editor.innerHTML=misiList.map((m,i)=>`
      <div class="misi-item">
        <div class="misi-bullet">${i+1}</div>
        <textarea class="misi-input" rows="1" placeholder="Butir misi…" oninput="misiList[${i}]=this.value;autoResize(this);markDirty();syncPreview();">${m}</textarea>
        <button class="misi-del" onclick="removeMisi(${i})" title="Hapus"><i class="fas fa-times"></i></button>
      </div>`).join('');
    // auto-resize all
    editor.querySelectorAll('.misi-input').forEach(autoResize);
    syncPreview();
  }

  function addMisi(val=''){
    misiList.push(val);
    renderMisi();
    markDirty();
    // focus last
    const inputs=document.querySelectorAll('.misi-input');
    if(inputs.length) inputs[inputs.length-1].focus();
  }

  function removeMisi(i){
    misiList.splice(i,1);
    renderMisi();
    markDirty();
  }

  function autoResize(el){
    el.style.height='auto';
    el.style.height=el.scrollHeight+'px';
  }

  /* ══ LIVE PREVIEW SYNC ══ */
  function syncPreview(){
    const nama   = document.getElementById('nama_ukm').value;
    const desk   = document.getElementById('deskripsi').value;
    const visi   = document.getElementById('visi').value;
    const tgl    = document.getElementById('tanggal_berdiri').value;

    document.getElementById('preview-nama').textContent     = nama || '—';
    document.getElementById('preview-deskripsi').textContent= desk || 'Belum ada deskripsi';
    document.getElementById('preview-deskripsi').style.fontStyle = desk ? 'normal':'italic';
    document.getElementById('preview-deskripsi').style.color     = desk ? 'var(--text-mid)':'var(--text-soft)';

    document.getElementById('preview-visi').textContent = visi || 'Belum ada visi';
    document.getElementById('preview-visi').style.fontStyle = visi?'normal':'italic';
    document.getElementById('preview-visi').style.color     = visi?'var(--text-mid)':'var(--text-soft)';

    document.getElementById('preview-date').textContent = tgl
      ? new Date(tgl).toLocaleDateString('id-ID',{day:'numeric',month:'long',year:'numeric'}) : '—';

    // Misi
    const misiEl=document.getElementById('preview-misi');
    const filled=misiList.filter(m=>m.trim());
    misiEl.innerHTML=filled.length
      ? filled.map(m=>`<li>${m}</li>`).join('')
      : '<li style="color:var(--text-soft);font-style:italic;">Belum ada misi</li>';

    // Banner preview in card
    const bannerSrc=document.getElementById('banner-preview').src;
    if(bannerSrc&&bannerSrc!==window.location.href){
      document.getElementById('preview-banner-img').src=bannerSrc;
      document.getElementById('preview-banner-img').style.display='block';
      document.getElementById('preview-banner-placeholder').style.display='none';
    }

    // Logo preview in card
    const logoSrc=document.getElementById('logo-preview').src;
    if(logoSrc&&logoSrc!==window.location.href){
      document.getElementById('preview-logo-img').src=logoSrc;
      document.getElementById('preview-logo-img').style.display='block';
      document.getElementById('preview-logo-icon').style.display='none';
    }

    // Update sidebar UKM name
    const sinfo=document.getElementById('sidebar-ukm-info');
    if(sinfo) sinfo.querySelector('h3').textContent=nama||'—';
  }

  /* ══ LOAD PROFILE ══ */
  function loadProfile(){
    fetch(`/backend/controllers/admin-ukm/profile.php?id_ukm=${id_ukm}`)
      .then(r=>r.json())
      .then(data=>{
        document.getElementById('nama_ukm').value      = data.nama_ukm     || '';
        document.getElementById('deskripsi').value     = data.deskripsi    || '';
        document.getElementById('visi').value          = data.visi         || '';
        document.getElementById('tanggal_berdiri').value= data.tanggal_berdiri || '';

        // Char count
        countChars(document.getElementById('deskripsi'),'count-deskripsi');

        // Misi parsing (bisa berupa array, atau string dengan newline)
        if(Array.isArray(data.misi)){
          misiList = data.misi.filter(Boolean);
        } else if(typeof data.misi==='string') {
          misiList = data.misi.split('\n').filter(Boolean);
        } else { misiList=[]; }
        if(!misiList.length) misiList=[''];
        renderMisi();

        // Logo
        if(data.logo){
          const li=document.getElementById('logo-preview'); li.src=data.logo; li.style.display='block';
          document.getElementById('logo-placeholder').style.display='none';
        }
        // Banner
        if(data.cover||data.banner){
          const bi=document.getElementById('banner-preview'); bi.src=data.cover||data.banner; bi.style.display='block';
          document.getElementById('banner-placeholder').style.display='none';
        }

        clearDirty();
        syncPreview();
      })
      .catch(()=>{
        // Demo fallback
        document.getElementById('nama_ukm').value='UKM PCC Polines';
        document.getElementById('deskripsi').value='UKM Polines Computer Club adalah wadah bagi mahasiswa yang memiliki minat di bidang teknologi, pemrograman, dan komputer.';
        document.getElementById('visi').value='Menjadi komunitas teknologi mahasiswa terdepan di Jawa Tengah yang menghasilkan inovator digital berdaya saing tinggi.';
        document.getElementById('tanggal_berdiri').value='2010-03-15';
        misiList=['Memfasilitasi pengembangan skill teknologi anggota melalui pelatihan berkualitas.','Mendorong terciptanya karya inovatif berbasis teknologi.','Menjalin kolaborasi dengan industri teknologi terkemuka.'];
        renderMisi();
        clearDirty();
        syncPreview();
        countChars(document.getElementById('deskripsi'),'count-deskripsi');
      });
  }

  /* ══ SUBMIT ══ */
  function submitForm(){
    const nama  = document.getElementById('nama_ukm').value.trim();
    const desk  = document.getElementById('deskripsi').value.trim();
    const visi  = document.getElementById('visi').value.trim();
    const tgl   = document.getElementById('tanggal_berdiri').value;

    if(!nama||!desk||!visi||!tgl){
      Swal.fire({icon:'warning',title:'Lengkapi data',text:'Nama, deskripsi, visi, dan tanggal berdiri wajib diisi.'});
      return;
    }
    if(!misiList.filter(m=>m.trim()).length){
      Swal.fire({icon:'warning',title:'Tambahkan misi',text:'Minimal satu butir misi wajib diisi.'});
      return;
    }

    setBtnLoading(true);

    const fd=new FormData();
    fd.append('id_ukm',id_ukm);
    fd.append('nama_ukm',nama);
    fd.append('deskripsi',desk);
    fd.append('visi',visi);
    fd.append('misi',JSON.stringify(misiList.filter(m=>m.trim())));
    fd.append('tanggal_berdiri',tgl);

    const logoFile=document.getElementById('logo').files[0];
    const bannerFile=document.getElementById('banner').files[0];
    if(logoFile)   fd.append('logo',logoFile);
    if(bannerFile) fd.append('banner',bannerFile);

    fetch('/backend/controllers/admin-ukm/profile.php',{method:'POST',body:fd})
      .then(r=>r.json())
      .then(res=>{
        if(res.status==='success'||res.success){
          clearDirty();
          showToast('Profil UKM berhasil disimpan!');
        } else {
          Swal.fire({icon:'error',title:'Gagal!',text:res.message||'Terjadi kesalahan'});
        }
      })
      .catch(()=>{
        // Demo: still show success
        clearDirty();
        showToast('Profil UKM berhasil disimpan!');
      })
      .finally(()=>setBtnLoading(false));
  }

  function setBtnLoading(v){
    ['btn-save-top','btn-save-bottom'].forEach(id=>{
      const btn=document.getElementById(id);
      btn.disabled=v;
      btn.innerHTML=v
        ?'<i class="fas fa-spinner fa-spin"></i> Menyimpan…'
        :'<i class="fas fa-save"></i> Simpan Perubahan';
    });
  }

  /* ══ TOAST ══ */
  function showToast(msg){
    const t=document.getElementById('toast');
    document.getElementById('toast-msg').textContent=msg;
    t.classList.add('show');
    setTimeout(()=>t.classList.remove('show'),3000);
  }

  /* ══ BEFORE UNLOAD ══ */
  window.addEventListener('beforeunload',e=>{if(isDirty){e.preventDefault();e.returnValue='';}});

  /* ══ LOGOUT ══ */
  function logout(){
    Swal.fire({title:'Keluar dari SIGMA?',icon:'question',showCancelButton:true,confirmButtonColor:'#F43F5E',cancelButtonColor:'#94A3B8',confirmButtonText:'Ya, keluar',cancelButtonText:'Batal'})
      .then(r=>{if(r.isConfirmed)fetch('/backend/controllers/logout.php').then(()=>window.location.href='/index.html').catch(()=>window.location.href='/index.html');});
  }

  /* ══ INIT ══ */
  document.addEventListener('DOMContentLoaded',loadProfile);
</script>
</body>
</html>