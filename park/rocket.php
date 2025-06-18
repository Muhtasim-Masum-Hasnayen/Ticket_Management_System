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
            $payment_method = 'nagad';
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
    <title>rocket Payment</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f9f5ff;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 60px 20px;
        }

        h1 {
            color: #7A1E9C; /* Rocket purple */
            margin-bottom: 30px;
        }

        form {
            background: #ffffff;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 6px 20px rgba(122, 30, 156, 0.1);
            max-width: 420px;
            width: 100%;
            border-top: 6px solid #7A1E9C;
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
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
            transition: border-color 0.2s ease;
        }

        input:focus {
            border-color: #7A1E9C;
            outline: none;
        }

        button {
            padding: 12px;
            width: 100%;
            background: #7A1E9C;
            color: white;
            font-weight: bold;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        button:hover {
            background: #660f87;
        }

        .error {
            color: #7A1E9C;
            margin-bottom: 10px;
            font-weight: 500;
        }
    </style>

</head>
<body>
    <h1>
        <img src="rocket.jpg" alt="nagad Logo" style="height: 50px; vertical-align: middle; margin-right: 10px;">
         Payment
    </h1>
    <form method="POST">
        <?php if ($error_message): ?>
            <div class="error"><?= $error_message ?></div>
        <?php endif; ?>


        <div class="form-group">
            <label>rocket Number:</label>
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

</body>
</html>
