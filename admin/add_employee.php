<?php
session_start();
require_once('../classes/database.php');

$con = new database();
$sweetAlertConfig = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emp_fn = $_POST['emp_fn'] ?? '';
    $emp_ln = $_POST['emp_ln'] ?? '';
    $emp_sex = $_POST['emp_sex'] ?? '';
    $emp_email = $_POST['emp_email'] ?? '';
    $emp_phone = $_POST['emp_phone'] ?? '';
    $emp_role = $_POST['emp_role'] ?? '';

    $result = $con->addEmployee($emp_fn, $emp_ln, $emp_sex, $emp_email, $emp_phone, $emp_role);

    if ($result) {
        $sweetAlertConfig = "
        <script>
          Swal.fire({
            icon: 'success',
            title: 'Employee added successfully',
            text: 'A new employee has been added to the database.',
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add New Employee</title>
  <link rel="stylesheet" href="../css/admin_homepage.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container bg-white p-5 rounded shadow-sm">
    <h2 class="mb-4 text-center">Add New Employee</h2>
    <form action="add_employee.php" method="POST">
      <div class="mb-3">
        <label for="emp_fn" class="form-label">First Name</label>
        <input type="text" class="form-control" id="emp_fn" name="emp_fn" required>
      </div>
      <div class="mb-3">
        <label for="emp_ln" class="form-label">Last Name</label>
        <input type="text" class="form-control" id="emp_ln" name="emp_ln" required>
      </div>
      <div class="mb-3">
        <label for="emp_sex" class="form-label">Sex</label>
        <select class="form-select" id="emp_sex" name="emp_sex" required>
          <option value="" selected disabled>Select Sex</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
          <option value="Other">Other</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="emp_email" class="form-label">Email</label>
        <input type="email" class="form-control" id="emp_email" name="emp_email" required>
      </div>
      <div class="mb-3">
        <label for="emp_phone" class="form-label">Phone</label>
        <input type="text" class="form-control" id="emp_phone" name="emp_phone" required>
      </div>
      <div class="mb-3">
      <label for="emp_role" class="form-label">Role</label>
      <select class="form-select" id="emp_role" name="emp_role" required>
        <option value="Waiter" selected>Waiter</option>
        <option value="Dishwasher">Dishwasher</option>
        <option value="FoodAttendant">Food Attendant</option>
      </select>
    </div>
      <div class="text-end">
        <a href="admin_homepage.php" class="btn btn-secondary">Cancel</a>
        <button type="submit" class="btn btn-success">Add Employee</button>
      </div>
    </form>
  </div>
</body>
</html>
