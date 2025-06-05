<?php
include 'db_connect.php'; // Make sure this file connects to your database
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
      background: linear-gradient(to right, #a18cd1, #fbc2eb);
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
      background: rgba(44, 62, 80, 0.8);
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
      background: #1c1e26;
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
  </style>
</head>

<body>

  <h1 class="title">Explore Museums</h1>

    <div class="filter-section">
      <input type="text" placeholder="Search Museum Name" />
      <select>
        <option value="">Select Location</option>
        <option value="Dhaka">Dhaka</option>
        <option value="Chattogram">Chattogram</option>
        <option value="Khulna">Khulna</option>
      </select>
      <select>
        <option value="">Type</option>
        <option value="Art">Art</option>
        <option value="History">History</option>
        <option value="Science">Science</option>
        <option value="Culture">Culture</option>
        <option value="Natural">Natural History</option>
      </select>
      <select>
        <option value="">Open Now?</option>
        <option value="yes">Yes</option>
        <option value="no">No</option>
      </select>
      <select>
        <option value="">Ticket Price</option>
        <option value="low">৳0–100</option>
        <option value="mid">৳101–300</option>
        <option value="high">৳301+</option>
      </select>
    </div>

    <div class="museum-grid">
     <?php
     $sql = "SELECT * FROM museums";
     $stmt = $conn->prepare($sql);
     $stmt->execute();
     $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
     $popupIndex = 1;

     foreach ($result as $row) {
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
     ?>


        <!-- Museum Card -->
        <div class="museum-card" data-name="<?= $name ?>" data-location="<?= $location ?>" data-type="<?= $type ?>" data-open="<?= $open ?>" data-price="<?= $price ?>">
          <img src="<?= $image ?>" alt="<?= $name ?>" />
          <div class="museum-card-content">
            <h3><?= $name ?></h3>
            <div class="rating">★★★★☆</div>
            <button class="view-btn" onclick="openPopup('popup<?= $popupIndex ?>')">View Details</button>
          </div>
        </div>

        <!-- Popup -->
        <div class="popup" id="popup<?= $popupIndex ?>" style="display: none;">
          <div class="popup-content">
            <span class="close-btn" onclick="closePopup('popup<?= $popupIndex ?>')">&times;</span>
            <h2><?= $name ?></h2>
            <p><strong>Description:</strong> <?= $description ?></p>
            <p><strong>Address:</strong> <?= $address ?></p>
            <p><strong>Opening Hours:</strong> <?= $hours ?></p>
            <p><strong>Ticket Price:</strong> <?= $ticket_price ?></p>
            <p><strong>Contact:</strong> <?= $contact ?></p>
            <iframe src="<?= $map_url ?>" width="100%" height="200" frameborder="0" allowfullscreen></iframe>
          </div>
        </div>

      <?php
        $popupIndex++;
      }
      ?>
    </div>

    <script>
      function openPopup(id) {
        document.getElementById(id).style.display = "flex";
      }

      function closePopup(id) {
        document.getElementById(id).style.display = "none";
      }
    </script>
</body>


</html>