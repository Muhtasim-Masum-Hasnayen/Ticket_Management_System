<?php
session_start();
include 'db_connect.php';

$user_id = $_SESSION['id'];
$success = $error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $photo = '';

    if (!empty($_FILES["photo"]["name"])) {
        $target_dir = "uploads/";
        $filename = basename($_FILES["photo"]["name"]);
        $photo = $target_dir . $filename;
        move_uploaded_file($_FILES["photo"]["tmp_name"], $photo);
    }

    if (!empty($photo)) {
        $sql = "UPDATE users SET name=?, phone=?, photo=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $phone, $photo, $user_id]);
    } else {
        $sql = "UPDATE users SET name=?, phone=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $phone, $user_id]);
    }

    if ($stmt) {
        $success = "<p class='success'>Profile saved successfully!</p>";
    } else {
        $error = "<p class='error'>Error: Could not update profile.</p>";
    }
}

$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$data = $result->fetch();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SmartTicket | User Profile</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    /* Paste your entire dual-mode CSS here (as you shared) */

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
            margin-top: 40px;
            margin-bottom: 15px;
            font-size: 20px;
            border-bottom: 2px solid #ffd369;
            padding-bottom: 5px;
            color: #ffffff;
          }

          body.dark-mode .explore-grid,
          body.dark-mode .offer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
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
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 8px;
          }

          body.dark-mode .explore-item h4,
          body.dark-mode .offer-item h4 {
            font-size: 14px;
            color: #fff;
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
            margin-top: 40px;
            margin-bottom: 15px;
            font-size: 20px;
            border-bottom: 2px solid #007acc;
            padding-bottom: 5px;
            color: #ffffff;
          }

          body.light-mode .explore-grid,
          body.light-mode .offer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
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
            width: 100%;
            height: 100px;
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
          }

          body.light-mode #mode-toggle {
            border-color: #ffffff;
            color: #ffffff;
          }

          #mode-toggle:hover {
            background: #ffd369;
            color: #1a1f2b;
          }

          body.light-mode #mode-toggle:hover {
            background: #007acc;
            color: #fff;
          }    <?= file_get_contents("style.css"); // You can replace with direct paste if needed ?>

    .main-content {
      flex-grow: 1;
      padding: 40px;
    }

    .form-container {
      display: flex;
      justify-content: center;  /* Center horizontally */
      align-items: center;      /* Center vertically if needed */
      height: 100vh;            /* Full height for vertical centering */
    }

    form {
      text-align: center;
    }

    .form-group {
      margin-bottom: 10px;
    }

    input[type="text"],
    input[type="file"] {
      width: 50%;
      padding: 10px 10px;
      border-radius: 6px;
      border: none;
      margin-top: 4px;
      font-size: 18px;
    }

    input[type="submit"] {
      padding: 8px 16px;
      background: #ffd369;
      border: none;
      border-radius: 6px;
      font-weight: 600;
      cursor: pointer;
      font-size: 14px;
      transition: background 0.3s ease;
    }

    input[type="submit"]:hover {
      background: #ffc107;
    }



    .profile-card {
      margin: 30px auto;
      padding: 16px;
      max-width: 350px;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.08);
      text-align: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .profile-card img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      border: 3px solid #ffd369;
      object-fit: cover;
      box-shadow: 0 0 15px #ffd36970;
    }

    .success, .error {
      padding: 10px 20px;
      border-radius: 8px;
      margin-bottom: 20px;
      display: inline-block;
    }

    .success {
      background: #4CAF50;
      color: white;
    }

    .error {
      background: #f44336;
      color: white;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px;
    }

  </style>
</head>

<body class="light-mode"> <!-- default is dark-mode -->

  <!-- Sidebar -->
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

  <!-- Main Content -->
  <div class="main-content">
    <header>
      <h2>User Profile</h2>
      <div class="profile">
        <button id="mode-toggle">Toggle Mode</button>
         <div class="profile">
                <img src="<?php echo htmlspecialchars($data['photo']); ?>" alt="User Profile Photo" />
              </div>
      </div>
    </header>

    <?= $success ?>
    <?= $error ?>

    <!-- Profile Form -->
    <form method="POST" enctype="multipart/form-data">
    <?php if (!empty($data['photo'])): ?>
          <div class="profile-card">
            <img src="<?= htmlspecialchars($data['photo']) ?>" alt="Profile Photo">
            <h3><?= htmlspecialchars($data['name']) ?></h3>
            <p>📞 <?= htmlspecialchars($data['phone']) ?></p>
          </div>
        <?php endif; ?>
      <div class="form-group">
        <label>User Name    :</label>
        <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>" required>
      </div>
      <div class="form-group">
        <label>Phone Num:</label>
        <input type="text" name="phone" value="<?= htmlspecialchars($data['phone']) ?>" required>
      </div>
      <div class="form-group">
        <label>Upload Photo:</label>
        <input type="file" name="photo">
      </div>
      <input type="submit" value="Save">
    </form>


  </div>

  <script>
    document.getElementById('mode-toggle').addEventListener('click', function () {
      document.body.classList.toggle('dark-mode');
      document.body.classList.toggle('light-mode');
    });
  </script>
</body>
</html>


