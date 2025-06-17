<?php
session_start();
require_once '../db_connect.php';

// Redirect if user not logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}

// Ensure booking session exists
if (!isset($_SESSION['pending_museum_booking'])) {
    echo "No pending museum booking found.";
    exit;
}

$booking = $_SESSION['pending_museum_booking'];

// Fetch museum name
$stmt = $conn->prepare("SELECT name FROM museums WHERE museum_id = ?");
$stmt->execute([$booking['museum_id']]);
$museum = $stmt->fetch();

if (!$museum) {
    echo "Invalid museum ID.";
    exit;
}

$museum_name = htmlspecialchars($museum['name']);
$quantity = (int)$booking['quantity'];
$price_per_ticket = number_format($booking['ticket_price'], 2);
$total_amount = number_format($booking['total_amount'], 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Confirm Payment - Museum Ticket</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #ff6b6b, #6c5ce7);
      font-family: 'Segoe UI', sans-serif;
      color: #fff;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .payment-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(15px);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.4);
      max-width: 800px;
      width: 90%;
      text-align: center;
      color: #fff;
    }

    .payment-card h2 {
      font-weight: 700;
      margin-bottom: 20px;
    }

    .payment-card p {
      font-size: 18px;
      margin: 8px 0;
    }

    .methods {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 30px;
      margin-top: 30px;
    }

    .method {
      background: rgba(255, 255, 255, 0.9);
      border-radius: 16px;
      padding: 20px 25px;
      width: 160px;
      text-align: center;
      box-shadow: 0 6px 18px rgba(0,0,0,0.2);
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .method:hover {
      transform: translateY(-5px) scale(1.03);
    }

    .method img {
      width: 80px;
      height: 80px;
      object-fit: contain;
    }

    .method p {
      color: #333;
      margin-top: 10px;
      font-weight: bold;
    }

    .btn-cancel {
      margin-top: 30px;
      background: rgba(255, 255, 255, 0.2);
      border: 1px solid white;
      color: white;
      padding: 10px 24px;
      border-radius: 30px;
      font-weight: 600;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .btn-cancel:hover {
      background: rgba(255, 255, 255, 0.4);
      color: #000;
    }

    .divider {
      width: 60px;
      height: 4px;
      background: #fff;
      border-radius: 4px;
      margin: 20px auto;
    }
  </style>
</head>
<body>
  <div class="payment-card">
    <h2>🎟️ Confirm Your Museum Ticket</h2>
    <div class="divider"></div>

    <p><strong>Museum:</strong> <?= $museum_name ?></p>
    <p><strong>Quantity:</strong> <?= $quantity ?></p>
    <p><strong>Price Per Ticket:</strong> ৳<?= $price_per_ticket ?></p>
    <p><strong>Total Amount:</strong> ৳<?= $total_amount ?></p>

    <h2 style="margin-top: 40px;">Select Payment Method</h2>
    <div class="methods">
      <div class="method" onclick="window.location.href='bkash.php'">
        <img src="bkash.jpeg" alt="bKash">
        <p>bKash</p>
      </div>
      <div class="method" onclick="window.location.href='nagad.php'">
        <img src="nagad.jpeg" alt="Nagad">
        <p>Nagad</p>
      </div>
      <div class="method" onclick="window.location.href='rocket.php'">
        <img src="rocket.jpg" alt="Rocket">
        <p>Rocket</p>
      </div>
    </div>

    <a href="../Museums.php" class="btn-cancel" style="margin-top: 60px; display: inline-block;">Cancel</a>
  </div>
</body>
</html>
