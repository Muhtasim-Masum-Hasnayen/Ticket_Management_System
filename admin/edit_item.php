<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

$type = $_GET['type'] ?? '';
$id = intval($_GET['id'] ?? 0);
$allowed_types = ['movie', 'museum', 'park'];

if (!in_array($type, $allowed_types) || $id <= 0) {
    die('Invalid request.');
}

$error = '';
$success = '';

$table = '';
$id_col = '';

switch ($type) {
    case 'movie': $table = 'movies'; $id_col = 'movie_id'; break;
    case 'museum': $table = 'museums'; $id_col = 'museum_id'; break;
    case 'park': $table = 'parks'; $id_col = 'park_id'; break;
}

// Fetch item
$stmt = $pdo->prepare("SELECT * FROM $table WHERE $id_col = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    die(ucfirst($type) . " not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $duration = $_POST['duration'] ?? null;
    $location = $_POST['location'] ?? '';
    $available_tickets = $_POST['available_tickets'] ?? 0;
    $price = $_POST['price'] ?? 0;

    try {
        if ($type === 'movie') {
            $stmt = $pdo->prepare("UPDATE movies SET title=?, description=?, duration_minutes=?, available_tickets=?, price=? WHERE movie_id=?");
            $stmt->execute([$title, $description, $duration, $available_tickets, $price, $id]);
        } elseif ($type === 'museum') {
            $stmt = $pdo->prepare("UPDATE museums SET name=?, description=?, location=?, available_tickets=?, price=? WHERE museum_id=?");
            $stmt->execute([$title, $description, $location, $available_tickets, $price, $id]);
        } else { // park
            $stmt = $pdo->prepare("UPDATE parks SET name=?, description=?, location=?, available_tickets=?, price=? WHERE park_id=?");
            $stmt->execute([$title, $description, $location, $available_tickets, $price, $id]);
        }

        $success = ucfirst($type) . " updated successfully!";
        // Refresh item data
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE $id_col = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();

    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit <?= ucfirst($type) ?> - Admin</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        form { max-width: 500px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], textarea, input[type="number"] {
            width: 100%; padding: 8px; margin-top: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            margin-top: 15px; padding: 10px 20px; background: #007bff; border: none; color: white; cursor: pointer;
            border-radius: 4px;
        }
        .success { color: green; }
        .error { color: red; }
        a { text-decoration: none; color: #555; }
    </style>
</head>
<body>

<h1>Edit <?= ucfirst($type) ?></h1>
<a href="dashboard.php">Back to Dashboard</a>

<?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php elseif ($success): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST">
    <label><?= ($type === 'movie') ? 'Title' : 'Name' ?>:</label>
    <input type="text" name="title" required value="<?= htmlspecialchars($item[($type === 'movie') ? 'title' : 'name']) ?>">

    <label>Description:</label>
    <textarea name="description" rows="4"><?= htmlspecialchars($item['description']) ?></textarea>

    <?php if ($type === 'movie'): ?>
        <label>Duration (minutes):</label>
        <input type="number" name="duration" min="1" required value="<?= htmlspecialchars($item['duration_minutes']) ?>">
    <?php else: ?>
        <label>Location:</label>
        <input type="text" name="location" required value="<?= htmlspecialchars($item['location']) ?>">
    <?php endif; ?>

    <label>Available Tickets:</label>
    <input type="number" name="available_tickets" min="0" required value="<?= htmlspecialchars($item['available_tickets']) ?>">

    <label>Price:</label>
    <input type="number" step="0.01" name="price" min="0" required value="<?= htmlspecialchars($item['price']) ?>">

    <input type="submit" value="Update <?= ucfirst($type) ?>">
</form>

</body>
</html>
