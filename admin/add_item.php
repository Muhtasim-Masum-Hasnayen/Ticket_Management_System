<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

$type = $_GET['type'] ?? '';
$allowed_types = ['movie', 'museum', 'park'];

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

    try {
        if ($type === 'movie') {
            $stmt = $pdo->prepare("INSERT INTO movies (title, description, duration_minutes, available_tickets, price) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $duration, $available_tickets, $price]);
        } elseif ($type === 'museum') {
            $stmt = $pdo->prepare("INSERT INTO museums (name, description, location, available_tickets, price) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $location, $available_tickets, $price]);
        } else { // park
            $stmt = $pdo->prepare("INSERT INTO parks (name, description, location, available_tickets, price) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $description, $location, $available_tickets, $price]);
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
        input[type="text"], textarea, input[type="number"] {
            width: 100%; padding: 8px; margin-top: 4px;
            box-sizing: border-box;
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

<h1>Add <?= ucfirst($type) ?></h1>
<a href="dashboard.php">Back to Dashboard</a>

<?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php elseif ($success): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST">
    <label><?= ($type === 'movie') ? 'Title' : 'Name' ?>:</label>
    <input type="text" name="title" required>

    <label>Description:</label>
    <textarea name="description" rows="4"></textarea>

    <?php if ($type === 'movie'): ?>
        <label>Duration (minutes):</label>
        <input type="number" name="duration" min="1" required>
    <?php else: ?>
        <label>Location:</label>
        <input type="text" name="location" required>
    <?php endif; ?>

    <label>Available Tickets:</label>
    <input type="number" name="available_tickets" min="0" required>

    <label>Price:</label>
    <input type="number" step="0.01" name="price" min="0" required>

    <input type="submit" value="Add <?= ucfirst($type) ?>">
</form>

</body>
</html>
