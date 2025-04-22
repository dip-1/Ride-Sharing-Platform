<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'rider') {
    header("Location: login.php");
    exit();
}

$ride_id = $_GET['ride_id'] ?? null;

if (!$ride_id) {
    echo "No ride found.";
    exit();
}

// Simulate finding nearest driver (fetch first available driver with vehicle)
$sql = "SELECT u.id, u.name, u.phone, v.vehicle_number, v.vehicle_model, v.vehicle_type
        FROM users u
        JOIN vehicles v ON u.id = v.driver_id
        WHERE u.user_type = 'driver' AND v.status = 'active'
        LIMIT 1";

$result = $conn->query($sql);
$driver = $result->fetch_assoc();

if ($driver) {
    // Assign driver to ride
    $stmt = $conn->prepare("UPDATE rides SET driver_id = ?, ride_status = 'accepted' WHERE id = ?");
    $stmt->bind_param("ii", $driver['id'], $ride_id);
    $stmt->execute();

    echo "<h3>Driver Found!</h3>";
    echo "<p><strong>Name:</strong> {$driver['name']}</p>";
    echo "<p><strong>Phone:</strong> {$driver['phone']}</p>";
    echo "<p><strong>Vehicle:</strong> {$driver['vehicle_model']} ({$driver['vehicle_type']}) - {$driver['vehicle_number']}</p>";
    echo "<p>Ride status: <strong>Accepted</strong></p>";
    echo "<a href='ride_status.php?ride_id=$ride_id'>Go to Ride Status</a>";

} else {
    echo "<h3>Finding a driver...</h3>";
    echo "<p>Please wait 10 seconds. If no one is found, try again.</p>";
    echo "<script>
        setTimeout(function() {
            window.location.reload();
        }, 10000);
    </script>";
}
