<?php
session_start();
require_once 'db_connect.php';

if (!isset($_SESSION['pending_booking'])) {
    echo "No booking found.";
    exit;
}

$booking = $_SESSION['pending_booking'];
$random_trx = rand(1000000000, 9999999999);
$amount = $booking['total_amount'];
$show_verification_box = false;
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['verify_step']) && $_POST['verify_step'] == '1') {
        $show_verification_box = true;
    } elseif (isset($_POST['verification_code'])) {
       if ($_POST['verification_code'] == '22') {
           $user_id = $_SESSION['id'];
           $payment_method = 'nagad';
           $transaction_id = $_POST['trx_id'];
           $showtime_id = $booking['showtime_id'];
           $seats = $booking['selected_seats']; // array
           $price_per_seat = $amount / count($seats);

           // Prepare insert for each seat
           $stmt = $conn->prepare("INSERT INTO bookings (user_id, showtime_id, seat_number, payment_method, transaction_id, total_amount)
                                   VALUES (?, ?, ?, ?, ?, ?)");

           foreach ($seats as $seat) {
               $stmt->execute([$user_id, $showtime_id, $seat, $payment_method, $transaction_id, $price_per_seat]);
           }

           // Optional: get last booking ID
           $last_booking_id = $conn->lastInsertId();

           // Mark each seat as booked
           $update_stmt = $conn->prepare("UPDATE seats SET is_booked = 1 WHERE showtime_id = ? AND seat_number = ?");
           foreach ($seats as $seat) {
               $update_stmt->execute([$showtime_id, $seat]);
           }

           $_SESSION['transaction_id'] = $transaction_id;
           header("Location: ticket/ticket.php");

           exit;
       }
 else {
            $error_message = "❌ Incorrect verification code.";
            $show_verification_box = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nagad Payment</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f2f2f2;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 60px 20px;
        }

        h1 {
            color: #D60000;
            margin-bottom: 30px;
        }

        form {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            max-width: 420px;
            width: 100%;
            border-top: 6px solid #D60000;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #333;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
            transition: border-color 0.2s ease;
        }

        input:focus {
            border-color: #D60000;
            outline: none;
        }

        button {
            padding: 12px;
            width: 100%;
            background: #D60000;
            color: white;
            font-weight: bold;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        button:hover {
            background: #b90000;
        }

        .error {
            color: #D60000;
            margin-bottom: 10px;
            font-weight: 500;
        }
    </style>


</head>
<body>
<h1>
    <img src="nagad.jpeg" alt="Nagad Logo" style="height: 50px; vertical-align: middle; margin-right: 10px;">
    Payment
</h1>
<form method="POST">
    <?php if ($error_message): ?>
        <div class="error"><?= $error_message ?></div>
    <?php endif; ?>

    <div class="form-group">
        <label>Nagad Number:</label>
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
        <input type="hidden" name="verify_step" value="1">
        <button type="submit">Verify Number</button>
    <?php else: ?>
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
      background-color: white;
      transition: all 0.3s ease;
    " onmouseover="this.style.backgroundColor='#b90000'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='black';">
      ← Back
    </a>

</body>
</html>
