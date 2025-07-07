<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "skincare");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get login inputs
$email = $_POST['email'];
$password = $_POST['password'];

// Query user
$sql = "SELECT * FROM customer WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {
        // Login successful - store session
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['membership_plan'] = strtolower($user['membership_plan']);
        $_SESSION['user_id'] = $user['custID']; // Assuming this is your user ID column

        // Redirect based on membership
        switch ($_SESSION['membership_plan']) {
            case 'gold':
                header("Location: gold.php");
                break;
            case 'silver':
                header("Location: silver.php");
                break;
            case 'bronze':
                header("Location: bronze_member.php");
                break;
            default:
                header("Location: profile.php"); // fallback
                break;
        }
        exit();
    } else {
        echo "<script>alert('Invalid password.'); window.location.href='login.html';</script>";
    }
} else {
    echo "<script>alert('User not found.'); window.location.href='login.html';</script>";
}

$stmt->close();
$conn->close();
?>
