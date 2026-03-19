document.addEventListener("DOMContentLoaded", function () {
        // Cek otentikasi ketika halaman dimuat
        fetch('/backend/controllers/mahasiswa/auth.php')
            .then(response => response.json())
            .then(data => {
                if (!data.authenticated) {
                    // Jika tidak terautentikasi, redirect ke halaman login
                    window.location.href = '/index.html';
                } else {
                    // Lakukan sesuatu dengan data.role jika perlu
                    console.log('Role:', data.role);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                window.location.href = '/index.html'; // Redirect jika ada kesalahan
            });
    });
