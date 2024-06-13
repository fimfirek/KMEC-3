<?php
$servername = "localhost";   // Server name, usually 'localhost' for local development
$username = "userdb";          // MySQL username
$password = "databaza";              // MySQL password (leave empty if no password is set)
$database = "northwindmysql"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully"; // Optional: Confirmation message
?>
