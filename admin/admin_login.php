<?php
session_start();
require_once '../db_connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); // important!

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin) {
        if (password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Password is incorrect.";
        }
    } else {
        $error = "Username not found.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - SmartTicket</title>
    <style>
        body {
            font-family: Arial;
            background: linear-gradient(135deg, #ffffff, #ffcce5);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            max-width: 400px;
            width: 100%;
            background: linear-gradient(135deg, #ff4c60, #4ecdc4);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            color: white;
        }
        .login-title {
          text-align: center;
          color: white; /* Optional: adjust color for better visibility */
          margin-bottom: 20px;
        }

        .login-box img {
          max-width: 100px;
          margin: 0 auto 10px;
          display: block;
        }


        input[type="text"],
        input[type="password"] {
            width: 97%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: none;
            outline: none;
        }

        input[type="submit"] {
            background: #222;
            color: white;
            border: none;
            padding: 12px 190px;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 6px;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background: #000;
        }

        .error {
            color: #ffe0e0;
            background: rgba(0, 0, 0, 0.2);
            padding: 8px;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>

</head>
<body>
<div class="login-box">
   <img src="SmartTicketLogo.png" alt="Smart Ticket Logo">
       <h2 class="login-title">Smart Ticket Admin Login</h2>
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <input type="submit" value="Login">
    </form>
</div>
</body>
</html>
