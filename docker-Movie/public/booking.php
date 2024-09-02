<?php
// Start output buffering
ob_start();

// Include the configuration file
require_once 'config.php';

// Check if showtime_id is provided
$showtime_id = getGetData('showtime_id');
if (!$showtime_id) {
    header("Location: index.php");
    exit();
}

// Prepare and execute query to get showtime details
$sql = "SELECT m.title, s.start_time, s.price, t.name AS theater_name
        FROM showtimes s
        JOIN movies m ON s.movie_id = m.id
        JOIN theaters t ON s.theater_id = t.id
        WHERE s.id = ?";

$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $showtime_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$showtime = mysqli_fetch_assoc($result);

// Check if showtime exists
if (!$showtime) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

// Process the booking form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = getPostData('customer_name');
    $customer_email = getPostData('customer_email');
    $seats = getPostData('seats');
    
    // Validate input
    if (empty($customer_name) || empty($customer_email) || !filter_var($customer_email, FILTER_VALIDATE_EMAIL) || !is_numeric($seats) || $seats < 1) {
        $error = "Please fill all fields correctly.";
    } else {
        $total_price = $seats * $showtime['price'];

        $sql = "INSERT INTO bookings (showtime_id, customer_name, customer_email, seats, total_price) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "issis", $showtime_id, $customer_name, $customer_email, $seats, $total_price);
        
        if (mysqli_stmt_execute($stmt)) {
            $booking_id = mysqli_insert_id($link);
            header("Location: confirmation.php?booking_id=" . $booking_id);
            exit();
        } else {
            $error = "Oops! Something went wrong. Please try again later.";
        }
    }
}

// HTML content starts here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Tickets - <?php echo htmlspecialchars($showtime['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Book Tickets</h1>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($showtime['title']); ?></h5>
                <p class="card-text">
                    Theater: <?php echo htmlspecialchars($showtime['theater_name']); ?><br>
                    Date & Time: <?php echo htmlspecialchars($showtime['start_time']); ?><br>
                    Price per ticket: ₹<?php echo htmlspecialchars($showtime['price']); ?>
                </p>
            </div>
        </div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?showtime_id=" . $showtime_id; ?>" method="post">
            <div class="mb-3">
                <label for="customer_name" class="form-label">Your Name</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
            </div>
            <div class="mb-3">
                <label for="customer_email" class="form-label">Your Email</label>
                <input type="email" class="form-control" id="customer_email" name="customer_email" required>
            </div>
            <div class="mb-3">
                <label for="seats" class="form-label">Number of Seats</label>
                <input type="number" class="form-control" id="seats" name="seats" min="1" max="10" required>
            </div>
            <button type="submit" class="btn btn-primary">Book Now</button>
        </form>
        <a href="index.php" class="btn btn-secondary mt-3">Back to Movies</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// End output buffering and flush
ob_end_flush();
?>