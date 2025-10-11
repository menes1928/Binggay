<?php

// Helper: resolve profile image
function current_user_avatar() {
    $default = '../images/logo.png';
    $id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
    if ($id <= 0) return $default;
    // Prefer explicit session photo if present and exists
    if (!empty($_SESSION['user_photo'])) {
        $p = (string)$_SESSION['user_photo'];
        $path = __DIR__ . '/../../' . ltrim($p, '/');
        if (file_exists($path)) {
            // Make it relative from /user/* pages
            return (strpos($p, '../') === 0 || strpos($p, './') === 0) ? $p : '../' . ltrim($p, '/');
        }
    }
    // Fallback: find latest profile image in /profiles
    $glob = glob(__DIR__ . '/../../profiles/user_' . $id . '_*.{png,jpg,jpeg,webp}', GLOB_BRACE);
    if ($glob && count($glob) > 0) {
        usort($glob, function($a, $b){ return filemtime($b) <=> filemtime($a); });
        $fname = basename($glob[0]);
        return '../profiles/' . $fname;
    }
    return $default;
}

function current_user_display_name() {
    if (!empty($_SESSION['user_username'])) return (string)$_SESSION['user_username'];
    $fn = isset($_SESSION['user_fn']) ? trim((string)$_SESSION['user_fn']) : '';
    $ln = isset($_SESSION['user_ln']) ? trim((string)$_SESSION['user_ln']) : '';
    $name = trim($fn . ' ' . $ln);
    if ($name !== '') return $name;
    if (!empty($_SESSION['user_email'])) return (string)$_SESSION['user_email'];
    return 'Account';
}
?>

<header class="nav-root nav-clear fixed top-0 left-0 right-0 z-50">
    <div class="container mx-auto px-6 py-4">
        <nav class="flex items-center justify-between">
            <!-- Logo -->
            <a href="home.php" class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="../images/logo.png" alt="Sandok ni Binggay Logo" class="w-10 h-10 object-contain" />
                </div>
                <div>
                    <h1 class="text-yellow-400 text-lg font-semibold">Sandok ni Binggay</h1>
                    <p class="text-yellow-300 text-xs tracking-wider">CATERING SERVICES</p>
                </div>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="home.php#home" class="nav-link transition-colors duration-300 relative group">
                    Home
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-yellow-400 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="menu.php" class="nav-link transition-colors duration-300 relative group">
                    Menu
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-yellow-400 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="cateringpackages.php" class="nav-link transition-colors duration-300 relative group">
                    Packages
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-yellow-400 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="booking.php" class="nav-link transition-colors duration-300 relative group">
                    Bookings
                    <span class="absolute -bottom-1 left-0 w-0 h-0.5 bg-yellow-400 transition-all duration-300 group-hover:w-full"></span>
                </a>
            </div>

            <!-- Auth / Profile -->
            <div class="hidden md:flex items-center space-x-4">
                <?php if (!empty($_SESSION['user_id'])): ?>
                    <div class="relative" id="nav-profile">
                        <button id="profile-btn" class="flex items-center gap-2 text-white hover:text-yellow-400 transition-colors">
                            <img src="<?php echo htmlspecialchars(current_user_avatar()); ?>" alt="Avatar" class="w-9 h-9 rounded-full object-cover border border-yellow-400/30" />
                            <span class="max-w-[160px] truncate">
                                <?php echo htmlspecialchars(current_user_display_name()); ?>
                            </span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
                        </button>
                        <div id="profile-menu" class="absolute right-0 mt-2 w-44 rounded-md bg-white shadow-lg ring-1 ring-black/5 py-1 hidden">
                            <a href="profile.php" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                            <a href="../logout.php" class="block px-3 py-2 text-sm text-rose-600 hover:bg-rose-50">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a id="login-btn-nav" href="../login.php" class="px-4 py-2 rounded border-2 border-white text-white hover:bg-white hover:text-green-900 transition-colors">Login</a>
                    <a href="../registration.php" class="px-4 py-2 rounded bg-yellow-400 text-green-900 hover:bg-yellow-300 transition">Sign up</a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden text-white hover:text-yellow-400 transition-colors duration-300">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </nav>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden absolute top-full left-0 right-0 bg-green-900/95 backdrop-blur-sm border-t border-green-700 hidden">
            <div class="container mx-auto px-6 py-4 space-y-4">
                <a href="home.php#home" class="block nav-link transition-colors duration-300">Home</a>
                <a href="menu.php" class="block nav-link transition-colors duration-300">Menu</a>
                <a href="cateringpackages.php" class="block nav-link transition-colors duration-300">Packages</a>
                <a href="booking.php" class="block nav-link transition-colors duration-300">Bookings</a>
                <?php if (!empty($_SESSION['user_id'])): ?>
                    <a href="profile.php" class="block nav-link transition-colors duration-300">Profile</a>
                    <a href="../logout.php" class="block nav-link transition-colors duration-300">Logout</a>
                <?php else: ?>
                    <a href="../login.php" class="block nav-link transition-colors duration-300">Login</a>
                    <a href="../registration.php" class="block nav-link transition-colors duration-300">Sign up</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<script>
    // Navbar behaviors (self-contained)
    (function(){
        // Sync Login button color with nav state (clear vs solid)
        const navRoot = document.querySelector('header.nav-root');
        const loginBtn = document.getElementById('login-btn-nav');
        const setLoginStyle = () => {
            if (!loginBtn || !navRoot) return;
            const solid = navRoot.classList.contains('nav-solid');
            loginBtn.classList.remove('border-white','text-white','hover:bg-white','hover:text-green-900');
            loginBtn.classList.remove('border-green-800','text-green-800','hover:bg-green-800','hover:text-white');
            if (solid) {
                loginBtn.classList.add('border-green-800','text-green-800','hover:bg-green-800','hover:text-white');
            } else {
                loginBtn.classList.add('border-white','text-white','hover:bg-white','hover:text-green-900');
            }
        };
        // Observe header class changes (works with page-level IntersectionObserver)
        if (navRoot) {
            setLoginStyle();
            const mo = new MutationObserver(setLoginStyle);
            mo.observe(navRoot, { attributes: true, attributeFilter: ['class'] });
        }

        // Profile dropdown toggle
        const btn = document.getElementById('profile-btn');
        const menu = document.getElementById('profile-menu');
        if (btn && menu) {
            const toggle = () => menu.classList.toggle('hidden');
            const close = (e) => { if (!menu.contains(e.target) && !btn.contains(e.target)) menu.classList.add('hidden'); };
            btn.addEventListener('click', (e)=>{ e.stopPropagation(); toggle(); });
            document.addEventListener('click', close);
        }

        // Mobile menu toggle
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        let isOpen = false;
        const setIcon = () => {
            if (!mobileBtn) return;
            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                mobileBtn.innerHTML = isOpen ? '<i data-lucide="x" class="w-6 h-6"></i>' : '<i data-lucide="menu" class="w-6 h-6"></i>';
                window.lucide.createIcons();
            } else {
                mobileBtn.textContent = isOpen ? 'Close' : 'Menu';
            }
        };
        if (mobileBtn && mobileMenu) {
            mobileBtn.addEventListener('click', function(){
                isOpen = !isOpen;
                if (isOpen) {
                    mobileMenu.classList.remove('hidden');
                } else {
                    mobileMenu.classList.add('hidden');
                }
                setIcon();
            });
            // Initialize icon on load
            setIcon();
        }

        // Initialize lucide icons if available on page
        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        }
    })();
</script>
