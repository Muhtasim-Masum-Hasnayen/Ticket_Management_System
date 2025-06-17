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
  <title>Payment - Museum Ticket</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #f953c6, #b91d73);
      color: white;
      font-family: 'Segoe UI', sans-serif;
    }
    .payment-card {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 6px 25px rgba(0, 0, 0, 0.5);
      max-width: 700px;
      margin: 60px auto;
      font-size: 18px;
    }

    .btn-pay {
      background: linear-gradient(to right, #00f260, #0575e6);
      border: none;
      color: white;
    }
    .btn-pay:hover {
      background: linear-gradient(to right, #0575e6, #00f260);
    }
    h2 {
      margin-bottom: 30px;
      color: #fff;
    }
    .methods {
      display: flex;
      gap: 30px;
      flex-wrap: wrap;
      justify-content: center;
      margin-top: 30px;
    }
    .method {
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      text-align: center;
      cursor: pointer;
      transition: transform 0.2s;
    }
    .method:hover {
      transform: scale(1.05);
    }
    .method img {
      width: 100px;
      height: 100px;
    }
    .method p {
      margin-top: 10px;
      font-weight: bold;
      color: #444;
    }
  </style>
</head>
<body>
  <div class="payment-card text-center">
    <h2>Confirm Your Museum Ticket</h2>
    <hr class="bg-white">

    <p><strong>Museum:</strong> <?= $museum_name ?></p>
    <p><strong>Quantity:</strong> <?= $quantity ?></p>
    <p><strong>Price Per Ticket:</strong> ৳<?= $price_per_ticket ?></p>
    <p><strong>Total Amount:</strong> ৳<?= $total_amount ?></p>

    <h2>Select Payment Method</h2>
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

    <a href="museums.php" class="btn btn-light mt-3">Cancel</a>
  </div>
</body>
</html>
