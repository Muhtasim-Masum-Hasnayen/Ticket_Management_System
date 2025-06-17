<?php
session_start();
if (!isset($_SESSION['pending_booking'])) {
    echo "No booking data found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Payment Method </title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 60px 20px;
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
    <h2>Select Your Payment Method</h2>
    <div class="methods">
        <div class="method" onclick="window.location.href='../bkash.php'">
            <img src="../bkash.jpeg" alt="bKash">
            <p>bKash</p>
        </div>
        <div class="method" onclick="window.location.href='../nagad.php'">
            <img src="../nagad.jpeg" alt="Nagad">
            <p>Nagad</p>
        </div>
        <div class="method" onclick="window.location.href='../rocket.php'">
            <img src="../rocket.jpg" alt="Rocket">
            <p>Rocket</p>
        </div>
    </div>
</body>
</html>
