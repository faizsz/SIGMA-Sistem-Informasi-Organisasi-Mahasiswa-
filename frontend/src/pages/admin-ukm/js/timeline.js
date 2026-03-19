$(document).ready(function () {
    // Ambil id_ukm dari session PHP
    const id_ukm = document.querySelector('meta[name="id_ukm"]').content;
    
    bsCustomFileInput.init();
    loadTimeline();

    // Function to load timeline data
    function loadTimeline() {
        $.ajax({
            url: `/backend/controllers/admin-ukm/timeline.php?id_ukm=${id_ukm}`,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log('Data timeline:', data);
                populateTable(data);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    }

    // Populate table with data
    function populateTable(data) {
        const tbody = $('#table-timeline tbody');
        tbody.empty();
        $.each(data, function(index, item) {
            // Helper function untuk menentukan warna badge berdasarkan jenis
            const getBadgeClass = (jenis) => {
                switch(jenis) {
                    case 'proker':
                        return 'badge-primary';
                    case 'agenda':
                        return 'badge-warning';
                    default:
                        return 'badge-secondary';
                }
            };
    
            // Helper function untuk format text jenis
            const formatJenis = (jenis) => {
                switch(jenis) {
                    case 'proker':
                        return 'Program Kerja';
                    case 'agenda':
                        return 'Agenda';
                    default:
                        return jenis;
                }
            };
    
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.judul_kegiatan || 'N/A'}</td>
                    <td>${item.deskripsi || 'N/A'}</td>
                    <td>${formatDate(item.tanggal_kegiatan)}</td>
                    <td>${formatTime(item.waktu_mulai)} - ${formatTime(item.waktu_selesai)}</td>
                    <td>
                        <span class="badge badge-${item.status === 'active' ? 'success' : 'danger'}">
                            ${item.status}
                        </span>
                    </td>
                    <td>
                        <span class="badge ${getBadgeClass(item.jenis)}">
                            ${formatJenis(item.jenis)}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-info btn-sm manage-panitia" data-id="${item.id_timeline}">
                            <i class="fas fa-users"></i> Panitia (${item.jumlah_panitia || 0})
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-info btn-sm manage-rapat" data-id="${item.id_timeline}">
                            <i class="fas fa-book"></i> Rapat (${item.jumlah_rapat || 0})
                        </button>
                    </td>
                    <td>
                        <img src='/frontend/public/assets/${item.image_path || 'default.png'}' 
                             width='50' height='50' 
                             class="img-circle" 
                             alt='Foto'>
                    </td>
                    <td>
                        <button class="btn btn-primary btn-sm edit-btn" data-id="${item.id_timeline}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${item.id_timeline}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Helper function untuk format tanggal
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('id-ID', {
            day: '2-digit',
            month: 'long',
            year: 'numeric'
        }).format(date);
    }

    // Helper function untuk format waktu
    function formatTime(timeString) {
        if (!timeString) return 'N/A';
        const [hours, minutes] = timeString.split(':');
        return `${hours}:${minutes}`;
    }

    function resetForm() {
        $('#form-timeline')[0].reset();
        $('#id_timeline').val('');
        $('#preview-image').empty();
        $('#status').prop('checked', true);
        $('#jenis').val(''); // Reset ke pilihan default
    }

    // Add button click
    $('#add-btn').on('click', function() {
        resetForm();
        $('#modal-title').text('Tambah Timeline');
        $('#modal-form').modal('show');
    });

   
  // Edit button click
    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.ajax({
            url: `/backend/controllers/admin-ukm/timeline.php?id_timeline=${id}`,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log('Data dari server:', data); // Debug
                $('#id_timeline').val(data.id_timeline);
                $('#judul_kegiatan').val(data.judul_kegiatan);
                $('#deskripsi').val(data.deskripsi);
                $('#tanggal_kegiatan').val(data.tanggal_kegiatan);
                $('#waktu_mulai').val(data.waktu_mulai);
                $('#waktu_selesai').val(data.waktu_selesai);
                $('#status').prop('checked', data.status === 'active');
                $('#jenis').val(data.jenis);
                console.log('Nilai jenis yang diset:', data.jenis); // Debug
                
                if (data.image_path) {
                    $('#preview-image').html(`
                        <img src="/frontend/public/assets/${data.image_path}" 
                            width="200" 
                            class="img-thumbnail" 
                            alt="Preview">
                    `);
                }
                
                $('#modal-title').text('Edit Timeline');
                $('#modal-form').modal('show');
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                Swal.fire('Error!', 'Gagal mengambil data timeline', 'error');
            }
        });
    });

    // Form submit
    $('#form-timeline').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('id_ukm', id_ukm);

        // Debug untuk melihat nilai yang dikirim
        console.log('Jenis yang dipilih:', formData.get('jenis'));

        $.ajax({
            url: '/backend/controllers/admin-ukm/timeline.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    $('#modal-form').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data timeline berhasil disimpan',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        loadTimeline();
                    });
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                Swal.fire('Error!', 'Gagal menyimpan data timeline', 'error');
            }
        });
    });

    // Delete button click
    $(document).on('click', '.delete-btn', function() {
        const id_timeline = $(this).data('id');
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data timeline akan dihapus beserta data panitia dan rapat terkait!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteTimeline(id_timeline);
            }
        });
    });

    // Function untuk menghapus timeline
    function deleteTimeline(id_timeline) {
        $.ajax({
            url: `/backend/controllers/admin-ukm/timeline.php?id_timeline=${id_timeline}`,
            method: 'DELETE',
            success: function(response) {
                if(response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus!',
                        text: 'Data timeline berhasil dihapus',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        loadTimeline(); // Reload tabel timeline
                    });
                } else {
                    Swal.fire('Error!', response.message || 'Gagal menghapus data', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                Swal.fire('Error!', 'Gagal menghapus data timeline', 'error');
            }
        });
    }

    // Preview image
    $('#image').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-image').html(`
                    <img src="${e.target.result}" 
                         width="200" 
                         class="img-thumbnail" 
                         alt="Preview">
                `);
            }
            reader.readAsDataURL(file);
        }
    });
});