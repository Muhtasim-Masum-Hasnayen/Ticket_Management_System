<?php
session_start();
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $museum_id = $_POST['museum_id'];
    $user_id = $_SESSION['user_id'] ?? 1; // Use actual session user ID
    $booking_time = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO museum_bookings (user_id, museum_id, booking_time) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $museum_id, $booking_time]);

    $booking_id = $conn->lastInsertId();
    header("Location: museum_ticket.php?id=$booking_id");
    exit;
}
?>
