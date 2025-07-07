<?php
$host = "localhost"; // change this if your MySQL is hosted elsewhere
$user = "root";
$password = ""; // change this if your MySQL has a password
$db = "skincare";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
