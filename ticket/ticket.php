<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['transaction_id'])) {
    echo "No transaction found.";
    exit;
}

$transaction_id = $_SESSION['transaction_id'];

// Get all bookings under this transaction
$stmt = $conn->prepare("
    SELECT b.*, m.title AS movie, t.name AS theater, s.show_time
    FROM bookings b
    JOIN movie_showtimes s ON b.showtime_id = s.showtime_id
    JOIN movies m ON s.movie_id = m.movie_id
    JOIN theaters t ON s.theater_id = t.theater_id
    WHERE b.transaction_id = ?
");
$stmt->execute([$transaction_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$bookings) {
    echo "No booking found.";
    exit;
}

$info = $bookings[0]; // Common details
$all_seats = array_column($bookings, 'seat_number');
$total_amount = array_sum(array_column($bookings, 'total_amount'));

// Clear pending booking data
unset($_SESSION['pending_booking']);
unset($_SESSION['transaction_id']);
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
    <p><strong>Movie:</strong> <?= htmlspecialchars($info['movie']) ?></p>
    <p><strong>Theater:</strong> <?= htmlspecialchars($info['theater']) ?></p>
    <p><strong>Show Time:</strong> <?= htmlspecialchars($info['show_time']) ?></p>
    <p><strong>Seat(s):</strong> <?= implode(', ', $all_seats) ?></p>
    <p><strong>Total Paid:</strong> ৳<?= number_format($total_amount, 2) ?></p>
    <p><strong>Transaction ID:</strong> <?= htmlspecialchars($transaction_id) ?></p>
  </div>

  <div class="text-center">
    <button class="btn btn-print" onclick="window.print()">Print Ticket</button>
  </div>
</div>

<div style="text-align: center; margin-top: 60px;">
  <a href="../dashboard.php" style="
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    background: linear-gradient(to right, #141e30, #243b55);
    color: #fff;
    text-decoration: none;
    border: none;
    border-radius: 100px;
    font-weight: 600;
    font-size: 16px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;">
    <i class="fas fa-home"></i> Dashboard
  </a>
</div>

</body>
</html>
