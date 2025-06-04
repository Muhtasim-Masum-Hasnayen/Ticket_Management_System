<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

$error = '';
$success = '';
$theaters = $pdo->query("SELECT theater_id, name, location FROM theaters")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $duration = $_POST['duration'] ?? null;
    $available_tickets = $_POST['available_tickets'] ?? 0;
    $price = $_POST['price'] ?? 0;
    $location = $_POST['location'] ?? '';
    $showTime = $_POST['show_time'] ?? null;

    $uploadDir = 'uploads/movies/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $photoPath = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoTmp = $_FILES['photo']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
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
        $stmt = $pdo->prepare("INSERT INTO movies (title, description, duration_minutes, available_tickets, price, photo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $duration, $available_tickets, $price, $photoPath]);
        $movieId = $pdo->lastInsertId();

        if (!empty($_POST['theaters']) && is_array($_POST['theaters'])) {
            $insertStmt = $pdo->prepare("INSERT INTO movie_theaters (movie_id, theater_id) VALUES (?, ?)");
            foreach ($_POST['theaters'] as $theaterId) {
                $insertStmt->execute([$movieId, $theaterId]);

                // Dummy seats
                $seats = ['A1', 'A2', 'A3', 'B1', 'B2'];
                foreach ($seats as $seat) {
                    $stmt = $pdo->prepare("INSERT INTO tickets (movie_id, theater_id, show_time, seat_number, price) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$movieId, $theaterId, $showTime, $seat, $price]);
                }
            }
        }

        $success = "Movie added successfully!";
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Movie - Admin</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        form { max-width: 500px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], textarea, input[type="number"], input[type="datetime-local"] {
            width: 100%; padding: 8px; margin-top: 4px; box-sizing: border-box;
        }
        input[type="submit"] {
            margin-top: 15px; padding: 10px 20px; background: #28a745; border: none; color: white;
            border-radius: 4px; cursor: pointer;
        }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>

<h1>Add Movie</h1>
<a href="dashboard.php">Back to Dashboard</a>

<?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php elseif ($success): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Title:</label>
    <input type="text" name="title" required>

    <label>Description:</label>
    <textarea name="description" rows="4"></textarea>

    <label>Duration (minutes):</label>
    <input type="number" name="duration" min="1" required>

    <label>Show Time:</label>
    <input type="datetime-local" name="show_time" required>

    <label>Location:</label>
    <select name="location" required>
        <option disabled selected>Select District</option>
        <option value="Dhaka">Dhaka</option>
        <option value="Chattogram">Chattogram</option>
        <option value="Sylhet">Sylhet</option>
        <!-- Add more as needed -->
    </select>

    <label>Available Tickets:</label>
    <input type="number" name="available_tickets" min="0" required>

    <label>Price:</label>
    <input type="number" name="price" step="0.01" min="0" required>

    <label>Select Theaters:</label>
    <?php foreach ($theaters as $theater): ?>
        <div>
            <label>
                <input type="checkbox" name="theaters[]" value="<?= $theater['theater_id'] ?>">
                <?= htmlspecialchars($theater['name']) ?> (<?= htmlspecialchars($theater['location']) ?>)
            </label>
        </div>
    <?php endforeach; ?>

    <label>Upload Photo:</label>
    <input type="file" name="photo" accept="image/*" required>

    <input type="submit" value="Add Movie">
</form>

</body>
</html>
