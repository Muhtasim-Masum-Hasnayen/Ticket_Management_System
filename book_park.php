<?php
session_start();
require_once 'db_connect.php';

// Validate input
if (!isset($_GET['park_id']) || !isset($_GET['package'])) {
    die("Missing park ID or package type.");
}

$park_id = (int) $_GET['park_id'];
$package = preg_replace('/[^a-z_]/i', '', $_GET['package']); // sanitize column name
$quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

if ($quantity < 1) {
    $quantity = 1;
}

// Prevent SQL injection via column name
$valid_packages = ['general', 'family', 'student', 'corporate'];
if (!in_array($package, $valid_packages)) {
    die("Invalid package type.");
}

// Prepare SQL query
$query = "SELECT {$package}_price AS price FROM parks WHERE park_id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$park_id]);
$row = $stmt->fetch();

if (!$row) {
    die("Invalid park ID or package not found.");
}

$total = $row['price'] * $quantity;

// Store in session
$_SESSION['pending_park_booking'] = [
    'park_id' => $park_id,
    'package_type' => $package,
    'quantity' => $quantity,
    'total_amount' => $total
];

// Redirect to payment
header("Location: payment_park.php");
exit;
?>
