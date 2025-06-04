<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

$type = $_GET['type'] ?? '';
$allowed_types = [ 'park'];

if (!in_array($type, $allowed_types)) {
    die('Invalid type specified.');
}

$error = '';
$success = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $duration = $_POST['duration'] ?? null;
    $location = $_POST['location'] ?? '';
    $available_tickets = $_POST['available_tickets'] ?? 0;
    $price = $_POST['price'] ?? 0;


    // Handle photo upload
    $uploadDir = 'uploads/' . $type . 's/';
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
            $newName = uniqid('photo_') . '.' . $ext;
            $photoPath = $uploadDir . $newName;
            move_uploaded_file($photoTmp, $photoPath);
        } else {
            $error = "Invalid photo type.";
        }
    }

    try {
         if ($type === 'museum') {
            $stmt = $pdo->prepare("INSERT INTO museums (name, description, location, available_tickets, price, photo) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $location, $available_tickets, $price, $photoPath]);

        } else { // park
            $stmt = $pdo->prepare("INSERT INTO parks (name, description, location, available_tickets, price, photo) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $location, $available_tickets, $price, $photoPath]);
        }

        $success = ucfirst($type) . " added successfully!";
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add <?= ucfirst($type) ?> - Admin</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        form { max-width: 500px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], textarea, input[type="number"], input[type="datetime-local"] {
            width: 100%; padding: 8px; margin-top: 4px; box-sizing: border-box;
        }
        input[type="submit"] {
            margin-top: 15px; padding: 10px 20px; background: #28a745; border: none; color: white; cursor: pointer;
            border-radius: 4px;
        }
        .success { color: green; }
        .error { color: red; }
        a { text-decoration: none; color: #555; }
    </style>
</head>
<body>

<<h1>Add <?= ucfirst($type) ?></h1>
 <a href="dashboard.php">Back to Dashboard</a>

 <?php if ($error): ?>
     <p class="error"><?= htmlspecialchars($error) ?></p>
 <?php elseif ($success): ?>
     <p class="success"><?= htmlspecialchars($success) ?></p>
 <?php endif; ?>

 <form method="POST" enctype="multipart/form-data">
     <label>Name:</label>
     <input type="text" name="title" required>

     <label>Description:</label>
     <textarea name="description" rows="4"></textarea>

    <label for="location">Location:</label>
    <select name="location" id="location" required>
        <option value="" disabled selected>Select District</option>
        <option value="Bagerhat">Bagerhat</option>
        <option value="Bandarban">Bandarban</option>
        <option value="Barguna">Barguna</option>
        <option value="Barisal">Barisal</option>
        <option value="Bhola">Bhola</option>
        <option value="Bogra">Bogra</option>
        <option value="Brahmanbaria">Brahmanbaria</option>
        <option value="Chandpur">Chandpur</option>
        <option value="Chapai Nawabganj">Chapai Nawabganj</option>
        <option value="Chattogram">Chattogram</option>
        <option value="Chuadanga">Chuadanga</option>
        <option value="Comilla">Comilla</option>
        <option value="Cox's Bazar">Cox's Bazar</option>
        <option value="Dhaka">Dhaka</option>
        <option value="Dinajpur">Dinajpur</option>
        <option value="Faridpur">Faridpur</option>
        <option value="Feni">Feni</option>
        <option value="Gaibandha">Gaibandha</option>
        <option value="Gazipur">Gazipur</option>
        <option value="Gopalganj">Gopalganj</option>
        <option value="Habiganj">Habiganj</option>
        <option value="Jamalpur">Jamalpur</option>
        <option value="Jashore">Jashore</option>
        <option value="Jhalokati">Jhalokati</option>
        <option value="Jhenaidah">Jhenaidah</option>
        <option value="Joypurhat">Joypurhat</option>
        <option value="Khagrachhari">Khagrachhari</option>
        <option value="Khulna">Khulna</option>
        <option value="Kishoreganj">Kishoreganj</option>
        <option value="Kurigram">Kurigram</option>
        <option value="Kushtia">Kushtia</option>
        <option value="Lakshmipur">Lakshmipur</option>
        <option value="Lalmonirhat">Lalmonirhat</option>
        <option value="Madaripur">Madaripur</option>
        <option value="Magura">Magura</option>
        <option value="Manikganj">Manikganj</option>
        <option value="Meherpur">Meherpur</option>
        <option value="Moulvibazar">Moulvibazar</option>
        <option value="Munshiganj">Munshiganj</option>
        <option value="Mymensingh">Mymensingh</option>
        <option value="Naogaon">Naogaon</option>
        <option value="Narail">Narail</option>
        <option value="Narayanganj">Narayanganj</option>
        <option value="Narsingdi">Narsingdi</option>
        <option value="Natore">Natore</option>
        <option value="Netrokona">Netrokona</option>
        <option value="Nilphamari">Nilphamari</option>
        <option value="Noakhali">Noakhali</option>
        <option value="Pabna">Pabna</option>
        <option value="Panchagarh">Panchagarh</option>
        <option value="Patuakhali">Patuakhali</option>
        <option value="Pirojpur">Pirojpur</option>
        <option value="Rajbari">Rajbari</option>
        <option value="Rajshahi">Rajshahi</option>
        <option value="Rangamati">Rangamati</option>
        <option value="Rangpur">Rangpur</option>
        <option value="Satkhira">Satkhira</option>
        <option value="Shariatpur">Shariatpur</option>
        <option value="Sherpur">Sherpur</option>
        <option value="Sirajganj">Sirajganj</option>
        <option value="Sunamganj">Sunamganj</option>
        <option value="Sylhet">Sylhet</option>
        <option value="Tangail">Tangail</option>
        <option value="Thakurgaon">Thakurgaon</option>
    </select>


    <label>Available Tickets:</label>
    <input type="number" name="available_tickets" min="0" required>

    <label>Price:</label>
    <input type="number" step="0.01" name="price" min="0" required>

    <label>Upload Photo:</label>
    <input type="file" name="photo" accept="image/*" required>

    <input type="submit" value="Add <?= ucfirst($type) ?>">
</form>

</body>
</html>
