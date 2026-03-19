$(document).ready(function () {
    loadAnggota();
    loadPeriodeDropdown();

    // Function untuk load data anggota
    function loadAnggota() {
        $.ajax({
            url: `/backend/controllers/admin-ukm/keanggotaan.php`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    populateTable(response.data);
                } else {
                    Swal.fire('Error!', response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    }

    // Populate table dengan data
    function populateTable(data) {
        const tbody = $('#table-anggota tbody');
        tbody.empty();
        $.each(data, function(index, item) {
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.nim || 'N/A'}</td>
                    <td>${item.nama_lengkap || 'N/A'}</td>
                    <td>${item.nama_program_studi || 'N/A'}</td>
                    <td>
                        <span class="badge badge-${item.status === 'pengurus' ? 'success' : 'info'}">
                            ${item.status}
                        </span>
                    </td>
                    <td>${item.periode || 'N/A'}</td>
                    <td>
                        <button class="btn btn-primary btn-sm edit-btn" data-id="${item.id_keanggotaan}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${item.id_keanggotaan}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Load periode untuk dropdown
    function loadPeriodeDropdown() {
        $.ajax({
            url: '/backend/controllers/admin-ukm/keanggotaan.php?action=get_periode',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const select = $('#filter-periode, #id_periode');
                    select.empty();
                    select.append('<option value="">Pilih Periode</option>');
                    response.data.forEach(function(item) {
                        select.append(`<option value="${item.id_periode}">${item.tahun_mulai} - ${item.tahun_selesai}</option>`);
                    });
                }
            }
        });
    }

    // Load mahasiswa untuk dropdown (yang sudah acc_tahap3)
    function loadMahasiswaDropdown() {
        $.ajax({
            url: '/backend/controllers/admin-ukm/keanggotaan.php?action=get_mahasiswa',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const select = $('#nim');
                    select.empty();
                    select.append('<option value="">Pilih Mahasiswa</option>');
                    response.data.forEach(function(item) {
                        select.append(`<option value="${item.nim}">${item.nim} - ${item.nama_lengkap}</option>`);
                    });
                }
            }
        });
    }

    // Handle filter status
    $('#filter-status').on('change', function() {
        const status = $(this).val();
        const periode = $('#filter-periode').val();
        const search = $('#search-box').val();
        filterData(status, periode, search);
    });

    // Handle filter periode
    $('#filter-periode').on('change', function() {
        const status = $('#filter-status').val();
        const periode = $(this).val();
        const search = $('#search-box').val();
        filterData(status, periode, search);
    });

    // Handle search
    $('#search-box').on('keyup', function() {
        const status = $('#filter-status').val();
        const periode = $('#filter-periode').val();
        const search = $(this).val();
        filterData(status, periode, search);
    });

    // Function untuk filter data
    function filterData(status, periode, search) {
        $.ajax({
            url: '/backend/controllers/admin-ukm/keanggotaan.php',
            method: 'GET',
            data: {
                status: status,
                periode: periode,
                search: search
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    populateTable(response.data);
                }
            }
        });
    }

    // Add button click
    $('#add-btn').on('click', function() {
        resetForm();
        loadMahasiswaDropdown();
        $('#modal-title').text('Tambah Anggota');
    });

    // Edit button click
    $(document).on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.ajax({
            url: `/backend/controllers/admin-ukm/keanggotaan.php?id_keanggotaan=${id}`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    const data = response.data;
                    $('#id_keanggotaan').val(data.id_keanggotaan);
                    $('#status').val(data.status);
                    $('#id_periode').val(data.id_periode);

                    // Set NIM dengan data yang sedang diedit
                    const select = $('#nim');
                    select.empty();
                    select.append(`<option value="${data.nim}">${data.nim} - ${data.nama_lengkap}</option>`);
                    select.prop('disabled', true);

                    $('#modal-title').text('Edit Anggota');
                    $('#modal-form').modal('show');
                }
            }
        });
    });

    // Form submit
    $('#form-keanggotaan').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('id_ukm', id_ukm);

        $.ajax({
            url: '/backend/controllers/admin-ukm/keanggotaan.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                let result = response;
                if (typeof response === 'string') {
                    try {
                        result = JSON.parse(response);
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                    }
                }

                if (result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: result.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#modal-form').modal('hide');
                        resetForm();
                        loadAnggota();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: result.message || 'Terjadi kesalahan'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Full error response:', xhr.responseText);
                
                let errorMessage = 'Gagal menyimpan data';
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.message) {
                        errorMessage = errorResponse.message;
                    }
                } catch (e) {
                    console.error('Error parsing error response:', e);
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: errorMessage
                });
            }
        });
    });

    // Delete button click
    $(document).on('click', '.delete-btn', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data anggota akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/backend/controllers/admin-ukm/keanggotaan.php?id_keanggotaan=${id}`,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                loadAnggota();
                            });
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    }
                });
            }
        });
    });

    // Reset form
    function resetForm() {
        $('#form-keanggotaan')[0].reset();
        $('#id_keanggotaan').val('');
        $('#nim').prop('disabled', false);
    }
});