<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

$error = '';
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $duration = $_POST['duration'] ?? null;
    $language = $_POST['language'] ?? '';
    $genre = $_POST['genre'] ?? '';
    $cast = $_POST['cast'] ?? '';
    $director = $_POST['director'] ?? '';

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
        $stmt = $conn->prepare("INSERT INTO movies (title, description, duration_minutes, language, genre, cast, director, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $description, $duration, $language, $genre, $cast, $director, $photoPath]);
        $movieId = $conn->lastInsertId();

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
    <textarea name="description" rows="4" required></textarea>

    <label>Duration (minutes):</label>
    <input type="number" name="duration" min="1" required>

    <label>Language:</label>
    <input type="text" name="language" required>

    <label>Genre:</label>
    <select name="genre" required>
        <option value="" disabled selected>Select Genre</option>
        <option value="Action">Action</option>
        <option value="Adventure">Adventure</option>
        <option value="Animation">Animation</option>
        <option value="Comedy">Comedy</option>
        <option value="Crime">Crime</option>
        <option value="Drama">Drama</option>
        <option value="Fantasy">Fantasy</option>
        <option value="Horror">Horror</option>
        <option value="Mystery">Mystery</option>
        <option value="Romance">Romance</option>
        <option value="Sci-Fi">Sci-Fi</option>
        <option value="Thriller">Thriller</option>
        <option value="War">War</option>
        <option value="Western">Western</option>
    </select>

    <label>Cast:</label>
    <textarea name="cast" rows="3" placeholder="Separate names with commas" required></textarea>

    <label>Director:</label>
    <input type="text" name="director" required>

    <label>Upload Photo:</label>
    <input type="file" name="photo" accept="image/*" required>

    <input type="submit" value="Add Movie">
</form>


</body>
</html>
