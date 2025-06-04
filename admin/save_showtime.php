<?php
require_once '../db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movieId = $_POST['movie_id'];
    $price = $_POST['price'];
    $theaters = $_POST['theaters'] ?? [];
    $showTimes = $_POST['show_times'] ?? [];

    if ($movieId && $price && $theaters && $showTimes) {
        $stmt = $pdo->prepare("INSERT INTO movie_showtimes (movie_id, theater_id, show_time, price) VALUES (?, ?, ?, ?)");

foreach ($theaters as $theaterId) {
    foreach ($showTimes as $time) {
        $stmt->execute([$movieId, $theaterId, $time, $price]);
        $showtimeId = $pdo->lastInsertId();

        // Get theater capacity
        $capStmt = $pdo->prepare("SELECT capacity FROM theaters WHERE theater_id = ?");
        $capStmt->execute([$theaterId]);
        $capacity = $capStmt->fetchColumn();

        // Generate seat labels dynamically
        $columns = 10; // number of seats per row
        $rows = ceil($capacity / $columns);
        $rowLetters = range('A', 'Z');

        for ($i = 0; $i < $rows; $i++) {
            for ($j = 1; $j <= $columns; $j++) {
                $seatNum = $i * $columns + $j;
                if ($seatNum > $capacity) break;

                $seatLabel = $rowLetters[$i] . $j;
                $pdo->prepare("INSERT INTO seats (showtime_id, seat_number) VALUES (?, ?)")
                    ->execute([$showtimeId, $seatLabel]);
            }
        }
    }
}
        header("Location: add_movie.php?success=1");
        exit;
    } else {
        header("Location: add_movie.php?error=1");
        exit;
    }
}
?>
