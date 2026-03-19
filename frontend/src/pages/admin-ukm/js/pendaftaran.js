// Initialize DataTables
// Modifikasi tables definition di pendaftaran.js:
const tables = {
    tahap1: $('#table-tahap1').DataTable({
        ajax: {
            url: '/backend/controllers/admin-ukm/pendaftaran.php?action=get_tahap1',
            dataSrc: 'data',
            error: function (xhr, error, thrown) {
                if (error !== 'abort') { // Ignore abort errors
                    console.error('DataTables error:', error, thrown);
                    console.log('Server response:', xhr.responseText);
                    alert('Error loading data. Please check console for details.');
                }
            }
        },
        columns: [
            { data: 'nim' },
            { 
                data: null,
                render: function(data) {
                    const genderIcon = data.jenis_kelamin === 'Laki-laki' ? 
                        '<i class="fas fa-mars text-primary"></i>' : 
                        '<i class="fas fa-venus text-danger"></i>';
                    return `
                        <div>
                            ${genderIcon} ${data.nama_lengkap || ''}
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-graduation-cap"></i> ${data.nama_program_studi || '-'}
                                <br>
                                <i class="fas fa-map-marker-alt"></i> ${data.alamat || '-'}
                            </small>
                        </div>`;
                }
            },
            { 
                data: 'motivasi',
                defaultContent: '-'
            },
            { 
                data: 'status_tahap1',
                render: function(data) {
                    return getStatusBadge(data || 'pending');
                }
            },
            {
                data: null,
                render: function(data) {
                    return `<button class="btn btn-sm btn-primary review-btn" data-id="${data.id_pendaftaran}" data-tahap="1">Review</button>`;
                }
            }
        ],
        processing: true,
        serverSide: false,
        language: {
            emptyTable: "Tidak ada data pendaftar",
            processing: "Memuat data..."
        },
        drawCallback: function() {
            bindReviewButtons();
        }    
    }),
    tahap2: $('#table-tahap2').DataTable({
        ajax: {
            url: '/backend/controllers/admin-ukm/pendaftaran.php?action=get_tahap2',
            dataSrc: 'data',
            error: function (xhr, error, thrown) {
                if (error !== 'abort') { // Ignore abort errors
                    console.error('DataTables error:', error, thrown);
                    console.log('Server response:', xhr.responseText);
                    alert('Error loading data. Please check console for details.');
                }
            }
        },
        columns: [
            { data: 'nim' },
            { 
                data: null,
                render: function(data) {
                    const genderIcon = data.jenis_kelamin === 'Laki-laki' ? 
                        '<i class="fas fa-mars text-primary"></i>' : 
                        '<i class="fas fa-venus text-danger"></i>';
                    return `
                        <div>
                            ${genderIcon} ${data.nama_lengkap || ''}
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-graduation-cap"></i> ${data.nama_program_studi || '-'}
                                <br>
                                <i class="fas fa-map-marker-alt"></i> ${data.alamat || '-'}
                            </small>
                        </div>`;
                }
            },
            { 
                data: 'nama_divisi',
                defaultContent: '-'
            },
            {
                data: null,
                render: function(data) {
                    let html = '';
                    if (data.izin_ortu_path) {
                        html += `<a href="../../../public/uploads/dokumen_pendaftaran/${data.izin_ortu_path}" target="_blank" class="btn btn-xs btn-info mb-1">
                            <i class="fas fa-file-pdf"></i> Izin Ortu
                        </a><br>`;
                    }
                    if (data.sertifikat_warna_path) {
                        html += `<a href="../../../public/uploads/dokumen_pendaftaran/${data.sertifikat_warna_path}" target="_blank" class="btn btn-xs btn-info">
                            <i class="fas fa-file-pdf"></i> Sertifikat WaRna
                        </a>`;
                    }
                    return html || '-';
                }
            },
            { 
                data: 'status_tahap2',
                render: function(data) {
                    return getStatusBadge(data || 'pending');
                }
            },
            {
                data: null,
                render: function(data) {
                    return `<button class="btn btn-sm btn-primary review-btn" data-id="${data.id_pendaftaran}" data-tahap="2">Review</button>`;
                }
            }
        ],
        processing: true,
        serverSide: false,
        language: {
            emptyTable: "Tidak ada data pendaftar",
            processing: "Memuat data..."
        },
        responsive: true,
        autoWidth: true,
        drawCallback: function() {
            bindReviewButtons();
        }
        
    }),
    
    tahap3: $('#table-tahap3').DataTable({
        ajax: {
            url: '/backend/controllers/admin-ukm/pendaftaran.php?action=get_tahap3',
            dataSrc: 'data',
            error: function (xhr, error, thrown) {
                if (error !== 'abort') { // Ignore abort errors
                    console.error('DataTables error:', error, thrown);
                    console.log('Server response:', xhr.responseText);
                    alert('Error loading data. Please check console for details.');
                }
            }
        },
        columns: [
            { data: 'nim' },
            { 
                data: null,
                render: function(data) {
                    const genderIcon = data.jenis_kelamin === 'Laki-laki' ? 
                        '<i class="fas fa-mars text-primary"></i>' : 
                        '<i class="fas fa-venus text-danger"></i>';
                    return `
                        <div>
                            ${genderIcon} ${data.nama_lengkap || ''}
                            <br>
                            <small class="text-muted">
                                <i class="fas fa-graduation-cap"></i> ${data.nama_program_studi || '-'}
                                <br>
                                <i class="fas fa-map-marker-alt"></i> ${data.alamat || '-'}
                            </small>
                        </div>`;
                }
            },
            {
                data: 'cv_path',
                render: function(data) {
                    return data ? 
                        `<a href="../../../public/uploads/dokumen_pendaftaran/${data}" target="_blank" class="btn btn-xs btn-info">
                            <i class="fas fa-file-pdf"></i> Lihat CV
                        </a>` : '-';
                }
            },
            {
                data: 'motivation_letter_path',
                render: function(data) {
                    return data ? 
                        `<a href="../../../public/uploads/dokumen_pendaftaran/${data}" target="_blank" class="btn btn-xs btn-info">
                            <i class="fas fa-file-pdf"></i> Lihat Surat Motivasi
                        </a>` : '-';
                }
            },
            { 
                data: 'status_tahap3',
                render: function(data) {
                    return getStatusBadge(data || 'pending');
                }
            },
            {
                data: null,
                render: function(data) {
                    return `<button class="btn btn-sm btn-primary review-btn" data-id="${data.id_pendaftaran}" data-tahap="3">Review</button>`;
                }
            }
        ],
        processing: true,
        serverSide: false,
        language: {
            emptyTable: "Tidak ada data pendaftar",
            processing: "Memuat data..."
        },
        responsive: true,
        autoWidth: true,
        drawCallback: function() {
            bindReviewButtons();
        }    
    })
};

