<?php
session_start();
require_once '../db_connect.php';

// Simulate retrieving last booking
$stmt = $conn->query("SELECT * FROM park_bookings ORDER BY id DESC LIMIT 1");
$booking = $stmt->fetch();

if (!$booking) {
    echo "No booking found.";
    exit;
}

// Fetch park info
$parkStmt = $conn->prepare("SELECT * FROM parks WHERE park_id = ?");
$parkStmt->execute([$booking['park_id']]);
$park = $parkStmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Park Ticket</title>
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

  <div class="ticket text-start">
    <h2>Amusement Park Entry Ticket</h2>

    <div class="ticket-info">
      <p><strong>Park Name:</strong> <?= htmlspecialchars($park['name']) ?></p>
      <p><strong>Location:</strong> <?= htmlspecialchars($park['location']) ?></p>
      <p><strong>Package Type:</strong> <?= ucfirst($booking['package_type']) ?></p>
      <p><strong>Quantity:</strong> <?= $booking['quantity'] ?></p>
      <p><strong>Total Amount:</strong> ৳<?= number_format($booking['total_amount'], 2) ?></p>
      <p><strong>Booking Date:</strong> <?= date("F j, Y, g:i a", strtotime($booking['booking_time'])) ?></p>
      <p><strong>Ticket ID:</strong> #<?= str_pad($booking['id'], 6, '0', STR_PAD_LEFT) ?></p>
    </div>

    <div class="text-center">
      <button class="btn btn-print" onclick="window.print()">Print Ticket</button>
    </div>
  </div>
  <!-- Place this after your theater cards section -->
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
        transition: all 0.3s ease;
      ">
        <i class="fas fa-home"></i> Dashboard
      </a>

</body>
</html>
