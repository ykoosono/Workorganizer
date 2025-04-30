<?php
// Database credentials
$host     = 'localhost';
$username = 'root';        // Change if using a different user
$password = '';            // Change if your MySQL user has a password
$dbname   = 'workorganizer_db';

// Create the database connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection and handle errors
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Optional: Set charset
$conn->set_charset("utf8mb4");
?>
