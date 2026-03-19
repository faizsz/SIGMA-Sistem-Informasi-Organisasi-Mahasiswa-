// Add this script right after the registration-cta section
document.addEventListener('DOMContentLoaded', async function() {
    try {
        // Get UKM ID from URL
        const urlParams = new URLSearchParams(window.location.search);
        const id_ukm = urlParams.get('id');
        
        if (!id_ukm) {
            console.error('ID UKM not found in URL');
            return;
        }

        // Store ID UKM in sessionStorage for later use
        sessionStorage.setItem('current_ukm_id', id_ukm);

        // Check authentication status first
        const authResponse = await fetch('/backend/controllers/mahasiswa/auth.php');
        const authData = await authResponse.json();

        if (!authData.authenticated) {
            // If not authenticated, redirect to login when clicking daftar
            const daftarBtn = document.querySelector('.registration-cta button');
            if (daftarBtn) {
                daftarBtn.addEventListener('click', () => {
                    window.location.href = '/frontend/src/pages/auth/login.html';
                });
            }
            return;
        }

        // Check registration period
        const periodResponse = await fetch(`/backend/controllers/mahasiswa/cek-periode-pendaftaran.php?id_ukm=${id_ukm}`);
        const periodData = await periodResponse.json();

        // Check registration status
        const statusResponse = await fetch(`/backend/controllers/mahasiswa/cek-status-pendaftaran.php?id_ukm=${id_ukm}`);
        const statusData = await statusResponse.json();

        const daftarBtn = document.querySelector('.registration-cta button');
        if (!daftarBtn) return;

        // Update button based on period and status
        if (periodData.status === 'success') {
            if (!periodData.is_open) {
                daftarBtn.textContent = 'Pendaftaran Ditutup';
                daftarBtn.disabled = true;
                return;
            }

            if (statusData.status === 'success') {
                const registrationStatus = statusData.data.status.toLowerCase();
                updateButtonStatus(daftarBtn, registrationStatus);
            }

            // Add click event listener
            daftarBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                if (!daftarBtn.disabled) {
                    await handleDaftar();
                }
            });
        } else {
            console.error('Failed to check registration period');
            daftarBtn.disabled = true;
        }

    } catch (error) {
        console.error('Error checking registration status:', error);
    }
});

// Function to update button status
function updateButtonStatus(button, status) {
    const buttonTexts = {
        'belum_daftar': 'Daftar Sekarang',
        'pending_tahap1': 'Sedang Diproses',
        'acc_tahap1': 'Lihat Hasil',
        'pending_tahap2': 'Sedang Diproses',
        'acc_tahap2': 'Lihat Hasil',
        'pending_tahap3': 'Sedang Diproses',
        'acc_tahap3': 'Pendaftaran Selesai',
        'ditolak': 'Pendaftaran Ditolak',
        'periode_tutup': 'Pendaftaran Ditutup'
    };

    button.textContent = buttonTexts[status] || 'Daftar Sekarang';
}

// Function to handle registration process
async function handleDaftar() {
    try {
        const id_ukm = sessionStorage.getItem('current_ukm_id');
        if (!id_ukm) {
            throw new Error('ID UKM tidak ditemukan');
        }

        const response = await fetch(`/backend/controllers/mahasiswa/cek-status-pendaftaran.php?id_ukm=${id_ukm}`);
        const statusData = await response.json();

        if (statusData.status === 'success') {
            const registrationStatus = statusData.data.status.toLowerCase();
            
            // Route based on status
            switch (registrationStatus) {
                case 'belum_daftar':
                    window.location.href = `/frontend/src/pages/user/form_daftar1.html?id_ukm=${id_ukm}`;
                    break;
                case 'pending_tahap1':
                    window.location.href = `/frontend/src/pages/user/status_pending.php?tahap=1`;
                    break;
                case 'acc_tahap1':
                    window.location.href = `/frontend/src/pages/user/acc_form1.html?id_ukm=${id_ukm}`;
                    break;
                case 'pending_tahap2':
                    window.location.href = `/frontend/src/pages/user/status_pending.php?tahap=2`;
                    break;
                case 'acc_tahap2':
                    window.location.href = `/frontend/src/pages/user/acc_form2.html?id_ukm=${id_ukm}`;
                    break;
                case 'pending_tahap3':
                    window.location.href = `/frontend/src/pages/user/status_pending.php?tahap=3`;
                    break;
                case 'acc_tahap3':
                    window.location.href = `/frontend/src/pages/user/acc_form3.html?id_ukm=${id_ukm}`;
                    break;
                case 'ditolak':
                    window.location.href = `/frontend/src/pages/user/rejected_page.html?id_ukm=${id_ukm}`;
                    break;
                default:
                    throw new Error(`Status tidak dikenal: ${registrationStatus}`);
            }
        } else {
            throw new Error(statusData.message || 'Terjadi kesalahan pada server');
        }

    } catch (error) {
        console.error('Error in handleDaftar:', error);
        alert(error.message || 'Terjadi kesalahan saat memproses pendaftaran');
    }
}