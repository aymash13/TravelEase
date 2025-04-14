<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelEase HOME</title>

    <link rel="stylesheet" href="css/style.css">
    <!--google font links for multiple fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=menu" />
    <!--font awseom-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Monomakh&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!--swiper link-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

</head>

<body>

    <!--header start -->
    <?php include('navbar.php'); ?>
    <!--header end-->

    <!--Home Section Starts-->
    <section class="home">
        <div class="swiper home-slider">
            <div class="swiper-wrapper">

                <div class="swiper-slide slide" style="background: url(images/coverpage1.jpg) no-repeat">
                    <div class="home-content">
                        <span>explore, discover, travel</span>
                        <h3>travel arround the world</h3>
                        <a href="packages.php" class="btn">discover more</a>
                    </div>
                </div>

                <div class="swiper-slide slide" style="background: url(images/coverpage3.jpg) no-repeat">
                    <div class="home-content">
                        <span>explore, discover, travel</span>
                        <h3>travel arround the world</h3>
                        <a href="packages.php" class="btn">discover more</a>
                    </div>
                </div>

                <div class="swiper-slide slide" style="background: url(images/coverpage2.jpg) no-repeat">
                    <div class="home-content">
                        <span>explore, discover, travel</span>
                        <h3>travel arround the world</h3>
                        <a href="packages.php" class="btn">discover more</a>
                    </div>
                </div>


                <div class="swiper-slide slide" style="background: url(images/coverpage5.jpeg) no-repeat">
                    <div class="home-content">
                        <span>explore, discover, travel</span>
                        <h3>travel arround the world</h3>
                        <a href="packages.php" class="btn">discover more</a>
                    </div>
                </div>


                <div class="swiper-slide slide" style="background: url(images/coverpage4.jpg) no-repeat">
                    <div class="home-content">
                        <span>explore, discover, travel</span>
                        <h3>travel arround the world</h3>
                        <a href="packages.php" class="btn">discover more</a>
                    </div>
                </div>

            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>
    <!--Home Section ends-->


    <!--Packages Section start-->
        <?php include('home_packages.php');?>
    
    <!-- Package section ends -->

    <!--about us start-->
    <section class="about-container">

        <div class="benefits">
            <h3 class="section-title">Why Choose Us?</h3>
            <div class="grid">
                <div class="benefit-item">
                    <div class="icon">📚</div>
                    <h4 class="benefit-title">Expert Guidance</h4>
                    <p class="benefit-text">Our travel experts help you plan the perfect trip with insights and
                        recommendations.</p>
                </div>
                <div class="benefit-item">
                    <div class="icon">✈</div>
                    <h4 class="benefit-title">Best Places</h4>
                    <p class="benefit-text">Discover the most beautiful and unique places around the world with us.</p>
                </div>
                <div class="benefit-item">
                    <div class="icon">💰</div>
                    <h4 class="benefit-title">Affordable Prices</h4>
                    <p class="benefit-text">Get the best deals and discounts on travel packages without compromising
                        quality.</p>
                </div>
            </div>
        </div>
        <div class="text-center">
            <h2 class="title">About Us</h2>
            <p class="subtitle">Explore the world with confidence and ease with our travel services.</p>
        </div>

        <div class="card">
            <h3 class="card-title">Who We Are</h3>
            <p class="card-text">We are a team of passionate travelers dedicated to making your travel experience
                unforgettable. With years of expertise, we offer the best travel packages, personalized itineraries, and
                seamless bookings to make your journey stress-free.</p>
        </div>
    </section>

    <!--reviews -->
    <?php
        include('reviews_home.php');
    ?>
    <!--footer start-->
    <?php
        include('footer.php');
    ?>
    <!--footer end-->


    <!--swipper link js-->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/login.js"></script>
    <script src="js/booking.js"></script>
    <!-- js link for dynamic layout-->
</body>

</html>