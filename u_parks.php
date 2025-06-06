<?php
include 'db_connect.php';

// Fetch all parks
$stmt = $conn->query("SELECT * FROM parks ORDER BY name");
$parks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
    @media (max-width:768px) {
      .park-card { margin-bottom: 30px; }
    }
  </style>
</head>
<body>
  <div class="container py-4">
    <h1 class="text-center mb-4">Explore Parks & Ticket Packages</h1>
    <div class="row justify-content-center">
      <?php foreach ($parks as $park):
        $id = $park['park_id'];
        $img = !empty($park['photo']) ? $park['photo'] : 'no-image.png';
      ?>
        <div class="col-md-6 col-lg-4 d-flex justify-content-center">
          <div class="park-card">
            <img src="admin/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($park['name']) ?>">
            <h4 class="mt-2"><?= htmlspecialchars($park['name']) ?></h4>
            <p class="text-muted-light"><?= htmlspecialchars($park['location']) ?></p>
            <small><?= nl2br(htmlspecialchars($park['description'])) ?></small>

            <button class="btn btn-gradient w-100 mt-3"
                    data-bs-toggle="collapse"
                    data-bs-target="#packages<?= $id ?>">
              View Packages
            </button>

            <div class="collapse mt-3" id="packages<?= $id ?>">
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
                  <h5><?= htmlspecialchars($title) ?></h5>
                  <?php if ($desc): ?>
                    <p><?= nl2br(htmlspecialchars($desc)) ?></p>
                  <?php endif; ?>
                  <?php if ($price !== null): ?>
                    <p><strong>Price:</strong> ৳<?= htmlspecialchars(number_format($price, 2)) ?></p>
                  <?php endif; ?>
                  <?php if ($tickets !== null): ?>
                    <p><strong>Available Tickets:</strong> <?= htmlspecialchars($tickets) ?></p>
                  <?php endif; ?>
                  <a href="book.php?park_id=<?= $id ?>&package=<?= urlencode($col) ?>"
                     class="btn btn-outline-light btn-sm">Book Now</a>
                      <a href="buy.php?park_id=<?= $id ?>&package=<?= urlencode($col) ?>"
                                          class="btn btn-outline-light btn-sm">Buy Now</a>
                </div>
              <?php
                  endif;
                endforeach;
              ?>
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
</body>
</html>
