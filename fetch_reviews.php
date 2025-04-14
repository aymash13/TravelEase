<?php
$connect = new PDO("mysql:host=localhost;dbname=travel_agency", "root", "");

$query = "SELECT review_id, user_name, user_rating, user_review FROM review_table ORDER BY review_id DESC";
$statement = $connect->prepare($query);
$statement->execute();
$reviews = $statement->fetchAll(PDO::FETCH_ASSOC);

$output = "";
if (count($reviews) > 0) {
    foreach ($reviews as $row) {
        $initial = strtoupper(substr($row["user_name"], 0, 1));
        $stars = str_repeat('<i class="fas fa-star selected"></i>', $row["user_rating"]);

        $output .= '
            <div class="review-box">
                <div class="initial-circle">' . htmlspecialchars($initial) . '</div>
                <div class="review-content">
                    <h4>' . htmlspecialchars($row["user_name"]) . '</h4>
                    <p>' . $stars . '</p>
                    <p>' . htmlspecialchars($row["user_review"]) . '</p>
                    
                </div>
            </div>
            <hr>
        ';
    }
} else {
    $output = "<p>No reviews yet.</p>";
}

echo $output;
?>
