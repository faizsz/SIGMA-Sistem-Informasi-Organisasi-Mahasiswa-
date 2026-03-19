document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const maxSize = 2 * 1024 * 1024;
            const file = this.files[0];
            
            if (file) {
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'File terlalu besar. Maksimal 2MB'
                    });
                    this.value = '';
                    return;
                }

                const allowedTypes = {
                    'scan_ktm': ['application/pdf', 'image/jpeg', 'image/png'],
                    'scan_khs': ['application/pdf', 'image/jpeg', 'image/png'],
                    'cv': ['application/pdf'],
                    'motivation_letter': ['application/pdf']
                };

                const inputId = this.id;
                if (allowedTypes[inputId] && !allowedTypes[inputId].includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Format file tidak sesuai'
                    });
                    this.value = '';
                    return;
                }

                const label = this.previousElementSibling;
                const icon = label.querySelector('i');
                label.innerHTML = file.name + ' ';
                if (icon) {
                    label.appendChild(icon);
                }
            }
        });
    });
});

document.getElementById('registrationForm').addEventListener('submit', async function(event) {
    event.preventDefault();

    const formData = new FormData();
    const id_ukm = new URLSearchParams(window.location.search).get('id_ukm');
    formData.append('id_ukm', id_ukm);

    const requiredFiles = {
        'scan_ktm': {
            maxSize: 2,
            allowedTypes: ['application/pdf', 'image/jpeg', 'image/png']
        },
        'scan_khs': {
            maxSize: 2,
            allowedTypes: ['application/pdf', 'image/jpeg', 'image/png']
        },
        'cv': {
            maxSize: 2,
            allowedTypes: ['application/pdf']
        },
        'motivation_letter': {
            maxSize: 2,
            allowedTypes: ['application/pdf']
        }
    };

    try {
        for (const [inputId, requirements] of Object.entries(requiredFiles)) {
            const fileInput = document.getElementById(inputId);
            const file = fileInput.files[0];

            if (!file) {
                throw new Error(`Silakan pilih file untuk ${inputId.replace(/_/g, ' ')}`);
            }

            if (file.size > requirements.maxSize * 1024 * 1024) {
                throw new Error(`File ${inputId.replace(/_/g, ' ')} terlalu besar. Maksimal ${requirements.maxSize}MB`);
            }

            if (!requirements.allowedTypes.includes(file.type)) {
                throw new Error(`Format file ${inputId.replace(/_/g, ' ')} tidak sesuai`);
            }

            formData.append(inputId, file);
        }

        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.textContent = 'Mengunggah...';

        const response = await fetch('/backend/controllers/mahasiswa/submit_tahap3.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: 'Pendaftaran berhasil!',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = `/frontend/src/pages/user/status_pending.php?tahap=3`;
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