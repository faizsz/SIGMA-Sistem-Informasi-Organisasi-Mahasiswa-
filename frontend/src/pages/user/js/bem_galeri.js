document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    function showContent(contentId) {
        document.querySelectorAll('.content').forEach(content => {
            content.classList.remove('active');
        });
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.getElementById(contentId).classList.add('active');
        event.target.classList.add('active');
    }

    // Attach showContent function to tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            showContent(this.getAttribute('onclick').match(/'([^']+)'/)[1]);
        });
    });

    // Gallery functionality
    const track = document.querySelector('.carousel-track');
    const slides = Array.from(track.children);
    const nextButton = document.querySelector('.carousel-button.next');
    const prevButton = document.querySelector('.carousel-button.prev');
 
    const updateSlidePositions = () => {
        const activeSlide = track.querySelector('.active');
        const activeIndex = slides.indexOf(activeSlide);
        
        slides.forEach((slide, index) => {
            slide.classList.remove('prev', 'active', 'next');
            if (index === activeIndex) {
                slide.classList.add('active');
            } else if (index === (activeIndex - 1 + slides.length) % slides.length) {
                slide.classList.add('prev');
            } else if (index === (activeIndex + 1) % slides.length) {
                slide.classList.add('next');
            }
        });
    };

    const moveToSlide = (direction) => {
        const currentSlide = track.querySelector('.active');
        let targetSlide;

        if (direction === 'next') {
            targetSlide = currentSlide.nextElementSibling || slides[0];
        } else {
            targetSlide = currentSlide.previousElementSibling || slides[slides.length - 1];
        }

        // Hide all slides except the current and target
        slides.forEach(slide => {
            if (slide !== currentSlide && slide !== targetSlide) {
                slide.style.opacity = '0';
            }
        });

        // Move to the target slide
        currentSlide.classList.remove('active');
        targetSlide.classList.add('active');

        // Update positions after a short delay
        setTimeout(() => {
            updateSlidePositions();
            // Restore opacity of all slides
            slides.forEach(slide => {
                slide.style.opacity = '';
            });
        }, 50);
    };

    nextButton.addEventListener('click', () => moveToSlide('next'));
    prevButton.addEventListener('click', () => moveToSlide('prev'));

    // Initialize
    updateSlidePositions();
    
    // Auto slide every 5 seconds
    setInterval(() => moveToSlide('next'), 5000);
});