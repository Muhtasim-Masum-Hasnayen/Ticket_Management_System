<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

// Assume you also have user ID and photo stored in session
$user_id = $_SESSION['id'] ?? 1; // fallback id if missing
$user_name = $_SESSION['name'];
$user_phone = $_SESSION['phone'] ?? 'N/A'; // fallback phone
$user_photo = $_SESSION['photo'] ?? 'assets/user.jpg'; // fallback photo path
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

    body {
      display: flex;
      background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
      color: #f5f5f5;
      min-height: 100vh;
    }

    .sidebar {
      width: 240px;
      background: linear-gradient(160deg, #1a1f2b, #11131a);
      padding: 20px;
      display: flex;
      flex-direction: column;
      gap: 20px;
      box-shadow: 4px 0 12px rgba(0, 0, 0, 0.4);
    }

    .sidebar h1 {
      color: #ffd369;
      font-size: 24px;
      margin-bottom: 30px;
      text-shadow: 0 2px 4px rgba(255, 211, 105, 0.3);
    }

    .sidebar a {
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

    .sidebar a:hover {
      background: #ffd369;
      color: #1a1f2b;
      box-shadow: 0 4px 10px rgba(255, 211, 105, 0.4);
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
      color: #ffffff;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
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
      border: 2px solid #ffd369;
      box-shadow: 0 0 10px #ffd36980;
      object-fit: cover;
    }

    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .card {
      background: rgba(255, 255, 255, 0.05);
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
      transition: 0.3s ease-in-out;
      backdrop-filter: blur(6px);
    }

    .card:hover {
      background: rgba(255, 255, 255, 0.1);
      transform: translateY(-5px);
      box-shadow: 0 6px 20px rgba(255, 211, 105, 0.2);
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
      background: rgba(255, 255, 255, 0.08);
      border-left: 5px solid #ffd369;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(255, 211, 105, 0.2);
    }

    .upcoming-ticket h3 {
      margin-bottom: 10px;
      color: #ffffff;
    }

    .explore-section h2,
    .offer-section h2 {
      margin-top: 40px;
      margin-bottom: 15px;
      font-size: 20px;
      border-bottom: 2px solid #ffd369;
      padding-bottom: 5px;
      color: #ffffff;
    }

    .explore-grid,
    .offer-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 15px;
    }

    .explore-item,
    .offer-item {
      background: rgba(255, 255, 255, 0.05);
      padding: 15px;
      border-radius: 10px;
      text-align: center;
      transition: 0.3s ease;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .explore-item:hover,
    .offer-item:hover {
      background: rgba(255, 255, 255, 0.1);
      transform: scale(1.03);
      box-shadow: 0 6px 20px rgba(255, 211, 105, 0.2);
    }

    .explore-item img,
    .offer-item img {
      width: 100%;
      height: 100px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 8px;
    }

    .explore-item h4,
    .offer-item h4 {
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
    <a href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
      <i class="fas fa-user"></i> Profile
    </a>
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
        <img src="<?php echo htmlspecialchars($user_photo); ?>" alt="User Profile Photo" />
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
        <p>1 park visit planned</p>
      </div>
      <div class="card">
        <i class="fas fa-ticket-alt"></i>
        <h3>🎟️ Tickets</h3>
        <p>3 tickets booked</p>
      </div>
    </section>

    <section class="upcoming-ticket">
      <h3>🎉 Upcoming Ticket</h3>
      <p><strong>Event:</strong> Rock Music Festival</p>
      <p><strong>Date:</strong> June 15, 2025</p>
      <p><strong>Venue:</strong> Central Park</p>
      <p><strong>Seat:</strong> Section B, Row 12, Seat 7</p>
    </section>

    <section class="explore-section">
      <h2>Explore Movies</h2>
      <div class="explore-grid">
        <div class="explore-item">
          <img src="assets/movie1.jpg" alt="Movie 1" />
          <h4>Action Thriller</h4>
        </div>
        <div class="explore-item">
          <img src="assets/movie2.jpg" alt="Movie 2" />
          <h4>Romantic Comedy</h4>
        </div>
        <div class="explore-item">
          <img src="assets/movie3.jpg" alt="Movie 3" />
          <h4>Science Fiction</h4>
        </div>
      </div>
    </section>

    <section class="offer-section">
      <h2>Special Offers</h2>
      <div class="offer-grid">
        <div class="offer-item">
          <img src="assets/offer1.jpg" alt="Offer 1" />
          <h4>20% off on Rock Festival</h4>
        </div>
        <div class="offer-item">
          <img src="assets/offer2.jpg" alt="Offer 2" />
          <h4>Buy 1 Get 1 Free</h4>
        </div>
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
</body>
</html>
