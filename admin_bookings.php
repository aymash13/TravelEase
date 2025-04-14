<?php
include 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('location:login.php');
    exit();
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed!");
    }

    $booking_id = intval($_POST['booking_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);

    $valid_statuses = ['Pending', 'Confirmed', 'Completed', 'Cancelled'];
    if (!in_array($new_status, $valid_statuses)) {
        die("Invalid status value!");
    }

    $update_query = "UPDATE bookings SET status = '$new_status' WHERE id = $booking_id";
    
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['message'] = "Booking status updated successfully!";
    } else {
        $_SESSION['message'] = "Update failed: " . mysqli_error($conn);
    }

    header("Location: admin_bookings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="bookings">
    <h1 class="title">Manage Bookings</h1>

    <!-- Display session messages -->
    <?php if (isset($_SESSION['message'])): ?>
        <p class="message"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>

    <table>
        <tr>
            <th>Booking ID</th>
            <th>User</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Package</th>
            <th>Travel Date</th>
            <th>Guests</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        
        <?php
        try {
            $stmt = $conn->prepare("SELECT bookings.id, users.name AS user_name, users.phone, users.email, packages.name AS package_name, 
                                            bookings.travel_date, bookings.guests, packages.price, bookings.status 
                                    FROM bookings 
                                    INNER JOIN users ON bookings.user_id = users.id
                                    INNER JOIN packages ON bookings.package_id = packages.id 
                                    ORDER BY bookings.created_at DESC");
            $stmt->execute();
            $result = $stmt->get_result();

            while ($booking = $result->fetch_assoc()) {
                $total_price = $booking['price'] * max(1, intval($booking['guests']));
        ?>
        <tr>
            <td><?php echo $booking['id']; ?></td>
            <td><?php echo htmlspecialchars($booking['user_name'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo !empty($booking['phone']) ? htmlspecialchars($booking['phone']) : 'Not Provided'; ?></td>
            <td><?php echo htmlspecialchars($booking['email'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($booking['package_name'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($booking['travel_date'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo $booking['guests']; ?></td>
            <td>₹<?php echo number_format($total_price, 2); ?></td>
            <td style="color: <?php echo ($booking['status'] == 'Confirmed') ? 'green' : (($booking['status'] == 'Cancelled') ? 'red' : 'blue'); ?>;">
                <?php echo htmlspecialchars($booking['status'], ENT_QUOTES, 'UTF-8'); ?>
            </td>
            <td>
                <form method="POST">
                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <select name="status">
                        <option value="Pending" <?php if ($booking['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                        <option value="Confirmed" <?php if ($booking['status'] == 'Confirmed') echo 'selected'; ?>>Confirmed</option>
                        <option value="Completed" <?php if ($booking['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                        <option value="Cancelled" <?php if ($booking['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                    </select>
                    <button type="submit" name="update_status" class="update-btn">Update</button>
                </form>
            </td>
        </tr>
        <?php } $stmt->close(); } catch (Exception $e) { echo "Error: " . $e->getMessage(); } ?>
    </table>
</section>

</body>
</html>
