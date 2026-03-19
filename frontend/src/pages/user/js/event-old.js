document.addEventListener('DOMContentLoaded', async function() {
    // Fungsi untuk mengambil data dari API
    // Modified function to fetch timeline data
    async function fetchTimelineData() {
        try {
            console.log('Starting fetch request...');
            
            // First, get the latest 4 timeline IDs to exclude
            const latestResponse = await fetch(`${API_URL}?limit=4&latest=true`);
            const latestData = await latestResponse.json();
            
            const latestIds = latestData.data.map(item => item.id_timeline);
            
            // Fetch remaining timelines excluding the latest 4
            const params = new URLSearchParams({
                limit: '99',
                exclude: latestIds.join(',')
            });
            
            const url = `/backend/controllers/admin-ukm/timeline.php?${params.toString()}`;
            console.log('Fetching from URL:', url);
            
            const response = await fetch(url);
            console.log('Response status:', response.status);
            
            const text = await response.text();
            console.log('Raw response:', text);
            
            try {
                const data = JSON.parse(text);
                console.log('Parsed data:', data);
                
                if (data.status === 'success') {
                    return data.data;
                } else {
                    console.error('Server returned error:', data.message);
                    return [];
                }
            } catch (parseError) {
                console.error('JSON parse error:', parseError);
                console.error('Response that failed to parse:', text);
                return [];
            }
        } catch (error) {
            console.error('Network/fetch error:', error);
            return [];
        }
    }

    // Test function
    async function testFetch() {
        console.log('Running fetch test...');
        const data = await fetchTimelineData();
        console.log('Final data:', data);
    }

    // Run test
    testFetch();


    // Tambahkan style untuk carousel
    const styleSheet = document.createElement("style");
    styleSheet.textContent = `
        .carousel-container {
            position: relative;
            width: 95%;
            max-width: 1200px;
            height: 400px;
            margin: 0 auto;
            perspective: 2000px;
            transform-style: preserve-3d;
        }
    
        .carousel-track {
            position: relative;
            height: 100%;
            transform-style: preserve-3d;
        }
    
        .carousel-slide {
            position: absolute;
            width: 35%;
            height: 75%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.3s ease-out;
            opacity: 0;
            visibility: hidden;
            border-radius: 40px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
    
        .carousel-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 40px;
            transition: all 0.3s ease;
        }
    
        .carousel-slide .slide-description {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 35px 30px;
            background: linear-gradient(to top, rgba(0, 32, 96, 0.95), rgba(0, 32, 96, 0.7) 70%, rgba(0, 32, 96, 0));
            color: white;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            transform: translateZ(0);
            overflow-y: auto;
            max-height: 45%;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    
        .carousel-slide .slide-description h3 {
            margin: 0 auto 12px;
            font-size: 1.1em;
            line-height: 1.3;
            font-weight: 600;
            text-align: center; /* Added this line to center the title */
            width: 100%; /* Added to ensure full width */
        }
    
        .carousel-slide .description-text {
            font-size: 0.9em;
            line-height: 1.4;
            margin: 0;
            overflow-y: auto;
            max-height: calc(100% - 45px);
            padding-right: 10px;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
            text-align: left;
        }
    
        /* Custom scrollbar for webkit browsers */
        .carousel-slide .description-text::-webkit-scrollbar {
            width: 4px;
        }
    
        .carousel-slide .description-text::-webkit-scrollbar-track {
            background: transparent;
        }
    
        .carousel-slide .description-text::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
        }
    
        .carousel-slide .description-text::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
    
        /* Active slide */
        .carousel-slide.active {
            transform: translate(-50%, -50%) scale(1.3);
            opacity: 1;
            visibility: visible;
            z-index: 2;
            height: 75%;
        }
    
        .carousel-slide.active img {
            opacity: 1 !important;
        }
    
        /* Preview slides */
        .carousel-slide.prev,
        .carousel-slide.next {
            visibility: visible;
            z-index: 1;
        }
    
        /* Previous slide */
        .carousel-slide.prev {
            transform: translate(-120%, -50%);
            opacity: 0.7;
        }
    
        .carousel-slide.prev img {
            opacity: 0.7;
        }
    
        /* Next slide */
        .carousel-slide.next {
            transform: translate(20%, -50%);
            opacity: 0.7;
        }
    
        .carousel-slide.next img {
            opacity: 0.7;
        }
    
        /* Transition states */
        .carousel-slide.transition-right {
            transition: all 0.3s ease-in;
        }
    
        .carousel-slide.transition-left {
            transition: all 0.3s ease-in;
        }
    
        /* Hidden description for non-active slides */
        .carousel-slide .description-text {
            display: none;
            margin-top: 15px;
            font-size: 0.9em;
            line-height: 1.5;
            max-height: 100px;
            overflow-y: auto;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
    
        /* Show description for active slide */
        .carousel-slide.active .description-text {
            display: block;
            opacity: 1;
        }
    
        /* Previous slide styles */
        .carousel-slide.prev {
            transform: translate(-150%, -50%) translateZ(-100px) rotateY(25deg);
            visibility: visible;
            opacity: 0.7;
            z-index: 1;
        }
    
        /* Next slide styles */
        .carousel-slide.next {
            transform: translate(50%, -50%) translateZ(-100px) rotateY(-25deg);
            visibility: visible;
            opacity: 0.7;
            z-index: 1;
        }
    
        /* Hidden slides */
        .carousel-slide.instant-hide-left {
            transform: translate(-200%, -50%) translateZ(-200px);
            visibility: hidden;
            opacity: 0;
        }
    
        .carousel-slide.instant-hide-right {
            transform: translate(200%, -50%) translateZ(-200px);
            visibility: hidden;
            opacity: 0;
        }
    
        /* Animation classes */
        .carousel-slide.moving-left {
            animation: slideLeft 0.5s cubic-bezier(0.4, 0.0, 0.2, 1) forwards;
        }
    
        .carousel-slide.moving-right {
            animation: slideRight 0.5s cubic-bezier(0.4, 0.0, 0.2, 1) forwards;
        }
    
        @keyframes slideLeft {
            0% {
                transform: translate(-50%, -50%) translateZ(200px) scale(1.2);
                opacity: 1;
            }
            100% {
                transform: translate(-150%, -50%) translateZ(-100px) rotateY(25deg);
                opacity: 0.7;
            }
        }
    
        @keyframes slideRight {
            0% {
                transform: translate(50%, -50%) translateZ(-100px) rotateY(-25deg);
                opacity: 0.7;
            }
            100% {
                transform: translate(-50%, -50%) translateZ(200px) scale(1.2);
                opacity: 1;
            }
        }
    
        /* Navigation buttons */
        .carousel-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(10, 38, 71, 0.7);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            transition: all 0.3s ease;
        }
    
        .carousel-button:hover {
            background: rgba(10, 38, 71, 0.9);
            transform: translateY(-50%) scale(1.1);
        }
    
        .carousel-button.prev {
            left: 20%;
        }
    
        .carousel-button.next {
            right: 20%;
        }
    
        /* Responsive styles */
        @media (max-width: 768px) {
            .carousel-container {
                height: 300px;
            }
    
            .carousel-slide {
                width: 70%;
                height: 60%;
            }
    
            .carousel-slide.active {
                transform: translate(-50%, -50%) translateZ(100px) scale(1.1);
            }
    
            .carousel-slide.prev {
                transform: translate(-130%, -50%) translateZ(-50px) rotateY(20deg);
            }
    
            .carousel-slide.next {
                transform: translate(30%, -50%) translateZ(-50px) rotateY(-20deg);
            }
        }
    
        @media (max-width: 480px) {
            .carousel-container {
                height: 250px;
            }
    
            .carousel-slide {
                width: 80%;
                height: 50%;
            }
    
            .carousel-slide.active {
                transform: translate(-50%, -50%) translateZ(50px) scale(1.05);
            }
    
            .carousel-slide.prev {
                transform: translate(-120%, -50%) translateZ(-25px) rotateY(15deg);
            }
    
            .carousel-slide.next {
                transform: translate(20%, -50%) translateZ(-25px) rotateY(-15deg);
            }
        }
    `;
    document.head.appendChild(styleSheet);

// Carousel class implementation
class Carousel {
    constructor(container) {
        this.container = container;
        this.track = container.querySelector('.carousel-track');
        this.slides = Array.from(this.track.children);
        this.nextButton = container.querySelector('.carousel-button.next');
        this.prevButton = container.querySelector('.carousel-button.prev');
        
        this.currentIndex = 0;
        this.isAnimating = false;
        this.autoplayInterval = null;
        
        this.init();
    }

    init() {
        this.updateSlides();
        this.setupEventListeners();
        this.startAutoplay();
    }

    updateSlides() {
        this.slides.forEach((slide, index) => {
            // Remove all classes first
            slide.className = 'carousel-slide';
            slide.style.removeProperty('visibility');
            slide.style.removeProperty('opacity');

            const img = slide.querySelector('img');
            if (img) {
                img.style.removeProperty('opacity');
            }
            
            if (index === this.currentIndex) {
                slide.classList.add('active');
                if (img) {
                    img.style.opacity = '1';
                }
            } else if (index === this.getPrevIndex()) {
                slide.classList.add('prev');
                slide.style.visibility = 'visible';
                if (img) {
                    img.style.opacity = '0.7';
                }
            } else if (index === this.getNextIndex()) {
                slide.classList.add('next');
                slide.style.visibility = 'visible';
                if (img) {
                    img.style.opacity = '0.7';
                }
            } else {
                slide.style.visibility = 'hidden';
            }
        });
    }

    getPrevIndex() {
        return (this.currentIndex - 1 + this.slides.length) % this.slides.length;
    }

    getNextIndex() {
        return (this.currentIndex + 1) % this.slides.length;
    }

    async moveSlide(direction) {
        if (this.isAnimating || this.slides.length < 2) return;
        this.isAnimating = true;

        const currentSlide = this.slides[this.currentIndex];
        const nextIndex = direction === 'next' ? this.getNextIndex() : this.getPrevIndex();
        const nextSlide = this.slides[nextIndex];
        
        // Ensure current and next slides are visible
        [currentSlide, nextSlide].forEach(slide => {
            slide.style.visibility = 'visible';
            const img = slide.querySelector('img');
            if (img) {
                img.style.transition = 'opacity 0.3s ease';
            }
        });

        // Pre-position the new adjacent slides
        if (direction === 'next') {
            const nextNextIndex = (nextIndex + 1) % this.slides.length;
            const nextNextSlide = this.slides[nextNextIndex];
            nextNextSlide.className = 'carousel-slide next';
            nextNextSlide.style.visibility = 'visible';
            const img = nextNextSlide.querySelector('img');
            if (img) {
                img.style.opacity = '0.7';
            }
        } else {
            const prevPrevIndex = (nextIndex - 1 + this.slides.length) % this.slides.length;
            const prevPrevSlide = this.slides[prevPrevIndex];
            prevPrevSlide.className = 'carousel-slide prev';
            prevPrevSlide.style.visibility = 'visible';
            const img = prevPrevSlide.querySelector('img');
            if (img) {
                img.style.opacity = '0.7';
            }
        }

        // Update opacity during transition
        const currentImg = currentSlide.querySelector('img');
        const nextImg = nextSlide.querySelector('img');
        
        if (currentImg) {
            currentImg.style.opacity = '0.7';
        }
        if (nextImg) {
            nextImg.style.opacity = '1';
        }

        // Add transition classes
        currentSlide.classList.add(direction === 'next' ? 'transition-left' : 'transition-right');
        nextSlide.classList.add(direction === 'next' ? 'transition-right' : 'transition-left');

        // Update current index
        this.currentIndex = nextIndex;

        // Update positions
        await new Promise(resolve => setTimeout(resolve, 50));
        this.updateSlides();

        // Reset animation flag after transition
        setTimeout(() => {
            this.isAnimating = false;
            this.slides.forEach(slide => {
                slide.classList.remove('transition-left', 'transition-right');
                const img = slide.querySelector('img');
                if (img) {
                    img.style.removeProperty('transition');
                }
            });
        }, 300);
    }

    setupEventListeners() {
        this.nextButton?.addEventListener('click', () => {
            this.stopAutoplay();
            this.moveSlide('next');
            this.startAutoplay();
        });

        this.prevButton?.addEventListener('click', () => {
            this.stopAutoplay();
            this.moveSlide('prev');
            this.startAutoplay();
        });

        this.container.addEventListener('mouseenter', () => this.stopAutoplay());
        this.container.addEventListener('mouseleave', () => this.startAutoplay());

        let touchStartX = 0;
        this.container.addEventListener('touchstart', (e) => {
            touchStartX = e.touches[0].clientX;
        }, { passive: true });

        this.container.addEventListener('touchend', (e) => {
            const touchEndX = e.changedTouches[0].clientX;
            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > 50) {
                if (diff > 0) {
                    this.moveSlide('next');
                } else {
                    this.moveSlide('prev');
                }
            }
        });
    }

    startAutoplay() {
        if (this.slides.length < 2) return;
        if (this.autoplayInterval) this.stopAutoplay();
        this.autoplayInterval = setInterval(() => this.moveSlide('next'), 5000);
    }

    stopAutoplay() {
        if (this.autoplayInterval) {
            clearInterval(this.autoplayInterval);
            this.autoplayInterval = null;
        }
    }
}

    // Inisialisasi carousel
    async function initializeCarousel() {
        const carouselTrack = document.querySelector('.carousel-track');
        if (!carouselTrack) {
            console.error('Carousel track element not found');
            return;
        }

        let timelineData;
        try {
            timelineData = await fetchTimelineData();
            console.log('Timeline data received:', timelineData); // Debug log
        } catch (error) {
            console.error('Failed to fetch timeline data:', error);
            timelineData = [];
        }

        if (timelineData && timelineData.length > 0) {
            carouselTrack.innerHTML = '';
            timelineData.forEach(timeline => {
                if (validateTimelineData(timeline)) {
                    const slideHTML = createSlideHTML(timeline);
                    carouselTrack.insertAdjacentHTML('beforeend', slideHTML);
                }
            });

            new Carousel(document.querySelector('.carousel-container'));
        } else {
            carouselTrack.innerHTML = createEmptyStateSlide();
        }
    }

    // Helper function to validate timeline data
    function validateTimelineData(timeline) {
        const requiredFields = ['judul_kegiatan', 'deskripsi'];
        return requiredFields.every(field => 
            timeline.hasOwnProperty(field) && timeline[field] !== null && timeline[field] !== undefined
        );
    }

    // Helper function to create slide HTML
    function createSlideHTML(timeline) {
        const imagePath = timeline.image_path 
            ? `/frontend/public/assets/${timeline.image_path}`
            : '/frontend/public/assets/placeholder.png';
            
        return `
            <div class="carousel-slide">
                <img src="${imagePath}" alt="${timeline.judul_kegiatan || 'Event Image'}">
                <div class="slide-description">
                    <h3>${timeline.judul_kegiatan || 'Untitled Event'}</h3>
                    <div class="description-text">
                        ${timeline.deskripsi || 'No description available'}
                    </div>
                </div>
            </div>
        `;
    }

    // Helper function to create empty state slide
    function createEmptyStateSlide() {
        return `
            <div class="carousel-slide active">
                <img src="/frontend/public/assets/placeholder.png" alt="No Events">
                <div class="slide-description">
                    <h3>Tidak ada event saat ini</h3>
                    <div class="description-text">
                        Belum ada event yang ditambahkan ke dalam sistem.
                    </div>
                </div>
            </div>
        `;
    }

    // Initialize
    await initializeCarousel();
});