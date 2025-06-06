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
    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
            padding: 20px;
        }
        form {
            background: #fff;
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-top: 12px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        input[type="file"] {
            margin-top: 8px;
        }
        input[type="submit"] {
            margin-top: 20px;
            background: #007bff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Add New Theater</h2>

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

</body>
</html>
