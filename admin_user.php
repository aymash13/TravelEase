<?php
include 'config.php'; // Database connection
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('location:login.php');
    exit();
}


if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="users">
    <h1 class="title">User Accounts</h1>

    <!-- Display session messages -->
    <?php if (isset($_SESSION['message'])): ?>
        <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>

    <table>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>User Type</th>
            <th>Actions</th>
        </tr>
        
        <?php
        try {
            
            $stmt = $conn->prepare("SELECT id, name, email, user_type FROM `users`");
            $stmt->execute();
            $result = $stmt->get_result();

            while ($fetch_users = $result->fetch_assoc()) {
        ?>
        <tr>
            <td><?php echo $fetch_users['id']; ?></td>
            <td><?php echo htmlspecialchars($fetch_users['name'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($fetch_users['email'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td style="color: <?php echo ($fetch_users['user_type'] == 'admin') ? 'orange' : 'purple'; ?>">
                <?php echo htmlspecialchars($fetch_users['user_type'], ENT_QUOTES, 'UTF-8'); ?>
            </td>
            <td>
                <?php if ($fetch_users['id'] != $_SESSION['admin_id']): ?>
                    <a href="delete_user.php?delete=<?php echo $fetch_users['id']; ?>&token=<?php echo $_SESSION['csrf_token']; ?>" 
                       onclick="return confirm('Are you sure you want to delete this user?');" 
                       class="delete-btn">Delete User</a>
                <?php else: ?>
                    <span class="disabled-btn">Cannot Delete</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php } $stmt->close(); } catch (Exception $e) { echo "Error: " . $e->getMessage(); } ?>
    </table>
</section>

</body>
</html>
