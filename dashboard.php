<?php
session_start();

require_once 'db_connect.php';

// Redirect to login if not logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Assume you also have user ID and photo stored in session
$user_id = $_SESSION['id'];  // fallback id if missing

$stmt = $conn->prepare("SELECT name, phone, photo FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


$user_name = $user['name'];
$user_phone = !empty($user['phone']) ? $user['phone'] : 'N/A';
$user_photo = !empty($user['photo']) ? $user['photo'] : 'assets/user.jpg';


// Count tickets per table
function getCount($conn, $table, $user_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM $table WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchColumn();
}

$cnt_movies  = getCount($conn, 'bookings', $user_id);
$cnt_museums = getCount($conn, 'museum_bookings', $user_id);
$cnt_parks   = getCount($conn, 'park_bookings', $user_id);
$cnt_tickets = $cnt_movies + $cnt_museums + $cnt_parks;

// Get next upcoming ticket
$stmtt = $conn->prepare("
    SELECT
        'Movie' AS type,
        b.booking_time,
        m.title AS event,
        CONCAT(t.name, ' - ', t.location) AS location
    FROM bookings b
    JOIN movie_showtimes s ON b.showtime_id = s.showtime_id
    JOIN movies m ON s.movie_id = m.movie_id
    JOIN theaters t ON s.theater_id = t.theater_id
    WHERE b.user_id = ? AND b.booking_time >= NOW()

    UNION

    SELECT
        'Museum' AS type,
        mb.booking_time,
        mu.name AS event,
        mu.address AS location
    FROM museum_bookings mb
    JOIN museums mu ON mb.museum_id = mu.museum_id
    WHERE mb.user_id = ? AND mb.booking_time >= NOW()

    UNION

    SELECT
        'Park' AS type,
        pb.booking_time,
        p.name AS event,
        p.location
    FROM park_bookings pb
    JOIN parks p ON pb.park_id = p.park_id
    WHERE pb.user_id = ? AND pb.booking_time >= NOW()

    ORDER BY booking_time ASC
    LIMIT 1
");
$stmtt->execute([$user_id, $user_id, $user_id]);
$upcoming = $stmtt->fetch(PDO::FETCH_ASSOC);



// Latest movies in showtimes (group by movie)
$movieStmt = $conn->prepare("
  SELECT m.movie_id, m.title, m.genre, m.duration_minutes, m.photo, COUNT(DISTINCT s.theater_id) AS theater_count
  FROM movies m
  JOIN movie_showtimes s ON m.movie_id = s.movie_id
  GROUP BY m.movie_id
  ORDER BY MAX(s.created_at) DESC
  LIMIT 4
");
$movieStmt->execute();
$latestMovies = $movieStmt->fetchAll(PDO::FETCH_ASSOC);

// Latest museums
$museumStmt = $conn->prepare("
  SELECT museum_id, name, photo
  FROM museums
  ORDER BY created_at DESC
  LIMIT 3
");
$museumStmt->execute();
$latestMuseums = $museumStmt->fetchAll(PDO::FETCH_ASSOC);

// Latest parks
$parkStmt = $conn->prepare("
  SELECT park_id, name, photo
  FROM parks
  ORDER BY created_at DESC
  LIMIT 3
");
$parkStmt->execute();
$latestParks = $parkStmt->fetchAll(PDO::FETCH_ASSOC);



$stmtt = $conn->prepare("
  SELECT 'Movie' AS type, mb.booking_time, m.title AS event, t.name AS location
  FROM bookings mb
  JOIN movie_showtimes st ON mb.showtime_id = st.showtime_id
  JOIN movies m ON st.movie_id = m.movie_id
  JOIN theaters t ON st.theater_id = t.theater_id
  WHERE mb.user_id = ?

  UNION

  SELECT 'Museum', mb.booking_time, m.name, m.address
  FROM museum_bookings mb
  JOIN museums m ON mb.museum_id = m.museum_id
  WHERE mb.user_id = ?

  UNION

  SELECT 'Park', pb.booking_time, p.name, p.location
  FROM park_bookings pb
  JOIN parks p ON pb.park_id = p.park_id
  WHERE pb.user_id = ?

  ORDER BY booking_time DESC LIMIT 1
");
$stmtt->execute([$user_id, $user_id, $user_id]);
$latest_booking = $stmtt->fetch(PDO::FETCH_ASSOC);




?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>SmartTicket | Dashboard</title>

  <!-- Bootstrap CSS for modal & responsive -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Inter', sans-serif;
    }

      /* DARK MODE - your original style */
      body.dark-mode {
        display: flex;
        background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
        color: #f5f5f5;
        min-height: 100vh;
        transition: background 0.4s ease, color 0.4s ease;
      }

      body.dark-mode .sidebar {
        width: 240px;
        background: linear-gradient(160deg, #1a1f2b, #11131a);
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        box-shadow: 4px 0 12px rgba(0, 0, 0, 0.4);
      }

      body.dark-mode .sidebar h1 {
        color: #ffd369;
        font-size: 24px;
        margin-bottom: 30px;
        text-shadow: 0 2px 4px rgba(255, 211, 105, 0.3);
      }

      body.dark-mode .sidebar a {
        color: #fff;
        text-decoration: none;
        font-weight: 500;
        padding: 10px 15px;
        border-radius: 8px;
        transition: 0.3s ease;
        background: rgba(255, 255, 255, 0.03);
        display: flex;
        align-items: center;
        gap: 10px;
      }

      body.dark-mode .sidebar a:hover {
        background: #ffd369;
        color: #1a1f2b;
        box-shadow: 0 4px 10px rgba(255, 211, 105, 0.4);
      }

      body.dark-mode .main-content {
        flex-grow: 1;
        padding: 20px;
      }

      body.dark-mode header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
      }

      body.dark-mode header h2 {
        font-size: 24px;
        font-weight: 700;
        color: #ffffff;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
      }

      body.dark-mode header .profile {
        display: flex;
        align-items: center;
        gap: 10px;
      }

      body.dark-mode header .profile img {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        border: 2px solid #ffd369;
        box-shadow: 0 0 10px #ffd36980;
        object-fit: cover;
      }

      body.dark-mode .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
      }

      body.dark-mode .card {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
        transition: 0.3s ease-in-out;
        backdrop-filter: blur(6px);
      }

      body.dark-mode .card:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(255, 211, 105, 0.2);
      }

      body.dark-mode .card i {
        font-size: 28px;
        margin-bottom: 10px;
        color: #ffd369;
      }

      body.dark-mode .card h3 {
        font-size: 18px;
        margin-bottom: 5px;
      }

      body.dark-mode .card p {
        font-size: 14px;
        color: #ccc;
      }

      body.dark-mode .upcoming-ticket {
        margin: 40px 0;
        padding: 20px;
        background: rgba(255, 255, 255, 0.08);
        border-left: 5px solid #ffd369;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(255, 211, 105, 0.2);
      }

      body.dark-mode .upcoming-ticket h3 {
        margin-bottom: 10px;
        color: #ffffff;
      }

      body.dark-mode .explore-section h2,
      body.dark-mode .offer-section h2 {
        margin-top: 60px;
                margin-bottom: 40px;
                font-size: 32px;
                font-weight: 800;
                color: #ffffff;
                background: linear-gradient(to left, #b33030, #3b2a84);
                border-bottom: 2px solid #007acc;
                padding: 20px 30px;
                width: 100%;
                display: block;
                box-sizing: border-box;
                text-align: center; /* ✅ Center the text */
      }

      body.dark-mode .explore-grid,
      body.dark-mode .offer-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 80px;
      }

      body.dark-mode .explore-item,
      body.dark-mode .offer-item {
        background: rgba(255, 255, 255, 0.05);
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        transition: 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
      }

      body.dark-mode .explore-item:hover,
      body.dark-mode .offer-item:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: scale(1.03);
        box-shadow: 0 6px 20px rgba(255, 211, 105, 0.2);
      }

      body.dark-mode .explore-item img,
      body.dark-mode .offer-item img {
        width: 95%;
        height: 350px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 8px;
      }

      body.dark-mode .explore-item h4,
      body.dark-mode .offer-item h4 {
        font-size: 14px;
        color: #f0f0f0;
      }

      body.dark-mode footer {
        margin-top: 50px;
        text-align: center;
        font-size: 13px;
        color: #aaa;
      }


      /* LIGHT MODE - your requested lighter theme */
      body.light-mode {
        display: flex;
        background: linear-gradient(to right, #ff6b6b, #6c5ce7);
        color: #333;
        min-height: 100vh;
        transition: background 0.4s ease, color 0.4s ease;
      }

      body.light-mode .sidebar {
        width: 240px;
        background: linear-gradient(to right, #ffecd2, #fcb69f);
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        box-shadow: 4px 0 12px rgba(0, 0, 0, 0.05);
      }

      body.light-mode .sidebar h1 {
        color: #007acc;
        font-size: 24px;
        margin-bottom: 30px;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
      }

      body.light-mode .sidebar a {
        color: #333;
        text-decoration: none;
        font-weight: 500;
        padding: 10px 15px;
        border-radius: 8px;
        transition: 0.3s ease;
        background: rgba(0, 122, 204, 0.05);
        display: flex;
        align-items: center;
        gap: 10px;
      }

      body.light-mode .sidebar a:hover {
        background: #007acc;
        color: #fff;
        box-shadow: 0 4px 10px rgba(0, 122, 204, 0.2);
      }

      body.light-mode .main-content {
        flex-grow: 1;
        padding: 20px;
      }

      body.light-mode header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
      }

      body.light-mode header h2 {
        font-size: 24px;
        font-weight: 700;
        color: #222;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
      }

      body.light-mode header .profile {
        display: flex;
        align-items: center;
        gap: 10px;
      }

      body.light-mode header .profile img {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        border: 2px solid #007acc;
        box-shadow: 0 0 8px #007acc88;
        object-fit: cover;
      }

      body.light-mode .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
      }

      body.light-mode .card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        transition: 0.3s ease-in-out;
      }

      body.light-mode .card:hover {
        background: #f0f8ff;
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0, 122, 204, 0.1);
      }

      body.light-mode .card i {
        font-size: 28px;
        margin-bottom: 10px;
        color: #007acc;
      }

      body.light-mode .card h3 {
        font-size: 18px;
        margin-bottom: 5px;
      }

      body.light-mode .card p {
        font-size: 14px;
        color: #555;
      }

      body.light-mode .upcoming-ticket {
        margin: 40px 0;
        padding: 20px;
        background: #dbe9ff;
        border-left: 5px solid #007acc;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 122, 204, 0.1);
      }

      body.light-mode .upcoming-ticket h3 {
        margin-bottom: 10px;
        color: #003366;
      }

      body.light-mode .explore-section h2,
      body.light-mode .offer-section h2 {
        margin-top: 60px;
        margin-bottom: 40px;
        font-size: 32px;
        font-weight: 800;
        color: #ffffff;
        background: linear-gradient(to left, #b33030, #3b2a84);
        border-bottom: 2px solid #007acc;
        padding: 20px 30px;
        width: 100%;
        display: block;
        box-sizing: border-box;
        text-align: center; /* ✅ Center the text */
      }



      body.light-mode .explore-grid,
      body.light-mode .offer-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 2fr));
        gap: 80px;
      }

      body.light-mode .explore-item,
      body.light-mode .offer-item {
        background: #f8faff;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        transition: 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      }

      body.light-mode .explore-item:hover,
      body.light-mode .offer-item:hover {
        background: #e1f0ff;
        transform: scale(1.03);
        box-shadow: 0 6px 20px rgba(0, 122, 204, 0.1);
      }

      body.light-mode .explore-item img,
      body.light-mode .offer-item img {
        width: 95%;
        height: 350px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 8px;
      }

      body.light-mode .explore-item h4,
      body.light-mode .offer-item h4 {
        font-size: 14px;
        color: #004080;
      }

      body.light-mode footer {
        margin-top: 50px;
        text-align: center;
        font-size: 13px;
        color: #ffffff;
      }

     /* Toggle Button Style */
     #mode-toggle {
       cursor: pointer;
       background: transparent;
       border: 2px solid #ffd369;
       padding: 6px 12px;
       border-radius: 20px;
       color: #ffd369;
       font-weight: 600;
       transition: all 0.3s ease;
       margin-left: auto; /* Move to right if inside flex */
     }

     /* Light Mode Style */
     body.light-mode #mode-toggle {
       border-color: #ffffff;
       color: #ffffff;
     }

     /* Dark Mode Hover */
     #mode-toggle:hover {
       background: #ffd369;
       color: #1a1f2b;
     }

     /* Light Mode Hover */
     body.light-mode #mode-toggle:hover {
       background: #007acc;
       color: #fff;
     }


  </style>
