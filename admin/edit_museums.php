<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

$error = '';
$success = '';

if (!isset($_GET['id'])) {
    $error = "No museum ID provided.";
} else {
    $museum_id = intval($_GET['id']);

    // Fetch museum info
    $stmt = $conn->prepare("SELECT * FROM museums WHERE museum_id = ?");
    $stmt->execute([$museum_id]);
    $museum = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$museum) {
        $error = "Museum not found.";
    }
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($museum)) {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $location = $_POST['location'] ?? '';
    $available_tickets = $_POST['available_tickets'] ?? 0;
    $price = $_POST['price'] ?? 0;
    $address = $_POST['address'] ?? '';
    $opening_hours = $_POST['opening_hours'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $photoPath = $museum['photo']; // Keep existing photo by default

    $uploadDir = 'uploads/museums/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // New photo upload (optional)
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoTmp = $_FILES['photo']['tmp_name'];
        $photoName = basename($_FILES['photo']['name']);
        $ext = strtolower(pathinfo($photoName, PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $allowedExts)) {
            $newName = uniqid('museum_') . '.' . $ext;
            $photoPath = $uploadDir . $newName;
            move_uploaded_file($photoTmp, $photoPath);
        } else {
            $error = "Invalid photo type.";
        }
    }

    if (!$error) {
        try {
            $stmt = $conn->prepare("UPDATE museums SET name=?, description=?, location=?, available_tickets=?, price=?, photo=?, address=?, opening_hours=?, contact=? WHERE museum_id=?");
            $stmt->execute([$title, $description, $location, $available_tickets, $price, $photoPath, $address, $opening_hours, $contact, $museum_id]);
            $success = "Museum updated successfully!";
            // Refresh museum info
            $stmt = $conn->prepare("SELECT * FROM museums WHERE museum_id = ?");
            $stmt->execute([$museum_id]);
            $museum = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Museum</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
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
            background: linear-gradient(135deg, #4ecdc4, #ff4c60);
            border-radius: 16px;
            padding: 30px 40px;
            width: 100%;
            max-width: 600px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
        }
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
        }
        a {
            display: inline-block;
            color: #f1f1f1;
            text-decoration: none;
            margin-bottom: 20px;
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
        input[type="file"] {
            background: #ffffff;
        }
        select[name="location"] {
            color: #000;
        }
        select[name="location"] option {
            color: black;
        }
        textarea {
            resize: vertical;
        }
        input[type="submit"] {
            background-color: #218838;
            margin-top: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: bold;
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
    <h1>✏️ Edit Museum</h1>
    <a href="dashboard.php">← Back to Dashboard</a>

    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?= htmlspecialchars($success) ?></p><?php endif; ?>

    <?php if (isset($museum)): ?>
    <form method="POST" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($museum['name']) ?>" required>

        <label>Description:</label>
        <textarea name="description" rows="4"><?= htmlspecialchars($museum['description']) ?></textarea>

        <label>Location:</label>
        <select name="location" required>
            <option value="" disabled>Select District</option>
            <?php
            $districts = ["Bagerhat","Bandarban","Barguna","Barisal","Bhola","Bogra","Brahmanbaria","Chandpur","Chapai Nawabganj","Chattogram","Chuadanga","Comilla","Cox's Bazar","Dhaka","Dinajpur","Faridpur","Feni","Gaibandha","Gazipur","Gopalganj","Habiganj","Jamalpur","Jashore","Jhalokati","Jhenaidah","Joypurhat","Khagrachhari","Khulna","Kishoreganj","Kurigram","Kushtia","Lakshmipur","Lalmonirhat","Madaripur","Magura","Manikganj","Meherpur","Moulvibazar","Munshiganj","Mymensingh","Naogaon","Narail","Narayanganj","Narsingdi","Natore","Netrokona","Nilphamari","Noakhali","Pabna","Panchagarh","Patuakhali","Pirojpur","Rajbari","Rajshahi","Rangamati","Rangpur","Satkhira","Shariatpur","Sherpur","Sirajganj","Sunamganj","Sylhet","Tangail","Thakurgaon"];
            foreach ($districts as $district) {
                $selected = ($museum['location'] === $district) ? 'selected' : '';
                echo "<option value=\"$district\" $selected>$district</option>";
            }
            ?>
        </select>

        <div class="form-row">
            <div class="form-group">
                <label>Available Tickets:</label>
                <input type="number" name="available_tickets" value="<?= htmlspecialchars($museum['available_tickets']) ?>" min="0" required>
            </div>
            <div class="form-group">
                <label>Price (৳):</label>
                <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($museum['price']) ?>" min="0" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Upload New Photo (optional):</label>
                <input type="file" name="photo" accept="image/*">
            </div>
            <div class="form-group">
                <label>Address:</label>
                <input type="text" name="address" value="<?= htmlspecialchars($museum['address']) ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Opening Hours:</label>
                <input type="text" name="opening_hours" value="<?= htmlspecialchars($museum['opening_hours']) ?>" required>
            </div>
            <div class="form-group">
                <label>Contact Info:</label>
                <input type="text" name="contact" value="<?= htmlspecialchars($museum['contact']) ?>" required>
            </div>
        </div>

        <input type="submit" value="Update Museum">
    </form>
    <?php endif; ?>
</div>
</body>
</html>
