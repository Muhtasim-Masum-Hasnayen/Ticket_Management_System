<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $location = $_POST['location'] ?? '';
    $available_tickets = $_POST['available_tickets'] ?? 0;
    $price = $_POST['price'] ?? 0;
    $address = $_POST['address'] ?? '';
    $opening_hours = $_POST['opening_hours'] ?? '';
    $contact = $_POST['contact'] ?? '';


    $uploadDir = 'uploads/museums/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $photoPath = null;
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
            $stmt = $pdo->prepare("INSERT INTO museums (name, description, location, available_tickets, price, photo, address, opening_hours, contact) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $location, $available_tickets, $price, $photoPath, $address, $opening_hours, $contact]);
            $success = "Museum added successfully!";
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
    <title>Add Museum</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }
        .container {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 30px 40px;
            width: 100%;
            max-width: 600px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
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
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }
        input[type="file"] {
            background: transparent;
        }
        textarea {
            resize: vertical;
        }
        input[type="submit"] {
            background-color: #00c9a7;
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
    <h1>🎨 Add Museum</h1>
    <a href="dashboard.php">← Back to Dashboard</a>

    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?= htmlspecialchars($success) ?></p><?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="title" required>

        <label>Description:</label>
        <textarea name="description" rows="4"></textarea>

        <label>Location:</label>
        <select name="location" required>
            <option value="" disabled selected>Select District</option>
            <?php
            $districts = ["Bagerhat","Bandarban","Barguna","Barisal","Bhola","Bogra","Brahmanbaria","Chandpur","Chapai Nawabganj","Chattogram","Chuadanga","Comilla","Cox's Bazar","Dhaka","Dinajpur","Faridpur","Feni","Gaibandha","Gazipur","Gopalganj","Habiganj","Jamalpur","Jashore","Jhalokati","Jhenaidah","Joypurhat","Khagrachhari","Khulna","Kishoreganj","Kurigram","Kushtia","Lakshmipur","Lalmonirhat","Madaripur","Magura","Manikganj","Meherpur","Moulvibazar","Munshiganj","Mymensingh","Naogaon","Narail","Narayanganj","Narsingdi","Natore","Netrokona","Nilphamari","Noakhali","Pabna","Panchagarh","Patuakhali","Pirojpur","Rajbari","Rajshahi","Rangamati","Rangpur","Satkhira","Shariatpur","Sherpur","Sirajganj","Sunamganj","Sylhet","Tangail","Thakurgaon"];
            foreach ($districts as $district) {
                echo "<option value=\"$district\">$district</option>";
            }
            ?>
        </select>

        <label>Available Tickets:</label>
        <input type="number" name="available_tickets" min="0" required>

        <label>Price (৳):</label>
        <input type="number" step="0.01" name="price" min="0" required>

        <label>Upload Photo:</label>
        <input type="file" name="photo" accept="image/*" required>

        <label>Address:</label>
        <input type="text" name="address" required>

        <label>Opening Hours:</label>
        <input type="text" name="opening_hours" placeholder="e.g. 10:00 AM - 5:00 PM" required>

        <label>Contact Info:</label>
        <input type="text" name="contact" placeholder="Phone number or email" required>


        <input type="submit" value="Add Museum">
    </form>
</div>
</body>
</html>
