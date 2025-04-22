<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'driver') {
    header("Location: login.php");
    exit();
}

$driver_id = $_SESSION['user']['id'];

$stmt = $conn->prepare("SELECT * FROM vehicles WHERE driver_id = ?");
$stmt->bind_param("i", $driver_id);
$stmt->execute();
$result = $stmt->get_result();
$vehicle = $result->fetch_assoc();
?>

<h2>My Vehicle Info</h2>

<?php if ($vehicle): ?>
    <p><strong>Vehicle Number:</strong> <?php echo $vehicle['vehicle_number']; ?></p>
    <p><strong>Model:</strong> <?php echo $vehicle['vehicle_model']; ?></p>
    <p><strong>Type:</strong> <?php echo $vehicle['vehicle_type']; ?></p>
    <p><strong>Status:</strong> <?php echo $vehicle['status']; ?></p>
    <a href="vehicle_register.php">Edit Vehicle Info</a>
<?php else: ?>
    <p>No vehicle registered.</p>
    <a href="vehicle_register.php">Register Vehicle</a>
<?php endif; ?>

<p><a href="index.php">← Back to Dashboard</a></p>
