<?php
require_once __DIR__ . '/../classes/database.php';

// Build menu items and categories from DB
$db = new database();
$pdo = $db->opencon();

// Categories list for filter
$categories = ['All'];
$availableCategories = [];
try {
    $catStmt = $pdo->query("SELECT category_name FROM category ORDER BY category_name ASC");
    $availableCategories = array_map(fn($r) => $r['category_name'], $catStmt->fetchAll());
    $categories = array_merge($categories, $availableCategories);
} catch (Throwable $e) {
    // keep just 'All' on failure
}

// Helper: derive category from menu_name using keywords; only return if in DB categories
function derive_category_name(string $name, array $validCats): ?string {
    $n = strtolower($name);
    $map = [
        'Beef' => ['beef', 'bulalo', 'steak'],
        'Pork' => ['pork', 'liempo', 'humba', 'binagoongan', 'menudo', 'dinuguan', 'pochero', 'estofado', 'kare-kare (pork)', 'tokwa\'t baboy', 'spare ribs', 'bbq spare ribs', 'baby back ribs', 'bicol express', 'bicol'],
        'Chicken' => ['chicken', 'manok', 'cordon bleu', 'lollipop'],
        'Seafood' => ['seafood', 'shrimp', 'tahong', 'bangus', 'fish', 'salvatore', 'tuna'],
        'Pasta' => ['pasta', 'spaghetti', 'carbonara', 'pesto', 'pansit', 'pancit'],
        'Vegetables' => ['vegg', 'vegetable', 'vegies', 'veggies', 'laing', 'chopsuey', 'pakbet', 'tokwa', 'lumpiang'],
        'Best Sellers' => ['best seller']
    ];
    foreach ($map as $cat => $keywords) {
        if (!in_array($cat, $validCats, true)) continue;
        foreach ($keywords as $kw) {
            if (strpos($n, $kw) !== false) return $cat;
        }
    }
    return null;
}