</head>

<body class="light-mode">
  <div class="sidebar">
    <h1>🎟 SmartTicket</h1>
    <a href="u_profile.php" >
      <i class="fas fa-user"></i> Profile
    </a>
    <a href="dashboard.php"><i class="fas fa-house"></i> Dashboard</a>
    <a href="ticket/Movies.php"><i class="fas fa-film"></i> Movies</a>
    <a href="Museums.php"><i class="fas fa-landmark"></i> Museums</a>
    <a href="u_parks.php"><i class="fas fa-tree"></i> Parks</a>
    <a href="my_tickets.php">🎟️ My Tickets</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <div class="main-content">
    <header>
      <h2>👋 Welcome, <span style="color:#ffd369"><?php echo htmlspecialchars($user_name); ?></span></h2>
            <button id="mode-toggle" aria-label="Toggle Dark/Light Mode">Dark Mode</button>

      <div class="profile">
        <img src="<?php echo htmlspecialchars($user_photo); ?>" alt="User Profile Photo" />
      </div>
    </header>

    <section class="card-grid">
      <div class="card">
        <i class="fas fa-film"></i>
        <h3>🎬 Movies</h3>
        <p>Purchase <?= $cnt_movies ?> <?= $cnt_movies !== "1" ? "s" : "" ?> Tickets of Movies</p>
      </div>
      <div class="card">
        <i class="fas fa-landmark"></i>
        <h3>🏛️ Museums</h3>
        <p>Purchase <?= $cnt_museums ?> Ticket'<?= $cnt_museums !== "1" ? " s" : " visit" ?> of Museums</p>
      </div>
      <div class="card">
        <i class="fas fa-tree"></i>
        <h3>🏞️ Parks</h3>
        <p>Purchase <?= $cnt_parks ?> <?= $cnt_parks !== "1" ? " s" : " visit" ?>Tickets of Parks</p>
      </div>
      <div class="card">
        <i class="fas fa-ticket-alt"></i>
        <h3>🎟️ Tickets</h3>
        <p><?= $cnt_tickets ?> total ticket<?= $cnt_tickets !== "1" ? "s" : "" ?></p>
      </div>
    </section>

    <section class="upcoming-ticket">
      <h3>🎟️ Most Recent Ticket</h3>
      <?php if ($latest_booking): ?>
        <p><strong>Type:</strong> <?= htmlspecialchars($latest_booking['type']) ?></p>
        <p><strong>Event:</strong> <?= htmlspecialchars($latest_booking['event']) ?></p>
        <p><strong>Purchase On:</strong> <?= date("F j, Y, g:i a", strtotime($latest_booking['booking_time'])) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($latest_booking['location']) ?></p>
      <?php else: ?>
        <p>No tickets booked yet.</p>
      <?php endif; ?>
    </section>



    <section class="explore-section">
      <h2>🎥 Explore Movies</h2>
      <div class="explore-grid">
        <?php foreach ($latestMovies as $movie): ?>
          <div class="explore-item">
           <img src="<?php echo 'admin/' . htmlspecialchars($movie['photo']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>" />

            <h4><?php echo htmlspecialchars($movie['title']); ?></h4>
            <p style="font-size: 14px; color: #999CA3;">
              <strong>Genre:</strong> <?php echo htmlspecialchars($movie['genre']); ?> &nbsp; | &nbsp;
              <strong>Duration:</strong> <?php echo htmlspecialchars($movie['duration_minutes']); ?> mins &nbsp; | &nbsp;
              <strong>Theaters:</strong> <?php echo $movie['theater_count']; ?>
            </p>

          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="explore-section">
      <h2>🏛️ Explore Museums</h2>
      <div class="explore-grid">
        <?php foreach ($latestMuseums as $museum): ?>
          <div class="explore-item">
            <img src="<?php echo 'admin/'. htmlspecialchars($museum['photo']); ?>" alt="<?php echo htmlspecialchars($museum['name']); ?>" />
            <h4><?php echo htmlspecialchars($museum['name']); ?></h4>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="explore-section">
      <h2>🏞️ Explore Parks</h2>
      <div class="explore-grid">
        <?php foreach ($latestParks as $park): ?>
          <div class="explore-item">
            <img src="<?php echo 'admin/'.htmlspecialchars($park['photo']); ?>" alt="<?php echo htmlspecialchars($park['name']); ?>" />
            <h4><?php echo htmlspecialchars($park['name']); ?></h4>
          </div>
        <?php endforeach; ?>
      </div>
    </section>



    <footer>
      &copy; 2025 SmartTicket. All rights reserved.
    </footer>
  </div>

  <!-- Profile Modal -->
  <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="background:#11131a; color:#fff; border-radius: 15px;">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="profileModalLabel">Your Profile</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="update_profile.php" method="post" enctype="multipart/form-data" class="px-3 pb-3">
          <div class="text-center mb-3">
            <img src="<?php echo htmlspecialchars($user_photo); ?>" alt="Profile Picture" id="profilePreview" style="width: 120px; height: 120px; object-fit: cover; border-radius: 50%; border: 2px solid #ffd369; box-shadow: 0 0 12px #ffd36988;" />
          </div>
          <div class="mb-3">
            <label for="profileName" class="form-label">Name</label>
            <input type="text" class="form-control" id="profileName" name="name" value="<?php echo htmlspecialchars($user_name); ?>" required />
          </div>
          <div class="mb-3">
            <label for="profilePhone" class="form-label">Phone</label>
            <input type="tel" class="form-control" id="profilePhone" name="phone" value="<?php echo htmlspecialchars($user_phone); ?>" required />
          </div>
          <div class="mb-3">
            <label for="profilePhoto" class="form-label">Profile Picture</label>
            <input type="file" class="form-control" id="profilePhoto" name="photo" accept="image/*" onchange="previewImage(event)" />
          </div>
          <input type="hidden" name="id" value="<?php echo htmlspecialchars($user_id); ?>" />
          <button type="submit" class="btn btn-warning w-100">Update Profile</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Preview profile picture on file input change
    function previewImage(event) {
      const input = event.target;
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('profilePreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
  <!-- FontAwesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script>
      const toggleBtn = document.getElementById('mode-toggle');
      const body = document.body;

      toggleBtn.addEventListener('click', () => {
        if(body.classList.contains('dark-mode')){
          body.classList.remove('dark-mode');
          body.classList.add('light-mode');
          toggleBtn.textContent = 'Dark Mode';
        } else {
          body.classList.remove('light-mode');
          body.classList.add('dark-mode');
          toggleBtn.textContent = 'Light Mode';
        }
      });
    </script>
</body>
</html>


