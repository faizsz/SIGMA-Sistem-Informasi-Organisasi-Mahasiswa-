document.getElementById('username').addEventListener('input', function() {
    const errorMessageContainer = document.getElementById('errorMessageContainer');
    errorMessageContainer.style.display = 'none';
});

document.getElementById('password').addEventListener('input', function() {
    const errorMessageContainer = document.getElementById('errorMessageContainer');
    errorMessageContainer.style.display = 'none';
});

function showErrorMessage(message) {
    const errorMessageContainer = document.getElementById('errorMessageContainer');
    const errorMessageText = document.getElementById('errorMessageText');
    
    errorMessageText.textContent = message;
    errorMessageContainer.style.display = 'flex';
    
    setTimeout(() => {
        errorMessageContainer.style.display = 'none';
    }, 5000);
}

function validateInput(username, password) {
    if (!username.trim()) {
        showErrorMessage('Silakan masukkan username');
        return false;
    }
    if (!password.trim()) {
        showErrorMessage('Silakan masukkan password');
        return false;
    }
    return true;
}

// Handle role-based redirect
function handleRoleRedirect(role, idUkm = null) {
    switch(role.toLowerCase()) {
        case 'mahasiswa':
            window.location.href = '/frontend/src/pages/user/beranda.html'; 
            break;
        case 'super_admin':
            window.location.href = '/frontend/src/pages/admin/dashboard.html';
            break;
        case 'admin_ukm':
            if (idUkm) {
                window.location.href = `/frontend/src/pages/admin-ukm/dashboard.php?id_ukm=${idUkm}`;
            } else {
                showErrorMessage('ID UKM tidak ditemukan.');
            }
            break;
        default:
            showErrorMessage('Role tidak valid');
            break;
    }
}

document.getElementById('loginForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    if (!validateInput(username, password)) {
        return;
    }
    
    try {
        const response = await fetch('/backend/controllers/mahasiswa/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ username, password })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.status === 'success') {
            document.getElementById('errorMessageContainer').style.display = 'none';
            handleRoleRedirect(result.user.role, result.user.id_ukm);
        } else {
            showErrorMessage(result.message || 'Maaf, username atau password yang anda masukkan salah.');
        }
    } catch (error) {
        console.error('Error:', error);
        showErrorMessage('Terjadi kesalahan pada server. Silakan coba lagi.');
    }
});


document.getElementById('errorMessageContainer').addEventListener('click', function() {
    this.style.display = 'none';
});

// Mengaktifkan tombol login saat semua input terisi
document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("loginForm");
    const loginButton = document.getElementById("loginButton");

    loginForm.addEventListener("input", function () {
        const username = document.getElementById("username").value;
        const password = document.getElementById("password").value;

        if (username && password) {
            loginButton.classList.add("active");
            loginButton.disabled = false;
        } else {
            loginButton.classList.remove("active");
            loginButton.disabled = true;
        }
    });
});