// Fetch menu items
$menuItems = [];
try {
    $sql = "
        SELECT m.menu_id, m.menu_name, m.menu_desc, m.menu_pax, m.menu_price, m.menu_pic, m.menu_avail,
               (
                   SELECT c.category_name
                   FROM menucategory mc2
                   JOIN category c ON c.category_id = mc2.category_id
                   WHERE mc2.menu_id = m.menu_id
                   ORDER BY c.category_name ASC
                   LIMIT 1
               ) AS category_name
        FROM menu m
        ORDER BY m.created_at DESC, m.menu_id DESC
    ";
    $rows = $pdo->query($sql)->fetchAll();
    foreach ($rows as $r) {
        // Resolve image or fallback
        $pic = trim((string)($r['menu_pic'] ?? ''));
        $imgRel = '../menu/' . ($pic !== '' ? $pic : '');
        $imgFs = __DIR__ . '/../menu/' . ($pic !== '' ? $pic : '');
        if ($pic === '' || !file_exists($imgFs)) {
            $imgRel = '../images/logo.png';
        }

        // Determine category
        $derived = derive_category_name((string)$r['menu_name'], $availableCategories);
        $categoryName = $derived ?? ($r['category_name'] ?? 'Uncategorized');
        $isBest = ((string)($r['category_name'] ?? '') === 'Best Sellers');

        $menuItems[] = [
            'id' => (int)$r['menu_id'],
            'name' => (string)$r['menu_name'],
            'description' => (string)($r['menu_desc'] ?? ''),
            'price' => (float)$r['menu_price'],
            'image' => $imgRel,
            'category' => $categoryName,
            'servings' => (string)($r['menu_pax'] ?? ''),
            'prepTime' => '—',
            'popular' => $isBest,
            'rating' => 5.0,
            'reviews' => 0
        ];
    }
} catch (Throwable $e) {
    // On failure, leave empty; UI will show no items when searching
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sandok ni Binggay - Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --font-size: 16px;
            --background: #fefffe;
            --foreground: #1a2e1a;
            --card: #ffffff;
            --primary: #1B4332;
            --primary-foreground: #ffffff;
            --muted: #f8f8f6;
            --muted-foreground: #6b7062;
            --accent: #D4AF37;
            --accent-foreground: #1a2e1a;
            --border: rgba(27, 67, 50, 0.1);
            --radius: 0.625rem;
        }

        body {
            background: linear-gradient(to bottom, var(--background), #f8f8f6);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        .text-primary { color: var(--primary); }
        .text-muted-foreground { color: var(--muted-foreground); }
        .bg-primary { background-color: var(--primary); }
        .bg-accent { background-color: var(--accent); }
        .border-primary { border-color: var(--primary); }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
            }
            to {
                transform: translateX(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out;
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        .menu-card {
            transition: all 0.3s ease;
        }

        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .menu-card:hover .menu-image {
            transform: scale(1.1);
        }

        .menu-card:hover .menu-overlay {
            opacity: 1;
        }

        .menu-card:hover .add-btn {
            opacity: 1;
            transform: translateY(0);
        }

        .menu-image {
            transition: transform 0.4s ease;
        }

        .menu-overlay {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .add-btn {
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 1000;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease-out;
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            animation: scaleIn 0.3s ease-out;
            max-height: 90vh;
            overflow-y: auto;
        }

        /* Cart Sidebar */
        .cart-sidebar {
            position: fixed;
            top: 0;
            right: -100%;
            width: 100%;
            max-width: 28rem;
            height: 100vh;
            background: white;
            box-shadow: -4px 0 6px -1px rgba(0, 0, 0, 0.1);
            transition: right 0.3s ease;
            z-index: 1001;
        }

        .cart-sidebar.active {
            right: 0;
        }

        .cart-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .cart-backdrop.active {
            display: block;
        }

        /* Badge pulse animation */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Scrollbar hide */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Stagger animation for cards */
        .menu-card {
            animation: fadeInUp 0.5s ease-out backwards;
        }

        .menu-card:nth-child(1) { animation-delay: 0.05s; }
        .menu-card:nth-child(2) { animation-delay: 0.1s; }
        .menu-card:nth-child(3) { animation-delay: 0.15s; }
        .menu-card:nth-child(4) { animation-delay: 0.2s; }
        .menu-card:nth-child(5) { animation-delay: 0.25s; }
        .menu-card:nth-child(6) { animation-delay: 0.3s; }
        .menu-card:nth-child(7) { animation-delay: 0.35s; }
        .menu-card:nth-child(8) { animation-delay: 0.4s; }
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
                        'border': 'rgba(27, 67, 50, 0.1)'
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-md border-b border-gray-200">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <!-- Logo -->
                <div class="flex items-center gap-3 animate-fade-in-up">
                    <img src="../images/logo.png" 
                         alt="Sandok ni Binggay" 
                         class="w-16 h-16 rounded-full border-4 border-primary shadow-lg object-cover">
                    <div class="hidden md:block">
                        <h1 class="text-2xl font-medium text-primary">Sandok ni Binggay</h1>
                        <p class="text-sm text-muted-foreground">Nothing Beats Home-Cooked Meals</p>
                    </div>
                </div>

    <!-- Floating Cart Button (keeps cart accessible and preserves cartBadge) -->
    <button onclick="toggleCart()" class="fixed bottom-6 right-6 z-50 bg-primary text-white rounded-full w-14 h-14 shadow-lg hover:bg-green-800 transition-colors flex items-center justify-center relative">
        <i class="fas fa-shopping-cart text-xl"></i>
        <span id="cartBadge" class="hidden absolute -top-2 -right-2 bg-amber-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold"></span>
    </button>

    <!-- Hero Banner -->
    <div class="bg-gradient-to-r from-primary to-green-800 text-white py-12 animate-fade-in">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-medium text-white mb-3">Delicious Home-Cooked Catering</h2>
            <p class="text-white/90 max-w-2xl mx-auto mb-6">
                Experience authentic Filipino cuisine and international favorites, perfectly prepared for your special occasions
            </p>
            <div class="flex items-center justify-center gap-6 flex-wrap text-sm">
                <div class="flex items-center gap-2">
                    <i class="fas fa-envelope"></i>
                    <span>riatriumfo06@gmail.com</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-phone"></i>
                    <span>0919-230-8344</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Bar (moved here from header to keep search working) -->
    <div class="bg-white/90 backdrop-blur-sm border-b border-gray-200">
        <div class="container mx-auto px-4 py-4">
            <div class="relative max-w-2xl mx-auto">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input type="text"
                       id="searchInput"
                       placeholder="Search menu items..."
                       class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none">
            </div>
        </div>
    </div>

    <!-- Category Filter -->
    <div class="sticky top-20 z-40 bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-sm">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide">
                <i class="fas fa-filter text-gray-400 flex-shrink-0"></i>
                <?php foreach($categories as $category): ?>
                <button onclick="filterCategory('<?php echo $category; ?>')" 
                        class="category-btn px-4 py-2 rounded-full whitespace-nowrap transition-all hover:scale-105 <?php echo $category === 'All' ? 'bg-primary text-white shadow-md' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'; ?>"
                        data-category="<?php echo $category; ?>">
                    <?php echo $category; ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Menu Grid -->
    <div class="container mx-auto px-4 py-8">
        <!-- Popular Items -->
        <div id="popularSection" class="mb-12 animate-fade-in-up">
            <div class="flex items-center gap-2 mb-6">
                <i class="fas fa-star text-amber-500"></i>
                <h3 class="text-2xl font-medium text-primary">Popular Items</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach($menuItems as $item): ?>
                    <?php if($item['popular']): ?>
                        <?php menu_card_template(); ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- All Items -->
        <div class="animate-fade-in-up">
            <h3 id="sectionTitle" class="text-2xl font-medium text-primary mb-6">All Menu Items</h3>
            <div id="menuGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach($menuItems as $item): ?>
                    <?php menu_card_template(); ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="noResults" class="hidden text-center py-16">
            <p class="text-muted-foreground">No items found matching your search.</p>
        </div>
    </div>

    <!-- Item Detail Modal -->
    <div id="itemModal" class="modal">
        <div class="modal-content bg-white rounded-lg max-w-3xl w-full mx-4">
            <div id="modalContent"></div>
        </div>
    </div>

    <!-- Cart Sidebar -->
    <div class="cart-backdrop" id="cartBackdrop" onclick="toggleCart()"></div>
    <div class="cart-sidebar" id="cartSidebar">
        <div class="flex flex-col h-full">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-2xl font-medium text-primary">Your Cart</h2>
                <button onclick="toggleCart()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div id="cartItems" class="flex-1 overflow-y-auto p-6">
                <div id="emptyCart" class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                        <p class="text-muted-foreground">Your cart is empty</p>
                    </div>
                </div>
                <div id="cartList" class="hidden space-y-4"></div>
            </div>

            <div id="cartFooter" class="hidden border-t border-gray-200 p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <span class="font-medium">Total:</span>
                    <span id="cartTotal" class="text-2xl font-bold text-primary">₱0</span>
                </div>
                <button onclick="openCheckout()" class="w-full bg-primary hover:bg-green-800 text-white py-3 rounded-lg transition-colors font-medium">
                    Proceed to Checkout
                </button>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div id="checkoutModal" class="modal">
        <div class="modal-content bg-white rounded-lg max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-medium text-primary">Checkout</h2>
                    <button onclick="closeCheckout()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form id="checkoutForm" onsubmit="processOrder(event)">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Left Column: Customer Information -->
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium text-primary mb-4">Customer Information</h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-2">Full Name <span class="text-red-500">*</span></label>
                                        <input type="text" id="customerName" required 
                                               class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none"
                                               placeholder="Juan Dela Cruz">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-2">Phone Number <span class="text-red-500">*</span></label>
                                        <input type="tel" id="customerPhone" required 
                                               class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none"
                                               placeholder="0919-123-4567">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-2">Email Address <span class="text-red-500">*</span></label>
                                        <input type="email" id="customerEmail" required 
                                               class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none"
                                               placeholder="juan@example.com">
                                    </div>

                                    <div>
                                        <h4 class="text-sm font-medium mb-2">Delivery Address <span class="text-red-500">*</span></h4>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                            <div>
                                                <label class="block text-xs text-gray-600 mb-1">Street</label>
                                                <input type="text" id="oa_street" required
                                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"
                                                       placeholder="House No. / Street / Barangay">
                                            </div>
                                            <div>
                                                <label class="block text-xs text-gray-600 mb-1">City</label>
                                                <select id="oa_city" required
                                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none bg-white">
                                                    <option value="Lipa City">Lipa City</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs text-gray-600 mb-1">Province</label>
                                                <select id="oa_province" required
                                                        class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none bg-white">
                                                    <option value="Batangas">Batangas</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-2">Event Date & Time <span class="text-red-500">*</span></label>
                                        <input type="datetime-local" id="eventDate" required 
                                               class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium mb-2">Special Instructions (Optional)</label>
                                        <textarea id="specialInstructions" rows="3"
                                                  class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none resize-none"
                                                  placeholder="Any dietary restrictions, allergies, or special requests..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <h3 class="text-lg font-medium text-primary mb-4">Payment Method</h3>
                                <div class="space-y-3">
                                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-colors">
                                        <input type="radio" name="paymentMethod" value="cod" checked class="w-4 h-4 text-primary">
                                        <div class="ml-3 flex-1">
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-money-bill-wave text-primary"></i>
                                                <span class="font-medium">Cash on Delivery</span>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">Pay when your order is delivered</p>
                                        </div>
                                    </label>

                                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-colors">
                                        <input type="radio" name="paymentMethod" value="gcash" class="w-4 h-4 text-primary">
                                        <div class="ml-3 flex-1">
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-mobile-alt text-primary"></i>
                                                <span class="font-medium">GCash</span>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">Send payment via GCash</p>
                                        </div>
                                    </label>

                                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-colors">
                                        <input type="radio" name="paymentMethod" value="bank" class="w-4 h-4 text-primary">
                                        <div class="ml-3 flex-1">
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-university text-primary"></i>
                                                <span class="font-medium">Bank Transfer</span>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">Transfer to our bank account</p>
                                        </div>
                                    </label>

                                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-primary transition-colors">
                                        <input type="radio" name="paymentMethod" value="card" class="w-4 h-4 text-primary">
                                        <div class="ml-3 flex-1">
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-credit-card text-primary"></i>
                                                <span class="font-medium">Credit/Debit Card</span>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">Pay securely with your card</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Order Summary -->
                        <div>
                            <h3 class="text-lg font-medium text-primary mb-4">Order Summary</h3>
                            
                            <div class="bg-gray-50 rounded-lg p-4 mb-4 max-h-96 overflow-y-auto">
                                <div id="checkoutItems" class="space-y-3"></div>
                            </div>

                            <div class="space-y-3 border-t border-gray-200 pt-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span id="checkoutSubtotal" class="font-medium">₱0</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Delivery Fee:</span>
                                    <span id="checkoutDelivery" class="font-medium">₱200</span>
                                </div>
                                <div class="flex justify-between text-lg font-bold text-primary border-t border-gray-200 pt-3">
                                    <span>Total:</span>
                                    <span id="checkoutTotal">₱0</span>
                                </div>
                            </div>

                            <!-- Important Notes -->
                            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex gap-2">
                                    <i class="fas fa-info-circle text-yellow-600 mt-1"></i>
                                    <div class="text-sm text-yellow-800">
                                        <p class="font-medium mb-2">Important Notes:</p>
                                        <ul class="list-disc list-inside space-y-1 text-xs">
                                            <li>Orders must be placed at least 24 hours in advance</li>
                                            <li>A 50% deposit may be required for large orders</li>
                                            <li>We only deliver through Grab</li>
                                            <li>Delivery fee is shouldered by the customer</li>
                                            <li>We'll contact you to confirm your order details</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full mt-6 bg-primary hover:bg-green-800 text-white py-3 rounded-lg transition-colors font-medium">
                                <i class="fas fa-check-circle mr-2"></i>
                                Place Order
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content bg-white rounded-lg max-w-md w-full mx-4">
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-3xl text-green-600"></i>
                </div>
                <h2 class="text-2xl font-medium text-primary mb-2">Order Placed Successfully!</h2>
                <p class="text-gray-600 mb-6">Thank you for your order. We'll contact you shortly to confirm the details.</p>
                
                <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                    <p class="text-sm text-gray-600 mb-2">Order Reference:</p>
                    <p id="orderReference" class="text-lg font-bold text-primary"></p>
                </div>

                <div class="space-y-3">
                    <button onclick="closeSuccess()" class="w-full bg-primary hover:bg-green-800 text-white py-3 rounded-lg transition-colors font-medium">
                        Continue Shopping
                    </button>
                    <button onclick="window.print()" class="w-full border-2 border-gray-300 hover:border-primary text-gray-700 hover:text-primary py-3 rounded-lg transition-colors font-medium">
                        <i class="fas fa-print mr-2"></i>Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-primary text-white py-8 mt-16">
        <div class="container mx-auto px-4 text-center">
            <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=150&h=150&fit=crop&crop=center" 
                 alt="Sandok ni Binggay" 
                 class="w-20 h-20 mx-auto mb-4 rounded-full border-4 border-white/20 object-cover">
            <h3 class="text-xl font-medium text-white mb-2">Sandok ni Binggay</h3>
            <p class="text-white/80 mb-4">Nothing Beats Home-Cooked Meals</p>
            <div class="text-sm text-white/80">
                <span>Party Trays • Packed Meals • Foods for Caterings • Grazing Table Setup</span>
            </div>
        </div>
    </footer>

    <script>
        // Menu items data as JavaScript
        const menuItems = <?php echo json_encode($menuItems); ?>;
        let cart = [];
        let selectedCategory = 'All';

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            renderMenu();
            setupSearch();
        });

        // Filter by category
        function filterCategory(category) {
            selectedCategory = category;
            
            // Update active button
            document.querySelectorAll('.category-btn').forEach(btn => {
                if(btn.dataset.category === category) {
                    btn.className = 'category-btn px-4 py-2 rounded-full whitespace-nowrap transition-all hover:scale-105 bg-primary text-white shadow-md';
                } else {
                    btn.className = 'category-btn px-4 py-2 rounded-full whitespace-nowrap transition-all hover:scale-105 bg-gray-100 hover:bg-gray-200 text-gray-700';
                }
            });

            // Update section title
            document.getElementById('sectionTitle').textContent = category === 'All' ? 'All Menu Items' : category;
            
            // Show/hide popular section
            document.getElementById('popularSection').style.display = category === 'All' ? 'block' : 'none';
            
            renderMenu();
        }

        // Render menu items
        function renderMenu() {
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();
            const filteredItems = menuItems.filter(item => {
                const matchesCategory = selectedCategory === 'All' || item.category === selectedCategory;
                const matchesSearch = item.name.toLowerCase().includes(searchQuery) || 
                                     item.description.toLowerCase().includes(searchQuery);
                return matchesCategory && matchesSearch;
            });

            const menuGrid = document.getElementById('menuGrid');
            const noResults = document.getElementById('noResults');

            if(filteredItems.length === 0) {
                menuGrid.innerHTML = '';
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
                menuGrid.innerHTML = filteredItems.map(item => createMenuCard(item)).join('');
            }
        }

        // Create menu card HTML
        function createMenuCard(item) {
            return `
                <div class="menu-card bg-white rounded-lg overflow-hidden border border-gray-200 cursor-pointer">
                    <div class="relative overflow-hidden h-48" onclick="openItemModal(${item.id})">
                        <img src="${item.image}" alt="${item.name}" class="menu-image w-full h-full object-cover">
                        <div class="menu-overlay absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        ${item.popular ? '<span class="absolute top-3 left-3 bg-amber-500 text-white px-3 py-1 rounded-full text-xs font-medium shadow-lg"><i class="fas fa-star mr-1"></i>Popular</span>' : ''}
                        <button onclick="event.stopPropagation(); addToCart(${item.id})" class="add-btn absolute bottom-3 right-3 bg-white text-primary hover:bg-primary hover:text-white px-4 py-2 rounded-lg shadow-lg text-sm font-medium transition-colors">
                            <i class="fas fa-plus mr-1"></i>Add
                        </button>
                    </div>
                    <div class="p-4" onclick="openItemModal(${item.id})">
                        <h4 class="font-medium text-primary mb-2 hover:text-amber-500 transition-colors">${item.name}</h4>
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">${item.description}</p>
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-1 text-sm text-gray-500">
                                <i class="fas fa-star text-amber-500"></i>
                                <span class="font-medium text-gray-700">${item.rating}</span>
                                <span class="text-xs">(${item.reviews})</span>
                            </div>
                            <p class="text-xl font-bold text-primary">₱${item.price.toLocaleString()}</p>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-500">
                            <div class="flex items-center gap-1">
                                <i class="fas fa-users"></i>
                                <span>${item.servings}</span>
                            </div>
                            <span>•</span>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-clock"></i>
                                <span>${item.prepTime}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Setup search
        function setupSearch() {
            document.getElementById('searchInput').addEventListener('input', renderMenu);
        }

        // Open item modal
        function openItemModal(itemId) {
            const item = menuItems.find(i => i.id === itemId);
            if(!item) return;

            const modalContent = `
                <div class="relative h-64 md:h-96">
                    <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover">
                    ${item.popular ? '<span class="absolute top-4 right-4 bg-amber-500 text-white px-3 py-1 rounded-full text-sm font-medium"><i class="fas fa-star mr-1"></i>Popular</span>' : ''}
                </div>
                <div class="p-6">
                    <h2 class="text-2xl font-medium text-primary mb-4">${item.name}</h2>
                    <div class="flex items-center gap-4 text-sm text-gray-500 mb-6">
                        <div class="flex items-center gap-1">
                            <i class="fas fa-star text-amber-500"></i>
                            <span class="font-medium text-gray-700">${item.rating}</span>
                            <span>(${item.reviews} reviews)</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <i class="fas fa-users"></i>
                            <span>${item.servings}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <i class="fas fa-clock"></i>
                            <span>${item.prepTime}</span>
                        </div>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-6">${item.description}</p>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div>
                            <p class="text-sm text-gray-500">Price</p>
                            <p class="text-3xl font-bold text-primary">₱${item.price.toLocaleString()}</p>
                        </div>
                        <button onclick="addToCart(${item.id}); closeModal(); toggleCart()" class="bg-primary hover:bg-green-800 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add to Cart
                        </button>
                    </div>
                </div>
                <button onclick="closeModal()" class="absolute top-4 right-4 bg-white text-gray-600 hover:text-gray-900 w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            `;

            document.getElementById('modalContent').innerHTML = modalContent;
            document.getElementById('itemModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Close modal
        function closeModal() {
            document.getElementById('itemModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Toggle cart
        function toggleCart() {
            document.getElementById('cartSidebar').classList.toggle('active');
            document.getElementById('cartBackdrop').classList.toggle('active');
            document.body.style.overflow = document.getElementById('cartSidebar').classList.contains('active') ? 'hidden' : 'auto';
        }

        // Add to cart
        function addToCart(itemId) {
            const item = menuItems.find(i => i.id === itemId);
            if(!item) return;

            const existingItem = cart.find(i => i.id === itemId);
            if(existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({...item, quantity: 1});
            }

            updateCart();
        }

        // Update quantity
        function updateQuantity(itemId, quantity) {
            if(quantity === 0) {
                cart = cart.filter(item => item.id !== itemId);
            } else {
                const item = cart.find(i => i.id === itemId);
                if(item) item.quantity = quantity;
            }
            updateCart();
        }

        // Update cart display
        function updateCart() {
            const cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);
            const cartTotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            // Update badge
            const badge = document.getElementById('cartBadge');
            if(cartCount > 0) {
                badge.textContent = cartCount;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }

            // Update cart items
            if(cart.length === 0) {
                document.getElementById('emptyCart').classList.remove('hidden');
                document.getElementById('cartList').classList.add('hidden');
                document.getElementById('cartFooter').classList.add('hidden');
            } else {
                document.getElementById('emptyCart').classList.add('hidden');
                document.getElementById('cartList').classList.remove('hidden');
                document.getElementById('cartFooter').classList.remove('hidden');

                const cartList = document.getElementById('cartList');
                cartList.innerHTML = cart.map(item => `
                    <div class="flex gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <img src="${item.image}" alt="${item.name}" class="w-20 h-20 object-cover rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-sm mb-1">${item.name}</h4>
                            <p class="text-sm text-primary font-semibold">₱${item.price.toLocaleString()}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <button onclick="updateQuantity(${item.id}, ${item.quantity - 1})" class="w-7 h-7 border border-gray-300 rounded flex items-center justify-center hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <span class="text-sm font-medium w-8 text-center">${item.quantity}</span>
                                <button onclick="updateQuantity(${item.id}, ${item.quantity + 1})" class="w-7 h-7 border border-gray-300 rounded flex items-center justify-center hover:bg-gray-100 transition-colors">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');

                document.getElementById('cartTotal').textContent = '₱' + cartTotal.toLocaleString();
            }
        }

        // Close modal when clicking outside
        document.getElementById('itemModal').addEventListener('click', function(e) {
            if(e.target === this) closeModal();
        });

        // Checkout functions
        function openCheckout() {
            if(cart.length === 0) {
                alert('Your cart is empty!');
                return;
            }

            // Populate checkout items
            const checkoutItems = document.getElementById('checkoutItems');
            checkoutItems.innerHTML = cart.map(item => `
                <div class="flex gap-3 pb-3 border-b border-gray-200">
                    <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium text-sm">${item.name}</h4>
                        <p class="text-xs text-gray-500">${item.servings}</p>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-xs text-gray-600">Qty: ${item.quantity}</span>
                            <span class="text-sm font-semibold text-primary">₱${(item.price * item.quantity).toLocaleString()}</span>
                        </div>
                    </div>
                </div>
            `).join('');

            // Calculate totals
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const deliveryFee = 200;
            const total = subtotal + deliveryFee;

            document.getElementById('checkoutSubtotal').textContent = '₱' + subtotal.toLocaleString();
            document.getElementById('checkoutDelivery').textContent = '₱' + deliveryFee.toLocaleString();
            document.getElementById('checkoutTotal').textContent = '₱' + total.toLocaleString();

            // Set minimum date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            document.getElementById('eventDate').min = tomorrow.toISOString().slice(0, 16);

            document.getElementById('checkoutModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeCheckout() {
            document.getElementById('checkoutModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function processOrder(event) {
            event.preventDefault();

            // Gather form fields aligned to DB
            const name = document.getElementById('customerName').value.trim();
            const phone = document.getElementById('customerPhone').value.trim();
            const email = document.getElementById('customerEmail').value.trim();
            const oa_street = document.getElementById('oa_street').value.trim();
            const oa_city = document.getElementById('oa_city').value.trim();
            const oa_province = document.getElementById('oa_province').value.trim();
            const eventDT = document.getElementById('eventDate').value; // yyyy-MM-ddTHH:mm
            const order_needed = eventDT ? eventDT.split('T')[0] : '';
            const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
            const specialInstructions = document.getElementById('specialInstructions').value.trim();

            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const deliveryFee = 200;
            const total = subtotal + deliveryFee;

            const payload = {
                customer_name: name,
                customer_phone: phone,
                customer_email: email,
                oa_street,
                oa_city,
                oa_province,
                order_needed,
                payment_method: paymentMethod,
                notes: specialInstructions,
                items: cart.map(it => ({ menu_id: it.id, quantity: it.quantity, price: it.price })),
                subtotal,
                delivery_fee: deliveryFee,
                total
            };

            fetch('submit_order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(r => r.json())
            .then(data => {
                if (!data || !data.success) {
                    throw new Error(data && data.message ? data.message : 'Order failed');
                }
                document.getElementById('orderReference').textContent = data.reference || ('SB-' + data.order_id);
                closeCheckout();
                document.getElementById('successModal').classList.add('active');
                cart = [];
                updateCart();
            })
            .catch(err => {
                console.error('Checkout error:', err);
                alert('Sorry, we could not place your order. Please try again.');
            });
        }

        function closeSuccess() {
            document.getElementById('successModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close modals when clicking outside
        document.getElementById('checkoutModal').addEventListener('click', function(e) {
            if(e.target === this) closeCheckout();
        });

        document.getElementById('successModal').addEventListener('click', function(e) {
            if(e.target === this) closeSuccess();
        });

        // Payment method selection visual feedback
        document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('input[name="paymentMethod"]').forEach(r => {
                    r.parentElement.classList.remove('border-primary', 'bg-green-50');
                    r.parentElement.classList.add('border-gray-200');
                });
                if(this.checked) {
                    this.parentElement.classList.add('border-primary', 'bg-green-50');
                    this.parentElement.classList.remove('border-gray-200');
                }
            });
        });
    </script>
</body>
</html>

<?php
// Menu card template for PHP rendering
function menu_card_template() {
    global $item;
    ?>
    <div class="menu-card bg-white rounded-lg overflow-hidden border border-gray-200 cursor-pointer">
        <div class="relative overflow-hidden h-48" onclick="openItemModal(<?php echo $item['id']; ?>)">
            <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="menu-image w-full h-full object-cover">
            <div class="menu-overlay absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <?php if($item['popular']): ?>
                <span class="absolute top-3 left-3 bg-amber-500 text-white px-3 py-1 rounded-full text-xs font-medium shadow-lg">
                    <i class="fas fa-star mr-1"></i>Popular
                </span>
            <?php endif; ?>
            <button onclick="event.stopPropagation(); addToCart(<?php echo $item['id']; ?>)" class="add-btn absolute bottom-3 right-3 bg-white text-primary hover:bg-primary hover:text-white px-4 py-2 rounded-lg shadow-lg text-sm font-medium transition-colors">
                <i class="fas fa-plus mr-1"></i>Add
            </button>
        </div>
        <div class="p-4" onclick="openItemModal(<?php echo $item['id']; ?>)">
            <h4 class="font-medium text-primary mb-2 hover:text-amber-500 transition-colors"><?php echo $item['name']; ?></h4>
            <p class="text-sm text-gray-600 mb-3 line-clamp-2"><?php echo $item['description']; ?></p>
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-1 text-sm text-gray-500">
                    <i class="fas fa-star text-amber-500"></i>
                    <span class="font-medium text-gray-700"><?php echo $item['rating']; ?></span>
                    <span class="text-xs">(<?php echo $item['reviews']; ?>)</span>
                </div>
                <p class="text-xl font-bold text-primary">₱<?php echo number_format($item['price']); ?></p>
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <div class="flex items-center gap-1">
                    <i class="fas fa-users"></i>
                    <span><?php echo $item['servings']; ?></span>
                </div>
                <span>•</span>
                <div class="flex items-center gap-1">
                    <i class="fas fa-clock"></i>
                    <span><?php echo $item['prepTime']; ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>