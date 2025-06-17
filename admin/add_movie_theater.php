<?php
require_once '../db_connect.php';
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

$movies = $conn->query("SELECT * FROM movies")->fetchAll();
$theaters = $conn->query("SELECT * FROM theaters")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Showtimes</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 30px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
            padding: 0 20px;
        }

        .movie-card {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            text-align: center;
            transition: transform 0.2s ease-in-out;
        }

        .movie-card:hover {
            transform: scale(1.03);
        }

        .movie-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
        }

        .movie-title {
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            color: #444;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            background: crimson;
            color: #fff;
            border: none;
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 6px;
            cursor: pointer;
        }

        form label {
            display: block;
            margin-top: 14px;
            font-weight: 600;
            color: #333;
        }

        form input[type="number"],
        form input[type="datetime-local"],
        form select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .checkbox-group {
            margin-top: 8px;
            max-height: 150px;
            overflow-y: auto;
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #f9f9f9;
        }

        .checkbox-group label {
            display: block;
            margin: 5px 0;
            font-weight: normal;
        }

        #show-times input {
            margin-bottom: 10px;
        }

        .add-time-btn,
        button[type="submit"] {
            background: #007bff;
            color: white;
            padding: 12px 20px;
            margin-top: 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
        }

        .add-time-btn:hover,
        button[type="submit"]:hover {
            background: #0056b3;
        }
    </style>

    <script>
        function openModal(movieId, title) {
            document.getElementById('modal').style.display = 'flex';
            document.getElementById('selectedMovieId').value = movieId;
            document.getElementById('movieTitle').innerText = title;
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function addShowTimeField() {
            const container = document.getElementById('show-times');
            const input = document.createElement('input');
            input.type = 'datetime-local';
            input.name = 'show_times[]';
            input.required = true;
            container.appendChild(input);
        }
    </script>
</head>
<body>

    <h1>Assign Showtimes to Movies</h1>

    <div class="grid">
        <?php foreach ($movies as $movie): ?>
            <div class="movie-card" onclick="openModal('<?= $movie['movie_id'] ?>', '<?= htmlspecialchars($movie['title']) ?>')">
                <img src="<?= htmlspecialchars($movie['photo']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                <div class="movie-title"><?= htmlspecialchars($movie['title']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Modal -->
    <div class="modal" id="modal">
        <div class="modal-content">
            <button class="close-btn" onclick="closeModal()">X</button>
            <h2 id="movieTitle">Assign Showtimes</h2>
            <form method="POST" action="save_showtime.php">
                <input type="hidden" name="movie_id" id="selectedMovieId">

                <label>Ticket Price (৳):</label>
                <input type="number" name="price" min="0" step="0.01" required>

                <label>Select Theaters:</label>
                <div class="checkbox-group">
                    <?php foreach ($theaters as $theater): ?>
                        <label>
                            <input type="checkbox" name="theaters[]" value="<?= $theater['theater_id'] ?>">
                            <?= htmlspecialchars($theater['name']) ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <label>Show Times:</label>
                <div id="show-times">
                    <input type="datetime-local" name="show_times[]" required>
                </div>
                <button type="button" class="add-time-btn" onclick="addShowTimeField()">+ Add More Show Time</button>

                <button type="submit">Save Showtimes</button>
            </form>
        </div>
    </div>

</body>
</html>
