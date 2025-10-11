<?php
require_once('../classes/database.php');
$con = new database();
$conn = $con->opencon();
$sweetAlertConfig = "";

// Use $_GET for initial load, $_POST for update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $data = $con->viewMenuID($id);
} elseif (isset($_GET['id'])) {
    $id = $_GET['id'];
    $data = $con->viewMenuID($id);
} else {
    header('location:admin_homepage.php');
    exit;
}

if (isset($_POST['updateFood'])) {
    $name = $_POST['foodName'];
    $desc = $_POST['foodDescription'];
    $pax = $_POST['foodPax'];
    $price = $_POST['foodPrice'];
    $avail = ($_POST['foodAvailability'] === 'available') ? 1 : 0;

    // Handle image upload
    $menu_pic = $data['menu_pic']; // Default to existing
    if (isset($_FILES['foodPic']) && $_FILES['foodPic']['error'] == 0) {
        $targetDir = "../images/menu/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = uniqid() . "_" . basename($_FILES["foodPic"]["name"]);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES["foodPic"]["tmp_name"], $targetFile)) {
            $menu_pic = $fileName;
        }
    }

    $success = $con->updateMenu($id, $name, $desc, $pax, $price, $avail, $menu_pic);

    if ($success) {
        $sweetAlertConfig = "
        <script>
            Swal.fire({
                title: 'Updated!',
                text: 'Menu item updated successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'admin_homepage.php';
            });
        </script>";
    } else {
        $sweetAlertConfig = "
        <script>
            Swal.fire({
                title: 'Error!',
                text: 'Failed to update the menu item.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Food Item</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="../package/dist/sweetalert2.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light py-5">

<div class="container bg-white p-5 rounded shadow-sm">
  <h2 class="text-center mb-4">Edit Food Item</h2>

  <form method="POST" enctype="multipart/form-data">
   <div class="row mb-3">
      <div class="col-md-6">
        <label for="foodName" class="form-label">Food Name</label>
        <input type="text" class="form-control" id="foodName" name="foodName" value="<?php echo htmlspecialchars($data['menu_name']); ?>" required>
      </div>
      <div class="col-md-6">
        <label for="foodPrice" class="form-label">Price (₱)</label>
        <input type="number" class="form-control" id="foodPrice" name="foodPrice" step="0.01" value="<?php echo htmlspecialchars($data['menu_price']); ?>" required>
      </div>
    </div>

    <div class="mb-3">
      <label for="foodDescription" class="form-label">Description</label>
      <textarea class="form-control" id="foodDescription" name="foodDescription" rows="3" required><?php echo htmlspecialchars($data['menu_desc']); ?></textarea>
    </div>

    <div class="mb-3">
      <label for="foodPax" class="form-label">Pax</label>
      <select class="form-select" id="foodPax" name="foodPax" required>
        <option value="pax6-8" <?php echo ($data['menu_pax'] == 'pax6-8') ? 'selected' : ''; ?>>6-8 PAX</option>
        <option value="pax10-15" <?php echo ($data['menu_pax'] == 'pax10-15') ? 'selected' : ''; ?>>10-15 PAX</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="foodPic" class="form-label">Food Picture</label>
      <input type="file" class="form-control" id="foodPic" name="foodPic" accept="image/*">
      <?php if (!empty($data['menu_pic'])): ?>
        <small>Current: <?php echo htmlspecialchars($data['menu_pic']); ?></small>
      <?php endif; ?>
    </div>

    <div class="mb-3">
      <label for="foodAvailability" class="form-label">Availability</label>
      <select class="form-select" id="foodAvailability" name="foodAvailability" required>
        <option value="available" <?php echo ($data['menu_avail']) ? 'selected' : ''; ?>>Available</option>
        <option value="unavailable" <?php echo (!$data['menu_avail']) ? 'selected' : ''; ?>>Unavailable</option>
      </select>
    </div>

    <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['menu_id']); ?>" >
    <div class="d-flex justify-content-end">
      <a href="admin_homepage.php" class="btn btn-secondary me-2">Cancel</a>
      <button type="submit" name="updateFood" class="btn btn-primary">Update</button>
    </div>
    
  </form>
</div>
<script src="../package/dist/sweetalert2.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $sweetAlertConfig; ?>
</body>
</html>