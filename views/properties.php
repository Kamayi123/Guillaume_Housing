<?php $pageTitle = 'Properties - Guillaume Housing'; ?>
<?php include 'header.php'; ?>

<main>
    <section class="properties-header">
        <div class="container">
            <p class="section-subtitle">FIND YOUR PERFECT HOME</p>
            <h1 class="section-title">NICE APARTMENTS</h1>
            <div class="title-line"></div>
        </div>
    </section>

    <section class="properties-content">
        <div class="container">
            <div id="property-grid" class="property-grid">
                <p style="grid-column: 1/-1; text-align: center; padding: 40px; color: #666;">Loading properties...</p>
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        // Fetch and display all properties from API
        fetch('/GuillaumeHousing/api/properties')
            .then(r => r.json())
            .then(properties => {
                const grid = document.getElementById('property-grid');
                
                if (properties.length === 0) {
                    grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; padding: 40px; color: #666;">No properties available</p>';
                    return;
                }
                
                let html = '';
                properties.forEach(p => {
                    // Determine tag based on status
                    let tagText = 'AVAILABLE';
                    let tagClass = 'available';
                    if (p.status === 'for-rent') {
                        tagText = 'FOR RENT';
                        tagClass = 'rent';
                    } else if (p.status === 'for-sale') {
                        tagText = 'FOR SALE';
                        tagClass = 'sale';
                    } else if (p.status === 'rented') {
                        tagText = 'RENTED';
                        tagClass = 'rented';
                    } else if (p.status === 'sold') {
                        tagText = 'SOLD';
                        tagClass = 'sold';
                    }
                    
                    // Determine property type icon
                    const iconType = p.type === 'Commercial' ? 'building' : 'home';
                    
                    // Format price for display
                    const priceDisplay = p.status === 'for-sale' || p.status === 'sold' 
                        ? Number(p.price).toLocaleString() + ' FCFA'
                        : Number(p.price).toLocaleString() + ' FCFA /MO';
                    
                    // Get image
                    const imgSrc = p.image || '/GuillaumeHousing/images/default-property.jpg';
                    
                    html += `
                        <div class="property-card">
                            <div class="property-image">
                                <img src="${imgSrc}" alt="${p.title}">
                                <span class="property-tag ${tagClass}">${tagText}</span>
                            </div>
                            <div class="property-content">
                                <p class="property-type"><i data-lucide="${iconType}"></i> ${p.type}</p>
                                <h3>${p.title}</h3>
                                <h4>${priceDisplay}</h4>
                                <p class="property-info">
                                    ${p.bedrooms} bd / ${p.bathrooms} ba / ${p.area} Sq Ft
                                </p>
                                <button class="btn-book" data-property-id="${p.id}" data-property-title="${p.title}" data-property-price="${p.price}" data-property-status="${p.status}">Book Now</button>
                            </div>
                        </div>
                    `;
                });
                
                grid.innerHTML = html;
                // Initialize lucide icons for dynamically added elements
                lucide.createIcons();
            })
            .catch(e => {
                console.error('Error loading properties:', e);
                const grid = document.getElementById('property-grid');
                grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; padding: 40px; color: #e74c3c;">Error loading properties</p>';
            });
    </script>

</main>

<?php include 'footer.php'; ?>
