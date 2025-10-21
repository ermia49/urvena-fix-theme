/**
 * URVENA FIX Main JavaScript
 * Version: 1.1.0 - Enhanced with improved mobile menu and interactions
 * 
 * @package URVENA_Fix
 * @since 1.0.0
 */

(function() {
	'use strict';
	
	// Debug log to confirm script is loading
	console.log('URVENA FIX Enhanced JS v1.1.0 loaded successfully');

	/**
	 * Mobile Menu Toggle - Removed (navigation moved to footer)
	 */
	function initMobileMenu() {
		// Mobile menu removed - navigation now in footer
		return;
	}

	/**
	 * Smooth Scroll for Anchor Links
	 */
	function initSmoothScroll() {
		const links = document.querySelectorAll('a[href^="#"]');
		
		links.forEach(function(link) {
			link.addEventListener('click', function(e) {
				const href = this.getAttribute('href');
				
				// Skip if it's just '#'
				if (href === '#') return;
				
				const target = document.querySelector(href);
				
				if (target) {
					e.preventDefault();
					
					const headerHeight = document.querySelector('.site-header')?.offsetHeight || 0;
					const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;
					
					window.scrollTo({
						top: targetPosition,
						behavior: 'smooth'
					});
				}
			});
		});
	}

	/**
	 * Form Validation Enhancement
	 */
	function initFormValidation() {
		const contactForm = document.querySelector('.contact-form');
		
		if (!contactForm) return;

		contactForm.addEventListener('submit', function(e) {
			let isValid = true;
			const requiredFields = this.querySelectorAll('[required]');
			
			// Clear previous error states
			requiredFields.forEach(function(field) {
				field.classList.remove('error');
			});
			
			// Validate required fields
			requiredFields.forEach(function(field) {
				if (!field.value.trim()) {
					field.classList.add('error');
					isValid = false;
				}
				
				// Email validation
				if (field.type === 'email' && field.value.trim()) {
					const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
					if (!emailPattern.test(field.value)) {
						field.classList.add('error');
						isValid = false;
					}
				}
			});
			
			if (!isValid) {
				e.preventDefault();
				
				// Focus first error field
				const firstError = this.querySelector('.error');
				if (firstError) {
					firstError.focus();
					firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
				}
			}
		});

		// Real-time validation feedback
		const inputs = contactForm.querySelectorAll('input, textarea');
		inputs.forEach(function(input) {
			input.addEventListener('blur', function() {
				if (this.hasAttribute('required') && !this.value.trim()) {
					this.classList.add('error');
				} else {
					this.classList.remove('error');
				}
			});

			input.addEventListener('input', function() {
				if (this.classList.contains('error') && this.value.trim()) {
					this.classList.remove('error');
				}
			});
		});
	}

	/**
	 * Add CSS for form validation
	 */
	function addValidationStyles() {
		const style = document.createElement('style');
		style.textContent = `
			.form-group input.error,
			.form-group textarea.error {
				border-color: var(--color-error, #f44336);
			}
			.form-group input.error:focus,
			.form-group textarea.error:focus {
				box-shadow: 0 0 0 3px rgba(244, 67, 54, 0.1);
			}
		`;
		document.head.appendChild(style);
	}

	/**
	 * Logo Overlay Protection Fix
	 */
	function initLogoFix() {
		const logo = document.querySelector('.site-logo');
		const logoLink = document.querySelector('.site-logo-link');
		const branding = document.querySelector('.site-branding');
		const header = document.querySelector('.site-header');
		
		if (!logo || !logoLink || !branding || !header) return;
		
		// Force logo visibility
		function ensureLogoVisibility() {
			// Remove any overlay styles
			header.style.zIndex = '99999';
			header.style.isolation = 'isolate';
			branding.style.zIndex = '100000';
			branding.style.position = 'relative';
			logoLink.style.zIndex = '100001';
			logoLink.style.position = 'relative';
			logoLink.style.pointerEvents = 'auto';
			logo.style.zIndex = '100002';
			logo.style.position = 'relative';
			logo.style.visibility = 'visible';
			logo.style.opacity = '1';
			logo.style.display = 'block';
		}
		
		// Run on load and periodically check
		ensureLogoVisibility();
		setInterval(ensureLogoVisibility, 1000);
		
		// Fix any potential overlay issues
		window.addEventListener('scroll', ensureLogoVisibility);
		window.addEventListener('resize', ensureLogoVisibility);
	}

	/**
	 * Sticky Header on Scroll
	 */
	function initStickyHeader() {
		const header = document.querySelector('.site-header');
		
		if (!header) return;

		let lastScroll = 0;
		
		window.addEventListener('scroll', function() {
			const currentScroll = window.pageYOffset;
			
			if (currentScroll > 100) {
				header.classList.add('scrolled');
			} else {
				header.classList.remove('scrolled');
			}
			
			lastScroll = currentScroll;
		});

		// Add scrolled styles
		const style = document.createElement('style');
		style.textContent = `
			.site-header.scrolled {
				box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
			}
		`;
		document.head.appendChild(style);
	}

	/**
	 * Lazy Load Images Enhancement
	 */
	function initLazyLoad() {
		if ('IntersectionObserver' in window) {
			const images = document.querySelectorAll('img[data-src]');
			
			const imageObserver = new IntersectionObserver(function(entries) {
				entries.forEach(function(entry) {
					if (entry.isIntersecting) {
						const img = entry.target;
						img.src = img.dataset.src;
						img.removeAttribute('data-src');
						imageObserver.unobserve(img);
					}
				});
			});
			
			images.forEach(function(img) {
				imageObserver.observe(img);
			});
		}
	}

	/**
	 * Auto-hide success/error messages
	 */
	function initMessageAutoHide() {
		const messages = document.querySelectorAll('.contact-message');
		
		messages.forEach(function(message) {
			setTimeout(function() {
				message.style.transition = 'opacity 0.5s ease';
				message.style.opacity = '0';
				
				setTimeout(function() {
					message.style.display = 'none';
				}, 500);
			}, 5000);
		});
	}

	/**
	 * Initialize all functions when DOM is ready
	 */
	function init() {
		initLogoFix();
		initMobileMenu();
		initSmoothScroll();
		initFormValidation();
		addValidationStyles();
		initStickyHeader();
		initLazyLoad();
		initMessageAutoHide();
	}

	// Run on DOM ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}

})();

