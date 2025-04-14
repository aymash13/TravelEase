<?php
$connect = new PDO("mysql:host=localhost;dbname=travel_agency", "root", "");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Review & Rating</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css2/review.css">
</head>
<body>

    <h2>Submit Your Review</h2>
    <form id="reviewForm">
        <input type="text" name="user_name" id="user_name" placeholder="Your Name" required><br><br>
        <div id="starRating">
            <i class="fas fa-star star" data-rating="1"></i>
            <i class="fas fa-star star" data-rating="2"></i>
            <i class="fas fa-star star" data-rating="3"></i>
            <i class="fas fa-star star" data-rating="4"></i>
            <i class="fas fa-star star" data-rating="5"></i>
        </div>
        <input type="hidden" name="rating_data" id="rating_data">
        <br>
        <textarea name="user_review" id="user_review" placeholder="Write your review..." required></textarea><br>
        <button type="submit">Submit</button>
        <a href="reviews.php" class="button">View All Reviews</a>
    </form>


    <script>
        $(document).ready(function () {
            function loadReviews() {
                $.ajax({
                    url: "fetch_reviews.php",
                    method: "POST",
                    data: { limit: 5 }, // Request only the latest 5 reviews
                    success: function (data) {
                        $("#reviewList").html(data);
                    }
                });
            }
            

            $(document).on("mouseenter", ".star", function () {
                $(".star").removeClass("selected");
                $(this).prevAll().addBack().addClass("selected");
            });

            $(document).on("click", ".star", function () {
                $("#rating_data").val($(this).data("rating"));
            });

            $("#reviewForm").submit(function (event) {
                event.preventDefault();
                $.ajax({
                    url: "submit_review.php",
                    method: "POST",
                    data: $(this).serialize(),
                    success: function (data) {
                        alert(data);
                        $("#reviewForm")[0].reset();
                        $(".star").removeClass("selected");
                        
                    }
                });
            });

            $(document).on("click", ".deleteReview", function () {
                var review_id = $(this).data("id");
                if (review_id === undefined) {
                    alert("Error: Review ID is missing.");
                    return;
                }
                if (confirm("Are you sure you want to delete this review?")) {
                    $.ajax({
                        url: "delete_review.php",
                        method: "POST",
                        data: { id: review_id },
                        success: function (data) {
                            alert(data);
                            loadReviews();
                        }
                    });
                }
            });
        });
    </script>

</body>
</html>
