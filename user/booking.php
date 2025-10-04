<?php
session_start();
require_once('../classes/database.php');
$con = new database();

$sweetAlertConfig = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eb_name = $_POST['eb_name'];
    $eb_contact = $_POST['eb_contact'];
    $eb_type = $_POST['eb_type'];
    $eb_date = $_POST['eb_date'];
    $eb_guest = $_POST['eb_guest'];
    $eb_order = $_POST['eb_order'];
    $user_id = $_SESSION['user_id'] ?? null;

    // Combine venue fields
    $venue_name = $_POST['venue_name'];
    $venue_street = $_POST['venue_street'];
    $venue_city = $_POST['venue_city'];
    $venue_province = $_POST['venue_province'];
    $eb_place = "$venue_name, $venue_street, $venue_city, $venue_province";

    // Check if the date is already booked
$existing = $con->isDateBooked($eb_date);

if ($existing) {
    $sweetAlertConfig = "
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Date Unavailable',
        text: 'Sorry, this date is already booked. Please choose another date.',
        confirmButtonText: 'OK'
    });
    </script>";
} else {

    $result = $con->addEventBooking($user_id, $eb_name, $eb_contact, $eb_type, $eb_place, $eb_date, $eb_guest, $eb_order);

    if ($result) {
        $sweetAlertConfig = "
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Booking Successful!',
            text: 'Your event booking has been submitted. We will contact you soon.',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'booking.php';
        });
        </script>";
    } else {
        $sweetAlertConfig = "
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Something went wrong',
            text: 'Please try again.',
            confirmButtonText: 'OK'
        });
        </script>";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Booking - Sandok ni Binggay</title>

  <!-- Bootstrap CSS FIRST -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Animate.css, Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

  <!-- YOUR CUSTOM CSS LAST -->
  <link rel="stylesheet" href="../css/booking.css"/>
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
          <a class="nav-link text-white position-relative" href="cateringpackages.php">
            Catering
            <span class="nav-underline"></span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white position-relative" href="#top">
            Booking
            <span class="nav-underline"></span>
          </a>
        </li>
      </ul>
      <a href="../logout.php" class="btn btn-outline-light ms-lg-3 mt-3 mt-lg-0">Logout</a>
    </div>
  </div>
</nav>

  <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
  <div class="booking-card position-relative w-100" style="max-width: 600px;">
    <div class="booking-icon shadow"><i class="fas fa-calendar-check"></i></div>
    <h2 class="mb-4">Book an Event</h2>
    <form  method="POST">
      <input type="hidden" name="access_key" value="69c91db5-68b5-4ba5-9e05-3f7b019ffc8f">
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" class="form-control" name="eb_name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Contact No.</label>
            <input type="text" class="form-control" name="eb_contact" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Type</label>
            <select class="form-select" name="eb_type" required>
                <option value="" disabled selected>Select type</option>
                <option value="social">Social</option>
                <option value="marriage">Marriage</option>
                <option value="birthday">Birthday</option>
                <option value="baptism">Baptism</option>
            </select>
        </div>
        <!-- Venue Details -->
        <div class="mb-3">
            <label class="form-label">Venue Name</label>
            <input type="text" class="form-control" name="venue_name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Street</label>
            <input type="text" class="form-control" name="venue_street" required>
        </div>
        <div class="mb-3">
            <label class="form-label">City</label>
            <input type="text" class="form-control" name="venue_city" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Province</label>
            <input type="text" class="form-control" name="venue_province" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date" class="form-control" name="eb_date" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Guests</label>
            <input type="number" class="form-control" name="eb_guest" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Order</label>
            <select class="form-select" name="eb_order" required>
                <option value="" disabled selected>Select order type</option>
                <option value="party trays">Party Trays</option>
                <option value="customize">Customize</option>
            </select>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-book">Book Event</button>
        </div>
    </form>
  </div>
</div>

<section class="custom-footer">
  <div class="footer-overlay">
    <div class="container text-white py-5">
      <div class="row justify-content-center text-center text-md-start">
        <!-- Brand Info -->
        <div class="col-md-4 mb-4">
          <h2 class="footer-title">Sandok ni Binggay</h2>
          <p class="footer-desc">Serving tradition with every spoonful 🍽️</p>
        </div>

        <!-- Quick Links -->
        <div class="col-md-3 mb-4">
          <h5 class="footer-heading">Quick Links</h5>
          <ul class="list-unstyled">
            <li><a href="homepage.php" class="footer-link">About</a></li>
            <li><a href="menu.php" class="footer-link">Menu</a></li>
            <li><a href="#top" class="footer-link">Booking</a></li>
          </ul>
        </div>

        <!-- Follow Us -->
        <div class="col-md-3 mb-4">
          <h5 class="footer-heading">Connect With Us</h5>
          <ul class="list-unstyled">
            <li><a href="https://www.facebook.com/profile.php?id=100064113068426" target="_blank" class="footer-link"><i class="fab fa-facebook me-2"></i>Sandok ni Binggay</a></li>
            <li><p class="footer-link"><i class="fas fa-envelope me-2"></i>riatriumfo06@gmail.com</p></li>
            <li><p class="footer-link"><i class="fas fa-phone me-2"></i>Call: 0919-230-8344</p></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

  <!-- Footer -->
  <footer class="text-center py-4 brand-color">
    <div class="container">
      <p class="mb-0">&copy; 2025 Sandok ni Binggay | Contact: 0919-230-8344 | riatriumfo06@gmail.com</p>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<script src="./package/dist/sweetalert2.js"></script>
<script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
<?= $sweetAlertConfig ?>

</body>

</html>
