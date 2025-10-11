<?php
session_start();
require_once('../classes/database.php');
$con = new database();

$sweetAlertConfig = "";

if (!isset($_GET['eb_id'])) {
    header("Location: admin_homepage.php");
    exit;
}

$eb_id = $_GET['eb_id'];
$booking = $con->getEventBookingById($eb_id);

if (!$booking) {
    $sweetAlertConfig = "
    <script>
        Swal.fire({
            title: 'Error!',
            text: 'Booking not found.',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'admin_homepage.php';
        });
    </script>";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eb_status'])) {
    $eb_status = $_POST['eb_status'];
    $result = $con->updateEventBookings($eb_id, $eb_status);
    if ($result) {
        $sweetAlertConfig = "
        <script>
            Swal.fire({
                title: 'Updated!',
                text: 'Booking status updated successfully!',
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
                text: 'Failed to update status.',
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
    <title>Edit Event Booking Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="../package/dist/sweetalert2.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light py-5">

<div class="container bg-white p-5 rounded shadow-sm" style="max-width: 600px;">
    <h2 class="text-center mb-4">Edit Event Booking Status</h2>
    <?php if ($booking): ?>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Booking ID</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($booking['eb_id']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($booking['user_username'] ?? 'Guest'); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($booking['eb_name']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Contact</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($booking['eb_contact']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Type</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($booking['eb_type']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Venue</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($booking['eb_venue']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($booking['eb_date']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Guests</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($booking['eb_guest']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Order</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($booking['eb_order']); ?>" disabled>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="eb_status" class="form-select" required>
                <option value="Pending" <?php if($booking['eb_status']=='Pending') echo 'selected'; ?>>Pending</option>
                <option value="Half-Paid" <?php if($booking['eb_status']=='Half-Paid') echo 'selected'; ?>>Half-Paid</option>
                <option value="Fully Paid" <?php if($booking['eb_status']=='Fully Paid') echo 'selected'; ?>>Fully Paid</option>
                
            </select>
        </div>
        <div class="d-flex justify-content-end">
            <a href="admin_homepage.php" class="btn btn-secondary me-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Status</button>
        </div>
    </form>
    <?php endif; ?>
</div>
<script src="../package/dist/sweetalert2.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php echo $sweetAlertConfig; ?>
</body>
</html>