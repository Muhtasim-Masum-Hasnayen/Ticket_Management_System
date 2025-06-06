<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

$type = 'park';
$error = '';
$success = '';

$districts = ["Bagerhat", "Bandarban", "Barguna", "Barisal", "Bhola", "Bogra", "Brahmanbaria", "Chandpur", "Chapai Nawabganj", "Chattogram", "Chuadanga", "Comilla", "Cox's Bazar", "Dhaka", "Dinajpur", "Faridpur", "Feni", "Gaibandha", "Gazipur", "Gopalganj", "Habiganj", "Jamalpur", "Jashore", "Jhalokati", "Jhenaidah", "Joypurhat", "Khagrachhari", "Khulna", "Kishoreganj", "Kurigram", "Kushtia", "Lakshmipur", "Lalmonirhat", "Madaripur", "Magura", "Manikganj", "Meherpur", "Moulvibazar", "Munshiganj", "Mymensingh", "Naogaon", "Narail", "Narayanganj", "Narsingdi", "Natore", "Netrokona", "Nilphamari", "Noakhali", "Pabna", "Panchagarh", "Patuakhali", "Pirojpur", "Rajbari", "Rajshahi", "Rangamati", "Rangpur", "Satkhira", "Shariatpur", "Sherpur", "Sirajganj", "Sunamganj", "Sylhet", "Tangail", "Thakurgaon"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $location = $_POST['location'] ?? '';

    $general_description = $_POST['general_description'] ?? '';
    $general_price = $_POST['general_price'] ?? 0;
    $general_available_ticket = $_POST['general_available_ticket'] ?? 0;

    $family_description = $_POST['family_description'] ?? '';
    $family_price = $_POST['family_price'] ?? 0;
    $family_available_ticket = $_POST['family_available_ticket'] ?? 0;

    $student_description = $_POST['student_description'] ?? '';
    $student_price = $_POST['student_price'] ?? 0;
    $student_available_ticket = $_POST['student_available_ticket'] ?? 0;

    $corporate_description = $_POST['corporate_description'] ?? '';
    $corporate_price = $_POST['corporate_price'] ?? 0;
    $corporate_available_ticket = $_POST['corporate_available_ticket'] ?? 0;

    $uploadDir = 'uploads/parks/';
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
            $newName = uniqid('park_') . '.' . $ext;
            $photoPath = $uploadDir . $newName;
            move_uploaded_file($photoTmp, $photoPath);
        } else {
            $error = "Invalid photo type.";
        }
    }

   if (!$error) {
       try {
           $stmt = $conn->prepare("INSERT INTO parks
               (name, description, location, photo,
                general_description, general_price, general_available_ticket,
                family_description, family_price, family_available_ticket,
                student_description, student_price, student_available_ticket,
                corporate_description, corporate_price, corporate_available_ticket)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

           $stmt->execute([
               $title, $description, $location, $photoPath,
               $general_description, $general_price, $general_available_ticket,
               $family_description, $family_price, $family_available_ticket,
               $student_description, $student_price, $student_available_ticket,
               $corporate_description, $corporate_price, $corporate_available_ticket
           ]);

           $success = "🎉 Park added successfully!";
       } catch (Exception $e) {
           $error = "❌ Error: " . $e->getMessage();
       }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Park</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #a1c4fd, #c2e9fb);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 30px 40px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            color: #ffffff;
            margin-bottom: 20px;
        }
        label {
            color: #fff;
            margin-top: 15px;
            display: block;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: none;
            border-radius: 8px;
            outline: none;
        }
        textarea { resize: vertical; }
        input[type="submit"] {
            background: #28a745;
            color: white;
            font-weight: bold;
            transition: background 0.3s;
            margin-top: 25px;
        }
        input[type="submit"]:hover {
            background: #218838;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 10px;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
        }
        .success, .error {
            margin-top: 10px;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
        }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
<div class="form-container">
    <a class="back-link" href="dashboard.php">← Back to Dashboard</a>
    <h1>Add Park</h1>

    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="title" required>

        <label>Description:</label>
        <textarea name="description" rows="4" placeholder="Enter details about the park..."></textarea>

        <label>Location:</label>
        <select name="location" required>
            <option value="" disabled selected>Select District</option>
            <?php foreach ($districts as $district): ?>
                <option value="<?= $district ?>"><?= $district ?></option>
            <?php endforeach; ?>
        </select>

        <label>Upload Photo:</label>
        <input type="file" name="photo" accept="image/*" required>

        <hr><h3 style="color: white;">🎫 General Package</h3>
        <label>Description:</label>
        <textarea name="general_description" rows="2"></textarea>
        <label>Price (BDT):</label>
        <input type="number" step="0.01" name="general_price" min="0">
        <label>Available Tickets:</label>
        <input type="number" name="general_available_ticket" min="0">

        <hr><h3 style="color: white;">👨‍👩‍👧‍👦 Family Package (4 members)</h3>
        <label>Description:</label>
        <textarea name="family_description" rows="2"></textarea>
        <label>Price (BDT):</label>
        <input type="number" step="0.01" name="family_price" min="0">
        <label>Available Tickets:</label>
        <input type="number" name="family_available_ticket" min="0">

        <hr><h3 style="color: white;">🎓 Student Package (10 students)</h3>
        <label>Description:</label>
        <textarea name="student_description" rows="2"></textarea>
        <label>Price (BDT):</label>
        <input type="number" step="0.01" name="student_price" min="0">
        <label>Available Tickets:</label>
        <input type="number" name="student_available_ticket" min="0">

        <hr><h3 style="color: white;">🏢 Corporate Package (10 persons)</h3>
        <label>Description:</label>
        <textarea name="corporate_description" rows="2"></textarea>
        <label>Price (BDT):</label>
        <input type="number" step="0.01" name="corporate_price" min="0">
        <label>Available Tickets:</label>
        <input type="number" name="corporate_available_ticket" min="0">

        <input type="submit" value="Add Park">
    </form>

</div>
</body>
</html>
