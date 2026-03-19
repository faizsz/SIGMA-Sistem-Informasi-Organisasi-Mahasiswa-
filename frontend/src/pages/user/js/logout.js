// Fetch data from the PHP backend
fetch('/backend/controllers/get_mahasiswa.php')
    .then(response => response.json())
    .then(data => {
        if (data.length > 0) {
            // Assume we're taking the first student's data
            const student = data[0];
            document.getElementById('profileName').innerText = student.nama_lengkap;
            document.getElementById('profileNim').innerText = student.nim;
        }
    })
    .catch(error => console.error('Error fetching data:', error));

    // Logout function
    function logout() {
    fetch('/backend/controllers/mahasiswa/logout.php')
        .then(() => {
            window.location.href = "/index.html";
        })
        .catch(error => console.error('Error logging out:', error));
    }
