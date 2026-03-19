// Constants
const API_URL = '/backend/controllers/admin-ukm/timeline.php';

// Utility Functions
const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
};

const formatTime = (timeString) => {
    if (!timeString) return '';
    const options = { hour: '2-digit', minute: '2-digit' };
    return new Date(`2000-01-01T${timeString}`).toLocaleTimeString('id-ID', options);
};

// Main Functions
async function fetchEvents() {
    try {
        // Modified to get latest events only
        const response = await fetch(`${API_URL}?order=latest&limit=4`);
        if (!response.ok) throw new Error('Network response was not ok');
        
        const result = await response.json();
        
        if (result.status === 'error') {
            throw new Error(result.message);
        }
        
        if (result.status === 'success' && Array.isArray(result.data)) {
            renderEvents(result.data);
        } else {
            throw new Error('Invalid data format received');
        }
    } catch (error) {
        console.error('Error fetching events:', error);
        const eventContainer = document.querySelector('.event-container');
        if (eventContainer) {
            eventContainer.innerHTML = `
                <h2>EVENT</h2>
                <p class="error-message">Maaf, terjadi kesalahan saat memuat event.</p>
            `;
        }
    }
}

function renderEvents(events) {
    const eventContainer = document.querySelector('.event-container');
    if (!eventContainer) return;

    eventContainer.innerHTML = '';

    if (events.length === 0) {
        const noEventMessage = document.createElement('p');
        noEventMessage.className = 'no-events-message';
        noEventMessage.textContent = 'Tidak ada event yang aktif saat ini.';
        eventContainer.appendChild(noEventMessage);
        return;
    }

    events.forEach((event, index) => {
        const isLeft = index % 2 === 0;
        const eventCard = createEventCard(event, isLeft);
        eventContainer.appendChild(eventCard);
    });

    setupCardAnimations();
}

function createEventCard(event, isLeft) {
    const div = document.createElement('div');
    div.className = `event-card ${isLeft ? 'left' : 'right'}`;

    const imagePath = event.image_path 
        ? `/frontend/public/assets/${event.image_path}`
        : '/frontend/public/assets/event.webp';

    const words = event.judul_kegiatan.split(' ');
    let titleHTML;
    
    if (words.length >= 2) {
        const firstWord = words[0];
        const restWords = words.slice(1).join(' ');
        titleHTML = `<span class="highlight">${firstWord}</span>${restWords}`;
    } else {
        const highlightText = event.judul_kegiatan.substring(0, 4);
        const remainingText = event.judul_kegiatan.substring(4);
        titleHTML = `<span class="highlight">${highlightText}</span>${remainingText}`;
    }

    const content = isLeft ? `
        <div class="event-info">
            <h3>${titleHTML}</h3>
            <p>${event.deskripsi}</p>
            <div class="period">
                <span class="period-label">PERIOD</span>
                <span class="period-date">${formatDate(event.tanggal_kegiatan)}</span>
            </div>
            <div class="tags">
                <span class="tag software">Event</span>
                <span class="tag multimedia">${formatTime(event.waktu_mulai)} - ${formatTime(event.waktu_selesai)}</span>
            </div>
        </div>
        <div class="event-image">
            <img src="${imagePath}" alt="${event.judul_kegiatan}" onerror="this.src='/frontend/public/assets/event.webp'">
        </div>
    ` : `
        <div class="event-image">
            <img src="${imagePath}" alt="${event.judul_kegiatan}" onerror="this.src='/frontend/public/assets/event.webp'">
        </div>
        <div class="event-info">
            <h3>${titleHTML}</h3>
            <p>${event.deskripsi}</p>
            <div class="period">
                <span class="period-label">PERIOD</span>
                <span class="period-date">${formatDate(event.tanggal_kegiatan)}</span>
            </div>
            <div class="tags">
                <span class="tag software">Event</span>
                <span class="tag multimedia">${formatTime(event.waktu_mulai)} - ${formatTime(event.waktu_selesai)}</span>
            </div>
        </div>
    `;

    div.innerHTML = content;
    return div;
}

// Animation Setup
document.addEventListener('DOMContentLoaded', () => {
    const titleObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('slide-in');
                titleObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });

    const eventTitle = document.querySelector('.event-title');
    if (eventTitle) {
        titleObserver.observe(eventTitle);
    }

    fetchEvents();
});

function setupCardAnimations() {
    const cardObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
                cardObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1
    });

    document.querySelectorAll('.event-card').forEach(card => {
        cardObserver.observe(card);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector(".navbar");
    const hamburger = document.querySelector(".hamburger");
    const navMenu = document.querySelector(".nav-menu");

    window.addEventListener("scroll", () => {
        if (window.scrollY > 50) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }
    });

    hamburger.addEventListener("click", () => {
        hamburger.classList.toggle("active");
        navMenu.classList.toggle("active");
    });

    document.querySelectorAll(".nav-link").forEach(n => n.addEventListener("click", () => {
        hamburger.classList.remove("active");
        navMenu.classList.remove("active");
    }));
});