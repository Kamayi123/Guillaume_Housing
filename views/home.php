<?php $pageTitle = 'Home - Guillaume Housing'; ?>
<?php include 'header.php'; ?>

<main>
   <section class="hero">

    <div class="overlay"></div>

    <div class="hero-container">

        <!-- LEFT CONTENT -->
        <div class="hero-left">

            <div class="phone-box">
                <i data-lucide="phone"></i>
                <p>+237 653901025</p>
            </div>

            <h1>
                FIND YOUR <br>
                DREAM HOME <br>
                TODAY
            </h1>

            <a href="/GuillaumeHousing/contact" class="contact-btn">
                Contact Us
            </a>

            <p class="hero-text">
                Find the place where your life fits perfectly.
                Explore homes for rent and buy simple,
                seamless, and stress-free.
            </p>

        </div>

        <!-- RIGHT FORM -->
        <div class="hero-form">

            <h4>NEED HELP?</h4>

            <h2>MESSAGE US</h2>

            <form id="homeContactForm" method="POST" action="/GuillaumeHousing/contact">

                <input type="text" name="name" placeholder="Your Name *" required>

                <input type="email" name="email" placeholder="Your Email *" required>

                <textarea name="message" placeholder="Message" required></textarea>

                <button type="submit">
                    Send Message
                </button>

            </form>

        </div>

    </div>

</section>

<section class="featured-properties">

    <div class="container">

        <p class="featured-subtitle">FEATURED LISTINGS</p>

        <h2 class="featured-title">FIND YOUR PERFECT HOME</h2>

        <div class="title-line"></div>

        <div class="property-grid">

            <!-- PROPERTY 1 -->
            <div class="property-card">

                <div class="property-image">

                    <img src="/GuillaumeHousing/images/p3.jpg" alt="Property">

                    <span class="property-tag rent">
                        FOR RENT
                    </span>

                </div>

                <div class="property-content">

                    <p class="property-type"><i data-lucide="home"></i> Residential</p>

                    <h3>MODERN APARTMENT</h3>

                    <h4>200,000 FCFA /MO</h4>

                    <p class="property-info">
                        3 bd / 2 ba / 1100 Sq Ft
                    </p>

                </div>

            </div>

            <!-- PROPERTY 2 -->
            <div class="property-card">

                <div class="property-image">

                    <img src="/GuillaumeHousing/images/ppp.jpg" alt="Property">

                    <span class="property-tag sale">
                        FOR SALE
                    </span>

                </div>

                <div class="property-content">

                    <p class="property-type"><i data-lucide="home"></i> Residential</p>

                    <h3>FAMILY HOME</h3>

                    <h4>10,000,000 FCFA</h4>

                    <p class="property-info">
                        3 bd / 3 ba / 2700 Sq Ft
                    </p>

                </div>

            </div>

            <!-- PROPERTY 3 -->
            <div class="property-card">

                <div class="property-image">

                    <img src="/GuillaumeHousing/images/p55.jpg" alt="Property">

                    <span class="property-tag rent">
                        FOR RENT
                    </span>

                </div>

                <div class="property-content">

                    <p class="property-type"><i data-lucide="building"></i> Commercial</p>

                    <h3>MODERN APARTMENTS</h3>

                    <h4>150,000 FCFA /MO</h4>

                    <p class="property-info">
                        2 bd / 2 ba / 1450 Sq Ft
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<section class="why-choose-us">
    <div class="container">
        <p class="section-subtitle">WE'RE HERE TO HELP YOU</p>
        <h2>WHAT ARE YOU LOOKING FOR?</h2>
        <div class="title-line"></div>
        
        <div class="features-grid">
            <div class="feature">
                <div class="feature-icon"><i data-lucide="building-2"></i></div>
                <h3>APARTMENTS</h3>
                <p>Explore apartments designed for easy, modern living. From cozy studios to spacious family homes, each space offers comfort, convenience, and a location that supports your lifestyle.</p>
                <a href="/GuillaumeHousing/properties?type=apartment" class="feature-btn">Find Apartments</a>
            </div>
            <div class="feature">
                <div class="feature-icon"><i data-lucide="home"></i></div>
                <h3>HOUSES</h3>
                <p>Find houses that give you privacy, space, and room to grow. Each home is thoughtfully located in peaceful neighborhoods, perfect for families and long-term living.</p>
                <a href="/GuillaumeHousing/properties?type=house" class="feature-btn">Find Houses</a>
            </div>
            <div class="feature">
                <div class="feature-icon"><i data-lucide="building"></i></div>
                <h3>OFFICES</h3>
                <p>Choose offices built to boost productivity and support business growth. From compact setups to larger floors, each space offers accessibility and flexibility.</p>
                <a href="/GuillaumeHousing/properties?type=office" class="feature-btn">Find Offices</a>
            </div>
        </div>
    </div>
</section>
</main>

<?php include 'footer.php'; ?>
