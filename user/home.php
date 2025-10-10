<?php
// Collections data
$collections = [
    [
        'id' => 1,
        'category' => 'TABLE SETTING EXPERIENCES',
        'title' => 'Elegant Spreads',
        'image' => 'https://images.unsplash.com/photo-1758810744035-c88d4225870c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxlbGVnYW50JTIwdGFibGUlMjBzZXR0aW5nJTIwd2VkZGluZ3xlbnwxfHx8fDE3NTk1OTI1MTJ8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
        'hasFrame' => false
    ],
    [
        'id' => 2,
        'category' => 'BEAUTIFULLY CRAFTED PRESENTATIONS',
        'title' => 'Artisan Displays',
        'image' => 'https://images.unsplash.com/photo-1695290242164-e595e411424c?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxhcnRpc2FuJTIwZm9vZCUyMHByZXNlbnRhdGlvbiUyMHBsYXRpbmd8ZW58MXx8fHwxNzU5NTkyNTE2fDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
        'hasFrame' => true
    ],
    [
        'id' => 3,
        'category' => 'LOVE ON A PLATE',
        'title' => 'Wedding Collections',
        'image' => 'https://images.unsplash.com/photo-1558535299-1fc041f8a331?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx3ZWRkaW5nJTIwZGVzc2VydCUyMGRvbnV0cyUyMHBhc3RyaWVzfGVufDF8fHx8MTc1OTU5MjUxOHww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
        'hasFrame' => false
    ],
    [
        'id' => 4,
        'category' => 'ELEVATED SEASONAL INSPIRATIONS',
        'title' => 'Seasonal Delights',
        'image' => 'https://images.unsplash.com/photo-1743793055775-3c07ab847ad0?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxlbGVnYW50JTIwZGluaW5nJTIwcm9vbSUyMHJlc3RhdXJhbnR8ZW58MXx8fHwxNzU5NTkyNTIxfDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
        'hasFrame' => false
    ],
    [
        'id' => 5,
        'category' => 'SIGNATURE FILIPINO CLASSICS',
        'title' => 'Traditional Favorites',
        'image' => 'https://images.unsplash.com/photo-1606525575548-2d62ed40291d?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxGaWxpcGlubyUyMGZvb2QlMjBkaXNoZXMlMjBhZG9ib3xlbnwxfHx8fDE3NTk1OTIzMzZ8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
        'hasFrame' => false
    ],
    [
        'id' => 6,
        'category' => 'SWEET CELEBRATIONS',
        'title' => 'Dessert Paradise',
        'image' => 'https://images.unsplash.com/photo-1705234384751-84081009588e?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxGaWxpcGlubyUyMGhhbG8lMjBoYWxvJTIwZGVzc2VydHxlbnwxfHx8fDE3NTk1OTIzMzh8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
        'hasFrame' => false
    ]
];

