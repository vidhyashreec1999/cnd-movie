<?php
require_once 'config.php';

if ($GLOBALS['db_connection_status'] === "Connected successfully") {
    echo "Database connection successful!";
} else {
    echo "Database connection failed.";
}