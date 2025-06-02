<?php
$host = 'localhost';
$db = 'smartticket';
$user = 'root';
$pass = ''; // your DB password

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $conn->real_escape_string($_POST["name"]);
  $phone = $conn->real_escape_string($_POST["phone"]);
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

  $sql = "INSERT INTO users (name, phone, password) VALUES ('$name', '$phone', '$password')";
  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Registration Successful!'); window.location.href='login.php';</script>";
  } else {
    echo "Error: " . $conn->error;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - SmartTicket</title>
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

    .register-container {
      background-color: #ffffff;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      padding: 40px;
      max-width: 450px;
      width: 100%;
    }

    .register-container h2 {
      font-weight: bold;
      color: #6c5ce7;
    }

    .form-control {
      border-radius: 10px;
      box-shadow: none !important;
    }

    .btn-register {
      background: linear-gradient(to right, #ff6b6b, #6c5ce7);
      color: white;
      border: none;
      border-radius: 30px;
      padding: 10px 0;
      font-weight: bold;
      transition: all 0.3s ease;
    }

    .btn-register:hover {
      background: linear-gradient(to right, #6c5ce7, #ff6b6b);
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

  <div class="register-container text-center">
    <h2 class="mb-4">Smart<span class="text-warning">Ticket</span> Register</h2>

    <form method="POST">
      <div class="mb-3 text-start">
        <label for="name" class="form-label fw-semibold">Full Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required />
      </div>

      <div class="mb-3 text-start">
        <label for="phone" class="form-label fw-semibold">Phone Number</label>
        <input type="tel" class="form-control" id="phone" name="phone" placeholder="01XXXXXXXXX" pattern="01[0-9]{9}" required />
      </div>

      <div class="mb-4 text-start">
        <label for="password" class="form-label fw-semibold">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required />
      </div>

      <button type="submit" class="btn btn-register w-100">Register</button>
    </form>

    <p class="mt-3 text-muted">Already have an account? <a href="login.php">Login here</a></p>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
