<?php
// You can add PHP logic here for authentication, database connections, etc.
session_start();

// Sample data arrays (in a real application, these would come from a database)
$salesData = [
    ['month' => 'Jan', 'revenue' => 45000, 'orders' => 156],
    ['month' => 'Feb', 'revenue' => 52000, 'orders' => 189],
    ['month' => 'Mar', 'revenue' => 48000, 'orders' => 167],
    ['month' => 'Apr', 'revenue' => 61000, 'orders' => 221],
    ['month' => 'May', 'revenue' => 55000, 'orders' => 198],
    ['month' => 'Jun', 'revenue' => 67000, 'orders' => 245]
];

$recentOrders = [
    ['id' => 'ORD-001', 'customer' => 'Maria Santos', 'items' => 'Party Tray for 50', 'amount' => '₱8,500', 'status' => 'Completed', 'time' => '2h ago'],
    ['id' => 'ORD-002', 'customer' => 'Juan Dela Cruz', 'items' => 'Wedding Catering Package', 'amount' => '₱25,000', 'status' => 'Preparing', 'time' => '4h ago'],
    ['id' => 'ORD-003', 'customer' => 'Ana Reyes', 'items' => 'Corporate Lunch', 'amount' => '₱12,000', 'status' => 'Delivered', 'time' => '6h ago'],
    ['id' => 'ORD-004', 'customer' => 'Carlos Martinez', 'items' => 'Birthday Package', 'amount' => '₱6,500', 'status' => 'Pending', 'time' => '8h ago']
];

$upcomingBookings = [
    ['event' => 'Corporate Event', 'client' => 'ABC Company', 'date' => 'Oct 8, 2025', 'guests' => 120, 'package' => 'Premium'],
    ['event' => 'Wedding Reception', 'client' => 'Smith Family', 'date' => 'Oct 10, 2025', 'guests' => 200, 'package' => 'Deluxe'],
    ['event' => 'Birthday Party', 'client' => 'Johnson Family', 'date' => 'Oct 12, 2025', 'guests' => 50, 'package' => 'Standard']
];

