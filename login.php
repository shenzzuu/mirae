<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 🔐 External Railway DB connection
    $host = getenv("DB_HOST");
    $port = getenv("DB_PORT");
    $user = getenv("DB_USER");
    $pass = getenv("DB_PASS");
    $dbname = getenv("DB_NAME");

    $conn = new mysqli($host, $user, $pass, $dbname, (int)$port);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, full_name, password, membership_plan FROM customer WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['membership_plan'] = strtolower($user['membership_plan']);
            $_SESSION['membertype'] = 'member';

            switch ($_SESSION['membership_plan']) {
                case 'bronze':
                    header("Location: bronze_member.php");
                    break;
                case 'silver':
                    header("Location: silver.php");
                    break;
                case 'gold':
                    header("Location: gold.php");
                    break;
                default:
                    header("Location: profile.php");
                    break;
            }
            exit();
        } else {
            echo "<script>alert('Invalid password'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('User not found'); window.location.href='login.html';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
