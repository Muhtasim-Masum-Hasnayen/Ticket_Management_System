<?php
session_start();
require_once '../db_connect.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch all contact messages
$stmt = $conn->query("SELECT * FROM contact_messages ORDER BY submitted_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Messages | Admin Panel</title>
    <style>
        body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    margin: 0;
                    background: linear-gradient(135deg, #ffffff, #ffcce5); /* Soft gradient */
                    color: #212529;
                    min-height: 100vh;
                }

                /* Optional: make header slightly darker to stand out from gradient */
                header {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 99%;
                    background: linear-gradient(135deg, #ff4c60, #4ecdc4); /* Colorful gradient */
                    color: white;
                    padding: 15px 10px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    z-index: 1000;
                    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); /* Optional shadow */
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

                .container {
                    padding-top: 30px;
                    max-width: 1100px;
                    margin: auto;
                }

                h2 {
                    margin-top: 50px;
                    margin-bottom: 10px;
                    color: #343a40;
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
                            color: white;
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
            background: linear-gradient(135deg, #ffffff, #ffcce5);
            margin-left: 220px;
            padding: 30px;
        }

        h2 {
            color: #343a40;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
        }
        th {
                    border: 1px solid #dee2e6;
                    padding: 10px 12px;
                    text-align: left;
                    vertical-align: top;
                    color: #ffffff
                }
        td {
            border: 1px solid #dee2e6;
            padding: 10px 12px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #000000;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }

            table, th, td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>SmartTicket Admin Panel</h1>
    <a href="logout.php" class="logout">Logout</a>
</header>

<div class="sidebar">
     <h2>Admin Panel</h2>
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="view_users.php">👥 View Users</a>
        <a href="a_contact.php">📄 Contact Message</a>
        <a href="add_movie.php?type=movie">➕ Add Movie</a>
        <a href="add_theater.php">🏛 Add Theater</a>
        <a href="add_museum.php?type=Add Movie_Theater">🖼 Add Museum</a>
        <a href="add_park.php?type=park">🌳 Add Park</a>
        <a href="view_bookings.php">📄 View Bookings</a>
        <a href="admin_login.php">🚪 Logout</a>
</div>

<div class="main-content">
    <h2>📩 Contact Messages</h2>

    <?php if (count($messages) === 0): ?>
        <p>No contact messages found.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Contact Info</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Submitted At</th>
            </tr>
            <?php foreach ($messages as $msg): ?>
            <tr>
                <td><?= htmlspecialchars($msg['id']) ?></td>
                <td><?= htmlspecialchars($msg['name']) ?></td>
                <td><?= htmlspecialchars($msg['contact_info']) ?></td>
                <td><?= htmlspecialchars($msg['subject']) ?></td>
                <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
                <td><?= htmlspecialchars($msg['submitted_at']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
