<?php
session_start();
require_once '../db_connect.php';



// Get form data (example values — replace with actual POST or GET)
$museum_id = $_POST['museum_id'];
$quantity = (int)$_POST['quantity'];

// Fetch museum and price
$stmt = $conn->prepare("SELECT name, price FROM museums WHERE museum_id = ?");
$stmt->execute([$museum_id]);
$museum = $stmt->fetch();

if (!$museum) {
    echo "Invalid museum selected.";
    exit;
}

$ticket_price = $museum['price'];
$total_amount = $ticket_price * $quantity;

// Store in session
$_SESSION['pending_museum_booking'] = [
    'museum_id'     => $museum_id,
    'quantity'      => $quantity,
    'ticket_price'  => $ticket_price,
    'total_amount'  => $total_amount
];

// Redirect to payment page
header("Location: museum_booking.php");
exit;
?>