// Bind click handlers to review buttons
function bindReviewButtons() {
    $('.review-btn').off('click').on('click', function() {
        const id_pendaftaran = $(this).data('id');
        const tahap = $(this).data('tahap');
        const currentStatus = $(this).closest('tr').find('[data-status]').data('status');
        
        $('#review_id_pendaftaran').val(id_pendaftaran);
        $('#review_tahap').val(tahap);
        
        // Reset form
        $('#review_status').val('acc');
        $('#review_catatan').val('');
        
        $('#modal-review').modal('show');
    });
}

// Handle form submission

$('#form-review').on('submit', function(e) {
    e.preventDefault();
    
    const id_pendaftaran = $('#review_id_pendaftaran').val();
    const tahap = $('#review_tahap').val();
    const status = $('#review_status').val();
    const catatan = $('#review_catatan').val();

    // Debug log
    console.log('Submitting review:', {
        id_pendaftaran,
        tahap,
        status,
        catatan
    });

    const formData = {
        action: 'review',
        id_pendaftaran: id_pendaftaran,
        tahap: tahap,
        status: status,
        catatan: catatan
    };
    
    // Show loading state
    const submitButton = $(this).find('button[type="submit"]');
    const originalText = submitButton.text();
    submitButton.prop('disabled', true).text('Menyimpan...');
    
    $.ajax({
        url: '/backend/controllers/admin-ukm/pendaftaran.php',
        method: 'POST',
        data: formData,
        success: function(response) {
            console.log('Success response:', response); // Debug log
            if (response.status === 'success') {
                $('#modal-review').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Review berhasil disimpan',
                    didClose: () => {
                        tables[`tahap${tahap}`].ajax.reload();
                    }
                });
                $('#form-review')[0].reset();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: response.message || 'Terjadi kesalahan saat menyimpan review'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error details:', {
                status: xhr.status,
                responseText: xhr.responseText,
                error: error
            });
            
            let errorMessage = 'Terjadi kesalahan pada server';
            try {
                const response = JSON.parse(xhr.responseText);
                errorMessage = response.message || errorMessage;
            } catch(e) {}

            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: errorMessage
            });
        },
        complete: function() {
            submitButton.prop('disabled', false).text(originalText);
        }
    });
});

// Reset form when modal is closed
$('#modal-review').on('hidden.bs.modal', function() {
    $('#form-review')[0].reset();
});

