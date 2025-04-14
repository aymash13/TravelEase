<?php
$connect = new PDO("mysql:host=localhost;dbname=travel_agency", "root", "");

if(isset($_POST["user_name"], $_POST["rating_data"], $_POST["user_review"])) {
    $query = "INSERT INTO review_table (user_name, user_rating, user_review) VALUES (:user_name, :user_rating, :user_review)";
    $statement = $connect->prepare($query);
    
    $statement->execute([
        ':user_name' => $_POST["user_name"],
        ':user_rating' => $_POST["rating_data"],
        ':user_review' => $_POST["user_review"]
    ]);

    echo "Your Review & Rating Successfully Submitted!";
} else {
    echo "Error: Missing Required Fields.";
}
?>
