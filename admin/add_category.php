<?php
require_once('../classes/database.php');
$con = new database();

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['category_name'])) {
    $category_name = trim($_POST['category_name']);
    if ($con->addCategory($category_name)) {
        $message = "<div class='alert alert-success'>Category added successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Failed to add category.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add Category</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>Add New Category</h2>
  <?= $message ?>
  <form method="POST">
    <div class="mb-3">
      <label for="category_name" class="form-label">Category Name</label>
      <input type="text" class="form-control" id="category_name" name="category_name" required>
    </div>
    <button type="submit" class="btn btn-success">Add Category</button>
    <a href="admin_homepage.php" class="btn btn-secondary">Back to Dashboard</a>
  </form>
</div>
</body>
</html>