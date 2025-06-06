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
<html>
<head>
    <title>Assign Showtimes</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; margin: 0; padding: 20px; }
        h1 { text-align: center; }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .movie-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            cursor: pointer;
            text-align: center;
            transition: transform 0.2s;
        }

        .movie-card:hover {
            transform: scale(1.02);
        }

        .movie-card img {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
        }

        .movie-title {
            padding: 10px;
            font-weight: bold;
            font-size: 1rem;
            color: #333;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 600px;
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 10px; right: 10px;
            background: red; color: white;
            border: none; padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        form label, form select, form input, .checkbox-group {
            display: block;
            width: 100%;
            margin-top: 10px;
        }

        .checkbox-group label {
            margin: 5px 0;
        }

        .add-time-btn, button[type="submit"] {
            margin-top: 15px;
            background: #007BFF;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .add-time-btn:hover, button[type="submit"]:hover {
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
    <h1>Select a Movie to Assign Showtimes</h1>

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
            <h2 id="movieTitle">Assign Showtime</h2>
            <form method="POST" action="save_showtime.php">
                <input type="hidden" name="movie_id" id="selectedMovieId">

                <label>Ticket Price:</label>
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
                <button type="button" class="add-time-btn" onclick="addShowTimeField()">+ Add Show Time</button>

                <button type="submit">Save Showtimes</button>
            </form>
        </div>
    </div>
</body>
</html>
