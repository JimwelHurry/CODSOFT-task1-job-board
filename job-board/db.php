<?php
$host = "localhost";
$username = "root"; // Default MySQL user
$password = ""; // Blank by default sa XAMPP
$database = "job_board";

// Connect to database
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


