<?php
require_once("../classes/database.php");
session_start();
$db = new database();
$sweetAlertConfig = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderName'])) {
    // Get and sanitize form values
    $fullName = trim($_POST['orderName']); // cp_name
    $venueName = trim($_POST['venueName']);
    $venueStreet = trim($_POST['venueStreet']);
    $venueCity = trim($_POST['venueCity']);
    $venueProvince = trim($_POST['venueProvince']);
    $phone = trim($_POST['orderPhone']); // cp_phone
    $eventDate = trim($_POST['orderDate']); // cp_date
    $package = trim($_POST['orderCategory']); // cp_price
    $note = trim($_POST['orderNotes']); // cp_desc

    // Get user_id from session
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Check if the date is already booked for catering
    if ($db->isCateringDateBooked($eventDate)) {
        $sweetAlertConfig = "
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Date Unavailable',
            text: 'Sorry, this date is already booked for catering. Please choose another date.',
            confirmButtonText: 'OK'
        });
        </script>";
    } else if ($userId && $fullName && $venueName && $venueStreet && $venueCity && $venueProvince && $phone && $eventDate && $package) {
        $cp_id = $db->bookCateringPackage(
            $userId,
            $fullName,
            $venueName,
            $venueStreet,
            $venueCity,
            $venueProvince,
            $phone,
            $eventDate,
            $package,
            $note
        );
        if ($cp_id) {
            header("Location: payment.php?cp_id=" . $cp_id);
            exit;
        } else {
            $sweetAlertConfig = "
            <script>
            Swal.fire({
                icon: 'error',
                title: 'Something went wrong',
                text: 'Failed to save booking. Please try again.',
                confirmButtonText: 'OK'
            });
            </script>";
        }
    } else {
        $sweetAlertConfig = "
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Missing Fields',
            text: 'Please fill in all required fields.',
            confirmButtonText: 'OK'
        });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Catering Services</title>
  <link rel="stylesheet" href="../css/cateringpackages.css"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600&display=swap" rel="stylesheet">
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
          <a class="nav-link text-white position-relative" href="menu.php">
            Menu
            <span class="nav-underline"></span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white position-relative" href="#top">
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
      <a href="../logout.php" class="btn btn-outline-light ms-lg-3 mt-3 mt-lg-0">Logout</a>
    </div>
  </div>
</nav>

  <header class="hero">
    <div class="hero-bg-slideshow">
      <img src="../images/catering/1.jpg" alt="Slide 1" class="active">
      <img src="../images/catering/4.jpg" alt="Slide 2">
      <img src="../images/catering/6.jpg" alt="Slide 3">
      <img src="../images/catering/8.jpg" alt="Slide 4">
      <img src="../images/catering/2.jpg" alt="Slide 5">
      <img src="../images/catering/5.jpg" alt="Slide 6">
      <img src="../images/catering/3.jpg" alt="Slide 7">
    </div>
    <div class="hero-content">
      <h1>Elegant Catering Services</h1>
      <p>Crafting unforgettable dining experiences for your most special day.</p>
    </div>
  </header>

  
  <section class="services">
    <h2>Our Packages</h2>
