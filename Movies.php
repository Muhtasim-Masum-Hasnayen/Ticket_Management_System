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
      <!-- Movie cards will be here -->

      <!-- Sample Movie Card -->
      <div class="col-md-4 movie-item" data-title="Dhaka Dreams" data-genre="Drama" data-origin="Bangladeshi">
        <div class="movie-card">
          <img src="https://upload.wikimedia.org/wikipedia/en/7/7d/Dhaka_Dreams_poster.jpg" alt="Dhaka Dreams Poster" class="movie-poster" />
          <div class="movie-body">
            <div class="movie-title">Dhaka Dreams</div>
            <div class="movie-meta">Drama | 2h 10m | Released: 2023</div>
            <p>A touching story about dreams and struggles in Dhaka.</p>
            <button class="btn-book" onclick="alert('Booking feature coming soon!')">Book Now</button>
          </div>
        </div>
      </div>

      <div class="col-md-4 movie-item" data-title="The Avengers" data-genre="Action" data-origin="Hollywood">
        <div class="movie-card">
          <img src="https://upload.wikimedia.org/wikipedia/en/f/f9/The_Avengers_%282012_film%29_poster.jpg" alt="The Avengers Poster" class="movie-poster" />
          <div class="movie-body">
            <div class="movie-title">The Avengers</div>
            <div class="movie-meta">Action | 2h 23m | Released: 2012</div>
            <p>Earth's mightiest heroes team up to fight a global threat.</p>
            <button class="btn-book" onclick="alert('Booking feature coming soon!')">Book Now</button>
          </div>
        </div>
      </div>

      <div class="col-md-4 movie-item" data-title="Monpura" data-genre="Romance" data-origin="Bangladeshi">
        <div class="movie-card">
          <img src="https://upload.wikimedia.org/wikipedia/en/5/5e/Monpura_poster.jpg" alt="Monpura Poster" class="movie-poster" />
          <div class="movie-body">
            <div class="movie-title">Monpura</div>
            <div class="movie-meta">Romance | 2h 5m | Released: 2009</div>
            <p>A rural love story deeply loved in Bangladesh cinema.</p>
            <button class="btn-book" onclick="alert('Booking feature coming soon!')">Book Now</button>
          </div>
        </div>
      </div>

      <div class="col-md-4 movie-item" data-title="Inception" data-genre="Thriller" data-origin="Hollywood">
        <div class="movie-card">
          <img src="https://upload.wikimedia.org/wikipedia/en/7/7f/Inception_ver3.jpg" alt="Inception Poster" class="movie-poster" />
          <div class="movie-body">
            <div class="movie-title">Inception</div>
            <div class="movie-meta">Thriller | 2h 28m | Released: 2010</div>
            <p>A mind-bending thriller exploring dreams within dreams.</p>
            <button class="btn-book" onclick="alert('Booking feature coming soon!')">Book Now</button>
          </div>
        </div>
      </div>

      <div class="col-md-4 movie-item" data-title="Television" data-genre="Drama" data-origin="Bangladeshi">
        <div class="movie-card">
          <img src="https://upload.wikimedia.org/wikipedia/en/e/e7/Television_2012_film_poster.jpg" alt="Television Poster" class="movie-poster" />
          <div class="movie-body">
            <div class="movie-title">Television</div>
            <div class="movie-meta">Drama | 1h 50m | Released: 2012</div>
            <p>A socio-political drama about rural Bangladesh.</p>
            <button class="btn-book" onclick="alert('Booking feature coming soon!')">Book Now</button>
          </div>
        </div>
      </div>

      <div class="col-md-4 movie-item" data-title="Titanic" data-genre="Romance" data-origin="Hollywood">
        <div class="movie-card">
          <img src="https://upload.wikimedia.org/wikipedia/en/2/2e/Titanic_poster.jpg" alt="Titanic Poster" class="movie-poster" />
          <div class="movie-body">
            <div class="movie-title">Titanic</div>
            <div class="movie-meta">Romance | 3h 14m | Released: 1997</div>
            <p>The epic love story aboard the doomed Titanic ship.</p>
            <button class="btn-book" onclick="alert('Booking feature coming soon!')">Book Now</button>
          </div>
        </div>
      </div>

    </div>
  </div>

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
