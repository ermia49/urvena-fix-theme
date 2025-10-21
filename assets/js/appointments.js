jQuery(document).ready(function($) {
    // Appointment booking functionality
    let selectedDate = null;
    let selectedTime = null;
    let currentStep = 1;
    
    // Calendar functionality
    function initCalendar() {
        const calendar = $('#appointment-calendar');
        if (calendar.length === 0) return;
        
        const today = new Date();
        const currentMonth = today.getMonth();
        const currentYear = today.getFullYear();
        
        renderCalendar(currentYear, currentMonth);
    }
    
    function renderCalendar(year, month) {
        const calendar = $('#appointment-calendar');
        const monthNames = [
            'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
            'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'
        ];
        
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();
        
        let calendarHTML = `
            <div class="calendar-header">
                <button class="calendar-nav" data-direction="prev">&lt;</button>
                <h3>${monthNames[month]} ${year}</h3>
                <button class="calendar-nav" data-direction="next">&gt;</button>
            </div>
            <div class="calendar-grid">
                <div class="calendar-day-header">Mo</div>
                <div class="calendar-day-header">Di</div>
                <div class="calendar-day-header">Mi</div>
                <div class="calendar-day-header">Do</div>
                <div class="calendar-day-header">Fr</div>
                <div class="calendar-day-header">Sa</div>
                <div class="calendar-day-header">So</div>
        `;
        
        // Add empty cells for days before first day of month
        const startDay = firstDay === 0 ? 6 : firstDay - 1; // Convert Sunday = 0 to Monday = 0
        for (let i = 0; i < startDay; i++) {
            calendarHTML += '<div class="calendar-day empty"></div>';
        }
        
        // Add days of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const dateString = date.toISOString().split('T')[0];
            const isPast = date < today;
            const isWeekend = date.getDay() === 0; // Sunday only (Saturday has limited hours)
            
            let dayClass = 'calendar-day';
            if (isPast) dayClass += ' past';
            if (isWeekend) dayClass += ' unavailable';
            if (!isPast && !isWeekend) dayClass += ' available';
            
            calendarHTML += `<div class="${dayClass}" data-date="${dateString}">${day}</div>`;
        }
        
        calendarHTML += '</div>';
        calendar.html(calendarHTML);
        
        // Add event listeners
        $('.calendar-nav').off('click').on('click', handleCalendarNavigation);
        $('.calendar-day.available').off('click').on('click', handleDateSelection);
    }
    
    function handleCalendarNavigation(e) {
        const direction = $(this).data('direction');
        const header = $(this).siblings('h3').text().split(' ');
        const currentMonth = {
            'Januar': 0, 'Februar': 1, 'März': 2, 'April': 3, 'Mai': 4, 'Juni': 5,
            'Juli': 6, 'August': 7, 'September': 8, 'Oktober': 9, 'November': 10, 'Dezember': 11
        }[header[0]];
        const currentYear = parseInt(header[1]);
        
        let newMonth = currentMonth;
        let newYear = currentYear;
        
        if (direction === 'next') {
            newMonth++;
            if (newMonth > 11) {
                newMonth = 0;
                newYear++;
            }
        } else {
            newMonth--;
            if (newMonth < 0) {
                newMonth = 11;
                newYear--;
            }
        }
        
        renderCalendar(newYear, newMonth);
    }
    
    function handleDateSelection(e) {
        const date = $(this).data('date');
        selectedDate = date;
        
        $('.calendar-day').removeClass('selected');
        $(this).addClass('selected');
        
        // Load available times for selected date
        loadAvailableTimes(date);
    }
    
    function loadAvailableTimes(date) {
        const timeContainer = $('#available-times');
        if (timeContainer.length === 0) return;
        
        timeContainer.html('<p>Verfügbare Zeiten werden geladen...</p>');
        
        $.ajax({
            url: urvena_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_available_times',
                date: date,
                nonce: urvena_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    displayAvailableTimes(response.data);
                } else {
                    timeContainer.html('<p>Keine verfügbaren Zeiten gefunden.</p>');
                }
            },
            error: function() {
                timeContainer.html('<p>Fehler beim Laden der verfügbaren Zeiten.</p>');
            }
        });
    }
    
    function displayAvailableTimes(times) {
        const container = $('#available-times');
        container.empty();
        
        if (times.length === 0) {
            container.html('<p>Keine verfügbaren Termine an diesem Tag.</p>');
            return;
        }
        
        const timesHTML = times.map(time => 
            `<div class="time-slot" data-time="${time.time}">${time.display}</div>`
        ).join('');
        
        container.html(timesHTML);
        
        $('.time-slot').on('click', function() {
            selectedTime = $(this).data('time');
            $('.time-slot').removeClass('selected');
            $(this).addClass('selected');
        });
    }
    
    // Quick booking functionality
    function initQuickBooking() {
        $('#quick-booking-form').on('submit', function(e) {
            e.preventDefault();
            
            if (!selectedDate || !selectedTime) {
                alert('Bitte wählen Sie Datum und Uhrzeit aus.');
                return;
            }
            
            const formData = {
                action: 'book_appointment',
                nonce: urvena_ajax.nonce,
                customer_name: $('#quick_name').val(),
                customer_email: $('#quick_email').val(),
                customer_phone: $('#quick_phone').val(),
                service_id: $('#quick_service').val(),
                appointment_date: selectedDate,
                appointment_time: selectedTime,
                notes: $('#quick_notes').val()
            };
            
            $.ajax({
                url: urvena_ajax.ajax_url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        alert('Termin erfolgreich gebucht! Sie erhalten eine Bestätigungsmail.');
                        $('#quick-booking-form')[0].reset();
                        $('.calendar-day, .time-slot').removeClass('selected');
                        selectedDate = null;
                        selectedTime = null;
                    } else {
                        alert('Fehler: ' + response.data);
                    }
                },
                error: function() {
                    alert('Es ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut.');
                }
            });
        });
    }
    
    // Contact form enhancement
    function initContactForm() {
        $('#contact-form').on('submit', function(e) {
            e.preventDefault();
            
            const submitButton = $(this).find('button[type="submit"]');
            const originalText = submitButton.text();
            
            submitButton.text('Wird gesendet...').prop('disabled', true);
            
            const formData = {
                action: 'handle_contact_form',
                nonce: urvena_ajax.nonce,
                name: $('#contact_name').val(),
                email: $('#contact_email').val(),
                phone: $('#contact_phone').val(),
                subject: $('#contact_subject').val(),
                message: $('#contact_message').val()
            };
            
            $.ajax({
                url: urvena_ajax.ajax_url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        alert('Ihre Nachricht wurde erfolgreich gesendet!');
                        $('#contact-form')[0].reset();
                    } else {
                        alert('Fehler beim Senden: ' + response.data);
                    }
                },
                error: function() {
                    alert('Es ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut.');
                },
                complete: function() {
                    submitButton.text(originalText).prop('disabled', false);
                }
            });
        });
    }
    
    // Smooth scrolling for anchor links
    function initSmoothScrolling() {
        $('a[href^="#"]').on('click', function(e) {
            e.preventDefault();
            
            const target = $($(this).attr('href'));
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 500);
            }
        });
    }
    
    // Mobile menu toggle
    function initMobileMenu() {
        $('.mobile-menu-toggle').on('click', function() {
            $('.main-navigation').toggleClass('active');
            $(this).toggleClass('active');
        });
        
        // Close mobile menu when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.main-navigation, .mobile-menu-toggle').length) {
                $('.main-navigation').removeClass('active');
                $('.mobile-menu-toggle').removeClass('active');
            }
        });
    }
    
    // Lazy loading for images
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            document.querySelectorAll('img[data-src]').forEach(function(img) {
                imageObserver.observe(img);
            });
        }
    }
    
    // Initialize all functionality
    function init() {
        initCalendar();
        initQuickBooking();
        initContactForm();
        initSmoothScrolling();
        initMobileMenu();
        initLazyLoading();
        
        // Initialize date picker with German locale
        if ($.datepicker) {
            $.datepicker.setDefaults($.datepicker.regional['de']);
            $('.datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
                minDate: 1,
                maxDate: '+3M',
                beforeShowDay: function(date) {
                    const day = date.getDay();
                    // Disable Sundays (0)
                    return [day !== 0, day !== 0 ? 'available' : 'unavailable'];
                }
            });
        }
    }
    
    // Run initialization
    init();
    
    // Reinitialize on AJAX content load
    $(document).ajaxComplete(function() {
        initCalendar();
    });
});

// CSS Animation helpers
function fadeInUp(element, delay = 0) {
    setTimeout(function() {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'all 0.6s ease';
        
        setTimeout(function() {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, 10);
    }, delay);
}

// Intersection Observer for animations
if ('IntersectionObserver' in window) {
    const animationObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                animationObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.animate-on-scroll').forEach(function(el) {
            animationObserver.observe(el);
        });
    });
}

// Performance optimization: Debounce function
function debounce(func, wait, immediate) {
    let timeout;
    return function() {
        const context = this;
        const args = arguments;
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

// Window resize handler with debounce
window.addEventListener('resize', debounce(function() {
    // Handle responsive layouts
    const isMobile = window.innerWidth < 768;
    document.body.classList.toggle('is-mobile', isMobile);
}, 250));

// Add loading states for better UX
jQuery(document).ajaxStart(function() {
    jQuery('body').addClass('ajax-loading');
}).ajaxStop(function() {
    jQuery('body').removeClass('ajax-loading');
});