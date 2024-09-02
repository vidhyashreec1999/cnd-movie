<?php
require_once 'config.php';

$sql = "SELECT DISTINCT m.* FROM movies m JOIN showtimes s ON m.id = s.movie_id ORDER BY m.title";
$result = mysqli_query($link, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Ticket Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fdfdfd; /* Light background color */
            font-family: 'Arial', sans-serif;
            color: #2125629;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            border-bottom: 5px solid #0056b3;
        }
        h1 {
            color: #343a40;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .movie-card {
    height: 100%;
    border: 1px solid #e0e0e0; /* Light border color matching the theme */
    border-radius: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease, background-color 0.3s ease;
    background: #ffffff;
    overflow: hidden;
    animation: slideInUp 0.6s ease-in-out;
}

        @keyframes slideInUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .movie-card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 15px 25px rgba(0, 0, 0, 0.15);
            background-color: #f1f3f5;
        }
        .movie-image {
            height: 350px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .movie-card:hover .movie-image {
            opacity: 0.85;
            transform: scale(1.05);
        }
        .card-body {
            padding: 20px;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #212529;
            margin-bottom: 15px;
            text-align: center;
        }
        .card-text {
            color: #6c757d;
            text-align: center;
            font-size: 1rem;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
            border-radius: 50px;
            padding: 10px 20px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }
        .admin-access {
            margin-top: 40px;
            padding: 20px;
            background-color: #343a40;
            border-radius: 10px;
            text-align: center;
            color: #ffffff;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
        }
        .admin-access:hover {
            background-color: #212529;
            transform: scale(1.05);
        }
        .admin-access a {
            color: #ffffff;
            font-weight: bold;
            font-size: 1.25rem;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .admin-access a:hover {
            color: #adb5bd;
        }
        footer {
            margin-top: 100px;
            padding: 20px 0;
            background-color: #343a40;
            color: #ffffff;
            text-align: center;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <header>
        Movie Ticket Booking System
    </header>
    <div class="container mt-5">
        <h1 class="mb-4">Now Showing</h1>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php while($movie = mysqli_fetch_assoc($result)): ?>
                <div class="col">
                    <div class="card movie-card">
                        <?php if (!empty($movie['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($movie['image_url']); ?>" class="card-img-top movie-image" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/300x450?text=No+Image" class="card-img-top movie-image" alt="No image available">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($movie['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr($movie['description'], 0, 100)) . '...'; ?></p>
                            <a href="showtimes.php?movie_id=<?php echo $movie['id']; ?>" class="btn btn-primary w-100">View Showtimes</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="admin-access mt-5">
            <a href="admin/">Admin Access</a>
        </div>
    </div>
    <footer>
        &copy; 2024 Movie Ticket Booking. All rights reserved.
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
