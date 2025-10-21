/**
 * URVENA FIX - Auto-Sliding Image Carousel
 * Professional tire service image gallery with German optimization
 */

class UrvenaCarousel {
    constructor(containerSelector) {
        this.container = document.querySelector(containerSelector);
        if (!this.container) return;
        
        this.slides = this.container.querySelectorAll('.carousel-slide');
        this.indicators = this.container.querySelectorAll('.indicator');
        this.prevBtn = this.container.querySelector('.carousel-nav.prev');
        this.nextBtn = this.container.querySelector('.carousel-nav.next');
        
        this.currentSlide = 0;
        this.isAutoPlaying = true;
        this.autoPlayInterval = null;
        this.autoPlayDelay = 4000; // 4 seconds between slides
        
        this.init();
    }
    
    init() {
        if (this.slides.length === 0) return;
        
        // Initialize first slide
        this.showSlide(0);
        
        // Add event listeners
        this.addEventListeners();
        
        // Start auto-play
        this.startAutoPlay();
        
        // Load images progressively
        this.loadImages();
        
        // Add touch/swipe support
        this.addTouchSupport();
    }
    
    addEventListeners() {
        // Navigation buttons
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => {
                this.prevSlide();
                this.pauseAutoPlay();
            });
        }
        
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => {
                this.nextSlide();
                this.pauseAutoPlay();
            });
        }
        
        // Indicators
        this.indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => {
                this.goToSlide(index);
                this.pauseAutoPlay();
            });
        });
        
        // Pause on hover, resume on leave
        this.container.addEventListener('mouseenter', () => {
            this.pauseAutoPlay();
        });
        
        this.container.addEventListener('mouseleave', () => {
            if (this.isAutoPlaying) {
                this.startAutoPlay();
            }
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (this.container.matches(':hover')) {
                if (e.key === 'ArrowLeft') {
                    this.prevSlide();
                    this.pauseAutoPlay();
                } else if (e.key === 'ArrowRight') {
                    this.nextSlide();
                    this.pauseAutoPlay();
                }
            }
        });
        
        // Visibility API - pause when tab is not active
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.pauseAutoPlay();
            } else if (this.isAutoPlaying) {
                this.startAutoPlay();
            }
        });
    }
    
    showSlide(index) {
        // Remove active class from all slides and indicators
        this.slides.forEach(slide => slide.classList.remove('active'));
        this.indicators.forEach(indicator => indicator.classList.remove('active'));
        
        // Add active class to current slide and indicator
        if (this.slides[index]) {
            this.slides[index].classList.add('active');
            this.currentSlide = index;
        }
        
        if (this.indicators[index]) {
            this.indicators[index].classList.add('active');
        }
        
        // Preload next image
        const nextIndex = (index + 1) % this.slides.length;
        this.preloadImage(nextIndex);
    }
    
    nextSlide() {
        const nextIndex = (this.currentSlide + 1) % this.slides.length;
        this.showSlide(nextIndex);
    }
    
    prevSlide() {
        const prevIndex = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.showSlide(prevIndex);
    }
    
    goToSlide(index) {
        if (index >= 0 && index < this.slides.length) {
            this.showSlide(index);
        }
    }
    
    startAutoPlay() {
        this.stopAutoPlay(); // Clear any existing interval
        this.autoPlayInterval = setInterval(() => {
            this.nextSlide();
        }, this.autoPlayDelay);
    }
    
    stopAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
        }
    }
    
    pauseAutoPlay() {
        this.stopAutoPlay();
        // Resume after 8 seconds of inactivity
        setTimeout(() => {
            if (this.isAutoPlaying) {
                this.startAutoPlay();
            }
        }, 8000);
    }
    
    loadImages() {
        this.slides.forEach((slide, index) => {
            const img = slide.querySelector('.carousel-image');
            if (img) {
                // Add loading event listeners
                img.addEventListener('load', () => {
                    slide.classList.add('loaded');
                });
                
                img.addEventListener('error', () => {
                    console.warn(`Failed to load carousel image ${index + 1}`);
                    slide.classList.add('loaded'); // Remove loading animation even on error
                });
                
                // If image is already loaded (cached)
                if (img.complete) {
                    slide.classList.add('loaded');
                }
            }
        });
    }
    
    preloadImage(index) {
        if (this.slides[index]) {
            const img = this.slides[index].querySelector('.carousel-image');
            if (img && !img.complete) {
                // Force loading of the image
                const tempImg = new Image();
                tempImg.src = img.src;
            }
        }
    }
    
    addTouchSupport() {
        let startX = 0;
        let startY = 0;
        let endX = 0;
        let endY = 0;
        
        this.container.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        }, { passive: true });
        
        this.container.addEventListener('touchend', (e) => {
            endX = e.changedTouches[0].clientX;
            endY = e.changedTouches[0].clientY;
            
            const deltaX = endX - startX;
            const deltaY = endY - startY;
            
            // Only trigger swipe if horizontal movement is greater than vertical
            if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > 50) {
                if (deltaX > 0) {
                    this.prevSlide(); // Swipe right = previous
                } else {
                    this.nextSlide(); // Swipe left = next
                }
                this.pauseAutoPlay();
            }
        }, { passive: true });
    }
    
    // Public methods for external control
    play() {
        this.isAutoPlaying = true;
        this.startAutoPlay();
    }
    
    pause() {
        this.isAutoPlaying = false;
        this.stopAutoPlay();
    }
    
    destroy() {
        this.stopAutoPlay();
        // Remove event listeners and clean up
        this.container.removeEventListener('mouseenter', this.pauseAutoPlay);
        this.container.removeEventListener('mouseleave', this.startAutoPlay);
    }
}

// Initialize carousel when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize URVENA carousel
    const urvenaCarousel = new UrvenaCarousel('.about-carousel-container');
    
    // Make it globally accessible for debugging
    window.urvenaCarousel = urvenaCarousel;
    
    // Optional: Add performance monitoring
    if ('IntersectionObserver' in window) {
        const carouselObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    urvenaCarousel.play();
                } else {
                    urvenaCarousel.pause();
                }
            });
        }, { threshold: 0.1 });
        
        const carouselContainer = document.querySelector('.about-carousel-container');
        if (carouselContainer) {
            carouselObserver.observe(carouselContainer);
        }
    }
});

// SEO and accessibility enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Add ARIA labels for better accessibility
    const carousel = document.querySelector('.about-carousel-container');
    if (carousel) {
        carousel.setAttribute('role', 'region');
        carousel.setAttribute('aria-label', 'URVENA FIX Werkstatt Bildergalerie');
        
        // Add aria-live region for screen readers
        const liveRegion = document.createElement('div');
        liveRegion.setAttribute('aria-live', 'polite');
        liveRegion.setAttribute('aria-atomic', 'true');
        liveRegion.className = 'sr-only';
        liveRegion.id = 'carousel-status';
        carousel.appendChild(liveRegion);
        
        // Update live region when slide changes
        const slides = carousel.querySelectorAll('.carousel-slide');
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    const target = mutation.target;
                    if (target.classList.contains('active')) {
                        const slideNumber = Array.from(slides).indexOf(target) + 1;
                        liveRegion.textContent = `Bild ${slideNumber} von ${slides.length}`;
                    }
                }
            });
        });
        
        slides.forEach(slide => {
            observer.observe(slide, { attributes: true });
        });
    }
});