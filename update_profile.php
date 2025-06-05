<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id    = $_POST['id'];
    $name  = $_POST['name'];
    $phone = $_POST['phone'];

    // Handle image upload
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $targetDir = "uploads/";
        $photoName = basename($_FILES["photo"]["name"]);
        $targetFile = $targetDir . time() . '_' . $photoName;

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
            $photo = $targetFile;
        }
    }

    try {
        if ($photo != '') {
            // Update with photo
            $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ?, photo = ? WHERE id = ?");
            $stmt->execute([$name, $phone, $photo, $id]);
        } else {
            // Update without photo
            $stmt = $conn->prepare("UPDATE users SET name = ?, phone = ? WHERE id = ?");
            $stmt->execute([$name, $phone, $id]);
        }

        // Redirect or success message
        header("Location: dashboard.php?message=Profile+updated+successfully");
        exit;
    } catch (PDOException $e) {
        echo "Update failed: " . $e->getMessage();
    }
}
?>