// Early action handling before any output (prevents header issues)
require_once __DIR__ . '/../classes/database.php';
$db = new database();
$sectionEarly = $_GET['section'] ?? '';
$sectionEarly = is_string($sectionEarly) ? strtolower($sectionEarly) : '';
if ($sectionEarly === 'products') {
    $action = $_GET['action'] ?? '';
    $mid = isset($_GET['menu_id']) ? (int)$_GET['menu_id'] : 0;
    // Allow POST for AJAX actions
    if (!$action) { $action = $_POST['action'] ?? ''; }
    if ($mid <= 0 && isset($_POST['menu_id'])) { $mid = (int)$_POST['menu_id']; }
    $isAjaxAction = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
        || (isset($_POST['ajax']) && $_POST['ajax'] == '1')
        || (isset($_GET['ajax']) && $_GET['ajax'] == '1');
    if ($action && $mid > 0) {
        if ($action === 'get_menu' && $isAjaxAction) {
            header('Content-Type: application/json');
            try {
                $row = $db->viewMenuID($mid);
                if (!$row) { echo json_encode(['success'=>false,'message'=>'Menu not found']); exit; }
                // Return only fields needed for editing
                echo json_encode([
                    'success' => true,
                    'data' => [
                        'menu_id' => (int)($row['menu_id'] ?? $mid),
                        'menu_name' => (string)($row['menu_name'] ?? ''),
                        'menu_desc' => (string)($row['menu_desc'] ?? ''),
                        'menu_pax' => (string)($row['menu_pax'] ?? ''),
                        'menu_price' => (float)($row['menu_price'] ?? 0),
                        'menu_avail' => (int)($row['menu_avail'] ?? 1),
                        'menu_pic' => (string)($row['menu_pic'] ?? '')
                    ]
                ]);
            } catch (Throwable $e) {
                echo json_encode(['success'=>false,'message'=>'Error fetching menu']);
            }
            exit;
        }
        if ($action === 'update' && $isAjaxAction) {
            header('Content-Type: application/json');
            try {
                $name = trim((string)($_POST['name'] ?? ''));
                $desc = trim((string)($_POST['description'] ?? ''));
                $paxVal = trim((string)($_POST['pax'] ?? ''));
                $price = (float)($_POST['price'] ?? 0);
                $availVal = (string)($_POST['availability'] ?? '1');
                $availInt = ($availVal === '0' || $availVal === 0) ? 0 : 1;
                if ($name === '') { echo json_encode(['success'=>false,'message'=>'Name is required']); exit; }
                if ($price < 0) { echo json_encode(['success'=>false,'message'=>'Price must be non-negative']); exit; }
                $newPicName = null;
                if (!empty($_FILES['photo']) && is_uploaded_file($_FILES['photo']['tmp_name'])) {
                    $orig = $_FILES['photo']['name'];
                    $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
                    $allowed = ['jpg','jpeg','png','webp','gif','avif'];
                    if (!in_array($ext, $allowed)) { echo json_encode(['success'=>false,'message'=>'Unsupported image type']); exit; }
                    $newPicName = uniqid('menu_', true) . '.' . $ext;
                    $destDir = realpath(__DIR__ . '/../menu');
                    if ($destDir === false) { $destDir = __DIR__ . '/../menu'; }
                    @mkdir($destDir, 0775, true);
                    $destPath = rtrim($destDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $newPicName;
                    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $destPath)) {
                        echo json_encode(['success'=>false,'message'=>'Failed to save uploaded image']); exit;
                    }
                }
                // Try to use database layer update method; fall back to partial updates if not available
                $ok = false;
                if (method_exists($db, 'updateMenu')) {
                    try {
                        // Common signature guess: (id, name, desc, pax, price, avail, pic|null)
                        $ok = $db->updateMenu($mid, $name, $desc, $paxVal, $price, $availInt, $newPicName);
                    } catch (Throwable $e) { $ok = false; }
                }
                if (!$ok) {
                    // Try more granular updates if available
                    $ok = true;
                    try { if (method_exists($db, 'updateMenuName')) $db->updateMenuName($mid, $name); } catch (Throwable $e) { $ok = false; }
                    try { if (method_exists($db, 'updateMenuDesc')) $db->updateMenuDesc($mid, $desc); } catch (Throwable $e) {}
                    try { if (method_exists($db, 'updateMenuPax')) $db->updateMenuPax($mid, $paxVal); } catch (Throwable $e) {}
                    try { if (method_exists($db, 'updateMenuPrice')) $db->updateMenuPrice($mid, $price); } catch (Throwable $e) {}
                    try { if (method_exists($db, 'setMenuAvailability')) $db->setMenuAvailability($mid, $availInt); } catch (Throwable $e) {}
                    try { if ($newPicName && method_exists($db, 'updateMenuPic')) $db->updateMenuPic($mid, $newPicName); } catch (Throwable $e) {}
                }
                if ($ok) { echo json_encode(['success'=>true]); } else { echo json_encode(['success'=>false,'message'=>'Update not supported by database layer']); }
            } catch (Throwable $e) {
                echo json_encode(['success'=>false,'message'=>'Error updating menu']);
            }
            exit;
        }
        if ($action === 'toggle') {
            try {
                $current = $db->viewMenuID($mid);
                if ($current) {
                    $newAvail = ((int)$current['menu_avail'] === 1) ? 0 : 1;
                    $db->setMenuAvailability($mid, $newAvail);
                }
            } catch (Throwable $e) {}
        } elseif ($action === 'archive' || $action === 'delete') {
            try { $db->archiveMenu($mid); } catch (Throwable $e) {}
        }
        if ($isAjaxAction) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        } else {
            // After actions, return to Products with filters cleared
            header('Location: ?section=products');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sandok ni Binggay - Admin Dashboard</title>
    <?php
        // Load DB and prepare data for Products Management
        require_once __DIR__ . '/../classes/database.php';
        $db = new database();

    // Query params
    $section = $_GET['section'] ?? '';
    $section = is_string($section) ? strtolower($section) : '';
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
    $category = isset($_GET['category']) && $_GET['category'] !== '' ? (int)$_GET['category'] : null;
    // PAX filter (optional): '6-8', '10-15', or 'per' (matches any "* pieces")
    $pax = isset($_GET['pax']) && $_GET['pax'] !== '' ? (string)$_GET['pax'] : null;
        $avail = isset($_GET['avail']) && $_GET['avail'] !== '' ? $_GET['avail'] : null; // expects '1' or '0'
        $sort = isset($_GET['sort']) && $_GET['sort'] !== '' ? $_GET['sort'] : null;
        $page = max(1, (int)($_GET['page'] ?? 1));
        // Reset filters on full reload (non-AJAX request)
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        if (($section === 'products') && !$isAjax) {
            $q = '';
            $category = null;
            $pax = null;
            $avail = null;
            $sort = null;
            $page = 1;
        }
        $limit = 10;
        $offset = ($page - 1) * $limit;

    // Defaults; only hydrate data for the requested section to speed up initial load
    $categories = [];
    $totalCount = 0; $menus = []; $totalPages = 1;
    $projTotalCount = 0; $projMenus = []; $projTotalPages = 1; $projPage = 1; // ensure defaults to avoid undefined notices

        if ($section === 'products') {
            try { $categories = $db->viewCategories(); } catch (Throwable $e) { $categories = []; }
            try {
                // When PAX filter is active, switch to an in-PHP filtering path so counts/pagination remain correct
                if ($pax !== null && $pax !== '') {
                    $all = [];
                    if (method_exists($db, 'getFilteredMenuOOP')) {
                        // Get all records for current category/availability with desired sort
                        $all = $db->getFilteredMenuOOP($category, $avail, $sort);
                    } elseif (method_exists($db, 'getFilteredMenuPaged')) {
                        // Fallback: fetch a large page and filter client-side (works for typical dataset sizes)
                        $all = $db->getFilteredMenuPaged($category, $avail, $sort, 10000, 0, null);
                    }
                    // Apply search filter (name) if any
                    if ($q !== '') {
                        $needle = mb_strtolower($q);
                        $all = array_values(array_filter($all, function($row) use ($needle) {
                            return stripos($row['menu_name'] ?? '', $needle) !== false;
                        }));
                    }
                    // Apply PAX filter: exact for 6-8 / 10-15, substring 'pieces' for per pieces
                    $paxFilter = mb_strtolower($pax);
                    $all = array_values(array_filter($all, function($row) use ($paxFilter) {
                        $val = mb_strtolower((string)($row['menu_pax'] ?? ''));
                        if ($paxFilter === 'per') {
                            return $val !== '' && strpos($val, 'pieces') !== false; // matches 'N pieces' or 'per pieces'
                        }
                        return $val === $paxFilter; // matches '6-8' or '10-15'
                    }));
                    $totalCount = count($all);
                    $menus = array_slice($all, $offset, $limit);
                } else {
                    if (method_exists($db, 'countFilteredMenu') && method_exists($db, 'getFilteredMenuPaged')) {
                        $totalCount = $db->countFilteredMenu($category, $avail, $q);
                        $menus = $db->getFilteredMenuPaged($category, $avail, $sort, $limit, $offset, $q);
                    } else {
                        $all = $db->getFilteredMenuOOP($category, $avail, $sort);
                        if ($q !== '') {
                            $all = array_values(array_filter($all, function($row) use ($q) {
                                return stripos($row['menu_name'] ?? '', $q) !== false;
                            }));
                        }
                        $totalCount = count($all);
                        $menus = array_slice($all, $offset, $limit);
                    }
                }
            } catch (Throwable $e) {
                $totalCount = 0; $menus = [];
            }
            $totalPages = max(1, (int)ceil($totalCount / $limit));
        } elseif ($section === 'projects') {
            $projPage = max(1, (int)($_GET['proj_page'] ?? 1));
            $projLimit = 10;
            $projOffset = ($projPage - 1) * $projLimit;
            try {
                // Updated signatures: countFilteredMenu(category_id, avail, q) and getFilteredMenuPaged(category_id, avail, sort, limit, offset, q)
                $projTotalCount = $db->countFilteredMenu(null, null, null);
                $projMenus = $db->getFilteredMenuPaged(null, null, null, $projLimit, $projOffset, null);
            } catch (Throwable $e) {
                $projTotalCount = 0; $projMenus = [];
            }
            $projTotalPages = max(1, (int)ceil($projTotalCount / $projLimit));
        }
        // Helper to build query preserving filters
        function build_query($overrides = []) {
            $params = $_GET;
            $params['section'] = 'products';
            foreach ($overrides as $k => $v) {
                if ($v === null) {
                    unset($params[$k]);
                } else {
                    $params[$k] = $v;
                }
            }
            return http_build_query($params);
        }
        function menu_img_src($menu_pic) {
            $menu_pic = (string)$menu_pic;
            if ($menu_pic === '') return null;
            if (str_starts_with($menu_pic, 'http') || str_contains($menu_pic, '/')) return $menu_pic;
            return '../menu/' . $menu_pic;
        }
    ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- App icons: use site logo for favicon/PWA tiles -->
    <link rel="icon" type="image/png" sizes="32x32" href="../images/logo.png">
    <link rel="icon" type="image/png" sizes="192x192" href="../images/logo.png">
    <link rel="apple-touch-icon" href="../images/logo.png">
    <link rel="shortcut icon" href="../images/logo.png">
    <meta name="theme-color" content="#1B4332">
    <meta name="msapplication-TileImage" content="../images/logo.png">
    <meta name="msapplication-TileColor" content="#1B4332">
    <style>
        :root {
            --font-size: 16px;
            --background: #fefffe;
            --foreground: #1a2e1a;
            --card: #ffffff;
            --card-foreground: #1a2e1a;
            --popover: #ffffff;
            --popover-foreground: #1a2e1a;
            --primary: #1B4332;
            --primary-foreground: #ffffff;
            --secondary: #F4F3F0;
            --secondary-foreground: #1a2e1a;
            --muted: #f8f8f6;
            --muted-foreground: #6b7062;
            --accent: #D4AF37;
            --accent-foreground: #1a2e1a;
            --destructive: #d4183d;
            --destructive-foreground: #ffffff;
            --border: rgba(27, 67, 50, 0.1);
            --input: transparent;
            --input-background: #f8f8f6;
            --switch-background: #d1d5db;
            --font-weight-medium: 500;
            --font-weight-normal: 400;
            --ring: #1B4332;
            --chart-1: #1B4332;
            --chart-2: #D4AF37;
            --chart-3: #2D5A3D;
            --chart-4: #E8C547;
            --chart-5: #95A890;
            --radius: 0.625rem;
            --sidebar: #1B4332;
            --sidebar-foreground: #ffffff;
            --sidebar-primary: #D4AF37;
            --sidebar-primary-foreground: #1a2e1a;
            --sidebar-accent: #2D5A3D;
            --sidebar-accent-foreground: #ffffff;
            --sidebar-border: rgba(255, 255, 255, 0.1);
            --sidebar-ring: #D4AF37;
        }

        body {
            background-color: var(--background);
            color: var(--foreground);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .sidebar {
            background-color: var(--sidebar);
            color: var(--sidebar-foreground);
            border-right: 1px solid var(--sidebar-border);
        }

        .sidebar-collapsed {
            width: 4rem;
        }

        .sidebar-expanded {
            width: 16rem;
        }

        .nav-item {
            transition: all 200ms ease;
        }

        .nav-item:hover {
            background-color: var(--sidebar-accent);
            transform: scale(1.05);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .nav-item.active {
            background-color: var(--sidebar-primary);
            color: var(--sidebar-primary-foreground);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        /* Collapsed sidebar: center icons and make active/hover highlight a square behind icon */
        .sidebar-collapsed .nav-item {
            justify-content: center;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
            padding-top: 0.25rem;  /* tighter vertical padding */
            padding-bottom: 0.25rem;
            transform: none; /* disable scale effect for cleaner look */
        }

        /* Reduce the vertical gap between items when collapsed (override space-y-2) */
        .sidebar-collapsed nav > * + * {
            margin-top: 0.25rem !important; /* 4px gap between icons */
        }

        .sidebar-collapsed .nav-item .sidebar-text {
            display: none !important;
        }

        /* Base square size for icons in collapsed state (keeps layout from jumping) */
        .sidebar-collapsed .nav-item i {
            width: 2.25rem; /* 36px */
            height: 2.25rem;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0; /* prevent offset */
            transition: background-color 150ms ease, color 150ms ease;
        }

        /* Remove row-wide active background in collapsed state */
        .sidebar-collapsed .nav-item.active {
            background-color: transparent;
            box-shadow: none;
        }

        /* Highlight the icon itself when active in collapsed state */
        .sidebar-collapsed .nav-item.active i {
            background-color: var(--sidebar-primary);
            color: var(--sidebar-primary-foreground);
        }

        /* Optional: subtle hover state focuses the icon only */
        .sidebar-collapsed .nav-item:hover {
            background-color: transparent;
            box-shadow: none;
        }

        .sidebar-collapsed .nav-item:hover i {
            background-color: var(--sidebar-accent);
            color: var(--sidebar-accent-foreground);
        }

        /* Keyboard focus mirrors the active look for clarity */
        .sidebar-collapsed .nav-item:focus-visible i {
            background-color: var(--sidebar-primary);
            color: var(--sidebar-primary-foreground);
            outline: none;
        }

        .card {
            background-color: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
        }

        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transition: box-shadow 200ms ease;
        }

        .text-primary { color: var(--primary); }
        .text-muted-foreground { color: var(--muted-foreground); }
        .bg-primary { background-color: var(--primary); }
        .bg-accent { background-color: var(--accent); }
        .border-primary { border-color: var(--primary); }
        .border-accent { border-color: var(--accent); }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
        }

        .hidden { display: none; }
        .block { display: block; }
        /* Smooth fade during results refresh */
        #products-results { transition: opacity 150ms ease; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#1B4332',
                        'primary-foreground': '#ffffff',
                        'accent': '#D4AF37',
                        'accent-foreground': '#1a2e1a',
                        'muted': '#f8f8f6',
                        'muted-foreground': '#6b7062',
                        'sidebar': '#1B4332',
                        'sidebar-foreground': '#ffffff',
                        'sidebar-primary': '#D4AF37',
                        'sidebar-accent': '#2D5A3D',
                        'chart-3': '#2D5A3D',
                        'chart-4': '#E8C547'
                    }
                }
            }
        }
    </script>