// Triple the collections for seamless infinite scroll
$duplicatedCollections = array_merge($collections, $collections, $collections);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sandok ni Binggay - Filipino Catering Services</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .font-serif {
            font-family: 'Playfair Display', serif;
        }
        
        /* Custom animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(10px);
            }
        }
        
        @keyframes slide-left {
            from {
                transform: translateX(0);
            }
            to {
                transform: translateX(-2400px);
            }
        }
        
        .animate-slide-in {
            animation: slideIn 0.8s ease-out;
        }
        
        .animate-slide-up {
            animation: slideUp 0.8s ease-out;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        
        .animate-bounce-slow {
            animation: bounce 1.5s ease-in-out infinite;
        }
        
        .collections-slider {
            animation: slide-left 40s linear infinite;
        }
        
        .collections-slider.paused {
            animation-play-state: paused;
        }
        
        /* Custom utility classes */
        .bg-gradient-radial {
            background: radial-gradient(circle at 30% 40%, rgba(0,0,0,0.3), transparent 70%);
        }
        
        .text-shadow {
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        /* Intersection Observer animations */
        .fade-in-element {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }
        
        .fade-in-element.visible {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Hover effects */
        .hover-lift {
            transition: transform 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-8px);
        }
        
        .hover-scale {
            transition: transform 0.3s ease;
        }
        
        .hover-scale:hover {
            transform: scale(1.05);
        }
        
        /* Collection card styles */
        .collection-card {
            transition: all 0.3s ease;
        }
        
        .collection-card:hover {
            transform: scale(1.02);
        }
        
        .corner-lines {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .collection-card:hover .corner-lines,
        .collection-card.has-frame .corner-lines {
            opacity: 1;
        }
        
        .image-overlay {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .collection-card:hover .image-overlay {
            opacity: 1;
        }
        
        .text-content {
            transition: all 0.3s ease;
        }
        
        .collection-card:hover .text-content {
            transform: translateY(-5px);
        }
        
        .collection-card:hover .text-title {
            color: #FDE047;
            transform: scale(1.05);
        }
        
        .yellow-border {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .collection-card:hover .yellow-border {
            opacity: 1;
        }
        
        /* Legacy section styles */
        .legacy-card {
            transition: all 0.3s ease;
        }
        
        .legacy-card:hover {
            transform: translateY(-8px);
        }
        
        .legacy-diamond {
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .legacy-card:hover .legacy-diamond {
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            transform: rotate(45deg) scale(1.1);
        }
        
        /* Statistics section styles */
        .stats-card {
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        /* Menu section styles */
        .menu-card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .menu-card:hover {
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            transform: translateY(-8px);
        }
        
        /* Counter animation */
        .counter {
            transition: all 0.3s ease;
        }
        /* Hero plus pattern and navbar styles for elegant/classy look */
        .hero-plus-pattern {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='32' height='32' viewBox='0 0 32 32'%3E%3Cpath fill='rgba(255,255,255,0.12)' d='M15 6h2v20h-2zM6 15h20v2H6z'/%3E%3C/svg%3E");
            background-size: 32px 32px;
        }
        .nav-root {
            transition: background-color .3s ease, box-shadow .3s ease, border-color .3s ease;
            backdrop-filter: saturate(120%) blur(2px);
        }
        .nav-hero {
            background: linear-gradient(90deg, rgba(6,78,59,0.85), rgba(6,78,59,0.65)); /* similar to hero */
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .nav-hero::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='28' height='28' viewBox='0 0 28 28'%3E%3Cpath fill='rgba(255,255,255,0.08)' d='M13 6h2v16h-2zM6 13h16v2H6z'/%3E%3C/svg%3E");
            opacity: .25;
        }
        .nav-clear {
            background: transparent;
            border-bottom-color: transparent;
            box-shadow: none;
        }
    .nav-link { color: #ffffff; text-shadow: 0 1px 2px rgba(0,0,0,0.35); }
    .nav-link:hover { color: #fde047; }
    /* Transparent over hero: white links with gold hover */
    .nav-clear .nav-link { color: #ffffff; }
    .nav-clear .nav-link:hover { color: #fde047; }
    /* After hero: subtle off-white bar and green links */
    .nav-solid { background: rgba(255,255,255,0.92); border-bottom: 1px solid rgba(0,0,0,0.06); box-shadow: 0 6px 24px rgba(0,0,0,0.08); }
    .nav-solid .nav-link { color: #065f46; text-shadow: none; }
    .nav-solid .nav-link:hover { color: #064e3b; }
    #mobile-menu .nav-link { color: #ffffff; }
    #mobile-menu .nav-link:hover { color: #fde047; }
    </style>

</head>
<body class="min-h-screen bg-gradient-to-b from-green-800 via-green-900 to-green-950">
    <!-- Header -->
    <?php include __DIR__ . '/partials/navbar.php'; ?>

    <!-- Hero Section -->
    <section id="home" class="min-h-screen flex items-center relative overflow-hidden">
        <!-- Background Effects -->
        <div class="absolute inset-0 bg-gradient-to-br from-green-800 via-green-900 to-green-950"></div>
        <div class="absolute inset-0 bg-gradient-radial"></div>
        <!-- Plus pattern overlay to match reference background -->
        <div class="absolute inset-0 hero-plus-pattern opacity-25"></div>
        
        <div class="container max-w-7xl mx-auto px-6 lg:px-12 pt-28 relative z-10">
            <div class="grid lg:grid-cols-2 gap-10 items-center">
                <!-- Left Content -->
                <div class="space-y-8 fade-in-element" data-delay="0">
                    <!-- Decorative Line -->
                    <div class="w-20 h-1 bg-yellow-400" id="decorative-line"></div>

                    <!-- Main Heading -->
                    <div>
                        <h1 class="text-6xl lg:text-7xl xl:text-8xl text-yellow-400 font-serif leading-tight fade-in-element" data-delay="200">
                            Sandok ni Binggay
                        </h1>
                        
                        <div class="mt-6 space-y-2 fade-in-element" data-delay="400">
                            <p class="text-white text-xl">Where Every Dish Tells a Story of</p>
                            <p class="text-yellow-300 text-xl italic">Authentic Filipino Hospitality</p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-4 fade-in-element" data-delay="600">
                        <p class="text-gray-300 leading-relaxed max-w-lg">
                            Experience the warmth of home-cooked Filipino cuisine, crafted with passion and 
                            served with love for your most cherished celebrations.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-4 fade-in-element" data-delay="800">
                        <button class="bg-yellow-400 text-green-900 px-8 py-4 font-semibold tracking-wide hover:bg-yellow-300 transition-all duration-300 transform hover:scale-105 hover:shadow-xl group">
                            <span class="relative">
                                BOOK YOUR EVENT
                                <span class="absolute inset-0 bg-yellow-300 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left -z-10"></span>
                            </span>
                        </button>
                        <button class="border-2 border-white text-white px-8 py-4 font-semibold tracking-wide hover:bg-white hover:text-green-900 transition-all duration-300 transform hover:scale-105 group">
                            <span class="relative">
                                VIEW MENU
                                <span class="absolute inset-0 bg-white transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left -z-10"></span>
                            </span>
                        </button>
                    </div>

                    <!-- Contact Info -->
                    <div class="flex flex-col sm:flex-row gap-6 pt-8 fade-in-element" data-delay="1000">
                        <div class="flex items-center space-x-3 text-yellow-300 hover:text-yellow-400 transition-colors duration-300 group cursor-pointer">
                            <i data-lucide="phone" class="w-5 h-5 group-hover:scale-110 transition-transform duration-300"></i>
                            <span>0919-230-8344</span>
                        </div>
                        <div class="flex items-center space-x-3 text-yellow-300 hover:text-yellow-400 transition-colors duration-300 group cursor-pointer">
                            <i data-lucide="mail" class="w-5 h-5 group-hover:scale-110 transition-transform duration-300"></i>
                            <span>riabrimfood@gmail.com</span>
                        </div>
                    </div>
                </div>

                <!-- Right Content - Circular Image -->
                <div class="flex justify-center lg:justify-end fade-in-element" data-delay="500">
                    <div class="relative">
                        <!-- Corner Brackets -->
                        <div class="absolute -top-8 -left-8 w-16 h-16">
                            <div class="w-8 h-1 bg-yellow-400"></div>
                            <div class="w-1 h-8 bg-yellow-400"></div>
                        </div>
                        <div class="absolute -top-8 -right-8 w-16 h-16">
                            <div class="absolute top-0 right-0 w-8 h-1 bg-yellow-400"></div>
                            <div class="absolute top-0 right-0 w-1 h-8 bg-yellow-400"></div>
                        </div>
                        <div class="absolute -bottom-8 -left-8 w-16 h-16 flex flex-col justify-end">
                            <div class="w-1 h-8 bg-yellow-400"></div>
                            <div class="w-8 h-1 bg-yellow-400"></div>
                        </div>
                        <div class="absolute -bottom-8 -right-8 w-16 h-16 flex flex-col justify-end items-end">
                            <div class="w-1 h-8 bg-yellow-400"></div>
                            <div class="w-8 h-1 bg-yellow-400"></div>
                        </div>

                        <!-- Glowing Background Circle -->
                        <div class="absolute inset-0 bg-gradient-to-br from-yellow-400/20 to-orange-500/20 rounded-full blur-xl scale-110"></div>
                        
                        <!-- Main Circle (Real Logo, Larger) -->
                        <div class="relative w-96 h-96 lg:w-[28rem] lg:h-[28rem] rounded-full overflow-hidden border-4 border-yellow-400/30 shadow-2xl hover-scale bg-white/5">
                            <img src="../images/logo.png" alt="Sandok ni Binggay Logo" class="w-full h-full object-contain" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 text-center fade-in-element" data-delay="1200">
            <p class="text-gray-400 text-sm tracking-widest mb-2">SCROLL</p>
            <div class="animate-bounce-slow">
                <i data-lucide="chevron-down" class="w-6 h-6 text-yellow-400 mx-auto"></i>
            </div>
            <div class="w-px h-16 bg-gradient-to-b from-yellow-400 to-transparent mx-auto mt-2"></div>
        </div>
    </section>

    <!-- Our Legacy Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-6">
            <!-- Header -->
            <div class="text-center mb-16 fade-in-element">
                <div class="inline-flex items-center space-x-2 mb-4">
                    <div class="w-8 h-px bg-yellow-600"></div>
                    <div class="w-2 h-2 bg-yellow-600 rotate-45"></div>
                    <div class="w-8 h-px bg-yellow-600"></div>
                </div>
                <h2 class="text-5xl lg:text-6xl text-green-800 font-serif mb-6 fade-in-element" data-delay="200">
                    Our Legacy
                </h2>
                <p class="text-gray-600 text-lg max-w-3xl mx-auto leading-relaxed fade-in-element" data-delay="400">
                    At Sandok ni Binggay, we honor the timeless traditions of Filipino cuisine, bringing the warmth of 
                    family gatherings and the joy of shared meals to your celebrations. Each dish is a testament to our 
                    commitment to excellence and authentic flavors.
                </p>
            </div>

            <!-- Legacy Features -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Crafted with Passion -->
                <div class="text-center group legacy-card fade-in-element" data-delay="0">
                    <div class="relative mb-6">
                        <div class="w-24 h-24 bg-green-700 rotate-45 mx-auto flex items-center justify-center group-hover:bg-green-600 transition-colors duration-300 legacy-diamond">
                            <div class="-rotate-45">
                                <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-green-800 mb-3 group-hover:text-green-600 transition-colors duration-300">
                        Crafted with Passion
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        Every dish embodies the heart and soul of authentic Filipino cooking traditions.
                    </p>
                </div>

                <!-- Premium Quality -->
                <div class="text-center group legacy-card fade-in-element" data-delay="200">
                    <div class="relative mb-6">
                        <div class="w-24 h-24 bg-green-700 rotate-45 mx-auto flex items-center justify-center group-hover:bg-green-600 transition-colors duration-300 legacy-diamond">
                            <div class="-rotate-45">
                                <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M7 4V2C7 1.45 7.45 1 8 1H16C16.55 1 17 1.45 17 2V4H20C20.55 4 21 4.45 21 5S20.55 6 20 6H19V19C19 20.1 18.1 21 17 21H7C5.9 21 5 20.1 5 19V6H4C3.45 6 3 5.55 3 5S3.45 4 4 4H7ZM9 3V4H15V3H9ZM7 6V19H17V6H7ZM9 8V17H11V8H9ZM13 8V17H15V8H13Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-green-800 mb-3 group-hover:text-green-600 transition-colors duration-300">
                        Premium Quality
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        Only the finest ingredients, sourced with care and prepared to perfection.
                    </p>
                </div>

                <!-- Gracious Service -->
                <div class="text-center group legacy-card fade-in-element" data-delay="400">
                    <div class="relative mb-6">
                        <div class="w-24 h-24 bg-green-700 rotate-45 mx-auto flex items-center justify-center group-hover:bg-green-600 transition-colors duration-300 legacy-diamond">
                            <div class="-rotate-45">
                                <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 7V9C15 10.1 15.9 11 17 11V20C17 21.1 16.1 22 15 22H9C7.9 22 7 21.1 7 20V11C8.1 11 9 10.1 9 9V7H3V9C3 10.1 3.9 11 5 11V20C5 21.1 5.9 22 7 22H17C18.1 22 19 21.1 19 20V11C20.1 11 21 10.1 21 9Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-green-800 mb-3 group-hover:text-green-600 transition-colors duration-300">
                        Gracious Service
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        Attentive, professional hospitality that makes every event extraordinary.
                    </p>
                </div>

                <!-- Time-Honored Recipes -->
                <div class="text-center group legacy-card fade-in-element" data-delay="600">
                    <div class="relative mb-6">
                        <div class="w-24 h-24 bg-green-700 rotate-45 mx-auto flex items-center justify-center group-hover:bg-green-600 transition-colors duration-300 legacy-diamond">
                            <div class="-rotate-45">
                                <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M16.2,16.2L11,13V7H12.5V12.2L17,14.9L16.2,16.2Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-xl font-semibold text-green-800 mb-3 group-hover:text-green-600 transition-colors duration-300">
                        Time-Honored Recipes
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                        Generations of culinary wisdom in every lovingly prepared meal.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-16 relative">
        <div class="absolute inset-0 bg-green-700" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 35px, rgba(255,255,255,.1) 35px, rgba(255,255,255,.1) 70px);"></div>
        <div class="container mx-auto px-6 relative z-10">
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Memorable Events -->
                <div class="text-center group stats-card fade-in-element" data-delay="0">
                    <div class="mb-4 transform group-hover:scale-110 transition-transform duration-300">
                        <span class="text-6xl lg:text-7xl text-yellow-400 font-serif counter" data-target="500">0</span>
                        <span class="text-6xl lg:text-7xl text-yellow-400 font-serif">+</span>
                    </div>
                    <h3 class="text-xl text-white font-medium">Memorable Events</h3>
                    <div class="w-16 h-px bg-yellow-400 mx-auto mt-3 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
                </div>

                <!-- Delighted Guests -->
                <div class="text-center group stats-card fade-in-element" data-delay="200">
                    <div class="mb-4 transform group-hover:scale-110 transition-transform duration-300">
                        <span class="text-6xl lg:text-7xl text-yellow-400 font-serif counter" data-target="1000">0</span>
                        <span class="text-6xl lg:text-7xl text-yellow-400 font-serif">+</span>
                    </div>
                    <h3 class="text-xl text-white font-medium">Delighted Guests</h3>
                    <div class="w-16 h-px bg-yellow-400 mx-auto mt-3 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
                </div>

                <!-- Years of Excellence -->
                <div class="text-center group stats-card fade-in-element" data-delay="400">
                    <div class="mb-4 transform group-hover:scale-110 transition-transform duration-300">
                        <span class="text-6xl lg:text-7xl text-yellow-400 font-serif counter" data-target="15">0</span>
                        <span class="text-6xl lg:text-7xl text-yellow-400 font-serif">+</span>
                    </div>
                    <h3 class="text-xl text-white font-medium">Years of Excellence</h3>
                    <div class="w-16 h-px bg-yellow-400 mx-auto mt-3 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Menu Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <!-- Header -->
            <div class="text-center mb-16 fade-in-element">
                <div class="inline-flex items-center space-x-2 mb-4">
                    <div class="w-8 h-px bg-yellow-600"></div>
                    <div class="w-2 h-2 bg-yellow-600 rotate-45"></div>
                    <div class="w-8 h-px bg-yellow-600"></div>
                </div>
                <h2 class="text-5xl lg:text-6xl text-green-800 font-serif mb-6 fade-in-element" data-delay="200">
                    Our Menu
                </h2>
                <p class="text-gray-600 text-lg max-w-3xl mx-auto leading-relaxed fade-in-element" data-delay="400">
                    Discover our refined selection of catering services, each designed to elevate your 
                    celebration with impeccable taste and presentation.
                </p>
            </div>

            <!-- Menu Grid -->
            <div class="grid lg:grid-cols-2 gap-8">
                <!-- Intimate Celebrations -->
                <div class="relative group menu-card cursor-pointer fade-in-element" data-delay="0">
                    <div class="relative h-80 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1555939594-58d7cb561ad1?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxlbGVnYW50JTIwcGxhdGluZyUyMGZpbmUlMjBkaW5pbmd8ZW58MXx8fHwxNzU5NTkyNTI2fDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral" 
                             alt="Intimate Celebrations" 
                             class="w-full h-full object-cover transition-all duration-500 group-hover:scale-110">
                        
                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                        
                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-green-800/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <!-- Content -->
                        <div class="absolute bottom-0 left-0 right-0 p-8">
                            <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                <p class="text-yellow-400 text-sm tracking-widest uppercase mb-2 opacity-80">
                                    Intimate Celebrations
                                </p>
                                <h3 class="text-3xl lg:text-4xl text-white font-serif leading-tight mb-4">
                                    Private Dining
                                </h3>
                                <p class="text-gray-200 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-200">
                                    Exquisite culinary experiences crafted for your most special moments and intimate gatherings.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Corner Brackets -->
                        <div class="absolute top-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">
                            <div class="w-8 h-px bg-yellow-400"></div>
                            <div class="w-px h-8 bg-yellow-400"></div>
                        </div>
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">
                            <div class="w-8 h-px bg-yellow-400 ml-auto"></div>
                            <div class="w-px h-8 bg-yellow-400 ml-auto"></div>
                        </div>
                    </div>
                </div>

                <!-- Traditional Elegance -->
                <div class="relative group menu-card cursor-pointer fade-in-element" data-delay="200">
                    <div class="relative h-80 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx0cmFkaXRpb25hbCUyMGZvb2QlMjBwbGF0dGVyJTIwY2hlZXNlJTIwY3JhY2tlcnN8ZW58MXx8fHwxNzU5NTkyNTI4fDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral" 
                             alt="Traditional Elegance" 
                             class="w-full h-full object-cover transition-all duration-500 group-hover:scale-110">
                        
                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                        
                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-green-800/40 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <!-- Content -->
                        <div class="absolute bottom-0 left-0 right-0 p-8">
                            <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                <p class="text-yellow-400 text-sm tracking-widest uppercase mb-2 opacity-80">
                                    Traditional Elegance
                                </p>
                                <h3 class="text-3xl lg:text-4xl text-white font-serif leading-tight mb-4">
                                    Banquet Menus
                                </h3>
                                <p class="text-gray-200 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-200">
                                    Time-honored Filipino flavors presented with modern sophistication for grand celebrations.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Corner Brackets -->
                        <div class="absolute top-4 left-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">
                            <div class="w-8 h-px bg-yellow-400"></div>
                            <div class="w-px h-8 bg-yellow-400"></div>
                        </div>
                        <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">
                            <div class="w-8 h-px bg-yellow-400 ml-auto"></div>
                            <div class="w-px h-8 bg-yellow-400 ml-auto"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu CTA -->
            <div class="text-center mt-16 fade-in-element" data-delay="400">
                <button class="bg-green-800 text-white px-10 py-4 font-semibold tracking-wider hover:bg-green-700 transition-all duration-300 transform hover:scale-105 hover:shadow-xl group">
                    <span class="relative">
                        VIEW COMPLETE MENU
                        <span class="absolute inset-0 bg-green-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left -z-10"></span>
                    </span>
                </button>
            </div>
        </div>
    </section>

    <!-- Collections Section -->
    <section class="py-20 bg-gradient-to-b from-white to-gray-50 overflow-hidden">
        <div class="container mx-auto px-6 mb-12">
            <!-- Header -->
            <div class="text-center mb-16 fade-in-element">
                <h2 class="text-5xl lg:text-6xl text-green-800 font-serif mb-6 fade-in-element" data-delay="200">
                    Our Collections
                </h2>
                <p class="text-gray-600 text-lg max-w-3xl mx-auto leading-relaxed fade-in-element" data-delay="400">
                    Explore our curated gallery of culinary masterpieces, each collection thoughtfully designed to make your event unforgettable.
                </p>
                
                <!-- Hover to Pause Text -->
                <div class="mt-8 fade-in-element" data-delay="600">
                    <div class="inline-flex items-center space-x-2">
                        <div class="w-8 h-px bg-yellow-600"></div>
                        <span class="text-yellow-600 text-sm tracking-widest uppercase">HOVER TO PAUSE</span>
                        <div class="w-8 h-px bg-yellow-600"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Continuous Sliding Gallery -->
        <div id="collections-gallery" class="relative">
            <!-- Gradient Overlays -->
            <div class="absolute left-0 top-0 bottom-0 w-32 bg-gradient-to-r from-gray-50 to-transparent z-10"></div>
            <div class="absolute right-0 top-0 bottom-0 w-32 bg-gradient-to-l from-gray-50 to-transparent z-10"></div>

            <div id="collections-slider" class="flex space-x-8 collections-slider" style="width: fit-content;">
                <?php foreach ($duplicatedCollections as $index => $collection): ?>
                    <div class="flex-shrink-0 w-80 h-96 collection-card cursor-pointer relative <?php echo $collection['hasFrame'] ? 'has-frame' : ''; ?>" 
                         data-card-id="<?php echo $collection['id'] . '-' . $index; ?>">
                        <div class="relative w-full h-full overflow-hidden">
                            <!-- Corner Lines -->
                            <div class="corner-lines">
                                <!-- Top Left Corner -->
                                <div class="absolute top-4 left-4 z-30">
                                    <div class="w-8 h-px bg-yellow-400"></div>
                                    <div class="w-px h-8 bg-yellow-400"></div>
                                </div>
                                <!-- Top Right Corner -->
                                <div class="absolute top-4 right-4 z-30">
                                    <div class="w-8 h-px bg-yellow-400 ml-auto"></div>
                                    <div class="w-px h-8 bg-yellow-400 ml-auto"></div>
                                </div>
                                <!-- Bottom Left Corner -->
                                <div class="absolute bottom-4 left-4 z-30">
                                    <div class="w-px h-8 bg-yellow-400"></div>
                                    <div class="w-8 h-px bg-yellow-400"></div>
                                </div>
                                <!-- Bottom Right Corner -->
                                <div class="absolute bottom-4 right-4 z-30">
                                    <div class="w-px h-8 bg-yellow-400 ml-auto"></div>
                                    <div class="w-8 h-px bg-yellow-400 ml-auto"></div>
                                </div>
                            </div>

                            <!-- Main Image -->
                            <img src="<?php echo $collection['image']; ?>" 
                                 alt="<?php echo $collection['title']; ?>" 
                                 class="w-full h-full object-cover transition-all duration-500 collection-image">

                            <!-- Base Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                            <!-- Additional Dark Overlay on Hover -->
                            <div class="absolute inset-0 bg-black/30 image-overlay"></div>

                            <!-- Yellow Border on Hover -->
                            <div class="absolute inset-0 border-2 border-yellow-400 yellow-border"></div>

                            <!-- Text Content -->
                            <div class="absolute bottom-0 left-0 right-0 p-6 z-20">
                                <div class="text-white text-content">
                                    <p class="text-yellow-400 text-xs tracking-widest uppercase mb-2 opacity-80 text-category">
                                        <?php echo $collection['category']; ?>
                                    </p>
                                    <h3 class="text-2xl lg:text-3xl font-serif leading-tight text-title">
                                        <?php echo $collection['title']; ?>
                                    </h3>
                                </div>
                            </div>

                            <!-- Subtle Glow Effect on Hover -->
                            <div class="absolute inset-0 bg-yellow-400/10 image-overlay"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Bottom CTA -->
        <div class="container mx-auto px-6">
            <div class="text-center mt-16 fade-in-element" data-delay="800">
                <button class="bg-green-800 text-white px-10 py-4 font-semibold tracking-wider hover:bg-green-700 transition-all duration-300 transform hover:scale-105 hover:shadow-xl group">
                    <span class="relative">
                        EXPLORE ALL COLLECTIONS
                        <span class="absolute inset-0 bg-green-700 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left -z-10"></span>
                    </span>
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-green-950 border-t border-green-800">
        <div class="container mx-auto px-6 py-16">
            <div class="grid md:grid-cols-3 gap-12">
                <!-- Company Info -->
                <div class="fade-in-element">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">S</span>
                        </div>
                        <div>
                            <h3 class="text-yellow-400 text-xl font-semibold">Sandok ni Binggay</h3>
                            <p class="text-yellow-300 text-sm tracking-wider">CATERING SERVICES</p>
                        </div>
                    </div>
                    <p class="text-gray-300 leading-relaxed mb-6">
                        Bringing authentic Filipino flavors to your special occasions with 
                        passion, tradition, and exceptional service.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-yellow-400 transition-colors duration-300 transform hover:scale-110">
                            <i data-lucide="facebook" class="w-6 h-6"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-yellow-400 transition-colors duration-300 transform hover:scale-110">
                            <i data-lucide="instagram" class="w-6 h-6"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-yellow-400 transition-colors duration-300 transform hover:scale-110">
                            <i data-lucide="twitter" class="w-6 h-6"></i>
                        </a>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="fade-in-element" data-delay="200">
                    <h3 class="text-yellow-400 text-xl font-semibold mb-6">Contact Us</h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 text-gray-300 hover:text-yellow-400 transition-colors duration-300 group cursor-pointer">
                            <i data-lucide="phone" class="w-5 h-5 group-hover:scale-110 transition-transform duration-300"></i>
                            <span>0919-230-8344</span>
                        </div>
                        <div class="flex items-center space-x-3 text-gray-300 hover:text-yellow-400 transition-colors duration-300 group cursor-pointer">
                            <i data-lucide="mail" class="w-5 h-5 group-hover:scale-110 transition-transform duration-300"></i>
                            <span>riabrimfood@gmail.com</span>
                        </div>
                        <div class="flex items-start space-x-3 text-gray-300 hover:text-yellow-400 transition-colors duration-300 group cursor-pointer">
                            <i data-lucide="map-pin" class="w-5 h-5 mt-1 group-hover:scale-110 transition-transform duration-300"></i>
                            <span>Metro Manila, Philippines</span>
                        </div>
                    </div>
                </div>

                <!-- Services -->
                <div class="fade-in-element" data-delay="400">
                    <h3 class="text-yellow-400 text-xl font-semibold mb-6">Our Services</h3>
                    <div class="space-y-3">
                        <a href="#" class="block text-gray-300 hover:text-yellow-400 transition-colors duration-300 hover:translate-x-2 transform">
                            Wedding Catering
                        </a>
                        <a href="#" class="block text-gray-300 hover:text-yellow-400 transition-colors duration-300 hover:translate-x-2 transform">
                            Corporate Events
                        </a>
                        <a href="#" class="block text-gray-300 hover:text-yellow-400 transition-colors duration-300 hover:translate-x-2 transform">
                            Birthday Parties
                        </a>
                        <a href="#" class="block text-gray-300 hover:text-yellow-400 transition-colors duration-300 hover:translate-x-2 transform">
                            Special Occasions
                        </a>
                        <a href="#" class="block text-gray-300 hover:text-yellow-400 transition-colors duration-300 hover:translate-x-2 transform">
                            Custom Menus
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bottom Section -->
            <div class="border-t border-green-800 mt-12 pt-8 text-center fade-in-element" data-delay="600">
                <p class="text-gray-400">
                    © 2024 Sandok ni Binggay Catering Services. All rights reserved.
                </p>
                <p class="text-gray-500 text-sm mt-2">
                    Made with ❤️ for authentic Filipino cuisine
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // Navbar switches: transparent over hero (nav-clear) -> off-white after hero (nav-solid)
        (function() {
            const navRoot = document.querySelector('header.nav-root');
            const heroSection = document.getElementById('home');
            if (!navRoot || !heroSection) return;
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        navRoot.classList.add('nav-clear');
                        navRoot.classList.remove('nav-solid');
                    } else {
                        navRoot.classList.remove('nav-clear');
                        navRoot.classList.add('nav-solid');
                    }
                });
            }, { rootMargin: '-88px 0px 0px 0px', threshold: 0 });
            observer.observe(heroSection);
        })();

        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        let isMobileMenuOpen = false;

        mobileMenuBtn.addEventListener('click', function() {
            isMobileMenuOpen = !isMobileMenuOpen;
            
            if (isMobileMenuOpen) {
                mobileMenu.classList.remove('hidden');
                mobileMenuBtn.innerHTML = '<i data-lucide="x" class="w-6 h-6"></i>';
            } else {
                mobileMenu.classList.add('hidden');
                mobileMenuBtn.innerHTML = '<i data-lucide="menu" class="w-6 h-6"></i>';
            }
            
            lucide.createIcons();
        });

        // Collections gallery functionality
        const collectionsGallery = document.getElementById('collections-gallery');
        const collectionsSlider = document.getElementById('collections-slider');
        const collectionCards = document.querySelectorAll('.collection-card');
        let isPaused = false;
        let hoveredCard = null;

        // Gallery hover functionality
        collectionsGallery.addEventListener('mouseenter', function() {
            isPaused = true;
            collectionsSlider.classList.add('paused');
        });

        collectionsGallery.addEventListener('mouseleave', function() {
            isPaused = false;
            hoveredCard = null;
            collectionsSlider.classList.remove('paused');
            
            // Reset all cards
            collectionCards.forEach(card => {
                card.classList.remove('hovered');
                const image = card.querySelector('.collection-image');
                if (image) {
                    image.style.filter = 'brightness(1)';
                }
            });
        });

        // Individual card hover functionality
        collectionCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                const cardId = this.getAttribute('data-card-id');
                hoveredCard = cardId;
                
                // Reset all cards first
                collectionCards.forEach(c => c.classList.remove('hovered'));
                
                // Add hover state to current card
                this.classList.add('hovered');
                
                // Darken the image
                const image = this.querySelector('.collection-image');
                if (image) {
                    image.style.filter = 'brightness(0.7)';
                }
            });

            card.addEventListener('mouseleave', function() {
                if (hoveredCard === this.getAttribute('data-card-id')) {
                    hoveredCard = null;
                    this.classList.remove('hovered');
                    
                    const image = this.querySelector('.collection-image');
                    if (image) {
                        image.style.filter = 'brightness(1)';
                    }
                }
            });
        });

        // Intersection Observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const delay = entry.target.getAttribute('data-delay') || 0;
                    
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, delay);
                    
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe all fade-in elements
        document.querySelectorAll('.fade-in-element').forEach(element => {
            observer.observe(element);
        });

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
                
                // Close mobile menu if open
                if (isMobileMenuOpen) {
                    mobileMenu.classList.add('hidden');
                    isMobileMenuOpen = false;
                    mobileMenuBtn.innerHTML = '<i data-lucide="menu" class="w-6 h-6"></i>';
                    lucide.createIcons();
                }
            });
        });

        // Animate decorative line on load
        window.addEventListener('load', function() {
            const decorativeLine = document.getElementById('decorative-line');
            if (decorativeLine) {
                decorativeLine.style.width = '0';
                setTimeout(() => {
                    decorativeLine.style.transition = 'width 0.8s ease-out';
                    decorativeLine.style.width = '80px';
                }, 300);
            }
        });

        // Counter Animation
        function animateCounter(element, target, duration = 2000) {
            let start = 0;
            const increment = target / (duration / 16);
            
            function updateCounter() {
                start += increment;
                if (start < target) {
                    element.textContent = Math.floor(start);
                    requestAnimationFrame(updateCounter);
                } else {
                    element.textContent = target;
                }
            }
            
            updateCounter();
        }

        // Stats counter observer
        const statsObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target.querySelector('.counter');
                    if (counter && !counter.classList.contains('animated')) {
                        const target = parseInt(counter.getAttribute('data-target'));
                        counter.classList.add('animated');
                        
                        // Delay the animation slightly for effect
                        setTimeout(() => {
                            animateCounter(counter, target);
                        }, 500);
                    }
                    
                    statsObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.5
        });

        // Observe stats cards
        document.querySelectorAll('.stats-card').forEach(card => {
            statsObserver.observe(card);
        });

        // Enhanced legacy card hover effects
        document.querySelectorAll('.legacy-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                const diamond = this.querySelector('.legacy-diamond');
                if (diamond) {
                    diamond.style.transform = 'rotate(45deg) scale(1.1)';
                }
            });

            card.addEventListener('mouseleave', function() {
                const diamond = this.querySelector('.legacy-diamond');
                if (diamond) {
                    diamond.style.transform = 'rotate(45deg) scale(1)';
                }
            });
        });

        // Menu card hover effects with enhanced interactivity
        document.querySelectorAll('.menu-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                const img = this.querySelector('img');
                if (img) {
                    img.style.transform = 'scale(1.1)';
                }
            });

            card.addEventListener('mouseleave', function() {
                const img = this.querySelector('img');
                if (img) {
                    img.style.transform = 'scale(1)';
                }
            });

            // Add click effect
            card.addEventListener('click', function() {
                // Add a subtle flash effect
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'translateY(-8px)';
                }, 100);
            });
        });

        // Parallax effect for statistics section
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const statsSection = document.querySelector('.stats-card').closest('section');
            
            if (statsSection) {
                const rect = statsSection.getBoundingClientRect();
                if (rect.top < window.innerHeight && rect.bottom > 0) {
                    const parallax = scrolled * 0.1;
                    statsSection.style.backgroundPosition = `center ${parallax}px`;
                }
            }
        });

        // Add stagger effect to legacy cards on scroll
        const legacyObserver = new IntersectionObserver(function(entries) {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.style.transform = 'translateY(0)';
                        entry.target.style.opacity = '1';
                    }, index * 200);
                    
                    legacyObserver.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.3
        });

        // Initially hide legacy cards for stagger effect
        document.querySelectorAll('.legacy-card').forEach((card, index) => {
            if (index > 0) { // Keep first card visible for immediate impact
                card.style.transform = 'translateY(30px)';
                card.style.opacity = '0';
                card.style.transition = 'all 0.6s ease';
                legacyObserver.observe(card);
            }
        });
    </script>
</body>
</html>