<?php
$servername = "localhost";  // Usually 'localhost'
$username = "root";         // Your MySQL username (default is 'root')
$password = "";             // Your MySQL password (default is empty for local)
$dbname = "meal_planner";   // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
