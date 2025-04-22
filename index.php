<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
<p>You are logged in as: <strong><?php echo $user['user_type']; ?></strong></p>

<?php if ($user['user_type'] == 'rider'): ?>
    <ul>
        <li><a href="book_ride.php">Book a Ride</a></li>
        <li><a href="ride_history.php">View Ride History</a></li>
        <li><a href="edit_profile.php">Edit Profile</a></li>
    </ul>

<?php elseif ($user['user_type'] == 'driver'): ?>
    <ul>
        <li><a href="vehicle_register.php">Register/Edit Vehicle</a></li>
        <li><a href="view_vehicle.php">View My Vehicle Info</a></li>
        <li><a href="ride_status.php">My Ride Requests</a></li>
        <li><a href="edit_profile.php">Edit Profile</a></li>
    </ul>

<?php elseif ($user['user_type'] == 'admin'): ?>
    <ul>
        <li><a href="admin_dashboard.php">Admin Panel</a></li>
        <li><a href="delete_user.php">Delete User</a></li>
        <li><a href="edit_profile.php">Edit Profile</a></li>
    </ul>

<?php endif; ?>

<p><a href="logout.php">Logout</a></p>
