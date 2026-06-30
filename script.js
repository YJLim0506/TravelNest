// Mobile Navigation Toggle
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');

if (hamburger && navMenu) {
    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('active');
    });

    // Close mobile menu when clicking on a link
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            hamburger.classList.remove('active');
            navMenu.classList.remove('active');
        });
    });
}

// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Navbar background change on scroll
window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        if (window.scrollY > 100) {
            navbar.style.background = 'rgba(255, 255, 255, 0.98)';
            navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
        } else {
            navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            navbar.style.boxShadow = 'none';
        }
    }
});

// Intersection Observer for animations
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Contact form handling
// Updated contact form handling
const contactForm = document.querySelector('.contact-form');
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        // Get form data
        const subject = document.getElementById('fb-subject')?.value?.trim();
        const message = document.getElementById('fb-message')?.value?.trim();
        
        // Basic validation
        if (!subject || !message) {
            e.preventDefault();
            showNotification('Please fill in both subject and message fields.', 'error');
            return false;
        }
        
        // If validation passes, let the form submit normally
        // The PHP will handle the processing
    });
}

// Email validation
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Notification system
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close">&times;</button>
        </div>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 10000;
        max-width: 400px;
        animation: slideInRight 0.3s ease;
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Close button functionality
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        notification.remove();
    });
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Hero section parallax effect
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const hero = document.querySelector('.hero');
    if (hero && !document.querySelector('.hero-slider')) {
        const rate = Math.max(-50, Math.min(0, scrolled * -0.1));
        hero.style.transform = `translateY(${rate}px)`;
    }
});

// Add loading animation to images
document.querySelectorAll('img').forEach(img => {
    img.addEventListener('load', () => {
        img.style.opacity = '1';
    });
    
    img.addEventListener('error', () => {
        img.style.opacity = '0.5';
        img.style.filter = 'grayscale(100%)';
    });
});

// Counter animation for statistics
function animateCounter(element, target, duration = 2000) {
    let start = 0;
    const increment = target / (duration / 16);
    
    const timer = setInterval(() => {
        start += increment;
        if (start >= target) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(start);
        }
    }, 16);
}

// Add interactive features to destination cards
document.querySelectorAll('.destination-card').forEach(card => {
    card.addEventListener('click', () => {
        const destinationName = card.querySelector('h3')?.textContent;
        const destinationDesc = card.querySelector('p')?.textContent;
        
        console.log(`Clicked on: ${destinationName} - ${destinationDesc}`);
    });
});

// Add hover effects to service cards
document.querySelectorAll('.service-card').forEach(card => {
    card.addEventListener('mouseenter', () => {
        card.style.transform = 'translateY(-15px) scale(1.02)';
    });
    
    card.addEventListener('mouseleave', () => {
        card.style.transform = 'translateY(0) scale(1)';
    });
});

// Smooth reveal animation for sections
const revealSections = document.querySelectorAll('section');
const revealSection = (entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('section-revealed');
        }
    });
};

const sectionObserver = new IntersectionObserver(revealSection, {
    threshold: 0.15
});

revealSections.forEach(section => {
    section.classList.add('section-hidden');
    sectionObserver.observe(section);
});

