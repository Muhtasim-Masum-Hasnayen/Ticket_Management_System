<?php
session_start();

// Database credentials
$host = "localhost";
$user = "root";
$pass = "";
$db = "smartticket"; // your actual DB name

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $conn->real_escape_string($_POST['phone']);
    $password = $_POST['password']; // Don't escape password, use raw for hashing

    // Check if a user exists with this phone
    $sql = "SELECT * FROM users WHERE phone = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify hashed password
        if (password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password. Please try again.'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Phone number not found. Please register first.'); window.location.href='login.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
