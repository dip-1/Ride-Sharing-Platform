<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = strtolower(trim($_POST['email']));
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user_type = $_POST['user_type'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password, user_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $password, $user_type);

    if ($stmt->execute()) {
        echo "Registered successfully. <a href='login.php'>Login now</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<form method="post">
    <h2>Register</h2>
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="text" name="phone" placeholder="Phone" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <select name="user_type" required>
        <option value="rider">Rider</option>
        <option value="driver">Driver</option>
        <option value="admin">Admin</option>
    </select><br>
    <button type="submit">Register</button>
</form>