<div class="service-items">
  <!-- Package 1 -->
  <div class="service-card">
    <img src="../images/catering/8.jpg" alt="Classic Wedding Package" class="mb-3 rounded" style="width:100%;height:180px;object-fit:cover;">
    <h3>FOR 50 PAX</h3>
    <p>Perfect for intimate gatherings. Includes full meal, desserts, drinks, basic decor (flowers/balloons), complete setup, and attendants.</p>
    <ul class="list-unstyled mb-3">
      <li><i class="fa fa-check text-success"></i> Beef Menu</li>
      <li><i class="fa fa-check text-success"></i> Pork Menu</li>
      <li><i class="fa fa-check text-success"></i> Chicken Menu</li>
      <li><i class="fa fa-check text-success"></i> Rice</li>
      <li><i class="fa fa-check text-success"></i> Veggies or Pasta or Fish Fillet</li>
      <li><i class="fa fa-check text-success"></i> 50 Cups of Desserts</li>
      <li><i class="fa fa-check text-success"></i> Drinks</li>
      <li><i class="fa fa-check text-success"></i> Backdrop and Platform / Complete Setup</li>
      <li><i class="fa fa-check text-success"></i> Elegant Table Buffet</li>
      <li><i class="fa fa-check text-success"></i> 6 Chaffing Dish</li>
      <li><i class="fa fa-check text-success"></i> Banquet Complete Setup</li>
      <li><i class="fa fa-check text-success"></i> Tables and Chairs with cover</li>
      <li><i class="fa fa-check text-success"></i> Artificial Flowers and Balloons for Decoration</li>
      <li><i class="fa fa-check text-success"></i> 60 Pax Silverware and Dinnerware</li>
      <li><i class="fa fa-check text-success"></i> 2 Food Attendants</li>
      <li><i class="fa fa-check text-success"></i> Elegant Table Buffet</li>
    </ul>
    <a href="#contact" class="btn btn-outline-success">PHP35000</a>
  </div>

  <!-- Package 2 -->
  <div class="service-card">
    <img src="../images/catering/10.jpg" alt="Premium Wedding Package" class="mb-3 rounded" style="width:100%;height:180px;object-fit:cover;">
    <h3>FOR 100 PAX</h3>
    <p>A comprehensive wedding package for mid-sized receptions. Features full catering, detailed buffet setup, cake table, and ample serving staff.</p>
    <ul class="list-unstyled mb-3">
      <li><i class="fa fa-check text-success"></i> Beef Menu</li>
      <li><i class="fa fa-check text-success"></i> Pork Menu</li>
      <li><i class="fa fa-check text-success"></i> Chicken Menu</li>
      <li><i class="fa fa-check text-success"></i> Rice</li>
      <li><i class="fa fa-check text-success"></i> Veggies or Pasta or Fish Fillet</li>
      <li><i class="fa fa-check text-success"></i> 100 Cups of Desserts</li>
      <li><i class="fa fa-check text-success"></i> Drinks</li>
      <li><i class="fa fa-check text-success"></i> Backdrop and Platform / Complete Setup</li>
      <li><i class="fa fa-check text-success"></i> Table Buffet w/ Skirting Setup</li>
      <li><i class="fa fa-check text-success"></i> 7 Chaffing Dish w/ Food Heat Lamp</li>
      <li><i class="fa fa-check text-success"></i> Cake and Gift Table w/ Skirting Designs</li>
      <li><i class="fa fa-check text-success"></i> Chairs with cover</li>
      <li><i class="fa fa-check text-success"></i> Tables with cover</li>
      <li><i class="fa fa-check text-success"></i> 100 Pax Silverware, Glassware, and Dinnerware</li>
      <li><i class="fa fa-check text-success"></i> 100pcs Serving Spoons</li>
      <li><i class="fa fa-check text-success"></i> 4 Food Attendants</li>
      <li><i class="fa fa-check text-success"></i> Elegant Table Buffet</li>
    </ul>
    <a href="#contact" class="btn btn-outline-success">PHP 55000</a>
  </div>

  <!-- Package 3 -->
  <div class="service-card">
    <img src="../images/catering/6.jpg" alt="Luxury Wedding Package" class="mb-3 rounded" style="width:100%;height:180px;object-fit:cover;">
    <h3>FOR 150 PAX</h3>
    <p>Ideal for medium to large events. Offers extensive meal options, desserts, drinks, a complete and elegant buffet setup, and more attendants for smooth service.</p>
    <ul class="list-unstyled mb-3">
      <li><i class="fa fa-check text-success"></i> Beef Menu</li>
      <li><i class="fa fa-check text-success"></i> Pork Menu</li>
      <li><i class="fa fa-check text-success"></i> Chicken Menu</li>
      <li><i class="fa fa-check text-success"></i> Rice</li>
      <li><i class="fa fa-check text-success"></i> Veggies or Pasta or Fish Fillet</li>
      <li><i class="fa fa-check text-success"></i> 100 Cups of Desserts</li>
      <li><i class="fa fa-check text-success"></i> Drinks</li>
      <li><i class="fa fa-check text-success"></i> Backdrop and Platform / Complete Setup</li>
      <li><i class="fa fa-check text-success"></i> Table Buffet w/ Skirting Setup</li>
      <li><i class="fa fa-check text-success"></i> 7 Chaffing Dish w/ Food Heat Lamp</li>
      <li><i class="fa fa-check text-success"></i> Cake and Gift Table w/ Skirting Designs</li>
      <li><i class="fa fa-check text-success"></i> Chairs with cover</li>
      <li><i class="fa fa-check text-success"></i> Tables with cover</li>
      <li><i class="fa fa-check text-success"></i> 150 Pax Silverware, Glassware, and Dinnerware</li>
      <li><i class="fa fa-check text-success"></i> 150pcs Serving Spoons</li>
      <li><i class="fa fa-check text-success"></i> 6 Food Attendants</li>
      <li><i class="fa fa-check text-success"></i> Elegant Table Buffet</li>
    </ul>
    <a href="#contact" class="btn btn-outline-success">PHP 78000</a>
  </div>
  <!-- Package 4 -->
  <div class="service-card">
    <img src="../images/catering/4.jpg" alt="Family Feast Package" class="mb-3 rounded" style="width:100%;height:180px;object-fit:cover;">
    <h3>FOR 200 PAX</h3>
    <p>Our largest package for grand events. Provides a full premium catering experience for a substantial guest list, ensuring elegant presentation and efficient service with more staff.</p>
    <ul class="list-unstyled mb-3">
      <li><i class="fa fa-check text-success"></i> Beef Menu</li>
      <li><i class="fa fa-check text-success"></i> Pork Menu</li>
      <li><i class="fa fa-check text-success"></i> Chicken Menu</li>
      <li><i class="fa fa-check text-success"></i> Rice</li>
      <li><i class="fa fa-check text-success"></i> Veggies or Pasta or Fish Fillet</li>
      <li><i class="fa fa-check text-success"></i> 200 Cups of Desserts</li>
      <li><i class="fa fa-check text-success"></i> Drinks</li>
      <li><i class="fa fa-check text-success"></i> Backdrop and Platform / Complete Setup</li>
      <li><i class="fa fa-check text-success"></i> Table Buffet w/ Skirting Setup</li>
      <li><i class="fa fa-check text-success"></i> 7 Chaffing Dish w/ Food Heat Lamp</li>
      <li><i class="fa fa-check text-success"></i> Cake and Gift Table w/ Skirting Designs</li>
      <li><i class="fa fa-check text-success"></i> Chairs with cover</li>
      <li><i class="fa fa-check text-success"></i> Tables with cover</li>
      <li><i class="fa fa-check text-success"></i> 200 Pax Silverware, Glassware, and Dinnerware</li>
      <li><i class="fa fa-check text-success"></i> 200pcs Serving Spoons</li>
      <li><i class="fa fa-check text-success"></i> 8 Food Attendants</li>
      <li><i class="fa fa-check text-success"></i> Elegant Table Buffet</li>
    </ul>
    <a href="#contact" class="btn btn-outline-success">PHP 99000</a>
  </div>
