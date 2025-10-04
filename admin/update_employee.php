<?php
require_once('../classes/database.php');
$con = new database();

$success = null;
$error = null;

// Fetch employee data if id is set (GET or POST)
$id = $_POST['id'] ?? $_GET['id'] ?? null;
$employee = null;
if ($id) {
    $employee = $con->viewEmployeeID($id);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $fn = $_POST['emp_fn'];
    $ln = $_POST['emp_ln'];
    $sex = $_POST['emp_sex'];
    $email = $_POST['emp_email'];
    $phone = $_POST['emp_phone'];
    $role = $_POST['emp_role'];

    if ($con->updateEmployee($id, $fn, $ln, $sex, $email, $phone, $role)) {
        $success = "Employee updated successfully!";
        // Refresh employee data
        $employee = $con->viewEmployeeID($id);
    } else {
        $error = "Failed to update employee.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Employee</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Update Employee</h2>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if ($employee): ?>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($employee['emp_id']); ?>">
        <div class="mb-3">
            <label for="emp_fn" class="form-label">First Name</label>
            <input type="text" class="form-control" id="emp_fn" name="emp_fn" value="<?php echo htmlspecialchars($employee['emp_fn']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="emp_ln" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="emp_ln" name="emp_ln" value="<?php echo htmlspecialchars($employee['emp_ln']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="emp_sex" class="form-label">Sex</label>
            <select class="form-select" id="emp_sex" name="emp_sex" required>
                <option value="Male" <?php if ($employee['emp_sex'] == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($employee['emp_sex'] == 'Female') echo 'selected'; ?>>Female</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="emp_email" class="form-label">Email</label>
            <input type="email" class="form-control" id="emp_email" name="emp_email" value="<?php echo htmlspecialchars($employee['emp_email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="emp_phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="emp_phone" name="emp_phone" value="<?php echo htmlspecialchars($employee['emp_phone']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="emp_role" class="form-label">Role</label>
            <input type="text" class="form-control" id="emp_role" name="emp_role" value="<?php echo htmlspecialchars($employee['emp_role']); ?>" required>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update Employee</button>
        <a href="admin_homepage.php" class="btn btn-secondary">Back</a>
    </form>
    <?php else: ?>
        <div class="alert alert-warning">Employee not found.</div>
    <?php endif; ?>
</div>
</body>
</html>
