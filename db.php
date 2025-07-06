<?php
// db.php - handles DB connection
$host = 'localhost';
$db   = 'bincom_test';
$user = 'root'; // change if needed
$pass = '';     // change if needed

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
