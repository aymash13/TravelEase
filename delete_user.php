<?php
include 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    if (isset($_GET['delete']) && isset($_GET['token']) && $_GET['token'] === $_SESSION['csrf_token']) {
        $delete_id = intval($_GET['delete']); // Ensure valid integer ID

        if ($delete_id > 0) {
            // Prevent self-deletion
            if ($delete_id == $_SESSION['admin_id']) {
                $_SESSION['message'] = "You cannot delete your own account!";
                header('Location: admin_users.php');
                exit();
            }

            // Secure deletion
            $stmt = $conn->prepare("DELETE FROM `users` WHERE id = ?");
            $stmt->bind_param("i", $delete_id);
            $stmt->execute();
            $stmt->close();

            $_SESSION['message'] = "User deleted successfully!";
        }
    } else {
        $_SESSION['message'] = "Invalid request or CSRF token!";
    }
} catch (Exception $e) {
    $_SESSION['message'] = "Error: " . $e->getMessage();
}

// ✅ Corrected Redirect
header("Location: admin_user.php");
exit();
?>
