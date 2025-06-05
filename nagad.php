<?php
session_start();
if (!isset($_SESSION['lang'])) $_SESSION['lang'] = 'bn';
if (isset($_GET['lang']) && in_array($_GET['lang'], ['bn', 'en'])) $_SESSION['lang'] = $_GET['lang'];
$lang = $_SESSION['lang'];
$text = [
    'bn' => ['title' => 'নগদ পেমেন্ট', 'number' => 'নগদ নম্বর', 'trxid' => 'লেনদেন আইডি', 'amount' => 'টাকার পরিমাণ', 'submit' => 'জমা দিন'],
    'en' => ['title' => 'Nagad Payment', 'number' => 'Nagad Number', 'trxid' => 'Transaction ID', 'amount' => 'Amount (BDT)', 'submit' => 'Submit']
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
            background: #fff4f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 60px 20px;
        }
        h1 {
            color: #f76820;
        }
        form {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            max-width: 400px;
            width: 100%;
            border-top: 8px solid #f76820;
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
        input:focus {
            border-color: #f76820;
            outline: none;
        }
        button {
            padding: 12px;
            width: 100%;
            background: #f76820;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #dd4c00;
        }
        .back {
            margin-top: 20px;
        }
        .back a {
            text-decoration: none;
            color: #f76820;
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
        <img src="nagad.jpeg" alt="Nagad Logo">
    </div>
    <h1><?= $current['title'] ?></h1>
    <form method="POST" action="">
        <div class="form-group">
            <label><?= $current['number'] ?>:</label>
            <input type="text" name="nagad_number" required>
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
