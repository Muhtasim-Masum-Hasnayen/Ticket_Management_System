<?php
session_start();
if (!isset($_SESSION['lang'])) $_SESSION['lang'] = 'bn';
if (isset($_GET['lang']) && in_array($_GET['lang'], ['bn', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'];
$text = [
    'bn' => ['title' => 'বিকাশ পেমেন্ট', 'number' => 'বিকাশ নম্বর', 'trxid' => 'লেনদেন আইডি', 'amount' => 'টাকার পরিমাণ', 'submit' => 'জমা দিন'],
    'en' => ['title' => 'bKash Payment', 'number' => 'bKash Number', 'trxid' => 'Transaction ID', 'amount' => 'Amount (BDT)', 'submit' => 'Submit']
];
$current = $text[$lang];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $current['title'] ?> | SmartKirshi</title>
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
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            transition: 0.3s;
        }
        input[type="text"]:focus, input[type="number"]:focus {
            border-color: #e2136e;
            outline: none;
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
            transition: background 0.3s;
        }
        button:hover {
            background: #c51162;
        }
        .back {
            margin-top: 20px;
        }
        .back a {
            text-decoration: none;
            color: #e2136e;
            font-weight: bold;
        }
        .logo {
            margin-bottom: 20px;
        }
        .logo img {
            height: 60px;
        }
    </style>
</head>
<body>
    <div class="logo">
        <img src="bkash.jpeg" alt="bkash Logo">
    </div>
    <h1><?= $current['title'] ?></h1>
    <form method="POST" action="">
        <div class="form-group">
            <label><?= $current['number'] ?>:</label>
            <input type="text" name="bkash_number" required>
        </div>
        <div class="form-group">
            <label><?= $current['trxid'] ?>:</label>
            <input type="text" name="trx_id" required>
        </div>
        <div class="form-group">
            <label><?= $current['amount'] ?>:</label>
            <input type="number" name="amount" required>
        </div>
        <button type="submit"><?= $current['submit'] ?></button>
    </form>
</body>
</html>
