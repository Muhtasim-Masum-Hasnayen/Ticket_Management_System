<?php
require_once '../db_connect.php';

$movie_id = $_GET['movie_id'] ?? null;
if (!$movie_id) {
    echo "Movie not selected.";
    exit;
}

// Get theaters showing the movie
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
  <title>Select Theater - SmartTicket</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #ff6b6b, #6c5ce7);
      padding: 40px 20px;
      color: #333;
      margin: 0;
    }

    .container {
      background: #fff;
      border-radius: 18px;
      padding: 40px;
      max-width: 1200px;
      margin: auto;
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
    }

    .section-title {
      font-weight: 700;
      font-size: 1.8rem;
      margin-bottom: 40px;
      color: #4a4a4a;
      border-bottom: 3px solid #6c5ce7;
      padding-bottom: 10px;
      text-align: center;
    }

    .card {
      border-radius: 16px;
      overflow: hidden;
      transition: transform 0.3s ease;
      border: none;
      background: #fff;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
    }

    .card:hover {
      transform: translateY(-6px);
    }

    .card-title {
      font-weight: 600;
      font-size: 1.1rem;
      color: #6c5ce7;
    }

    .card-text {
      font-size: 0.95rem;
      color: #555;
    }

    .card-img-top {
      height: 200px;
      object-fit: cover;
    }

    .btn-gradient {
      background: linear-gradient(to right, #6c5ce7, #a29bfe);
      color: white;
      border-radius: 30px;
      padding: 10px 18px;
      font-weight: 600;
      border: none;
      transition: all 0.3s ease;
      text-decoration: none;
      text-align: center;
    }

    .btn-gradient:hover {
      background: linear-gradient(to right, #a29bfe, #6c5ce7);
      color: white;
    }

    @media (max-width: 768px) {
      .card-body {
        text-align: center;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <h2 class="section-title">🎬 Available Theaters for: <?= htmlspecialchars($movie['title']) ?></h2>

    <div class="row g-4">
      <?php foreach ($theaters as $theater): ?>
        <div class="col-md-4">
          <div class="card h-100">
            <?php if (!empty($theater['photo'])): ?>
              <img src="admin/upload/theater/<?= htmlspecialchars($theater['photo']) ?>" class="card-img-top" alt="<?= htmlspecialchars($theater['name']) ?>">
            <?php endif; ?>
            <div class="card-body d-flex flex-column justify-content-between">
              <div>
                <h5 class="card-title"><?= htmlspecialchars($theater['name']) ?></h5>
                <p class="card-text"><strong>📍 Address:</strong> <?= htmlspecialchars($theater['address']) ?></p>
                <p class="card-text"><strong>🗺 Location:</strong> <?= htmlspecialchars($theater['location']) ?></p>
                <p class="card-text"><strong>🎟 Showtimes:</strong> <?= $theater['show_count'] ?></p>
              </div>
              <a href="select_show.php?movie_id=<?= $movie_id ?>&theater_id=<?= $theater['theater_id'] ?>" class="btn btn-gradient mt-3 w-100">
                View Showtimes
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
