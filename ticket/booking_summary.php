<?php
$movie = $_GET['movie'];
$theater = $_GET['theater'];
$show_time = $_GET['show_time'];
$seats = explode(',', $_GET['seats']);
$total = $_GET['total'];
$showtime_id = $_GET['showtime_id'];
?>

<h2>Booking Summary</h2>
<p><strong>Movie:</strong> <?= htmlspecialchars($movie) ?></p>
<p><strong>Theater:</strong> <?= htmlspecialchars($theater) ?></p>
<p><strong>Show Time:</strong> <?= htmlspecialchars($show_time) ?></p>
<p><strong>Selected Seats:</strong> <?= implode(', ', $seats) ?></p>
<p><strong>Total Price:</strong> ₹<?= htmlspecialchars($total) ?></p>

<form action="confirm_booking.php" method="POST">
  <input type="hidden" name="showtime_id" value="<?= $showtime_id ?>">
  <input type="hidden" name="seats" value="<?= implode(',', $seats) ?>">
  <input type="hidden" name="total" value="<?= $total ?>">
  <button type="submit" class="btn btn-success">Confirm & Pay</button>
</form>
