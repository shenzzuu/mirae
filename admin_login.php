<?php
session_start();

$host = getenv("DB_HOST");
$port = getenv("DB_PORT");
$user = getenv("DB_USER");
$pass = getenv("DB_PASS");
$dbname = getenv("DB_NAME");

$conn = new mysqli($host, $user, $pass, $dbname, (int)$port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($storedPassword);
        $stmt->fetch();

        // 🔐 Compare passwords (replace with password_verify if hashed)
        if ($password === $storedPassword) {
            $_SESSION['admin'] = $username;
            header("Location: adminDashboard.php");
            exit();
        } else {
            echo "<script>alert('❌ Incorrect password.'); window.location.href = 'admin_login.html';</script>";
        }
    } else {
        echo "<script>alert('❌ Username not found.'); window.location.href = 'admin_login.html';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
