<?php
// User navbar: renders with user controls and correct relative paths under /user
if (session_status() !== PHP_SESSION_ACTIVE && !headers_sent()) {
    session_start();
}

// This variant assumes logged-in user; fall back to session check
$FORCE_NAV_MODE = 'user';
$NAV_IS_LOGGED_IN = !empty($_SESSION['user_id']);

require_once __DIR__ . '/../classes/database.php';

function normalize_user_photo_path_user(?string $raw): ?string {
    if ($raw === null) return null;
    $raw = trim((string)$raw);
    if ($raw === '') return null;
    $raw = str_replace('\\', '/', $raw);
    if (preg_match('~^https?://~i', $raw)) return $raw;
    $root = realpath(__DIR__ . '/..');
    $rel = preg_replace('~^([./\\]+)~', '', $raw);
    $fs = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel);
    if (is_file($fs)) {
        // from /user pages, going up one level not needed; keep relative to repo root
        return '../' . $rel;
    }
    return null;
}

function current_user_avatar_user(): string {
    $default = '../images/logo.png';
    $id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
    if ($id <= 0) return $default;
    if (!empty($_SESSION['user_photo'])) {
        $web = normalize_user_photo_path_user((string)$_SESSION['user_photo']);
        if ($web !== null) return $web;
    }
    try {
        $db = new database();
        $pdo = $db->opencon();
        $stmt = $pdo->prepare('SELECT user_photo FROM users WHERE user_id = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && !empty($row['user_photo'])) {
            $_SESSION['user_photo'] = (string)$row['user_photo'];
            $web = normalize_user_photo_path_user((string)$row['user_photo']);
            if ($web !== null) return $web;
        }
    } catch (Throwable $e) {}
    return $default;
}

