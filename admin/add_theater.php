<?php
require_once '../db_connect.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $location = $_POST['location'] ?? '';
    $capacity = $_POST['capacity'] ?? 0;
    $address = $_POST['address'] ?? '';
    $contact = $_POST['contact_number'] ?? '';
    $created_at = date('Y-m-d H:i:s');

    $uploadDir = 'uploads/theaters/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $photoPath = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['photo']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowedExts)) {
            $newName = uniqid('theater_') . '.' . $ext;
            $photoPath = $uploadDir . $newName;
            move_uploaded_file($tmpName, $photoPath);
        } else {
            $error = "Invalid photo format!";
        }
    }

    if (!$error) {
        try {
            $stmt = $conn->prepare("INSERT INTO theaters (name, location, capacity, created_at, address, contact_number, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $location, $capacity, $created_at, $address, $contact, $photoPath]);
            $success = "Theater added successfully!";
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Theater</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #ffffff, #ffcce5);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }
        .container {
            background: linear-gradient(135deg, #ff4c60, #4ecdc4);
            border-radius: 16px;
            padding: 30px 40px;
            width: 100%;
            max-width: 650px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
        }
        label {
            margin-top: 12px;
            display: block;
            font-weight: 500;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px 14px;
            margin-top: 6px;
            border: none;
            border-radius: 8px;
            background: #ffffff;
            color: #000;
        }
        select[name="location"] {
            color: #000;
        }
        select[name="location"] option {
            color: black;
        }
        input[type="file"] {
            background: #ffffff;
        }
        textarea {
            resize: vertical;
        }
        input[type="submit"] {
            background-color: #218838;
            margin-top: 20px;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #00b092;
        }
        .success {
            color: #a2ffbf;
            margin-bottom: 15px;
        }
        .error {
            color: #ff8f8f;
            margin-bottom: 15px;
        }
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Theater</h2>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" required>

        <label for="location">Location:</label>
        <select name="location" id="location" required>
            <option value="" disabled selected>Select District</option>
            <option value="Dhaka">Dhaka</option>
            <option value="Chattogram">Chattogram</option>
            <option value="Khulna">Khulna</option>
            <option value="Rajshahi">Rajshahi</option>
            <option value="Sylhet">Sylhet</option>
            <!-- Add all districts here if needed -->
        </select>

        <label>Capacity:</label>
        <input type="number" name="capacity" min="0" required>

        <label>Address:</label>
        <textarea name="address" rows="3" required></textarea>

        <label>Contact Number:</label>
        <input type="text" name="contact_number" required>

        <label>Upload Photo:</label>
        <input type="file" name="photo" accept="image/*" required>

        <input type="submit" value="Add Theater">
    </form>
</div>

</body>
</html>
