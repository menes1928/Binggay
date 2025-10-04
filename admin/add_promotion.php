<?php
session_start();
require_once('../classes/database.php');

$con = new database();
$sweetAlertConfig = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $promotion_name = $_POST['promotion_name'] ?? '';
    $promotion_desc = $_POST['promotion_desc'] ?? '';
    $promotion_disc = $_POST['promotion_disc'] ?? '';
    $promotion_start = $_POST['promotion_start'] ?? null;
    $promotion_end = $_POST['promotion_end'] ?? null;

    // You need to implement addPromotion in your database class
    $result = $con->addPromotion($promotion_name, $promotion_desc, $promotion_disc, $promotion_start, $promotion_end);

    if ($result) {
        $sweetAlertConfig = "
        <script>
          Swal.fire({
            icon: 'success',
            title: 'Promotion added successfully',
            text: 'The promotion has been saved to the database.',
            confirmButtonText: 'OK'
          }).then(() => {
            window.location.href = 'admin_homepage.php';
          });
        </script>";
    } else {
        $sweetAlertConfig = "
        <script>
          Swal.fire({
            icon: 'error',
            title: 'Failed to add promotion',
            text: 'Something went wrong. Please try again.',
          });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Promotion</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
  <?= $sweetAlertConfig ?>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm rounded">
          <div class="card-body">
            <h2 class="text-center mb-4">Add Promotion Deal</h2>
            <form action="add_promotion.php" method="POST">
              <div class="mb-3">
                <label for="promotion_name" class="form-label">Promotion Name</label>
                <input type="text" class="form-control" id="promotion_name" name="promotion_name" required>
              </div>
              <div class="mb-3">
                <label for="promotion_desc" class="form-label">Description</label>
                <textarea class="form-control" id="promotion_desc" name="promotion_desc" rows="3"></textarea>
              </div>
              <div class="mb-3">
                <label for="promotion_disc" class="form-label">Discount (%)</label>
                <input type="number" class="form-control" id="promotion_disc" name="promotion_disc" step="0.01" required>
              </div>
              <div class="row mb-3">
                <div class="col">
                  <label for="promotion_start" class="form-label">Start Date</label>
                  <input type="date" class="form-control" id="promotion_start" name="promotion_start">
                </div>
                <div class="col">
                  <label for="promotion_end" class="form-label">End Date</label>
                  <input type="date" class="form-control" id="promotion_end" name="promotion_end" required>
                </div>
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-primary rounded-pill">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
