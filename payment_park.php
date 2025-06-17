<?php
session_start();
require_once 'db_connect.php';

// Ensure booking data exists
if (!isset($_SESSION['pending_park_booking'])) {
    echo "No pending booking found.";
    exit;
}

$booking = $_SESSION['pending_park_booking'];

// Fetch park name
$stmt = $conn->prepare("SELECT name FROM parks WHERE park_id = ?");
$stmt->execute([$booking['park_id']]);
$park = $stmt->fetch();

if (!$park) {
    echo "Invalid park ID.";
    exit;
}

$park_name = htmlspecialchars($park['name']);
$package_type = ucfirst($booking['package_type']);
$quantity = (int)$booking['quantity'];
$total = number_format($booking['total_amount'], 2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Park Ticket Payment</title>
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
      padding: 30px;
    }

    .payment-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(8px);
      border-radius: 20px;
      padding: 50px 40px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      width: 100%;
      max-width: 750px;
      text-align: center;
    }

    .payment-card h2 {
      font-weight: 700;
      color: #ffffff;
      margin-bottom: 25px;
    }

    .payment-card p {
      font-size: 18px;
      margin: 10px 0;
      color: #f1f1f1;
    }

    .methods {
      display: flex;
      gap: 30px;
      justify-content: center;
      margin: 40px 0;
      flex-wrap: wrap;
    }

    .method {
      background: #ffffff;
      padding: 20px;
      border-radius: 16px;
      width: 140px;
      cursor: pointer;
      box-shadow: 0 5px 15px rgba(0,0,0,0.15);
      transition: transform 0.3s, box-shadow 0.3s;
    }

    .method:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    }

    .method img {
      width: 80px;
      height: 80px;
      object-fit: contain;
    }

    .method p {
      margin-top: 10px;
      font-weight: 600;
      color: #333;
    }

    .btn-cancel {
      background: transparent;
      border: 2px solid #fff;
      color: #fff;
      font-weight: bold;
      padding: 10px 30px;
      border-radius: 50px;
      margin-top: 60px;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-block;
    }

    .btn-cancel:hover {
      background: #fff;
      color: #6c5ce7;
      border-color: #fff;
    }

    hr {
      border-top: 2px solid rgba(255, 255, 255, 0.2);
      width: 80%;
      margin: 20px auto;
    }
  </style>
</head>
<body>
  <div class="payment-card">
    <h2>Confirm Your Park Ticket</h2>
    <hr>

    <p><strong>Park:</strong> <?= $park_name ?></p>
    <p><strong>Package:</strong> <?= $package_type ?></p>
    <p><strong>Quantity:</strong> <?= $quantity ?></p>
    <p><strong>Total Amount:</strong> ৳<?= $total ?></p>

    <h2>Select Your Payment Method</h2>

    <div class="methods">
      <div class="method" onclick="window.location.href='park/bkash.php'">
        <img src="bkash.jpeg" alt="bKash">
        <p>bKash</p>
      </div>
      <div class="method" onclick="window.location.href='park/nagad.php'">
        <img src="nagad.jpeg" alt="Nagad">
        <p>Nagad</p>
      </div>
      <div class="method" onclick="window.location.href='park/rocket.php'">
        <img src="rocket.jpg" alt="Rocket">
        <p>Rocket</p>
      </div>
    </div>

    <a href="parks.php" class="btn-cancel">Cancel</a>
  </div>
</body>
</html>
