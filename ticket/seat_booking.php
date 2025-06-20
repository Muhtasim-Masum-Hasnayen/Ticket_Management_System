<?php
session_start();
//session_unset();
//session_destroy();

require_once '../db_connect.php';


 // Required to access session

$isLoggedIn = isset($_SESSION['id']);



$showtime_id = $_GET['showtime_id'] ?? null;
if (!$showtime_id) {
    echo "Showtime not selected.";
    exit;
}

// Get showtime details
$stmt = $conn->prepare("SELECT s.show_time, s.price, m.title AS movie, t.name AS theater
                        FROM movie_showtimes s
                        JOIN movies m ON s.movie_id = m.movie_id
                        JOIN theaters t ON s.theater_id = t.theater_id
                        WHERE s.showtime_id = ?");
$stmt->execute([$showtime_id]);
$details = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$details) {
    echo "Invalid showtime.";
    exit;
}

// Get already booked seats
$stmt = $conn->prepare("SELECT seat_number FROM bookings WHERE showtime_id = ?");
$stmt->execute([$showtime_id]);
$bookedSeats = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Select Seats</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #6c5ce7, #ff6b6b);
      min-height: 100vh;
      padding: 40px 20px;
      color: #333;
    }
    .container {
      background: white;
      border-radius: 20px;
      padding: 30px;
      max-width: 1000px;
      margin: auto;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
    .seat {
      width: 40px;
      height: 40px;
      margin: 5px;
      border-radius: 8px;
      background-color: #4caf50;
      color: white;
      border: none;
    }
    .seat.selected {
      background-color: #ff9800;
    }
    .seat.booked {
      background-color: #ccc;
      cursor: not-allowed;
    }
    .summary-box {
      background: #f4f4f4;
      padding: 20px;
      border-radius: 10px;
      margin-top: 20px;
    }
    .btn-confirm {
      background: linear-gradient(to right, #ff6b6b, #6c5ce7);
      color: white;
      border: none;
      padding: 10px 25px;
      border-radius: 30px;
      font-weight: bold;
    }
  </style>
</head>
<body>
<a href="javascript:history.back()" style="
  display: inline-block;
  padding: 10px 20px;
  border: 2px solid white;
  color: white;
  text-decoration: none;
  border-radius: 8px;
  font-weight: bold;
  background-color: transparent;
  transition: all 0.3s ease;
" onmouseover="this.style.backgroundColor='white'; this.style.color='#333';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='white';">
  ← Back
</a>

<div class="container">
  <h2 class="text-center text-primary mb-4">Select Your Seats for <?= htmlspecialchars($details['movie']) ?></h2>

  <p><strong>Theater:</strong> <?= htmlspecialchars($details['theater']) ?></p>
  <p><strong>Show Time:</strong> <?= htmlspecialchars($details['show_time']) ?></p>
  <p><strong>Price per seat:</strong> TK. <?= htmlspecialchars($details['price']) ?></p>

  <hr>

  <div class="d-flex flex-wrap justify-content-center">
    <?php
    $rows = 5;
    $cols = 10;
    for ($r = 1; $r <= $rows; $r++) {
      for ($c = 1; $c <= $cols; $c++) {
        $seat = chr(64 + $r) . $c;
        $booked = in_array($seat, $bookedSeats);
        echo "<button class='seat " . ($booked ? "booked" : "") . "' data-seat='$seat' " . ($booked ? "disabled" : "") . ">$seat</button>";
      }
      echo "<br/>";
    }
    ?>
  </div>

  <form id="booking-form" method="POST">

    <input type="hidden" name="showtime_id" value="<?= $showtime_id ?>">
    <input type="hidden" name="selected_seats" id="selected_seats_input">

    <div class="summary-box mt-4">
      <h5>Booking Summary</h5>
      <p><strong>Movie:</strong> <?= htmlspecialchars($details['movie']) ?></p>
      <p><strong>Theater:</strong> <?= htmlspecialchars($details['theater']) ?></p>
      <p><strong>Show Time:</strong> <?= htmlspecialchars($details['show_time']) ?></p>
      <p><strong>Selected Seats:</strong> <span id="selected-seats-display">None</span></p>
      <p><strong>Total Amount:</strong> <span id="total-amount">0</span></p>
    </div>

    <div class="text-center mt-4">
      <button type="button" class="btn btn-confirm" disabled id="confirm-btn">Confirm </button>

    </div>
  </form>
</div>
<script>
  const seatButtons = document.querySelectorAll('.seat:not(.booked)');
  const selectedSeatsDisplay = document.getElementById('selected-seats-display');
  const totalAmountDisplay = document.getElementById('total-amount');
  const selectedSeatsInput = document.getElementById('selected_seats_input');
  const confirmBtn = document.getElementById('confirm-btn');
  const bookingForm = document.getElementById('booking-form');

  const seatPrice = <?= $details['price'] ?>;
  let selectedSeats = [];

  seatButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const seat = btn.dataset.seat;
      if (selectedSeats.includes(seat)) {
        selectedSeats = selectedSeats.filter(s => s !== seat);
        btn.classList.remove('selected');
      } else {
        selectedSeats.push(seat);
        btn.classList.add('selected');
      }

      // Update UI
      selectedSeatsDisplay.textContent = selectedSeats.length > 0 ? selectedSeats.join(', ') : 'None';
      totalAmountDisplay.textContent = seatPrice * selectedSeats.length;
      selectedSeatsInput.value = selectedSeats.join(',');
      confirmBtn.disabled = selectedSeats.length === 0;
    });
  });

  // Handle login check before submission
  confirmBtn.addEventListener('click', function (e) {
    e.preventDefault();

    const isLoggedIn = <?= $isLoggedIn ? 'true' : 'false' ?>;
     console.log("Login status:", isLoggedIn);

    if (!isLoggedIn) {
     console.log("User not logged in. Saving temp data and redirecting.");

      // Store booking data in session via AJAX
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'store_temp_booking.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

      const postData = `showtime_id=<?= $showtime_id ?>&selected_seats=${selectedSeats.join(',')}&total_amount=${seatPrice * selectedSeats.length}`;
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          // Redirect to login
          window.location.href = '../login.php';
        }
      };
      xhr.send(postData);
    } else {
    console.log("User logged in. Storing session and redirecting to payment.");
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'store_temp_booking.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    const postData = `showtime_id=<?= $showtime_id ?>&selected_seats=${selectedSeats.join(',')}&total_amount=${seatPrice * selectedSeats.length}`;
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        // Redirect to payment method selection page
        window.location.href = 'confirm_booking.php';
      }
    };
    xhr.send(postData);

    }
  });
</script>


</body>
</html>
