document.addEventListener('DOMContentLoaded', function() {
    loadProfileData();

    document.getElementById('editProfileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        updateProfile();
    });

    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        changePassword();
    });
});

// Load data profil
async function loadProfileData() {
    try {
        const response = await fetch('/backend/controllers/mahasiswa/profile/get_profile.php');
        const data = await response.json();
        
        if (data.status === 'success') {
            // Fill readonly fields
            document.getElementById('nim').value = data.data.nim;
            document.getElementById('nama_lengkap').value = data.data.nama_lengkap;
            document.getElementById('program_studi').value = data.data.nama_program_studi;
            document.getElementById('kelas').value = data.data.kelas;
            
            // Fill editable fields
            document.getElementById('alamat').value = data.data.alamat || '';
            document.getElementById('no_whatsapp').value = data.data.no_whatsapp || '';
            document.getElementById('email').value = data.data.email || '';
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire('Error', 'Gagal memuat data profil', 'error');
    }
}

// Update profile
async function updateProfile() {
    const formData = new FormData(document.getElementById('editProfileForm'));
    
    try {
        const response = await fetch('/backend/controllers/mahasiswa/profile/update_profile.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Profil berhasil diperbarui',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = '/frontend/src/pages/user/profile.html';
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire('Error', 'Gagal memperbarui profil', 'error');
    }
}

// Change password
async function changePassword() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (newPassword !== confirmPassword) {
        Swal.fire('Error', 'Password baru dan konfirmasi tidak cocok', 'error');
        return;
    }
    
    const formData = new FormData(document.getElementById('changePasswordForm'));
    
    try {
        const response = await fetch('/backend/controllers/mahasiswa/profile/change_password.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Password berhasil diperbarui',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                closePasswordModal();
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire('Error', 'Gagal memperbarui password', 'error');
    }
}

// Modal functions
function showChangePasswordModal() {
    document.getElementById('passwordModal').style.display = 'block';
}

function closePasswordModal() {
    document.getElementById('passwordModal').style.display = 'none';
    document.getElementById('changePasswordForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('passwordModal');
    if (event.target == modal) {
        closePasswordModal();
    }
}