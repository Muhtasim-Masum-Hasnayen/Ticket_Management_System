<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SmartTicket - Ticket Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f7f9fc;
    }

    .top-bar {
      background: linear-gradient(to right, #ff6b6b, #6c5ce7);
      color: white;
      padding: 30px 0;
      font-size: 14px;
    }

    .navbar {
      background-color: #ffffff;
      padding: 30px 0;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }


    .hero {
      background: linear-gradient(135deg, #ff4c60, #4ecdc4);
      color: white;
      padding: 100px 0;
      text-align: center;
    }

    .hero h1 {
      font-size: 62px;
      font-weight: bold;
    }

    .hero p {
      font-size: 36px;
    }

    .carousel-inner img {
          height: 600px;
          object-fit: cover;
          border-radius: 15px;
        }

        .carousel-container {
          flex: 1 1 50%;
          padding: 0 30px;
        }

        .welcome-text {
          flex: 1 1 50%;
          display: flex;
          flex-direction: column;
          justify-content: center;
          padding: 30 30px;
        }

        .welcome-text h1 {
          font-size: 48px;
          font-weight: bold;
          color: #6c5ce7;
        }

        .welcome-text p {
          font-size: 20px;
          color: #333;
        }


    .ticket-card {
      border: none;
      border-radius: 15px;
      color: white;
    }

    .movie-card { background-color: #ff6b6b; }
    .park-card { background-color: #4ecdc4; }
    .museum-card { background-color: #6c5ce7; }

    .features i {
      font-size: 40px;
      color: #ff4c60;
    }

    footer {
      background-color: #222;
      color: white;
      padding: 30px 0;
    }
    body.dark-mode {
      background-color: #121212;
      color: #f0f0f0;
    }

    .dark-mode section {
      background: linear-gradient(to right, #333, #444, #555);
    }

    .dark-mode a, .dark-mode h1, .dark-mode h2, .dark-mode h3, .dark-mode p {
      color: #11f1c2c !important;
    }

  </style>
</head>

<body>
  <!-- Top Bar -->
  <div class="top-bar text-center">
<span class="fs-5">
  <i class="fas fa-phone"></i> +8801730202960 |
  <i class="fas fa-envelope"></i> support@smartticket.com
</span>
<div class="float-end me-3 d-flex gap-2 flex-wrap">
  <a href="login.php" class="btn btn-outline-light px-4 py-2 rounded-pill fw-semibold shadow-sm">Login</a>
  <a href="register.php" class="btn btn-light text-primary px-4 py-2 rounded-pill fw-semibold shadow-sm">Register</a>
</div>
  </div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-gradient shadow-sm py-3" style="background: linear-gradient(90deg, #1f1c2c, #928dab);">
  <div class="container">

    <a class="navbar-brand fw-bold fs-3 text-black d-flex align-items-center gap-2" href="#">
      <img src="SmartTicketLogo.png" alt="Logo" style="height: 55px;">
      Smart<span class="text-warning">Ticket</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto gap-3">
        <li class="nav-item">
          <a class="nav-link fs-5 fw-semibold text-primary hover-effect" href="LandingPage.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fs-5 fw-semibold text-primary hover-effect" href="Movies.php">Movies</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fs-5 fw-semibold text-primary hover-effect" href="Parks.php">Parks</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fs-5 fw-semibold text-primary hover-effect" href="Museums.php">Museums</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fs-5 fw-semibold text-primary hover-effect" href="My_Ticket.php">My Tickets</a>
        </li>
        <li class="nav-item">
          <a class="nav-link fs-5 fw-semibold text-primary hover-effect" href="Contact.php">Contact</a>
        </li>
        <!-- Dark Mode Toggle -->
        <div id="darkModeToggle" style="position: fixed; top: 200px; right: 20px; z-index: 9999; cursor: pointer; font-size: 34px;">
          🌙
        </div>

      </ul>
    </div>
  </div>
</nav>

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <h1>Book Tickets for Movies, Parks & Museums</h1>
      <p>Colorful, Fast, and Easy Ticket Management System</p>
      <a href="#" class="btn btn-light px-4 py-2 mt-3 fw-bold">Explore Tickets</a>
    </div>
  </section>

 <section class="hero-section d-flex align-items-center justify-content-center p-5" style="background: linear-gradient(to right, #ffecd2, #fcb69f); min-height: 90vh;">
   <div class="row w-100">

<!-- Section Container -->
<section class="container my-5">
  <div class="row align-items-center justify-content-center g-4">

    <!-- Left: Image Carousel -->
    <div class="col-md-6 d-flex justify-content-center">
      <img id="slideshow-image" src="WaterKingdom.jpg" alt="Slide"
        style="width:100%; max-width: 640px; height: 400px; object-fit: cover; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
    </div>

    <!-- Right: Typing Text -->
    <div class="col-md-6 d-flex flex-column justify-content-center align-items-start text-dark ps-md-4">
      <h1 class="fw-bold mb-3">🎟️ Welcome to SmartTicket</h1>
      <h3 class="typing-text" style="font-family: 'Courier New', monospace; font-weight: 600; font-size: 1.5rem;"></h3>
    </div>

  </div>
</section>

<!-- Scripts -->
<script>
  // Image slideshow
  const images = [
    "WaterKingdom.jpg",
    "manabae.jpg",
    "military museum.jpg",
    "military museum1.jpg",
    "WaterKingdom1.jpg",
    "movies.jpg",
    "Fantasy.jpg",
    "Liberationwarmuseum.jpg"
  ];
  let current = 0;
  setInterval(() => {
    current = (current + 1) % images.length;
    document.getElementById('slideshow-image').src = images[current];
  }, 2500);

  // Typing effect
const text = "Where Fun Meets Simplicity! Smart Ticket is your all-in-one solution for booking tickets to your favorite movies, parks, museums, and more. With a smooth and user-friendly interface, Smart Ticket makes planning your leisure time effortless. Say goodbye to long lines and hello to instant access!";
  let i = 0;
  function typeWriter() {
    if (i < text.length) {
      document.querySelector('.typing-text').innerHTML += text.charAt(i);
      i++;
      setTimeout(typeWriter, 100);
    }
  }
  typeWriter();
</script>



  <!-- Ticket Categories -->
  <section class="py-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <div class="card ticket-card movie-card text-center p-4">
            <i class="fas fa-film fa-3x mb-3"></i>
            <h4>Movie Tickets</h4>
            <p>Book your seat for the latest blockbuster now!</p>
            <a href="Movies.php" class="btn btn-light btn-sm">Book Now</a>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card ticket-card park-card text-center p-4">
            <i class="fas fa-tree fa-3x mb-3"></i>
            <h4>Park Tickets</h4>
            <p>Relax and enjoy nature in city parks.</p>
            <a href="Parks.php" class="btn btn-light btn-sm">Book Now</a>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card ticket-card museum-card text-center p-4">
            <i class="fas fa-landmark fa-3x mb-3"></i>
            <h4>Museum Tickets</h4>
            <p>Discover art, history, and culture today.</p>
            <a href="Museums.php" class="btn btn-light btn-sm">Book Now</a>
          </div>
        </div>
      </div>
    </div>
  </section>




  <!-- Features -->
  <section class="features py-5 bg-light">
    <div class="container text-center">
      <h2 class="mb-4">Why Choose SmartTicket?</h2>
      <div class="row g-4">
        <div class="col-md-3">
          <i class="fas fa-bolt"></i>
          <h5 class="mt-2">Fast Booking</h5>
        </div>
        <div class="col-md-3">
          <i class="fas fa-lock"></i>
          <h5 class="mt-2">Secure Payments</h5>
        </div>
        <div class="col-md-3">
          <i class="fas fa-ticket-alt"></i>
          <h5 class="mt-2">Digital Tickets</h5>
        </div>
        <div class="col-md-3">
          <i class="fas fa-mobile-alt"></i>
          <h5 class="mt-2">Mobile Friendly</h5>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="text-center">
    <div class="container">
      <p>Follow us on</p>
      <div>
        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
        <a href="#" class="text-white"><i class="fab fa-twitter fa-lg"></i></a>
      </div>
      <p class="mt-3">&copy; 2025 SmartTicket. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const toggle = document.getElementById('darkModeToggle');
  const body = document.body;

  // Load preference
  if (localStorage.getItem('darkMode') === 'enabled') {
    body.classList.add('dark-mode');
    toggle.textContent = '☀️';
  }

  toggle.addEventListener('click', () => {
    body.classList.toggle('dark-mode');
    if (body.classList.contains('dark-mode')) {
      toggle.textContent = '☀️';
      localStorage.setItem('darkMode', 'enabled');
    } else {
      toggle.textContent = '🌙';
      localStorage.setItem('darkMode', 'disabled');
    }
  });
</script>

</body>

</html>
