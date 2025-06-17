<?php
include '../db_connect.php';

$booking_id = $_GET['id'] ?? null;

if (!$booking_id) {
    echo "Invalid ticket.";
    exit;
}

$stmt = $conn->prepare("
  SELECT b.booking_time, m.name, m.location, m.price
  FROM museum_bookings b
  JOIN museums m ON b.museum_id = m.museum_id
  WHERE b.booking_id = :id
");
$stmt->execute([':id' => $booking_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    echo "Ticket not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Your Museum Ticket</title>
  <style>
    body {
      background: #e0f7fa;
      font-family: 'Poppins', sans-serif;
      padding: 40px;
    }
    .ticket {
      background: white;
      padding: 30px;
      border-radius: 15px;
      max-width: 600px;
      margin: auto;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }
    h2 {
      color: #6c5ce7;
    }
    p {
      font-size: 16px;
    }
    .btn {
      margin-top: 20px;
      background: #6c5ce7;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="ticket">
    <h2>Museum Entry Ticket</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($data['name']) ?></p>
    <p><strong>Location:</strong> <?= htmlspecialchars($data['location']) ?></p>
    <p><strong>Price:</strong> ৳<?= $data['price'] ?></p>
    <p><strong>Booking Time:</strong> <?= $data['booking_time'] ?></p>
    <button class="btn" onclick="window.print()">Print Ticket</button>
  </div>
</body>
</html>
