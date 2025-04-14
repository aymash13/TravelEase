<?php include('navbar.php'); ?>
<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; 
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];


if (!isset($_GET['id'])) {
    die("No package ID provided.");
}

$package_id = intval($_GET['id']);


$package_query = mysqli_query($conn, "SELECT * FROM packages WHERE id = $package_id");
$package = mysqli_fetch_assoc($package_query);


if (!$package) {
    die("Package not found.");
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $travel_date = mysqli_real_escape_string($conn, $_POST['travel_date']);
    $guests = intval($_POST['guests']);
    $total_price = $package['price'] * $guests;

    
    $insert_booking = mysqli_query($conn, "INSERT INTO bookings (user_id, package_id, name, email, phone, travel_date, guests, total_price) 
                                           VALUES ('$user_id', '$package_id', '$name', '$email', '$phone', '$travel_date', '$guests', '$total_price')");

    if ($insert_booking) {
        echo "<script>alert('Booking successful! Total Price: ₹$total_price'); window.location.href='bookings.php';</script>";
    } else {
        die("Booking failed: " . mysqli_error($conn)); 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Package</title>
    
    <script>
        function updateTotalPrice() {
            var pricePerPerson = <?php echo $package['price']; ?>;
            var guests = document.getElementById("guest-count").value;
            var totalPrice = pricePerPerson * guests;
            document.getElementById("total-price").innerText = "Total Price: ₹" + totalPrice;
            document.getElementById("total-price").style.color = "#FF5733";
        }
    </script>
    <link rel="stylesheet" href="css2/books.css">
</head>
<body>
    
    <section id="booking-container">
        <h1 class="booking-title">Book Package: <?php echo htmlspecialchars($package['name']); ?></h1>
        
        <div class="package-summary">
            <p><strong>📍 Location:</strong> <?php echo htmlspecialchars($package['location']); ?></p>
            <p><strong>⏳ Duration:</strong> <?php echo htmlspecialchars($package['duration']); ?></p>
            <p class="price-text"><strong>💰 Price Per Person:</strong> ₹<?php echo number_format($package['price'], 2); ?></p>
        </div>

        <form action="" method="POST" class="booking-form">
            <div class="form-group">
                <label for="user-name">👤 Name:</label>
                <input type="text" id="user-name" name="name" value="<?php echo htmlspecialchars($user_name); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="user-email">📧 Email:</label>
                <input type="email" id="user-email" name="email" value="<?php echo htmlspecialchars($user_email); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="user-phone">📞 Phone:</label>
                <input type="text" id="user-phone" name="phone" required placeholder="Enter your phone number">
            </div>

            <div class="form-group">
                <label for="travel-date">📅 Travel Date:</label>
                <input type="date" id="travel-date" name="travel_date" required>
            </div>

            <div class="form-group">
                <label for="guest-count">🧑‍🤝‍🧑 Number of Guests:</label>
                <input type="number" id="guest-count" name="guests" value="1" min="1" required oninput="updateTotalPrice()">
            </div>

            <p id="total-price">Total Price: ₹<?php echo $package['price']; ?></p>

            <button type="submit" class="btn-book">Confirm Booking</button>
        </form>
    </section>
    
    <?php include('footer.php'); ?>
</body>
</html>
