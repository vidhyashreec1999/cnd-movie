<?php
session_start();

// Check if the user is logged in, if not then redirect to the login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
            color: #212529;
        }
        h1 {
            color: #343a40;
            font-weight: bold;
            text-align: center;
            margin-bottom: 40px;
        }
        .card-deck {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .card {
            width: 18rem;
            border: none;
            border-radius: 10px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0px 15px 25px rgba(0, 0, 0, 0.15);
        }
        .card-body {
            text-align: center;
            padding: 20px;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: #343a40;
        }
        .card-text {
            font-size: 1rem;
            margin-bottom: 20px;
            color: #6c757d;
        }
        .btn {
            border-radius: 50px;
            padding: 10px 20px;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .btn-info {
            background-color: #17a2b8;
            border: none;
        }
        .btn-info:hover {
            background-color: #138496;
            transform: scale(1.05);
        }
        .btn-warning {
            background-color: #ffc107;
            border: none;
            color: #212529;
        }
        .btn-warning:hover {
            background-color: #e0a800;
            transform: scale(1.05);
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-success:hover {
            background-color: #218838;
            transform: scale(1.05);
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to the Admin Dashboard.</h1>
        <div class="card-deck">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Add New Movie</h5>
                    <p class="card-text">Add new movies to your collection.</p>
                    <a href="add_movie.php" class="btn btn-primary">Add Movie</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Add New Showtime</h5>
                    <p class="card-text">Schedule new showtimes for movies.</p>
                    <a href="add_showtime.php" class="btn btn-info">Add Show Time</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">View Bookings</h5>
                    <p class="card-text">Check the bookings made by users.</p>
                    <a href="view_bookings.php" class="btn btn-warning">View Bookings</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Modify Movies</h5>
                    <p class="card-text">Edit or Delete Movies</p>
                    <a href="modify_movies.php" class="btn btn-primary">Modify Movies</a>
                </div>
            </div>
        </div>
        <div class="text-center mt-5">
            <a href="logout.php" class="btn btn-danger">Sign Out</a>
        </div>
    </div>
</body>
</html>
