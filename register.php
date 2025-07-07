<?php
// Database connection
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'skincare';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$fullName = $_POST['fullName'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';
$plan = $_POST['membershipPlan'] ?? '';
$registeredAt = date('Y-m-d H:i:s');

// ✅ Hash the password before saving
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Prepare SQL
$sql = "INSERT INTO customer (full_name, email, phone, password, membership_plan, registered_at) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
  die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ssssss", $fullName, $email, $phone, $hashedPassword, $plan, $registeredAt);

// Execute
if ($stmt->execute()) {
  echo "Success";
} else {
  echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
