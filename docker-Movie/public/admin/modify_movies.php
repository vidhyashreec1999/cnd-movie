<?php
session_start();
require_once '../config.php';

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

// Fetch the list of movies
$sql = "SELECT id, title FROM movies";
$result = mysqli_query($link, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Modify Movies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            color: #212529;
        }
        h2 {
            color: #343a40;
            font-weight: bold;
            text-align: center;
            margin-bottom: 40px;
        }
        .movie-list {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
        }
        .movie-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .movie-item:last-child {
            border-bottom: none;
        }
        .movie-title {
            font-size: 1.1rem;
            color: #343a40;
        }
        .btn-group {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Modify Movies</h2>
        <div class="movie-list">
            <?php while($movie = mysqli_fetch_assoc($result)): ?>
                <div class="movie-item">
                    <div class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></div>
                    <div class="btn-group">
                        <a href="edit_movie.php?id=<?php echo $movie['id']; ?>" class="btn btn-info btn-sm">Edit</a>
                        <a href="delete_movie.php?id=<?php echo $movie['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this movie?');">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
