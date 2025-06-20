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
    <title>Select Payment Method</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #ff6b6b, #6c5ce7);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            color: #fff;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            backdrop-filter: blur(12px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            padding: 40px;
            max-width: 700px;
            width: 100%;
            text-align: center;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        h2 {
            font-size: 32px;
            margin-bottom: 40px;
            font-weight: 600;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.3);
        }

        .methods {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .method {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 30px 20px;
            text-align: center;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            width: 180px;
        }

        .method:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 28px rgba(0,0,0,0.3);
        }

        .method img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-bottom: 15px;
            border-radius: 10px;
            background: #fff;
            padding: 5px;
        }

        .method p {
            font-weight: bold;
            color: #fff;
            font-size: 18px;
        }

        .footer {
            margin-top: 50px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
        }

        @media (max-width: 600px) {
            .method {
                width: 140px;
                padding: 20px 10px;
            }

            .method img {
                width: 60px;
                height: 60px;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>🎟️ Choose Your Payment Method</h2>
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
        <div class="footer">SmartTicket © <?= date('Y') ?>. All rights reserved.</div>
    </div>
    <a href="javascript:history.back()" style="
      display: inline-block;
      padding: 10px 20px;
      border: 2px solid white;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
      background-color: transparent;
      transition: all 0.3s ease;
    " onmouseover="this.style.backgroundColor='white'; this.style.color='#333';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='white';">
      ← Back
    </a>

</body>
</html>
