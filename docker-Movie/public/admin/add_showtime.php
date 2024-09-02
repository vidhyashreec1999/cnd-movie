<?php
session_start();
require_once '../config.php';

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

$movie_id = $theater_id = $start_time = $price = "";
$movie_id_err = $theater_id_err = $start_time_err = $price_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate movie
    if(empty(trim($_POST["movie_id"]))){
        $movie_id_err = "Please select a movie.";
    } else{
        $movie_id = trim($_POST["movie_id"]);
    }
    
    // Validate theater
    if(empty(trim($_POST["theater_id"]))){
        $theater_id_err = "Please select a theater.";
    } else{
        $theater_id = trim($_POST["theater_id"]);
    }
    
    // Validate start time
    if(empty(trim($_POST["start_time"]))){
        $start_time_err = "Please enter the start time.";
    } else{
        $start_time = trim($_POST["start_time"]);
    }
    
    // Validate price
    if(empty(trim($_POST["price"]))){
        $price_err = "Please enter the price.";
    } else{
        $price = trim($_POST["price"]);
    }
    
    // Check input errors before inserting in database
    if(empty($movie_id_err) && empty($theater_id_err) && empty($start_time_err) && empty($price_err)){
        $sql = "INSERT INTO showtimes (movie_id, theater_id, start_time, price) VALUES (?, ?, ?, ?)";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "iisd", $param_movie_id, $param_theater_id, $param_start_time, $param_price);
            
            $param_movie_id = $movie_id;
            $param_theater_id = $theater_id;
            $param_start_time = $start_time;
            $param_price = $price;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: dashboard.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }
    
    mysqli_close($link);
}

// Fetch movies and theaters for dropdown
$movies_query = "SELECT id, title FROM movies";
$theaters_query = "SELECT id, name FROM theaters";

$movies_result = mysqli_query($link, $movies_query);
$theaters_result = mysqli_query($link, $theaters_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Showtime</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add New Showtime</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Movie</label>
                <select name="movie_id" class="form-control <?php echo (!empty($movie_id_err)) ? 'is-invalid' : ''; ?>">
                    <option value="">Select a movie</option>
                    <?php while($movie = mysqli_fetch_assoc($movies_result)): ?>
                        <option value="<?php echo $movie['id']; ?>"><?php echo htmlspecialchars($movie['title']); ?></option>
                    <?php endwhile; ?>
                </select>
                <span class="invalid-feedback"><?php echo $movie_id_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Theater</label>
                <select name="theater_id" class="form-control <?php echo (!empty($theater_id_err)) ? 'is-invalid' : ''; ?>">
                    <option value="">Select a theater</option>
                    <?php while($theater = mysqli_fetch_assoc($theaters_result)): ?>
                        <option value="<?php echo $theater['id']; ?>"><?php echo htmlspecialchars($theater['name']); ?></option>
                    <?php endwhile; ?>
                </select>
                <span class="invalid-feedback"><?php echo $theater_id_err; ?></span>
            </div>
            <div class="form-group">
                <label>Start Time</label>
                <input type="datetime-local" name="start_time" class="form-control <?php echo (!empty($start_time_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $start_time; ?>">
                <span class="invalid-feedback"><?php echo $start_time_err; ?></span>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input type="number" step="0.01" name="price" class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $price; ?>">
                <span class="invalid-feedback"><?php echo $price_err; ?></span>
            </div>
            <div class="form-group mt-3">
                <input type="submit" class="btn btn-primary" value="Add Showtime">
                <a href="dashboard.php" class="btn btn-secondary ml-2">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>