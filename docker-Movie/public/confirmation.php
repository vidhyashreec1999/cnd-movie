<?php
// Start output buffering
ob_start();

// Include the configuration file
require_once 'config.php';

// Check if booking_id is provided
$booking_id = getGetData('booking_id');
if (!$booking_id) {
    header("Location: index.php");
    exit();
}

// Prepare and execute query to get booking details
$sql = "SELECT b.*, m.title, s.start_time, t.name AS theater_name
        FROM bookings b
        JOIN showtimes s ON b.showtime_id = s.id
        JOIN movies m ON s.movie_id = m.id
        JOIN theaters t ON s.theater_id = t.id
        WHERE b.id = ?";

$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $booking_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);

// Check if booking exists
if (!$booking) {
    header("Location: index.php");
    exit();
}

// HTML content starts here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Booking Confirmation</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Thank you for your booking, <?php echo htmlspecialchars($booking['customer_name']); ?>!</h5>
                <p class="card-text">
                    <strong>Booking ID:</strong> <?php echo htmlspecialchars($booking['id']); ?><br>
                    <strong>Movie:</strong> <?php echo htmlspecialchars($booking['title']); ?><br>
                    <strong>Theater:</strong> <?php echo htmlspecialchars($booking['theater_name']); ?><br>
                    <strong>Date & Time:</strong> <?php echo htmlspecialchars($booking['start_time']); ?><br>
                    <strong>Number of Seats:</strong> <?php echo htmlspecialchars($booking['seats']); ?><br>
                    <strong>Total Price:</strong> ₹<?php echo htmlspecialchars($booking['total_price']); ?>
                </p>
                <p class="card-text">
                    An email confirmation has been sent to <?php echo htmlspecialchars($booking['customer_email']); ?>.
                    Please bring this confirmation or your booking ID to the theater.
                </p>
            </div>
        </div>
        <a href="index.php" class="btn btn-primary mt-3">Back to Home</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// End output buffering and flush
ob_end_flush();
?>