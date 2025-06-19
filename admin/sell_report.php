<?php
session_start();
require_once '../db_connect.php';

// Optional: Check login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

try {
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

    $stmt2 = $conn->query("
        SELECT p.name AS park, pb.package_type, COUNT(pb.id) AS tickets_sold,
               SUM(pb.total_amount) AS total_revenue
        FROM park_bookings pb
        JOIN parks p ON pb.park_id = p.park_id
        GROUP BY p.name, pb.package_type
    ");
    $parkSales = $stmt2->fetchAll(PDO::FETCH_ASSOC);

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sell Report | SmartTicket Admin</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background: linear-gradient(135deg, #ffffff, #ffcce5);
            color: #212529;
            min-height: 100vh;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 99%;
            background: linear-gradient(135deg, #ff4c60, #4ecdc4);
            color: white;
            padding: 15px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        .logout {
            color: white;
            text-decoration: none;
            background-color: #dc3545;
            padding: 8px 14px;
            border-radius: 4px;
            font-size: 14px;
        }

        .logout:hover {
            background-color: #c82333;
        }

        .sidebar {
            width: 220px;
            background: linear-gradient(135deg, #ff4c60, #4ecdc4);
            color: white;
            height: 100vh;
            padding-top: 20px;
            position: fixed;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        .main-content {
            margin-left: 220px;
            margin-top: 70px;
            padding: 20px;
        }

        .main-content h1 {
            margin-top: 0;
        }

        .section {
            margin-bottom: 60px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
            color: #000;
        }

        th {
            background-color: #000;
            color: #fff;
        }

        h2 {
            color: #343a40;
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }

            header h1 {
                font-size: 20px;
            }

            .sidebar {
                display: none;
            }

            table, th, td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Sell Report</h1>
    <a href="admin_login.php" class="logout">Logout</a>
</header>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="view_users.php">👥 View Users</a>
    <a href="a_contact.php">📄 Contact Message</a>
    <a href="add_movie.php?type=movie">➕ Add Movie</a>
    <a href="add_theater.php">🏛 Add Theater</a>
    <a href="add_museum.php?type=museum">🖼 Add Museum</a>
    <a href="add_park.php?type=park">🌳 Add Park</a>
    <a href="sell_report.php">📄 Sell Report</a>
    <a href="admin_login.php">🚪 Logout</a>
</div>

<div class="main-content">
    <h1>🎟️ Ticket Sales Report</h1>

    <div class="section">
        <h2>🎬 Movie Theater Ticket Sales</h2>
        <table>
            <tr>
                <th>Movie</th>
                <th>Theater</th>
                <th>Total Orders</th>
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
                <th>Total Orders</th>
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
                <th>Total Orders</th>
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
</div>

</body>
</html>
