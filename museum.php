<?php
include 'db_connect.php';

// Build dynamic filter conditions
$conditions = [];
$params = [];

if (!empty($_GET['name'])) {
  $conditions[] = "name LIKE :name";
  $params[':name'] = '%' . $_GET['name'] . '%';
}

if (!empty($_GET['location'])) {
  $conditions[] = "location = :location";
  $params[':location'] = $_GET['location'];
}

if (!empty($_GET['type'])) {
  $conditions[] = "description LIKE :type";
  $params[':type'] = '%' . $_GET['type'] . '%';
}

if (!empty($_GET['open'])) {
  if ($_GET['open'] === 'yes') {
    $conditions[] = "opening_hours LIKE '%Open%'";
  } elseif ($_GET['open'] === 'no') {
    $conditions[] = "opening_hours NOT LIKE '%Open%'";
  }
}

if (!empty($_GET['price_range'])) {
  switch ($_GET['price_range']) {
    case 'low':
      $conditions[] = "price <= 100";
      break;
    case 'mid':
      $conditions[] = "price BETWEEN 101 AND 300";
      break;
    case 'high':
      $conditions[] = "price > 300";
      break;
  }
}

$sql = "SELECT * FROM museums";
if (!empty($conditions)) {
  $sql .= " WHERE " . implode(" AND ", $conditions);
}
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$popupIndex = 1;

$districts = [
  "Bagerhat", "Bandarban", "Barguna", "Barisal", "Bhola", "Bogra", "Brahmanbaria",
  "Chandpur", "Chapai Nawabganj", "Chattogram", "Chuadanga", "Comilla", "Cox's Bazar",
  "Dhaka", "Dinajpur", "Faridpur", "Feni", "Gaibandha", "Gazipur", "Gopalganj",
  "Habiganj", "Jamalpur", "Jashore", "Jhalokathi", "Jhenaidah", "Joypurhat", "Khagrachari",
  "Khulna", "Kishoreganj", "Kurigram", "Kushtia", "Lakshmipur", "Lalmonirhat", "Madaripur",
  "Magura", "Manikganj", "Meherpur", "Moulvibazar", "Munshiganj", "Mymensingh", "Naogaon",
  "Narail", "Narayanganj", "Narsingdi", "Natore", "Netrokona", "Nilphamari", "Noakhali",
  "Pabna", "Panchagarh", "Patuakhali", "Pirojpur", "Rajbari", "Rajshahi", "Rangamati",
  "Rangpur", "Satkhira", "Shariatpur", "Sherpur", "Sirajganj", "Sunamganj", "Sylhet",
  "Tangail", "Thakurgaon"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Museums | SmartTicket</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
      }

      body {
        background: linear-gradient(to right, #ff6b6b, #6c5ce7);
        padding: 20px;
      }

      h1.title {
        text-align: center;
        font-size: 2.5rem;
        margin-bottom: 20px;
        color: #fff;
      }

      .filter-section {
        background: rgba(255, 255, 255, 0.9);
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
      }

      .filter-section input,
      .filter-section select {
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        width: 200px;
      }

      .museum-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin: 40px 0;
        padding: 0 20px;
      }

      .museum-card {
        background: #6c5ce7;

        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        transition: transform 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
      }

      .museum-card:hover {
        transform: translateY(-5px);
      }

      .museum-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
      }

      .museum-card-content {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
      }

      .museum-card-content h3 {
        font-size: 18px;
        margin-bottom: 10px;
        color: #ffd369;
      }

      .museum-card-content p {
        flex-grow: 1;
        font-size: 14px;
        color: #ccc;
      }

      .rating {
        font-size: 16px;
        margin: 10px 0;
        color: gold;
      }

      .view-btn {
        padding: 8px 14px;
        background-color: #ffd369;
        color: #1c1e26;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        align-self: flex-start;
      }

      @media (max-width: 1024px) {
        .museum-grid {
          grid-template-columns: repeat(2, 1fr);
        }
      }

      @media (max-width: 768px) {
        .museum-grid {
          grid-template-columns: 1fr;
        }
      }

      /* Popup styling */
      .popup {
        display: none;
        position: fixed;
        z-index: 1000;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        justify-content: center;
        align-items: center;
      }

      .popup-content {
        background: linear-gradient(to right, #ff6b6b, #6c5ce7);
        padding: 30px;
        border-radius: 12px;
        width: 90%;
        max-width: 600px;
        color: #fff;
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
        position: relative;
      }

      .popup-content .close-btn {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 24px;
        cursor: pointer;
        color: #ffd369;
      }

      iframe {
        margin-top: 15px;
        border-radius: 8px;
      }
      .buy-ticket-btn {
        padding: 14px 26px;
        background: linear-gradient(135deg, #f6d365, #fda085);
        color: white;
        border: none;
        border-radius: 14px;
        font-weight: 600;
        font-size: 17px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
      }

      .buy-ticket-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.25);
      }

      .modal-overlay {
        position: fixed;
        top: 0; left: 0;
        width: 100vw; height: 100vh;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(6px);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
      }

      .modal-box {
        background: white;
        border-radius: 20px;
        padding: 30px 25px;
        width: 90%;
        max-width: 420px;
        text-align: center;
        box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        animation: zoomIn 0.3s ease;
      }

      .modal-box h2 {
        font-size: 24px;
        margin-bottom: 12px;
        color: #333;
      }

      .modal-box p {
        font-size: 16px;
        color: #555;
        margin-bottom: 30px;
      }

      .modal-actions {
        display: flex;
        justify-content: center;
        gap: 20px;
      }

      .btn-login {
        background: #6c5ce7;
        color: #fff;
        padding: 10px 22px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: bold;
        transition: background 0.3s;
      }

      .btn-login:hover {
        background: #594bd3;
      }

      .btn-cancel {
        background: #ddd;
        border: none;
        padding: 10px 22px;
        border-radius: 10px;
        font-weight: bold;
        cursor: pointer;
      }

      .btn-cancel:hover {
        background: #bbb;
      }

      @keyframes zoomIn {
        from {
          transform: scale(0.85);
          opacity: 0;
        }
        to {
          transform: scale(1);
          opacity: 1;
        }
      }

    </style>
