$(document).ready(function() {
    // Initialize plugins
    bsCustomFileInput.init();
    
    // Event handler untuk tombol manage rapat
    $(document).on('click', '.manage-rapat', function() {
        const id_timeline = $(this).data('id');
        $('#id_timeline_rapat').val(id_timeline);
        resetFormRapat();
        loadRapatData(id_timeline);
        $('#modal-rapat').modal('show');
    });

    // Reset form rapat
    function resetFormRapat() {
        $('#form-rapat')[0].reset();
        $('#id_rapat').val('');
        $('.custom-file-label').html('Pilih file');
        $('#preview-dokumentasi').empty();
    }

    // Load data rapat
    function loadRapatData(id_timeline) {
        $.ajax({
            url: `/backend/controllers/admin-ukm/rapat.php?id_timeline=${id_timeline}`,
            method: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    populateRapatTable(response.data);
                } else {
                    console.error('Failed to load rapat:', response.message);
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                Swal.fire('Error!', 'Gagal memuat data rapat', 'error');
            }
        });
    }

    // Populate tabel rapat
    function populateRapatTable(data) {
        const tbody = $('#table-rapat tbody');
        tbody.empty();
        
        if (data.length === 0) {
            tbody.append(`
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data rapat</td>
                </tr>
            `);
            return;
        }
        
        data.forEach((item, index) => {
            tbody.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.judul}</td>
                    <td>${formatDate(item.tanggal)}</td>
                    <td>
                        ${item.notulensi_path ? 
                            `<a href="/frontend/public/assets/notulensi/${item.notulensi_path}" target="_blank" 
                                class="btn btn-info btn-sm">
                                <i class="fas fa-file-pdf"></i> Lihat
                            </a>` : 
                            'Tidak ada file'
                        }
                    </td>
                    <td>
                        ${item.jumlah_foto > 0 ? 
                            `<button class="btn btn-info btn-sm view-dokumentasi" data-id="${item.id_rapat}">
                                <i class="fas fa-images"></i> Lihat (${item.jumlah_foto})
                            </button>` : 
                            'Tidak ada foto'
                        }
                    </td>
                    <td>
                        <button class="btn btn-primary btn-sm edit-rapat" data-id="${item.id_rapat}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm delete-rapat" data-id="${item.id_rapat}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    // Handle form submit
    $('#form-rapat').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        // Validasi form
        if (!formData.get('judul_rapat') || !formData.get('tanggal_rapat')) {
            Swal.fire('Error!', 'Semua field harus diisi', 'error');
            return;
        }

        $.ajax({
            url: '/backend/controllers/admin-ukm/rapat.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    resetFormRapat();
                    loadRapatData($('#id_timeline_rapat').val());
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                Swal.fire('Error!', 'Gagal menyimpan data rapat', 'error');
            }
        });
    });

    // Edit rapat
    $(document).on('click', '.edit-rapat', function() {
        const id_rapat = $(this).data('id');
        
        $.ajax({
            url: `/backend/controllers/admin-ukm/rapat.php?id_rapat=${id_rapat}`,
            method: 'GET',
            success: function(response) {
                if (response.status === 'success') {
                    const data = response.data;
                    
                    $('#id_rapat').val(data.id_rapat);
                    $('#judul_rapat').val(data.judul);
                    $('#tanggal_rapat').val(data.tanggal);
                    
                    // Reset file input labels
                    $('.custom-file-label').html('Pilih file');
                    
                    // Show existing dokumentasi preview if any
                    const previewContainer = $('#preview-dokumentasi');
                    previewContainer.empty();
                    
                    if (data.dokumentasi && data.dokumentasi.length > 0) {
                        data.dokumentasi.forEach(foto => {
                            previewContainer.append(`
                                <div class="col-md-3 mb-2">
                                    <img src="/frontend/public/assets/dokumentasi/${foto.foto_path}" 
                                         class="img-thumbnail" 
                                         style="height: 100px; object-fit: cover;">
                                </div>
                            `);
                        });
                    }
                    
                    // Scroll to form
                    $('html, body').animate({
                        scrollTop: $("#form-rapat").offset().top - 100
                    }, 500);
                    
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                Swal.fire('Error!', 'Gagal memuat data rapat', 'error');
            }
        });
    });

    // Delete rapat
    $(document).on('click', '.delete-rapat', function() {
        const id_rapat = $(this).data('id');
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data rapat akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/backend/controllers/admin-ukm/rapat.php?id_rapat=${id_rapat}`,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            loadRapatData($('#id_timeline_rapat').val());
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        Swal.fire('Error!', 'Gagal menghapus data rapat', 'error');
                    }
                });
            }
        });
    });

    // Preview dokumentasi
    $(document).on('click', '.view-dokumentasi', function() {
        const id_rapat = $(this).data('id');
        
        $.ajax({
            url: `/backend/controllers/admin-ukm/rapat.php?id_rapat=${id_rapat}`,
            method: 'GET',
            success: function(response) {
                if (response.status === 'success' && response.data.dokumentasi) {
                    const carousel = $('#dokumentasi-carousel .carousel-inner');
                    carousel.empty();
                    
                    response.data.dokumentasi.forEach((foto, index) => {
                        carousel.append(`
                            <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                <img src="/frontend/public/assets/dokumentasi/${foto.foto_path}" 
                                     class="d-block w-100" 
                                     style="height: 400px; object-fit: contain;">
                            </div>
                        `);
                    });
                    
                    $('#modal-preview-dokumentasi').modal('show');
                } else {
                    Swal.fire('Error!', 'Tidak ada dokumentasi', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                Swal.fire('Error!', 'Gagal memuat dokumentasi', 'error');
            }
        });
    });

    // Preview file sebelum upload
    $('#dokumentasi').on('change', function() {
        const previewContainer = $('#preview-dokumentasi');
        previewContainer.empty();
        
        if (this.files) {
            [...this.files].forEach(file => {
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewContainer.append(`
                            <div class="col-md-3 mb-2">
                                <img src="${e.target.result}" 
                                     class="img-thumbnail" 
                                     style="height: 100px; object-fit: cover;">
                            </div>
                        `);
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });

    // Format tanggal
    function formatDate(dateString) {
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }
});