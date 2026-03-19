document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const ukmId = urlParams.get('id');
    console.log('DOM fully loaded and parsed.');
    const timelineList = document.getElementById('timeline-list');
    const paginationControls = document.getElementById('pagination-controls');
    console.log('timelineList:', timelineList);
    console.log('paginationControls:', paginationControls);
    
    const API_URL = '/backend/controllers/mahasiswa/ukm_detail_registered.php';
    const ASSETS_URL = '/frontend/public/assets';
    
    let currentJenis = 'proker';
    let currentPage = 1;
    const itemsPerPage = 4;
    let allEvents = [];
    
    if (!ukmId) {
        showError('UKM ID tidak valid');
        return;
    }

    loadBanner();
    loadTimeline(currentJenis);

    // Definisikan sebagai fungsi global di awal file
    window.showFullImage = function(src) {
        const modal = document.createElement('div');
        modal.className = 'modal-image';
        modal.innerHTML = `
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <img src="${src}" alt="Full size image">
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close modal when clicking outside or on close button
        modal.addEventListener('click', function(e) {
            if (e.target === modal || e.target.className === 'close-modal') {
                document.body.removeChild(modal);
            }
        });

        // Close modal with escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.querySelector('.modal-image')) {
                document.body.removeChild(modal);
            }
        });
    };

    
    document.querySelectorAll('.menu-item').forEach(item => {
        item.addEventListener('click', function () {
            document.querySelector('.menu-item.active')?.classList.remove('active');
            this.classList.add('active');
            currentJenis = this.getAttribute('data-jenis');
            currentPage = 1; // Reset to the first page
            loadTimeline(currentJenis);
        });
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('tab-btn')) {
            const tabId = e.target.getAttribute('data-tab');
            const tabContainer = e.target.closest('.rapat-content');
            
            // Update active tab button
            tabContainer.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            e.target.classList.add('active');
            
            // Update active content
            tabContainer.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');
        }
    });

    document.addEventListener('click', function (e) {
        const timelineHeader = e.target.closest('.timeline-header');
        const dropdownHeader = e.target.closest('.dropdown-header');
        const rapatItemHeader = e.target.closest('.rapat-item-header');
        
        if (timelineHeader && !dropdownHeader && !rapatItemHeader) {
            const timelineItem = timelineHeader.closest('.timeline-item');
            const content = timelineItem.querySelector('.timeline-content');
            const icon = timelineHeader.querySelector('.dropdown-icon');
            
            if (content.style.display === 'none' || !content.style.display) {
                content.style.display = 'block';
                if (icon) icon.textContent = '▲';
            } else {
                content.style.display = 'none';
                if (icon) icon.textContent = '▼';
            }
        }
        
        if (dropdownHeader && !rapatItemHeader) {
            const dropdownList = dropdownHeader.nextElementSibling;
            const icon = dropdownHeader.querySelector('.dropdown-icon');
            
            if (dropdownList.style.display === 'none' || !dropdownList.style.display) {
                dropdownList.style.display = 'block';
                if (icon) icon.textContent = '▲';
            } else {
                dropdownList.style.display = 'none';
                if (icon) icon.textContent = '▼';
            }
            e.stopPropagation();
        }
        
        if (rapatItemHeader) {
            const content = rapatItemHeader.nextElementSibling;
            const icon = rapatItemHeader.querySelector('.dropdown-icon');
            
            if (content.style.display === 'none' || !content.style.display) {
                content.style.display = 'block';
                if (icon) icon.textContent = '▲';
            } else {
                content.style.display = 'none';
                if (icon) icon.textContent = '▼';
            }
            e.stopPropagation();
        }
    });

    async function loadBanner() {
        try {
            const formData = new FormData();
            formData.append('action', 'getBanner');
            formData.append('ukm_id', ukmId);

            const response = await fetch(API_URL, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.status === 'success') {
                const bannerImg = document.getElementById('ukm-banner');
                bannerImg.src = `${ASSETS_URL}/${result.data.banner_path}`;
                bannerImg.onerror = () => {
                    bannerImg.src = `${ASSETS_URL}/default-banner.jpg`;
                };
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error loading banner:', error);
            document.getElementById('ukm-banner').src = `${ASSETS_URL}/default-banner.jpg`;
        }
    }

    
    async function loadTimeline(jenis) {
        const loading = document.getElementById('loading');
        const timelineList = document.getElementById('timeline-list');

        if (!loading || !timelineList) {
            console.error('Required DOM elements are missing.');
            return;
        }

        try {
            loading.style.display = 'flex';
            timelineList.innerHTML = '';

            const formData = new FormData();
            formData.append('action', 'getTimeline');
            formData.append('ukm_id', ukmId);
            formData.append('jenis', jenis);

            const response = await fetch(API_URL, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.status === 'success') {
                allEvents = result.data;
                displayPage(currentPage);
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error loading timeline:', error);
            showError(error.message);
        } finally {
            loading.style.display = 'none';
        }
    }

    function displayPage(page) {
        const timelineList = document.getElementById('timeline-list');
        const paginationControls = document.getElementById('pagination-controls');
    
        if (!timelineList || !paginationControls) {
            console.error('Required DOM elements are missing:');
            if (!timelineList) console.error('#timeline-list is missing.');
            if (!paginationControls) console.error('#pagination-controls is missing.');
            return;
        }
    
        console.log('Timeline list and pagination controls found.');
        
        const totalPages = Math.ceil(allEvents.length / itemsPerPage);
        timelineList.innerHTML = '';
    
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const eventsToDisplay = allEvents.slice(start, end);
    
        if (eventsToDisplay.length === 0) {
            showEmptyState(currentJenis);
        } else {
            eventsToDisplay.forEach(event => {
                const timelineElement = createTimelineElement(event);
                timelineList.appendChild(timelineElement);
            });
        }
    
        paginationControls.innerHTML = `
            <button class="prev-btn" ${page === 1 ? 'disabled' : ''}>Prev</button>
            <span>Page ${page} of ${totalPages}</span>
            <button class="next-btn" ${page === totalPages ? 'disabled' : ''}>Next</button>
        `;
    
        paginationControls.querySelector('.prev-btn').addEventListener('click', () => {
            if (page > 1) {
                currentPage--;
                displayPage(currentPage);
            }
        });
    
        paginationControls.querySelector('.next-btn').addEventListener('click', () => {
            if (page < totalPages) {
                currentPage++;
                displayPage(currentPage);
            }
        });
    }
    

    function showEmptyState(jenis) {
        const timelineList = document.getElementById('timeline-list');
        timelineList.innerHTML = `
            <div class="empty-state">
                Tidak ada ${jenis === 'proker' ? 'program kerja' : 'agenda'} yang tersedia
            </div>
        `;
    }

    function showError(message) {
        const timelineList = document.getElementById('timeline-list');
        timelineList.innerHTML = `
            <div class="error-message">
                ${message || 'Terjadi kesalahan saat memuat data'}
            </div>
        `;
    }

    function createTimelineElement(timeline) {
        const element = document.createElement('div');
        element.className = 'timeline-item';
    
        element.innerHTML = `
            <div class="timeline-header">
                <div class="timeline-header-content">
                    <h3 class="timeline-title">${timeline.judul_kegiatan || ''}</h3>
                    <div class="timeline-meta">
                        <span class="timeline-date">${formatDate(timeline.tanggal_kegiatan)}</span>
                        ${timeline.waktu_mulai ? `
                            <span class="timeline-time">
                                ${timeline.waktu_mulai} - ${timeline.waktu_selesai} WIB
                            </span>
                        ` : ''}
                    </div>
                    <span class="dropdown-icon">▼</span>
                </div>
            </div>
            <div class="timeline-content" style="display: none;">
                ${timeline.deskripsi ? `
                    <div class="timeline-description">
                        ${timeline.deskripsi}
                    </div>
                ` : ''}
                
                ${timeline.panitia && timeline.panitia.length > 0 ? `
                    <div class="panitia-section">
                        <h4 class="section-title dropdown-header" style="cursor: pointer;">
                            Panitia Program Kerja
                            <span class="dropdown-icon">▼</span>
                        </h4>
                        <div class="panitia-list" style="display: none;">
                            ${createPanitiaContent(timeline.panitia)}
                        </div>
                    </div>
                ` : ''}
                
                ${timeline.rapat && timeline.rapat.length > 0 ? `
                    <div class="rapat-section">
                        <h4 class="section-title dropdown-header" style="cursor: pointer;">
                            Rapat
                            <span class="dropdown-icon">▼</span>
                        </h4>
                        <div class="rapat-list" style="display: none;">
                            ${createRapatContent(timeline.rapat)}
                        </div>
                    </div>
                ` : ''}
            </div>
        `;
    
        return element;
    }

    
    function createDokumentasiSection(dokumentasiData) {
        if (!dokumentasiData || !dokumentasiData.length) return '';
        
        return `
            <div class="dokumentasi-section">
                <h4 class="section-title dropdown-header" style="cursor: pointer;">
                    Dokumentasi
                    <span class="dropdown-icon">▼</span>
                </h4>
                <div class="dokumentasi-list" style="display: none;">
                    <div class="dokumentasi-grid">
                        ${dokumentasiData.map(foto => `
                            <img src="${ASSETS_URL}/dokumentasi/${foto.foto_path}" 
                                 alt="Dokumentasi"
                                 class="dokumentasi-img"
                                 onclick="showFullImage('${ASSETS_URL}/dokumentasi/${foto.foto_path}')">
                        `).join('')}
                    </div>
                </div>
            </div>
        `;
    }

    
    function createPanitiaContent(panitiaData) {
        const panitiaByLevel = panitiaData.reduce((acc, p) => {
            if (!acc[p.level]) acc[p.level] = [];
            acc[p.level].push(p);
            return acc;
        }, {});
    
        return Object.entries(panitiaByLevel)
            .sort(([a], [b]) => parseInt(a) - parseInt(b))
            .map(([level, members]) => `
                <div class="panitia-level">
                    <div class="jabatan">${members[0].jabatan}</div>
                    ${members.map(m => `
                        <div class="nama-anggota">${m.nama}</div>
                    `).join('')}
                </div>
            `).join('');
    }
    
    function createRapatContent(rapatData) {
        return rapatData.map(rapat => `
            <div class="rapat-item">
                <div class="rapat-item-header">
                    <div class="rapat-header">
                        <h4>${rapat.judul}</h4>
                        <span class="rapat-date">${formatDate(rapat.tanggal)}</span>
                    </div>
                    <span class="dropdown-icon">▼</span>
                </div>
                <div class="rapat-content" style="display: none;">
                    <div class="rapat-dokumen-tabs">
                        <button class="tab-btn active" data-tab="notulensi-${rapat.id_rapat}">Notulensi</button>
                        <button class="tab-btn" data-tab="dokumentasi-${rapat.id_rapat}">Dokumentasi</button>
                    </div>
                    
                    <div class="tab-content active" id="notulensi-${rapat.id_rapat}">
                        ${rapat.notulensi_path ? `
                            <iframe src="/frontend/public/assets/notulensi/${rapat.notulensi_path}"
                                    class="notulensi-iframe"></iframe>
                        ` : '<p>Tidak ada notulensi.</p>'}
                    </div>
                    
                    <div class="tab-content" id="dokumentasi-${rapat.id_rapat}">
                        ${rapat.dokumentasi && rapat.dokumentasi.length ? `
                            <div class="dokumentasi-grid">
                                ${rapat.dokumentasi.map(foto => `
                                    <img src="/frontend/public/assets/dokumentasi/${foto.foto_path}"
                                         alt="Dokumentasi"
                                         class="dokumentasi-img"
                                         onclick="showFullImage('/frontend/public/assets/dokumentasi/${foto.foto_path}')">
                                `).join('')}
                            </div>
                        ` : '<p>Tidak ada dokumentasi.</p>'}
                    </div>
                </div>
            </div>
        `).join('');
    }

    // Tambahkan di bagian awal file JS
    function showFullImage(src) {
        const modal = document.createElement('div');
        modal.className = 'modal-image';
        modal.innerHTML = `
            <div class="modal-content">
                <span class="close-modal">&times;</span>
                <img src="${src}" alt="Full size image">
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Close modal when clicking outside the image
        modal.addEventListener('click', function(e) {
            if (e.target === modal || e.target.className === 'close-modal') {
                document.body.removeChild(modal);
            }
        });

        // Close modal with escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.querySelector('.modal-image')) {
                document.body.removeChild(modal);
            }
        });
    }

    function formatDate(dateString) {
        if (!dateString) return '';
        try {
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        } catch (error) {
            console.error('Error formatting date:', error);
            return dateString;
        }
    }
});