</head>

<body>
  <h1 class="title">Explore Museums</h1>

  <form method="GET" class="filter-section">
    <input type="text" name="name" placeholder="Search Museum Name" value="<?= htmlspecialchars($_GET['name'] ?? '') ?>" />
    <select name="location">
      <option value="">Select District</option>
      <?php foreach ($districts as $district): ?>
        <option value="<?= $district ?>" <?= (($_GET['location'] ?? '') === $district) ? 'selected' : '' ?>>
          <?= $district ?>
        </option>
      <?php endforeach; ?>
    </select>

    <select name="type">
      <option value="">Type</option>
      <?php
        $types = ["Art", "History", "Science", "Culture", "Natural"];
        foreach ($types as $type) {
          $selected = ($_GET['type'] ?? '') === $type ? 'selected' : '';
          echo "<option value=\"$type\" $selected>$type</option>";
        }
      ?>
    </select>

    <select name="open">
      <option value="">Open Now?</option>
      <option value="yes" <?= ($_GET['open'] ?? '') === 'yes' ? 'selected' : '' ?>>Yes</option>
      <option value="no" <?= ($_GET['open'] ?? '') === 'no' ? 'selected' : '' ?>>No</option>
    </select>

    <select name="price_range">
      <option value="">Ticket Price</option>
      <option value="low" <?= ($_GET['price_range'] ?? '') === 'low' ? 'selected' : '' ?>>৳0–100</option>
      <option value="mid" <?= ($_GET['price_range'] ?? '') === 'mid' ? 'selected' : '' ?>>৳101–300</option>
      <option value="high" <?= ($_GET['price_range'] ?? '') === 'high' ? 'selected' : '' ?>>৳301+</option>
    </select>

    <button type="submit" class="view-btn">Filter</button>
  </form>

  <div class="museum-grid">
    <?php foreach ($result as $row): ?>
      <?php
        $id = $row['museum_id'];
        $name = htmlspecialchars($row['name']);
        $location = htmlspecialchars($row['location']);
        $price = htmlspecialchars($row['price']);
        $image = htmlspecialchars($row['photo']);
        $description = htmlspecialchars($row['description']);
        $address = htmlspecialchars($row['address']);
        $hours = htmlspecialchars($row['opening_hours']);
        $ticket_price = htmlspecialchars($row['price']);
        $contact = htmlspecialchars($row['contact']);
        $map_url = "https://www.google.com/maps?q=" . urlencode($address);
      ?>
      <div class="museum-card">
        <img src="<?= 'admin/' . $image ?>" alt="<?= $name ?>" />
        <div class="museum-card-content">
          <h3><?= $name ?></h3>
          <div class="rating">★★★★☆</div>
          <button class="view-btn" onclick="openPopup('popup<?= $popupIndex ?>')">View Details</button>
        </div>
      </div>

      <div class="popup" id="popup<?= $popupIndex ?>" style="display: none;">
        <div class="popup-content">
          <span class="close-btn" onclick="closePopup('popup<?= $popupIndex ?>')">&times;</span>
          <h2><?= $name ?></h2>
          <p><strong>Description:</strong> <?= $description ?></p>
          <p><strong>Address:</strong> <?= $address ?></p>
          <p><strong>Opening Hours:</strong> <?= $hours ?></p>
          <p><strong>Ticket Price:</strong> <?= $ticket_price ?></p>
          <p><strong>Contact:</strong> <?= $contact ?></p>

          <form action="museum/temp_booking.php" method="POST" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: center; margin-top: 20px;">
            <input type="hidden" name="museum_id" value="<?= $id ?>">

            <label for="quantity<?= $popupIndex ?>" style="color: #333; font-weight: bold;">Quantity:</label>
            <input type="number" id="quantity<?= $popupIndex ?>" name="quantity" min="1" value="1"
                   style="width: 80px; padding: 6px 10px; border-radius: 8px; border: 1px solid #ccc;" required>

            <button type="button" class="buy-ticket-btn" onclick="showLoginModal(event)">
              <i class="fas fa-lock"></i> Buy Ticket
            </button>
          </form>
        </div>
      </div>

      <?php $popupIndex++; ?>
    <?php endforeach; ?>
  </div>

  <script>
    function openPopup(id) {
      document.getElementById(id).style.display = "flex";
    }

    function closePopup(id) {
      document.getElementById(id).style.display = "none";
    }
  </script>
  <!-- Login First Modal -->
  <div id="loginModal" class="modal-overlay">
    <div class="modal-box">
      <h2>🔐 Login Required</h2>
      <p>Please login first to continue buying your ticket.</p>
      <div class="modal-actions">
        <a href="login.php" class="btn-login">Login Now</a>
        <button onclick="closeLoginModal()" class="btn-cancel">Cancel</button>
      </div>
    </div>
  </div>
<script>
function showLoginModal(e) {
  e.preventDefault();
  document.getElementById("loginModal").style.display = "flex";
}

function closeLoginModal() {
  document.getElementById("loginModal").style.display = "none";
}
</script>

</body>

</html>
