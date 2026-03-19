$(document).ready(function() {
    function loadTotalAnggota() {
        $.ajax({
            url: '/backend/controllers/admin-ukm/get-dashboard.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#total-anggota').text(data.totalAnggota);
            },
            error: function() {
                alert('Terjadi kesalahan saat mengambil data.');
            }
        });
    }

    function loadTotalKegiatan() {
        $.ajax({
            url: '/backend/controllers/admin-ukm/get-dashboard.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#total-kegiatan').text(data.totalKegiatan);
            },
            error: function() {
                alert('Terjadi kesalahan saat mengambil data.');
            }
        });
    }

    function loadTotalPendaftar() {
        $.ajax({
            url: '/backend/controllers/admin-ukm/get-dashboard.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#total-pendaftar').text(data.totalPendaftar);
            },
            error: function() {
                alert('Terjadi kesalahan saat mengambil data.');
            }
        });
    }
    

    function loadTotalRapat() {
        $.ajax({
            url: '/backend/controllers/admin-ukm/get-dashboard.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#total-rapat').text(data.totalRapat);
            },
            error: function() {
                alert('Terjadi kesalahan saat mengambil data.');
            }
        });
    }
    
    function loadUpcomingEvents() {
        $.ajax({
            url: '/backend/controllers/admin-ukm/get-dashboard.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.timelines.length > 0) {
                    let eventsHtml = '<div class="list-group">';
                    data.timelines.forEach(function(event) {
                        eventsHtml += `
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <div>
                                        <h5 class="mb-1">
                                            <i class="fas fa-calendar-day text-primary mr-2"></i>
                                            ${event.judul_kegiatan}
                                        </h5>
                                    </div>
                                    <small class="text-muted">${event.tanggal_kegiatan}</small>
                                </div>
                            </div>`;
                    });
                    eventsHtml += '</div>';
                    $('#upcoming-events').html(eventsHtml);
                } else {
                    $('#upcoming-events').html(`
                        <div class="text-center py-3">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada kegiatan mendatang.</p>
                        </div>`);
                }
            },
            error: function() {
                $('#upcoming-events').html(`
                    <div class="alert alert-danger">
                        Terjadi kesalahan saat mengambil data kegiatan mendatang.
                    </div>`);
            }
        });
    }
    
    function loadLatestMeetings() {
        $.ajax({
            url: '/backend/controllers/admin-ukm/get-dashboard.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.rapatDilaksanakan.length > 0) {
                    let meetingsHtml = '<div class="list-group">';
                    data.rapatDilaksanakan.forEach(function(meeting) {
                        meetingsHtml += `
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <div>
                                        <h5 class="mb-1">
                                            <i class="fas fa-users text-info mr-2"></i>
                                            ${meeting.judul}
                                        </h5>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            Rapat untuk kegiatan: ${meeting.timeline_judul}
                                        </small>
                                    </div>
                                    <small class="text-muted">${meeting.tanggal}</small>
                                </div>
                            </div>`;
                    });
                    meetingsHtml += '</div>';
                    $('#latest-meetings').html(meetingsHtml);
                } else {
                    $('#latest-meetings').html(`
                        <div class="text-center py-3">
                            <i class="fas fa-comments-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Tidak ada rapat yang sudah dilaksanakan.</p>
                        </div>`);
                }
            },
            error: function() {
                $('#latest-meetings').html(`
                    <div class="alert alert-danger">
                        Terjadi kesalahan saat mengambil data rapat.
                    </div>`);
            }
        });
    }

    // Load dashboard data on page load
    loadTotalAnggota();
    loadTotalKegiatan();
    loadTotalRapat();
    loadTotalPendaftar();
    loadUpcomingEvents();
    loadLatestMeetings();
});