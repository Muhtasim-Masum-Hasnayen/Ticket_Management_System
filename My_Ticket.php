<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

include 'db_connect.php';
$user_id = $_SESSION['user_id'];

// Use prepared statement
$stmt = $conn->prepare("SELECT * FROM tickets WHERE user_id = ? ORDER BY show_date DESC");
$stmt->bind_param("i", $user_id); // "i" for integer
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Tickets - SmartTicket</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
      color: #fff;
      min-height: 100vh;
      font-family: 'Segoe UI', sans-serif;
    }
    .ticket-card {
      background: #1e2a38;
      border-radius: 16px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    .badge-status {
      font-size: 0.8rem;
      padding: 5px 10px;
    }
    .download-btn, .cancel-btn {
      margin-top: 10px;
    }
    .header {
      padding: 20px 0;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2><i class="fas fa-ticket-alt"></i> My Tickets</h2>
      <p class="text-muted">Here you can view, download, or cancel your movie tickets.</p>
    </div>

    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="ticket-card">
          <div class="row">
            <div class="col-md-9">
              <h4><?= htmlspecialchars($row['movie_name']) ?> <span class="badge bg-info text-dark">#<?= $row['ticket_id'] ?></span></h4>
              <p><strong>Date:</strong> <?= $row['show_date'] ?> at <?= $row['show_time'] ?></p>
              <p><strong>Theatre:</strong> <?= htmlspecialchars($row['theatre_name']) ?>, <?= htmlspecialchars($row['location']) ?></p>
              <p><strong>Seat(s):</strong> <?= $row['seat_number'] ?> | <strong>Type:</strong> <?= $row['ticket_type'] ?></p>
              <span class="badge <?= $row['status'] == 'Cancelled' ? 'bg-danger' : ($row['status'] == 'Pending' ? 'bg-warning text-dark' : 'bg-success') ?> badge-status">
                <?= $row['status'] ?>
              </span>
            </div>
            <div class="col-md-3 text-end">
              <?php if ($row['status'] != 'Cancelled'): ?>
                <a href="download_ticket.php?ticket_id=<?= $row['ticket_id'] ?>" class="btn btn-outline-light btn-sm download-btn"><i class="fas fa-download"></i> Download</a>
                <?php
                  $currentTime = strtotime(date("Y-m-d H:i:s"));
                  $showTime = strtotime($row['show_date'] . ' ' . $row['show_time']);
                  if ($showTime - $currentTime > 3600):
                ?>
                  <a href="cancel_ticket.php?ticket_id=<?= $row['ticket_id'] ?>" class="btn btn-danger btn-sm cancel-btn"><i class="fas fa-times"></i> Cancel</a>
                <?php endif; ?>
              <?php else: ?>
                <em class="text-muted">Ticket Cancelled</em>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="alert alert-warning text-center">No tickets found.</div>
    <?php endif; ?>
  </div>
</body>
</html>
