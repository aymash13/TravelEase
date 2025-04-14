<?php
include 'config.php';

// Debugging: Check database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch all travel packages
$select_packages = mysqli_query($conn, "SELECT * FROM packages");

// Debugging: Check if query is successful
if (!$select_packages) {
    die("Query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Packages</title>
    <link rel="stylesheet" href="css/packages.css">
</head>
<body>
    <?php include('navbar.php'); ?>
    
    <section id="packages-section" class="show-packages">
        <h1 class="section-title">Available Travel Packages</h1>
        <div class="packages-container">
            <?php 
            if (mysqli_num_rows($select_packages) > 0) { 
                while ($fetch_packages = mysqli_fetch_assoc($select_packages)) { ?>
                    <div class="package-box" id="package-<?php echo $fetch_packages['id']; ?>">
                        <div class="package-details">
                            <h3 class="package-name"><?php echo htmlspecialchars($fetch_packages['name']); ?></h3>
                            <p class="package-location"><strong>Location:</strong> <?php echo htmlspecialchars($fetch_packages['location']); ?></p>
                            <p class="package-duration"><strong>Duration:</strong> <?php echo htmlspecialchars($fetch_packages['duration']); ?></p>
                            <p class="package-description"><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($fetch_packages['description'])); ?></p>
                            <p class="package-price"><strong>Price:</strong> ₹<?php echo number_format($fetch_packages['price'], 2); ?></p>
                            <a href="book.php?id=<?php echo $fetch_packages['id']; ?>" class="btn package-book-btn">Book</a>
                        </div>
                        <img class="package-image" src="uploaded_img/<?php echo !empty($fetch_packages['image']) ? htmlspecialchars($fetch_packages['image']) : 'default.jpg'; ?>" alt="Package Image">
                    </div>
                <?php } 
            } else {
                echo "<p class='no-packages'>No travel packages available.</p>";
            }
            ?>
        </div>
    </section>
    
    <?php include('footer.php'); ?>
</body>
</html>