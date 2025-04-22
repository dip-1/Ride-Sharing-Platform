<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$user_id = $_GET['id'] ?? null;
if ($user_id) {
    // Delete user
    $conn->query("DELETE FROM users WHERE id = $user_id");
    // Optionally delete vehicle or rides
    $conn->query("DELETE FROM vehicles WHERE driver_id = $user_id");
    $conn->query("DELETE FROM rides WHERE rider_id = $user_id OR driver_id = $user_id");
}

header("Location: admin_dashboard.php");
exit();
