<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

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
<?php include 'admin_header.php'; ?>


    <h2>Reviews</h2>
    <div id="reviewList"></div>

    <script>
        $(document).ready(function () {
            function loadReviews() {
                $.ajax({
                    url: "admin_fetch_review.php",
                    method: "POST",
                    success: function (data) {
                        $("#reviewList").html(data);
                    }
                });
            }
            loadReviews();

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
                        loadReviews();
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
