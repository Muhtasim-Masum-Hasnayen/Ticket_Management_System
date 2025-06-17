<!-- Save this as login.php -->

<?php session_start();
 if (isset($_SESSION['pending_booking'])) {
     header('Location: ticket/confirm_booking.php');
     exit;
 }


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - SmartTicket</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #ff6b6b, #6c5ce7);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-container {
      background-color: #ffffff;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      padding: 40px;
      max-width: 450px;
      width: 100%;
    }

    .login-container h2 {
      font-weight: bold;
      color: #6c5ce7;
    }

    .form-control {
      border-radius: 10px;
      box-shadow: none !important;
    }

    .btn-login {
      background: linear-gradient(to right, #ff6b6b, #6c5ce7);
      color: white;
      border: none;
      border-radius: 30px;
      padding: 10px 0;
      font-weight: bold;
      transition: all 0.3s ease;
    }

    .btn-login:hover {
      background: linear-gradient(to right, #6c5ce7, #ff6b6b);
    }

    .social-icons a {
      color: #6c5ce7;
      font-size: 20px;
      margin: 0 10px;
      transition: color 0.3s;
    }

    .social-icons a:hover {
      color: #ff6b6b;
    }

    .text-muted a {
      text-decoration: none;
      color: #6c5ce7;
    }

    .text-muted a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="login-container text-center">
    <h2 class="mb-4">Smart<span class="text-warning">Ticket</span> Login</h2>

    <form action="login_process.php" method="POST">
      <div class="mb-3 text-start">
        <label for="phone" class="form-label fw-semibold">Phone Number</label>
        <input type="tel" class="form-control" id="phone" name="phone" placeholder="01XXXXXXXXX" pattern="01[0-9]{9}" required />
      </div>

      <div class="mb-4 text-start">
        <label for="password" class="form-label fw-semibold">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required />
      </div>

      <button type="submit" class="btn btn-login w-100">Login</button>
    </form>

    <p class="mt-3 text-muted">Don't have an account? <a href="register.php">Register here</a></p>

    <p class="mt-4">Or sign in with</p>
    <div class="social-icons">
      <a href="#"><i class="fab fa-google"></i></a>
      <a href="#"><i class="fab fa-facebook"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
