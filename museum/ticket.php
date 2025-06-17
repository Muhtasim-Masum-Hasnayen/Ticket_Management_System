<?php
session_start();
require_once '../db_connect.php';

// Get latest museum booking (you can pass booking_id via GET for precision)
$stmt = $conn->query("SELECT * FROM museum_bookings ORDER BY booking_id DESC LIMIT 1");
$booking = $stmt->fetch();

if (!$booking) {
    echo "No booking found.";
    exit;
}

// Get museum info
$museumStmt = $conn->prepare("SELECT * FROM museums WHERE museum_id = ?");
$museumStmt->execute([$booking['museum_id']]);
$museum = $museumStmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Print Museum Ticket</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      padding: 40px;
      background-color: #f8f9fa;
    }
    .ticket {
      max-width: 600px;
      margin: auto;
      padding: 30px;
      border: 2px dashed #333;
      background-color: #fff;
      border-radius: 10px;
    }
    .ticket h2 {
      margin-bottom: 20px;
    }
    .ticket-info {
      font-size: 16px;
      line-height: 1.6;
    }
    .btn-print {
      margin-top: 20px;
    }
  </style>
</head>
<body>

<div class="ticket text-center">
  <h2>Museum Ticket</h2>
  <div class="ticket-info text-start">
    <p><strong>Museum Name:</strong> <?= htmlspecialchars($museum['name']) ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($museum['address']) ?></p>
    <p><strong>Quantity:</strong> <?= $booking['quantity'] ?></p>
    <p><strong>Total Amount:</strong> ৳<?= number_format($booking['total_amount'], 2) ?></p>
    <p><strong>Payment Method:</strong> <?= htmlspecialchars($booking['payment_method']) ?></p>
    <p><strong>Payment Status:</strong> <?= ucfirst($booking['payment_status']) ?></p>
    <p><strong>Booking Date:</strong> <?= date("F j, Y, g:i a", strtotime($booking['booking_time'])) ?></p>
    <p><strong>Ticket ID:</strong> #<?= str_pad($booking['booking_id'], 6, '0', STR_PAD_LEFT) ?></p>
  </div>

  <button class="btn btn-primary btn-print" onclick="window.print()">Print Ticket</button>
</div>

</body>
</html>
