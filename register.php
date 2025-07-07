<?php
// ✅ Use environment variables (from Render dashboard)
$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: 3306;
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$dbname = getenv('DB_NAME') ?: 'skincare';

// ✅ Connect to Railway MySQL
$conn = new mysqli($host, $user, $pass, $dbname, (int)$port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Collect POST data safely
$fullName = $_POST['fullName'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';
$plan = $_POST['membershipPlan'] ?? '';
$registeredAt = date('Y-m-d H:i:s');

// ✅ Hash the password before saving
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// ✅ Prepare and bind statement
$sql = "INSERT INTO customer (full_name, email, phone, password, membership_plan, registered_at) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ssssss", $fullName, $email, $phone, $hashedPassword, $plan, $registeredAt);

// ✅ Execute and return response
if ($stmt->execute()) {
    echo "Success";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
