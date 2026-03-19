// Load data mahasiswa saat halaman dimuat
document.addEventListener('DOMContentLoaded', async function() {
    try {
        const response = await fetch('/backend/controllers/mahasiswa/get_mahasiswa_form.php');
        const result = await response.json();
        
        if (result.status === 'success') {
            populateForm(result.data);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal memuat data mahasiswa'
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan saat memuat data'
        });
    }
});

// Fungsi untuk mengisi form dengan data mahasiswa
function populateForm(data) {
    document.getElementById('nim').value = data.nim;
    document.getElementById('nama_lengkap').value = data.nama_lengkap;
    document.getElementById('program_studi').value = data.program_studi;
    document.getElementById('kelas').value = data.kelas;
    document.getElementById('jenis_kelamin').value = data.jenis_kelamin;
    document.getElementById('alamat').value = data.alamat;
    document.getElementById('no_whatsapp').value = data.no_whatsapp;
}


// Handle submit form
document.getElementById('registrationForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    
    const urlParams = new URLSearchParams(window.location.search);
    const id_ukm = urlParams.get('id_ukm');
    
    if (!id_ukm) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'ID UKM tidak ditemukan'
        });
        return;
    }
    
    const formData = new FormData();
    formData.append('id_ukm', id_ukm);
    formData.append('motivasi', document.getElementById('motivasi').value);

    try {
        const response = await fetch('/backend/controllers/submit_tahap1.php', {
            method: 'POST',
            body: formData
        });

        const responseText = await response.text();
        console.log('Raw response:', responseText);

        let result;
        try {
            result = JSON.parse(responseText);
        } catch (e) {
            console.error('Failed to parse JSON:', responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan pada server'
            });
            return;
        }
        
        if (result.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: 'Pendaftaran berhasil!',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = `/frontend/src/pages/user/status_pending.php?tahap=1`;
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result.message || 'Terjadi kesalahan'
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan. Silakan coba lagi.'
        });
    }
});