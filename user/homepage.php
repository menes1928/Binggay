<?php
session_start();

// Redirect admin users to admin dashboard
if (isset($_SESSION['user_id']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] == 1) {
    header("Location: ../admin/admin_homepage.php");
    exit();
}


require_once('../classes/database.php');

// Remove redirect/session check so homepage is public

// Fetch username and photo if logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $con = new database();
    $user = $con->getUserById($user_id);
    $username = $user['user_username'] ?? 'User';
    $user_photo = $user['user_photo'] ?? 'default.png';
    $is_logged_in = true;
} else {
    $username = 'User';
    $user_photo = 'default.png';
    $is_logged_in = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sandok ni Binggay</title>
  <link rel="stylesheet" href="../css/homepage.css" />
  <!-- Bootstrap, Font Awesome, Animate.css -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark brand-color sticky-top shadow-sm py-3 animate__animated animate__fadeInDown">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2 fs-4 fw-bold rounded-pill px-3 py-1 bg-white text-success shadow-sm"
        href="#">
        <i class="fas fa-utensils"></i> Sandok ni Binggay
      </a>
      <!-- Mobile: profile or login/register beside toggle, Desktop: profile or login/register at far right -->
      <div class="d-flex d-lg-none align-items-center ms-auto" style="gap: 0.75rem;">
        <?php if ($is_logged_in): ?>
        <div class="profile-dropdown d-flex align-items-center position-relative">
          <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none" id="profileDropdownMobile"
              data-bs-toggle="dropdown" aria-expanded="false">
              <img src="../profiles/<?= htmlspecialchars($user_photo) ?>" alt="Profile" class="profile-img-navbar">
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdownMobile">
              <li class="px-3 py-2 text-center">
                <img src="../profiles/<?= htmlspecialchars($user_photo) ?>" alt="Profile" class="profile-img-navbar mb-2">
                <div class="fw-bold"><?= htmlspecialchars($username) ?></div>
              </li>
              <li>
                <a class="dropdown-item" href="profile.php">
                  <i class="fas fa-user-edit me-2"></i>Change Profile Picture
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item text-danger fw-bold" href="../logout.php">
                  <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
              </li>
            </ul>
          </div>
        </div>
        <?php else: ?>
        <a href="../login.php" class="btn btn-outline-light me-2">Login</a>
        <a href="../registration.php" class="btn btn-success">Register</a>
        <?php endif; ?>
        <button class="navbar-toggler border-0 ms-1" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
      <div class="collapse navbar-collapse" id="navMenu">
        <ul class="navbar-nav text-center gap-2 me-auto">
          <li class="nav-item">
            <a class="nav-link text-white position-relative" href="#about">
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
        <!-- Desktop: profile or login/register at far right -->
        <div class="profile-dropdown d-none d-lg-flex align-items-center position-relative ms-auto">
          <?php if ($is_logged_in): ?>
          <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none" id="profileDropdownDesktop"
              data-bs-toggle="dropdown" aria-expanded="false">
              <img src="../profiles/<?= htmlspecialchars($user_photo) ?>" alt="Profile" class="profile-img-navbar">
              <span class="text-white fw-bold ms-2">Welcome, <?= htmlspecialchars($username) ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdownDesktop">
              <li class="px-3 py-2 text-center">
                <img src="../profiles/<?= htmlspecialchars($user_photo) ?>" alt="Profile" class="profile-img-navbar mb-2">
                <div class="fw-bold"><?= htmlspecialchars($username) ?></div>
              </li>
              <li>
                <a class="dropdown-item" href="profile.php">
                  <i class="fas fa-user-edit me-2"></i>Change Profile Picture
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item text-danger fw-bold" href="../logout.php">
                  <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
              </li>
            </ul>
          </div>
          <?php else: ?>
          <a href="../login.php" class="btn btn-outline-light me-2">Login</a>
          <a href="../registration.php" class="btn btn-success">Register</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->

  <header class="hero-section d-flex align-items-center justify-items-start position-relative">
    <div class="container text-center z-2">
      <h1 class="fw-bold animate__animated animate__fadeInLeft" id="h1">Welcome to Sandok ni Binggay! 🍽️</h1>
      <p class="lead mt-4 fw-bold animate__animated animate__fadeInLeft">Home of hearty Filipino dishes and catering
        services with a smile 😊</p>
      <lottie-player src="https://assets4.lottiefiles.com/packages/lf20_hnsz0qkx.json" background="transparent"
        speed="1" style="width: 200px; height: 200px;" loop autoplay></lottie-player>
    </div>
    <div class="hero-overlay"></div>
  </header>

  <!-- About -->
  <section id="about" class="about-section">
    <div class="container">
      <h2 class="about-title">About Us</h2>
      <img src="../images/divider.png" alt="Divider" class="about-divider">
      <p class="about-intro">
        Welcome to <strong>Sandok ni Bingay</strong> — where every dish is made with heart and soul.
      </p>

      <div class="about-columns">
        <div class="about-col card green">
          <div class="face face1">
            <h3>Founded With Heart</h3>
          </div>
          <div class="face face2">
            <p>
              Founded by <strong>Ria Vida V. Triumfo</strong>, Sandok ni Bingay is a proud family-run food and catering
              business dedicated to bringing flavorful, homemade and homecooked meals to your table.
            </p>
          </div>
        </div>

        <div class="about-col card red">
          <div class="face face1">
            <h3>Serving Every Need</h3>
          </div>
          <div class="face face2">
            <p>
              From generous party trays to packed meals for offices and schools, we offer flexible options for every
              event or craving, big or small.
            </p>
          </div>
        </div>

        <div class="about-col card yellow">
          <div class="face face1">
            <h3>Made With Love</h3>
          </div>
          <div class="face face2">
            <p>
              Each dish is crafted using fresh ingredients and timeless recipes inspired by tradition and family —
              ensuring every meal is as heartwarming as it is delicious.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>


  <section id="services" class="services-section py-5">
    <div class="container">
      <h2 class="section-heading text-center mb-4" id="serviceH2">Our Services</h2>
      <div class="text-center">
        <img src="../images/divider.png" alt="Divider" class="about-divider">
      </div>
      <div class="row g-4 justify-content-center">

        <!-- Social Gathering Card -->
        <div class="col-md-4">
          <a href="booking.php" class="service-card text-decoration-none position-relative overflow-hidden rounded shadow">
            <img src="../images/social.jpg" alt="Social Gathering" class="w-100 h-100 object-fit-cover">
            <div class="overlay d-flex">
              <h4 class="text-white fw-bold">Social Gathering</h4>
            </div>
          </a>
        </div>

        <!-- Birthday Card -->
        <div class="col-md-4">
          <a href="booking.php" class="service-card text-decoration-none position-relative overflow-hidden rounded shadow">
            <img src="../images/debut.jpg" alt="Debut" class="w-100 h-100 object-fit-cover">
            <div class="overlay d-flex">
              <h4 class="text-white fw-bold">Birthday Party</h4>
            </div>
          </a>
        </div>

        <!-- Wedding Card -->
        <div class="col-md-4">
          <a href="booking.php" class="service-card text-decoration-none position-relative overflow-hidden rounded shadow">
            <img src="../images/wedding.jpg" alt="Wedding" class="w-100 h-100 object-fit-cover">
            <div class="overlay d-flex">
              <h4 class="text-white fw-bold">Wedding Event</h4>
            </div>
          </a>
        </div>

        <div class="text-center mb-4">
          <img src="../images/divider.png" alt="Divider" class="about-divider">
        </div>
      </div>
    </div>
  </section>

  <section id="collection" class="py-5 bg-light">
    <div class="container">
      <h2 class="section-heading text-center mb-3" id="coll-title">Collections</h2>
      <div class="text-center mb-4">
        <img src="../images/divider.png" alt="Divider" class="about-divider">
      </div>
      <div class="slider" style="--width: 250px; --height: 400px; --quantity: 21;">
        <div class="list">
          <div class="item" style="--position: 1;"><img src="../images/1.jpg" alt=""></div>
          <div class="item" style="--position: 2;"><img src="../images/2.jpg" alt=""></div>
          <div class="item" style="--position: 3;"><img src="../images/3.jpg" alt=""></div>
          <div class="item" style="--position: 4;"><img src="../images/4.jpg" alt=""></div>
          <div class="item" style="--position: 5;"><img src="../images/5.jpg" alt=""></div>
          <div class="item" style="--position: 6;"><img src="../images/6.jpg" alt=""></div>
          <div class="item" style="--position: 7;"><img src="../images/7.jpg" alt=""></div>
          <div class="item" style="--position: 8;"><img src="../images/8.jpg" alt=""></div>
          <div class="item" style="--position: 9;"><img src="../images/9.jpg" alt=""></div>
          <div class="item" style="--position: 10;"><img src="../images/10.jpg" alt=""></div>
          <div class="item" style="--position: 11;"><img src="../images/11.jpg" alt=""></div>
          <div class="item" style="--position: 12;"><img src="../images/12.jpg" alt=""></div>
          <div class="item" style="--position: 13;"><img src="../images/13.jpg" alt=""></div>
          <div class="item" style="--position: 14;"><img src="../images/14.jpg" alt=""></div>
          <div class="item" style="--position: 15;"><img src="../images/15.jpg" alt=""></div>
          <div class="item" style="--position: 16;"><img src="../images/16.jpg" alt=""></div>
          <div class="item" style="--position: 17;"><img src="../images/17.jpg" alt=""></div>
          <div class="item" style="--position: 18;"><img src="../images/18.jpg" alt=""></div>
          <div class="item" style="--position: 19;"><img src="../images/19.jpg" alt=""></div>
          <div class="item" style="--position: 20;"><img src="../images/20.jpg" alt=""></div>
          <div class="item" style="--position: 21;"><img src="../images/21.jpg" alt=""></div>
        </div>
      </div>
    </div>
  </section>

  <section class="custom-footer">
    <div class="footer-overlay">
      <div class="container text-white py-5">
        <div class="row g-4 justify-content-center">
          <!-- Brand Info -->
          <div class="col-12 col-md-4 mb-4 text-center text-md-start">
            <h2 class="footer-title">Sandok ni Binggay</h2>
            <p class="footer-desc d-none d-md-block">Serving tradition with every spoonful 🍽️</p>
          </div>
          <!-- Quick Links (hide on mobile) -->
          <div class="col-12 col-md-3 mb-4 text-center text-md-start d-none d-md-block">
            <h5 class="footer-heading">Quick Links</h5>
            <ul class="list-unstyled">
              <li><a href="#about" class="footer-link">About</a></li>
              <li><a href="menu.php" class="footer-link">Menu</a></li>
              <li><a href="booking.php" class="footer-link">Booking</a></li>
            </ul>
          </div>
          <!-- Follow Us (show only Facebook on mobile) -->
          <div class="col-12 col-md-3 mb-4 text-center text-md-start">
            <h5 class="footer-heading d-none d-md-block">Connect With Us</h5>
            <ul class="list-unstyled mb-0">
              <li>
                <a href="https://www.facebook.com/profile.php?id=100064113068426" target="_blank" class="footer-link">
                  <i class="fab fa-facebook me-2"></i>Sandok ni Binggay
                </a>
              </li>
              <li class="d-none d-md-block">
                <p class="footer-link mb-0"><i class="fas fa-envelope me-2"></i>riatriumfo06@gmail.com</p>
              </li>
              <li class="d-none d-md-block">
                <p class="footer-link mb-0"><i class="fas fa-phone me-2"></i>Call: 0919-230-8344</p>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="text-center py-4 brand-color">
    <div class="container">
      <p class="mb-1 fw-bold">&copy; 2025 Sandok ni Binggay | Contact: 0919-230-8344 | riatriumfo06@gmail.com
      </p>
      <p class="mb-0 fw-bold">All Rights Reserved.</p>
    </div>
  </footer>

  <!-- Back to Top -->
  <button id="backToTop" onclick="scrollToTop()" title="Back to Top"><i class="fas fa-chevron-up"></i></button>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Show/hide back to top
    window.onscroll = function () {
      document.getElementById("backToTop").style.display = (document.documentElement.scrollTop > 200) ? "block" : "none";
    }

    function scrollToTop() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  </script>

</body>

</html>
