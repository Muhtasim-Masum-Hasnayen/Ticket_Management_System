<?php
session_start();
require_once '../db_connect.php';

// Check admin login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch all items
$movies = $pdo->query("SELECT * FROM movies ORDER BY created_at DESC")->fetchAll();
$museums = $pdo->query("SELECT * FROM museums ORDER BY created_at DESC")->fetchAll();
$parks = $pdo->query("SELECT * FROM parks ORDER BY created_at DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - SmartTicket</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #eee; }
        a.button { padding: 6px 12px; background: #28a745; color: white; text-decoration: none; border-radius: 4px; }
        a.button:hover { background: #218838; }
        h2 { margin-top: 40px; }
        .logout { float: right; margin-top: -40px; }
    </style>
</head>
<body>

<h1>Admin Dashboard</h1>
<a href="logout.php" class="logout">Logout</a>

<h2>Movies <a href="add_item.php?type=movie" class="button">Add Movie</a></h2>
<table>
    <tr>
        <th>ID</th><th>Title</th><th>Duration (min)</th><th>Available Tickets</th><th>Price</th><th>Actions</th>
    </tr>
    <?php foreach ($movies as $movie): ?>
    <tr>
        <td><?= htmlspecialchars($movie['movie_id']) ?></td>
        <td><?= htmlspecialchars($movie['title']) ?></td>
        <td><?= htmlspecialchars($movie['duration_minutes']) ?></td>
        <td><?= htmlspecialchars($movie['available_tickets']) ?></td>
        <td><?= htmlspecialchars($movie['price']) ?></td>
        <td>
            <a href="edit_item.php?type=movie&id=<?= $movie['movie_id'] ?>">Edit</a> |
            <a href="delete_item.php?type=movie&id=<?= $movie['movie_id'] ?>" onclick="return confirm('Delete this movie?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<h2>Museums <a href="add_item.php?type=museum" class="button">Add Museum</a></h2>
<table>
    <tr>
        <th>ID</th><th>Name</th><th>Location</th><th>Available Tickets</th><th>Price</th><th>Actions</th>
    </tr>
    <?php foreach ($museums as $museum): ?>
    <tr>
        <td><?= htmlspecialchars($museum['museum_id']) ?></td>
        <td><?= htmlspecialchars($museum['name']) ?></td>
        <td><?= htmlspecialchars($museum['location']) ?></td>
        <td><?= htmlspecialchars($museum['available_tickets']) ?></td>
        <td><?= htmlspecialchars($museum['price']) ?></td>
        <td>
            <a href="edit_item.php?type=museum&id=<?= $museum['museum_id'] ?>">Edit</a> |
            <a href="delete_item.php?type=museum&id=<?= $museum['museum_id'] ?>" onclick="return confirm('Delete this museum?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<h2>Parks <a href="add_item.php?type=park" class="button">Add Park</a></h2>
<table>
    <tr>
        <th>ID</th><th>Name</th><th>Location</th><th>Available Tickets</th><th>Price</th><th>Actions</th>
    </tr>
    <?php foreach ($parks as $park): ?>
    <tr>
        <td><?= htmlspecialchars($park['park_id']) ?></td>
        <td><?= htmlspecialchars($park['name']) ?></td>
        <td><?= htmlspecialchars($park['location']) ?></td>
        <td><?= htmlspecialchars($park['available_tickets']) ?></td>
        <td><?= htmlspecialchars($park['price']) ?></td>
        <td>
            <a href="edit_item.php?type=park&id=<?= $park['park_id'] ?>">Edit</a> |
            <a href="delete_item.php?type=park&id=<?= $park['park_id'] ?>" onclick="return confirm('Delete this park?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
