<?php
session_start();
require_once '../db_connect.php';

// Check admin login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch all items using $conn (PDO)
$movies  = $conn->query("SELECT * FROM movies  ORDER BY created_at DESC")
                ->fetchAll(PDO::FETCH_ASSOC);

$museums = $conn->query("SELECT * FROM museums ORDER BY created_at DESC")
                ->fetchAll(PDO::FETCH_ASSOC);

$parks   = $conn->query("SELECT * FROM parks   ORDER BY created_at DESC")
                ->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | SmartTicket</title>
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
            background: linear-gradient(135deg, #ffffff, #ffcce5);
            padding-top: 30px;
            max-width: 1500px;
            margin: auto;
        }

        h2 {
            margin-top: 50px;
            margin-bottom: 10px;
            color: #343a40;
        }

        table {
            background: #ffffff;
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
            background-color: white;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
        }

        th {
                    border: 1px solid #dee2e6;
                    padding: 10px 12px;
                    text-align: left;
                    color: #ffffff;
                }
         td {
            border: 1px solid #dee2e6;
            padding: 10px 12px;
            text-align: left;
            color: #000000;
        }

        th {
            background: #000000;
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
            color: #0000000;
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

    </style>
</head>
<body>

<header>
    <h1>SmartTicket Admin Dashboard</h1>
    <a href="admin_login.php" class="logout">Logout</a>
</header>
<div class="sidebar">
    <h2>Admin Panel</h2>
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
    <h1>Welcome, Admin</h1>
    <p>Use the sidebar to manage movies, theaters, parks, and museums.</p>
</div>



<div class="container">

    <h2>🎬 Movies <a href="add_movie_theater.php?type=movie" class="button">Add Movie</a></h2>
    <table>
        <tr>
            <th>ID</th><th>Title</th><th>Duration (min)</th><th>Available Tickets</th><th>Price</th><th>Actions</th>
        </tr>
        <?php foreach ($movies as $movie): ?>
        <tr>
            <td><?= htmlspecialchars($movie['movie_id']) ?></td>
            <td><?= htmlspecialchars($movie['title']) ?></td>
            <td><?= htmlspecialchars($movie['duration_minutes']) ?></td>
            <td><?= htmlspecialchars($movie['available_tickets']) ?></td>
            <td><?= htmlspecialchars($movie['price']) ?></td>
            <td class="actions">
                <a href="edit_item.php?type=movie&id=<?= $movie['movie_id'] ?>">Edit</a>
                <a href="delete_item.php?type=movie&id=<?= $movie['movie_id'] ?>" onclick="return confirm('Delete this movie?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

   <h2>🏛️ Museums <a href="add_museum.php?type=museum" class="button">Add Museum</a></h2>
   <table>
       <tr>
           <th>ID</th>
           <th>Name</th>
           <th>Location</th>
           <th>Available Tickets</th>
           <th>Price</th>
           <th>Address</th>
           <th>Opening Hours</th>
           <th>Contact</th>
           <th>Actions</th>
       </tr>
       <?php foreach ($museums as $museum): ?>
       <tr>
           <td><?= htmlspecialchars($museum['museum_id']) ?></td>
           <td><?= htmlspecialchars($museum['name']) ?></td>
           <td><?= htmlspecialchars($museum['location']) ?></td>
           <td><?= htmlspecialchars($museum['available_tickets']) ?></td>
           <td><?= htmlspecialchars($museum['price']) ?></td>
           <td><?= htmlspecialchars($museum['address']) ?></td>
           <td><?= htmlspecialchars($museum['opening_hours']) ?></td>
           <td><?= htmlspecialchars($museum['contact']) ?></td>
           <td class="actions">
               <a href="edit_museums.php?id=<?= $museum['museum_id'] ?>">Edit</a>
               <a href="delete_item.php?type=museum&id=<?= $museum['museum_id'] ?>" onclick="return confirm('Delete this museum?')">Delete</a>
           </td>
       </tr>
       <?php endforeach; ?>
   </table>

    <h2>🌳 Parks <a href="add_park.php?type=park" class="button">Add Park</a></h2>
    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Location</th><th>Description</th><th>Photo</th><th>Actions</th>
        </tr>
        <?php foreach ($parks as $park): ?>
        <tr>
            <td><?= htmlspecialchars($park['park_id']) ?></td>
            <td><?= htmlspecialchars($park['name']) ?></td>
            <td><?= htmlspecialchars($park['location']) ?></td>
            <td><?= htmlspecialchars($park['description']) ?></td>
            <td><?= htmlspecialchars($park['photo']) ?></td>
            <td class="actions">
                <a href="edit_park.php?type=park&id=<?= $park['park_id'] ?>">Edit</a>
                <a href="delete_item.php?type=park&id=<?= $park['park_id'] ?>" onclick="return confirm('Delete this park?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

</div>

</body>
</html>