</head>
<body class="h-screen overflow-hidden">
    <div class="flex h-full">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar sidebar-expanded flex flex-col transition-all duration-300">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-sidebar-border">
                <div id="sidebar-header" class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-sidebar-primary rounded-lg flex items-center justify-center">
                        <i class="fas fa-utensils text-sidebar-primary-foreground text-xl"></i>
                    </div>
                    <div class="sidebar-text">
                        <div class="font-semibold">Sandok ni Binggay</div>
                        <div class="text-xs opacity-80">Admin Panel</div>
                    </div>
                </div>
                <button id="sidebar-toggle" class="text-sidebar-foreground hover:bg-sidebar-accent p-1 h-8 w-8 rounded transition-colors">
                    <i id="sidebar-icon" class="fas fa-times text-sm"></i>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-2">
                <a class="nav-item w-full flex items-center gap-3 px-3 py-3 rounded-lg <?php echo ($section === '' || $section === 'dashboard') ? 'active' : ''; ?>" href="?section=dashboard">
                    <i class="fas fa-chart-bar flex-shrink-0 w-5 h-5"></i>
                    <div class="sidebar-text text-left">
                        <div class="font-medium text-sm">Dashboard</div>
                        <div class="text-xs opacity-75">Report Summary</div>
                    </div>
                    <div class="sidebar-text ml-auto w-2 h-2 bg-sidebar-primary-foreground rounded-full"></div>
                </a>

                <a id="nav-products" class="nav-item w-full flex items-center gap-3 px-3 py-3 rounded-lg <?php echo ($section === 'products') ? 'active' : ''; ?>" href="?section=products">
                    <i class="fas fa-box flex-shrink-0 w-5 h-5"></i>
                    <div class="sidebar-text text-left">
                        <div class="font-medium text-sm">Products</div>
                    </div>
                </a>

                <a class="nav-item w-full flex items-center gap-3 px-3 py-3 rounded-lg <?php echo ($section === 'orders') ? 'active' : ''; ?>" href="?section=orders">
                    <i class="fas fa-shopping-cart flex-shrink-0 w-5 h-5"></i>
                    <div class="sidebar-text text-left">
                        <div class="font-medium text-sm">Orders</div>
                    </div>
                </a>

                <a class="nav-item w-full flex items-center gap-3 px-3 py-3 rounded-lg <?php echo ($section === 'bookings') ? 'active' : ''; ?>" href="?section=bookings">
                    <i class="fas fa-calendar flex-shrink-0 w-5 h-5"></i>
                    <div class="sidebar-text text-left">
                        <div class="font-medium text-sm">Bookings</div>
                    </div>
                </a>

                <a class="nav-item w-full flex items-center gap-3 px-3 py-3 rounded-lg <?php echo ($section === 'catering') ? 'active' : ''; ?>" href="?section=catering">
                    <i class="fas fa-utensils flex-shrink-0 w-5 h-5"></i>
                    <div class="sidebar-text text-left">
                        <div class="font-medium text-sm">Catering Packages</div>
                    </div>
                </a>

                <a class="nav-item w-full flex items-center gap-3 px-3 py-3 rounded-lg <?php echo ($section === 'categories') ? 'active' : ''; ?>" href="?section=categories">
                    <i class="fas fa-tags flex-shrink-0 w-5 h-5"></i>
                    <div class="sidebar-text text-left">
                        <div class="font-medium text-sm">Food Category</div>
                    </div>
                </a>

                <a class="nav-item w-full flex items-center gap-3 px-3 py-3 rounded-lg <?php echo ($section === 'employees') ? 'active' : ''; ?>" href="?section=employees">
                    <i class="fas fa-users flex-shrink-0 w-5 h-5"></i>
                    <div class="sidebar-text text-left">
                        <div class="font-medium text-sm">Employees</div>
                    </div>
                </a>

                <a id="nav-projects" class="nav-item w-full flex items-center gap-3 px-3 py-3 rounded-lg <?php echo ($section === 'projects') ? 'active' : ''; ?>" href="?section=projects">
                    <i class="fas fa-diagram-project flex-shrink-0 w-5 h-5"></i>
                    <div class="sidebar-text text-left">
                        <div class="font-medium text-sm">Projects</div>
                    </div>
                </a>

                <!-- New sections -->
                <a class="nav-item w-full flex items-center gap-3 px-3 py-3 rounded-lg <?php echo ($section === 'lock-sidebar') ? 'active' : ''; ?>" href="?section=lock-sidebar">
                    <i class="fas fa-lock flex-shrink-0 w-5 h-5"></i>
                    <div class="sidebar-text text-left">
                        <div class="font-medium text-sm">Lock Sidebar</div>
                    </div>
                </a>

                <a class="nav-item w-full flex items-center gap-3 px-3 py-3 rounded-lg <?php echo ($section === 'settings') ? 'active' : ''; ?>" href="?section=settings">
                    <i class="fas fa-cog flex-shrink-0 w-5 h-5"></i>
                    <div class="sidebar-text text-left">
                        <div class="font-medium text-sm">Settings</div>
                    </div>
                </a>
            </nav>

            <!-- Footer -->
            <div class="p-4 border-t border-sidebar-border">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-sidebar-primary rounded-full flex items-center justify-center text-xs font-semibold text-sidebar-primary-foreground">
                        A
                    </div>
                    <div class="sidebar-text">
                        <div class="font-medium">Admin User</div>
                        <div class="text-xs opacity-80">Administrator</div>
                    </div>
                </div>
            </div>
        </div>

    

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header Bar -->
            <header class="bg-white border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <!-- Left section -->
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-3">
                            <img src="../images/logo.png" 
                                 alt="Sandok ni Binggay Logo" 
                                 class="w-10 h-10 rounded-full object-cover border-2 border-primary">
                            <div>
                                <h2 id="page-title" class="text-primary font-semibold"><?php
                                    $titleMap = [
                                        'dashboard' => 'Dashboard',
                                        'products' => 'Products Management',
                                        'orders' => 'Orders Management',
                                        'bookings' => 'Bookings Management',
                                        'catering' => 'Catering Packages',
                                        'categories' => 'Food Categories',
                                        'employees' => 'Employee Management',
                                        'projects' => 'Project Management',
                                        'lock-sidebar' => 'Lock Sidebar',
                                        'settings' => 'Settings'
                                    ];
                                    echo $titleMap[$section] ?? 'Dashboard';
                                ?></h2>
                                <p class="text-sm text-muted-foreground">Sandok ni Binggay Admin</p>
                            </div>
                        </div>
                    </div>

                    <!-- Center section - Search -->
                    <div class="flex-1 max-w-md mx-8">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" placeholder="Search orders, customers, or menu items..." 
                                   class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                        </div>
                    </div>

                    <!-- Right section -->
                    <div class="flex items-center gap-4">
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="relative p-2 hover:bg-gray-100 rounded-lg transition-colors">
                                <i class="fas fa-bell text-gray-600"></i>
                                <span class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">3</span>
                            </button>
                        </div>

                        <!-- User Menu -->
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white font-medium">
                                A
                            </div>
                            <span class="text-sm font-medium">Admin</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="flex-1 overflow-y-auto">
                <!-- Dashboard Content -->
                <div id="dashboard-content" class="section-content <?php echo ($section && $section !== 'dashboard') ? 'hidden ' : ''; ?>p-6 space-y-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-medium text-primary mb-2">Dashboard Overview</h1>
                            <p class="text-muted-foreground">Welcome back! Here's what's happening with your catering business today.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 border border-yellow-200 rounded-lg text-sm">
                                Last updated: Today 2:30 PM
                            </span>
                        </div>
                    </div>

                    <!-- KPI Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="card p-6 border-l-4 border-l-primary hover:shadow-lg transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium">Total Revenue</h3>
                                <i class="fas fa-dollar-sign text-primary"></i>
                            </div>
                            <div class="text-2xl font-bold text-primary">₱328,000</div>
                            <div class="flex items-center text-sm text-muted-foreground">
                                <i class="fas fa-arrow-up text-green-600 mr-1"></i>
                                <span class="text-green-600">+12.5%</span>
                                <span class="ml-1">from last month</span>
                            </div>
                        </div>

                        <div class="card p-6 border-l-4 border-l-accent hover:shadow-lg transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium">Total Orders</h3>
                                <i class="fas fa-shopping-cart text-accent"></i>
                            </div>
                            <div class="text-2xl font-bold text-primary">1,176</div>
                            <div class="flex items-center text-sm text-muted-foreground">
                                <i class="fas fa-arrow-up text-green-600 mr-1"></i>
                                <span class="text-green-600">+8.2%</span>
                                <span class="ml-1">from last month</span>
                            </div>
                        </div>

                        <div class="card p-6 border-l-4 border-l-green-700 hover:shadow-lg transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium">Active Bookings</h3>
                                <i class="fas fa-calendar text-green-700"></i>
                            </div>
                            <div class="text-2xl font-bold text-primary">23</div>
                            <div class="flex items-center text-sm text-muted-foreground">
                                <i class="fas fa-arrow-up text-green-600 mr-1"></i>
                                <span class="text-green-600">+15.1%</span>
                                <span class="ml-1">this week</span>
                            </div>
                        </div>

                        <div class="card p-6 border-l-4 border-l-yellow-500 hover:shadow-lg transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-sm font-medium">Menu Items</h3>
                                <i class="fas fa-utensils text-yellow-500"></i>
                            </div>
                            <div class="text-2xl font-bold text-primary">147</div>
                            <div class="flex items-center text-sm text-muted-foreground">
                                <i class="fas fa-arrow-up text-green-600 mr-1"></i>
                                <span class="text-green-600">+3</span>
                                <span class="ml-1">new this week</span>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Revenue Chart -->
                        <div class="card p-6 hover:shadow-lg transition-shadow">
                            <div class="mb-4">
                                <h3 class="text-lg font-medium text-primary">Monthly Revenue & Orders</h3>
                                <p class="text-sm text-muted-foreground">Revenue and order trends over the last 6 months</p>
                            </div>
                            <div class="h-64">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>

                        <!-- Category Distribution -->
                        <div class="card p-6 hover:shadow-lg transition-shadow">
                            <div class="mb-4">
                                <h3 class="text-lg font-medium text-primary">Service Categories</h3>
                                <p class="text-sm text-muted-foreground">Distribution of orders by service type</p>
                            </div>
                            <div class="h-64">
                                <canvas id="categoryChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Tables Row -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Recent Orders -->
                        <div class="card p-6 hover:shadow-lg transition-shadow">
                            <div class="mb-4">
                                <h3 class="text-lg font-medium text-primary">Recent Orders</h3>
                                <p class="text-sm text-muted-foreground">Latest orders from your customers</p>
                            </div>
                            <div class="space-y-4">
                                <?php foreach($recentOrders as $order): ?>
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-medium text-primary"><?php echo $order['id']; ?></span>
                                            <span class="px-2 py-1 text-xs rounded <?php 
                                                echo $order['status'] === 'Completed' ? 'bg-green-100 text-green-800' : 
                                                    ($order['status'] === 'Delivered' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'); 
                                            ?>">
                                                <?php echo $order['status']; ?>
                                            </span>
                                        </div>
                                        <div class="text-sm font-medium"><?php echo $order['customer']; ?></div>
                                        <div class="text-sm text-muted-foreground"><?php echo $order['items']; ?></div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-medium text-primary"><?php echo $order['amount']; ?></div>
                                        <div class="text-sm text-muted-foreground"><?php echo $order['time']; ?></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Upcoming Bookings -->
                        <div class="card p-6 hover:shadow-lg transition-shadow">
                            <div class="mb-4">
                                <h3 class="text-lg font-medium text-primary">Upcoming Bookings</h3>
                                <p class="text-sm text-muted-foreground">Events scheduled for this week</p>
                            </div>
                            <div class="space-y-4">
                                <?php foreach($upcomingBookings as $booking): ?>
                                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex-1">
                                        <div class="font-medium text-primary mb-1"><?php echo $booking['event']; ?></div>
                                        <div class="text-sm text-muted-foreground"><?php echo $booking['client']; ?></div>
                                        <div class="text-sm text-muted-foreground"><?php echo $booking['guests']; ?> guests</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-medium text-primary"><?php echo $booking['date']; ?></div>
                                        <span class="px-2 py-1 text-xs border border-gray-300 rounded">
                                            <?php echo $booking['package']; ?>
                                        </span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other Section Contents (Hidden by default) -->
                <div id="products-content" class="section-content <?php echo ($section === 'products') ? '' : 'hidden '; ?>p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-2xl font-medium text-primary">Products Management</h2>
                            <p class="text-muted-foreground">Manage your menu items, pricing, and availability</p>
                        </div>
                        <button type="button" class="open-add-menu inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground hover:opacity-90 transition">
                            <i class="fas fa-plus"></i>
                            Add Menu
                        </button>
                    </div>

                    <!-- Filters and search -->
                    <form id="products-filter" method="get" class="card p-4 mb-4">
                        <input type="hidden" name="section" value="products" />
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 xl:grid-cols-6 gap-2 items-end">
                            <div>
                                <label class="text-sm text-muted-foreground">Search</label>
                                <input id="filter-q" type="text" name="q" value="<?php echo htmlspecialchars($q); ?>" placeholder="Search by name" class="w-full mt-1 px-2 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary">
                            </div>
                            <div>
                                <label class="text-sm text-muted-foreground">Category</label>
                                <select id="filter-category" name="category" class="w-full mt-1 px-2 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary">
                                    <option value="">All</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo (int)$cat['category_id']; ?>" <?php echo ($category === (int)$cat['category_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat['category_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm text-muted-foreground">PAX</label>
                                <select id="filter-pax" name="pax" class="w-full mt-1 px-2 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary">
                                    <option value="" <?php echo ($pax === null || $pax === '') ? 'selected' : ''; ?>>All</option>
                                    <option value="6-8" <?php echo ($pax === '6-8') ? 'selected' : ''; ?>>6-8 pax</option>
                                    <option value="10-15" <?php echo ($pax === '10-15') ? 'selected' : ''; ?>>10-15 pax</option>
                                    <option value="per" <?php echo ($pax === 'per') ? 'selected' : ''; ?>>per pieces</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm text-muted-foreground">Availability</label>
                                <select id="filter-avail" name="avail" class="w-full mt-1 px-2 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary">
                                    <option value="" <?php echo ($avail === null || $avail === '') ? 'selected' : ''; ?>>All</option>
                                    <option value="1" <?php echo ($avail === '1' || $avail === 1) ? 'selected' : ''; ?>>Available</option>
                                    <option value="0" <?php echo ($avail === '0' || $avail === 0) ? 'selected' : ''; ?>>Unavailable</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-sm text-muted-foreground">Sort by</label>
                                <select id="filter-sort" name="sort" class="w-full mt-1 px-2 py-1.5 bg-gray-50 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary">
                                    <?php
                                        $sortOptions = [
                                            '' => 'Default',
                                            'alpha_asc' => 'Name A → Z',
                                            'alpha_desc' => 'Name Z → A',
                                            'price_asc' => 'Price Low → High',
                                            'price_desc' => 'Price High → Low',
                                        ];
                                        foreach ($sortOptions as $val => $label) {
                                            $sel = ($sort === ($val === '' ? null : $val)) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($val) . '" ' . $sel . '>' . htmlspecialchars($label) . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!-- Apply/Reset buttons removed in favor of real-time filtering -->
                    </form>

                    <!-- Results -->
                    <div id="products-results" class="card overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50 text-gray-600">
                                    <tr>
                                        <th class="text-left p-3">Item</th>
                                        <th class="text-left p-3">PAX</th>
                                        <th class="text-left p-3">Price</th>
                                        <th class="text-left p-3">Availability</th>
                                        <th class="text-left p-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($menus)): ?>
                                        <tr>
                                            <td colspan="5" class="p-6 text-center text-muted-foreground">No menu items found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($menus as $m): ?>
                                            <?php
                                                $name = htmlspecialchars($m['menu_name'] ?? '');
                                                $paxv = htmlspecialchars((string)($m['menu_pax'] ?? ''));
                                                $price = isset($m['menu_price']) ? '₱' . number_format((float)$m['menu_price'], 2) : '';
                                                $isAvail = ((string)($m['menu_avail'] ?? '1') === '1');
                                                $availBadge = $isAvail
                                                    ? '<span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Available</span>'
                                                    : '<span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Unavailable</span>';
                                                $img = menu_img_src($m['menu_pic'] ?? '');
                                                $mid = (int)($m['menu_id'] ?? 0);
                                            ?>
                                            <tr class="border-t border-gray-100 hover:bg-gray-50">
                                                <td class="p-3">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-12 h-12 bg-gray-100 rounded object-cover overflow-hidden flex items-center justify-center">
                                                            <?php if ($img): ?>
                                                                <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo $name; ?>" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/48?text=Food';">
                                                            <?php else: ?>
                                                                <span class="text-xs text-gray-400">No image</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div>
                                                            <div class="font-medium text-primary"><?php echo $name; ?></div>
                                                            <?php if (!empty($m['menu_desc'])): ?>
                                                                <div class="text-xs text-muted-foreground truncate max-w-md"><?php echo htmlspecialchars($m['menu_desc']); ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="p-3"><?php echo $paxv; ?></td>
                                                <td class="p-3"><?php echo $price; ?></td>
                                                <td class="p-3"><?php echo $availBadge; ?></td>
                                                <td class="p-3">
                                                    <div class="flex items-center gap-2">
                                                        <button type="button" class="p-2 rounded border border-gray-200 hover:bg-gray-50 js-edit" data-menu-id="<?php echo $mid; ?>" title="Edit" aria-label="Edit">
                                                            <i class="fas fa-pen"></i>
                                                        </button>
                                                        <button type="button" class="p-2 rounded border border-gray-200 hover:bg-gray-50 js-action" data-action="toggle" data-menu-id="<?php echo $mid; ?>" title="Toggle availability" aria-label="Toggle availability">
                                                            <i class="fas <?php echo $isAvail ? 'fa-toggle-on text-green-600' : 'fa-toggle-off text-gray-500'; ?>"></i>
                                                        </button>
                                                        <button type="button" class="p-2 rounded border border-red-200 text-red-700 hover:bg-red-50 js-action" data-action="delete" data-menu-id="<?php echo $mid; ?>" title="Delete" aria-label="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="p-3 flex items-center justify-between border-t border-gray-100">
                            <div class="text-sm text-muted-foreground">
                                Page <?php echo $page; ?> of <?php echo $totalPages; ?> · <?php echo $totalCount; ?> items
                            </div>
                            <div class="flex items-center gap-1">
                                <?php $prev = max(1, $page-1); $next = min($totalPages, $page+1); ?>
                                <a class="px-2 py-1 rounded border border-gray-300 text-sm <?php echo $page <= 1 ? 'opacity-50 pointer-events-none' : 'hover:bg-gray-50'; ?>" href="?<?php echo build_query(['page'=>$prev]); ?>">Prev</a>
                                <?php
                                    $start = max(1, $page-2);
                                    $end = min($totalPages, $page+2);
                                    for ($i=$start; $i<=$end; $i++):
                                ?>
                                    <a class="px-2 py-1 rounded text-sm <?php echo $i === $page ? 'bg-primary text-white' : 'border border-gray-300 hover:bg-gray-50'; ?>" href="?<?php echo build_query(['page'=>$i]); ?>"><?php echo $i; ?></a>
                                <?php endfor; ?>
                                <a class="px-2 py-1 rounded border border-gray-300 text-sm <?php echo $page >= $totalPages ? 'opacity-50 pointer-events-none' : 'hover:bg-gray-50'; ?>" href="?<?php echo build_query(['page'=>$next]); ?>">Next</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Projects Section -->
                <div id="projects-content" class="section-content <?php echo ($section === 'projects') ? '' : 'hidden '; ?>p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-2xl font-medium text-primary">Project Management</h2>
                            <p class="text-muted-foreground">View all menu records from the database</p>
                        </div>
                        <button type="button" class="open-add-menu inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground hover:opacity-90 transition">
                            <i class="fas fa-plus"></i>
                            Add Menu
                        </button>
                    </div>

                    <div class="card overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50 text-gray-600">
                                    <tr>
                                        <th class="text-left p-3">ID</th>
                                        <th class="text-left p-3">Image</th>
                                        <th class="text-left p-3">Name</th>
                                        <th class="text-left p-3">Description</th>
                                        <th class="text-left p-3">PAX</th>
                                        <th class="text-left p-3">Price</th>
                                        <th class="text-left p-3">Available</th>
                                        <th class="text-left p-3">Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($projMenus)): ?>
                                        <tr>
                                            <td colspan="8" class="p-6 text-center text-muted-foreground">No menu records found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($projMenus as $m): ?>
                                            <?php
                                                $mid = (int)($m['menu_id'] ?? 0);
                                                $name = htmlspecialchars($m['menu_name'] ?? '');
                                                $desc = htmlspecialchars($m['menu_desc'] ?? '');
                                                $paxv = htmlspecialchars((string)($m['menu_pax'] ?? ''));
                                                $price = isset($m['menu_price']) ? '₱' . number_format((float)$m['menu_price'], 2) : '';
                                                $availBadge = ((string)($m['menu_avail'] ?? '1') === '1')
                                                    ? '<span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Yes</span>'
                                                    : '<span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">No</span>';
                                                $created = htmlspecialchars($m['created_at'] ?? '');
                                                $img = menu_img_src($m['menu_pic'] ?? '');
                                            ?>
                                            <tr class="border-t border-gray-100 hover:bg-gray-50">
                                                <td class="p-3 align-top">#<?php echo $mid; ?></td>
                                                <td class="p-3 align-top">
                                                    <div class="w-12 h-12 bg-gray-100 rounded overflow-hidden flex items-center justify-center">
                                                        <?php if ($img): ?>
                                                            <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo $name; ?>" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/48?text=Food';">
                                                        <?php else: ?>
                                                            <span class="text-xs text-gray-400">No image</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td class="p-3 align-top font-medium text-primary"><?php echo $name; ?></td>
                                                <td class="p-3 align-top">
                                                    <div class="max-w-xs text-muted-foreground truncate"><?php echo $desc; ?></div>
                                                </td>
                                                <td class="p-3 align-top"><?php echo $paxv; ?></td>
                                                <td class="p-3 align-top"><?php echo $price; ?></td>
                                                <td class="p-3 align-top"><?php echo $availBadge; ?></td>
                                                <td class="p-3 align-top"><?php echo $created; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3 flex items-center justify-between border-t border-gray-100">
                            <div class="text-sm text-muted-foreground">Page <?php echo $projPage; ?> of <?php echo $projTotalPages; ?> · <?php echo $projTotalCount; ?> items</div>
                            <div class="flex items-center gap-1">
                                <?php $pPrev = max(1, $projPage-1); $pNext = min($projTotalPages, $projPage+1); ?>
                                <a class="px-2 py-1 rounded border border-gray-300 text-sm <?php echo $projPage <= 1 ? 'opacity-50 pointer-events-none' : 'hover:bg-gray-50'; ?>" href="?<?php echo build_query(['section'=>'projects','proj_page'=>$pPrev]); ?>">Prev</a>
                                <?php
                                    $pStart = max(1, $projPage-2);
                                    $pEnd = min($projTotalPages, $projPage+2);
                                    for ($i=$pStart; $i<=$pEnd; $i++):
                                ?>
                                    <a class="px-2 py-1 rounded text-sm <?php echo $i === $projPage ? 'bg-primary text-white' : 'border border-gray-300 hover:bg-gray-50'; ?>" href="?<?php echo build_query(['section'=>'projects','proj_page'=>$i]); ?>"><?php echo $i; ?></a>
                                <?php endfor; ?>
                                <a class="px-2 py-1 rounded border border-gray-300 text-sm <?php echo $projPage >= $projTotalPages ? 'opacity-50 pointer-events-none' : 'hover:bg-gray-50'; ?>" href="?<?php echo build_query(['section'=>'projects','proj_page'=>$pNext]); ?>">Next</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="orders-content" class="section-content <?php echo ($section === 'orders') ? '' : 'hidden '; ?>p-6">
                    <div class="card max-w-2xl mx-auto p-8">
                        <h2 class="text-2xl font-medium text-primary mb-2">Orders Management</h2>
                        <p class="text-muted-foreground mb-8">Track and manage customer orders</p>
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <div class="w-8 h-8 bg-primary rounded-full"></div>
                            </div>
                            <p class="text-muted-foreground mb-4">Order tracking, fulfillment status, and customer communication tools will be shown here.</p>
                            <div class="inline-flex items-center gap-2 text-sm text-primary">
                                <div class="w-2 h-2 bg-primary rounded-full animate-pulse"></div>
                                Coming Soon
                            </div>
                        </div>
                    </div>
                </div>

                <div id="bookings-content" class="section-content <?php echo ($section === 'bookings') ? '' : 'hidden '; ?>p-6">
                    <div class="card max-w-2xl mx-auto p-8">
                        <h2 class="text-2xl font-medium text-primary mb-2">Bookings Management</h2>
                        <p class="text-muted-foreground mb-8">Manage catering event bookings and scheduling</p>
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <div class="w-8 h-8 bg-primary rounded-full"></div>
                            </div>
                            <p class="text-muted-foreground mb-4">Event calendar, booking requests, and scheduling tools will be available here.</p>
                            <div class="inline-flex items-center gap-2 text-sm text-primary">
                                <div class="w-2 h-2 bg-primary rounded-full animate-pulse"></div>
                                Coming Soon
                            </div>
                        </div>
                    </div>
                </div>

                <div id="catering-content" class="section-content <?php echo ($section === 'catering') ? '' : 'hidden '; ?>p-6">
                    <div class="card max-w-2xl mx-auto p-8">
                        <h2 class="text-2xl font-medium text-primary mb-2">Catering Packages</h2>
                        <p class="text-muted-foreground mb-8">Create and manage catering service packages</p>
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <div class="w-8 h-8 bg-primary rounded-full"></div>
                            </div>
                            <p class="text-muted-foreground mb-4">Package builder, pricing tiers, and service customization options will be displayed here.</p>
                            <div class="inline-flex items-center gap-2 text-sm text-primary">
                                <div class="w-2 h-2 bg-primary rounded-full animate-pulse"></div>
                                Coming Soon
                            </div>
                        </div>
                    </div>
                </div>

                <div id="categories-content" class="section-content <?php echo ($section === 'categories') ? '' : 'hidden '; ?>p-6">
                    <div class="card max-w-2xl mx-auto p-8">
                        <h2 class="text-2xl font-medium text-primary mb-2">Food Categories</h2>
                        <p class="text-muted-foreground mb-8">Organize your menu items by categories</p>
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <div class="w-8 h-8 bg-primary rounded-full"></div>
                            </div>
                            <p class="text-muted-foreground mb-4">Category management, menu organization, and classification tools will be shown here.</p>
                            <div class="inline-flex items-center gap-2 text-sm text-primary">
                                <div class="w-2 h-2 bg-primary rounded-full animate-pulse"></div>
                                Coming Soon
                            </div>
                        </div>
                    </div>
                </div>

                <div id="employees-content" class="section-content <?php echo ($section === 'employees') ? '' : 'hidden '; ?>p-6">
                    <div class="card max-w-2xl mx-auto p-8">
                        <h2 class="text-2xl font-medium text-primary mb-2">Employee Management</h2>
                        <p class="text-muted-foreground mb-8">Manage staff, schedules, and permissions</p>
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <div class="w-8 h-8 bg-primary rounded-full"></div>
                            </div>
                            <p class="text-muted-foreground mb-4">Staff directory, scheduling tools, and role management features will be available here.</p>
                            <div class="inline-flex items-center gap-2 text-sm text-primary">
                                <div class="w-2 h-2 bg-primary rounded-full animate-pulse"></div>
                                Coming Soon
                            </div>
                        </div>
                    </div>
                </div>

                <div id="lock-sidebar-content" class="section-content <?php echo ($section === 'lock-sidebar') ? '' : 'hidden '; ?>p-6">
                    <div class="card max-w-2xl mx-auto p-8">
                        <h2 class="text-2xl font-medium text-primary mb-2">Lock Sidebar</h2>
                        <p class="text-muted-foreground mb-8">Configure sidebar locking and navigation preferences</p>
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-lock text-2xl text-primary"></i>
                            </div>
                            <p class="text-muted-foreground mb-4">Sidebar lock settings, auto-collapse preferences, and navigation customization will be available here.</p>
                            <div class="inline-flex items-center gap-2 text-sm text-primary">
                                <div class="w-2 h-2 bg-primary rounded-full animate-pulse"></div>
                                Coming Soon
                            </div>
                        </div>
                    </div>
                </div>

                <div id="settings-content" class="section-content <?php echo ($section === 'settings') ? '' : 'hidden '; ?>p-6">
                    <div class="card max-w-2xl mx-auto p-8">
                        <h2 class="text-2xl font-medium text-primary mb-2">Settings</h2>
                        <p class="text-muted-foreground mb-8">Configure system settings and preferences</p>
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-cog text-2xl text-primary"></i>
                            </div>
                            <p class="text-muted-foreground mb-4">System configuration, user preferences, notification settings, and administrative controls will be displayed here.</p>
                            <div class="inline-flex items-center gap-2 text-sm text-primary">
                                <div class="w-2 h-2 bg-primary rounded-full animate-pulse"></div>
                                Coming Soon
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Sidebar functionality with persisted state
        let sidebarCollapsed = false;
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarIcon = document.getElementById('sidebar-icon');
        const sidebarTexts = document.querySelectorAll('.sidebar-text');

        // Initialize from persisted preference
        try {
            const persisted = localStorage.getItem('sidebarCollapsed');
            if (persisted === 'true') {
                sidebarCollapsed = true;
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                sidebarIcon.className = 'fas fa-bars text-sm';
                sidebarTexts.forEach(text => text.style.display = 'none');
                const headerEl = document.getElementById('sidebar-header');
                headerEl && headerEl.classList.add('justify-center');
            }
        } catch (_) {}

        sidebarToggle.addEventListener('click', function() {
            sidebarCollapsed = !sidebarCollapsed;
            try { localStorage.setItem('sidebarCollapsed', sidebarCollapsed ? 'true' : 'false'); } catch (_) {}

            if (sidebarCollapsed) {
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                sidebarIcon.className = 'fas fa-bars text-sm';
                sidebarTexts.forEach(text => text.style.display = 'none');
                const headerEl = document.getElementById('sidebar-header');
                headerEl && headerEl.classList.add('justify-center');
            } else {
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('sidebar-expanded');
                sidebarIcon.className = 'fas fa-times text-sm';
                sidebarTexts.forEach(text => text.style.display = 'block');
                const headerEl = document.getElementById('sidebar-header');
                headerEl && headerEl.classList.remove('justify-center');
            }
        });

        // Clicking empty space inside the sidebar toggles expand/collapse.
        // Ignore clicks on interactive elements (nav items, buttons, links, inputs).
        sidebar.addEventListener('click', function(e) {
            const interactive = e.target.closest('.nav-item, #sidebar-toggle, button, a, input, select, textarea');
            if (interactive) return;
            sidebarToggle.click();
        });

        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const initialSection = '<?php echo htmlspecialchars($section); ?>';
            // Sections visibility already handled server-side via PHP classes above

            // Let navigation links work normally to avoid section switching glitches

            // Real-time filtering in Products
            if (initialSection === 'products') {
                const form = document.getElementById('products-filter');
                const q = document.getElementById('filter-q');
                const cat = document.getElementById('filter-category');
                const pax = document.getElementById('filter-pax');
                const avail = document.getElementById('filter-avail');
                const sort = document.getElementById('filter-sort');
                const container = document.getElementById('products-results');
                let seq = 0;
                let controller = null;

                const submitNow = () => {
                    // Reset to page 1 on any filter change
                    const params = new URLSearchParams(new FormData(form));
                    params.set('page', '1');
                    // Fetch only the products section HTML to avoid page flicker
                    const url = '?' + params.toString();
                    if (!container) { window.location.href = url; return; }
                    const mySeq = ++seq;
                    container.style.opacity = '0.6';
                    try { controller && controller.abort(); } catch {}
                    controller = new AbortController();
                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal: controller.signal })
                        .then(r => r.text())
                        .then(html => {
                            if (mySeq !== seq) return;
                            const temp = document.createElement('div');
                            temp.innerHTML = html;
                            const updated = temp.querySelector('#products-results');
                            if (updated) {
                                container.innerHTML = updated.innerHTML;
                            } else {
                                window.location.href = url;
                            }
                        })
                        .catch((err) => { if (mySeq === seq && (!err || err.name !== 'AbortError')) window.location.href = url; })
                        .finally(() => { if (mySeq === seq) container.style.opacity = '1'; });
                };
                // Debounce for search input
                let t = null;
                q && q.addEventListener('input', () => {
                    clearTimeout(t);
                    t = setTimeout(submitNow, 300);
                });
                // Immediate submit on select changes
                [cat, pax, avail, sort].forEach(el => el && el.addEventListener('change', submitNow));

                // Intercept pagination clicks to update smoothly without full page reload
                if (container) {
                    container.addEventListener('click', (e) => {
                        // Intercept action buttons (toggle/delete)
                        const btn = e.target.closest('.js-action');
                        if (btn) {
                            e.preventDefault();
                            const act = btn.getAttribute('data-action');
                            const id = btn.getAttribute('data-menu-id');
                            if (!act || !id) return;
                            if (act === 'toggle') {
                                if (!confirm('Toggle availability for this item?')) return;
                            } else if (act === 'delete') {
                                if (!confirm('Delete this item? This action cannot be undone.')) return;
                            }
                            const formData = new FormData();
                            formData.append('section', 'products');
                            formData.append('ajax', '1');
                            formData.append('action', act);
                            formData.append('menu_id', id);
                            const postUrl = '?section=products';
                            const mySeq = ++seq;
                            container.style.opacity = '0.6';
                            try { controller && controller.abort(); } catch {}
                            controller = new AbortController();
                            fetch(postUrl, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal: controller.signal })
                                .then(r => r.json())
                                .then(data => {
                                    if (mySeq !== seq) return;
                                    // After success, refresh the list keeping current filters and page
                                    const params = new URLSearchParams(new FormData(form));
                                    const url = '?' + params.toString();
                                    return fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal: controller.signal })
                                        .then(r => r.text())
                                        .then(html => {
                                            if (mySeq !== seq) return;
                                            const temp = document.createElement('div');
                                            temp.innerHTML = html;
                                            const updated = temp.querySelector('#products-results');
                                            if (updated) {
                                                container.innerHTML = updated.innerHTML;
                                            } else {
                                                window.location.href = url;
                                            }
                                        });
                                })
                                .catch(err => { if (mySeq === seq && (!err || err.name !== 'AbortError')) alert('Request failed. Please retry.'); })
                                .finally(() => { if (mySeq === seq) container.style.opacity = '1'; });
                            return;
                        }
                        const a = e.target.closest('a');
                        if (!a) return;
                        const href = a.getAttribute('href') || '';
                        if (href.includes('section=products') && (href.includes('page=') || href.includes('sort='))) {
                            e.preventDefault();
                            const url = a.href;
                            const mySeq = ++seq;
                            container.style.opacity = '0.6';
                            try { controller && controller.abort(); } catch {}
                            controller = new AbortController();
                            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal: controller.signal })
                                .then(r => r.text())
                                .then(html => {
                                    if (mySeq !== seq) return;
                                    const temp = document.createElement('div');
                                    temp.innerHTML = html;
                                    const updated = temp.querySelector('#products-results');
                                    if (updated) {
                                        container.innerHTML = updated.innerHTML;
                                    } else {
                                        window.location.href = url;
                                    }
                                })
                                .catch((err) => { if (mySeq === seq && (!err || err.name !== 'AbortError')) window.location.href = url; })
                                .finally(() => { if (mySeq === seq) container.style.opacity = '1'; });
                        }
                    });
                }
            }

            // Revenue Chart (guarded)
            const revenueCanvas = document.getElementById('revenueChart');
            if (revenueCanvas && revenueCanvas.getContext) {
            const revenueCtx = revenueCanvas.getContext('2d');
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($salesData, 'month')); ?>,
                    datasets: [{
                        label: 'Revenue',
                        data: <?php echo json_encode(array_column($salesData, 'revenue')); ?>,
                        backgroundColor: '#1B4332',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f0f0f0'
                            }
                        },
                        x: {
                            grid: {
                                color: '#f0f0f0'
                            }
                        }
                    }
                }
            });
            }

            // Category Chart (guarded)
            const categoryCanvas = document.getElementById('categoryChart');
            if (categoryCanvas && categoryCanvas.getContext) {
            const categoryCtx = categoryCanvas.getContext('2d');
            new Chart(categoryCtx, {
                type: 'pie',
                data: {
                    labels: ['Party Trays', 'Packed Meals', 'Catering Events', 'Grazing Tables'],
                    datasets: [{
                        data: [35, 28, 22, 15],
                        backgroundColor: ['#1B4332', '#D4AF37', '#2D5A3D', '#E8C547']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
            }
        });

        // Add Menu modal + submit
        document.addEventListener('DOMContentLoaded', function() {
            // EDIT modal elements
            const editBackdrop = document.getElementById('edit-menu-backdrop');
            const editModal = document.getElementById('edit-menu-modal');
            const editDialog = editModal ? editModal.querySelector('.dialog') : null;
            const editClosers = document.querySelectorAll('.close-edit-menu');
            const editForm = document.getElementById('edit-menu-form');
            const editId = document.getElementById('edit-menu-id');
            const editName = document.getElementById('edit-name');
            const editDesc = document.getElementById('edit-description');
            const editPaxCat = document.getElementById('edit-pax-category');
            const editPiecesWrap = document.getElementById('edit-pieces-wrap');
            const editPiecesCount = document.getElementById('edit-pieces-count');
            const editPrice = document.getElementById('edit-price');
            const editAvail = document.getElementById('edit-availability');
            const editPhoto = document.getElementById('edit-menu-photo');
            const editPreview = document.getElementById('edit-photo-preview');
            const editPhotoClear = document.getElementById('edit-photo-clear');
            const editMsg = document.getElementById('edit-menu-message');
            const editSubmit = document.getElementById('edit-menu-submit');

            function showEditModal() {
                if (!editBackdrop || !editModal) return;
                // Ensure elements are not hidden before animating
                editBackdrop.style.display = 'block';
                editModal.style.display = 'flex';
                editBackdrop.setAttribute('aria-hidden', 'false');
                editModal.setAttribute('aria-hidden', 'false');
                editBackdrop.classList.remove('pointer-events-none');
                editModal.classList.remove('pointer-events-none');
                requestAnimationFrame(() => {
                    editBackdrop.style.opacity = '1';
                    editModal.style.opacity = '1';
                    if (editDialog) editDialog.style.transform = 'scale(1)';
                });
            }
            function hideEditModal() {
                if (!editBackdrop || !editModal) return;
                editBackdrop.style.opacity = '0';
                editModal.style.opacity = '0';
                if (editDialog) editDialog.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    editBackdrop.classList.add('pointer-events-none');
                    editModal.classList.add('pointer-events-none');
                    // Hide from layout to prevent flashes on navigation
                    editBackdrop.style.display = 'none';
                    editModal.style.display = 'none';
                    editBackdrop.setAttribute('aria-hidden', 'true');
                    editModal.setAttribute('aria-hidden', 'true');
                    if (editForm) editForm.reset();
                    if (editPreview) { editPreview.src = ''; editPreview.classList.add('hidden'); }
                    if (editMsg) { editMsg.textContent = ''; editMsg.className = 'text-sm mt-2'; }
                }, 180);
            }

            editClosers.forEach(btn => btn.addEventListener('click', hideEditModal));
            editBackdrop && editBackdrop.addEventListener('click', hideEditModal);

            function setEditPax(paxVal) {
                const val = (paxVal || '').toString().trim().toLowerCase();
                if (val === '6-8' || val === '6–8') {
                    editPaxCat.value = '6-8';
                    editPiecesWrap.classList.add('hidden');
                    editPiecesCount.value = '';
                } else if (val === '10-15' || val === '10–15') {
                    editPaxCat.value = '10-15';
                    editPiecesWrap.classList.add('hidden');
                    editPiecesCount.value = '';
                } else if (val.includes('pieces')) {
                    editPaxCat.value = 'per';
                    // Extract number before 'pieces' if present
                    const m = val.match(/(\d+)\s*pieces/);
                    editPiecesCount.value = m ? parseInt(m[1], 10) : '';
                    editPiecesWrap.classList.remove('hidden');
                } else {
                    editPaxCat.value = '6-8';
                    editPiecesWrap.classList.add('hidden');
                    editPiecesCount.value = '';
                }
            }
            function currentEditPaxString() {
                const cat = editPaxCat ? editPaxCat.value : '';
                if (cat === '6-8' || cat === '10-15') return cat;
                if (cat === 'per') {
                    const n = editPiecesCount && editPiecesCount.value ? parseInt(editPiecesCount.value, 10) : NaN;
                    return Number.isFinite(n) && n > 0 ? `${n} pieces` : 'per pieces';
                }
                return '';
            }
            editPaxCat && editPaxCat.addEventListener('change', () => {
                const show = editPaxCat.value === 'per';
                editPiecesWrap.classList.toggle('hidden', !show);
            });
            if (editPhoto && editPreview) {
                editPhoto.addEventListener('change', () => {
                    const file = editPhoto.files && editPhoto.files[0];
                    if (file) {
                        const url = URL.createObjectURL(file);
                        editPreview.src = url;
                        editPreview.classList.remove('hidden');
                    } else {
                        editPreview.src = '';
                        editPreview.classList.add('hidden');
                    }
                });
            }
            if (editPhotoClear && editPhoto) {
                editPhotoClear.addEventListener('click', () => {
                    editPhoto.value = '';
                    if (editPreview) { editPreview.src = ''; editPreview.classList.add('hidden'); }
                });
            }

            // Open edit modal on edit button click and fetch data
            document.addEventListener('click', async (e) => {
                const editBtn = e.target.closest('.js-edit');
                if (!editBtn) return;
                const id = editBtn.getAttribute('data-menu-id');
                if (!id) return;
                try {
                    const res = await fetch(`?section=products&ajax=1&action=get_menu&menu_id=${encodeURIComponent(id)}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
                    const data = await res.json();
                    if (!data.success) { alert(data.message || 'Failed to fetch menu'); return; }
                    const m = data.data || {};
                    if (editId) editId.value = m.menu_id || id;
                    if (editName) editName.value = m.menu_name || '';
                    if (editDesc) editDesc.value = m.menu_desc || '';
                    if (editPrice) editPrice.value = m.menu_price != null ? m.menu_price : '';
                    if (editAvail) editAvail.value = String(m.menu_avail ?? '1');
                    setEditPax(m.menu_pax || '');
                    if (editPreview) {
                        const pic = m.menu_pic || '';
                        if (pic) {
                            editPreview.src = (pic.startsWith('http') || pic.includes('/')) ? pic : (`../menu/${pic}`);
                            editPreview.classList.remove('hidden');
                        } else {
                            editPreview.src = '';
                            editPreview.classList.add('hidden');
                        }
                    }
                    showEditModal();
                } catch (_) {
                    alert('Network error while fetching menu');
                }
            });

            // Submit edit form via AJAX
            if (editForm) {
                editForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    if (editMsg) { editMsg.textContent = ''; editMsg.className = 'text-sm mt-2'; }
                    editSubmit && (editSubmit.disabled = true);
                    try {
                        const fd = new FormData(editForm);
                        fd.set('section', 'products');
                        fd.set('ajax', '1');
                        fd.set('action', 'update');
                        // Rebuild pax standardized value
                        fd.delete('pax');
                        fd.delete('pax_category');
                        fd.delete('pieces_count');
                        fd.append('pax', currentEditPaxString());
                        const res = await fetch('?section=products', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' }});
                        const data = await res.json().catch(() => ({ success:false, message: 'Invalid response'}));
                        if (!data.success) {
                            if (editMsg) { editMsg.textContent = data.message || 'Please fix the errors.'; editMsg.classList.add('text-red-600'); }
                            return;
                        }
                        if (editMsg) { editMsg.textContent = 'Saved successfully.'; editMsg.classList.add('text-green-700'); }
                        // Refresh products list, preserving filters/page
                        const container = document.getElementById('products-results');
                        const filterForm = document.getElementById('products-filter');
                        if (container && filterForm) {
                            const params = new URLSearchParams(new FormData(filterForm));
                            const url = '?' + params.toString();
                            try {
                                container.style.opacity = '0.6';
                                const html = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }}).then(r => r.text());
                                const temp = document.createElement('div');
                                temp.innerHTML = html;
                                const updated = temp.querySelector('#products-results');
                                if (updated) container.innerHTML = updated.innerHTML;
                            } catch {}
                            container.style.opacity = '1';
                        }
                        setTimeout(hideEditModal, 250);
                    } catch (_) {
                        if (editMsg) { editMsg.textContent = 'Network error. Please try again.'; editMsg.classList.add('text-red-600'); }
                    } finally {
                        editSubmit && (editSubmit.disabled = false);
                    }
                });
            }

            const openers = document.querySelectorAll('.open-add-menu');
            const backdrop = document.getElementById('add-menu-backdrop');
            const modal = document.getElementById('add-menu-modal');
            const dialog = modal ? modal.querySelector('.dialog') : null;
            const closers = document.querySelectorAll('.close-add-menu');
            const form = document.getElementById('add-menu-form');
            const preview = document.getElementById('photo-preview');
            const photoInput = document.getElementById('menu-photo');
            const photoClear = document.getElementById('photo-clear');
            const msg = document.getElementById('add-menu-message');
            const submitBtn = document.getElementById('add-menu-submit');
            const paxCategory = document.getElementById('pax-category');
            const piecesWrap = document.getElementById('pieces-wrap');
            const piecesCount = document.getElementById('pieces-count');

            function showModal() {
                if (!backdrop || !modal) return;
                // Ensure elements are not hidden before animating
                backdrop.style.display = 'block';
                modal.style.display = 'flex';
                backdrop.setAttribute('aria-hidden', 'false');
                modal.setAttribute('aria-hidden', 'false');
                backdrop.classList.remove('pointer-events-none');
                modal.classList.remove('pointer-events-none');
                requestAnimationFrame(() => {
                    backdrop.style.opacity = '1';
                    modal.style.opacity = '1';
                    if (dialog) dialog.style.transform = 'scale(1)';
                });
            }
            function hideModal() {
                if (!backdrop || !modal) return;
                backdrop.style.opacity = '0';
                modal.style.opacity = '0';
                if (dialog) dialog.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    backdrop.classList.add('pointer-events-none');
                    modal.classList.add('pointer-events-none');
                    // Hide from layout to prevent flashes on navigation
                    backdrop.style.display = 'none';
                    modal.style.display = 'none';
                    backdrop.setAttribute('aria-hidden', 'true');
                    modal.setAttribute('aria-hidden', 'true');
                    if (form) form.reset();
                    if (preview) { preview.src = ''; preview.classList.add('hidden'); }
                    clearErrors();
                    if (msg) { msg.textContent = ''; msg.className = 'text-sm mt-2'; }
                }, 180);
            }

            function clearErrors() {
                document.querySelectorAll('[data-error]').forEach(el => el.textContent = '');
            }

            openers.forEach(btn => btn.addEventListener('click', showModal));
            closers.forEach(btn => btn.addEventListener('click', hideModal));
            backdrop && backdrop.addEventListener('click', hideModal);

            // PAX category toggle
            function updatePiecesVisibility() {
                if (!paxCategory || !piecesWrap) return;
                const show = paxCategory.value === 'per';
                piecesWrap.classList.toggle('hidden', !show);
            }
            paxCategory && paxCategory.addEventListener('change', updatePiecesVisibility);
            updatePiecesVisibility();

            // Image preview
            if (photoInput && preview) {
                photoInput.addEventListener('change', () => {
                    const file = photoInput.files && photoInput.files[0];
                    if (file) {
                        const url = URL.createObjectURL(file);
                        preview.src = url;
                        preview.classList.remove('hidden');
                    } else {
                        preview.src = '';
                        preview.classList.add('hidden');
                    }
                });
            }

            // Clear photo
            if (photoClear && photoInput && preview) {
                photoClear.addEventListener('click', () => {
                    photoInput.value = '';
                    preview.src = '';
                    preview.classList.add('hidden');
                });
            }

            // Submit via AJAX
            if (form) {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    clearErrors();
                    if (msg) { msg.textContent = ''; msg.className = 'text-sm mt-2'; }
                    submitBtn && (submitBtn.disabled = true);
                    try {
                        const formData = new FormData(form);
                        // Build pax value based on category/pieces
                        const cat = paxCategory ? paxCategory.value : '';
                        let paxValue = '';
                        if (cat === '6-8' || cat === '10-15') {
                            paxValue = cat;
                        } else if (cat === 'per') {
                            const count = piecesCount && piecesCount.value ? parseInt(piecesCount.value, 10) : NaN;
                            paxValue = Number.isFinite(count) && count > 0 ? `${count} pieces` : 'per pieces';
                        }
                        formData.delete('pax');
                        formData.delete('pax_category');
                        formData.delete('pieces_count');
                        formData.append('pax', paxValue);
                        // API expects: name, description, pax, price, availability, photo
                        const res = await fetch('api_add_menu.php', {
                            method: 'POST',
                            body: formData
                        });
                        const data = await res.json().catch(() => ({ success: false, message: 'Invalid response' }));
                        if (!res.ok || !data.success) {
                            const errors = data.errors || {};
                            Object.keys(errors).forEach(k => {
                                const el = document.querySelector(`[data-error="${k}"]`);
                                if (el) el.textContent = errors[k];
                            });
                            if (msg) { msg.textContent = data.message || 'Please fix the errors.'; msg.classList.add('text-red-600'); }
                            return;
                        }
                        if (msg) { msg.textContent = 'Menu added successfully.'; msg.classList.add('text-green-700'); }
                        // Refresh products list if we are on products section
                        const productsNav = document.getElementById('nav-products');
                        const isOnProducts = productsNav && productsNav.classList.contains('active');
                        if (isOnProducts) {
                            const container = document.getElementById('products-results');
                            const filterForm = document.getElementById('products-filter');
                            if (container && filterForm) {
                                const params = new URLSearchParams(new FormData(filterForm));
                                params.set('page', '1'); // show newest on first page
                                const url = '?' + params.toString();
                                container.style.opacity = '0.6';
                                try {
                                    const html = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }}).then(r => r.text());
                                    const temp = document.createElement('div');
                                    temp.innerHTML = html;
                                    const updated = temp.querySelector('#products-results');
                                    if (updated) container.innerHTML = updated.innerHTML;
                                } catch {}
                                container.style.opacity = '1';
                            }
                        }
                        // Close after a brief delay
                        setTimeout(hideModal, 300);
                    } catch (err) {
                        if (msg) { msg.textContent = 'Network error. Please try again.'; msg.classList.add('text-red-600'); }
                    } finally {
                        submitBtn && (submitBtn.disabled = false);
                    }
                });
            }
        });
    </script>
    
    <!-- Add Menu Modal -->
    <div id="add-menu-backdrop" class="fixed inset-0 bg-black/40 opacity-0 pointer-events-none transition-opacity duration-200" style="display:none" aria-hidden="true"></div>
    <div id="add-menu-modal" class="fixed inset-0 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-200" style="display:none" aria-hidden="true">
        <div class="dialog w-full max-w-xl mx-4 scale-95 transition-transform duration-200">
            <div class="bg-white rounded-lg shadow-xl">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <h3 class="text-lg font-semibold text-primary">Add New Menu Item</h3>
                    <button type="button" class="close-add-menu p-2 hover:bg-gray-100 rounded">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="add-menu-form" class="p-4 space-y-4" enctype="multipart/form-data">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-muted-foreground">Name</label>
                            <input name="name" type="text" required class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary">
                            <p class="text-xs text-red-600 mt-1" data-error="name"></p>
                        </div>
                        <div>
                            <label class="text-sm text-muted-foreground">PAX</label>
                            <select name="pax_category" id="pax-category" class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary">
                                <option value="6-8">6-8 pax</option>
                                <option value="10-15">10-15 pax</option>
                                <option value="per">per pieces</option>
                            </select>
                            <p class="text-xs text-red-600 mt-1" data-error="pax"></p>
                        </div>
                        <div id="pieces-wrap" class="hidden">
                            <label class="text-sm text-muted-foreground">Pieces count</label>
                            <input name="pieces_count" id="pieces-count" type="number" min="1" step="1" placeholder="e.g., 6" class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary">
                        </div>
                        <div>
                            <label class="text-sm text-muted-foreground">Price (₱)</label>
                            <input name="price" type="number" min="0" step="0.01" required class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary">
                            <p class="text-xs text-red-600 mt-1" data-error="price"></p>
                        </div>
                        <div>
                            <label class="text-sm text-muted-foreground">Availability</label>
                            <select name="availability" class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary">
                                <option value="1" selected>Available</option>
                                <option value="0">Unavailable</option>
                            </select>
                            <p class="text-xs text-red-600 mt-1" data-error="availability"></p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm text-muted-foreground">Description</label>
                            <textarea name="description" rows="3" class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary"></textarea>
                            <p class="text-xs text-red-600 mt-1" data-error="description"></p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm text-muted-foreground">Photo</label>
                            <input id="menu-photo" name="photo" type="file" accept="image/*" class="block w-full mt-1 text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-green-700" />
                            <div class="mt-3 flex items-start gap-3">
                                <img id="photo-preview" src="" alt="Preview" class="hidden w-24 h-24 object-cover rounded border" />
                                <div class="flex flex-col gap-2">
                                    <p class="text-xs text-muted-foreground">Recommended: square image 512x512+. Supported formats: JPG, PNG, WebP.</p>
                                    <button type="button" id="photo-clear" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md border border-gray-200 text-xs hover:bg-gray-50">
                                        <i class="fas fa-eraser fa-xs"></i>
                                        Clear photo
                                    </button>
                                </div>
                            </div>
                            <p class="text-xs text-red-600 mt-1" data-error="photo"></p>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-2 border-t">
                        <button type="button" class="close-add-menu px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-green-700 disabled:opacity-60" id="add-menu-submit">Save</button>
                    </div>
                    <p class="text-sm mt-2" id="add-menu-message"></p>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Menu Modal -->
    <div id="edit-menu-backdrop" class="fixed inset-0 bg-black/40 opacity-0 pointer-events-none transition-opacity duration-200" style="display:none" aria-hidden="true"></div>
    <div id="edit-menu-modal" class="fixed inset-0 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-200" style="display:none" aria-hidden="true">
        <div class="dialog w-full max-w-xl mx-4 scale-95 transition-transform duration-200">
            <div class="bg-white rounded-lg shadow-xl">
                <div class="flex items-center justify-between px-4 py-3 border-b">
                    <h3 class="text-lg font-semibold text-primary">Edit Menu Item</h3>
                    <button type="button" class="close-edit-menu p-2 hover:bg-gray-100 rounded">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="edit-menu-form" class="p-4 space-y-4" enctype="multipart/form-data">
                    <input type="hidden" name="menu_id" id="edit-menu-id" />
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-muted-foreground">Name</label>
                            <input name="name" id="edit-name" type="text" required class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary">
                        </div>
                        <div>
                            <label class="text-sm text-muted-foreground">PAX</label>
                            <select name="pax_category" id="edit-pax-category" class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary">
                                <option value="6-8">6-8 pax</option>
                                <option value="10-15">10-15 pax</option>
                                <option value="per">per pieces</option>
                            </select>
                        </div>
                        <div id="edit-pieces-wrap" class="hidden">
                            <label class="text-sm text-muted-foreground">Pieces count</label>
                            <input name="pieces_count" id="edit-pieces-count" type="number" min="1" step="1" placeholder="e.g., 6" class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary">
                        </div>
                        <div>
                            <label class="text-sm text-muted-foreground">Price (₱)</label>
                            <input name="price" id="edit-price" type="number" min="0" step="0.01" required class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary">
                        </div>
                        <div>
                            <label class="text-sm text-muted-foreground">Availability</label>
                            <select name="availability" id="edit-availability" class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary">
                                <option value="1">Available</option>
                                <option value="0">Unavailable</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm text-muted-foreground">Description</label>
                            <textarea name="description" id="edit-description" rows="3" class="w-full mt-1 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:border-primary focus:ring-1 focus:ring-primary"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm text-muted-foreground">Photo (optional)</label>
                            <input id="edit-menu-photo" name="photo" type="file" accept="image/*" class="block w-full mt-1 text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-green-700" />
                            <div class="mt-3 flex items-start gap-3">
                                <img id="edit-photo-preview" src="" alt="Preview" class="hidden w-24 h-24 object-cover rounded border" />
                                <div class="flex flex-col gap-2">
                                    <p class="text-xs text-muted-foreground">Leave empty to keep current photo.</p>
                                    <button type="button" id="edit-photo-clear" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md border border-gray-200 text-xs hover:bg-gray-50">
                                        <i class="fas fa-eraser fa-xs"></i>
                                        Clear new photo
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-2 border-t">
                        <button type="button" class="close-edit-menu px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white hover:bg-green-700 disabled:opacity-60" id="edit-menu-submit">Save changes</button>
                    </div>
                    <p class="text-sm mt-2" id="edit-menu-message"></p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>