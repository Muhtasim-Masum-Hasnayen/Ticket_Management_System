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
    /* General Reset and Base Styles */
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #ff6b6b, #6c5ce7);
      min-height: 100vh;
      padding: 40px 20px;
      color: #333;
      margin: 0;
    }

    /* Container */
    .container {
      background: white;
      border-radius: 20px;
      padding: 30px;
      max-width: 1200px;
      margin: auto;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    /* Section Title */
    .section-title {
      font-weight: 700;
      margin-bottom: 30px;
      border-bottom: 2px solid #6c5ce7;
      padding-bottom: 5px;
      color: #6c5ce7;
      text-align: center;
    }

    /* Theater Cards */
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

    /* Button Gradient */
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

    /* Responsive Gutter Fix */
    .row.g-4 > [class*='col-'] {
      margin-bottom: 30px;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .card-body {
        text-align: center;
      }
    }

  </style>
</head>
<body>
<h2 class="section-title mb-4 text-center">Select Theater for: <?= htmlspecialchars($movie['title']) ?></h2>
<div class="row g-4">
  <?php foreach ($theaters as $theater): ?>
    <div class="col-md-4">
      <div class="card h-100 shadow-sm border-0" style="border-radius: 15px;">
        <?php if (!empty($theater['photo'])): ?>
          <img src="admin/upload/theater/<?= htmlspecialchars($theater['photo']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Theater Image">
        <?php endif; ?>
        <div class="card-body d-flex flex-column justify-content-between">
          <div>
            <h5 class="card-title text-primary"><?= htmlspecialchars($theater['name']) ?></h5>
            <p class="card-text"><strong>Address:</strong> <?= htmlspecialchars($theater['address']) ?></p>
            <p class="card-text"><strong>Location:</strong> <?= htmlspecialchars($theater['location']) ?></p>
            <p class="card-text"><strong>Available Showtimes:</strong> <?= $theater['show_count'] ?></p>
          </div>
          <a href="fetch_showtimes.php"
             class="btn btn-gradient mt-3 w-100 view-showtimes-btn"
             data-movie-id="<?= $movie_id ?>"
             data-theater-id="<?= $theater['theater_id'] ?>"
             data-bs-toggle="modal"
             data-bs-target="#showtimeModal">
             View Showtimes
          </a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
<!-- Bootstrap Modal for Showtimes -->
<div class="modal fade" id="showtimeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select Show Time</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="showtime-container" class="mb-3">Loading showtimes...</div>
        <div id="seat-container" class="mt-4"></div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const showtimeModal = document.getElementById('showtimeModal');

  // This runs when the modal is about to be shown
  showtimeModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget; // Button that triggered the modal
    const movieId = button.getAttribute('data-movie-id');
    const theaterId = button.getAttribute('data-theater-id');
    const showtimeContainer = document.getElementById('showtime-container');

    // Clear previous content
    showtimeContainer.innerHTML = 'Loading showtimes...';
    document.getElementById('seat-container').innerHTML = '';

    // Load showtimes
    fetch(`fetch_showtimes.php?movie_id=${movieId}&theater_id=${theaterId}`)
      .then(res => {
        if (!res.ok) {
          throw new Error('Network response was not ok');
        }
        return res.text();
      })
      .then(data => {
        showtimeContainer.innerHTML = data;
      })
      .catch(error => {
        console.error('Error loading showtimes:', error);
        showtimeContainer.innerHTML = 'Error loading showtimes. Please try again.';
      });
  });

  // Event delegation for showtime selection (works for dynamically loaded content)
  document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('select-showtime-btn')) {
      e.preventDefault();
      const showtimeId = e.target.dataset.showtimeId;
      const seatContainer = document.getElementById('seat-container');

      seatContainer.innerHTML = 'Loading seats...';

      fetch(`fetch_seats.php?showtime_id=${showtimeId}`)
        .then(res => {
          if (!res.ok) {
            throw new Error('Network response was not ok');
          }
          return res.text();
        })
        .then(data => {
          seatContainer.innerHTML = data;
        })
        .catch(error => {
          console.error('Error loading seats:', error);
          seatContainer.innerHTML = 'Error loading seats. Please try again.';
        });
    }
  });
});
</script>
<script>
let selectedSeats = [];
let seatPrice = 0;

document.addEventListener('click', function(e) {
  if (e.target.classList.contains('seat-select-btn')) {
    const seat = e.target.dataset.seat;
    const index = selectedSeats.indexOf(seat);

    if (index > -1) {
      selectedSeats.splice(index, 1);
      e.target.classList.remove('btn-warning');
      e.target.classList.add('btn-success');
    } else {
      selectedSeats.push(seat);
      e.target.classList.remove('btn-success');
      e.target.classList.add('btn-warning');
    }

    document.getElementById('proceed-btn').disabled = selectedSeats.length === 0;
  }

  if (e.target.id === 'proceed-btn') {
    const showtimeId = document.querySelector('.select-showtime-btn.active')?.dataset.showtimeId;

    fetch(`booking_summary.php?showtime_id=${showtimeId}`)
      .then(res => res.json())
      .then(info => {
        seatPrice = info.price;

        const total = seatPrice * selectedSeats.length;
        const query = new URLSearchParams({
          movie: info.movie,
          theater: info.theater,
          show_time: info.show_time,
          showtime_id: showtimeId,
          seats: selectedSeats.join(','),
          total: total
        }).toString();

        window.location.href = `booking_summary.php?${query}`;
      });
  }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
