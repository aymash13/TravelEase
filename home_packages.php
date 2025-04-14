<?php
include 'config.php';

// Fetch all travel packages
$select_packages = mysqli_query($conn, "SELECT * FROM packages") or die('Query failed');
?>
<head>
<link rel="stylesheet" href="css/hpackage.css">
</head>
<section id="travel-packages-section" class="show-packages">
    <h1 id="packages-title" class="title">Available Travel Packages</h1>
    <div id="packages-container" class="box-container">
        <?php while ($fetch_packages = mysqli_fetch_assoc($select_packages)) { ?>
            <div id="package-<?php echo htmlspecialchars($fetch_packages['id']); ?>" class="box package-item">
                <img id="package-img-<?php echo htmlspecialchars($fetch_packages['id']); ?>" class="package-img" src="uploaded_img/<?php echo htmlspecialchars($fetch_packages['image']); ?>" alt="Package Image">
                <h3 id="package-name-<?php echo htmlspecialchars($fetch_packages['id']); ?>" class="package-name">
                    <?php echo htmlspecialchars($fetch_packages['name']); ?>
                </h3>
                <p id="package-location-<?php echo htmlspecialchars($fetch_packages['id']); ?>" class="package-location">
                    <strong>Location:</strong> <?php echo htmlspecialchars($fetch_packages['location']); ?>
                </p>
                <p id="package-duration-<?php echo htmlspecialchars($fetch_packages['id']); ?>" class="package-duration">
                    <strong>Duration:</strong> <?php echo htmlspecialchars($fetch_packages['duration']); ?>
                </p>
                <p id="package-price-<?php echo htmlspecialchars($fetch_packages['id']); ?>" class="package-price">
                    <strong>Price:</strong> ₹<?php echo number_format($fetch_packages['price'], 2); ?>
                </p>
                <div class="button-container">
                    <a id="more-btn-<?php echo htmlspecialchars($fetch_packages['id']); ?>" class="btn more-btn" href="packages.php?id=<?php echo $fetch_packages['id']; ?>">More</a>
                    <a id="book-btn-<?php echo htmlspecialchars($fetch_packages['id']); ?>" class="btn book-btn" href="book.php?id=<?php echo $fetch_packages['id']; ?>">Book</a>
                </div>
            </div>
        <?php } ?>
    </div>
</section>
