<?php
// DB connection (optional if saving messages)
$host = 'localhost';
$db = 'smartticket';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $conn->real_escape_string($_POST['name']);
  $contact_info = $conn->real_escape_string($_POST['email']);  // you called it email in form, but DB column is contact_info
  $subject = $conn->real_escape_string($_POST['subject']);
  $message = $conn->real_escape_string($_POST['message']);

  $sql = "INSERT INTO contact_messages (name, contact_info, subject, message) VALUES ('$name', '$contact_info', '$subject', '$message')";

  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Message Sent Successfully!');</script>";
  } else {
    echo "<script>alert('Error sending message');</script>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact - SmartTicket</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #ff6b6b, #6c5ce7);
      min-height: 100vh;
      color: #333;
    }
    .contact-container {
      background-color: #fff;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      padding: 40px;
      max-width: 1000px;
      margin: 50px auto;
    }
    .form-control, .form-label {
      border-radius: 10px;
    }
    .btn-send {
      background: linear-gradient(to right, #ff6b6b, #6c5ce7);
      color: white;
      border-radius: 30px;
      padding: 10px 30px;
      font-weight: bold;
      border: none;
    }
    .btn-send:hover {
      background: linear-gradient(to right, #6c5ce7, #ff6b6b);
    }
    .info-box i {
      color: #6c5ce7;
      font-size: 20px;
      margin-right: 10px;
    }
    .faq-question {
      font-weight: 600;
    }
  </style>
</head>
<body>
  <div class="contact-container">
    <h2 class="text-center mb-4">Contact <span class="text-warning">SmartTicket</span></h2>

    <div class="row">
      <!-- Contact Form -->
      <div class="col-md-6">
        <form method="POST">
          <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Name</label>
            <input type="text" class="form-control" name="name" required />
          </div>
          <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email / Phone</label>
            <input type="text" class="form-control" name="email" required />
          </div>
          <div class="mb-3">
            <label for="subject" class="form-label fw-semibold">Subject</label>
            <input type="text" class="form-control" name="subject" required />
          </div>
          <div class="mb-3">
            <label for="message" class="form-label fw-semibold">Message</label>
            <textarea class="form-control" name="message" rows="5" required></textarea>
          </div>
          <button type="submit" class="btn btn-send">Send Message</button>
        </form>
      </div>

      <!-- Contact Info -->
      <div class="col-md-6">
        <h5 class="fw-bold mb-3">Company Information</h5>
        <p class="info-box"><i class="fas fa-map-marker-alt"></i>Dhaka, Bangladesh</p>
        <p class="info-box"><i class="fas fa-phone"></i>+880 1730 202960</p>
        <p class="info-box"><i class="fas fa-envelope"></i>support@smartticket.com</p>
        <p class="info-box"><i class="fas fa-clock"></i>Support Hours: 9 AM - 6 PM</p>

        <h5 class="fw-bold mt-4 mb-2">Follow Us</h5>
        <div class="d-flex gap-3">
          <a href="#"><i class="fab fa-facebook fa-lg"></i></a>
          <a href="#"><i class="fab fa-instagram fa-lg"></i></a>
          <a href="#"><i class="fab fa-twitter fa-lg"></i></a>
          <a href="#"><i class="fab fa-youtube fa-lg"></i></a>
          <a href="#"><i class="fab fa-whatsapp fa-lg"></i></a>
        </div>
      </div>
    </div>

    <!-- Google Map -->
    <div class="mt-5">
      <h5 class="fw-bold">Our Location</h5>
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.9028432032645!2d90.39134531543113!3d23.750903994836735!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8917c4b8cc7%3A0xf74f6ef2f9a8052e!2sDhaka!5e0!3m2!1sen!2sbd!4v1686943054707!5m2!1sen!2sbd" width="100%" height="250" style="border:0; border-radius: 15px;" allowfullscreen="" loading="lazy"></iframe>
    </div>

    <!-- FAQ -->
    <div class="mt-5">
      <h5 class="fw-bold">Frequently Asked Questions</h5>
      <ul>
        <li><span class="faq-question">How to book a ticket?</span><br>Go to our home page, search for your destination and follow the booking steps.</li>
        <li><span class="faq-question">How to reset my password?</span><br>Click on 'Forgot Password' from the login page and follow the instructions.</li>
        <li><span class="faq-question">Can I cancel a ticket?</span><br>Yes, login to your account and go to 'My Tickets' to cancel.</li>
        <li><span class="faq-question">Is SmartTicket available on mobile?</span><br>Yes, our website is mobile-friendly and apps are coming soon.</li>
      </ul>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>