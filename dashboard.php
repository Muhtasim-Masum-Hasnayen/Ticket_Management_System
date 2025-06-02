<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

$user_name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SmartTicket | Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Inter', sans-serif;
    }
    body {
      display: flex;
      background: linear-gradient(135deg, #1c1e26, #3e4164);
      color: white;
      min-height: 100vh;
    }
    .sidebar {
      width: 240px;
      background: #11131a;
      padding: 20px;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }
    .sidebar h1 {
      color: #ffd369;
      font-size: 24px;
      margin-bottom: 30px;
    }
    .sidebar a {
      color: #fff;
      text-decoration: none;
      font-weight: 500;
      padding: 10px 15px;
      border-radius: 8px;
      transition: 0.3s;
    }
    .sidebar a:hover {
      background: #ffd369;
      color: #11131a;
    }
    .main-content {
      flex-grow: 1;
      padding: 20px;
    }
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }
    header h2 {
      font-size: 24px;
      font-weight: 700;
    }
    header .profile {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    header .profile img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
    }
    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }
    .card {
      background: rgba(255,255,255,0.05);
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      transition: 0.3s;
    }
    .card:hover {
      background: rgba(255,255,255,0.1);
    }
    .card i {
      font-size: 28px;
      margin-bottom: 10px;
      color: #ffd369;
    }
    .card h3 {
      font-size: 18px;
      margin-bottom: 5px;
    }
    .card p {
      font-size: 14px;
      color: #ccc;
    }
    .upcoming-ticket {
      margin: 40px 0;
      padding: 20px;
      background: rgba(255,255,255,0.08);
      border-left: 5px solid #ffd369;
      border-radius: 10px;
    }
    .upcoming-ticket h3 {
      margin-bottom: 10px;
    }
    .explore-section h2, .offer-section h2 {
      margin-top: 40px;
      margin-bottom: 15px;
      font-size: 20px;
      border-bottom: 2px solid #ffd369;
      padding-bottom: 5px;
    }
    .explore-grid, .offer-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 15px;
    }
    .explore-item, .offer-item {
      background: rgba(255,255,255,0.05);
      padding: 15px;
      border-radius: 10px;
      text-align: center;
      transition: 0.3s;
    }
    .explore-item:hover, .offer-item:hover {
      background: rgba(255,255,255,0.1);
    }
    .explore-item img, .offer-item img {
      width: 100%;
      height: 100px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 8px;
    }
    .explore-item h4, .offer-item h4 {
      font-size: 14px;
      color: #fff;
    }
    footer {
      margin-top: 50px;
      text-align: center;
      font-size: 13px;
      color: #aaa;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <h1>🎟 SmartTicket</h1>
    <a href="#"><i class="fas fa-house"></i> Dashboard</a>
    <a href="#"><i class="fas fa-film"></i> Movies</a>
    <a href="#"><i class="fas fa-landmark"></i> Museums</a>
    <a href="#"><i class="fas fa-tree"></i> Parks</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
  </div>

  <div class="main-content">
    <header>
      <h2>👋 Welcome, <span style="color:#ffd369"><?php echo htmlspecialchars($user_name); ?></span></h2>
      <div class="profile">
        <img src="assets/user.jpg" alt="User">
      </div>
    </header>

    <section class="card-grid">
      <div class="card">
        <i class="fas fa-film"></i>
        <h3>🎬 Movies</h3>
        <p>You have 1 show tonight</p>
      </div>
      <div class="card">
        <i class="fas fa-landmark"></i>
        <h3>🏛️ Museums</h3>
        <p>2 museum visits this week</p>
      </div>
      <div class="card">
        <i class="fas fa-tree"></i>
        <h3>🏞️ Parks</h3>
        <p>Eco Park visit on Saturday</p>
      </div>
    </section>

    <section class="upcoming-ticket">
      <h3>🎟️ Next Ticket: <span style="color:#ffd369">"Jongli" at Star Cineplex</span></h3>
      <p>🗓️ June 3, 6:30 PM | 📍 Bashundhara | 🚪 Seat: C5 | ⏱️ 3 hours left</p>
      <a href="#" style="color: #ffd369; text-decoration: underline;">Open Ticket</a>
    </section>

    <section class="explore-section">
      <h2>🌟 Explore New Bookings</h2>
      <div class="explore-grid">
        <div class="explore-item">
          <img src="assets/movies.jpg" alt="Movies">
          <h4>Trending Movies</h4>
        </div>
        <div class="explore-item">
          <img src="assets/museum.jpg" alt="Museums">
          <h4>Featured Museums</h4>
        </div>
        <div class="explore-item">
          <img src="assets/park.jpg" alt="Parks">
          <h4>Top Parks</h4>
        </div>
      </div>
    </section>

    <section class="offer-section">
      <h2>💸 Offers & Discounts</h2>
      <div class="offer-grid">
        <div class="offer-item">
          <img src="assets/bkash_offer.jpg" alt="Bkash">
          <h4>Bkash Cashback</h4>
        </div>
        <div class="offer-item">
          <img src="assets/combo.jpg" alt="Combo">
          <h4>Combo Deals: Book 2 get 1</h4>
        </div>
      </div>
    </section>

    <footer>
      &copy; 2025 SmartTicket. All rights reserved.
    </footer>
  </div>
</body>
</html>