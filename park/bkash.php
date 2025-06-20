<?php
session_start();
require_once '../db_connect.php';

// Check for session booking data
if (!isset($_SESSION['pending_park_booking'])) {
    echo "No booking found.";
    exit;
}

$booking = $_SESSION['pending_park_booking'];
$random_trx = rand(1000000000, 9999999999);
$amount = $booking['total_amount'];
$show_verification_box = false;
$error_message = "";

// Step 2: After clicking verify and submitting verification code
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['verify_step']) && $_POST['verify_step'] == '1') {
        // Trigger show of verification field
        $show_verification_box = true;
    } elseif (isset($_POST['verification_code'])) {
        if ($_POST['verification_code'] == '22') {
            $user_id = $_SESSION['id'];
            $payment_method = 'bKash';
            $transaction_id = $_POST['trx_id'];
           $park_id = $booking['park_id'];
           $package_type = $booking['package_type'];
           $quantity = $booking['quantity'];
           $total_amount = $booking['total_amount'];

           // Optional: get user ID if logged in
           $user_id = $_SESSION['id'] ?? null;

           // Insert into bookings table
           $stmt = $conn->prepare("INSERT INTO park_bookings (user_id, park_id, package_type, quantity, total_amount, booking_time) VALUES (?, ?, ?, ?, ?, NOW())");
           $stmt->execute([$user_id, $park_id, $package_type, $quantity, $total_amount]);

          // Optionally decrease available tickets
          $col = $package_type . '_available_ticket';
          $update = $conn->prepare("UPDATE parks SET {$col} = {$col} - ? WHERE park_id = ?");
          $update->execute([$quantity, $park_id]);
          // Clear pending session
          unset($_SESSION['pending_park_booking']);

          //

                // After storing booking in DB
                $booking_id = $conn->lastInsertId();  // Assuming you're using PDO and this is the booking ID
                header("Location: ticket.php?booking_id=" . $booking_id);
                exit;

            }

        } else {
            $error_message = "❌ Incorrect verification code.";
            $show_verification_box = true;
        }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>bKash Payment</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #fdf2f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 60px 20px;
        }
        h1 {
            color: #e2136e;
        }
        form {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
            border-top: 8px solid #e2136e;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #444;
        }
        input {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        button {
            padding: 12px;
            width: 100%;
            background: #e2136e;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .error {
            color: red;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>
        <img src="bkash.jpeg" alt="bKash Logo" style="height: 50px; vertical-align: middle; margin-right: 10px;">
         Payment
    </h1>
    <form method="POST">
        <?php if ($error_message): ?>
            <div class="error"><?= $error_message ?></div>
        <?php endif; ?>


        <div class="form-group">
            <label>bKash Number:</label>
            <input type="text" name="bkash_number" required pattern="\d{11}" placeholder="01XXXXXXXXX"
                   value="<?= isset($_POST['bkash_number']) ? htmlspecialchars($_POST['bkash_number']) : '' ?>">
        </div>

        <div class="form-group">
            <label>Transaction ID:</label>
            <input type="text" name="trx_id" value="<?= $random_trx ?>" readonly>
        </div>
        <div class="form-group">
            <label>Amount (BDT):</label>
            <input type="number" name="amount" value="<?= $amount ?>" readonly>
        </div>

        <?php if (!$show_verification_box): ?>
            <!-- Show this first -->
            <input type="hidden" name="verify_step" value="1">
            <button type="submit">Verify Number</button>
        <?php else: ?>
            <!-- Show after clicking verify -->
            <div class="form-group">
                <label>Verification Code :</label>
                <input type="text" name="verification_code" required>
            </div>
            <button type="submit">Submit Payment</button>
        <?php endif; ?>
    </form>
    <a href="javascript:history.back()" style="
      display: inline-block;
      padding: 10px 20px;
      border: 2px solid black;
      color: black;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      background-color: transparent;
      transition: all 0.3s ease;
    " onmouseover="this.style.backgroundColor='#e2136e'; this.style.color='#333';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='black';">
      ← Back
    </a>

</body>
</html>
