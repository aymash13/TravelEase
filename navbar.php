<?php
session_start();
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelEase HOME</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <!-- Header Start -->
    <section class="header">
        <div class="logo-container">
            <img src="images/new logo.jpg" alt="TravelEase Logo">
            <a href="home.php" class="logo">TravelEase</a>
        </div>

        <nav class="nav-bar">
            <a href="home.php">Home</a>
            <a href="packages.php">Packages</a>
            <a href="bookings.php">Bookings</a>
            <a href="contact.php">Contact Us</a>
            <?php if (!isset($_SESSION['user_name'])): ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>

        <div class="icons">
            <i class="fa-solid fa-bars" id="menu-but"></i>
            <i class="fa-solid fa-user" id="user-icon"></i>
        </div>

        <!-- User Dropdown Menu -->
        <div class="user-dropdown" id="user-menu">
            <?php if (isset($_SESSION['user_name'])): ?>
                <p>Welcome, <strong><?php echo $_SESSION['user_name']; ?></strong></p>
                <a href="profile.php">Profile</a>
                <a href="logout.php" class="logout-btn">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </section>
    <!-- Header End -->

    <script>
        document.getElementById("user-icon").addEventListener("click", function () {
            document.getElementById("user-menu").classList.toggle("active");
        });

        document.addEventListener("click", function (event) {
            const userMenu = document.getElementById("user-menu");
            const userIcon = document.getElementById("user-icon");
            if (!userMenu.contains(event.target) && event.target !== userIcon) {
                userMenu.classList.remove("active");
            }
        });
    </script>
</body>
</html>
