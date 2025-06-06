<?php
require_once '../db_connect.php';

$showtime_id = $_GET['showtime_id'];

// Step 1: Get theater capacity from showtime
$stmt = $conn->prepare("
    SELECT s.theater_id, t.capacity, t.name AS theater_name
    FROM movie_showtimes s
    JOIN theaters t ON s.theater_id = t.theater_id
    WHERE s.showtime_id = ?
");
$stmt->execute([$showtime_id]);
$info = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$info) {
    echo "<p>Invalid showtime.</p>";
    exit;
}

$capacity = $info['capacity'];
$columns = 10;
$rows = ceil($capacity / $columns);
$rowLetters = range('A', 'Z');

// Step 2: Get booked seats for this showtime
$bookedStmt = $conn->prepare("SELECT seat_number FROM seats WHERE showtime_id = ?");
$bookedStmt->execute([$showtime_id]);
$bookedSeats = array_column($bookedStmt->fetchAll(PDO::FETCH_ASSOC), 'seat_number');

// Step 3: Render seat layout
echo "<h6>Select Seat(s):</h6>";
echo "<div class='d-flex flex-column gap-1'>";
$seatCount = 0;

for ($i = 0; $i < $rows; $i++) {
    echo "<div class='d-flex gap-2'>";
    for ($j = 1; $j <= $columns; $j++) {
        $seatCount++;
        if ($seatCount > $capacity) break;

        $seatLabel = $rowLetters[$i] . $j;
        $isBooked = in_array($seatLabel, $bookedSeats);
        $btnClass = $isBooked ? 'secondary' : 'success seat-select-btn';
        $disabled = $isBooked ? 'disabled' : '';

        echo "<button type='button' class='btn btn-sm btn-$btnClass' data-seat='$seatLabel' $disabled>$seatLabel</button>";
    }
    echo "</div>";
}
echo "</div>";

// Proceed button
echo "<div class='mt-3'>
        <button class='btn btn-primary' id='proceed-btn' disabled>Proceed to Checkout</button>
      </div>";
?>
