<?php
require_once('../classes/database.php');
$con = new database();
$conn = $con->opencon();
$sweetAlertConfig = "";


if (isset($_POST['addFood'])) {
    $name = $_POST['foodName'];
    $desc = $_POST['foodDescription'];
    $pax = $_POST['foodPax'];
    $price = $_POST['foodPrice'];
    $avail = isset($_POST['foodAvailability']) ? 1 : 0;

    $success = $con->addMenu($name, $desc, $pax, $price, $avail);

    if ($success) {
        $sweetAlertConfig = "
        <script>
          Swal.fire({
            icon: 'success',
            title: 'Food added successfully',
            text: 'A new food has been added to the database.',
            confirmButtonText: 'Continue'
          }).then(() => {
            window.location.href = 'admin_homepage.php';
          });
        </script>";
    } else {
        $sweetAlertConfig = "
        <script>
          Swal.fire({
            icon: 'error',
            title: 'Something went wrong',
            text: 'Please try again.',
          });
        </script>";
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add New Food</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light py-5">

<div class="container bg-white p-5 rounded shadow-sm">
  <h2 class="text-center mb-4">Add New Food Item</h2>

  <form method="POST" action="add_menu.php">
    <div class="row mb-3">
      <div class="col-md-6">
        <label for="foodName" class="form-label">Food Name</label>
        <input type="text" class="form-control" id="foodName" name="foodName" required>
      </div>
      <div class="col-md-6">
        <label for="foodPrice" class="form-label">Price (₱)</label>
        <input type="number" class="form-control" id="foodPrice" name="foodPrice" step="0.01" required>
      </div>
    </div>

    <div class="mb-3">
      <label for="foodDescription" class="form-label">Description</label>
      <textarea class="form-control" id="foodDescription" name="foodDescription" rows="3" required></textarea>
    </div>

    <div class="mb-3">
      <label for="foodPax" class="form-label">Description</label>
      <select class="form-select" id="foodPax" name="foodPax" required>
        <option value="pax6-8" selected>6-8 PAX</option>
        <option value="pax10-15">10-15 PAX</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="foodAvailability" class="form-label">Availability</label>
      <select class="form-select" id="foodAvailability" name="foodAvailability" required>
        <option value="available" selected>Available</option>
        <option value="unavailable">Unavailable</option>
      </select>
    </div>

    <div class="d-flex justify-content-end">
      <a href="admin_homepage.php" class="btn btn-secondary me-2">Cancel</a>
      <button type="submit" name="addFood" class="btn btn-success">Save</button>
    </div>
    
      <script src="./package/dist/sweetalert2.js"></script>
<script src="./bootstrap-5.3.3-dist/js/bootstrap.js"></script>
<?php echo $sweetAlertConfig; ?>
    
  </form>
</div>

</body>
</html>