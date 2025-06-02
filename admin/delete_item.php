<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

$type = $_GET['type'] ?? '';
$id = intval($_GET['id'] ?? 0);
$allowed_types = ['movie', 'museum', 'park'];

if (!in_array($type, $allowed_types) || $id <= 0) {
    die('Invalid request.');
}

$table = '';
$id_col = '';

switch ($type) {
    case 'movie': $table = 'movies'; $id_col = 'movie_id'; break;
    case 'museum': $table = 'museums'; $id_col = 'museum_id'; break;
    case 'park': $table = 'parks'; $id_col = 'park_id'; break;
}

$stmt = $pdo->prepare("DELETE FROM $table WHERE $id_col = ?");
$stmt->execute([$id]);

header('Location: dashboard.php');
exit();
