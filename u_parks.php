<?php
include 'db_connect.php';

// Fetch all parks
$stmt = $conn->query("SELECT * FROM parks ORDER BY name");
$parks = $stmt->fetchAll();





?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Parks - SmartTicket</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #ff6b6b, #6c5ce7);
      color: #fff;
      font-family: 'Segoe UI', sans-serif;
    }
    .park-card {
      width: 100%;
      max-width: 400px;
      background: rgba(255,255,255,0.05);
      border-radius: 15px;
      padding: 15px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
      transition: transform 0.3s ease;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      margin-bottom: 30px;
    }
    .park-card:hover {
      transform: scale(1.02);
    }
    .package-card {
      background-color: #111;
      border-radius: 12px;
      padding: 15px;
      margin-bottom: 10px;
    }
    .park-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 10px;
    }
    .btn-gradient {
      background: linear-gradient(to right, #ff512f, #dd2476);
      border: none;
      color: white;
    }
    .btn-gradient:hover {
      background: linear-gradient(to right, #dd2476, #ff512f);
    }
    .text-muted-light {
      color: #ddd;
    }
    .filter-section {
      background: rgba(255,255,255,0.1);
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 30px;
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      justify-content: center;
    }
    .filter-section input, .filter-section select {
      padding: 8px 12px;
      border-radius: 6px;
      border: none;
      width: 220px;
    }
  </style>
</head>
<body>

  <div class="container py-4">
    <a href="dashboard.php" style="
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        background: transparent;
        color: #fff;
        text-decoration: none;
        border: 2px solid white;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        transition: all 0.3s ease;
    ">
      <i class="fas fa-home"></i> Dashboard
    </a>

    <h1 class="text-center mb-4">Explore Parks & Ticket Packages</h1>

    <!-- Filter Section -->
    <div class="filter-section">
      <input type="text" id="searchInput" onkeyup="filterParks()" placeholder="Search by park name">
      <select id="locationFilter" onchange="filterParks()">
        <option value="">All Districts</option>
        <?php
        $districts = ["Bagerhat", "Bandarban", "Barguna", "Barisal", "Bhola", "Bogra", "Brahmanbaria", "Chandpur", "Chapai Nawabganj", "Chattogram", "Chuadanga", "Comilla", "Cox's Bazar", "Dhaka", "Dinajpur", "Faridpur", "Feni", "Gaibandha", "Gazipur", "Gopalganj", "Habiganj", "Jamalpur", "Jashore", "Jhalokathi", "Jhenaidah", "Joypurhat", "Khagrachari", "Khulna", "Kishoreganj", "Kurigram", "Kushtia", "Lakshmipur", "Lalmonirhat", "Madaripur", "Magura", "Manikganj", "Meherpur", "Moulvibazar", "Munshiganj", "Mymensingh", "Naogaon", "Narail", "Narayanganj", "Narsingdi", "Natore", "Netrokona", "Nilphamari", "Noakhali", "Pabna", "Panchagarh", "Patuakhali", "Pirojpur", "Rajbari", "Rajshahi", "Rangamati", "Rangpur", "Satkhira", "Shariatpur", "Sherpur", "Sirajganj", "Sunamganj", "Sylhet", "Tangail", "Thakurgaon"];
        foreach ($districts as $d) {
          echo "<option value='$d'>$d</option>";
        }
        ?>
      </select>
    </div>

    <div class="row justify-content-center" id="parkContainer">
      <?php foreach ($parks as $index => $park):
        $id = $park['park_id'];
        $name = htmlspecialchars($park['name']);
        $img = !empty($park['photo']) ? $park['photo'] : 'no-image.png';
        $collapseId = "collapse" . $index;
      ?>
        <div class="col-md-6 col-lg-4 park-box" data-name="<?= strtolower($name) ?>" data-location="<?= strtolower($park['location']) ?>">
          <div class="park-card">
            <img src="admin/<?= htmlspecialchars($img) ?>" alt="<?= $name ?>">
            <h4 class="mt-2"><?= $name ?></h4>
            <p class="text-muted-light"><?= htmlspecialchars($park['location']) ?></p>
            <small><?= nl2br(htmlspecialchars($park['description'])) ?></small>

            <button class="btn btn-gradient w-100 mt-3"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#<?= $collapseId ?>"
                    aria-expanded="false"
                    aria-controls="<?= $collapseId ?>">
              View Packages
            </button>

            <div class="collapse mt-3" id="<?= $collapseId ?>">
              <?php
                $sections = [
                  'General'   => 'general',
                  'Family (4 people)'    => 'family',
                  'Student (10 people)'   => 'student',
                  'Corporate (10 people)' => 'corporate'
                ];
                foreach ($sections as $title => $col):
                  $desc = $park["{$col}_description"];
                  $price = $park["{$col}_price"];
                  $tickets = $park["{$col}_available_ticket"];
                  if ($desc || $price || $tickets !== null):
              ?>
                <div class="package-card">
                  <h5><?= $title ?></h5>
                  <?php if ($desc): ?>
                    <p><?= nl2br(htmlspecialchars($desc)) ?></p>
                  <?php endif; ?>
                  <?php if ($price !== null): ?>
                    <p><strong>Price:</strong> ৳<?= number_format($price, 2) ?></p>
                  <?php endif; ?>
                  <?php if ($tickets !== null): ?>
                    <p><strong>Available Tickets:</strong> <?= $tickets ?></p>
                  <?php endif; ?>
                  <a href="book_park.php?park_id=<?= $id ?>&package=<?= urlencode($col) ?>"

                     class="btn btn-outline-light btn-sm">Buy Ticket</a>
                </div>
              <?php endif; endforeach; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="text-center mt-5">
      <p class="text-muted-light">More parks coming soon...</p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function filterParks() {
      const search = document.getElementById('searchInput').value.toLowerCase();
      const location = document.getElementById('locationFilter').value.toLowerCase();
      const parks = document.querySelectorAll('.park-box');

      parks.forEach(park => {
        const name = park.getAttribute('data-name');
        const loc = park.getAttribute('data-location');
        const match = name.includes(search) && (location === '' || loc === location);
        park.style.display = match ? 'block' : 'none';
      });
    }
  </script>

  </div>
</body>
</html>
