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
            $payment_method = 'Rocket';
            $transaction_id = $_POST['trx_id'];
            $showtime_id = $booking['showtime_id'];
            $seats = $booking['selected_seats'];

            $stmt = $conn->prepare("INSERT INTO bookings (user_id, showtime_id, seat_number, payment_method, transaction_id, total_amount) VALUES (?, ?, ?, ?, ?, ?)");
            foreach ($seats as $seat) {
                $stmt->execute([$user_id, $showtime_id, $seat, $payment_method, $transaction_id, $amount]);
                $booking_id = $conn->lastInsertId();
                header("Location: ticket/ticket.php?booking_id=" . $booking_id);
                exit;
            }

            unset($_SESSION['pending_booking']);
        } else {
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
    <title>Rocket Payment</title>
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
    <img src="rocket.jpg" alt="" style="height: 50px; vertical-align: middle; margin-right: 10px;">
    Payment
</h1>
<form method="POST">
    <?php if ($error_message): ?>
        <div class="error"><?= $error_message ?></div>
    <?php endif; ?>

    <div class="form-group">
        <label>Rocket Number:</label>
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
</body>
</html>
