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
$stmt = $conn->prepare("SELECT * FROM $table WHERE $id_col = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();

if (!$item) {
    die(ucfirst($type) . " not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $duration = $_POST['duration'] ?? null;
    $language = $_POST['language'] ?? '';
    $genre = $_POST['genre'] ?? '';
    $cast = $_POST['cast'] ?? '';
    $director = $_POST['director'] ?? '';
    $available_tickets = $_POST['available_tickets'] ?? 0;
    $price = $_POST['price'] ?? 0;
    $photo = $_FILES['photo']['name'] ?? '';

    if ($photo) {
        $uploadDir = '../uploads/';
        $targetFile = $uploadDir . basename($photo);
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
            $error = "Photo upload failed.";
        } else {
            $photo = basename($photo);
        }
    } else {
        $photo = $item['photo'] ?? '';
    }

    try {
        if ($type === 'movie') {
            $stmt = $conn->prepare("UPDATE movies SET title=?, description=?, duration_minutes=?, language=?, genre=?, cast=?, director=?, photo=? WHERE movie_id=?");
            $stmt->execute([$title, $description, $duration, $language, $genre, $cast, $director,  $photo, $id]);
        } else {
            $stmt = $conn->prepare("UPDATE $table SET name=?, description=?, location=?, available_tickets=?, price=?, photo=? WHERE $id_col=?");
            $stmt->execute([$title, $description, $_POST['location'], $available_tickets, $price, $photo, $id]);
        }

        $success = ucfirst($type) . " updated successfully!";
        $stmt = $conn->prepare("SELECT * FROM $table WHERE $id_col = ?");
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
        form { max-width: 600px; }
        label { display: block; margin-top: 10px; }
        input, textarea, select {
            width: 100%; padding: 8px; margin-top: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            margin-top: 15px; background: #007bff; border: none; color: white; cursor: pointer;
            border-radius: 4px; padding: 10px 20px;
        }
        .success { color: green; }
        .error { color: red; }
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

<form method="POST" enctype="multipart/form-data">
    <label><?= ($type === 'movie') ? 'Title' : 'Name' ?>:</label>
    <input type="text" name="title" required value="<?= htmlspecialchars($item[($type === 'movie') ? 'title' : 'name']) ?>">

    <label>Description:</label>
    <textarea name="description" rows="4" required><?= htmlspecialchars($item['description']) ?></textarea>

    <?php if ($type === 'movie'): ?>
        <label>Duration (minutes):</label>
        <input type="number" name="duration" min="1" required value="<?= htmlspecialchars($item['duration_minutes']) ?>">

        <label>Language:</label>
        <input type="text" name="language" required value="<?= htmlspecialchars($item['language']) ?>">

        <label>Genre:</label>
        <select name="genre" required>
            <?php
            $genres = ["Action", "Adventure", "Animation", "Comedy", "Crime", "Drama", "Fantasy", "Horror", "Mystery", "Romance", "Sci-Fi", "Thriller", "War", "Western"];
            foreach ($genres as $genre) {
                echo '<option value="' . $genre . '"' . ($item['genre'] == $genre ? ' selected' : '') . '>' . $genre . '</option>';
            }
            ?>
        </select>

        <label>Cast:</label>
        <textarea name="cast" rows="3" required><?= htmlspecialchars($item['cast']) ?></textarea>

        <label>Director:</label>
        <input type="text" name="director" required value="<?= htmlspecialchars($item['director']) ?>">
    <?php else: ?>
        <label>Location:</label>
        <input type="text" name="location" required value="<?= htmlspecialchars($item['location']) ?>">
    <?php endif; ?>



    <label>Upload Photo:</label>
    <input type="file" name="photo" accept="image/*">
    <small>Current: <?= htmlspecialchars($item['photo'] ?? 'N/A') ?></small>

    <input type="submit" value="Update <?= ucfirst($type) ?>">
</form>

</body>
</html>
