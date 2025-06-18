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
// Clear pending booking data
if (isset($_SESSION['pending_booking'])) {
    unset($_SESSION['pending_booking']);
}

// Proceed with showing success message or redirecting

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Movie Ticket</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #6c5ce7, #ff6b6b);
      font-family: 'Segoe UI', sans-serif;
      padding: 60px;
      color: #333;
    }

    .ticket {
      position: relative;
      background: #fff;
      max-width: 600px;
      margin: auto;
      padding: 40px 30px;
      border-radius: 16px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.3);
      border: 4px dashed #6c5ce7;
      background-image: url('SmartTicketLogo.png');
      background-repeat: no-repeat;
      background-position: right 30px top 30px;
      background-size: 80px;
    }

    .ticket::before {
      content: "SmartTicket";
      position: absolute;
      bottom: 200px;
      right: -50px;
      font-size: 36px;
      font-weight: bold;
      background: linear-gradient(to right, #6c5ce7, #ff6b6b);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-family: 'Courier New', monospace;
      transform: rotate(270deg);
    }

    .ticket h2 {
      font-weight: 700;
      color: #6c5ce7;
      border-bottom: 2px solid #eee;
      padding-bottom: 10px;
      margin-bottom: 30px;
    }

    .ticket-info p {
      font-size: 18px;
      margin: 10px 0;
    }

    .ticket-info strong {
      color: #555;
    }

    .btn-print {
      margin-top: 30px;
      background: linear-gradient(to right, #6c5ce7, #ff6b6b);
      border: none;
      padding: 12px 30px;
      font-weight: bold;
      font-size: 16px;
      color: white;
      border-radius: 50px;
      transition: all 0.3s ease;
    }

    .btn-print:hover {
      background: linear-gradient(to right, #ff6b6b, #6c5ce7);
    }

    @media print {
      body {
        background: none !important;
      }

      .ticket {
        border: 2px dashed #333 !important;
        background: #fff !important;
        color: #000 !important;
        box-shadow: none !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        background-image: url('SmartTicketLogo.png') !important;
        background-repeat: no-repeat !important;
        background-position: right 30px top 30px !important;
        background-size: 80px !important;
      }

      .btn-print,
      nav,
      header,
      footer {
        display: none !important;
      }

      .ticket::before {
        content: "SmartTicket";
        position: absolute;
        bottom: 200px;
        right: -50px;
        font-size: 36px;
        font-weight: bold;
        background: linear-gradient(to right, #6c5ce7, #ff6b6b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-family: 'Courier New', monospace;
        transform: rotate(270deg);
      }
    }
  </style>
</head>
<body>

<div class="ticket text-center">
  <h2>🎬 Movie Ticket</h2>
  <div class="ticket-info text-start">
    <p><strong>Movie:</strong> <?= htmlspecialchars($ticket['movie']) ?></p>
    <p><strong>Theater:</strong> <?= htmlspecialchars($ticket['theater']) ?></p>
    <p><strong>Show Time:</strong> <?= htmlspecialchars($ticket['show_time']) ?></p>
    <p><strong>Seat(s):</strong> <?= htmlspecialchars($ticket['seat_number']) ?></p>
    <p><strong>Amount Paid:</strong> ৳<?= number_format($ticket['total_amount'], 2) ?></p>
    <p><strong>Ticket ID:</strong> #<?= str_pad($ticket['booking_id'], 6, '0', STR_PAD_LEFT) ?></p>
  </div>

  <div class="text-center">
    <button class="btn btn-print" onclick="window.print()">Print Ticket</button>
  </div>
</div>

</body>
</html>
