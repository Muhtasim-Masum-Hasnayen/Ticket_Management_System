<?php
session_start();
require_once '../db_connect.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); // important!

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
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
        body { font-family: Arial; background: #f2f2f2; }
        .login-box {
            max-width: 400px; margin: 80px auto; background: white;
            padding: 30px; border-radius: 8px; box-shadow: 0 0 10px #ccc;
        }
        input[type="text"], input[type="password"] {
            width: 100%; padding: 10px; margin: 10px 0; border-radius: 4px; border: 1px solid #ccc;
        }
        input[type="submit"] {
            background: #28a745; color: white; border: none; padding: 10px 20px; cursor: pointer;
        }
        .error { color: red; }
    </style>
</head>
<body>
<div class="login-box">
    <h2>Admin Login</h2>
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
