<?php
session_start();
require_once '../db_connect.php'; // Ensure this returns a PDO connection

try {
    // Movie/Theater Sales Report
    $stmt1 = $conn->query("
        SELECT m.title AS movie, t.name AS theater, COUNT(b.booking_id) AS tickets_sold,
               SUM(b.total_amount) AS total_revenue
        FROM bookings b
        JOIN movie_showtimes s ON b.showtime_id = s.showtime_id
        JOIN movies m ON s.movie_id = m.movie_id
        JOIN theaters t ON s.theater_id = t.theater_id
        GROUP BY m.title, t.name
    ");
    $movieSales = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    // Park Ticket Sales
    $stmt2 = $conn->query("
        SELECT p.name AS park, pb.package_type, COUNT(pb.id) AS tickets_sold,
               SUM(pb.total_amount) AS total_revenue
        FROM park_bookings pb

        JOIN parks p ON pb.park_id = p.park_id
        GROUP BY p.name, pb.package_type
    ");
    $parkSales = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // Museum Ticket Sales
    $stmt3 = $conn->query("
        SELECT m.name AS museum, COUNT(mb.booking_id) AS tickets_sold,
               SUM(mb.total_amount) AS total_revenue
        FROM museum_bookings mb

        JOIN museums m ON mb.museum_id = m.museum_id
        GROUP BY m.name
    ");
    $museumSales = $stmt3->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Sell Report</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 20px; }
        h2 { color: #444; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; background: #fff; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background-color: #e0e0e0; }
        .section { margin-bottom: 60px; }
    </style>
</head>
<body>

    <h1>🎟️ Ticket Sales Report</h1>

    <div class="section">
        <h2>🎬 Movie Theater Ticket Sales</h2>
        <table>
            <tr>
                <th>Movie</th>
                <th>Theater</th>
                <th>Tickets Sold</th>
                <th>Total Revenue</th>
            </tr>
            <?php foreach ($movieSales as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['movie']) ?></td>
                    <td><?= htmlspecialchars($row['theater']) ?></td>
                    <td><?= $row['tickets_sold'] ?></td>
                    <td>৳<?= $row['total_revenue'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="section">
        <h2>🏞️ Park Ticket Sales</h2>
        <table>
            <tr>
                <th>Park</th>
                <th>Package</th>
                <th>Tickets Sold</th>
                <th>Total Revenue</th>
            </tr>
            <?php foreach ($parkSales as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['park']) ?></td>
                    <td><?= htmlspecialchars($row['package_type']) ?></td>
                    <td><?= $row['tickets_sold'] ?></td>
                    <td>৳<?= $row['total_revenue'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="section">
        <h2>🏛️ Museum Ticket Sales</h2>
        <table>
            <tr>
                <th>Museum</th>

                <th>Tickets Sold</th>
                <th>Total Revenue</th>
            </tr>
            <?php foreach ($museumSales as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['museum']) ?></td>

                    <td><?= $row['tickets_sold'] ?></td>
                    <td>৳<?= $row['total_revenue'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>
