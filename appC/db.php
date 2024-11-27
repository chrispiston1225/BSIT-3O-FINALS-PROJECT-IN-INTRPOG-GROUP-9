<?php
session_start();
// db.php - Connect to MySQL Database
$host = "localhost"; // Database host
$user = "root";      // Database username
$pass = "";          // Database password (default for XAMPP is empty)
$dbname = "milkteadb"; // Database name

// Create a connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
