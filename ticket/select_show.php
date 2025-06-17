<?php
require_once '../db_connect.php';

$movie_id = $_GET['movie_id'] ?? null;
$theater_id = $_GET['theater_id'] ?? null;

if (!$movie_id || !$theater_id) {
    echo "Movie or Theater not selected.";
    exit;
}

// Fetch movie & theater info
$movie = $conn->prepare("SELECT title, photo FROM movies WHERE movie_id = ?");
$movie->execute([$movie_id]);
$movie = $movie->fetch(PDO::FETCH_ASSOC);

$theater = $conn->prepare("SELECT name, address FROM theaters WHERE theater_id = ?");
$theater->execute([$theater_id]);
$theater = $theater->fetch(PDO::FETCH_ASSOC);

// Fetch showtimes
$stmt = $conn->prepare("SELECT showtime_id, show_time FROM movie_showtimes WHERE movie_id = ? AND theater_id = ? ORDER BY show_time");
$stmt->execute([$movie_id, $theater_id]);
$showtimes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Select Show Time - SmartTicket</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #6c5ce7, #ff6b6b);
      min-height: 100vh;
      padding: 40px 20px;
      margin: 0;
    }

    .container {
      background: white;
      border-radius: 20px;
      padding: 30px;
      max-width: 900px;
      margin: auto;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    .section-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .section-header h2 {
      color: #6c5ce7;
      font-weight: 700;
    }

    .movie-info {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 30px;
    }

    .movie-info img {
      width: 120px;
      height: 180px;
      object-fit: cover;
      border-radius: 10px;
    }

    .movie-details {
      flex: 1;
    }

    .showtime-box {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      justify-content: center;
    }

    .showtime-btn {
      background: linear-gradient(to right, #ff6b6b, #6c5ce7);
      color: white;
      font-weight: bold;
      border-radius: 25px;
      padding: 10px 20px;
      text-decoration: none;
      border: none;
      transition: 0.3s;
    }

    .showtime-btn:hover {
      background: linear-gradient(to right, #6c5ce7, #ff6b6b);
      color: white;
    }

    @media (max-width: 576px) {
      .movie-info {
        flex-direction: column;
        text-align: center;
      }

      .movie-info img {
        width: 100px;
        height: 150px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="section-header">
      <h2>Select Show Time</h2>
    </div>

    <div class="movie-info">
      <img src="../admin/<?= htmlspecialchars($movie['photo']) ?>" alt="Poster">
      <div class="movie-details">
        <h4><?= htmlspecialchars($movie['title']) ?></h4>
        <p><strong>Theater:</strong> <?= htmlspecialchars($theater['name']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($theater['address']) ?></p>
      </div>
    </div>

    <div class="showtime-box">
      <?php if ($showtimes): ?>
        <?php foreach ($showtimes as $show): ?>
          <a href="seat_booking.php?showtime_id=<?= $show['showtime_id'] ?>" class="showtime-btn">
            <?= date('g:i A, D M j', strtotime($show['show_time'])) ?>
          </a>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-danger">No showtimes available.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
