session_start();
require_once 'db_connect.php';

$park_id = $_GET['park_id'];
$package = $_GET['package'];
$quantity = $_POST['quantity'] ?? 1; // Assume from form
// Fetch price from DB
$stmt = $conn->prepare("SELECT {$package}_price AS price FROM parks WHERE park_id = ?");
$stmt->execute([$park_id]);
$row = $stmt->fetch();
$total = $row['price'] * $quantity;

// Store booking in session
$_SESSION['pending_park_booking'] = [
    'park_id' => $park_id,
    'package_type' => $package,
    'quantity' => $quantity,
    'total_amount' => $total
];

// Redirect to payment
header("Location: payment_park.php");
exit;
