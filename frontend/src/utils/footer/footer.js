fetch('/frontend/src/utils/footer/footer.html')
            .then(response => response.text())
            .then(data => document.getElementById('footer-container').innerHTML = data)
            .catch(error => console.error('Gagal memuat footer:', error));