<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'driver') {
    header("Location: login.php");
    exit();
}

$driver_id = $_SESSION['user']['id'];

// Check if vehicle already exists
$stmt = $conn->prepare("SELECT * FROM vehicles WHERE driver_id = ?");
$stmt->bind_param("i", $driver_id);
$stmt->execute();
$result = $stmt->get_result();
$vehicle = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle_number = $_POST['vehicle_number'];
    $vehicle_model = $_POST['vehicle_model'];
    $vehicle_type = $_POST['vehicle_type'];
    $status = 'active';

    if ($vehicle) {
        // Update existing
        $stmt = $conn->prepare("UPDATE vehicles SET vehicle_number=?, vehicle_model=?, vehicle_type=?, status=? WHERE driver_id=?");
        $stmt->bind_param("ssssi", $vehicle_number, $vehicle_model, $vehicle_type, $status, $driver_id);
    } else {
        // Insert new
        $stmt = $conn->prepare("INSERT INTO vehicles (driver_id, vehicle_number, vehicle_model, vehicle_type, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $driver_id, $vehicle_number, $vehicle_model, $vehicle_type, $status);
    }

    if ($stmt->execute()) {
        echo "<p>Vehicle information saved successfully.</p>";
        header("Location: view_vehicle.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<h2><?php echo $vehicle ? "Update Vehicle" : "Register Vehicle"; ?></h2>
<form method="post">
    <input type="text" name="vehicle_number" placeholder="Vehicle Number" value="<?php echo $vehicle['vehicle_number'] ?? ''; ?>" required><br>
    <input type="text" name="vehicle_model" placeholder="Vehicle Model" value="<?php echo $vehicle['vehicle_model'] ?? ''; ?>" required><br>
    <input type="text" name="vehicle_type" placeholder="Vehicle Type" value="<?php echo $vehicle['vehicle_type'] ?? ''; ?>" required><br>
    <button type="submit"><?php echo $vehicle ? "Update" : "Register"; ?></button>
</form>
<p><a href="index.php">← Back to Dashboard</a></p>
