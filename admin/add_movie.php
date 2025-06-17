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
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #ffffff, #ffcce5);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }
        .container {
            background: linear-gradient(135deg, #ff4c60, #4ecdc4);
            border-radius: 16px;
            padding: 30px 40px;
            width: 100%;
            max-width: 600px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
        }
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
        }
        a {
            display: inline-block;
            color: #f1f1f1;
            text-decoration: none;
            margin-bottom: 20px;
        }
        label {
            margin-top: 12px;
            display: block;
            font-weight: 500;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px 14px;
            margin-top: 6px;
            border: none;
            border-radius: 8px;
            background: #ffffff;
            color: #000;
        }
        input[type="file"] {
            background: #ffffff;
            color: #000;
        }
        select[name="location"] {
            color: #000;
        }
        select[name="location"] option {
            color: black;
        }
        textarea {
            resize: vertical;
        }
        input[type="submit"] {
            background-color: #218838;
            margin-top: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: bold;
            color: #fff;
        }
        input[type="submit"]:hover {
            background-color: #00b092;
        }
        .success {
            color: #a2ffbf;
            margin-bottom: 15px;
        }
        .error {
            color: #ff8f8f;
            margin-bottom: 15px;
        }
        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }
        }
    </style>

</head>
<body>

<div class="container">
    <h1>Add Movie</h1>
    <a href="dashboard.php">Back to Dashboard</a>

    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Title:</label>
            <input type="text" name="title" required>
        </div>

        <div class="form-group">
            <label>Description:</label>
            <textarea name="description" rows="4" required></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Duration (minutes):</label>
                <input type="number" name="duration" min="1" required>
            </div>
            <div class="form-group">
                <label>Language:</label>
                <input type="text" name="language" required>
            </div>
        </div>

        <div class="form-group">
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
        </div>

        <div class="form-group">
            <label>Cast:</label>
            <textarea name="cast" rows="3" placeholder="Separate names with commas" required></textarea>
        </div>

        <div class="form-group">
            <label>Director:</label>
            <input type="text" name="director" required>
        </div>

        <div class="form-group">
            <label>Upload Photo:</label>
            <input type="file" name="photo" accept="image/*" required>
        </div>

        <input type="submit" value="Add Movie">
    </form>
</div>


</body>
</html>
