<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$query = "SELECT r.*, u.name AS rider_name, d.name AS driver_name 
          FROM rides r
          LEFT JOIN users u ON r.rider_id = u.id
          LEFT JOIN users d ON r.driver_id = d.id
          ORDER BY r.id DESC";

$result = $conn->query($query);
?>

<h2>All Rides in System</h2>

<table border="1" cellpadding="6">
    <tr>
        <th>ID</th>
        <th>Rider</th>
        <th>Driver</th>
        <th>Pickup</th>
        <th>Drop</th>
        <th>Status</th>
        <th>Fare</th>
    </tr>
    <?php while ($ride = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $ride['id']; ?></td>
        <td><?php echo $ride['rider_name']; ?></td>
        <td><?php echo $ride['driver_name'] ?? 'Unassigned'; ?></td>
        <td><?php echo $ride['pickup_location']; ?></td>
        <td><?php echo $ride['drop_location']; ?></td>
        <td><?php echo $ride['ride_status']; ?></td>
        <td><?php echo $ride['fare'] ?? 'N/A'; ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<p><a href="admin_dashboard.php">← Back to Admin Panel</a></p>
