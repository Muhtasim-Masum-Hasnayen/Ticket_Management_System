<?php
session_start();
require_once '../db_connect.php';

$booking_id = $_GET['booking_id'] ?? null;
if (!$booking_id) {
    echo "Invalid ticket.";
    exit;
}

// Fetch ticket and booking details
$stmt = $conn->prepare("SELECT b.booking_id, b.seat_number, b.total_amount,
                               m.title AS movie, t.name AS theater, s.show_time
                        FROM bookings b
                        JOIN movie_showtimes s ON b.showtime_id = s.showtime_id
                        JOIN movies m ON s.movie_id = m.movie_id
                        JOIN theaters t ON s.theater_id = t.theater_id
                        WHERE b.booking_id = ?");
$stmt->execute([$booking_id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) {
    echo "Ticket not found.";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Ticket</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef2f3;
            display: flex;
            justify-content: center;
            padding: 50px;
        }
        .ticket {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            width: 400px;
            text-align: center;
        }
        .ticket h2 {
            margin-bottom: 20px;
        }
        .ticket p {
            margin: 8px 0;
        }
        .btn-print {
            margin-top: 20px;
            padding: 10px 20px;
            background: #28a745;
            border: none;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn-print:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<div class="ticket" id="ticket">
    <h2>🎟 Movie Ticket</h2>
    <p><strong>Movie:</strong> <?= htmlspecialchars($ticket['movie']) ?></p>
    <p><strong>Theater:</strong> <?= htmlspecialchars($ticket['theater']) ?></p>
    <p><strong>Show Time:</strong> <?= htmlspecialchars($ticket['show_time']) ?></p>
    <p><strong>Seat(s):</strong> <?= htmlspecialchars($ticket['seat_number']) ?></p>
    <p><strong>Amount Paid:</strong> <?= htmlspecialchars($ticket['total_amount']) ?></p>


    <button class="btn-print" onclick="window.print()">Print Ticket</button>
</div>

</body>
</html>
