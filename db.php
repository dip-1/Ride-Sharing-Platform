<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // use your MySQL password if set
$db = 'ride_sharing_app';

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
