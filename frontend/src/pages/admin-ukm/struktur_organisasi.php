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
  <title>Struktur Organisasi — Admin UKM SIGMA</title>
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
      --orange:      #F97316;
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

    /* ── STATS ── */
    .stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;}
    .mini-stat{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:18px 20px;display:flex;align-items:center;gap:14px;box-shadow:var(--shadow-sm);transition:all var(--transition);animation:fadeUp .4s ease both;}
    .mini-stat:nth-child(1){animation-delay:.05s}.mini-stat:nth-child(2){animation-delay:.1s}.mini-stat:nth-child(3){animation-delay:.15s}.mini-stat:nth-child(4){animation-delay:.2s}
    @keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}
    .mini-stat:hover{transform:translateY(-2px);box-shadow:var(--shadow-md);}
    .mini-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;}
    .mi-blue  {background:#EEF1FF;color:var(--accent);}
    .mi-green {background:#ECFDF5;color:var(--green);}
    .mi-amber {background:#FFFBEB;color:var(--amber);}
    .mi-purple{background:#F5F3FF;color:var(--purple);}
    .mini-body h2{font-size:24px;font-weight:800;color:var(--text-main);letter-spacing:-.5px;line-height:1;}
    .mini-body p{font-size:12px;color:var(--text-soft);margin-top:4px;font-weight:500;}

    /* ── TOOLBAR ── */
    .toolbar{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:20px;flex-wrap:wrap;}
    .toolbar-left{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
    .search-wrap{position:relative;min-width:220px;}
    .search-wrap i{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-soft);font-size:13px;}
    .search-input{width:100%;padding:9px 12px 9px 34px;border:1px solid var(--border);border-radius:9px;font-family:inherit;font-size:13px;color:var(--text-main);background:var(--card);outline:none;transition:all var(--transition);}
    .search-input:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(79,106,240,.1);}
    .toolbar-right{display:flex;gap:8px;flex-wrap:wrap;}

    /* Buttons */
    .btn-primary{display:inline-flex;align-items:center;gap:8px;padding:9px 18px;background:var(--accent);color:#fff;border:none;border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;transition:all var(--transition);box-shadow:0 4px 12px rgba(79,106,240,.3);}
    .btn-primary:hover{background:#3d59e0;transform:translateY(-1px);}
    .btn-primary:disabled{opacity:.6;cursor:not-allowed;transform:none;}
    .btn-secondary{display:inline-flex;align-items:center;gap:8px;padding:9px 18px;background:var(--surface);color:var(--text-mid);border:1px solid var(--border);border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;transition:all var(--transition);}
    .btn-secondary:hover{background:var(--border);}
    .btn-outline{display:inline-flex;align-items:center;gap:7px;padding:8px 16px;background:var(--card);color:var(--text-mid);border:1.5px solid var(--border);border-radius:10px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;transition:all var(--transition);}
    .btn-outline:hover{border-color:var(--accent);color:var(--accent);background:var(--accent-soft);}
    .btn-outline.green{color:var(--green);border-color:rgba(16,185,129,.3);}
    .btn-outline.green:hover{background:#ECFDF5;border-color:var(--green);}
    .btn-outline.amber{color:var(--amber);border-color:rgba(245,158,11,.3);}
    .btn-outline.amber:hover{background:#FFFBEB;border-color:var(--amber);}

    /* ── ORG CHART ── */
    .section-title{font-size:14px;font-weight:700;color:var(--text-mid);letter-spacing:.5px;text-transform:uppercase;margin-bottom:16px;display:flex;align-items:center;gap:10px;}
    .section-title::after{content:'';flex:1;height:1px;background:var(--border);}
    .inti-row{display:flex;justify-content:center;gap:16px;margin-bottom:32px;flex-wrap:wrap;}
    .connector{position:relative;text-align:center;margin-bottom:24px;}
    .connector::before{content:'';display:block;width:2px;height:32px;background:var(--border);margin:0 auto;}
    .divisi-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;}
    .div-card{background:var(--card);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden;animation:fadeUp .4s ease both;transition:box-shadow var(--transition);}
    .div-card:hover{box-shadow:var(--shadow-md);}
    .div-card-header{padding:14px 18px;display:flex;align-items:center;gap:12px;border-bottom:1px solid var(--border);}
    .div-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;}
    .div-name{font-size:14px;font-weight:700;color:var(--text-main);flex:1;}
    .div-count{font-size:11px;font-weight:700;padding:3px 9px;border-radius:12px;}
    .div-members{padding:4px 0;}
    .member-row{display:flex;align-items:center;gap:12px;padding:10px 18px;transition:background var(--transition);}
    .member-row:hover{background:#F8FAFF;}
    .member-avatar{width:36px;height:36px;border-radius:10px;object-fit:cover;flex-shrink:0;background:var(--surface);}
    .member-avatar-placeholder{width:36px;height:36px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#fff;}
    .member-info{flex:1;min-width:0;}
    .member-name{font-size:13px;font-weight:600;color:var(--text-main);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
    .member-jabatan{font-size:11px;color:var(--text-soft);margin-top:1px;}
    .member-actions{display:flex;gap:6px;flex-shrink:0;}
    .btn-icon{width:28px;height:28px;border-radius:7px;border:none;display:flex;align-items:center;justify-content:center;font-size:11px;cursor:pointer;transition:all var(--transition);}
    .btn-e{background:#EEF1FF;color:var(--accent);}
    .btn-d{background:#FFF1F2;color:var(--rose);}
    .btn-e:hover{background:var(--accent);color:#fff;}
    .btn-d:hover{background:var(--rose);color:#fff;}
    .div-footer{padding:10px 18px;border-top:1px solid var(--border);background:var(--surface);}
    .pal-0{background:#EEF1FF;color:var(--accent);}
    .pal-1{background:#ECFDF5;color:var(--green);}
    .pal-2{background:#FFFBEB;color:var(--amber);}
    .pal-3{background:#F5F3FF;color:var(--purple);}
    .pal-4{background:#FFF1F2;color:var(--rose);}
    .pal-5{background:#F0FDFA;color:var(--teal);}
    .count-0{background:#EEF1FF;color:var(--accent);}
    .count-1{background:#ECFDF5;color:var(--green);}
    .count-2{background:#FFFBEB;color:var(--amber);}
    .count-3{background:#F5F3FF;color:var(--purple);}
    .count-4{background:#FFF1F2;color:var(--rose);}
    .count-5{background:#F0FDFA;color:var(--teal);}
    .avatar-0{background:linear-gradient(135deg,var(--accent),var(--purple));}
    .avatar-1{background:linear-gradient(135deg,var(--green),var(--teal));}
    .avatar-2{background:linear-gradient(135deg,var(--amber),var(--orange));}
    .avatar-3{background:linear-gradient(135deg,var(--purple),#EC4899);}
    .avatar-4{background:linear-gradient(135deg,var(--rose),var(--orange));}
    .avatar-5{background:linear-gradient(135deg,var(--teal),var(--green));}
    .empty-div{padding:20px;text-align:center;color:var(--text-soft);font-size:13px;}

    /* ── MODAL ── */
    .modal-overlay{display:none;position:fixed;inset:0;background:rgba(15,27,76,.45);z-index:500;backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:20px;}
    .modal-overlay.open{display:flex;}
    .modal-box{background:var(--card);border-radius:20px;width:100%;max-width:520px;box-shadow:var(--shadow-lg);max-height:90vh;display:flex;flex-direction:column;animation:modalIn .25s cubic-bezier(.34,1.56,.64,1);}
    .modal-box.wide{max-width:640px;}
    @keyframes modalIn{from{opacity:0;transform:scale(.92) translateY(20px)}to{opacity:1;transform:scale(1) translateY(0)}}
    .modal-header{padding:22px 28px 18px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-shrink:0;}
    .modal-header-left{display:flex;align-items:center;gap:14px;}
    .modal-header-icon{width:42px;height:42px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px;}
    .icon-blue  {background:var(--accent-soft);color:var(--accent);}
    .icon-green {background:#ECFDF5;color:var(--green);}
    .icon-amber {background:#FFFBEB;color:var(--amber);}
    .modal-title{font-size:16px;font-weight:700;color:var(--text-main);}
    .modal-subtitle{font-size:12px;color:var(--text-soft);margin-top:2px;}
    .modal-close{width:32px;height:32px;border-radius:8px;background:var(--surface);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--text-soft);font-size:16px;transition:all var(--transition);}
    .modal-close:hover{background:#FFF1F2;color:var(--rose);border-color:var(--rose);}
    .modal-body{padding:22px 28px;display:flex;flex-direction:column;gap:16px;overflow-y:auto;flex:1;}
    .modal-footer{padding:14px 28px 22px;display:flex;gap:10px;justify-content:flex-end;flex-shrink:0;}
    .form-field{display:flex;flex-direction:column;gap:6px;}
    .form-row-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
    .form-label{font-size:11px;font-weight:700;color:var(--text-mid);letter-spacing:.4px;text-transform:uppercase;}
    .form-label span{color:var(--rose);margin-left:2px;}
    .form-control{padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:14px;font-family:inherit;color:var(--text-main);background:var(--surface);transition:all var(--transition);outline:none;}
    .form-control:focus{border-color:var(--accent);background:var(--card);box-shadow:0 0 0 3px rgba(79,106,240,.12);}
    .form-control:disabled{opacity:.6;cursor:not-allowed;}
    textarea.form-control{resize:vertical;min-height:72px;}
    .form-hint{font-size:11px;color:var(--text-soft);}
    .photo-upload{display:flex;align-items:center;gap:16px;}
    .photo-circle{width:72px;height:72px;border-radius:50%;background:var(--surface);border:2px dashed var(--border);display:flex;align-items:center;justify-content:center;font-size:24px;color:var(--text-soft);overflow:hidden;flex-shrink:0;cursor:pointer;transition:border-color var(--transition);}
    .photo-circle:hover{border-color:var(--accent);}
    .photo-circle img{width:100%;height:100%;object-fit:cover;}
    .photo-upload-info p{font-size:13px;font-weight:600;color:var(--text-main);}
    .photo-upload-info small{font-size:11px;color:var(--text-soft);}
    .photo-upload-btn{margin-top:8px;display:inline-flex;align-items:center;gap:6px;padding:5px 12px;border-radius:8px;background:var(--accent-soft);color:var(--accent);font-size:12px;font-weight:600;cursor:pointer;border:none;font-family:inherit;transition:all var(--transition);}
    .photo-upload-btn:hover{background:var(--accent);color:#fff;}
    .inner-table{width:100%;border-collapse:collapse;font-size:13px;}
    .inner-table thead tr{background:var(--surface);border-bottom:2px solid var(--border);}
    .inner-table th{padding:10px 14px;text-align:left;font-size:11px;font-weight:700;color:var(--text-soft);letter-spacing:.8px;text-transform:uppercase;}
    .inner-table tbody tr{border-bottom:1px solid var(--border);transition:background var(--transition);}
    .inner-table tbody tr:hover{background:#F8FAFF;}
    .inner-table td{padding:11px 14px;vertical-align:middle;}
    .badge{display:inline-flex;align-items:center;gap:5px;padding:3px 9px;border-radius:12px;font-size:11px;font-weight:700;}
    .badge-inti  {background:#ECFDF5;color:var(--green);}
    .badge-divisi{background:#EEF1FF;color:var(--accent);}
    .overlay-mob{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:199;backdrop-filter:blur(2px);}

    @media(max-width:1200px){.stats-row{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:768px){
      .sidebar{transform:translateX(-100%)}.sidebar.open{transform:translateX(0)}
      .overlay-mob.open{display:block}.main{margin-left:0}
      .topbar-toggle{display:flex}.content{padding:20px 16px}
      .stats-row{grid-template-columns:1fr 1fr;gap:12px}
      .divisi-grid{grid-template-columns:1fr}
      .inti-row{justify-content:flex-start}
      .toolbar{flex-direction:column;align-items:stretch}
      .form-row-2{grid-template-columns:1fr}
    }
    @media(max-width:480px){.stats-row{grid-template-columns:1fr}.topbar{padding:0 16px}.admin-name{display:none}}
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
    <!-- FIX: id ditambahkan, h3 diisi oleh JS -->
    <div class="ukm-info" id="sidebar-ukm-info">
      <h3>—</h3>
      <span>Periode 2024–2025</span>
    </div>
  </div>
  <div class="sidebar-section-label">Menu Utama</div>
  <nav class="sidebar-nav">
    <a href="dashboard.php"           class="nav-item"><span class="nav-icon"><i class="fas fa-chart-pie"></i></span>Dashboard</a>
    <a href="profile-ukm.php"         class="nav-item"><span class="nav-icon"><i class="fas fa-id-card"></i></span>Profil UKM</a>
    <a href="struktur_organisasi.php" class="nav-item active"><span class="nav-icon"><i class="fas fa-sitemap"></i></span>Struktur Organisasi</a>
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
      <h1>Struktur Organisasi</h1>
      <p>Kelola pengurus, divisi, dan jabatan UKM</p>
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
      <div class="mini-stat"><div class="mini-icon mi-blue"><i class="fas fa-users-gear"></i></div><div class="mini-body"><h2 id="stat-pengurus">—</h2><p>Total Pengurus</p></div></div>
      <div class="mini-stat"><div class="mini-icon mi-green"><i class="fas fa-sitemap"></i></div><div class="mini-body"><h2 id="stat-divisi">—</h2><p>Divisi</p></div></div>
      <div class="mini-stat"><div class="mini-icon mi-amber"><i class="fas fa-star"></i></div><div class="mini-body"><h2 id="stat-inti">—</h2><p>Pengurus Inti</p></div></div>
      <div class="mini-stat"><div class="mini-icon mi-purple"><i class="fas fa-tag"></i></div><div class="mini-body"><h2 id="stat-jabatan">—</h2><p>Jenis Jabatan</p></div></div>
    </div>

    <!-- Toolbar -->
    <div class="toolbar">
      <div class="toolbar-left">
        <div class="search-wrap">
          <i class="fas fa-search"></i>
          <input type="text" class="search-input" placeholder="Cari nama pengurus…" oninput="searchPengurus(this.value)">
        </div>
      </div>
      <div class="toolbar-right">
        <button class="btn-outline amber" onclick="openModalDivisi()"><i class="fas fa-sitemap"></i> Kelola Divisi</button>
        <button class="btn-outline green"  onclick="openModalJabatan()"><i class="fas fa-tags"></i> Kelola Jabatan</button>
        <button class="btn-primary"        onclick="openModalPengurus('add')"><i class="fas fa-plus"></i> Tambah Pengurus</button>
      </div>
    </div>

    <!-- Org Chart -->
    <div id="org-chart">
      <div style="text-align:center;padding:40px;color:var(--text-soft);"><i class="fas fa-spinner fa-spin" style="font-size:24px;"></i></div>
    </div>
  </main>
</div>

<!-- ════════ MODAL: PENGURUS ════════ -->
<div class="modal-overlay" id="modal-pengurus">
  <div class="modal-box">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-header-icon icon-blue"><i class="fas fa-user-tie"></i></div>
        <div>
          <div class="modal-title" id="modal-pengurus-title">Tambah Pengurus</div>
          <div class="modal-subtitle" id="modal-pengurus-sub">Isi data pengurus baru</div>
        </div>
      </div>
      <button class="modal-close" onclick="closeModal('modal-pengurus')"><i class="fas fa-times"></i></button>
    </div>
    <form id="form-pengurus" enctype="multipart/form-data">
      <input type="hidden" id="id_struktur" name="id_struktur">
      <div class="modal-body">
        <div class="photo-upload">
          <div class="photo-circle" onclick="document.getElementById('foto').click()" id="photo-circle">
            <i class="fas fa-camera"></i>
          </div>
          <div class="photo-upload-info">
            <p>Foto Pengurus</p>
            <small>JPG, PNG — maks 2MB</small>
            <button type="button" class="photo-upload-btn" onclick="document.getElementById('foto').click()">
              <i class="fas fa-upload"></i> Pilih Foto
            </button>
          </div>
        </div>
        <input type="file" id="foto" name="foto" accept="image/*" style="display:none;" onchange="previewFoto(this)">

        <div class="form-field">
          <label class="form-label">Mahasiswa <span>*</span></label>
          <select class="form-control" id="nim" name="nim" required>
            <option value="">Pilih Mahasiswa</option>
          </select>
        </div>

        <div class="form-row-2">
          <div class="form-field">
            <label class="form-label">Divisi <span>*</span></label>
            <select class="form-control" id="id_divisi" name="id_divisi" required onchange="loadJabatanByDivisi(this.value,'#id_jabatan_divisi')">
              <option value="">Pilih Divisi</option>
            </select>
          </div>
          <div class="form-field">
            <label class="form-label">Jabatan <span>*</span></label>
            <select class="form-control" id="id_jabatan_divisi" name="id_jabatan_divisi" required>
              <option value="">Pilih Jabatan</option>
            </select>
          </div>
        </div>

        <div class="form-field">
          <label class="form-label">Periode <span>*</span></label>
          <select class="form-control" id="id_periode" name="id_periode" required>
            <option value="">Pilih Periode</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-secondary" onclick="closeModal('modal-pengurus')">Batal</button>
        <button type="submit" class="btn-primary" id="btn-submit-pengurus">
          <i class="fas fa-save"></i> <span id="btn-pengurus-text">Simpan</span>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ════════ MODAL: DIVISI ════════ -->
<div class="modal-overlay" id="modal-divisi">
  <div class="modal-box wide">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-header-icon icon-amber"><i class="fas fa-sitemap"></i></div>
        <div>
          <div class="modal-title">Kelola Divisi</div>
          <div class="modal-subtitle">Tambah, edit, atau hapus divisi UKM</div>
        </div>
      </div>
      <button class="modal-close" onclick="closeModal('modal-divisi')"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:16px;display:flex;flex-direction:column;gap:12px;">
        <input type="hidden" id="id_divisi_edit">
        <div class="form-row-2">
          <div class="form-field">
            <label class="form-label">Nama Divisi <span>*</span></label>
            <input type="text" class="form-control" id="nama_divisi" placeholder="Nama divisi…">
          </div>
          <div class="form-field">
            <label class="form-label">Tipe <span>*</span></label>
            <select class="form-control" id="tipe_divisi">
              <option value="">Pilih Tipe</option>
              <option value="inti">Inti</option>
              <option value="divisi">Divisi</option>
            </select>
          </div>
        </div>
        <div class="form-field">
          <label class="form-label">Deskripsi</label>
          <textarea class="form-control" id="deskripsi_divisi" placeholder="Deskripsi singkat divisi…"></textarea>
        </div>
        <div style="display:flex;gap:8px;">
          <button class="btn-primary" onclick="saveDivisi()" style="height:40px;"><i class="fas fa-save"></i> Simpan Divisi</button>
          <button class="btn-secondary" onclick="resetFormDivisi()" style="height:40px;">Reset</button>
        </div>
      </div>
      <div style="overflow-x:auto;">
        <table class="inner-table">
          <thead><tr><th>Nama Divisi</th><th>Tipe</th><th>Deskripsi</th><th>Aksi</th></tr></thead>
          <tbody id="tbody-divisi">
            <tr><td colspan="4" style="text-align:center;padding:20px;color:var(--text-soft);">Memuat…</td></tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="modal-footer"><button class="btn-secondary" onclick="closeModal('modal-divisi')">Tutup</button></div>
  </div>
</div>

<!-- ════════ MODAL: JABATAN ════════ -->
<div class="modal-overlay" id="modal-jabatan">
  <div class="modal-box wide">
    <div class="modal-header">
      <div class="modal-header-left">
        <div class="modal-header-icon icon-green"><i class="fas fa-tags"></i></div>
        <div>
          <div class="modal-title">Kelola Jabatan</div>
          <div class="modal-subtitle">Tambah, edit, atau hapus jabatan per divisi</div>
        </div>
      </div>
      <button class="modal-close" onclick="closeModal('modal-jabatan')"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:16px;display:flex;flex-direction:column;gap:12px;">
        <input type="hidden" id="id_jabatan_edit">
        <div class="form-row-2">
          <div class="form-field">
            <label class="form-label">Divisi <span>*</span></label>
            <select class="form-control" id="jabatan_id_divisi">
              <option value="">Pilih Divisi</option>
            </select>
          </div>
          <div class="form-field">
            <label class="form-label">Nama Jabatan <span>*</span></label>
            <input type="text" class="form-control" id="nama_jabatan" placeholder="Nama jabatan…">
          </div>
        </div>
        <div class="form-field">
          <label class="form-label">Hierarki <span>*</span></label>
          <input type="number" class="form-control" id="hierarki_jabatan" min="1" max="10" placeholder="1 = tertinggi">
          <span class="form-hint">Level 1 adalah hierarki tertinggi dalam divisi</span>
        </div>
        <div style="display:flex;gap:8px;">
          <button class="btn-primary" onclick="saveJabatan()" style="height:40px;"><i class="fas fa-save"></i> Simpan Jabatan</button>
          <button class="btn-secondary" onclick="resetFormJabatan()" style="height:40px;">Reset</button>
        </div>
      </div>
      <div style="overflow-x:auto;">
        <table class="inner-table">
          <thead><tr><th>Divisi</th><th>Jabatan</th><th>Hierarki</th><th>Aksi</th></tr></thead>
          <tbody id="tbody-jabatan">
            <tr><td colspan="4" style="text-align:center;padding:20px;color:var(--text-soft);">Memuat…</td></tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="modal-footer"><button class="btn-secondary" onclick="closeModal('modal-jabatan')">Tutup</button></div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
  const id_ukm = <?= $id_ukm ?>;

  /* ── SIDEBAR ── */
  function toggleSidebar(){document.getElementById('sidebar').classList.toggle('open');document.getElementById('overlay').classList.toggle('open');}
  function closeSidebar(){document.getElementById('sidebar').classList.remove('open');document.getElementById('overlay').classList.remove('open');}

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

  /* ── DATA STATE ── */
  let allStruktur=[];
  let allDivisi=[];
  let searchQ='';

  const palettes=['pal-0','pal-1','pal-2','pal-3','pal-4','pal-5'];
  const counts  =['count-0','count-1','count-2','count-3','count-4','count-5'];
  const avatars =['avatar-0','avatar-1','avatar-2','avatar-3','avatar-4','avatar-5'];

  /* ── LOAD ALL ── */
  function loadAll(){
    loadStruktur();
    loadDivisiData();
    loadPeriodeDropdown();
  }

  function loadStruktur(){
    fetch(`/backend/controllers/admin-ukm/struktur_organisasi.php?id_ukm=${id_ukm}`)
      .then(r=>r.json())
      .then(data=>{
        allStruktur=Array.isArray(data)?data:[];
        updateStats();
        renderOrgChart();
      })
      .catch(()=>{
        allStruktur=[
          {id_struktur:1,nim:'3.34.21.0.01',nama_lengkap:'Budi Santoso',nama_divisi:'Inti',tipe_divisi:'inti',nama_jabatan:'Ketua',hierarki:1,id_divisi:1,id_jabatan_divisi:1,id_periode:1,foto_path:''},
          {id_struktur:2,nim:'3.34.21.0.02',nama_lengkap:'Siti Rahayu',nama_divisi:'Inti',tipe_divisi:'inti',nama_jabatan:'Sekretaris',hierarki:2,id_divisi:1,id_jabatan_divisi:2,id_periode:1,foto_path:''},
          {id_struktur:3,nim:'3.34.21.0.03',nama_lengkap:'Ahmad Fauzi',nama_divisi:'Divisi Teknologi',tipe_divisi:'divisi',nama_jabatan:'Ketua Divisi',hierarki:1,id_divisi:2,id_jabatan_divisi:3,id_periode:1,foto_path:''},
          {id_struktur:4,nim:'3.34.21.0.04',nama_lengkap:'Dewi Kusuma',nama_divisi:'Divisi Teknologi',tipe_divisi:'divisi',nama_jabatan:'Anggota',hierarki:2,id_divisi:2,id_jabatan_divisi:4,id_periode:1,foto_path:''},
          {id_struktur:5,nim:'3.34.21.0.05',nama_lengkap:'Reza Pratama',nama_divisi:'Divisi Kreatif',tipe_divisi:'divisi',nama_jabatan:'Ketua Divisi',hierarki:1,id_divisi:3,id_jabatan_divisi:5,id_periode:1,foto_path:''},
        ];
        updateStats();
        renderOrgChart();
      });
  }

  function updateStats(){
    document.getElementById('stat-pengurus').textContent=allStruktur.length;
    const divSet=new Set(allStruktur.map(d=>d.id_divisi));
    document.getElementById('stat-divisi').textContent=divSet.size;
    document.getElementById('stat-inti').textContent=allStruktur.filter(d=>d.tipe_divisi==='inti').length;
    const jabSet=new Set(allStruktur.map(d=>d.id_jabatan_divisi));
    document.getElementById('stat-jabatan').textContent=jabSet.size;
  }

  /* ── SEARCH ── */
  function searchPengurus(v){searchQ=v.toLowerCase();renderOrgChart();}

  function getFiltered(){
    if(!searchQ) return allStruktur;
    return allStruktur.filter(d=>d.nama_lengkap?.toLowerCase().includes(searchQ)||d.nim?.toLowerCase().includes(searchQ));
  }

  /* ── RENDER ORG CHART ── */
  function renderOrgChart(){
    const data=getFiltered();
    const chart=document.getElementById('org-chart');
    if(!data.length){
      chart.innerHTML=`<div style="text-align:center;padding:60px 20px;color:var(--text-soft);"><div style="width:72px;height:72px;border-radius:20px;background:var(--card);margin:0 auto 16px;display:flex;align-items:center;justify-content:center;font-size:28px;">🏢</div><h3 style="font-size:15px;font-weight:600;color:var(--text-mid);margin-bottom:6px;">Belum ada pengurus</h3><p style="font-size:13px;">Klik "Tambah Pengurus" untuk mulai mengisi struktur organisasi</p></div>`;
      return;
    }
    const groups={};
    data.forEach(d=>{
      const key=d.id_divisi;
      if(!groups[key]) groups[key]={nama:d.nama_divisi,tipe:d.tipe_divisi,members:[]};
      groups[key].members.push(d);
    });
    const sorted=Object.values(groups).sort((a,b)=>a.tipe==='inti'?-1:1);
    const inti  =sorted.filter(g=>g.tipe==='inti');
    const divisi=sorted.filter(g=>g.tipe!=='inti');
    let html='';
    if(inti.length){
      html+=`<div class="section-title"><i class="fas fa-star" style="color:var(--amber);"></i> Pengurus Inti</div><div class="inti-row">`;
      inti.forEach((g,gi)=>html+=buildDivCard(g,gi%palettes.length,true));
      html+=`</div>`;
      if(divisi.length) html+=`<div class="connector"></div>`;
    }
    if(divisi.length){
      html+=`<div class="section-title"><i class="fas fa-sitemap" style="color:var(--accent);"></i> Divisi</div><div class="divisi-grid">`;
      divisi.forEach((g,gi)=>html+=buildDivCard(g,(gi+1)%palettes.length,false));
      html+=`</div>`;
    }
    chart.innerHTML=html;
  }

  function buildDivCard(g,pi,isInti){
    const sorted=[...g.members].sort((a,b)=>a.hierarki-b.hierarki);
    const memberRows=sorted.map(m=>{
      const initials=(m.nama_lengkap||'?').split(' ').map(w=>w[0]).slice(0,2).join('').toUpperCase();
      const avatarEl=m.foto_path
        ?`<img class="member-avatar" src="/frontend/public/assets/profil/${m.foto_path}" alt="" onerror="this.style.display='none';">`
        :`<div class="member-avatar-placeholder ${avatars[pi]}">${initials}</div>`;
      return `<div class="member-row">${avatarEl}<div class="member-info"><div class="member-name">${m.nama_lengkap}</div><div class="member-jabatan">${m.nama_jabatan} · ${m.nim}</div></div><div class="member-actions"><button class="btn-icon btn-e" onclick="editPengurus(${m.id_struktur})" title="Edit"><i class="fas fa-pen"></i></button><button class="btn-icon btn-d" onclick="deletePengurus(${m.id_struktur})" title="Hapus"><i class="fas fa-trash"></i></button></div></div>`;
    }).join('');
    return `<div class="div-card" style="animation-delay:${pi*0.04}s;"><div class="div-card-header"><div class="div-icon ${palettes[pi]}"><i class="fas fa-${isInti?'star':'users'}"></i></div><div class="div-name">${g.nama}</div><span class="div-count ${counts[pi]}">${g.members.length}</span></div><div class="div-members">${memberRows||`<div class="empty-div">Belum ada anggota</div>`}</div><div class="div-footer"><button class="btn-outline" style="font-size:12px;padding:6px 12px;" onclick="quickAdd(${g.members[0]?.id_divisi||0})"><i class="fas fa-plus"></i> Tambah ke Divisi Ini</button></div></div>`;
  }

  /* ── PENGURUS MODAL ── */
  function openModalPengurus(mode,data=null){
    resetFormPengurus();
    loadMahasiswaDropdown();
    loadDivisiForPengurus();
    loadPeriodeDropdown();
    if(mode==='add'){
      document.getElementById('modal-pengurus-title').textContent='Tambah Pengurus';
      document.getElementById('modal-pengurus-sub').textContent='Isi data pengurus baru';
      document.getElementById('btn-pengurus-text').textContent='Simpan';
    }
    document.getElementById('modal-pengurus').classList.add('open');
  }

  function quickAdd(id_divisi){
    openModalPengurus('add');
    setTimeout(()=>{document.getElementById('id_divisi').value=id_divisi;loadJabatanByDivisi(id_divisi,'#id_jabatan_divisi');},500);
  }

  function editPengurus(id){
    fetch(`/backend/controllers/admin-ukm/struktur_organisasi.php?id_struktur=${id}`)
      .then(r=>r.json())
      .then(d=>{
        loadMahasiswaDropdown(d.nim);
        loadDivisiForPengurus();
        loadPeriodeDropdown();
        document.getElementById('id_struktur').value=d.id_struktur;
        setTimeout(()=>{
          document.getElementById('nim').value=d.nim;
          document.getElementById('nim').disabled=true;
          document.getElementById('id_divisi').value=d.id_divisi;
          loadJabatanByDivisi(d.id_divisi,'#id_jabatan_divisi').then(()=>{
            document.getElementById('id_jabatan_divisi').value=d.id_jabatan_divisi;
          });
          document.getElementById('id_periode').value=d.id_periode;
        },600);
        if(d.foto_path){
          document.getElementById('photo-circle').innerHTML=`<img src="/frontend/public/assets/profil/${d.foto_path}" alt="">`;
        }
        document.getElementById('modal-pengurus-title').textContent='Edit Pengurus';
        document.getElementById('modal-pengurus-sub').textContent='Ubah data pengurus';
        document.getElementById('btn-pengurus-text').textContent='Update';
        document.getElementById('modal-pengurus').classList.add('open');
      });
  }

  function deletePengurus(id){
    Swal.fire({title:'Hapus pengurus ini?',text:'Data akan dihapus permanen.',icon:'warning',showCancelButton:true,confirmButtonColor:'#F43F5E',cancelButtonColor:'#94A3B8',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'})
      .then(r=>{
        if(r.isConfirmed){
          fetch(`/backend/controllers/admin-ukm/struktur_organisasi.php?id_struktur=${id}`,{method:'DELETE'})
            .then(r=>r.json())
            .then(res=>{
              if(res.status==='success'){Swal.fire({icon:'success',title:'Terhapus!',timer:1500,showConfirmButton:false});loadStruktur();}
              else Swal.fire({icon:'error',title:'Gagal!',text:res.message});
            });
        }
      });
  }

  document.getElementById('form-pengurus').addEventListener('submit',function(e){
    e.preventDefault();
    const btn=document.getElementById('btn-submit-pengurus');
    btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Menyimpan…';
    const fd=new FormData(this);fd.append('id_ukm',id_ukm);
    fetch('/backend/controllers/admin-ukm/struktur_organisasi.php',{method:'POST',body:fd})
      .then(r=>r.json())
      .then(res=>{
        if(res.status==='success'){Swal.fire({icon:'success',title:'Berhasil!',text:'Data pengurus berhasil disimpan',timer:1500,showConfirmButton:false});closeModal('modal-pengurus');loadStruktur();}
        else Swal.fire({icon:'error',title:'Gagal!',text:res.message});
      })
      .catch(()=>Swal.fire({icon:'error',title:'Error',text:'Terjadi kesalahan jaringan'}))
      .finally(()=>{btn.disabled=false;btn.innerHTML='<i class="fas fa-save"></i> <span id="btn-pengurus-text">Simpan</span>';});
  });

  function resetFormPengurus(){
    document.getElementById('form-pengurus').reset();
    document.getElementById('id_struktur').value='';
    document.getElementById('nim').disabled=false;
    document.getElementById('photo-circle').innerHTML='<i class="fas fa-camera"></i>';
  }

  function previewFoto(input){
    if(input.files&&input.files[0]){
      const r=new FileReader();
      r.onload=e=>{document.getElementById('photo-circle').innerHTML=`<img src="${e.target.result}" alt="">`;};
      r.readAsDataURL(input.files[0]);
    }
  }

  /* ── DROPDOWN LOADERS ── */
  function loadMahasiswaDropdown(currentNim=null){
    const url=`/backend/controllers/admin-ukm/struktur_organisasi.php?action=get_mahasiswa&id_ukm=${id_ukm}${currentNim?`&current_nim=${currentNim}`:''}`;
    fetch(url).then(r=>r.json()).then(data=>{
      document.getElementById('nim').innerHTML='<option value="">Pilih Mahasiswa</option>'+(data||[]).map(m=>`<option value="${m.nim}">${m.nim} — ${m.nama_lengkap}</option>`).join('');
    });
  }

  function loadDivisiForPengurus(){
    fetch(`/backend/controllers/admin-ukm/divisi.php?id_ukm=${id_ukm}`).then(r=>r.json()).then(data=>{
      allDivisi=data||[];
      document.getElementById('id_divisi').innerHTML='<option value="">Pilih Divisi</option>'+allDivisi.map(d=>`<option value="${d.id_divisi}">${d.nama_divisi}</option>`).join('');
      document.getElementById('jabatan_id_divisi').innerHTML='<option value="">Pilih Divisi</option>'+allDivisi.map(d=>`<option value="${d.id_divisi}">${d.nama_divisi}</option>`).join('');
    });
  }

  function loadJabatanByDivisi(id_divisi,selector){
    return fetch(`/backend/controllers/admin-ukm/jabatan_divisi.php?id_divisi=${id_divisi}`)
      .then(r=>r.json())
      .then(data=>{
        document.querySelector(selector).innerHTML='<option value="">Pilih Jabatan</option>'+(data||[]).map(j=>`<option value="${j.id_jabatan_divisi}">${j.nama_jabatan}</option>`).join('');
      });
  }

  function loadPeriodeDropdown(){
    fetch('/backend/controllers/admin-ukm/struktur_organisasi.php?action=get_periode').then(r=>r.json()).then(data=>{
      document.getElementById('id_periode').innerHTML='<option value="">Pilih Periode</option>'+(data||[]).map(p=>`<option value="${p.id_periode}">${p.tahun_mulai} – ${p.tahun_selesai}</option>`).join('');
    });
  }

  /* ── DIVISI CRUD ── */
  function openModalDivisi(){loadDivisiData();loadDivisiForPengurus();document.getElementById('modal-divisi').classList.add('open');}

  function loadDivisiData(){
    fetch(`/backend/controllers/admin-ukm/divisi.php?id_ukm=${id_ukm}`).then(r=>r.json()).then(data=>{
      allDivisi=data||[];
      const tbody=document.getElementById('tbody-divisi');
      if(!allDivisi.length){tbody.innerHTML='<tr><td colspan="4" style="text-align:center;padding:20px;color:var(--text-soft);">Belum ada divisi</td></tr>';return;}
      tbody.innerHTML=allDivisi.map(d=>`
        <tr>
          <td style="font-weight:600;">${d.nama_divisi}</td>
          <td><span class="badge badge-${d.tipe_divisi}">${d.tipe_divisi}</span></td>
          <td style="font-size:12px;color:var(--text-mid);">${d.deskripsi||'—'}</td>
          <td><div style="display:flex;gap:6px;">
            <button class="btn-icon btn-e" onclick="editDivisi(${d.id_divisi},'${esc(d.nama_divisi)}','${d.tipe_divisi}','${esc(d.deskripsi||'')}')"><i class="fas fa-pen"></i></button>
            <button class="btn-icon btn-d" onclick="deleteDivisi(${d.id_divisi})"><i class="fas fa-trash"></i></button>
          </div></td>
        </tr>`).join('');
    });
  }

  function editDivisi(id,nama,tipe,desk){
    document.getElementById('id_divisi_edit').value=id;
    document.getElementById('nama_divisi').value=nama;
    document.getElementById('tipe_divisi').value=tipe;
    document.getElementById('deskripsi_divisi').value=desk;
  }

  function resetFormDivisi(){
    document.getElementById('id_divisi_edit').value='';
    document.getElementById('nama_divisi').value='';
    document.getElementById('tipe_divisi').value='';
    document.getElementById('deskripsi_divisi').value='';
  }

  function saveDivisi(){
    const nama=document.getElementById('nama_divisi').value;
    const tipe=document.getElementById('tipe_divisi').value;
    if(!nama||!tipe){Swal.fire({icon:'warning',title:'Lengkapi data',text:'Nama dan tipe divisi wajib diisi.'});return;}
    const fd=new FormData();
    fd.append('nama_divisi',nama);fd.append('tipe_divisi',tipe);
    fd.append('deskripsi',document.getElementById('deskripsi_divisi').value);
    fd.append('id_ukm',id_ukm);
    const id=document.getElementById('id_divisi_edit').value;
    if(id) fd.append('id_divisi',id);
    fetch('/backend/controllers/admin-ukm/divisi.php',{method:'POST',body:fd})
      .then(r=>r.json())
      .then(res=>{
        if(res.status==='success'){Swal.fire({icon:'success',title:'Berhasil!',timer:1200,showConfirmButton:false});resetFormDivisi();loadDivisiData();loadStruktur();}
        else Swal.fire({icon:'error',title:'Gagal!',text:res.message});
      });
  }

  function deleteDivisi(id){
    Swal.fire({title:'Hapus divisi?',text:'Semua jabatan dalam divisi ini juga akan terhapus.',icon:'warning',showCancelButton:true,confirmButtonColor:'#F43F5E',cancelButtonColor:'#94A3B8',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'})
      .then(r=>{if(r.isConfirmed){
        fetch(`/backend/controllers/admin-ukm/divisi.php?id_divisi=${id}`,{method:'DELETE'}).then(r=>r.json()).then(res=>{
          if(res.status==='success'){Swal.fire({icon:'success',title:'Terhapus!',timer:1200,showConfirmButton:false});loadDivisiData();loadStruktur();}
          else Swal.fire({icon:'error',title:'Gagal!',text:res.message});
        });
      }});
  }

  /* ── JABATAN CRUD ── */
  function openModalJabatan(){loadDivisiForPengurus();loadJabatanData();document.getElementById('modal-jabatan').classList.add('open');}

  function loadJabatanData(){
    fetch(`/backend/controllers/admin-ukm/jabatan_divisi.php?id_ukm=${id_ukm}`).then(r=>r.json()).then(data=>{
      const list=Array.isArray(data)?data:[];
      document.getElementById('tbody-jabatan').innerHTML=list.length
        ?list.map(j=>`<tr>
            <td>${j.nama_divisi}</td>
            <td style="font-weight:600;">${j.nama_jabatan}</td>
            <td><span class="badge badge-inti">Level ${j.hierarki}</span></td>
            <td><div style="display:flex;gap:6px;">
              <button class="btn-icon btn-e" onclick="editJabatan(${j.id_jabatan_divisi},${j.id_divisi},'${esc(j.nama_jabatan)}',${j.hierarki})"><i class="fas fa-pen"></i></button>
              <button class="btn-icon btn-d" onclick="deleteJabatan(${j.id_jabatan_divisi})"><i class="fas fa-trash"></i></button>
            </div></td>
          </tr>`).join('')
        :'<tr><td colspan="4" style="text-align:center;padding:20px;color:var(--text-soft);">Belum ada jabatan</td></tr>';
    });
  }

  function editJabatan(id,id_div,nama,hier){
    document.getElementById('id_jabatan_edit').value=id;
    document.getElementById('jabatan_id_divisi').value=id_div;
    document.getElementById('nama_jabatan').value=nama;
    document.getElementById('hierarki_jabatan').value=hier;
  }

  function resetFormJabatan(){
    document.getElementById('id_jabatan_edit').value='';
    document.getElementById('jabatan_id_divisi').value='';
    document.getElementById('nama_jabatan').value='';
    document.getElementById('hierarki_jabatan').value='';
  }

  function saveJabatan(){
    const id_div=document.getElementById('jabatan_id_divisi').value;
    const nama=document.getElementById('nama_jabatan').value;
    const hier=document.getElementById('hierarki_jabatan').value;
    if(!id_div||!nama||!hier){Swal.fire({icon:'warning',title:'Lengkapi data',text:'Semua field wajib diisi.'});return;}
    const fd=new FormData();
    fd.append('id_divisi',id_div);fd.append('nama_jabatan',nama);fd.append('hierarki',hier);
    const id=document.getElementById('id_jabatan_edit').value;
    if(id) fd.append('id_jabatan_divisi',id);
    fetch('/backend/controllers/admin-ukm/jabatan_divisi.php',{method:'POST',body:fd})
      .then(r=>r.json())
      .then(res=>{
        if(res.status==='success'){Swal.fire({icon:'success',title:'Berhasil!',timer:1200,showConfirmButton:false});resetFormJabatan();loadJabatanData();}
        else Swal.fire({icon:'error',title:'Gagal!',text:res.message});
      });
  }

  function deleteJabatan(id){
    Swal.fire({title:'Hapus jabatan?',icon:'warning',showCancelButton:true,confirmButtonColor:'#F43F5E',cancelButtonColor:'#94A3B8',confirmButtonText:'Ya, hapus',cancelButtonText:'Batal'})
      .then(r=>{if(r.isConfirmed){
        fetch(`/backend/controllers/admin-ukm/jabatan_divisi.php?id_jabatan_divisi=${id}`,{method:'DELETE'}).then(r=>r.json()).then(res=>{
          if(res.status==='success'){Swal.fire({icon:'success',title:'Terhapus!',timer:1200,showConfirmButton:false});loadJabatanData();}
          else Swal.fire({icon:'error',title:'Gagal!',text:res.message});
        });
      }});
  }

  /* ── HELPERS ── */
  function esc(s){return(s||'').replace(/'/g,"\\'");}

  function logout(){
    Swal.fire({title:'Keluar dari SIGMA?',icon:'question',showCancelButton:true,confirmButtonColor:'#F43F5E',cancelButtonColor:'#94A3B8',confirmButtonText:'Ya, keluar',cancelButtonText:'Batal'})
      .then(r=>{if(r.isConfirmed)fetch('/backend/controllers/logout.php').then(()=>window.location.href='/index.html').catch(()=>window.location.href='/index.html');});
  }

  /* ── INIT ── */
  document.addEventListener('DOMContentLoaded', () => {
    loadUkmProfile(); // ← ambil nama UKM → sidebar
    loadAll();
  });
</script>
</body>
</html>