</div>
  </section>
  <section class="gallery">
    <h2>Our Setup Gallery</h2>
    <div class="gallery-grid">
      <img src="../images/catering/1.jpg" alt="Venue 1" />
      <img src="../images/catering/11.jpg" alt="Venue 2" />
      <img src="../images/catering/5.jpg" alt="Venue 3" />
      <img src="../images/catering/12.jpg" alt="Venue 4" />
    </div>
  </section>
  <section class="cta">
    <h2>Ready to Plan Your Event?</h2>
    <a href="#contact">Contact Us Today</a>
  </section>
  <!-- Footer -->
  <footer class="text-center py-4 brand-color">
    <div class="container">
      <p class="mb-1 fw-bold">&copy; 2025 Sandok ni Binggay | Contact: 0919-230-8344 | riatriumfo06@gmail.com</p>
      <p class="mb-0 fw-bold">All Rights Reserved.</p>
    </div>
  </footer>
  <!-- Floating Ad (GRD'S IDEA LOL HAHAHAHA)-->
  <!-- <div class="floating-ad-wrapper" id="floatingAdWrapper">
    <div class="ad-btn-group">
      <button class="ad-btn ad-close" id="closeAdBtn" title="Close">&times;</button>
      <button class="ad-btn ad-max" id="maxAdBtn" title="Maximize/Minimize">
        <i class="fas fa-expand" id="maxAdIcon"></i>
      </button>
    </div>
    <a href="https://www.chowking.ph/" target="_blank" id="adLink">
      <img src="../images/chowking.gif" alt="Ad" class="floating-ad-img" id="floatingAdImg">
    </a>
  </div> -->
  

<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow rounded-4">
      <div class="modal-header brand-color text-white rounded-top-4">
        <h5 class="modal-title" id="orderModalLabel"><i class="fas fa-clipboard-list me-2"></i>Book Catering Package</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="orderForm" method="POST" action="">
        <div class="modal-body bg-light">
          <div class="mb-3">
            <label for="orderName" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="orderName" name="orderName" required>
          </div>
          <div class="mb-3">
            <label for="venueName" class="form-label">Name of Venue</label>
            <input type="text" class="form-control" id="venueName" name="venueName" required>
          </div>
          <div class="mb-3">
            <label for="venueStreet" class="form-label">Street</label>
            <input type="text" class="form-control" id="venueStreet" name="venueStreet" required>
          </div>
          <div class="mb-3">
            <label for="venueCity" class="form-label">City</label>
            <input type="text" class="form-control" id="venueCity" name="venueCity" required>
          </div>
          <div class="mb-3">
            <label for="venueProvince" class="form-label">Province</label>
            <input type="text" class="form-control" id="venueProvince" name="venueProvince" required>
          </div>
          <div class="mb-3">
            <label for="orderPhone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="orderPhone" name="orderPhone" required>
          </div>
          <div class="mb-3">
            <label for="orderDate" class="form-label">Event Date</label>
            <input type="date" class="form-control" id="orderDate" name="orderDate" required>
          </div>
          <div class="mb-3">
            <label for="orderCategory" class="form-label">Package</label>
            <select class="form-select" id="orderCategory" name="orderCategory" required>
              <option value="" disabled selected>Select a package</option>
              <option value="50 PAX">50 PAX - PHP 35,000</option>
              <option value="100 PAX">100 PAX - PHP 55,000</option>
              <option value="150 PAX">150 PAX - PHP 78,000</option>
              <option value="200 PAX">200 PAX - PHP 99,000</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="orderNotes" class="form-label">Note</label>
            <textarea class="form-control" id="orderNotes" name="orderNotes" rows="2"></textarea>
            <div class="alert alert-info mt-2 py-2 px-3 mb-0" style="font-size: 0.97rem;">
              <i class="fas fa-info-circle me-1"></i>
              <strong>Note:</strong> The final price may vary depending on your input note and special requests.
            </div>
          </div>
          <div id="orderPrice" class="alert alert-info py-2 px-3 mb-0" style="display:none;"></div>
        </div>
        <div class="modal-footer justify-content-between px-4 py-3 bg-white border-top-0">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
            <i class="fas fa-arrow-left me-1"></i> Cancel
          </button>
          <button type="submit" class="btn btn-success">
            <i class="fas fa-check-circle me-1"></i> Book Now
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Book Now modal logic
  document.addEventListener('DOMContentLoaded', function() {
    // Slideshow logic
    const slides = document.querySelectorAll('.hero-bg-slideshow img');
    let current = 0;
    if (slides.length > 1) {
      setInterval(() => {
        slides[current].classList.remove('active');
        current = (current + 1) % slides.length;
        slides[current].classList.add('active');
      }, 3500); // Change every 3.5 seconds
    }

    // Modal logic
    document.querySelectorAll('.service-card .btn').forEach(function(btn) {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        const card = btn.closest('.service-card');
        const h3 = card.querySelector('h3');
        const select = document.getElementById('orderCategory');
        if (h3 && select) {
          const text = h3.textContent.trim();
          for (let opt of select.options) {
            if (opt.value && text.includes(opt.value)) {
              select.value = opt.value;
              break;
            }
          }
          showOrderPrice();
        }
        var modal = new bootstrap.Modal(document.getElementById('orderModal'));
        modal.show();
      });
    });

    document.getElementById('orderCategory').addEventListener('change', showOrderPrice);

    function showOrderPrice() {
      const val = document.getElementById('orderCategory').value;
      const priceMap = {
        "50 PAX": "PHP 35,000",
        "100 PAX": "PHP 55,000",
        "150 PAX": "PHP 78,000",
        "200 PAX": "PHP 99,000"
      };
      const priceDiv = document.getElementById('orderPrice');
      if (priceMap[val]) {
        priceDiv.textContent = "Total Price: " + priceMap[val];
        priceDiv.style.display = "block";
      } else {
        priceDiv.style.display = "none";
      }
    }
  });
</script>
<?php echo $sweetAlertConfig; ?>
</body>
</html>