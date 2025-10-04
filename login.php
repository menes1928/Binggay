<?php
session_start();

if (isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] == 1) {
        header('Location: admin/admin_homepage.php');
    } else {
        header('Location: user/homepage.php');
    }
    exit;
}

require_once('classes/database.php');
$con = new database();
// Initialize SweetAlert configuration variable
$sweetAlertConfig = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Assuming your database class has a method to get user by username
    $user = $con->getUserByUsername($username);

    // Check if user exists and password is correct
    if ($user && password_verify($password, $user['user_password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['user_username'];
        $_SESSION['user_type'] = $user['user_type'];

        $redirectUrl = ($user['user_type'] == 1) ? 'admin/admin_homepage.php' : 'user/homepage.php';

        $sweetAlertConfig = "
          <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            Swal.fire({
              icon: 'success',
              title: 'Login Successful',
              text: 'Redirecting...',
              timer: 1500,
              showConfirmButton: false
            }).then(() => {
              window.location.href = '$redirectUrl';
            });
          </script>";
    } else {
        $sweetAlertConfig = "
          <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            Swal.fire({
              icon: 'error',
              title: 'Login Failed',
              text: 'Invalid username or password.'
            });
          </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Sandok ni Binggay</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap & Google Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto&display=swap" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="css/login.css">
</head>
<body>

  <div class="login-card">
    <h2 class="text-center mb-4">Sandok ni Binggay</h2>
    <form method="POST" action="">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" class="form-control" id="username" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" id="password" required>
      </div>
      <button type="submit" name="login" class="btn btn-custom w-100">Login</button>
      <div class="mt-3 text-center">
        <span>Don't have an account? </span><a href="registration.php" class="register-link">Register</a>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <?= $sweetAlertConfig ?>
</body>
</html>