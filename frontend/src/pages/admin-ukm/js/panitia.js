$(document).ready(function () {
    // Manage Panitia button click
    $(document).on('click', '.manage-panitia', function() {
        const id_timeline = $(this).data('id');
        $('#id_timeline_panitia').val(id_timeline);
        loadPanitia(id_timeline);
        loadMahasiswaDropdown();
        loadJabatanPanitiaDropdown();
        $('#modal-panitia').modal('show');
    });

    // Function untuk load data panitia
    function loadPanitia(id_timeline) {
        $.ajax({
            url: `/backend/controllers/admin-ukm/panitia.php?id_timeline=${id_timeline}`,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                console.log('Data panitia:', data);
                const tbody = $('#table-panitia tbody');
                tbody.empty();
                
                if (data && data.length > 0) {
                    data.forEach((item, index) => {
                        tbody.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.nim}</td>
                                <td>${item.nama_lengkap}</td>
                                <td>${item.nama_jabatan}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm delete-panitia" data-id="${item.id_panitia}">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        `);
                    });
                } else {
                    tbody.append(`
                        <tr>
                            <td colspan="5" class="text-center">Belum ada panitia</td>
                        </tr>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
            }
        });
    }

    // Function untuk load dropdown mahasiswa
    function loadMahasiswaDropdown() {
        $.ajax({
            url: `/backend/controllers/admin-ukm/panitia.php?action=get_mahasiswa&id_ukm=${id_ukm}`,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const select = $('#nim_panitia');
                select.empty();
                select.append('<option value="">Pilih Mahasiswa</option>');
                data.forEach(function(item) {
                    select.append(`<option value="${item.nim}">${item.nim} - ${item.nama_lengkap}</option>`);
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
            }
        });
    }

    // Function untuk load dropdown jabatan panitia
    function loadJabatanPanitiaDropdown() {
        $.ajax({
            url: '/backend/controllers/admin-ukm/panitia.php?action=get_jabatan',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const select = $('#jabatan_panitia');
                select.empty();
                select.append('<option value="">Pilih Jabatan</option>');
                data.forEach(function(item) {
                    select.append(`<option value="${item.id_jabatan_panitia}">${item.nama_jabatan}</option>`);
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
            }
        });
    }

    // Form submit untuk tambah panitia
    $('#form-panitia').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: '/backend/controllers/admin-ukm/panitia.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Panitia berhasil ditambahkan',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    loadPanitia($('#id_timeline_panitia').val());
                    loadTimeline(); // Refresh timeline untuk update jumlah panitia
                    $('#form-panitia')[0].reset();
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.responseText);
                Swal.fire('Error!', 'Gagal menambahkan panitia', 'error');
            }
        });
    });

    // Delete panitia button click
    $(document).on('click', '.delete-panitia', function() {
        const id_panitia = $(this).data('id');
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Panitia akan dihapus dari kegiatan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/backend/controllers/admin-ukm/panitia.php?id_panitia=${id_panitia}`,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: 'Panitia berhasil dihapus',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            loadPanitia($('#id_timeline_panitia').val());
                            loadTimeline(); // Refresh timeline untuk update jumlah panitia
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', xhr.responseText);
                        Swal.fire('Error!', 'Gagal menghapus panitia', 'error');
                    }
                });
            }
        });
    });
});