// Main JavaScript file for Guillaume Housing

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    // Re-initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // Initialize FAQ accordion if on FAQ page
    if (document.querySelector('.faq-content')) {
        initializeFAQ();
    }

    // Initialize contact form if on contact page
    if (document.getElementById('contactForm')) {
        initializeContactForm();
    }

    // Initialize home contact form if on home page
    if (document.getElementById('homeContactForm')) {
        initializeHomeContactForm();
    }

    // Initialize property filters if on properties page
    if (document.getElementById('filterForm')) {
        initializePropertyFilters();
    }

    // Initialize booking buttons if on properties page
    if (document.querySelector('.btn-book')) {
        initializeBookingButtons();
    }
    
    // Load property details if on property details page
    if (document.getElementById('propertyTitle')) {
        loadPropertyDetails();
    }
}

// FAQ Accordion Functionality
function initializeFAQ() {
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const faqItem = this.parentElement;
            const isActive = faqItem.classList.contains('active');
            
            // Close all FAQ items
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Open clicked item if it wasn't active
            if (!isActive) {
                faqItem.classList.add('active');
            }
        });
    });
}

// Contact Form
function initializeContactForm() {
    const form = document.getElementById('contactForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        fetch('/GuillaumeHousing/contact', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Message sent successfully!');
                form.reset();
            } else {
                alert('Failed to send message. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
}

// Home Contact Form
function initializeHomeContactForm() {
    const form = document.getElementById('homeContactForm');
    
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        fetch('/GuillaumeHousing/contact', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Message sent successfully!');
                form.reset();
            } else {
                alert('Failed to send message. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
}

// Property Filters
function initializePropertyFilters() {
    const form = document.getElementById('filterForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const filters = {
            location: document.getElementById('location').value,
            minPrice: document.getElementById('minPrice').value,
            maxPrice: document.getElementById('maxPrice').value,
            bedrooms: document.getElementById('bedrooms').value
        };
        
        loadFilteredProperties(filters);
    });
}

function loadFilteredProperties(filters) {
    console.log('Applying filters:', filters);
    // Implement AJAX call to filter properties
}

// Load Featured Properties
function loadFeaturedProperties() {
    fetch('/GuillaumeHousing/api/properties/featured')
        .then(response => response.json())
        .then(properties => {
            displayProperties(properties, '.featured-properties .property-grid');
        })
        .catch(error => console.error('Error loading featured properties:', error));
}

// Load All Properties
function loadAllProperties() {
    fetch('/GuillaumeHousing/api/properties')
        .then(response => response.json())
        .then(properties => {
            displayProperties(properties, '#propertiesGrid');
        })
        .catch(error => console.error('Error loading properties:', error));
}

// Display Properties
function displayProperties(properties, containerSelector) {
    const container = document.querySelector(containerSelector);
    
    if (!container) return;
    
    container.innerHTML = properties.map(property => {
        const statusTag = property.status === 'for-rent' ? 'rent' : 'sale';
        const statusText = property.status === 'for-rent' ? 'FOR RENT' : 'FOR SALE';
        const propertyType = property.type || 'Residential';
        const typeIcon = propertyType.toLowerCase() === 'commercial' ? 'building' : 'home';
        
        return `
            <div class="property-card">
                <div class="property-image">
                    <img src="${property.image || '/GuillaumeHousing/images/placeholder.jpg'}" alt="${property.title}">
                    <span class="property-tag ${statusTag}">${statusText}</span>
                </div>
                <div class="property-content">
                    <p class="property-type"><i data-lucide="${typeIcon}"></i> ${propertyType}</p>
                    <h3>${property.title.toUpperCase()}</h3>
                    <h4>${formatCurrency(property.price)}</h4>
                    <p class="property-location">${property.location.toUpperCase()}</p>
                    <p class="property-info">Bedrooms: ${property.bedrooms} / Baths: ${property.bathrooms} / Sq Ft: ${property.area}</p>
                </div>
            </div>
        `;
    }).join('');
    
    // Re-initialize Lucide icons after adding new content
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

// Format currency for FCFA
function formatCurrency(price) {
    const numPrice = parseFloat(price);
    return numPrice.toLocaleString('en-US') + ' FCFA/MO';
}

// Load Property Details
function loadPropertyDetails() {
    const urlParams = new URLSearchParams(window.location.search);
    const propertyId = window.location.pathname.split('/').pop();
    
    if (!propertyId) return;
    
    fetch(`/GuillaumeHousing/api/property/${propertyId}`)
        .then(response => response.json())
        .then(property => {
            document.getElementById('propertyTitle').textContent = property.title;
            document.getElementById('propertyLocation').textContent = property.location;
            document.getElementById('propertyPrice').textContent = `${formatCurrency(property.price)}`;
            document.getElementById('propertyBedrooms').textContent = property.bedrooms;
            document.getElementById('propertyBathrooms').textContent = property.bathrooms;
            document.getElementById('propertyArea').textContent = property.area;
            document.getElementById('propertyStatus').textContent = property.status;
            document.getElementById('propertyDescription').textContent = property.description;
            document.getElementById('propertyImage').src = property.image || '/GuillaumeHousing/images/placeholder.jpg';
        })
        .catch(error => console.error('Error loading property details:', error));
}

// Booking Functionality
function initializeBookingButtons() {
    const bookButtons = document.querySelectorAll('.btn-book');
    
    bookButtons.forEach(button => {
        button.addEventListener('click', function() {
            const propertyId = this.getAttribute('data-property-id');
            const propertyTitle = this.getAttribute('data-property-title');
            const propertyPrice = this.getAttribute('data-property-price');
            const propertyStatus = this.getAttribute('data-property-status');
            console.debug('Booking button clicked', { propertyId, propertyTitle, propertyPrice, propertyStatus });
            
            showBookingModal(propertyId, propertyTitle, propertyPrice, propertyStatus);
        });
    });
}

function showBookingModal(propertyId, propertyTitle, propertyPrice, propertyStatus) {
    // Determine if property is for rent or sale
    const isForRent = propertyStatus === 'rent';
    
    // Create different form fields based on property status
    let formFields = '';
    
    if (isForRent) {
        // For rent: Payment Date, Months of Payment, Number of Inhabitants
        formFields = `
            <div class="form-group">
                <label for="check_in">Payment Date:</label>
                <input type="date" id="check_in" name="check_in" required min="${getTodayDate()}">
            </div>
            
            <div class="form-group">
                <label for="months">Months of Payment:</label>
                <input type="number" id="months" name="months" required min="1" max="24" value="1">
            </div>
            
            <div class="form-group">
                <label for="guests">Number of Inhabitants:</label>
                <input type="number" id="guests" name="guests" required min="1" max="20" value="1">
            </div>
            
            <div class="form-group">
                <label>Total Price:</label>
                <p id="totalPrice" class="total-price">${formatPriceFCFA(propertyPrice)}/month</p>
            </div>
        `;
    } else {
        // For sale: Payment Date and Total Price only
        formFields = `
            <div class="form-group">
                <label for="check_in">Payment Date:</label>
                <input type="date" id="check_in" name="check_in" required min="${getTodayDate()}">
            </div>
            
            <div class="form-group">
                <label>Total Price:</label>
                <p id="totalPrice" class="total-price">${formatPriceFCFA(propertyPrice)}</p>
            </div>
        `;
    }
    
    // Create modal HTML
    const modalHTML = `
        <div id="bookingModal" class="modal">
            <div class="modal-content">
                <span class="modal-close">&times;</span>
                <h2>Book ${propertyTitle}</h2>
                <p class="booking-price">Price: ${isForRent ? formatPriceFCFA(propertyPrice) + '/month' : formatPriceFCFA(propertyPrice)}</p>
                <form id="bookingForm" method="POST" action="/GuillaumeHousing/booking/create">
                    <input type="hidden" name="property_id" value="${propertyId}">
                    <input type="hidden" name="property_price" value="${propertyPrice}">
                    <input type="hidden" name="property_status" value="${propertyStatus}">
                    
                    ${formFields}
                    
                    <button type="submit" class="btn-primary">Confirm Booking</button>
                </form>
            </div>
        </div>
    `;
    
    // Add modal to page
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Get modal elements
    const modal = document.getElementById('bookingModal');
    const closeBtn = modal.querySelector('.modal-close');
    const form = document.getElementById('bookingForm');
    const checkInInput = document.getElementById('check_in');
    
    console.debug('Booking modal shown', { propertyId, propertyTitle, propertyPrice, propertyStatus });

    // Close modal handlers
    closeBtn.addEventListener('click', () => {
        modal.remove();
    });
    
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.remove();
        }
    });
    
    // Update total price when months change (for rent properties)
    if (isForRent) {
        const monthsInput = document.getElementById('months');
        monthsInput.addEventListener('change', updateTotalPriceRent);
        
        function updateTotalPriceRent() {
            const months = parseInt(monthsInput.value) || 1;
            const total = months * parseInt(propertyPrice);
            document.getElementById('totalPrice').textContent = formatPriceFCFA(total) + ' (' + months + ' month' + (months > 1 ? 's' : '') + ')';
        }
    }
    
    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        console.debug('Booking form submit triggered');
        
        const formData = new FormData(form);
        
        // Calculate total price and set check_out based on property type
        if (isForRent) {
            const months = parseInt(document.getElementById('months').value);
            const totalPrice = months * parseInt(propertyPrice);
            formData.append('total_price', totalPrice);
            
            // Calculate check_out date based on months
            const checkInDate = new Date(checkInInput.value);
            const checkOutDate = new Date(checkInDate);
            checkOutDate.setMonth(checkOutDate.getMonth() + months);
            const checkOutStr = checkOutDate.toISOString().split('T')[0];
            formData.append('check_out', checkOutStr);
        } else {
            // For sale, use property price as total and set check_out to same as check_in
            formData.append('total_price', propertyPrice);
            formData.append('check_out', checkInInput.value);
            formData.append('guests', '1'); // Default value for sale
        }
        
        // Debug log what we're sending
        console.debug('Submitting booking with data:', {
            property_id: formData.get('property_id'),
            check_in: formData.get('check_in'),
            check_out: formData.get('check_out'),
            guests: formData.get('guests'),
            total_price: formData.get('total_price'),
            property_status: formData.get('property_status')
        });
        
        // Send AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onload = function() {
            if (xhr.status === 200) {
                let response = null;
                try {
                    response = JSON.parse(xhr.responseText);
                } catch (err) {
                    console.error('Failed to parse JSON response for booking:', err);
                    console.error('Raw response:', xhr.responseText);
                    alert('Server returned an unexpected response. Falling back to normal submit.');
                    // remove this submit handler and submit the form normally so server still receives the POST
                    try {
                        form.removeEventListener('submit', arguments.callee);
                    } catch (removeErr) {
                        console.warn('Could not remove submit handler:', removeErr);
                    }
                    form.submit();
                    return;
                }

                if (response && response.success) {
                    alert('Booking created successfully!');
                    modal.remove();
                    // Optionally redirect to bookings page
                    // window.location.href = '/GuillaumeHousing/bookings';
                } else {
                    // Show detailed error if available
                    let errorMsg = 'Error: ' + (response && response.message ? response.message : 'Unknown error');
                    if (response && response.error) {
                        errorMsg += '\n\nDetails: ' + response.error;
                    }
                    console.error('Booking error response:', response);
                    alert(errorMsg);
                }
            } else {
                console.error('XHR failed', xhr.status, xhr.responseText);
                alert('Error submitting booking. The request failed. Falling back to normal submit.');
                // fallback: remove AJAX prevention and submit normally
                try {
                    form.removeEventListener('submit', arguments.callee);
                } catch (removeErr) {
                    console.warn('Could not remove submit handler:', removeErr);
                }
                form.submit();
            }
        };
        xhr.onerror = function() {
            console.error('XHR network error');
            alert('Network error submitting booking. Falling back to normal submit.');
            form.removeEventListener('submit', arguments.callee);
            form.submit();
        };
        
        xhr.send(formData);
    });
}

function getTodayDate() {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

function formatPriceFCFA(price) {
    return new Intl.NumberFormat('en-US').format(price) + ' FCFA';
}

// Utility Functions
function formatPrice(price) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(price);
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}
