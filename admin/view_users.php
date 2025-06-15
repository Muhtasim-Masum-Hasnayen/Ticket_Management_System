<?php
// view_users.php — SmartTicket Admin Panel
session_start();
require_once '../db_connect.php';

// Redirect to admin login if not authenticated
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// ✅ Use $conn instead of $conn
$usersStmt = $conn->query(
    "SELECT id, name, phone, photo, created_at
     FROM users
     ORDER BY created_at DESC"
);
$users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Users | SmartTicket Admin</title>
    <style>
        /* ---------- Base ---------- */
        :root {
            --primary: #007bff;
            --primary-dark: #0056b3;
            --danger: linear-gradient(135deg, #ff4c60, #4ecdc4);
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-600: #6c757d;
            --sidebar-bg: #333;
        }
        * { box-sizing: border-box; }
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
                    width: 100%;
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

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 40px;
                    background-color: white;
                    box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
                }

                th, td {
                    border: 1px solid #dee2e6;
                    padding: 10px 12px;
                    text-align: left;
                }

                th {
                    background-color: #e9ecef;
                    font-weight: 600;
                }

                a.button {
                    background-color: #007bff;
                    color: white;
                    padding: 7px 14px;
                    text-decoration: none;
                    border-radius: 4px;
                    font-size: 14px;
                    margin-left: 10px;
                }

                a.button:hover {
                    background-color: #0056b3;
                }

                .actions a {
                    margin-right: 10px;
                    color: #007bff;
                    text-decoration: none;
                }

                .actions a:hover {
                    text-decoration: underline;
                }

                @media (max-width: 768px) {
                    header h1 {
                        font-size: 20px;
                    }

                    .container {
                        padding: 20px;
                    }

                    table, th, td {
                        font-size: 14px;
                    }
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
                            margin-left: 220px;
                            margin-top: 50px;
                            padding: 20px;
                            flex-grow: 1;
                        }

                        h1 {
                            margin-top: 0;
                        }

        /* ---------- Main ---------- */
        main {
            flex-grow: 1;
            margin-left: 220px;
            padding: 90px 30px 30px;       /* account for fixed header */
            max-width: 1200px;
        }
        main h2 { margin: 0 0 25px; color: #343a40; }

        /* ---------- User Grid ---------- */
        .user-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
        }
        .user-card {
            flex: 0 0 calc(33.333% - 25px);
            background: linear-gradient(135deg, #e6e6fa, #d0f0fd);
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 20px;
            display: flex;
            align-items: center;
            transition: transform .2s, box-shadow .2s;
        }
            .user-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 8px 18px rgba(0,0,0,0.1);
            }
        .user-card img {
            width: 72px; height: 72px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 18px;
            border: 3px solid var(--primary);
        }
        .user-info h3 {
            margin: 0 0 6px;
            font-size: 18px;
            font-weight: 600;
        }
        .user-info p {
            margin: 0;
            color: var(--gray-600);
            font-size: 14px;
        }

        /* ---------- Responsive ---------- */
        @media (max-width: 992px) {
            .user-card { flex: 0 0 calc(50% - 25px); }
        }
        @media (max-width: 600px) {
            header { left: 0; }
            .sidebar { width: 180px; }
            main { margin-left: 180px; padding: 90px 20px 30px; }
            .user-card { flex: 0 0 100%; }
        }
    </style>
</head>
<body>

<!-- ---------- Sidebar ---------- -->
<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="view_users.php" class="active">👥 View Users</a>
    <a href="add_movie.php?type=movie">➕ Add Movie</a>
    <a href="add_theater.php">🏛 Add Theater</a>
    <a href="add_museum.php?type=museum">🖼 Add Museum</a>
    <a href="add_park.php?type=park">🌳 Add Park</a>
    <a href="view_bookings.php">📄 View Bookings</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<!-- ---------- Header ---------- -->
<header>
    <h1>SmartTicket • Users</h1>
    <a href="logout.php" class="logout">Logout</a>
</header>

<!-- ---------- Main Content ---------- -->
<main>
    <h2>Registered Users (<?= count($users) ?>)</h2>

    <?php if (!$users): ?>
        <p>No users found.</p>
    <?php else: ?>
        <div class="user-grid">
            <?php foreach ($users as $user): ?>
                <div class="user-card">
                    <?php
                        // Graceful fallback if photo is missing
                        $photoPath = !empty($user['photo']) && file_exists('../' . $user['photo'])
                            ? '../' . htmlspecialchars($user['photo'])
                            : '../assets/default-avatar.png'; // or wherever your fallback avatar is stored

                    ?>
                    <img src="<?= $photoPath ?>" alt="<?= htmlspecialchars($user['name']) ?> photo">
                    <div class="user-info">
                        <h3><?= htmlspecialchars($user['name']) ?></h3>
                        <p>ID: #<?= htmlspecialchars($user['id']) ?></p>
                        <p>📞 <?= htmlspecialchars($user['phone']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

</body>
</html>
