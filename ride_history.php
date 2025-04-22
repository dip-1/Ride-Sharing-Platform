<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$user_id = $user['id'];
$type = $user['user_type'];

if ($type === 'rider') {
    $stmt = $conn->prepare("SELECT r.*, d.name AS driver_name 
                            FROM rides r 
                            LEFT JOIN users d ON r.driver_id = d.id 
                            WHERE r.rider_id = ? 
                            ORDER BY r.id DESC");
    $stmt->bind_param("i", $user_id);
} elseif ($type === 'driver') {
    $stmt = $conn->prepare("SELECT r.*, u.name AS rider_name 
                            FROM rides r 
                            LEFT JOIN users u ON r.rider_id = u.id 
                            WHERE r.driver_id = ? 
                            ORDER BY r.id DESC");
    $stmt->bind_param("i", $user_id);
} else {
    echo "Admins do not have ride history.";
    exit();
}

$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Your Ride History</h2>

<?php if ($result->num_rows > 0): ?>
    <table border="1" cellpadding="6">
        <tr>
            <th>ID</th>
            <th>Pickup</th>
            <th>Drop</th>
            <?php if ($type === 'rider'): ?>
                <th>Driver</th>
            <?php else: ?>
                <th>Rider</th>
            <?php endif; ?>
            <th>Status</th>
            <th>Fare</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['pickup_location']; ?></td>
                <td><?php echo $row['drop_location']; ?></td>
                <td><?php echo $type === 'rider' ? $row['driver_name'] : $row['rider_name']; ?></td>
                <td><?php echo $row['ride_status']; ?></td>
                <td><?php echo $row['fare'] ?? 'N/A'; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No rides found.</p>
<?php endif; ?>

<p><a href="index.php">← Back to Dashboard</a></p>
