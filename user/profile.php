<?php
session_start();
require_once('../classes/database.php');
$con = new database();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user = $con->getUserById($user_id);
$username = $user['user_username'] ?? 'User';
$user_photo = $user['user_photo'] ?? 'default.png';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    $file = $_FILES['profile_pic'];
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($file['error'] === 0 && in_array($ext, $allowed)) {
        $newName = 'user_' . $user_id . '_' . time() . '.' . $ext;
        $target = '../profiles/' . $newName;
        if (move_uploaded_file($file['tmp_name'], $target)) {
            // Update in DB
            $con->updateUserPhoto($user_id, $newName);
            // Optionally delete old photo if not default
            if ($user_photo && $user_photo !== 'default.png' && file_exists('../profiles/' . $user_photo)) {
                unlink('../profiles/' . $user_photo);
            }
            $message = "Profile picture updated!";
            // Refresh user photo
            $user_photo = $newName;
        } else {
            $message = "Failed to upload file.";
        }
    } else {
        $message = "Invalid file type or upload error.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Change Profile Picture</title>
    <link rel="stylesheet" href="../css/homepage.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container py-5">
    <h2>Change Profile Picture</h2>
    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <div class="mb-3">
        <img src="../profiles/<?= htmlspecialchars($user_photo) ?>" alt="Profile" class="profile-img-navbar mb-2">
    </div>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <input type="file" name="profile_pic" accept="image/*" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Upload</button>
        <a href="homepage.php" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>