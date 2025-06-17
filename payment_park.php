<?php
session_start();
require_once 'db_connect.php';

// Ensure booking data exists
if (!isset($_SESSION['pending_park_booking'])) {
    echo "No pending booking found.";
    exit;
}

$booking = $_SESSION['pending_park_booking'];

// Fetch park name from database
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
  <title>Payment - Park Ticket</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #00c6ff, #0072ff);
      color: white;
      font-family: 'Segoe UI', sans-serif;
    }
    .payment-card {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 40px;
      box-shadow: 0 6px 25px rgba(0, 0, 0, 0.5);
      max-width: 700px; /* Increased from 500px */
      margin: 60px auto;
      font-size: 18px; /* Optional: larger text for better spacing */
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
                color: #333;
            }
            .methods {
                display: flex;
                gap: 30px;
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
    <h2>Confirm Your Park Ticket</h2>
    <hr class="bg-white">

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




    <a href="parks.php" class="btn btn-light mt-3">Cancel</a>
  </div>
</body>
</html>

