document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk memuat navbar
    function loadNavbar() {
        // Sesuaikan path ini dengan lokasi navbar.html Anda
        fetch('/frontend/src/utils/navbar/navbar.html')
            .then(response => response.text())
            .then(data => {
                document.getElementById('navbar').innerHTML = data;
                initNavbar(); // Panggil fungsi untuk inisialisasi navbar setelah dimuat
            })
            .catch(error => console.error('Error loading navbar:', error));
    }

    // Fungsi untuk inisialisasi navbar (hamburger menu)
    function initNavbar() {
        const hamburger = document.querySelector(".hamburger");
        const navMenu = document.querySelector(".nav-menu");

        hamburger.addEventListener("click", () => {
            hamburger.classList.toggle("active");
            navMenu.classList.toggle("active");
        });

        document.querySelectorAll(".nav-link").forEach(n => n.addEventListener("click", () => {
            hamburger.classList.remove("active");
            navMenu.classList.remove("active");
        }));
    }

    // Panggil fungsi loadNavbar saat DOM sudah siap
    loadNavbar();
});
