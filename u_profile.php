<?php
session_start();
include 'db_connect.php';

$user_id = $_SESSION['id'];

// Language setup
if (!isset($_SESSION['lang'])) $_SESSION['lang'] = 'bn';
if (isset($_GET['lang']) && in_array($_GET['lang'], ['bn', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'];

$text = [
    'bn' => [
        'title' => ' প্রোফাইল',
        'name' => 'নাম',
        'phone' => 'ফোন নম্বর',
        'upload' => 'ছবি আপলোড করুন',
        'save' => 'সংরক্ষণ করুন',
        'dashboard' => 'ড্যাশবোর্ড',
        'profile' => 'প্রোফাইল',
        'market' => 'ক্রয় তালিকা',
        'messages' => 'বার্তা',
        'notifications' => 'নোটিফিকেশন',
        'settings' => 'সেটিংস',
        'logout' => 'লগ আউট',
    ],
    'en' => [
        'title' => ' Profile',
        'name' => 'Name',
        'phone' => 'Phone Number',
        'upload' => 'Upload Photo',
        'save' => 'Save',
        'dashboard' => 'Dashboard',
        'profile' => 'Profile',
        'market' => 'Market',
        'messages' => 'Messages',
        'notifications' => 'Notifications',
        'settings' => 'Settings',
        'logout' => 'Logout',
    ]
];
$current_text = $text[$lang];

$success = '';
$error = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $photo = '';

    if (!empty($_FILES["photo"]["name"])) {
        $target_dir = "uploads/";
        $photo = basename($_FILES["photo"]["name"]);
        $target_file = $target_dir . $photo;
        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);
    }

    if (!empty($photo)) {
        $sql = "UPDATE users SET name=?, phone=?, photo=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $phone, $photo, $user_id);
    } else {
        $sql = "UPDATE users SET name=?, phone=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $phone, $user_id);
    }

    if ($stmt->execute()) {
        $success = "<p class='success'>Profile saved successfully!</p>";
    } else {
        $error = "<p class='error'>Error: " . $stmt->error . "</p>";
    }
}

// Fetch user data
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$data = $result->fetch();
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $current_text['title'] ?></title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            background: #f0f4f8;
        }

        .sidebar {
            width: 70px;
            background: linear-gradient(180deg, #2E7D32, #1B5E20);
            color: white;
            height: 100vh;
            padding: 20px 10px;
            position: fixed;
            transition: width 0.3s;
            overflow: hidden;
        }

        .sidebar:hover { width: 250px; }
        .sidebar h2 { font-size: 24px; margin-bottom: 30px; white-space: nowrap; opacity: 0; transition: opacity 0.3s; }
        .sidebar:hover h2 { opacity: 1; }
        .sidebar a {
            display: flex;
            align-items: center;
            padding: 10px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 10px;
            white-space: nowrap;
            background-color: rgba(255,255,255,0.1);
            transition: background 0.2s;
        }

        .sidebar a:hover { background-color: rgba(255,255,255,0.3); }
        .sidebar a span { margin-left: 10px; display: none; transition: opacity 0.3s; }
        .sidebar:hover a span { display: inline; opacity: 1; }
        .logout-sidebar { color: #ffdddd; background-color: #c62828; }

        .main {
            margin-left: 270px;
            width: calc(100% - 270px);
            padding: 20px 40px;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logout-button {
            background-color: #d32f2f;
            border: none;
            padding: 10px 18px;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .logout-button:hover { background-color: #b71c1c; }

        .container {
            margin-top: 30px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        h2, h3 { color: #2E7D32; }
        .form-group { margin-bottom: 20px; }

        input[type="text"], input[type="file"] {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #388E3C;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        input[type="submit"]:hover { background-color: #2e7030; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }

        .profile-card {
            max-width: 800px;
            margin: 50px auto;
            padding: 50px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .profile-image {
            width: 180px;
            height: 180px;
            margin: 0 auto 30px;
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #4CAF50;
        }

        .profile-card h3 {
            font-size: 32px;
            margin-bottom: 20px;
            color: #2E7D32;
        }

        .profile-card p {
            font-size: 20px;
            margin: 12px 0;
            color: #333;
        }

    </style>
</head>
<body>

<div class="sidebar">
    <h2>SmartTicket</h2>
    <a href="dashboard.php">🏠 <span><?= $current_text['dashboard'] ?></span></a>
    <a href="u_profile.php">🧑 <span><?= $current_text['profile'] ?></span></a>
    <a href="C_market.php">🛒 <span><?= $current_text['market'] ?></span></a>
    <a href="messages.php">💬 <span><?= $current_text['messages'] ?></span></a>
    <a href="notifications.php">🔔 <span><?= $current_text['notifications'] ?></span></a>
    <a href="settings.php">⚙ <span><?= $current_text['settings'] ?></span></a>
    <a class="logout-sidebar" href="logout.php">🚪 <span><?= $current_text['logout'] ?></span></a>
</div>

<div class="main">
    <div class="top-bar">
        <h2><?= $current_text['title'] ?></h2>
        <div>
            <a href="?lang=bn" class="logout-button">বাংলা</a>
            <a href="?lang=en" class="logout-button">English</a>
            <a href="logout.php" class="logout-button"><?= $current_text['logout'] ?></a>
        </div>
    </div>

    <?= $success ?>
    <?= $error ?>

    <div class="container">
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label><?= $current_text['name'] ?>:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($data['name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label><?= $current_text['phone'] ?>:</label>
                <input type="text" name="phone" value="<?= htmlspecialchars($data['phone'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label><?= $current_text['upload'] ?>:</label>
                <input type="file" name="photo">
            </div>
            <input type="submit" value="<?= $current_text['save'] ?>">
        </form>
    </div>

    <?php if (!empty($data['photo'])): ?>
        <div class="profile-card">
            <div class="profile-image">
                <img src="uploads/<?= htmlspecialchars($data['photo']) ?>" alt="Profile Photo">
            </div>
            <h3><?= htmlspecialchars($data['name']) ?></h3>
            <p>📞 <?= htmlspecialchars($data['phone']) ?></p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
