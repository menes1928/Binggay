<?php
// Start session early so the shared navbar can read login state (avatar + username)
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Bookings - Sandok ni Binggay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0a2f1f 0%, #1B4332 50%, #2d5a47 100%);
            min-height: 100vh;
            color: #fff;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }
        
        .gold-text {
            color: #D4AF37;
        }
        
        .bg-primary {
            background-color: #1B4332;
        }
        
        .bg-gold {
            background-color: #D4AF37;
        }
        
        .border-gold {
            border-color: #D4AF37;
        }
        
        .hover-gold:hover {
            color: #D4AF37;
        }
        
        /* Navbar Styles */
        .navbar {
            background: rgba(27, 67, 50, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled {
            background: rgba(27, 67, 50, 1);
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.5);
        }
        
        .nav-link {
            position: relative;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: #D4AF37;
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 80%;
        }
        
        /* Hero Section */
        .hero-section {
            margin-top: 80px;
            padding: 100px 0;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                        url('https://images.unsplash.com/photo-1738669469338-801b4e9dbccf?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxlbGVnYW50JTIwd2VkZGluZyUyMHJlY2VwdGlvbnxlbnwxfHx8fDE3NjAxNDEyODZ8MA&ixlib=rb-4.1.0&q=80&w=1080');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
        }
        
        .hero-content {
            animation: fadeInUp 1s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Event Card Styles */
        .event-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            padding: 60px 0;
        }
        
        .event-card {
            position: relative;
            height: 500px;
            border-radius: 25px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid rgba(212, 175, 55, 0.2);
        }
        
        .event-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, transparent 0%, rgba(27, 67, 50, 0.8) 60%, rgba(27, 67, 50, 0.95) 100%);
            z-index: 1;
            transition: all 0.5s ease;
        }
        
        .event-card:hover::before {
            background: linear-gradient(180deg, rgba(212, 175, 55, 0.2) 0%, rgba(27, 67, 50, 0.85) 50%, rgba(27, 67, 50, 1) 100%);
        }
        
        .event-card-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: all 0.5s ease;
        }
        
        .event-card:hover .event-card-image {
            transform: scale(1.15);
        }
        
        .event-card-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 40px 30px;
            z-index: 2;
            transform: translateY(0);
            transition: all 0.5s ease;
        }
        
        .event-card:hover .event-card-content {
            transform: translateY(-10px);
        }
        
        .event-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #D4AF37 0%, #c9a32a 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 30px;
            color: #1B4332;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
        }
        
        .event-card:hover .event-icon {
            transform: rotate(360deg) scale(1.1);
            box-shadow: 0 15px 40px rgba(212, 175, 55, 0.5);
        }
        
        .event-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #fff;
            transition: all 0.3s ease;
        }
        
        .event-card:hover .event-title {
            color: #D4AF37;
        }
        
        .event-description {
            opacity: 0;
            max-height: 0;
            overflow: hidden;
            transition: all 0.5s ease;
            color: #fff;
            line-height: 1.6;
        }
        
        .event-card:hover .event-description {
            opacity: 1;
            max-height: 200px;
            margin-bottom: 20px;
        }
        
        .event-features {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease 0.1s;
        }
        
        .event-card:hover .event-features {
            opacity: 1;
            transform: translateY(0);
        }
        
        .feature-badge {
            display: inline-block;
            padding: 6px 15px;
            background: rgba(212, 175, 55, 0.2);
            border: 1px solid #D4AF37;
            border-radius: 20px;
            font-size: 0.75rem;
            margin-right: 8px;
            margin-bottom: 8px;
            color: #D4AF37;
        }
        
        /* Booking Form Section */
        .booking-form-section {
            background: rgba(27, 67, 50, 0.5);
            backdrop-filter: blur(10px);
            border-radius: 30px;
            padding: 60px;
            margin: 80px auto;
            max-width: 1200px;
            border: 2px solid rgba(212, 175, 55, 0.3);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .form-group {
            margin-bottom: 30px;
            position: relative;
        }
        
        .form-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #D4AF37;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 18px 24px;
            border-radius: 15px;
            border: 2px solid rgba(212, 175, 55, 0.3);
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            font-size: 1rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            outline: none;
        }
        
        .form-input::placeholder,
        .form-textarea::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }
        
        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: #D4AF37;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1),
                        0 10px 30px rgba(212, 175, 55, 0.2);
            transform: translateY(-2px);
        }
        
        .form-input:hover,
        .form-select:hover,
        .form-textarea:hover {
            border-color: rgba(212, 175, 55, 0.6);
            background: rgba(255, 255, 255, 0.08);
        }
        
        /* Input Icon Animation */
        .input-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(212, 175, 55, 0.5);
            transition: all 0.3s ease;
            pointer-events: none;
            margin-top: 18px;
        }
        
        .form-group:focus-within .input-icon {
            color: #D4AF37;
            transform: translateY(-50%) scale(1.2);
        }
        
        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23D4AF37'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.5em 1.5em;
            padding-right: 3rem;
        }
        
        .form-select option {
            background: #1B4332;
            color: #fff;
        }
        
        /* Checkbox Styles */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 15px;
            border: 2px solid rgba(212, 175, 55, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .checkbox-group:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(212, 175, 55, 0.4);
            transform: translateX(5px);
        }
        
        .checkbox-input {
            width: 24px;
            height: 24px;
            accent-color: #D4AF37;
            cursor: pointer;
        }
        
        /* Submit Button */
        .btn-submit {
            background: linear-gradient(135deg, #D4AF37 0%, #c9a32a 100%);
            color: #1B4332;
            padding: 20px 60px;
            border-radius: 50px;
            border: none;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
        }
        
        .btn-submit::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-submit:hover::before {
            width: 400px;
            height: 400px;
        }
        
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 50px rgba(212, 175, 55, 0.5);
        }
        
        .btn-submit:active {
            transform: translateY(-1px);
        }
        
        .btn-submit span {
            position: relative;
            z-index: 1;
        }
        
        /* Stats Section */
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin: 60px 0;
        }
        
        .stat-card {
            text-align: center;
            padding: 30px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(212, 175, 55, 0.2);
            transition: all 0.4s ease;
        }
        
        .stat-card:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: #D4AF37;
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(212, 175, 55, 0.2);
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: #D4AF37;
            font-family: 'Playfair Display', serif;
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
            }
            
            .event-grid {
                grid-template-columns: 1fr;
            }
            
            .booking-form-section {
                padding: 30px 20px;
                margin: 40px 20px;
            }
            
            .btn-submit {
                width: 100%;
            }
        }
        
        /* Scroll Animations */
        .scroll-animate {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }
        
        .scroll-animate.active {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Loading Animation */
        .loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        
        .loading.active {
            display: flex;
        }
        
        .loader {
            width: 60px;
            height: 60px;
            border: 5px solid rgba(212, 175, 55, 0.2);
            border-top: 5px solid #D4AF37;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Loading Overlay -->
    <div class="loading" id="loadingOverlay">
        <div class="loader"></div>
    </div>

    <?php include __DIR__ . '/partials/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section" data-nav-contrast="dark">
        <div class="container mx-auto px-4 text-center hero-content">
            <h1 class="text-5xl md:text-7xl font-bold mb-6">Book Your Event</h1>
            <p class="text-xl md:text-2xl gold-text mb-8">Let us make your celebration unforgettable</p>
            <p class="text-lg max-w-3xl mx-auto opacity-90">
                From intimate gatherings to grand celebrations, we provide exceptional catering services 
                that bring the warmth of home-cooked meals to your special occasions.
            </p>
        </div>
    </section>

    <!-- Small green spacer to keep nav in dark scheme below hero edge -->
    <div class="bg-gradient-to-r from-primary to-green-800 h-4 w-full" data-nav-contrast="dark"></div>

    <!-- Stats Section -->
    <section class="container mx-auto px-4">
        <div class="stats-section scroll-animate">
            <div class="stat-card">
                <div class="stat-number">500+</div>
                <div class="stat-label">Events Catered</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">5000+</div>
                <div class="stat-label">Happy Guests</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">98%</div>
                <div class="stat-label">Satisfaction Rate</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">10+</div>
                <div class="stat-label">Years Experience</div>
            </div>
        </div>
    </section>

    <!-- Event Types Section -->
    <section class="container mx-auto px-4 py-16">
        <h2 class="text-4xl md:text-5xl font-bold text-center mb-4 gold-text scroll-animate">Events We Cater</h2>
        <p class="text-center text-lg mb-12 opacity-90 scroll-animate">Choose from our wide range of event catering services</p>
        
        <div class="event-grid">
            <!-- Birthday Party -->
            <div class="event-card scroll-animate">
                <img src="https://images.unsplash.com/photo-1650584997985-e713a869ee77?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxiaXJ0aGRheSUyMHBhcnR5JTIwY2VsZWJyYXRpb258ZW58MXx8fHwxNzYwMTkyOTY5fDA&ixlib=rb-4.1.0&q=80&w=1080" 
                     alt="Birthday Party" class="event-card-image">
                <div class="event-card-content">
                    <div class="event-icon">
                        <i class="fas fa-birthday-cake"></i>
                    </div>
                    <h3 class="event-title">Birthday Parties</h3>
                    <p class="event-description">
                        Celebrate another year of joy with delicious food that brings everyone together. 
                        Perfect for all ages, from kids' parties to milestone birthdays.
                    </p>
                    <div class="event-features">
                        <span class="feature-badge">Custom Themes</span>
                        <span class="feature-badge">Birthday Cake</span>
                        <span class="feature-badge">Party Setup</span>
                    </div>
                </div>
            </div>

            <!-- Wedding -->
            <div class="event-card scroll-animate">
                <img src="https://images.unsplash.com/photo-1738669469338-801b4e9dbccf?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxlbGVnYW50JTIwd2VkZGluZyUyMHJlY2VwdGlvbnxlbnwxfHx8fDE3NjAxNDEyODZ8MA&ixlib=rb-4.1.0&q=80&w=1080" 
                     alt="Wedding" class="event-card-image">
                <div class="event-card-content">
                    <div class="event-icon">
                        <i class="fas fa-ring"></i>
                    </div>
                    <h3 class="event-title">Weddings</h3>
                    <p class="event-description">
                        Make your special day even more memorable with our premium wedding catering services. 
                        Elegant presentation meets authentic Filipino flavors.
                    </p>
                    <div class="event-features">
                        <span class="feature-badge">Premium Menu</span>
                        <span class="feature-badge">Elegant Setup</span>
                        <span class="feature-badge">Full Service</span>
                    </div>
                </div>
            </div>

            <!-- Debut -->
            <div class="event-card scroll-animate">
                <img src="https://images.unsplash.com/photo-1759866221951-38c624319c98?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxkZWJ1dCUyMHBhcnR5JTIwY2VsZWJyYXRpb258ZW58MXx8fHwxNzYwMjEwMTAxfDA&ixlib=rb-4.1.0&q=80&w=1080" 
                     alt="Debut" class="event-card-image">
                <div class="event-card-content">
                    <div class="event-icon">
                        <i class="fas fa-crown"></i>
                    </div>
                    <h3 class="event-title">Debut (18th Birthday)</h3>
                    <p class="event-description">
                        Celebrate this once-in-a-lifetime milestone with a grand feast. 
                        Sophisticated menus designed for this special coming-of-age celebration.
                    </p>
                    <div class="event-features">
                        <span class="feature-badge">Grand Buffet</span>
                        <span class="feature-badge">Elegant Decor</span>
                        <span class="feature-badge">Photo-worthy</span>
                    </div>
                </div>
            </div>

            <!-- Corporate Events -->
            <div class="event-card scroll-animate">
                <img src="https://images.unsplash.com/photo-1752766074168-44afdbaaf390?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxjb3Jwb3JhdGUlMjBldmVudCUyMGRpbmluZ3xlbnwxfHx8fDE3NjAxMTM3ODN8MA&ixlib=rb-4.1.0&q=80&w=1080" 
                     alt="Corporate Events" class="event-card-image">
                <div class="event-card-content">
                    <div class="event-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <h3 class="event-title">Corporate Events</h3>
                    <p class="event-description">
                        Professional catering for company events, meetings, and team building activities. 
                        Impress your colleagues and clients with quality food service.
                    </p>
                    <div class="event-features">
                        <span class="feature-badge">Professional</span>
                        <span class="feature-badge">Punctual</span>
                        <span class="feature-badge">Reliable</span>
                    </div>
                </div>
            </div>

            <!-- Family Reunions -->
            <div class="event-card scroll-animate">
                <img src="https://images.unsplash.com/photo-1753289595399-341aaf3b1ee1?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxmYW1pbHklMjByZXVuaW9uJTIwZ2F0aGVyaW5nfGVufDF8fHx8MTc2MDIxMDEwMXww&ixlib=rb-4.1.0&q=80&w=1080" 
                     alt="Family Reunions" class="event-card-image">
                <div class="event-card-content">
                    <div class="event-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="event-title">Family Reunions</h3>
                    <p class="event-description">
                        Bring your family together with the comforting taste of home-cooked Filipino meals. 
                        Perfect for large gatherings and multi-generational celebrations.
                    </p>
                    <div class="event-features">
                        <span class="feature-badge">Family Style</span>
                        <span class="feature-badge">Large Groups</span>
                        <span class="feature-badge">Flexible Menu</span>
                    </div>
                </div>
            </div>

            <!-- Anniversary -->
            <div class="event-card scroll-animate">
                <img src="https://images.unsplash.com/photo-1722491634411-09ed1b34eacc?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxhbm5pdmVyc2FyeSUyMGNlbGVicmF0aW9ufGVufDF8fHx8MTc2MDIwMDQ4Nnww&ixlib=rb-4.1.0&q=80&w=1080" 
                     alt="Anniversary" class="event-card-image">
                <div class="event-card-content">
                    <div class="event-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="event-title">Anniversaries</h3>
                    <p class="event-description">
                        Celebrate love and togetherness with a romantic or festive spread. 
                        From intimate dinners to grand celebrations with family and friends.
                    </p>
                    <div class="event-features">
                        <span class="feature-badge">Romantic Setup</span>
                        <span class="feature-badge">Special Menu</span>
                        <span class="feature-badge">Intimate or Grand</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Booking Form Section -->
    <section class="container mx-auto px-4 pb-16">
        <div class="booking-form-section scroll-animate">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold mb-4 gold-text">Book Your Event Now</h2>
                <p class="text-lg opacity-90">Fill out the form below and we'll get back to you within 24 hours</p>
            </div>

            <form id="bookingForm">
                <!-- Personal Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="fullName" class="form-input" placeholder="Juan Dela Cruz" required>
                        <i class="fas fa-user input-icon"></i>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-input" placeholder="juan@email.com" required>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Contact Number *</label>
                        <input type="tel" name="phone" class="form-input" placeholder="09XX XXX XXXX" required>
                        <i class="fas fa-phone input-icon"></i>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Alternative Contact</label>
                        <input type="tel" name="altPhone" class="form-input" placeholder="Optional">
                        <i class="fas fa-phone-alt input-icon"></i>
                    </div>
                </div>

                <!-- Event Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div class="form-group">
                        <label class="form-label">Event Type *</label>
                        <select name="eventType" class="form-select" required>
                            <option value="">Select Event Type</option>
                            <option value="birthday">Birthday Party</option>
                            <option value="wedding">Wedding</option>
                            <option value="debut">Debut (18th Birthday)</option>
                            <option value="corporate">Corporate Event</option>
                            <option value="reunion">Family Reunion</option>
                            <option value="anniversary">Anniversary</option>
                            <option value="christening">Christening/Baptism</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Event Date *</label>
                        <input type="date" name="eventDate" class="form-input" required>
                        <i class="fas fa-calendar input-icon"></i>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Event Time *</label>
                        <input type="time" name="eventTime" class="form-input" required>
                        <i class="fas fa-clock input-icon"></i>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Number of Guests *</label>
                        <select name="guestCount" class="form-select" required>
                            <option value="">Select Number of Guests</option>
                            <option value="50">50 Persons</option>
                            <option value="100">100 Persons</option>
                            <option value="150">150 Persons</option>
                            <option value="200">200 Persons</option>
                            <option value="200+">More than 200 Persons</option>
                        </select>
                    </div>
                </div>

                <!-- Venue Information -->
                <div class="form-group mt-6">
                    <label class="form-label">Venue Address *</label>
                    <input type="text" name="venue" class="form-input" placeholder="Complete event venue address" required>
                    <i class="fas fa-map-marker-alt input-icon"></i>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div class="form-group">
                        <label class="form-label">City/Municipality *</label>
                        <input type="text" name="city" class="form-input" placeholder="e.g., Quezon City" required>
                        <i class="fas fa-city input-icon"></i>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Barangay</label>
                        <input type="text" name="barangay" class="form-input" placeholder="Optional">
                        <i class="fas fa-location-arrow input-icon"></i>
                    </div>
                </div>

                <!-- Package Selection -->
                <div class="form-group mt-6">
                    <label class="form-label">Preferred Package</label>
                    <select name="package" class="form-select">
                        <option value="">Select a package (Optional)</option>
                        <option value="intimate">Intimate Gathering (50 Pax)</option>
                        <option value="classic">Classic Celebration (100 Pax)</option>
                        <option value="grand">Grand Festivity (150 Pax)</option>
                        <option value="ultimate">Ultimate Experience (200 Pax)</option>
                        <option value="custom">Custom Package</option>
                    </select>
                </div>

                <!-- Additional Services -->
                <div class="form-group mt-6">
                    <label class="form-label mb-4">Additional Services</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="checkbox-group">
                            <input type="checkbox" name="services[]" value="tables-chairs" class="checkbox-input">
                            <span><i class="fas fa-chair gold-text mr-2"></i>Tables & Chairs</span>
                        </label>
                        
                        <label class="checkbox-group">
                            <input type="checkbox" name="services[]" value="decorations" class="checkbox-input">
                            <span><i class="fas fa-palette gold-text mr-2"></i>Event Decorations</span>
                        </label>
                        
                        <label class="checkbox-group">
                            <input type="checkbox" name="services[]" value="waiters" class="checkbox-input">
                            <span><i class="fas fa-concierge-bell gold-text mr-2"></i>Extra Waiters</span>
                        </label>
                        
                        <label class="checkbox-group">
                            <input type="checkbox" name="services[]" value="sound-system" class="checkbox-input">
                            <span><i class="fas fa-volume-up gold-text mr-2"></i>Sound System</span>
                        </label>
                    </div>
                </div>

                <!-- Special Requests -->
                <div class="form-group mt-6">
                    <label class="form-label">Menu Preferences & Special Requests</label>
                    <textarea name="specialRequests" class="form-textarea" rows="6" 
                              placeholder="Please let us know about:&#10;- Dietary restrictions or allergies&#10;- Preferred dishes or menu items&#10;- Event theme or color scheme&#10;- Any other special requirements"></textarea>
                </div>

                <!-- Budget Range -->
                <div class="form-group mt-6">
                    <label class="form-label">Budget Range (Optional)</label>
                    <select name="budget" class="form-select">
                        <option value="">Select your budget range</option>
                        <option value="10000-15000">₱10,000 - ₱15,000</option>
                        <option value="15000-25000">₱15,000 - ₱25,000</option>
                        <option value="25000-35000">₱25,000 - ₱35,000</option>
                        <option value="35000-50000">₱35,000 - ₱50,000</option>
                        <option value="50000+">₱50,000 and above</option>
                    </select>
                </div>

                <!-- Terms and Conditions -->
                <div class="form-group mt-8">
                    <label class="checkbox-group">
                        <input type="checkbox" name="terms" class="checkbox-input" required>
                        <span>I agree to the <a href="#" class="gold-text hover:underline">Terms and Conditions</a> and understand that a 50% downpayment is required to confirm my booking.</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-10">
                    <button type="submit" class="btn-submit">
                        <span><i class="fas fa-calendar-check mr-3"></i>Submit Booking Request</span>
                    </button>
                    <p class="mt-4 text-sm opacity-75">We'll review your request and contact you within 24 hours</p>
                </div>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 class="text-2xl font-bold gold-text mb-4">Sandok ni Binggay</h3>
                    <p class="opacity-90">Nothing Beats Home-Cooked Meals</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4 gold-text">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="index.php" class="hover-gold">Home</a></li>
                        <li><a href="menu.php" class="hover-gold">Menu</a></li>
                        <li><a href="packages.php" class="hover-gold">Packages</a></li>
                        <li><a href="bookings.php" class="hover-gold">Book Event</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4 gold-text">Contact</h4>
                    <ul class="space-y-2">
                        <li><i class="fas fa-phone gold-text mr-2"></i>+63 912 345 6789</li>
                        <li><i class="fas fa-envelope gold-text mr-2"></i>info@sandoknibinggay.com</li>
                        <li><i class="fas fa-map-marker-alt gold-text mr-2"></i>Metro Manila, Philippines</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4 gold-text">Follow Us</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-2xl hover-gold"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-2xl hover-gold"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-2xl hover-gold"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/20 pt-6 text-center">
                <p class="opacity-80">&copy; 2025 Sandok ni Binggay. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Navbar is controlled by shared partial (contrast via data-nav-contrast)
        // Scroll Animation
        const scrollElements = document.querySelectorAll('.scroll-animate');
        
        const elementInView = (el, offset = 100) => {
            const elementTop = el.getBoundingClientRect().top;
            return (
                elementTop <= 
                (window.innerHeight || document.documentElement.clientHeight) - offset
            );
        };

        const displayScrollElement = (element) => {
            element.classList.add('active');
        };

        const handleScrollAnimation = () => {
            scrollElements.forEach((el) => {
                if (elementInView(el, 100)) {
                    displayScrollElement(el);
                }
            });
        };

        window.addEventListener('scroll', handleScrollAnimation);
        handleScrollAnimation(); // Initial check

        // Form Validation and Submission
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading overlay
            document.getElementById('loadingOverlay').classList.add('active');
            
            // Get form data
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            // Get selected services
            const services = [];
            document.querySelectorAll('input[name="services[]"]:checked').forEach(checkbox => {
                services.push(checkbox.value);
            });
            data.services = services;
            
            // Simulate form submission (replace with actual backend call)
            setTimeout(() => {
                console.log('Booking submitted:', data);
                
                // Hide loading overlay
                document.getElementById('loadingOverlay').classList.remove('active');
                
                // Show success message
                alert('🎉 Thank you for your booking request!\n\nWe have received your information and will contact you within 24 hours to confirm the details and discuss your event requirements.\n\nA confirmation email has been sent to ' + data.email);
                
                // Reset form
                this.reset();
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }, 2000);
        });

        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.querySelector('input[name="eventDate"]').setAttribute('min', today);

        // Input animation effects
        const formInputs = document.querySelectorAll('.form-input, .form-select, .form-textarea');
        
        formInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('.form-label').style.transform = 'translateY(-3px)';
                this.parentElement.querySelector('.form-label').style.color = '#D4AF37';
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.querySelector('.form-label').style.transform = 'translateY(0)';
                    this.parentElement.querySelector('.form-label').style.color = '#D4AF37';
                }
            });
        });

        // Event type change handler
        document.querySelector('select[name="eventType"]').addEventListener('change', function() {
            const customPackages = {
                'birthday': 'We recommend our Classic Celebration package for birthdays!',
                'wedding': 'Our Ultimate Experience package is perfect for weddings!',
                'debut': 'The Grand Festivity package is ideal for debut celebrations!',
                'corporate': 'Professional catering packages available for corporate events!',
                'reunion': 'Family-style packages perfect for reunions!',
                'anniversary': 'Romantic and elegant packages for anniversaries!'
            };
            
            if (customPackages[this.value]) {
                console.log(customPackages[this.value]);
            }
        });
    </script>
</body>
</html>
