<?php
require_once '../db_connect.php';

$showtimeId = $_GET['showtime_id'] ?? null;

if (!$showtimeId) {
    die("Showtime ID not specified.");
}

$stmt = $pdo->prepare("SELECT seat_id, seat_number, status FROM seats WHERE showtime_id = ?");
$stmt->execute([$showtimeId]);
$seats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Seat Selection</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }
        .seat-container {
            display: grid;
            grid-template-columns: repeat(10, 40px);
            gap: 10px;
            margin: 30px 0;
        }
        .seat {
            width: 40px;
            height: 40px;
            text-align: center;
            line-height: 40px;
            border-radius: 6px;
            cursor: pointer;
            border: 1px solid #999;
        }
        .available {
            background-color: #4CAF50;
            color: white;
        }
        .booked {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .selected {
            background-color: #2196F3;
            color: white;
        }
        .legend {
            margin-top: 20px;
        }
        .legend span {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 8px;
            border: 1px solid #000;
        }
    </style>
</head>
<body>

<h2>Select Your Seat</h2>

<form method="POST" action="confirm_booking.php">
    <input type="hidden" name="showtime_id" value="<?= htmlspecialchars($showtimeId) ?>">

    <div class="seat-container" id="seatGrid">
        <?php foreach ($seats as $seat): ?>
            <div
                class="seat <?= $seat['status'] === 'booked' ? 'booked' : 'available' ?>"
                data-seat-id="<?= $seat['seat_id'] ?>"
                data-seat-number="<?= $seat['seat_number'] ?>"
            >
                <?= $seat['seat_number'] ?>
            </div>
        <?php endforeach; ?>
    </div>

    <input type="hidden" name="selected_seats" id="selectedSeatsInput">
    <button type="submit">Confirm Booking</button>
</form>

<div class="legend">
    <p><span style="background:#4CAF50;"></span> Available</p>
    <p><span style="background:#ccc;"></span> Booked</p>
    <p><span style="background:#2196F3;"></span> Selected</p>
</div>

<script>
    const seats = document.querySelectorAll('.seat.available');
    const selectedInput = document.getElementById('selectedSeatsInput');
    let selected = [];

    seats.forEach(seat => {
        seat.addEventListener('click', () => {
            const seatId = seat.getAttribute('data-seat-id');
            const seatNumber = seat.getAttribute('data-seat-number');

            if (selected.includes(seatId)) {
                selected = selected.filter(id => id !== seatId);
                seat.classList.remove('selected');
            } else {
                selected.push(seatId);
                seat.classList.add('selected');
            }

            selectedInput.value = selected.join(',');
        });
    });
</script>

</body>
</html>
