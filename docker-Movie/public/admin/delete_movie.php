<?php
session_start();
require_once '../config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is logged in, if not then redirect to the login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

// Retrieve and validate the movie ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id > 0){
    // Start a transaction
    mysqli_begin_transaction($link);

    // First, delete related showtimes
    $sql_showtimes = "DELETE FROM showtimes WHERE movie_id = ?";
    if($stmt_showtimes = mysqli_prepare($link, $sql_showtimes)){
        mysqli_stmt_bind_param($stmt_showtimes, "i", $id);
        if(!mysqli_stmt_execute($stmt_showtimes)){
            echo "Oops! Something went wrong. Could not delete related showtimes.";
            echo "<br>Error: " . mysqli_stmt_error($stmt_showtimes);
            mysqli_rollback($link); // Rollback the transaction on error
            exit();
        }
        mysqli_stmt_close($stmt_showtimes);
    }

    // Now delete the movie
    $sql_movie = "DELETE FROM movies WHERE id = ?";
    if($stmt_movie = mysqli_prepare($link, $sql_movie)){
        mysqli_stmt_bind_param($stmt_movie, "i", $id);
        if(mysqli_stmt_execute($stmt_movie)){
            mysqli_commit($link); // Commit the transaction
            header("location: dashboard.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
            echo "<br>Error: " . mysqli_stmt_error($stmt_movie);
            mysqli_rollback($link); // Rollback the transaction on error
        }
        mysqli_stmt_close($stmt_movie);
    } else {
        echo "Oops! Something went wrong during statement preparation.";
        echo "<br>Error: " . mysqli_error($link);
        mysqli_rollback($link); // Rollback the transaction on error
    }
} else{
    echo "Error: Invalid movie ID. Received: " . $id;
}

mysqli_close($link);
?>
