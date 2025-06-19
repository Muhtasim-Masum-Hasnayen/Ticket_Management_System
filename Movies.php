
<?php
require_once 'db_connect.php';

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
    .modal-overlay {
      position: fixed;
      top: 0; left: 0;
      width: 100vw; height: 100vh;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(4px);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 10000;
    }

    .modal-box {
      background: #fff;
      border-radius: 16px;
      padding: 30px 25px;
      width: 90%;
      max-width: 400px;
      text-align: center;
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
      animation: slideUp 0.4s ease-out;
    }

    .modal-box h2 {
      font-size: 24px;
      margin-bottom: 10px;
      color: #333;
    }

    .modal-box p {
      font-size: 16px;
      color: #555;
      margin-bottom: 25px;
    }

    .modal-actions {
      display: flex;
      justify-content: center;
      gap: 15px;
    }

    .btn-login {
      background: #6c5ce7;
      color: #fff;
      padding: 10px 20px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      transition: background 0.3s;
    }

    .btn-login:hover {
      background: #5a4de1;
    }

    .btn-cancel {
      background: #ddd;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }

    .btn-cancel:hover {
      background: #bbb;
    }

    @keyframes slideUp {
      from { transform: translateY(50px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
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


      </div>


    <!-- Movies Grid -->
    <div class="row g-4 mt-1" id="moviesGrid">
      <?php foreach ($movies as $movie): ?>
        <div class="col-md-4 movie-item"
             data-title="<?= htmlspecialchars($movie['title']) ?>"
             data-genre="<?= htmlspecialchars($movie['genre']) ?>"
             data-origin="<?= htmlspecialchars($movie['language']) ?>">

          <div class="movie-card">
            <img src="admin/<?= htmlspecialchars($movie['photo']) ?>" alt="<?= htmlspecialchars($movie['title']) ?> Poster" class="movie-poster" />
            <div class="movie-body">
              <div class="movie-title"><?= htmlspecialchars($movie['title']) ?></div>
              <div class="movie-meta"><?= htmlspecialchars($movie['genre']) ?> | <?= htmlspecialchars($movie['duration_minutes']) ?> | Released: <?= htmlspecialchars($movie['release_year']) ?></div>
              <p><?= htmlspecialchars($movie['description']) ?></p>
              <button class="btn-book" onclick="showLoginModal(event)">Buy Ticket</button>
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


    const movies = document.querySelectorAll('.movie-item');
    movies.forEach(movie => {
      const title = movie.getAttribute('data-title').toLowerCase();
      const genre = movie.getAttribute('data-genre');


      const matchesSearch = title.includes(searchInput);
      const matchesGenre = genreFilter === '' || genre === genreFilter;


      if (matchesSearch && matchesGenre ) {
        movie.style.display = 'block';
      } else {
        movie.style.display = 'none';
      }
    });
  }

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Login Required Modal -->
<div id="loginModal" class="modal-overlay">
  <div class="modal-box">
    <h2>🔐 Login Required</h2>
    <p>Please login first to continue buying your ticket.</p>
    <div class="modal-actions">
      <a href="login.php" class="btn-login">Login Now</a>
      <button onclick="closeLoginModal()" class="btn-cancel">Cancel</button>
    </div>
  </div>
</div>
<script>
function showLoginModal(event) {
    event.preventDefault(); // Prevent default button action
    document.getElementById("loginModal").style.display = "flex";
}

function closeLoginModal() {
    document.getElementById("loginModal").style.display = "none";
}
</script>

</body>
</html>
