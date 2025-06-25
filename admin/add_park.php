<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

$type     = 'park';
$error    = '';
$success  = '';

$districts = [
    "Bagerhat","Bandarban","Barguna","Barisal","Bhola","Bogra","Brahmanbaria","Chandpur",
    "Chapai Nawabganj","Chattogram","Chuadanga","Comilla","Cox's Bazar","Dhaka","Dinajpur",
    "Faridpur","Feni","Gaibandha","Gazipur","Gopalganj","Habiganj","Jamalpur","Jashore",
    "Jhalokati","Jhenaidah","Joypurhat","Khagrachhari","Khulna","Kishoreganj","Kurigram",
    "Kushtia","Lakshmipur","Lalmonirhat","Madaripur","Magura","Manikganj","Meherpur",
    "Moulvibazar","Munshiganj","Mymensingh","Naogaon","Narail","Narayanganj","Narsingdi",
    "Natore","Netrokona","Nilphamari","Noakhali","Pabna","Panchagarh","Patuakhali",
    "Pirojpur","Rajbari","Rajshahi","Rangamati","Rangpur","Satkhira","Shariatpur",
    "Sherpur","Sirajganj","Sunamganj","Sylhet","Tangail","Thakurgaon"
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ----------  Grab & sanitise inputs ---------- */
    $title       = trim($_POST['title']             ?? '');
    $description = trim($_POST['description']       ?? '');
    $location    = trim($_POST['location']          ?? '');

    $general_description   = trim($_POST['general_description']    ?? '');
    $general_price         = floatval($_POST['general_price']      ?? 0);
    $general_available     = intval($_POST['general_available_ticket'] ?? 0);

    $family_description    = trim($_POST['family_description']     ?? '');
    $family_price          = floatval($_POST['family_price']       ?? 0);
    $family_available      = intval($_POST['family_available_ticket'] ?? 0);

    $student_description   = trim($_POST['student_description']    ?? '');
    $student_price         = floatval($_POST['student_price']      ?? 0);
    $student_available     = intval($_POST['student_available_ticket'] ?? 0);

    $corporate_description = trim($_POST['corporate_description']  ?? '');
    $corporate_price       = floatval($_POST['corporate_price']    ?? 0);
    $corporate_available   = intval($_POST['corporate_available_ticket'] ?? 0);

    /* ----------  File-upload handling ---------- */
    $photoPath = null;

    if (!empty($_FILES['photo']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $fileType     = mime_content_type($_FILES['photo']['tmp_name']);

        if (in_array($fileType, $allowedTypes, true)) {
            $uploadDir = __DIR__ . '/uploads/parks/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $safeName  = uniqid('park_', true) . '.' . $extension;
            $photoPath = 'uploads/parks/' . $safeName;

            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $safeName)) {
                $error = '❌ Failed to upload the photo.';
            }
        } else {
            $error = '❌ Invalid photo type. Allowed: JPG, PNG, GIF, WEBP.';
        }
    }

    /* ----------  Insert into database ---------- */
    if (!$error) {
        try {
            $sql = "INSERT INTO parks
                       (name, description, location, photo,
                        general_description, general_price, general_available_ticket,
                        family_description, family_price, family_available_ticket,
                        student_description, student_price, student_available_ticket,
                        corporate_description, corporate_price, corporate_available_ticket)
                    VALUES
                       (:name, :description, :location, :photo,
                        :g_desc, :g_price, :g_avail,
                        :f_desc, :f_price, :f_avail,
                        :s_desc, :s_price, :s_avail,
                        :c_desc, :c_price, :c_avail)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':name'     => $title,
                ':description' => $description,
                ':location' => $location,
                ':photo'    => $photoPath,

                ':g_desc'   => $general_description,
                ':g_price'  => $general_price,
                ':g_avail'  => $general_available,

                ':f_desc'   => $family_description,
                ':f_price'  => $family_price,
                ':f_avail'  => $family_available,

                ':s_desc'   => $student_description,
                ':s_price'  => $student_price,
                ':s_avail'  => $student_available,

                ':c_desc'   => $corporate_description,
                ':c_price'  => $corporate_price,
                ':c_avail'  => $corporate_available
            ]);

            $success = '🎉 Park added successfully!';
        } catch (PDOException $e) {
            $error = '❌ DB Error: ' . $e->getMessage();
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
            background: linear-gradient(135deg, #ffffff, #ffcce5);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .form-container {
            background: linear-gradient(135deg, #ff4c60, #4ecdc4);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 30px 40px;
            width: 100%;
            max-width: 1500px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }
        .form-row {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
          gap: 20px;
          margin-bottom: 30px;
        }

        .form-group {
          display: flex;
          flex-direction: column;
        }

        .full-width {
          grid-column: 1 / -1; /* makes this input span full width of the row */
        }

        .package-group {
          background: rgba(255 255 255 / 0.15);
          padding: 15px;
          border-radius: 10px;
          color: white;
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
            cursor: pointer;
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

      <!-- 1st Row: Basic Info -->
      <div class="form-row">
        <div class="form-group full-width">
          <label>Name:</label>
          <input type="text" name="title" required>
        </div>

        <div class="form-group full-width">
          <label>Description:</label>
          <textarea name="description" rows="4" placeholder="Enter details about the park..."></textarea>
        </div>

        <div class="form-group">
          <label>Location:</label>
          <select name="location" required>
            <option value="" disabled selected>Select District</option>
            <?php foreach ($districts as $district): ?>
              <option value="<?= htmlspecialchars($district) ?>"><?= htmlspecialchars($district) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label>Upload Photo:</label>
          <input type="file" name="photo" accept="image/*" required>
        </div>
      </div>

      <!-- 2nd Row: General + Family Packages -->
      <div class="form-row">

        <div class="package-group">
          <hr>
          <h3 style="color: white;">🎫 General Package</h3>
          <label>Description:</label>
          <textarea name="general_description" rows="2"></textarea>
          <label>Price (BDT):</label>
          <input type="number" step="0.01" name="general_price" min="0">
          <label>Available Tickets:</label>
          <input type="number" name="general_available_ticket" min="0">
        </div>

        <div class="package-group">
          <hr>
          <h3 style="color: white;">👨‍👩‍👧‍👦 Family Package (4 members)</h3>
          <label>Description:</label>
          <textarea name="family_description" rows="2"></textarea>
          <label>Price (BDT):</label>
          <input type="number" step="0.01" name="family_price" min="0">
          <label>Available Tickets:</label>
          <input type="number" name="family_available_ticket" min="0">
        </div>
        <div class="package-group">
                  <hr>
                  <h3 style="color: white;">🎓 Student Package (10 students)</h3>
                  <label>Description:</label>
                  <textarea name="student_description" rows="2"></textarea>
                  <label>Price (BDT):</label>
                  <input type="number" step="0.01" name="student_price" min="0">
                  <label>Available Tickets:</label>
                  <input type="number" name="student_available_ticket" min="0">
                </div>

                <div class="package-group">
                  <hr>
                  <h3 style="color: white;">🏢 Corporate Package (10 persons)</h3>
                  <label>Description:</label>
                  <textarea name="corporate_description" rows="2"></textarea>
                  <label>Price (BDT):</label>
                  <input type="number" step="0.01" name="corporate_price" min="0">
                  <label>Available Tickets:</label>
                  <input type="number" name="corporate_available_ticket" min="0">
                </div>
      </div>



      <input type="submit" value="Add Park" style="margin-top: 25px;">
    </form>

</div>
</body>
</html>
