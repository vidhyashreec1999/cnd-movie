<?php
require_once 'config.php';

if (!isset($_GET['movie_id'])) {
    header("Location: index.php");
    exit();
}

$movie_id = $_GET['movie_id'];

$sql = "SELECT m.title, s.id AS showtime_id, s.start_time, s.price, t.name AS theater_name, t.location 
        FROM showtimes s
        JOIN movies m ON s.movie_id = m.id
        JOIN theaters t ON s.theater_id = t.id
        WHERE s.movie_id = ?";

$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $movie_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$movie_title = "";
if ($row = mysqli_fetch_assoc($result)) {
    $movie_title = $row['title'];
    mysqli_data_seek($result, 0);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Showtimes - <?php echo htmlspecialchars($movie_title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Showtimes for <?php echo htmlspecialchars($movie_title); ?></h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Theater</th>
                    <th>Location</th>
                    <th>Date & Time</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($showtime = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($showtime['theater_name']); ?></td>
                        <td><?php echo htmlspecialchars($showtime['location']); ?></td>
                        <td><?php echo htmlspecialchars($showtime['start_time']); ?></td>
                        <td>₹<?php echo htmlspecialchars($showtime['price']); ?></td>
                        <td>
                            <a href="booking.php?showtime_id=<?php echo $showtime['showtime_id']; ?>" class="btn btn-primary btn-sm">Book Now</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-secondary">Back to Movies</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>