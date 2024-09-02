<?php
// Disable error reporting for production
error_reporting(0);
ini_set('display_errors', 0);

// Database credentials
define('DB_SERVER', 'mysql');
define('DB_USERNAME', 'user1');
define('DB_PASSWORD', 'passwd');
define('DB_NAME', 'movie_booking');

// Attempt to connect to MySQL database
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($link === false){
    // Log the error instead of displaying it
    error_log("Database connection failed: " . mysqli_connect_error());
    die("We're experiencing technical difficulties. Please try again later.");
}

// Make the $link variable available globally
$GLOBALS['link'] = $link;

// Set a flag for connection status
$GLOBALS['db_connection_status'] = true;

// Function to safely get POST data
function getPostData($key, $default = '') {
    return isset($_POST[$key]) ? htmlspecialchars(trim($_POST[$key])) : $default;
}

// Function to safely get GET data
function getGetData($key, $default = '') {
    return isset($_GET[$key]) ? htmlspecialchars(trim($_GET[$key])) : $default;
}