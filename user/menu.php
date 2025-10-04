<?php

session_start();
require_once('../classes/database.php');
$con = new database();

$categories = $con->getAllCategories();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Menu - Sandok ni Binggay</title>
  <link rel="stylesheet" href="../css/menu.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body id="top">
<nav class="navbar navbar-expand-lg navbar-dark brand-color sticky-top shadow-sm py-3 animate__animated animate__fadeInDown">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2 fs-4 fw-bold rounded-pill px-3 py-1 bg-white text-success shadow-sm" href="#">
      <i class="fas fa-utensils"></i> Sandok ni Binggay
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navMenu">
      <ul class="navbar-nav text-center gap-2">
        <li class="nav-item">
          <a class="nav-link text-white position-relative" href="homepage.php">
            About
            <span class="nav-underline"></span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white position-relative" href="#top">
            Menu
            <span class="nav-underline"></span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white position-relative" href="cateringpackages.php">
            Catering
            <span class="nav-underline"></span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white position-relative" href="booking.php">
            Booking
            <span class="nav-underline"></span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container my-4">
  <!-- Category Filter Buttons -->
  <div class="mb-4 text-center">
    <button class="btn btn-outline-success category-btn active" data-category="all">All</button>
    <?php foreach ($categories as $cat): ?>
      <button class="btn btn-outline-success category-btn" data-category="cat-<?= $cat['category_id'] ?>">
        <?= htmlspecialchars($cat['category_name']) ?>
      </button>
    <?php endforeach; ?>
  </div>
  <div class="row">
    <div class="col-lg-9">
      <?php foreach ($categories as $cat): ?>
        <?php
          $menuItems = $con->getMenuItemsByCategory($cat['category_id']);
          if (count($menuItems) === 0) continue;
        ?>
        <div class="category-section" data-category="cat-<?= $cat['category_id'] ?>">
          <h3 class="mt-5 mb-3"><?= htmlspecialchars($cat['category_name']) ?></h3>
          <div class="row">
            <?php foreach ($menuItems as $item): ?>
              <div class="col-md-6 col-xl-4 mb-4">
                <div class="card shadow-sm h-100">
                  <img src="<?= !empty($item['menu_pics'][0]) ? '../images/menu/' . htmlspecialchars($item['menu_pics'][0]) : '../images/default_food.png' ?>" class="card-img-top" alt="<?= htmlspecialchars($item['menu_name']) ?>">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($item['menu_name']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($item['menu_desc']) ?></p>
                    <ul class="list-group list-group-flush mb-2">
                      <?php foreach ($item['pax_options'] as $i => $pax): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                          <span><?= htmlspecialchars($pax) ?></span>
                          <span class="fw-bold text-success">₱<?= number_format($item['price_options'][$i], 2) ?></span>
                          <button class="btn btn-sm btn-success ms-2 add-to-cart"
                            data-name="<?= htmlspecialchars($item['menu_name']) ?>"
                            data-pack="<?= htmlspecialchars($pax) ?>"
                            data-price="<?= htmlspecialchars($item['price_options'][$i]) ?>">
                            <i class="fas fa-plus"></i> Add to Plate
                          </button>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <!-- Sticky Cart -->
    <div class="col-lg-3">
      <div class="sticky-cart card shadow-sm">
        <div class="card-header text-center fw-bold">🧺 Your Plate</div>
        <ul id="cart-items" class="list-group list-group-flush"></ul>
        <button id="checkoutBtn" class="btn btn-primary w-100 mt-3" <?php if(empty($_SESSION['user_id'])) echo 'disabled'; ?>>Checkout</button>
        <?php if(empty($_SESSION['user_id'])): ?>
  <div class="alert alert-warning mt-2 text-center">
    Please <a href="../login.php">log in</a> or <a href="../registration.php">create an account</a> to place an order.
  </div>
<?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="paymentForm">
        <div class="modal-header">
          <h5 class="modal-title" id="checkoutModalLabel">Confirm Payment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p>Total Amount: <span id="checkoutTotal" class="fw-bold"></span></p>
          <div class="mb-3">
            <label for="order_needed" class="form-label">Date Needed</label>
            <input type="date" class="form-control" id="order_needed" name="order_needed" required>
          </div>
          <div class="mb-3">
            <label for="oa_street" class="form-label">Street</label>
            <input type="text" class="form-control" id="oa_street" name="oa_street" required>
          </div>
          <div class="mb-3">
            <label for="oa_city" class="form-label">City</label>
            <input type="text" class="form-control" id="oa_city" name="oa_city" required>
          </div>
          <div class="mb-3">
            <label for="oa_province" class="form-label">Province</label>
            <input type="text" class="form-control" id="oa_province" name="oa_province" required>
          </div>
          <div class="mb-3">
            <label for="pay_method" class="form-label">Select Payment Method</label>
            <select class="form-select" id="pay_method" name="pay_method" required>
              <option value="" selected disabled>Choose...</option>
              <option value="Cash">Cash</option>
              <option value="Online">Online</option>
              <option value="Credit">Credit</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Confirm & Pay</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Category filter logic
  const categoryButtons = document.querySelectorAll('.category-btn');
  const categorySections = document.querySelectorAll('.category-section');
  categoryButtons.forEach(button => {
    button.addEventListener('click', () => {
      const selected = button.getAttribute('data-category');
      categoryButtons.forEach(btn => btn.classList.remove('active'));
      button.classList.add('active');
      categorySections.forEach(section => {
        if (selected === "all" || section.getAttribute('data-category') === selected) {
          section.style.display = "block";
        } else {
          section.style.display = "none";
        }
      });
    });
  });

  // Cart logic
  const cart = [];
  function updateCart() {
    const cartList = document.getElementById('cart-items');
    cartList.innerHTML = '';
    cart.forEach((item, index) => {
      const li = document.createElement('li');
      li.className = 'list-group-item d-flex justify-content-between align-items-center';
      li.innerHTML = `
        ${item.name} (${item.pack})
        <span>₱${parseFloat(item.price).toFixed(2)}</span>
        <button class='btn btn-sm btn-danger ms-2' onclick='removeItem(${index})'>&times;</button>
      `;
      cartList.appendChild(li);
    });
  }
  window.removeItem = function(index) {
    cart.splice(index, 1);
    updateCart();
  }
  document.querySelectorAll('.add-to-cart').forEach((btn) => {
    btn.addEventListener('click', () => {
      const name = btn.getAttribute('data-name');
      const pack = btn.getAttribute('data-pack');
      const price = btn.getAttribute('data-price');
      cart.push({ name, pack, price });
      updateCart();
    });
  });

  // Checkout logic
  const checkoutBtn = document.getElementById('checkoutBtn');
  const checkoutModalEl = document.getElementById('checkoutModal');
  const checkoutTotal = document.getElementById('checkoutTotal');
  const paymentForm = document.getElementById('paymentForm');
  const orderNeededInput = document.getElementById('order_needed');
  let checkoutModal = null;

  if (checkoutModalEl) {
    checkoutModal = new bootstrap.Modal(checkoutModalEl);
  }

  if (checkoutBtn && checkoutModal && checkoutTotal && paymentForm) {
    checkoutBtn.addEventListener('click', function() {
      if (cart.length === 0) {
        Swal.fire({
          icon: 'warning',
          title: 'Your plate is empty!',
          text: 'Please add items before checking out.'
        });
        return;
      }
      let total = 0;
      cart.forEach(item => total += parseFloat(item.price));
      checkoutTotal.textContent = '₱' + total.toFixed(2);
      // Set default date needed to tomorrow
      if (orderNeededInput) {
        const today = new Date();
        today.setDate(today.getDate() + 1);
        orderNeededInput.value = today.toISOString().split('T')[0];
      }
      checkoutModal.show();
    });

    paymentForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const pay_method = document.getElementById('pay_method').value;
      let total = 0;
      cart.forEach(item => total += parseFloat(item.price));
      const order_needed = orderNeededInput.value;
      const oa_street = document.getElementById('oa_street').value;
      const oa_city = document.getElementById('oa_city').value;
      const oa_province = document.getElementById('oa_province').value;

      // --- Date validation: block today and tomorrow ---
      const today = new Date();
today.setHours(0,0,0,0);

const tomorrow = new Date(today);
tomorrow.setDate(today.getDate() + 1);

const neededDate = new Date(order_needed);
neededDate.setHours(0,0,0,0);

if (
  neededDate.getTime() === today.getTime() ||
  neededDate.getTime() === tomorrow.getTime() ||
  neededDate.getTime() < tomorrow.getTime()
) {
  Swal.fire({
    icon: 'error',
    title: 'Invalid Date',
    text: 'You cannot place an order for today or tomorrow. Please select a date at least 2 days from now.'
  });
  return;
}

      fetch('save_payment.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
          pay_amount: total,
          pay_method: pay_method,
          cart: cart,
          order_needed: order_needed,
          oa_street: oa_street,
          oa_city: oa_city,
          oa_province: oa_province
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
            icon: 'success',
            title: 'Payment Successful!',
            text: 'Thank you for your order.',
            confirmButtonText: 'OK'
          }).then(() => {
            cart.length = 0;
            updateCart();
            checkoutModal.hide();
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Payment Failed',
            text: data.message || 'Please try again.'
          });
        }
      })
      .catch(() => {
        Swal.fire({
          icon: 'error',
          title: 'Payment Failed',
          text: 'Please try again.'
        });
      });
    });
  }
});
</script>
</body>