function loadPeriodeAktif() {
    $.ajax({
        url: '/backend/controllers/admin-ukm/pendaftaran.php?action=get_periode',
        method: 'GET',
        success: function(response) {
            if (response.status === 'success' && response.data) {
                // Update form inputs
                $('input[name="tanggal_buka"]').val(response.data.tanggal_buka);
                $('input[name="batas_waktu_tahap1"]').val(response.data.batas_waktu_tahap1);
                $('input[name="batas_waktu_tahap2"]').val(response.data.batas_waktu_tahap2);
                $('input[name="batas_waktu_tahap3"]').val(response.data.batas_waktu_tahap3);

                // Show periode details
                $('#no-periode').hide();
                $('#periode-details').show();

                // Format dates
                const tahap1Start = new Date(response.data.tanggal_buka);
                const tahap1End = new Date(response.data.tahap1_end);
                const tahap2Start = new Date(response.data.tahap2_start);
                const tahap2End = new Date(response.data.tahap2_end);
                const tahap3Start = new Date(response.data.tahap3_start);
                const tahap3End = new Date(response.data.tahap3_end);

                // Update info boxes
                $('#tahap1-date').html(`
                    <strong>Periode:</strong><br>
                    ${formatDate(tahap1Start)} -<br>
                    ${formatDate(tahap1End)}
                `);
                $('#tahap1-duration').html(`
                    <strong>Durasi:</strong> ${response.data.batas_waktu_tahap1} hari
                `);

                $('#tahap2-date').html(`
                    <strong>Periode:</strong><br>
                    ${formatDate(tahap2Start)} -<br>
                    ${formatDate(tahap2End)}
                `);
                $('#tahap2-duration').html(`
                    <strong>Durasi:</strong> ${response.data.batas_waktu_tahap2} hari
                `);

                $('#tahap3-date').html(`
                    <strong>Periode:</strong><br>
                    ${formatDate(tahap3Start)} -<br>
                    ${formatDate(tahap3End)}
                `);
                $('#tahap3-duration').html(`
                    <strong>Durasi:</strong> ${response.data.batas_waktu_tahap3} hari
                `);

                // Update timeline
                updateTimeline(response.data);
            } else {
                $('#no-periode').show();
                $('#periode-details').hide();
            }
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON?.message || 'Terjadi kesalahan saat memuat data'
            });
        }
    });
}