// Add CSS for section reveal animation
const style = document.createElement('style');
style.textContent = `
    .section-hidden {
        opacity: 0;
        transform: translateY(50px);
        transition: all 0.8s ease;
    }
    
    .section-revealed {
        opacity: 1;
        transform: translateY(0);
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    
    .notification-close {
        background: none;
        border: none;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0;
        line-height: 1;
    }
    
    .notification-close:hover {
        opacity: 0.8;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;

document.head.appendChild(style);

// Hero slider implementation
function initHeroSlider() {
    const slider = document.querySelector('.hero-slider');
    if (!slider) return;
    
    const slides = Array.from(slider.querySelectorAll('.hero-slide'));
    const prevBtn = document.querySelector('.hero-prev');
    const nextBtn = document.querySelector('.hero-next');
    const slideCount = slides.length;
    
    let index = 0;
    let autoplayTimer;

    // Set initial slide as active
    if (slides[0]) slides[0].classList.add('active');

    // Preload backgrounds and set fallback on error
    const fallbackUrl = 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1600&h=900&fit=crop';
    slides.forEach(s => {
        const bg = s.style.backgroundImage;
        const match = bg.match(/url\(["']?(.*?)["']?\)/);
        const src = match ? match[1] : '';
        if (!src) return;
        const img = new Image();
        img.onload = () => { /* ok */ };
        img.onerror = () => { s.style.backgroundImage = `url('${fallbackUrl}')`; };
        img.src = src;
    });

    function update() {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
    }

    function goTo(i) {
        index = (i + slideCount) % slideCount;
        update();
        restartAutoplay();
    }

    function next() { goTo(index + 1); }
    function prev() { goTo(index - 1); }

    prevBtn?.addEventListener('click', prev);
    nextBtn?.addEventListener('click', next);

    const progressBar = document.querySelector('.hero-progress-bar');
    
    function startAutoplay() {
        autoplayTimer = setInterval(next, 8000);
        if (progressBar) {
            progressBar.style.width = '0%';
            progressBar.style.transition = 'width 8s linear';
            setTimeout(() => {
                progressBar.style.width = '100%';
            }, 100);
        }
    }
    
    function stopAutoplay() {
        clearInterval(autoplayTimer);
        if (progressBar) {
            progressBar.style.transition = 'none';
            progressBar.style.width = '0%';
        }
    }
    
    function restartAutoplay() {
        stopAutoplay();
        startAutoplay();
    }

    const hero = document.querySelector('.hero');
    hero?.addEventListener('mouseenter', stopAutoplay);
    hero?.addEventListener('mouseleave', startAutoplay);

    document.addEventListener('keydown', (e) => {
    // don’t hijack keys while the user is typing
    const t = e.target;
    const tag = (t.tagName || '').toLowerCase();
    const isTyping = tag === 'input' || tag === 'textarea' || t.isContentEditable;
    if (isTyping) return;

    if (e.key === 'ArrowLeft') prev();
    if (e.key === 'ArrowRight') next();
    if (e.key === ' ') {
        e.preventDefault();
        if (autoplayTimer) stopAutoplay();
        else startAutoplay();
    }
    });

    let startX = 0;
    let endX = 0;
    
    hero?.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
    });
    
    hero?.addEventListener('touchend', (e) => {
        endX = e.changedTouches[0].clientX;
        const diff = startX - endX;
        const threshold = 50;
        
        if (Math.abs(diff) > threshold) {
            if (diff > 0) next();
            else prev();
        }
    });

    update();
    startAutoplay();
}

// =============================================================================
// PACKAGE DETAIL FUNCTIONALITY
// =============================================================================

// Global variables for package details
let selectedDate = null;
let basePrice = 0;
let seatAvailability = null;
let packageConfig = {};

// Initialize the package detail functionality
function initPackageDetail(config) {
    packageConfig = config;
    basePrice = config.defaultPrice || 0;
    
    initTabFunctionality();
    initQuantityInputs();
    initDateSelection();
    initRoomTypeSelection();
    initBookingHandling();
    
    updatePricing();
    updateQuantityButtons();
    
    console.log(`${config.packageName} package detail page loaded successfully!`);
}

// Tab functionality for package details
function initTabFunctionality() {
    const tabButtons = document.querySelectorAll('.package-nav-tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const targetTab = button.getAttribute('data-tab');
            
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            button.classList.add('active');
            const targetContent = document.getElementById(targetTab);
            if (targetContent) {
                targetContent.classList.add('active');
            }
        });
    });
}

// Quantity input functionality
function initQuantityInputs() {
    // Add error message container if it doesn't exist
    const quantitySection = document.querySelector('#adults').closest('.form-group').parentElement;
    if (quantitySection && !document.getElementById('date-selection-error')) {
        const errorDiv = document.createElement('div');
        errorDiv.id = 'date-selection-error';
        errorDiv.style.display = 'none';
        quantitySection.appendChild(errorDiv);
    }

    // Make changeQuantity globally available for onclick handlers
    window.changeQuantity = function(type, change) {
        if (!selectedDate) {
            showDateSelectionError();
            return;
        }

        hideDateSelectionError();

        const input = document.getElementById(type);
        if (!input) return;
        
        let currentValue = parseInt(input.value);
        const min = parseInt(input.getAttribute('min'));
        const max = parseInt(input.getAttribute('max'));

        let newValue = currentValue + change;
        if (newValue < min) newValue = min;
        if (newValue > max) newValue = max;

        const otherType = type === 'adults' ? 'children' : 'adults';
        const otherInput = document.getElementById(otherType);
        const otherValue = otherInput ? parseInt(otherInput.value) : 0;
        const available = seatAvailability != null ? seatAvailability : Infinity;
        
        if (newValue + otherValue > available) {
            newValue = Math.max(min, available - otherValue);
        }

        input.value = newValue;
        updatePricing();
        updateQuantityButtons();
    };
}

function showDateSelectionError() {
    const errorElement = document.getElementById('date-selection-error');
    if (errorElement) {
        errorElement.textContent = 'Please select a departure date first';
        errorElement.style.display = 'block';
    }
}

function hideDateSelectionError() {
    const errorElement = document.getElementById('date-selection-error');
    if (errorElement) {
        errorElement.style.display = 'none';
    }
}

function updateQuantityButtons() {
    const adultsInput = document.getElementById('adults');
    const childrenInput = document.getElementById('children');
    
    if (!adultsInput || !childrenInput) return;
    
    const adultsValue = parseInt(adultsInput.value);
    const childrenValue = parseInt(childrenInput.value);
    const adultsMin = parseInt(adultsInput.getAttribute('min'));
    const adultsMax = parseInt(adultsInput.getAttribute('max'));
    const childrenMin = parseInt(childrenInput.getAttribute('min'));
    const childrenMax = parseInt(childrenInput.getAttribute('max'));
    const available = seatAvailability != null ? seatAvailability : Infinity;
    
    const adultsButtons = adultsInput.parentElement.querySelectorAll('button');
    const childrenButtons = childrenInput.parentElement.querySelectorAll('button');
    
    // Apply grayed out style when no date is selected
    const isDisabled = !selectedDate;
    
    if (adultsButtons.length >= 2) {
        adultsButtons.forEach(btn => {
            btn.style.opacity = isDisabled ? '0.4' : '1';
            btn.style.cursor = isDisabled ? 'not-allowed' : 'pointer';
        });
        
        if (!isDisabled) {
            adultsButtons[0].disabled = adultsValue <= adultsMin;
            adultsButtons[1].disabled = adultsValue >= adultsMax || (adultsValue + childrenValue) >= available;
        }
    }
    
    if (childrenButtons.length >= 2) {
        childrenButtons.forEach(btn => {
            btn.style.opacity = isDisabled ? '0.4' : '1';
            btn.style.cursor = isDisabled ? 'not-allowed' : 'pointer';
        });
        
        if (!isDisabled) {
            childrenButtons[0].disabled = childrenValue <= childrenMin;
            childrenButtons[1].disabled = childrenValue >= childrenMax || (adultsValue + childrenValue) >= available;
        }
    }
}

// Date selection functionality
function initDateSelection() {
    document.querySelectorAll('.date-option').forEach(dateOption => {
        dateOption.addEventListener('click', () => {
            document.querySelectorAll('.date-option').forEach(opt => opt.classList.remove('selected'));
            dateOption.classList.add('selected');

            selectedDate = dateOption.getAttribute('data-date');
            basePrice = parseInt(dateOption.getAttribute('data-price'));

            const availabilityEl = dateOption.querySelector('.date-availability');
            if (availabilityEl) {
                const match = availabilityEl.textContent.match(/\d+/);
                seatAvailability = match ? parseInt(match[0]) : null;
            } else {
                seatAvailability = null;
            }

            hideDateSelectionError();
            updatePricing();
            updateQuantityButtons();
        });
    });
}

// Room type selection
function initRoomTypeSelection() {
    const roomTypeSelect = document.getElementById('room-type');
    if (roomTypeSelect) {
        roomTypeSelect.addEventListener('change', updatePricing);
    }
}

// Pricing calculation
function updatePricing() {
    const roomTypeSelect = document.getElementById('room-type');
    const adultsInput = document.getElementById('adults');
    const childrenInput = document.getElementById('children');
    
    if (!roomTypeSelect || !adultsInput || !childrenInput) return;
    
    const roomType = roomTypeSelect.value;
    const adults = parseInt(adultsInput.value);
    const children = parseInt(childrenInput.value);

    let roomUpgrade = 0;
    if (packageConfig.roomUpgrades && packageConfig.roomUpgrades[roomType]) {
        roomUpgrade = packageConfig.roomUpgrades[roomType];
    }

    const totalBasePrice = basePrice * adults;
    const additionalAdults = adults > 2 ? (adults - 2) * basePrice : 0;
    const childFee = children * (basePrice * 0.7);
    const serviceTax = (totalBasePrice + roomUpgrade + additionalAdults + childFee) * 0.06;
    const total = totalBasePrice + roomUpgrade + additionalAdults + childFee + serviceTax;

    const elements = {
        'base-price': `RM ${totalBasePrice.toLocaleString()}`,
        'room-upgrade': `RM ${roomUpgrade.toLocaleString()}`,
        'adult-fee': `RM ${additionalAdults.toLocaleString()}`,
        'child-fee': `RM ${Math.round(childFee).toLocaleString()}`,
        'service-tax': `RM ${Math.round(serviceTax).toLocaleString()}`,
        'total-price': `RM ${Math.round(total).toLocaleString()}`
    };

    Object.keys(elements).forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.textContent = elements[id];
        }
    });
}

// Booking handling
function initBookingHandling() {
    window.handleBooking = function() {
        if (!selectedDate) {
            alert('Please select a departure date first.');
            return;
        }

        const roomTypeSelect = document.getElementById('room-type');
        const adultsInput = document.getElementById('adults');
        const childrenInput = document.getElementById('children');
        const specialRequestsInput = document.getElementById('special-requests');
        const totalPriceElement = document.getElementById('total-price');

        if (!roomTypeSelect || !adultsInput || !childrenInput || !totalPriceElement) {
            console.error('Required form elements not found');
            return;
        }

        const bookingData = {
            packageName: packageConfig.packageName,
            departureDate: selectedDate,
            roomType: roomTypeSelect.value,
            adults: adultsInput.value,
            children: childrenInput.value,
            specialRequests: specialRequestsInput ? specialRequestsInput.value : '',
            totalPrice: totalPriceElement.textContent
        };

        sessionStorage.setItem('bookingData', JSON.stringify(bookingData));
        window.location.href = packageConfig.paymentPageUrl || '../payment.php';
    };
}

// Initialize everything when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('TravelNest website loaded successfully!');
    
    // Add initial animations
    setTimeout(() => {
        document.body.style.opacity = '1';
    }, 100);
    
    // Initialize hero slider if present
    initHeroSlider();
    
    // Initialize package detail functionality if config is available
    if (typeof window.packageDetailConfig !== 'undefined') {
        initPackageDetail(window.packageDetailConfig);
    }
});

// Add loading state to body
document.body.style.opacity = '0';
document.body.style.transition = 'opacity 0.5s ease';