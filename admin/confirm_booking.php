<?php
require_once '../db_connect.php';

$seatIds = explode(',', $_POST['selected_seats'] ?? '');
$showtimeId = $_POST['showtime_id'];

// Book seats
foreach ($seatIds as $seatId) {
    $stmt = $pdo->prepare("UPDATE seats SET status = 'booked' WHERE seat_id = ? AND showtime_id = ?");
    $stmt->execute([$seatId, $showtimeId]);
}

echo "Booking confirmed!";
?>
