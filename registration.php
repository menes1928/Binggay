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
$sweetAlertConfig = "";

if (isset($_POST['register'])) {

  $firstname   = $_POST['first_name'];
  $lastname    = $_POST['last_name'];
  $sex         = $_POST['sex'];
  $email       = $_POST['email'];
  $phone       = $_POST['phone'];
  $username    = $_POST['username'];
  $password    = password_hash($_POST['password'], PASSWORD_BCRYPT);
  $user_type   = 0; // Regular user

  // Check if email already exists
  if ($con->emailExists($email)) {
    $sweetAlertConfig = "
      <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
      <script>
        Swal.fire({
          icon: 'error',
          title: 'Email Already Registered',
          text: 'Please use a different email address.',
        });
      </script>";
  } else {}

if (isset($_POST['register'])) {

  $firstname   = $_POST['first_name'];
  $lastname    = $_POST['last_name'];
  $sex         = $_POST['sex'];
  $email       = $_POST['email'];
  $phone       = $_POST['phone'];
  $username    = $_POST['username'];
  $password    = password_hash($_POST['password'], PASSWORD_BCRYPT);
  $user_type   = 0; // Regular user

  // Profile photo upload
  $user_photo = null;
  if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $targetDir = "../uploads/profile/";
    if (!is_dir($targetDir)) {
      mkdir($targetDir, 0777, true);
    }

    $fileTmpPath = $_FILES['photo']['tmp_name'];
    $fileName = uniqid() . '_' . basename($_FILES['photo']['name']);
    $targetFilePath = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileType, $allowedTypes)) {
      if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
        $user_photo = $targetFilePath;
      }
    }
  }

  $userID = $con->signupUser(
    $firstname,
    $lastname,
    $sex,
    $email,
    $phone,
    $username,
    $password,
    $user_photo,
    $user_type
  );

  if ($userID) {
    $sweetAlertConfig = "
      <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
      <script>
        Swal.fire({
          icon: 'success',
          title: 'Registration Successful',
          text: 'Your account has been created successfully!',
          confirmButtonText: 'OK'
        }).then(() => {
          window.location.href = 'login.php';
        });
      </script>";
  } else {
    $sweetAlertConfig = "
      <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
      <script>
        Swal.fire({
          icon: 'error',
          title: 'Registration Failed',
          text: 'There was an error during signup.',
        });
      </script>";
  }
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Sandok ni Binggay</title>

  <!-- Bootstrap & Google Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Roboto&display=swap" rel="stylesheet">

  <!-- Custom CSS -->
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="css/registration.css">
</head>
<body>
  <div class="register-card">
    <h2 class="text-center mb-4">Sandok ni Binggay</h2>
    <form action="" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="first_name" class="form-label">First Name</label>
        <input type="text" name="first_name" class="form-control" id="first_name" required>
      </div>
      <div class="mb-3">
        <label for="last_name" class="form-label">Last Name</label>
        <input type="text" name="last_name" class="form-control" id="last_name" required>
      </div>
      <div class="mb-3">
        <label for="sex" class="form-label">Sex</label>
        <select name="sex" id="sex" class="form-control" required>
          <option value="" disabled selected>Select</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" name="email" class="form-control" id="email" required>
      </div>
      <div class="mb-3">
        <label for="phone" class="form-label">Phone Number</label>
        <input type="text" name="phone" class="form-control" id="phone" required>
      </div>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" class="form-control" id="username" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" class="form-control" id="password" required>
      </div>
      <div class="mb-3">
        <label for="photo" class="form-label">Profile Photo</label>
        <input type="file" name="photo" class="form-control" id="photo">
      </div>
      <button type="submit" name="register" class="btn btn-custom w-100">Register</button>
      <div class="mt-3 text-center">
        <span>Already have an account? </span><a href="login.php" class="login-link">Sign in</a>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <?= $sweetAlertConfig ?>

    <!-- AJAX for live checking of existing emails (inside the registration.php) (CODE STARTS HERE) -->
<script>
$(document).ready(function(){
    function toggleNextButton(isEnabled) {
        $('#nextButton').prop('disabled', !isEnabled);
    }

    $('#email').on('input', function(){
        var email = $(this).val();
        if (email.length > 0) {
            $.ajax({
                url: 'AJAX/check_email.php',
                method: 'POST',
                data: { email: email },
                dataType: 'json',
                success: function(response) {
                    if (response.exists) {
                        // Email is already taken
                        $('#email').removeClass('is-valid').addClass('is-invalid');
                        $('#emailFeedback').text('Email is already taken.').show();
                        $('#email')[0].setCustomValidity('Email is already taken.');
                        $('#email').siblings('.invalid-feedback').not('#emailFeedback').hide();
                        toggleNextButton(false); // ❌ Disable next button
                    } else {
                        // Email is valid and available
                        $('#email').removeClass('is-invalid').addClass('is-valid');
                        $('#emailFeedback').text('').hide();
                        $('#email')[0].setCustomValidity('');
                        $('#email').siblings('.valid-feedback').show();
                        toggleNextButton(true); // ✅ Enable next button
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred: ' + error);
                }
            });
        } else {
            // Empty input reset
            $('#email').removeClass('is-valid is-invalid');
            $('#emailFeedback').text('').hide();
            $('#email')[0].setCustomValidity('');
            toggleNextButton(false); // ❌ Disable next button
        }
    });

    $('#email').on('invalid', function() {
        if ($('#email')[0].validity.valueMissing) {
            $('#email')[0].setCustomValidity('Please enter a valid email.');
            $('#emailFeedback').hide();
            toggleNextButton(false); // ❌ Disable next button
        }
    });



    
    $('#username').on('input', function(){
        var username = $(this).val();
        if (username.length > 0) {
            $.ajax({
                url: 'AJAX/check_username.php',
                method: 'POST',
                data: { username: username },
                dataType: 'json',
                success: function(response) {
                    if (response.exists) {
                        // Email is already taken
                        $('#username').removeClass('is-valid').addClass('is-invalid');
                        $('#usernameFeedback').text('Username is already taken.').show();
                        $('#username')[0].setCustomValidity('Username is already taken.');
                        $('#username').siblings('.invalid-feedback').not('#usernameFeedback').hide();
                        toggleNextButton(false); // ❌ Disable next button
                    } else {
                        // Email is valid and available
                        $('#username').removeClass('is-invalid').addClass('is-valid');
                        $('#usernameFeedback').text('').hide();
                        $('#username')[0].setCustomValidity('');
                        $('#username').siblings('.valid-feedback').show();
                        toggleNextButton(true); // ✅ Enable next button
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred: ' + error);
                }
            });
        } else {
            // Empty input reset
            $('#username').removeClass('is-valid is-invalid');
            $('#usernameFeedback').text('').hide();
            $('#username')[0].setCustomValidity('');
            toggleNextButton(false); // ❌ Disable next button
        }
    });

    $('#username').on('invalid', function() {
        if ($('#username')[0].validity.valueMissing) {
            $('#username')[0].setCustomValidity('Please enter a valid username.');
            $('#usernameFeedback').hide();
            toggleNextButton(false); // ❌ Disable next button
        }
    });

    
});

</script>

</body>
</html>

