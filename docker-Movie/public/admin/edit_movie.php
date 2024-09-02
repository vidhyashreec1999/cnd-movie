<?php
session_start();
require_once '../config.php';

// Check if the user is logged in, if not then redirect him to the login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id > 0){
    // Fetch the movie details
    $sql = "SELECT * FROM movies WHERE id = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $id);
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $title = $row["title"];
                $description = $row["description"];
                $duration = $row["duration"];
                $image_url = $row["image_url"];
            } else{
                echo "Error: Movie not found.";
                exit();
            }
        } else{
            echo "Error: Could not execute query.";
            exit();
        }
        
        mysqli_stmt_close($stmt);
    }
} else{
    echo "Error: Invalid movie ID.";
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate inputs and update the movie in the database
    if(empty(trim($_POST["title"]))){
        $title_err = "Please enter a title.";
    } else{
        $title = trim($_POST["title"]);
    }

    if(empty(trim($_POST["description"]))){
        $description_err = "Please enter a description.";
    } else{
        $description = trim($_POST["description"]);
    }

    if(empty(trim($_POST["duration"]))){
        $duration_err = "Please enter the duration.";
    } else{
        $duration = trim($_POST["duration"]);
    }

    if(empty(trim($_POST["image_url"]))){
        $image_url_err = "Please enter an image URL.";
    } else{
        $image_url = trim($_POST["image_url"]);
    }

    if(empty($title_err) && empty($description_err) && empty($duration_err) && empty($image_url_err)){
        $sql = "UPDATE movies SET title = ?, description = ?, duration = ?, image_url = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ssisi", $param_title, $param_description, $param_duration, $param_image_url, $param_id);
            
            $param_title = $title;
            $param_description = $description;
            $param_duration = $duration;
            $param_image_url = $image_url;
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: dashboard.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Movie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Movie</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>" method="post">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                <span class="invalid-feedback"><?php echo $title_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
                <span class="invalid-feedback"><?php echo $description_err; ?></span>
            </div>
            <div class="form-group">
                <label>Duration (in minutes)</label>
                <input type="number" name="duration" class="form-control <?php echo (!empty($duration_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $duration; ?>">
                <span class="invalid-feedback"><?php echo $duration_err; ?></span>
            </div>
            <div class="form-group">
                <label>Image URL</label>
                <input type="text" name="image_url" class="form-control <?php echo (!empty($image_url_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $image_url; ?>">
                <span class="invalid-feedback"><?php echo $image_url_err; ?></span>
            </div>
            <div class="form-group mt-3">
                <input type="submit" class="btn btn-primary" value="Save Changes">
                <a href="dashboard.php" class="btn btn-secondary ml-2">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>
