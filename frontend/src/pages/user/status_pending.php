<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pendaftaran</title>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Pertahankan styling yang konsisten */
        body {
            font-family: 'Merriweather Sans', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .progress-bar {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background: white;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .step {
            flex: 1;
            text-align: center;
            padding: 10px;
            background-color: #f0f0f0;
            margin: 0 10px;
            border-radius: 5px;
        }

        .step.active {
            background-color: #4263eb;
            color: white;
        }

        .status-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }

        .success-icon {
            width: 100px;
            height: 100px;
            margin: 20px auto;
        }

        .success-title {
            color: #4263eb;
            font-size: 24px;
            margin: 20px 0;
        }

        .status-info {
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .button-group {
            margin-top: 20px;
        }

        .btn-back {
            background-color: #4263eb;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-back:hover {
            background-color: #374151;
        }
    </style>
</head>
<body>
    <div id="navbar"></div>

    <?php
    // Get tahap from URL
    $tahap = $_GET['tahap'] ?? '1';
    ?>

    <div class="progress-bar">
        <?php
        for($i = 1; $i <= 3; $i++) {
            $activeClass = ($i <= $tahap) ? 'active' : '';
            echo "<div class='step $activeClass'>
                    <span class='step-label'>TAHAP $i</span>
                  </div>";
        }
        ?>
    </div>

    <div class="status-container">
        <svg class="success-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="10" stroke="#4263eb" stroke-width="2"/>
            <path d="M8 12L11 15L16 9" stroke="#4263eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>

        <h1 class="success-title">Tahap <?php echo htmlspecialchars($tahap); ?> Telah Disubmit</h1>
        
        <div class="status-info">
            <p>Status: PENDING</p>
            <p>Mohon tunggu konfirmasi dari admin UKM</p>
            <p>Anda akan mendapatkan notifikasi setelah admin memverifikasi pendaftaran Anda.</p>
        </div>

        <div class="button-group">
            <a href="/frontend/src/pages/user/UKM.html" class="btn-back">Kembali ke Halaman UKM</a>
        </div>
    </div>
    <script>
    // Add this script to handle id_ukm from sessionStorage
    document.addEventListener('DOMContentLoaded', function() {
        // Get id_ukm from sessionStorage
        const id_ukm = sessionStorage.getItem('current_ukm_id');
        
        if (!id_ukm) {
            console.error('No UKM ID found in session');
            window.location.href = '/frontend/src/pages/user/UKM.html';
            return;
        }

        // You can use the id_ukm here to make any necessary API calls
        // without showing it in the URL
    });
    </script>
    <script src="/frontend/src/utils/navbar/navbar.js"></script>
    <script src="/frontend/src/pages/user/js/auth.js"></script>           
    <script src="/frontend/src/pages/user/js/pendaftaran.js"></script>
</body>
</html>