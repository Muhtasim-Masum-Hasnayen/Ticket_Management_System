<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
      width: 400px;
      height: 420px;
      background: rgba(255, 255, 255, 0.05);
      border-radius: 15px;
      padding: 15px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
      transition: transform 0.3s ease;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .park-card:hover {
      transform: scale(1.02);
    }

    .package-card {
      background-color: rgba(255, 255, 255, 0.07);
      border-radius: 12px;
      padding: 20px;
      margin: 15px 0;
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
  </style>
</head>

<body>
  <div class="container py-5">
    <h1 class="text-center mb-4">Explore Parks & Ticket Packages</h1>

    <!-- Featured Parks -->
    <div class="row">
      <!-- Example Park Card -->
      <div class="col-md-6 col-lg-4">
        <div class="park-card">
          <img src="Fantasy.jpg" class="img-fluid rounded mb-3" alt="Fantasy Kingdom">
          <h4>Fantasy Kingdom</h4>
          <p class="text-muted-light">Ashulia, Savar</p>
          <button class="btn btn-gradient w-100" data-bs-toggle="collapse" data-bs-target="#fantasyPackages">View Packages</button>

          <div class="collapse mt-3" id="fantasyPackages">
            <div class="package-card">
              <h5>🎓 Student Package</h5>
              <p>৳400 per person. ID required. Includes 5 rides.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
            <div class="package-card">
              <h5>💑 Couple Package</h5>
              <p>৳800 for 2 persons. Free photo booth & drink.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
            <div class="package-card">
              <h5>👨‍👩‍👧‍👦 Family Package</h5>
              <p>৳1800 for 5 members. Includes lunch & ride pass.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
            <div class="package-card">
              <h5>🏢 Corporate Package</h5>
              <p>৳5000 for 10 members. Free lounge & event support.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Add more park cards here -->
      <div class="col-md-6 col-lg-4">
        <div class="park-card">
          <img src="Nandan.jpg" class="img-fluid rounded mb-3" alt="Nandan Park">
          <h4>Nandan Park</h4>
          <p class="text-muted-light">Nabinagar, Dhaka</p>
          <button class="btn btn-gradient w-100" data-bs-toggle="collapse" data-bs-target="#nandanPackages">View Packages</button>

          <div class="collapse mt-3" id="nandanPackages">
            <div class="package-card">
              <h5>🎓 Student Package</h5>
              <p>৳350 per person. Includes entry & 3 rides.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
            <div class="package-card">
              <h5>💑 Couple Package</h5>
              <p>৳750 for 2. Romantic ride + lunch combo.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
            <div class="package-card">
              <h5>👨‍👩‍👧‍👦 Family Package</h5>
              <p>৳1600 family ticket. Includes water zone.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
            <div class="package-card">
              <h5>🏢 Corporate Package</h5>
              <p>৳4500 for 8. Meeting space & buffet included.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
          </div>
        </div>
      </div>


      <!-- Water Kingdom -->
      <div class="col-md-6 col-lg-4">
        <div class="park-card">
          <img src="WaterKingdom.jpg" class="img-fluid rounded mb-3" alt="Water Kingdom">
          <h4>Water Kingdom</h4>
          <p class="text-muted-light">Narayanganj</p>
          <button class="btn btn-gradient w-100" data-bs-toggle="collapse" data-bs-target="#waterPackages">View Packages</button>

          <div class="collapse mt-3" id="waterPackages">
            <div class="package-card">
              <h5>🎓 Student Package</h5>
              <p>৳350 per person. ID required. Includes water slides & wave pool.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
            <div class="package-card">
              <h5>💑 Couple Package</h5>
              <p>৳750 for 2 persons. Romantic tube ride & snack voucher.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
            <div class="package-card">
              <h5>👨‍👩‍👧‍👦 Family Package</h5>
              <p>৳1600 for 5 members. All-day pass + kid zone access.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
            <div class="package-card">
              <h5>🏢 Corporate Package</h5>
              <p>৳4800 for 10 people. Reserved cabana & team activities.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Mana Bay Water Park -->
      <div class="col-md-6 col-lg-4">
        <div class="park-card">
          <img src="Mana Bay.jpg" class="img-fluid rounded mb-3" alt="Mana Bay Water Park">
          <h4>Mana Bay Water Park</h4>
          <p class="text-muted-light">Ghatail, Tangail</p>
          <button class="btn btn-gradient w-100" data-bs-toggle="collapse" data-bs-target="#manaPackages">View Packages</button>

          <div class="collapse mt-3" id="manaPackages">
            <div class="package-card">
              <h5>🎓 Student Package</h5>
              <p>৳450 per student. Free locker, includes slides & rain dance.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
            <div class="package-card">
              <h5>💑 Couple Package</h5>
              <p>৳900 for 2. Couple cabana + ice cream voucher.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
            <div class="package-card">
              <h5>👨‍👩‍👧‍👦 Family Package</h5>
              <p>৳2000 for 5. Kids slide area & water shows included.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
            <div class="package-card">
              <h5>🏢 Corporate Package</h5>
              <p>৳5200 for 10. Water volleyball + event decoration.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Suborno Gram -->
      <div class="col-md-6 col-lg-4">
        <div class="park-card">
          <img src="SubornoGram.jpg" class="img-fluid rounded mb-3" alt="Suborno Gram">
          <h4>Suborno Gram</h4>
          <p class="text-muted-light">Kuril, Dhaka</p>
          <button class="btn btn-gradient w-100" data-bs-toggle="collapse" data-bs-target="#subornoPackages">View Packages</button>

          <div class="collapse mt-3" id="subornoPackages">
            <div class="package-card">
              <h5>🎓 Student Package</h5>
              <p>৳300 per student. Museum tour & cultural show access.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
            <div class="package-card">
              <h5>💑 Couple Package</h5>
              <p>৳700 for 2. Boating, garden walk & couple photo shoot.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
            <div class="package-card">
              <h5>👨‍👩‍👧‍👦 Family Package</h5>
              <p>৳1500 for 5. Picnic spot, play zone & snacks included.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
            <div class="package-card">
              <h5>🏢 Corporate Package</h5>
              <p>৳4500 for 10. Open stage, seminar hall, and catering.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Narsingdi Dream Holiday Park -->
      <div class="col-md-6 col-lg-4">
        <div class="park-card">
          <img src="NarsingdiDream.jpg" class="img-fluid rounded mb-3" alt="Narsingdi Dream Holiday Park">
          <h4>Dream Holiday Park</h4>
          <p class="text-muted-light">Narsingdi</p>
          <button class="btn btn-gradient w-100" data-bs-toggle="collapse" data-bs-target="#dreamPackages">View Packages</button>

          <div class="collapse mt-3" id="dreamPackages">
            <div class="package-card">
              <h5>🎓 Student Package</h5>
              <p>৳380 per student. Includes adventure zone & train ride.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
            <div class="package-card">
              <h5>💑 Couple Package</h5>
              <p>৳850 for 2. Private garden table & tea break.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
            <div class="package-card">
              <h5>👨‍👩‍👧‍👦 Family Package</h5>
              <p>৳1700 for 5. Free photo session & ride bands.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
            <div class="package-card">
              <h5>🏢 Corporate Package</h5>
              <p>৳4900 for 10. Large picnic spot + logistic support.</p>
              <button class="btn btn-outline-light btn-sm">Book Now</button>
            </div>
          </div>
        </div>
      </div>


      <!-- Repeat cards for Water Kingdom, Mana Bay, Narshindi Park, Suborno Gram -->
    </div>

    <div class="text-center mt-5">
      <p class="text-muted-light">More parks coming soon...</p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
