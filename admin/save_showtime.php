<?php
require_once '../db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movieId = $_POST['movie_id'];
    $price = $_POST['price'];
    $theaters = $_POST['theaters'] ?? [];
    $showTimes = $_POST['show_times'] ?? [];

    // ✅ Add this helper function here
    function getRowLabel($index) {
        $label = '';
        while ($index >= 0) {
            $label = chr($index % 26 + 65) . $label;
            $index = floor($index / 26) - 1;
        }
        return $label;
    }

    if ($movieId && $price && $theaters && $showTimes) {
        $stmt = $conn->prepare("INSERT INTO movie_showtimes (movie_id, theater_id, show_time, price) VALUES (?, ?, ?, ?)");

        foreach ($theaters as $theaterId) {
            foreach ($showTimes as $time) {
                $stmt->execute([$movieId, $theaterId, $time, $price]);
                $showtimeId = $conn->lastInsertId();

                // Get theater capacity
                $capStmt = $conn->prepare("SELECT capacity FROM theaters WHERE theater_id = ?");
                $capStmt->execute([$theaterId]);
                $capacity = $capStmt->fetchColumn();

                // Generate seat labels dynamically
                $columns = 10;
                $rows = ceil($capacity / $columns);

                for ($i = 0; $i < $rows; $i++) {
                    $rowLabel = getRowLabel($i);
                    for ($j = 1; $j <= $columns; $j++) {
                        $seatNum = $i * $columns + $j;
                        if ($seatNum > $capacity) break;

                        $seatLabel = $rowLabel . $j;
                        $conn->prepare("INSERT INTO seats (showtime_id, seat_number) VALUES (?, ?)")
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
