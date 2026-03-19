document.addEventListener('DOMContentLoaded', async function() {
    const urlParams = new URLSearchParams(window.location.search);
    const id_ukm = urlParams.get('id_ukm');

    if (!id_ukm) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'ID UKM tidak ditemukan'
        }).then(() => {
            window.location.href = '/frontend/src/pages/user/UKM.html';
        });
        return;
    }

    try {
        const response = await fetch(`/backend/controllers/mahasiswa/get_divisi_ukm.php?id_ukm=${id_ukm}`);
        const result = await response.json();

        if (result.status === 'success') {
            const divisi1 = document.getElementById('divisi1');
            const divisi2 = document.getElementById('divisi2');

            result.data.forEach(divisi => {
                divisi1.add(new Option(divisi.nama_divisi, divisi.id_divisi));
                divisi2.add(new Option(divisi.nama_divisi, divisi.id_divisi));
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Gagal memuat data divisi'
        });
    }

    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'File terlalu besar. Maksimal 2MB'
                    });
                    this.value = '';
                    return;
                }

                const label = this.previousElementSibling;
                const icon = label.querySelector('i');
                label.textContent = file.name + ' ';
                label.appendChild(icon);
            }
        });
    });
});

document.getElementById('registrationForm').addEventListener('submit', async function(event) {
    event.preventDefault();

    const formData = new FormData();
    const id_ukm = new URLSearchParams(window.location.search).get('id_ukm');
    
    if (!id_ukm) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'ID UKM tidak ditemukan'
        });
        return;
    }

    formData.append('id_ukm', id_ukm);
    formData.append('divisi_pilihan_1', document.getElementById('divisi1').value);
    formData.append('divisi_pilihan_2', document.getElementById('divisi2').value);

    const fileInputs = {
        'izin_ortu': document.getElementById('izin_ortu'),
        'sertifikat_wa_rna': document.getElementById('sertifikat_wa_rna'),
        'sertifikat_lkmm': document.getElementById('sertifikat_lkmm')
    };

    try {
        for (const [key, input] of Object.entries(fileInputs)) {
            const file = input.files[0];
            if (!file) {
                throw new Error(`Silakan pilih file untuk ${key.replace(/_/g, ' ')}`);
            }

            if (file.size > 2 * 1024 * 1024) {
                throw new Error(`File ${key.replace(/_/g, ' ')} terlalu besar. Maksimal 2MB`);
            }

            formData.append(key, file);
        }

        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.textContent = 'Mengunggah...';

        const submitResponse = await fetch('/backend/controllers/submit_tahap2.php', {
            method: 'POST',
            body: formData
        });

        const result = await submitResponse.json();

        if (result.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: 'Pendaftaran berhasil!',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = `/frontend/src/pages/user/status_pending.php?tahap=2`;
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result.message || 'Terjadi kesalahan'
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Terjadi kesalahan. Silakan coba lagi.'
        });
        console.error('Error:', error);
    } finally {
        const submitButton = this.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.textContent = 'Submit';
        }
    }
});