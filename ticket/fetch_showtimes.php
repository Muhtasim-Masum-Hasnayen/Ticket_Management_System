<?php
require_once '../db_connect.php';

$movie_id = $_GET['movie_id'];
$theater_id = $_GET['theater_id'];

$stmt = $pdo->prepare("SELECT showtime_id, show_date, show_time FROM movie_showtimes WHERE movie_id = ? AND theater_id = ?");
$stmt->execute([$movie_id, $theater_id]);
$showtimes = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($showtimes) === 0) {
    echo "<p>No showtimes available.</p>";
    exit;
}

echo "<div class='d-flex flex-wrap gap-2'>";
foreach ($showtimes as $show) {
    echo "<button class='btn btn-outline-primary select-showtime-btn' data-showtime-id='{$show['showtime_id']}'>
            {$show['show_date']} at {$show['show_time']}
          </button>";
}
echo "</div>";
?>
