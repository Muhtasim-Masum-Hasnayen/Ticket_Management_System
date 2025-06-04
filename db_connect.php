<?php
$host = 'localhost';       // or 127.0.0.1
$db   = 'smartticket';     // your database name
$user = 'root';            // your DB username (default is 'root' in XAMPP)
$pass = '';                // your DB password (empty in XAMPP by default)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // show errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch as assoc arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // use real prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options); // 🔁 Renamed to $conn
    // echo "Database connected successfully."; // Uncomment for testing
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>