function current_user_display_name_user() {
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
            <a href="home" class="flex items-center space-x-3">
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
                <?php $current = strtolower(basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))); ?>
                <?php
                    $links = [
                        ['href' => 'home', 'label' => 'Home', 'key' => 'home'],
                        ['href' => 'menu', 'label' => 'Menu', 'key' => 'menu'],
                        ['href' => 'cateringpackages', 'label' => 'Packages', 'key' => 'cateringpackages'],
                        ['href' => 'booking', 'label' => 'Bookings', 'key' => 'booking'],
                    ];
                ?>
                <?php foreach ($links as $ln): $isActive = ($current === strtolower($ln['key'])); ?>
                    <a href="<?php echo htmlspecialchars($ln['href']); ?>"
                       class="nav-link transition-colors duration-300 relative group <?php echo $isActive ? 'text-amber-400' : ''; ?>">
                        <?php echo htmlspecialchars($ln['label']); ?>
                        <span class="absolute -bottom-1 left-0 h-0.5 bg-amber-400 transition-all duration-300 <?php echo $isActive ? 'w-10' : 'w-0 group-hover:w-full'; ?>"></span>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Auth / Profile -->
            <div class="hidden md:flex items-center space-x-4">
                <!-- Global Cart Button -->
                <button id="nav-cart-btn" class="px-3 py-2 rounded border-2 transition-colors flex items-center gap-2 relative">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="sr-only">Cart</span>
                    <span id="cartBadge" class="hidden absolute -top-2 -right-2 bg-amber-500 text-white rounded-full w-6 h-6 items-center justify-center text-xs font-bold"></span>
                </button>
                <?php if ($NAV_IS_LOGGED_IN): ?>
                    <div class="relative" id="nav-profile">
                        <button id="profile-btn" class="flex items-center gap-2 text-white hover:text-yellow-400 transition-colors">
                            <img src="<?php echo htmlspecialchars(current_user_avatar_user()); ?>" alt="Avatar" class="w-9 h-9 rounded-full object-cover border border-yellow-400/30" />
                            <span class="max-w-[200px] truncate">Welcome, <?php echo htmlspecialchars(current_user_display_name_user()); ?></span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/></svg>
                        </button>
                        <div id="profile-menu" class="absolute right-0 mt-2 w-44 rounded-md bg-white shadow-lg ring-1 ring-black/5 py-1 hidden">
                            <a href="profile" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">Profile</a>
                            <a href="logout" class="block px-3 py-2 text-sm text-rose-600 hover:bg-rose-50">Logout</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a id="login-btn-nav" href="../login" class="px-4 py-2 rounded-full bg-amber-400 text-green-900 hover:bg-amber-300 transition flex items-center gap-2 font-medium">
                        <i class="fas fa-user"></i>
                        Login
                    </a>
                    <a id="signup-btn-nav" href="../registration" class="px-4 py-2 rounded-full border-2 text-white border-white hover:bg-white hover:text-green-900 transition-colors font-medium">Sign up</a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center gap-3">
                <button id="nav-cart-btn-mobile" class="p-2 rounded border-2 text-white border-white hover:bg-white hover:text-green-900 transition-colors relative">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-badge hidden absolute -top-2 -right-2 bg-amber-500 text-white rounded-full w-5 h-5 items-center justify-center text-[10px] font-bold"></span>
                </button>
                <button id="mobile-menu-btn" class="text-white hover:text-yellow-400 transition-colors duration-300">
                <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </nav>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden absolute top-full left-0 right-0 bg-green-900/95 backdrop-blur-sm border-t border-green-700 hidden">
            <div class="container mx-auto px-6 py-4 space-y-4">
                <a href="home" class="block nav-link transition-colors duration-300">Home</a>
                <a href="menu" class="block nav-link transition-colors duration-300">Menu</a>
                <a href="cateringpackages" class="block nav-link transition-colors duration-300">Packages</a>
                <a href="booking" class="block nav-link transition-colors duration-300">Bookings</a>
                <?php if ($NAV_IS_LOGGED_IN): ?>
                    <a href="profile" class="block nav-link transition-colors duration-300">Profile</a>
                    <a href="../logout" class="block nav-link transition-colors duration-300">Logout</a>
                <?php else: ?>
                    <a href="../login" class="block nav-link transition-colors duration-300">Login</a>
                    <a href="../registration" class="block nav-link transition-colors duration-300">Sign up</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<script>
    (function(){
        const navRoot = document.querySelector('header.nav-root');
        const loginBtn = document.getElementById('login-btn-nav');
        const links = Array.from(document.querySelectorAll('.nav-link'));
        const profileBtn = document.getElementById('profile-btn');
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const cartBtns = Array.from(document.querySelectorAll('#nav-cart-btn, #nav-cart-btn-mobile'));

        const setScheme = (solid) => {
            if (!navRoot) return;
            navRoot.classList.toggle('nav-solid', solid);
            if (solid) {
                navRoot.classList.add('bg-white/95','backdrop-blur','border-b','border-green-200','shadow');
                navRoot.classList.remove('bg-transparent','border-transparent','shadow-none');
            } else {
                navRoot.classList.add('bg-transparent');
                navRoot.classList.remove('bg-white/95','border-b','border-green-200','shadow');
            }
            links.forEach(a => {
                a.classList.remove('text-white','text-green-900');
                a.classList.add(solid ? 'text-green-900' : 'text-white');
            });
            if (profileBtn) {
                profileBtn.classList.remove('text-white','hover:text-yellow-400','text-green-900','hover:text-green-700');
                if (solid) {
                    profileBtn.classList.add('text-green-900','hover:text-green-700');
                } else {
                    profileBtn.classList.add('text-white','hover:text-yellow-400');
                }
            }
            if (mobileBtn) {
                mobileBtn.classList.remove('text-white');
                mobileBtn.classList.remove('text-green-900');
                mobileBtn.classList.add(solid ? 'text-green-900' : 'text-white');
            }
            const signupBtn = document.getElementById('signup-btn-nav');
            if (signupBtn) {
                signupBtn.classList.remove('border-white','text-white','hover:bg-white','hover:text-green-900');
                signupBtn.classList.remove('border-green-800','text-green-800','hover:bg-green-800','hover:text-white');
                if (solid) {
                    signupBtn.classList.add('border-green-800','text-green-800','hover:bg-green-800','hover:text-white');
                } else {
                    signupBtn.classList.add('border-white','text-white','hover:bg-white','hover:text-green-900');
                }
            }
            cartBtns.forEach(btn => {
                btn.classList.remove('border-white','text-white','hover:bg-white','hover:text-green-900');
                btn.classList.remove('border-green-800','text-green-800','hover:bg-green-800','hover:text-white');
                if (solid) {
                    btn.classList.add('border-green-800','text-green-800','hover:bg-green-800','hover:text-white');
                } else {
                    btn.classList.add('border-white','text-white','hover:bg-white','hover:text-green-900');
                }
            });
        };

        const targets = Array.from(document.querySelectorAll('[data-nav-contrast="dark"]'));
        if (targets.length && 'IntersectionObserver' in window) {
            const vis = new Map();
            const recompute = () => {
                const anyVisible = Array.from(vis.values()).some(Boolean);
                setScheme(!anyVisible);
            };
            const io = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    const isVisible = entry.isIntersecting && entry.intersectionRatio > 0;
                    vis.set(entry.target, isVisible);
                });
                recompute();
            }, { root: null, threshold: [0, 0.05, 0.1], rootMargin: '-80px 0px 0px 0px' });
            targets.forEach(t => {
                const r = t.getBoundingClientRect();
                const initiallyVisible = r.top <= 80 && r.bottom > 0;
                vis.set(t, initiallyVisible);
                io.observe(t);
            });
            recompute();
        } else {
            const threshold = 140;
            let ticking = false;
            const onScroll = () => {
                if (!ticking) {
                    window.requestAnimationFrame(() => {
                        const solid = window.scrollY > threshold;
                        setScheme(solid);
                        ticking = false;
                    });
                    ticking = true;
                }
            };
            setScheme(window.scrollY > threshold);
            window.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', onScroll);
        }

        const menu = document.getElementById('profile-menu');
        const profileBtnEl = document.getElementById('profile-btn');
        if (profileBtnEl && menu) {
            const toggle = () => menu.classList.toggle('hidden');
            const close = (e) => { if (!menu.contains(e.target) && !profileBtnEl.contains(e.target)) menu.classList.add('hidden'); };
            profileBtnEl.addEventListener('click', (e)=>{ e.stopPropagation(); toggle(); });
            document.addEventListener('click', close);
        }

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
                mobileMenu.classList.toggle('hidden', !isOpen);
                setIcon();
            });
            setIcon();
        }

        const handleCartClick = (e) => {
            e.preventDefault();
            try {
                const hasToggle = typeof window.toggleCart === 'function';
                const hasSidebar = document.getElementById('cartSidebar');
                if (hasToggle && hasSidebar) {
                    window.toggleCart();
                    return;
                }
            } catch (_) {}
            window.location.href = 'menu.php#cart';
        };
        cartBtns.forEach(btn => btn && btn.addEventListener('click', handleCartClick));

        window.SNB_USER = Object.assign({}, window.SNB_USER || {}, { loggedIn: <?php echo $NAV_IS_LOGGED_IN ? 'true' : 'false'; ?> });

        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        }
    })();
</script>
