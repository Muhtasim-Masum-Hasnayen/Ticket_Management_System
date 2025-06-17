<?php
session_start();
require_once '../db_connect.php';

// Get latest museum booking (optional: replace with GET param for better control)
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
      background: linear-gradient(to right, #ff6b6b, #6c5ce7);
      font-family: 'Segoe UI', sans-serif;
      padding: 50px;
      color: #333;
    }

    .ticket {
      position: relative;
      background: #fff;
      max-width: 700px;
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
      margin-top: 40px;
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
      .btn,
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

  <div class="text-center">
    <button class="btn btn-print" onclick="window.print()">Print Ticket</button>
  </div>
</div>

</body>
</html>
