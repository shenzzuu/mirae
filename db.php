<?php
// ✅ Use environment variables (for Render deployment)
$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: 3306;
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';
$db = getenv('DB_NAME') ?: 'skincare';

// ✅ Create MySQL connection
$conn = new mysqli($host, $user, $password, $db, (int)$port);

// ✅ Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
