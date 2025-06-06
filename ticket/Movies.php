
<?php
require_once '../db_connect.php';

// Fetch distinct movies that have showtimes, extract release year from release_date
$sql = "SELECT DISTINCT m.movie_id, m.title, m.genre, m.language, m.photo, m.description, m.duration_minutes,
               YEAR(m.release_date) AS release_year
        FROM movies m
        JOIN movie_showtimes s ON m.movie_id = s.movie_id
        ORDER BY m.title ASC";
$stmt = $conn->query($sql);
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    }
    .container {
      background: white;
      border-radius: 20px;
      padding: 30px;
      max-width: 1200px;
      margin: auto;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    h1 {
      color: #6c5ce7;
      font-weight: 700;
      margin-bottom: 30px;
      text-align: center;
    }
    .filter-section {
      margin-bottom: 30px;
    }
    .form-select, .form-control {
      border-radius: 10px;
      box-shadow: none !important;
    }
    .movie-card {
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 8px 20px rgba(108, 92, 231, 0.3);
      transition: transform 0.3s ease;
      background: #fff;
    }
    .movie-card:hover {
      transform: translateY(-8px);
    }
    .movie-poster {
      height: 320px;
      object-fit: cover;
      width: 100%;
    }
    .movie-body {
      padding: 15px;
    }
    .movie-title {
      font-weight: 700;
      color: #6c5ce7;
      margin-bottom: 5px;
    }
    .movie-meta {
      font-size: 0.9rem;
      color: #777;
      margin-bottom: 10px;
    }
    .btn-book {
      background: linear-gradient(to right, #ff6b6b, #6c5ce7);
      color: white;
      border-radius: 30px;
      padding: 8px 20px;
      font-weight: bold;
      transition: all 0.3s ease;
      border: none;
    }
    .btn-book:hover {
      background: linear-gradient(to right, #6c5ce7, #ff6b6b);
      color: white;
    }
    .section-title {
      font-weight: 600;
      margin-bottom: 20px;
      border-bottom: 2px solid #6c5ce7;
      padding-bottom: 5px;
      color: #6c5ce7;
    }
  </style>
</head>
<body>

  <div class="container">
    <h1>Movies Listing</h1>

    <!-- Search & Filter -->
    <div class="row filter-section g-3">
      <div class="col-md-6">
        <input type="text" id="searchInput" class="form-control" placeholder="Search movies by title..." onkeyup="filterMovies()" />
      </div>
      <div class="col-md-3">
        <select id="genreFilter" class="form-select" onchange="filterMovies()">
          <option value="">All Genres</option>
          <option>Action</option>
          <option>Drama</option>
          <option>Comedy</option>
          <option>Romance</option>
          <option>Thriller</option>
          <option>Documentary</option>
          <option>Animation</option>
          <option>Fantasy</option>
        </select>
      </div>
      <div class="col-md-3">
        <select id="originFilter" class="form-select" onchange="filterMovies()">
          <option value="">All Origins</option>
          <option>Bangladeshi</option>
          <option>Hollywood</option>
        </select>
      </div>
    </div>

    <!-- Movies Grid -->
    <div class="row g-4 mt-1" id="moviesGrid">
      <?php foreach ($movies as $movie): ?>
        <div class="col-md-4 movie-item"
             data-title="<?= htmlspecialchars($movie['title']) ?>"
             data-genre="<?= htmlspecialchars($movie['genre']) ?>"
             data-origin="<?= htmlspecialchars($movie['language']) ?>">

          <div class="movie-card">
            <img src="../admin/<?= htmlspecialchars($movie['photo']) ?>" alt="<?= htmlspecialchars($movie['title']) ?> Poster" class="movie-poster" />
            <div class="movie-body">
              <div class="movie-title"><?= htmlspecialchars($movie['title']) ?></div>
              <div class="movie-meta"><?= htmlspecialchars($movie['genre']) ?> | <?= htmlspecialchars($movie['duration_minutes']) ?> | Released: <?= htmlspecialchars($movie['release_year']) ?></div>
              <p><?= htmlspecialchars($movie['description']) ?></p>
              <button class="btn-book" onclick="openShowtimeModal(<?= $movie['movie_id'] ?>)">Book Now</button>
            </div>
          </div>

        </div>
      <?php endforeach; ?>
    </div>
<script>
  function openShowtimeModal(movieId) {
    // Here you can load showtime options dynamically using AJAX or navigate to a showtime selection page
    window.location.href = "selectshowtime.php?movie_id=" + movieId;
  }
</script>

<script>
  function filterMovies() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const genreFilter = document.getElementById('genreFilter').value;
    const originFilter = document.getElementById('originFilter').value;

    const movies = document.querySelectorAll('.movie-item');
    movies.forEach(movie => {
      const title = movie.getAttribute('data-title').toLowerCase();
      const genre = movie.getAttribute('data-genre');
      const origin = movie.getAttribute('data-origin');

      const matchesSearch = title.includes(searchInput);
      const matchesGenre = genreFilter === '' || genre === genreFilter;
      const matchesOrigin = originFilter === '' || origin === originFilter;

      if (matchesSearch && matchesGenre && matchesOrigin) {
        movie.style.display = 'block';
      } else {
        movie.style.display = 'none';
      }
    });
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
