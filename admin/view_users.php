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
            --danger: #dc3545;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-600: #6c757d;
            --sidebar-bg: #333;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--gray-100);
            color: #212529;
            display: flex;
            min-height: 100vh;
        }

        /* ---------- Header ---------- */
        header {
            position: fixed;
            top: 0;
            left: 220px;                    /*  = sidebar width  */
            right: 0;
            height: 60px;
            background-color: #343a40;
            color: #fff;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 100;
        }
        header h1 { font-size: 22px; margin: 0; }
        header .logout {
            background: var(--danger);
            color: #fff;
            padding: 8px 14px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }
        header .logout:hover { background: #c82333; }

        /* ---------- Sidebar ---------- */
        .sidebar {
            position: fixed;
            left: 0; top: 0;
            width: 220px;
            height: 100%;
            background: var(--sidebar-bg);
            color: #fff;
            padding: 20px 0;
            overflow-y: auto;
        }
        .sidebar h2 {
            font-size: 20px;
            margin: 0 0 25px;
            text-align: center;
        }
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #fff;
            text-decoration: none;
            font-size: 15px;
        }
        .sidebar a:hover,
        .sidebar a.active { background: #575757; }

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
            background: #fff;
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
