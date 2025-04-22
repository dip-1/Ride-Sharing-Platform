<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all users (except admin)
$users = $conn->query("SELECT * FROM users WHERE user_type != 'admin' ORDER BY user_type, id");
?>

<h2>Admin Dashboard</h2>

<h3>All Users</h3>
<table border="1" cellpadding="6">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>User Type</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $users->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['name']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo $row['user_type']; ?></td>
        <td>
            <a href="delete_user.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete user?');">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<p><a href="all_rides.php">View All Rides</a></p>
<p><a href="index.php">← Back to Dashboard</a></p>
