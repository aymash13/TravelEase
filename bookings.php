<?php include('navbar.php'); ?>
<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_booking'])) {
    $booking_id = intval($_POST['booking_id']);
    
    $delete_query = "DELETE FROM bookings WHERE id = $booking_id AND user_id = $user_id AND status = 'Pending'";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Booking cancelled and deleted successfully!'); window.location.href='bookings.php';</script>";
    } else {
        echo "<script>alert('Cancellation failed: " . mysqli_error($conn) . "');</script>";
    }
}

$booking_query = mysqli_query($conn, "SELECT bookings.*, packages.name AS package_name, packages.price, bookings.guests
                                      FROM bookings 
                                      INNER JOIN packages ON bookings.package_id = packages.id 
                                      WHERE bookings.user_id = '$user_id' 
                                      ORDER BY bookings.created_at DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet" href="css2/books.css">
    <script>
        function confirmCancellation(bookingId) {
            if (confirm("Are you sure you want to cancel and delete this booking? This action cannot be undone.")) {
                document.getElementById("cancel-form-" + bookingId).submit();
            }
        }
    </script>
</head>
<body>

<section id="booking-section">
    <h1 class="booking-title">My Bookings</h1>

    <?php if (mysqli_num_rows($booking_query) > 0) { ?>
        <div class="booking-container">
            <table id="booking-table">
                <thead>
                    <tr>
                        <th>Package</th>
                        <th>Travel Date</th>
                        <th>Guests</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Booked On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($booking = mysqli_fetch_assoc($booking_query)) { 
                        $guests = !empty($booking['guests']) ? intval($booking['guests']) : 1;
                        $total_price = $booking['price'] * $guests; // Now multiplies price by guests
                    ?>
                        <tr class="booking-row">
                            <td><?php echo htmlspecialchars($booking['package_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['travel_date']); ?></td>
                            <td><?php echo $guests; ?></td> 
                            <td class="price">₹<?php echo number_format($total_price, 2); ?></td>
                            <td class="status <?php echo strtolower($booking['status']); ?>">
                                <?php echo htmlspecialchars($booking['status']); ?>
                            </td>
                            <td><?php echo date("d M Y", strtotime($booking['created_at'])); ?></td>
                            <td>
                                <?php if ($booking['status'] == 'Pending') { ?>
                                    <form id="cancel-form-<?php echo $booking['id']; ?>" method="POST">
                                        <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                        <button type="submit" name="cancel_booking" class="cancel-btn">Cancel</button>
                                    </form>
                                <?php } else { ?>
                                    <span class="disabled-btn">Not Allowed</span>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <p id="no-bookings">You have no bookings yet.</p>
    <?php } ?>
</section>

<?php include('footer.php'); ?>

</body>
</html>
