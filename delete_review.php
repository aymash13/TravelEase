<?php
$connect = new PDO("mysql:host=localhost;dbname=travel_agency", "root", "");

$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(isset($_POST["id"])) {
    try {
        $query = "DELETE FROM review_table WHERE review_id = :id";
        $statement = $connect->prepare($query);
        $statement->execute([':id' => $_POST["id"]]);

        if ($statement->rowCount() > 0) {
            echo "Review deleted successfully!";
        } else {
            echo "Error: Review not found or already deleted.";
        }
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }
} else {
    echo "Error: Review ID not received.";
}
?>
