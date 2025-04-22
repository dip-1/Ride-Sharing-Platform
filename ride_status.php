<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$ride_id = $_GET['ride_id'] ?? null;

if (!$ride_id) {
    echo "No ride selected.";
    exit();
}

// Fetch ride
$stmt = $conn->prepare("SELECT r.*, d.name as driver_name, d.phone as driver_phone 
                        FROM rides r
                        LEFT JOIN users d ON r.driver_id = d.id
                        WHERE r.id = ?");
$stmt->bind_param("i", $ride_id);
$stmt->execute();
$result = $stmt->get_result();
$ride = $result->fetch_assoc();

if (!$ride) {
    echo "Ride not found.";
    exit();
}

echo "<h2>Ride Status</h2>";
echo "<p><strong>Pickup:</strong> {$ride['pickup_location']}</p>";
echo "<p><strong>Drop:</strong> {$ride['drop_location']}</p>";
echo "<p><strong>Status:</strong> {$ride['ride_status']}</p>";

if ($ride['driver_id']) {
    echo "<p><strong>Driver:</strong> {$ride['driver_name']} ({$ride['driver_phone']})</p>";
}

// Update ride status (driver)
if ($user['user_type'] == 'driver' && $user['id'] == $ride['driver_id']) {
    echo "<form method='post'>";
    if ($ride['ride_status'] == 'accepted') {
        echo "<button name='start_trip'>Start Trip</button>";
    } elseif ($ride['ride_status'] == 'on_trip') {
        echo "<button name='complete_trip'>Complete Trip</button>";
    }
    echo "</form>";
}

// Cancel option (rider)
if ($user['user_type'] == 'rider' && $ride['ride_status'] == 'requested') {
    echo "<form method='post'><button name='cancel_ride'>Cancel Ride</button></form>";
}

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['start_trip'])) {
        $conn->query("UPDATE rides SET ride_status='on_trip' WHERE id=$ride_id");
    } elseif (isset($_POST['complete_trip'])) {
        $fare = rand(100, 300); // Simulated fare
        $conn->query("UPDATE rides SET ride_status='completed', fare=$fare WHERE id=$ride_id");
    } elseif (isset($_POST['cancel_ride'])) {
        $conn->query("UPDATE rides SET ride_status='cancelled' WHERE id=$ride_id");
    }
    header("Location: ride_status.php?ride_id=$ride_id");
    exit();
}

echo "<p><a href='index.php'>← Back to Dashboard</a></p>";
