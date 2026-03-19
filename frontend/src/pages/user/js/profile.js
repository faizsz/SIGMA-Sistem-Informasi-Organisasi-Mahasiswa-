document.addEventListener("DOMContentLoaded", function() {
    initializeEditProfile();
    
    fetch('/backend/controllers/mahasiswa/auth.php')
        .then(response => response.json())
        .then(data => {
            if (!data.authenticated) {
                window.location.href = '/index.html';
            } else {
                loadProfileData();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.location.href = '/index.html';
        });
});

function initializeEditProfile() {
    const modal = document.getElementById('editProfileModal');
    const editBtn = document.getElementById('editProfileBtn');
    const closeBtn = document.querySelector('.close-btn');
    const form = document.getElementById('editProfileForm');
    const imagePreview = document.getElementById('imagePreview');
    const fileInput = document.getElementById('profile_picture');
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    const passwordForm = document.getElementById('changePasswordForm');

    if (!modal || !editBtn || !closeBtn || !form || !imagePreview || !fileInput) {
        console.error('Required elements not found');
        return;
    }

    // Tab switching logic
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.style.display = 'none');
            
            // Add active class to clicked button
            button.classList.add('active');
            
            // Show corresponding content
            const tabId = button.getAttribute('data-tab');
            if (tabId === 'profile') {
                document.getElementById('profileTab').style.display = 'block';
            } else {
                document.getElementById('passwordTab').style.display = 'block';
            }
        });
    });

    // Password change form handling
    passwordForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Clear previous messages
        const existingError = passwordForm.querySelector('.form-error');
        const existingSuccess = passwordForm.querySelector('.form-success');
        if (existingError) existingError.remove();
        if (existingSuccess) existingSuccess.remove();

        const oldPassword = document.getElementById('oldPassword').value;
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

         // Validate passwords match
         if (newPassword !== confirmPassword) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Password baru dan konfirmasi password tidak cocok!'
            });
            return;
        }

        try {
            const response = await fetch('/backend/controllers/mahasiswa/change_password.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    old_password: oldPassword,
                    new_password: newPassword
                })
            });

            const data = await response.json();
            
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Password berhasil diubah',
                    showConfirmButton: false,
                    timer: 1500
                });
                passwordForm.reset();
                modal.style.display = 'none';
                document.body.style.overflow = '';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat mengubah password'
            });
        }
    });


    editBtn.addEventListener('click', () => {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        loadCurrentProfileData();
    });

    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    });

    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file && validateFile(file)) {
            const reader = new FileReader();
            reader.onload = (event) => {
                imagePreview.innerHTML = `<img src="${event.target.result}" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        }
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        
        // Debug untuk memastikan foto ada dalam FormData
        const fileInput = document.getElementById('profile_picture');
        if (fileInput.files[0]) {
            console.log('File selected:', fileInput.files[0]);
        }
    
        try {
            // Tampilkan loading state
            Swal.fire({
                title: 'Memproses...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
    
            const response = await fetch('/backend/controllers/mahasiswa/profile.php', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
    
            if (data.status === 'success') {
                // Update tampilan foto profil langsung setelah berhasil
                if (fileInput.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        document.getElementById('profileImageDisplay').src = e.target.result;
                    };
                    reader.readAsDataURL(fileInput.files[0]);
                }
    
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Profile berhasil diperbarui',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    modal.style.display = 'none';
                    document.body.style.overflow = '';
                    location.reload(); // Reload halaman untuk memastikan semua perubahan terlihat
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error updating profile: ' + data.message
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Terjadi kesalahan saat memperbarui profile'
            });
        }
    });

    // File validation
    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file && validateFile(file)) {
            const reader = new FileReader();
            reader.onload = (event) => {
                imagePreview.innerHTML = `<img src="${event.target.result}" alt="Preview">`;
            };
            reader.readAsDataURL(file);
        }
    });
}

function loadProfileData() {
    fetch('/backend/controllers/mahasiswa/get_mahasiswa.php')
        .then(response => response.json())
        .then(data => {
            if (data.profile) {
                document.getElementById('profileName').textContent = data.profile.nama_lengkap;
                document.getElementById('profileNim').textContent = data.profile.nim;
                
                const profileImage = document.getElementById('profileImageDisplay');
                if (profileImage) {
                    if (data.profile.foto_path) {
                        profileImage.src = `/frontend/public/assets/profile/${data.profile.foto_path}`;
                    } else {
                        profileImage.src = '/frontend/public/assets/profile/pp.jpg';
                    }
                }
                
                updateUkmContainer('ukmAktifContainer', data.ukm_aktif, 'Tidak ada UKM yang sedang diikuti');
                updateUkmContainer('ukmHistoriContainer', data.ukm_histori, 'Tidak ada riwayat UKM');
                // Update container untuk UKM yang sedang didaftar
                updateUkmPendaftaranContainer(data.ukm_pendaftaran);
            } else if (data.status === 'error') {
                console.error(data.message);
                showError();
            }
        })
        .catch(error => {
            console.error('Error loading profile:', error);
            showError();
        });
}

// Tambahkan fungsi ini untuk menampilkan UKM yang sedang didaftar
function updateUkmPendaftaranContainer(pendaftaranData) {
    const container = document.getElementById('ukmPendaftaranContainer');
    container.innerHTML = '';
    
    if (pendaftaranData && pendaftaranData.length > 0) {
        pendaftaranData.forEach(ukm => {
            const statusText = getStatusDisplay(ukm.status);
            const card = document.createElement('div');
            card.className = 'card';
            
            card.innerHTML = `
                <img src="/frontend/public/assets/${ukm.logo_ukm}" 
                     alt="${ukm.nama_ukm}" 
                     class="ukm-logo"
                     onerror="this.src='/frontend/public/assets/default-ukm-logo.png'">
                <div class="card-content">
                    <h2 class="card-title">${ukm.nama_ukm}</h2>
                    <span class="status-badge">${statusText}</span>
                </div>
            `;
            
            container.appendChild(card);
        });
    } else {
        container.innerHTML = '<p class="info-text">Tidak ada UKM yang sedang didaftar</p>';
    }
}

function getStatusDisplay(status) {
    switch (status) {
        case 'PENDING_TAHAP1':
        case 'ACC_TAHAP1':
            return 'Anda Sedang di Tahap 1';
        case 'PENDING_TAHAP2':
        case 'ACC_TAHAP2':
            return 'Anda Sedang di Tahap 2';
        case 'PENDING_TAHAP3':
        case 'ACC_TAHAP3':
            return 'Anda Sedang di Tahap 3';
        default:
            return '';
    }
}

function updateUkmContainer(containerId, data, emptyMessage) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
    
    if (data && data.length > 0) {
        data.forEach(ukm => {
            const card = createUkmCard(ukm);
            container.appendChild(card);
        });
    } else {
        container.innerHTML = `<p class="info-text">${emptyMessage}</p>`;
    }
}

async function loadCurrentProfileData() {
    try {
        const [profileRes, prodiRes] = await Promise.all([
            fetch('/backend/controllers/mahasiswa/get_mahasiswa.php'),
            fetch('/backend/controllers/mahasiswa/get_program_studi.php')
        ]);

        const [profileData, prodiData] = await Promise.all([
            profileRes.json(),
            prodiRes.json()
        ]);

        if (!profileData.profile) {
            throw new Error('Profile data not found');
        }

        if (prodiData.status === 'success') {
            populateProdiDropdown(prodiData.data, profileData.profile.id_program_studi);
        }

        populateFormFields(profileData.profile);
        updateProfilePreview(profileData.profile.foto_path);

    } catch (error) {
        console.error('Error loading profile data:', error);
        alert('Failed to load profile data. Please try again.');
    }
}

function populateProdiDropdown(prodiData, selectedId) {
    const select = document.getElementById('program_studi');
    select.innerHTML = '<option value="">Pilih Program Studi</option>';
    prodiData.forEach(prodi => {
        const option = document.createElement('option');
        option.value = prodi.id_program_studi;
        option.textContent = prodi.nama_program_studi;
        select.appendChild(option);
    });
    select.value = selectedId || '';
}

function populateFormFields(profile) {
    const form = document.getElementById('editProfileForm');
    form.nama.value = profile.nama_lengkap || '';
    form.kelas.value = profile.kelas || '';
    form.alamat.value = profile.alamat || '';
    form.no_whatsapp.value = profile.no_whatsapp || '';
    form.email.value = profile.email || '';

    if (profile.jenis_kelamin === 'Laki-laki') {
        form.jk_laki.checked = true;
    } else if (profile.jenis_kelamin === 'Perempuan') {
        form.jk_perempuan.checked = true;
    }
}

function updateProfilePreview(fotoPath) {
    const imagePreview = document.getElementById('imagePreview');
    if (fotoPath) {
        imagePreview.innerHTML = `<img src="/frontend/public/assets/profile/${fotoPath}" alt="Current Profile">`;
    } else {
        imagePreview.innerHTML = '<p>No profile picture</p>';
    }
}

function createUkmCard(ukm) {
    const card = document.createElement('div');
    card.className = 'card';
    
    const statusText = ukm.status ? `${ukm.status} - ` : '';
    const periodeText = `Periode ${ukm.periode}`;
    
    card.innerHTML = `
        <img src="/frontend/public/assets/${ukm.logo_ukm}" 
             alt="${ukm.nama_ukm}" 
             class="ukm-logo"
             onerror="this.src='/frontend/public/assets/default-ukm-logo.png'">
        <div class="card-content">
            <h2 class="card-title">${ukm.nama_ukm}</h2>
            <p class="card-description">${statusText}${periodeText}</p>
        </div>
    `;
    
    return card;
}

function showError() {
    document.getElementById('profileName').textContent = 'Error loading data';
    document.getElementById('profileNim').textContent = 'Please try again later';
    
    ['ukmAktifContainer', 'ukmHistoriContainer'].forEach(containerId => {
        const container = document.getElementById(containerId);
        container.innerHTML = '<p class="error-text">Error loading UKM data</p>';
    });
}

function validateFile(file) {
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    const maxSize = 5 * 1024 * 1024; // 5MB

    if (!allowedTypes.includes(file.type)) {
        Swal.fire({
            icon: 'error',
            title: 'File Tidak Valid',
            text: 'Hanya file JPG, PNG dan GIF yang diperbolehkan!'
        });
        return false;
    }

    if (file.size > maxSize) {
        Swal.fire({
            icon: 'error',
            title: 'File Terlalu Besar',
            text: 'Ukuran file maksimal adalah 5MB!'
        });
        return false;
    }

    return true;
}