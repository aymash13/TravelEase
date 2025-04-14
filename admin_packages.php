<?php
include 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('location:login.php');
    exit();
}

function uploadImage($image, $image_tmp_name, $image_size) {
    $allowed_formats = ['jpg', 'jpeg', 'png'];
    $image_ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $image_tmp_name);
    finfo_close($finfo);

    $valid_mime_types = ['image/jpeg', 'image/png'];

    if (!in_array($image_ext, $allowed_formats) || !in_array($mime_type, $valid_mime_types)) {
        return ['error' => 'Invalid image format! Only JPG, JPEG, and PNG allowed.'];
    }

    if ($image_size > 2000000) {
        return ['error' => 'Image size is too large!'];
    }

    $upload_dir = 'uploaded_img/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $image_new_name = uniqid() . '.' . $image_ext;
    $image_path = $upload_dir . $image_new_name;

    if (move_uploaded_file($image_tmp_name, $image_path)) {
        return ['success' => $image_new_name];
    } else {
        return ['error' => 'Failed to upload image!'];
    }
}

if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    
    $stmt = $conn->prepare("SELECT image FROM packages WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $fetch = $result->fetch_assoc();
        if (!empty($fetch['image']) && file_exists('uploaded_img/' . $fetch['image'])) {
            unlink('uploaded_img/' . $fetch['image']);
        }

        $delete_stmt = $conn->prepare("DELETE FROM packages WHERE id = ?");
        $delete_stmt->bind_param("i", $delete_id);
        $delete_stmt->execute();
    }

    header('location:admin_packages.php');
    exit();
}


$update_mode = false;
$update_package_data = [];
if (isset($_GET['update'])) {
    $update_id = intval($_GET['update']);
    $stmt = $conn->prepare("SELECT * FROM packages WHERE id = ?");
    $stmt->bind_param("i", $update_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $update_mode = true;
        $update_package_data = $result->fetch_assoc();
    }
}


if (isset($_POST['update_package'])) {
    $name = trim($_POST['name']);
    $location = trim($_POST['location']);
    $duration = trim($_POST['duration']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $image_name = '';

    if ($update_mode) {
        $update_p_id = intval($_POST['update_p_id']);
        $update_old_image = $_POST['update_old_image'];

        
        $stmt = $conn->prepare("UPDATE packages SET name = ?, location = ?, duration = ?, description = ?, price = ? WHERE id = ?");
        $stmt->bind_param("ssssdi", $name, $location, $duration, $description, $price, $update_p_id);
        $stmt->execute();

        
        if (!empty($_FILES['image']['name'])) {
            $upload_result = uploadImage($_FILES['image']['name'], $_FILES['image']['tmp_name'], $_FILES['image']['size']);
            if (isset($upload_result['success'])) {
                $new_image_name = $upload_result['success'];

               
                if (!empty($update_old_image) && file_exists('uploaded_img/' . $update_old_image)) {
                    unlink('uploaded_img/' . $update_old_image);
                }

                $stmt = $conn->prepare("UPDATE packages SET image = ? WHERE id = ?");
                $stmt->bind_param("si", $new_image_name, $update_p_id);
                $stmt->execute();
            }
        }
    } else {
        
        if (!empty($_FILES['image']['name'])) {
            $upload_result = uploadImage($_FILES['image']['name'], $_FILES['image']['tmp_name'], $_FILES['image']['size']);
            if (isset($upload_result['success'])) {
                $image_name = $upload_result['success'];
            } else {
                echo "<script>alert('".$upload_result['error']."');</script>";
                exit();
            }
        }

        $stmt = $conn->prepare("INSERT INTO packages (name, location, duration, description, price, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssds", $name, $location, $duration, $description, $price, $image_name);
        $stmt->execute();
    }

    header('location:admin_packages.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Packages</title>
    <link rel="stylesheet" href="css2/admin_style.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<section class="package-form">
    <h1 class="title"><?php echo $update_mode ? 'UPDATE PACKAGE' : 'ADD PACKAGE'; ?></h1>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="update_p_id" value="<?php echo $update_mode ? $update_package_data['id'] : ''; ?>">
        <input type="text" name="name" class="box" placeholder="Enter package name" value="<?php echo $update_mode ? htmlspecialchars($update_package_data['name']) : ''; ?>" required>
        <input type="text" name="location" class="box" placeholder="Enter package location" value="<?php echo $update_mode ? htmlspecialchars($update_package_data['location']) : ''; ?>" required>
        <input type="text" name="duration" class="box" placeholder="Enter package duration" value="<?php echo $update_mode ? htmlspecialchars($update_package_data['duration']) : ''; ?>" required>
        <input type="text" name="description" class="box" placeholder="Enter package details" value="<?php echo $update_mode ? htmlspecialchars($update_package_data['description']) : ''; ?>" required>
        <input type="number" min="0" name="price" class="box" placeholder="Enter package price" value="<?php echo $update_mode ? htmlspecialchars($update_package_data['price']) : ''; ?>" required>
        <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box">
        <input type="submit" value="<?php echo $update_mode ? 'Update Package' : 'Add Package'; ?>" name="update_package" class="btn">
    </form>
</section>

<section class="show-packages">
    <div class="box-container">
        <?php
        $select_packages = mysqli_query($conn, "SELECT * FROM packages") or die('Query failed');
        while ($fetch_packages = mysqli_fetch_assoc($select_packages)) {
        ?>
            <div class="box">
                <img src="uploaded_img/<?php echo htmlspecialchars($fetch_packages['image']); ?>" alt="Package Image">
                <h3><?php echo htmlspecialchars($fetch_packages['name']); ?></h3>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($fetch_packages['location']); ?></p>
                <p><strong>Duration:</strong> <?php echo htmlspecialchars($fetch_packages['duration']); ?></p>
                <p><strong>Price:</strong> ₹<?php echo number_format($fetch_packages['price'], 2); ?></p>
                <a href="admin_packages.php?update=<?php echo $fetch_packages['id']; ?>" class="option-btn">Update</a>
                <a href="admin_packages.php?delete=<?php echo $fetch_packages['id']; ?>" class="delete-btn" onclick="return confirm('Delete this package?');">Delete</a>
            </div>
        <?php } ?>
    </div>
</section>

</body>
</html>