$('#form-periode').on('submit', function(e) {
    e.preventDefault();

    // Basic frontend validation
    const tanggal_buka = new Date($('input[name="tanggal_buka"]').val());
    const now = new Date();

    if (tanggal_buka < now) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Tanggal buka harus lebih besar dari waktu sekarang'
        });
        return;
    }

    // Validate duration fields
    const duration_fields = [
        'batas_waktu_tahap1',
        'batas_waktu_tahap2',
        'batas_waktu_tahap3'
    ];

    for (const field of duration_fields) {
        const value = parseInt($(`input[name="${field}"]`).val());
        if (!value || value < 1) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: `${field} harus diisi dengan angka positif`
            });
            return;
        }
    }

    // Calculate and display preview dates
    const total_days = duration_fields.reduce((sum, field) => {
        return sum + parseInt($(`input[name="${field}"]`).val());
    }, 0);

    // Show confirmation with date previews
    const preview_dates = calculatePhaseDates(
        tanggal_buka,
        parseInt($('input[name="batas_waktu_tahap1"]').val()),
        parseInt($('input[name="batas_waktu_tahap2"]').val()),
        parseInt($('input[name="batas_waktu_tahap3"]').val())
    );

    Swal.fire({
        title: 'Konfirmasi Periode Pendaftaran',
        html: `
            <div class="text-left">
                <p><strong>Tahap 1:</strong> ${formatDate(preview_dates.tahap1_start)} - ${formatDate(preview_dates.tahap1_end)}</p>
                <p><strong>Tahap 2:</strong> ${formatDate(preview_dates.tahap2_start)} - ${formatDate(preview_dates.tahap2_end)}</p>
                <p><strong>Tahap 3:</strong> ${formatDate(preview_dates.tahap3_start)} - ${formatDate(preview_dates.tahap3_end)}</p>
                <p><strong>Total Durasi:</strong> ${total_days} hari</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Simpan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            submitPeriode(this);
        }
    });
});

function updateTimeline(periodeData) {
    const timelineHtml = `
        <!-- Start marker -->
        <div class="time-label">
            <span class="bg-blue">Mulai Pendaftaran</span>
        </div>

        <!-- Tahap 1 -->
        <div>
            <i class="fas fa-hourglass-start bg-blue"></i>
            <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> ${formatDate(new Date(periodeData.tanggal_buka))}</span>
                <h3 class="timeline-header"><b>Tahap 1 Dimulai</b></h3>
                <div class="timeline-body">
                    <p>Seleksi Administrasi</p>
                    <ul>
                        <li>Durasi: ${periodeData.batas_waktu_tahap1} hari</li>
                        <li>Berakhir: ${formatDate(new Date(periodeData.tahap1_end))}</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tahap 2 -->
        <div>
            <i class="fas fa-hourglass-half bg-yellow"></i>
            <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> ${formatDate(new Date(periodeData.tahap2_start))}</span>
                <h3 class="timeline-header"><b>Tahap 2 Dimulai</b></h3>
                <div class="timeline-body">
                    <p>Verifikasi Dokumen</p>
                    <ul>
                        <li>Durasi: ${periodeData.batas_waktu_tahap2} hari</li>
                        <li>Berakhir: ${formatDate(new Date(periodeData.tahap2_end))}</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tahap 3 -->
        <div>
            <i class="fas fa-hourglass-end bg-green"></i>
            <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> ${formatDate(new Date(periodeData.tahap3_start))}</span>
                <h3 class="timeline-header"><b>Tahap 3 Dimulai</b></h3>
                <div class="timeline-body">
                    <p>Wawancara & Seleksi Akhir</p>
                    <ul>
                        <li>Durasi: ${periodeData.batas_waktu_tahap3} hari</li>
                        <li>Berakhir: ${formatDate(new Date(periodeData.tahap3_end))}</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- End marker -->
        <div>
            <i class="fas fa-flag-checkered bg-red"></i>
            <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> ${formatDate(new Date(periodeData.tahap3_end))}</span>
                <h3 class="timeline-header"><b>Pendaftaran Selesai</b></h3>
                <div class="timeline-body">
                    <p>Pengumuman hasil seleksi akan diinformasikan melalui email atau kontak yang terdaftar</p>
                </div>
            </div>
        </div>
    `;

    $('#timeline-items').html(timelineHtml);
}

function calculatePhaseDates(start_date, duration1, duration2, duration3) {
    const tahap1_start = new Date(start_date);
    const tahap1_end = new Date(tahap1_start);
    tahap1_end.setDate(tahap1_end.getDate() + duration1);

    const tahap2_start = new Date(tahap1_end);
    const tahap2_end = new Date(tahap2_start);
    tahap2_end.setDate(tahap2_end.getDate() + duration2);

    const tahap3_start = new Date(tahap2_end);
    const tahap3_end = new Date(tahap3_start);
    tahap3_end.setDate(tahap3_end.getDate() + duration3);

    return {
        tahap1_start,
        tahap1_end,
        tahap2_start,
        tahap2_end,
        tahap3_start,
        tahap3_end
    };
}

function formatDate(date) {
    return date.toLocaleString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function submitPeriode(form) {
    const formData = new FormData(form);
    
    $.ajax({
        url: '/backend/controllers/admin-ukm/pendaftaran.php?action=update_periode', // Ubah ini
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message
                });
                loadPeriodeAktif();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: response.message
                });
            }
        },
        error: function(xhr) {
            console.error('Error response:', xhr.responseText); // Tambahkan ini untuk debugging
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan data'
            });
        }
    });
}

$(document).on('click', '.review-btn', function() {
    const id_pendaftaran = $(this).data('id');
    const tahap = $(this).data('tahap');
    
    $('#review_id_pendaftaran').val(id_pendaftaran);
    $('#review_tahap').val(tahap);
    $('#modal-review').modal('show');
});

$('#form-review').on('submit', function(e) {
    e.preventDefault();
    const formData = {
        action: 'review',
        id_pendaftaran: $('#review_id_pendaftaran').val(),
        tahap: $('#review_tahap').val(),
        status: $('#review_status').val(),
        catatan: $('#review_catatan').val()
    };
    
    $.ajax({
        url: '/backend/controllers/admin-ukm/pendaftaran.php',
        method: 'POST',
        data: formData,
        success: function(response) {
            if (response.status === 'success') {
                $('#modal-review').modal('hide');
                tables[`tahap${formData.tahap}`].ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Review berhasil disimpan'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: response.message
                });
            }
        }
    });
});

function getStatusBadge(status) {
    const badges = {
        'pending_tahap1': '<span class="badge badge-warning">Pending Tahap 1</span>',
        'acc_tahap1': '<span class="badge badge-success">Diterima Tahap 1</span>',
        'pending_tahap2': '<span class="badge badge-warning">Pending Tahap 2</span>',
        'acc_tahap2': '<span class="badge badge-success">Diterima Tahap 2</span>',
        'pending_tahap3': '<span class="badge badge-warning">Pending Tahap 3</span>',
        'acc_tahap3': '<span class="badge badge-success">Diterima Tahap 3</span>',
        'ditolak': '<span class="badge badge-danger">Ditolak</span>'
    };
    return badges[status] || status;
}

$('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
    const targetId = $(e.target).attr('href');
    const tahap = targetId.replace('#tahap', '');
    tables[`tahap${tahap}`].ajax.reload();
});

$(document).ready(function() {
    loadPeriodeAktif();
    window.SidebarManager?.init();
});

window.logout = function() {
    window.SidebarManager?.logout();
};