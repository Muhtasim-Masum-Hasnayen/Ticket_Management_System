<?php
require_once '../db_connect.php';

$movie_id = $_GET['movie_id'] ?? null;
if (!$movie_id) {
    echo "Movie not selected.";
    exit;
}

// Get theaters showing the movie with photo, location, and showtime count
$sql = "SELECT t.theater_id, t.name, t.address, t.location, t.photo,
               COUNT(s.showtime_id) AS show_count
        FROM theaters t
        JOIN movie_showtimes s ON t.theater_id = s.theater_id
        WHERE s.movie_id = ?
        GROUP BY t.theater_id";
$stmt = $conn->prepare($sql);
$stmt->execute([$movie_id]);
$theaters = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get movie title
$stmt = $conn->prepare("SELECT title FROM movies WHERE movie_id = ?");
$stmt->execute([$movie_id]);
$movie = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Movies - SmartTicket</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #ff6b6b, #6c5ce7);
      min-height: 100vh;
      padding: 40px 20px;
      color: #333;
      margin: 0;
    }

    .container {
      background: white;
      border-radius: 20px;
      padding: 30px;
      max-width: 1200px;
      margin: auto;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .section-title {
      font-weight: 700;
      margin-bottom: 30px;
      border-bottom: 2px solid #6c5ce7;
      padding-bottom: 5px;
      color: #6c5ce7;
      text-align: center;
    }

    .card {
      border-radius: 15px !important;
      overflow: hidden;
      transition: transform 0.3s ease;
      background: #fff;
      border: none;
      box-shadow: 0 8px 20px rgba(108, 92, 231, 0.15);
    }
    .card:hover {
      transform: translateY(-6px);
    }
    .card-title {
      font-weight: 600;
      color: #6c5ce7;
    }
    .card-text {
      font-size: 0.95rem;
      color: #555;
    }

    .btn-gradient {
      background: linear-gradient(to right, #ff6b6b, #6c5ce7);
      color: white !important;
      border-radius: 30px;
      padding: 10px;
      font-weight: bold;
      border: none;
      transition: 0.3s ease;
      display: block;
      text-align: center;
      text-decoration: none;
    }
    .btn-gradient:hover {
      background: linear-gradient(to right, #6c5ce7, #ff6b6b);
      color: white !important;
    }

    .row.g-4 > [class*='col-'] {
      margin-bottom: 30px;
    }

    @media (max-width: 768px) {
      .card-body {
        text-align: center;
      }
    }
  </style>
</head>
<body>
<h2 class="section-title mb-4 text-center">Select Theater for: <?= htmlspecialchars($movie['title']) ?></h2>
<div class="container">
  <div class="row g-4">
    <?php foreach ($theaters as $theater): ?>
      <div class="col-md-4">
        <div class="card h-100 shadow-sm border-0">
          <?php if (!empty($theater['photo'])): ?>
            <img src="admin/upload/theater/<?= htmlspecialchars($theater['photo']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Theater Image">
          <?php endif; ?>
          <div class="card-body d-flex flex-column justify-content-between">
            <div>
              <h5 class="card-title"><?= htmlspecialchars($theater['name']) ?></h5>
              <p class="card-text"><strong>Address:</strong> <?= htmlspecialchars($theater['address']) ?></p>
              <p class="card-text"><strong>Location:</strong> <?= htmlspecialchars($theater['location']) ?></p>
              <p class="card-text"><strong>Available Showtimes:</strong> <?= $theater['show_count'] ?></p>
            </div>
            <a href="select_show.php?movie_id=<?= $movie_id ?>&theater_id=<?= $theater['theater_id'] ?>" class="btn btn-gradient mt-3 w-100">View Showtimes</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
