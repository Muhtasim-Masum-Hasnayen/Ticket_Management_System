<?php
require_once '../db_connect.php';

$showtime_id = $_GET['showtime_id'];

$stmt = $pdo->prepare("SELECT seat_number, is_booked FROM seats WHERE showtime_id = ?");
$stmt->execute([$showtime_id]);
$seats = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "<h6>Select a Seat:</h6>";
echo "<div class='d-flex flex-wrap gap-2'>";
foreach ($seats as $seat) {
    $disabled = $seat['is_booked'] ? 'disabled' : '';
    echo "<button class='btn btn-sm btn-" . ($seat['is_booked'] ? 'secondary' : 'success') . "' $disabled>
            {$seat['seat_number']}
          </button>";
}
echo "</div>";
?>
