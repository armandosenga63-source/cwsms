<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cwsms";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add this to ensure reports are accurate to your time
date_default_timezone_set('Africa/Kigali'); 
?>