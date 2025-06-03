<?php
session_start();
require 'db_connection.php'; // Your DB connection file

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Sanitize input
$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if (empty($name) || empty($phone)) {
    $_SESSION['error'] = "Name and phone are required.";
    header("Location: dashboard.php");
    exit;
}

$uploadDir = 'uploads/profile_photos/';
$photoPath = $_SESSION['user_photo'] ?? 'uploads/profile_photos/default.jpg';

// Handle profile photo upload if exists
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['photo']['tmp_name'];
    $fileName = basename($_FILES['photo']['name']);
    $fileSize = $_FILES['photo']['size'];
    $fileType = $_FILES['photo']['type'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($fileExt, $allowedExts)) {
        $_SESSION['error'] = "Only JPG, JPEG, PNG, GIF files are allowed for profile picture.";
        header("Location: dashboard.php");
        exit;
    }

    // Create unique file name to avoid collisions
    $newFileName = 'profile_' . $userId . '_' . time() . '.' . $fileExt;
    $destPath = $uploadDir . $newFileName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        // Delete old photo if not default
        if ($photoPath !== 'uploads/profile_photos/default.jpg' && file_exists($photoPath)) {
            unlink($photoPath);
        }
        $photoPath = $destPath;
    } else {
        $_SESSION['error'] = "Failed to upload profile picture.";
        header("Location: dashboard.php");
        exit;
    }
}

// Update user info in DB
$sql = "UPDATE users SET name = ?, phone = ?, photo = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    $_SESSION['error'] = "Database error: " . $conn->error;
    header("Location: dashboard.php");
    exit;
}
$stmt->bind_param('sssi', $name, $phone, $photoPath, $userId);

if ($stmt->execute()) {
    // Update session variables
    $_SESSION['user_name'] = $name;
    $_SESSION['user_phone'] = $phone;
    $_SESSION['user_photo'] = $photoPath;

    $_SESSION['success'] = "Profile updated successfully.";
} else {
    $_SESSION['error'] = "Failed to update profile.";
}

$stmt->close();
$conn->close();

header("Location: dashboard.php");
exit;
?>
