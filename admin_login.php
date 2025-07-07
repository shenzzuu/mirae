<?php
session_start();

$conn = new mysqli("localhost", "root", "", "skincare");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT password FROM admin WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($storedPassword);
    $stmt->fetch();

    // Compare plain password (ONLY if not using password_hash yet)
    if ($password === $storedPassword) {
        $_SESSION['admin'] = $username;
        header("Location: adminDashboard.php");
        exit();
    } else {
        // Show alert for incorrect password
        echo "<script>alert('❌ Incorrect password.'); window.location.href = 'admin_login.html';</script>";
    }
} else {
    // Show alert for username not found
    echo "<script>alert('❌ Username not found.'); window.location.href = 'admin_login.html';</script>";
}

$stmt->close();
$conn->close();
?>