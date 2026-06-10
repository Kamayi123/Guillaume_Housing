<?php $pageTitle = 'Contact Us - Guillaume Housing'; ?>
<?php include 'header.php'; ?>

<main>
    <section class="contact-header">
        <div class="container">
            <p class="section-subtitle">CONTACT US</p>
            <h1 class="section-title">GET IN TOUCH</h1>
            <div class="title-line"></div>
        </div>
    </section>

    <section class="contact-content">
        <div class="container">
            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success">Message sent successfully! We'll get back to you soon.</div>
            <?php endif; ?>
            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-error">Failed to send message. Please try again.</div>
            <?php endif; ?>
            
            <div class="contact-grid">
                <div class="contact-form-section">
                    <h2>SEND US A MESSAGE</h2>
                    <form id="contactForm" method="POST" action="/GuillaumeHousing/contact">
                        <div class="form-group">
                            <input type="text" id="name" name="name" placeholder="Your Name *" required>
                        </div>

                        <div class="form-group">
                            <input type="email" id="email" name="email" placeholder="Your Email *" required>
                        </div>

                        <div class="form-group">
                            <textarea id="message" name="message" rows="6" placeholder="Message" required></textarea>
                        </div>

                        <button type="submit" class="btn-contact">Send Message</button>
                    </form>
                </div>

                <div class="contact-info-section">
                    <h2>CONTACT INFO</h2>
                    <p class="contact-description">We're here to help with all your buying, renting, and property questions. Reach out anytime!</p>
                    
                    <div class="contact-details">
                        <div class="contact-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                            <span>+237 653901025</span>
                        </div>
                        <div class="contact-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            <span>Catholic university institute of Buea</span>
                        </div>
                        <div class="contact-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
                            <span>kamayiguillaume@gmail.com</span>
                        </div>
                    </div>

                    <div class="social-media-section">
                        <h3>SOCIAL MEDIA</h3>
                        <div class="social-icons">
                            <a href="https://www.facebook.com/tinchom.guillaume" class="social-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                            </a>
                            <a href="#" class="social-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path></svg>
                            </a>
                            <a href="https://www.linkedin.com/in/kamayi-guillaume-6355751a9" class="social-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                            </a>
                            <a href="https://www.instagram.com/kamayi__20/" class="social-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
