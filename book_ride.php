<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'rider') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rider_id = $_SESSION['user']['id'];
    $pickup = $_POST['pickup'];
    $drop = $_POST['drop'];

    // Create ride request with driver_id NULL (to be assigned)
    $stmt = $conn->prepare("INSERT INTO rides (rider_id, pickup_location, drop_location) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $rider_id, $pickup, $drop);
    if ($stmt->execute()) {
        $ride_id = $conn->insert_id;
        header("Location: find_driver.php?ride_id=$ride_id");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<h2>Book a Ride</h2>
<form method="post">
    <input type="text" name="pickup" placeholder="Pickup Location" required><br>
    <input type="text" name="drop" placeholder="Drop Location" required><br>
    <button type="submit">Request Ride</button>
</form>
<p><a href="index.php">← Back to Dashboard</a></p>
