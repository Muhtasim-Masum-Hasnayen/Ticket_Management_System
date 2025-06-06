<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    die('Invalid museum ID.');
}

$error = '';
$success = '';

// Fetch museum data
$stmt = $conn->prepare("SELECT * FROM museums WHERE museum_id = ?");
$stmt->execute([$id]);
$museum = $stmt->fetch();

if (!$museum) {
    die("Museum not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $location = $_POST['location'] ?? '';
    $available_tickets = $_POST['available_tickets'] ?? 0;
    $price = $_POST['price'] ?? 0;
    $address = $_POST['address'] ?? '';
    $opening_hours = $_POST['opening_hours'] ?? '';
    $contact = $_POST['contact'] ?? '';

    try {
        $stmt = $conn->prepare("UPDATE museums SET name=?, description=?, location=?, available_tickets=?, price=?, address=?, opening_hours=?, contact=? WHERE museum_id=?");
        $stmt->execute([$name, $description, $location, $available_tickets, $price, $address, $opening_hours, $contact, $id]);

        $success = "Museum updated successfully!";

        // Refresh data
        $stmt = $conn->prepare("SELECT * FROM museums WHERE museum_id = ?");
        $stmt->execute([$id]);
        $museum = $stmt->fetch();
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Museum - Admin</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        form { max-width: 600px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input[type="text"], textarea, input[type="number"] {
            width: 100%; padding: 8px; margin-top: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            margin-top: 15px; padding: 10px 20px;
            background: #007bff; border: none; color: white;
            cursor: pointer; border-radius: 4px;
        }
        .success { color: green; }
        .error { color: red; }
        a { text-decoration: none; color: #555; display: inline-block; margin-top: 15px; }
    </style>
</head>
<body>

<h1>Edit Museum</h1>
<a href="dashboard.php">← Back to Dashboard</a>

<?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php elseif ($success): ?>
    <p class="success"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<form method="POST">
    <label>Name:</label>
    <input type="text" name="name" required value="<?= htmlspecialchars($museum['name']) ?>">

    <label>Description:</label>
    <textarea name="description" rows="4"><?= htmlspecialchars($museum['description']) ?></textarea>

    <label>Location:</label>
    <input type="text" name="location" required value="<?= htmlspecialchars($museum['location']) ?>">

    <label>Available Tickets:</label>
    <input type="number" name="available_tickets" min="0" required value="<?= htmlspecialchars($museum['available_tickets']) ?>">

    <label>Price (BDT):</label>
    <input type="number" step="0.01" name="price" min="0" required value="<?= htmlspecialchars($museum['price']) ?>">

    <label>Address:</label>
    <input type="text" name="address" required value="<?= htmlspecialchars($museum['address']) ?>">

    <label>Opening Hours:</label>
    <input type="text" name="opening_hours" required value="<?= htmlspecialchars($museum['opening_hours']) ?>">

    <label>Contact Info:</label>
    <input type="text" name="contact" required value="<?= htmlspecialchars($museum['contact']) ?>">

    <input type="submit" value="Update Museum">
</form>

</body>
</html>
