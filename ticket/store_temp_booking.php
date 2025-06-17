<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['pending_booking'] = [
        'showtime_id' => $_POST['showtime_id'],
        'selected_seats' => explode(',', $_POST['selected_seats']), // Convert to array
        'total_amount' => $_POST['total_amount']
    ];
    echo "Stored";
} else {
    echo "Invalid request.";
}

