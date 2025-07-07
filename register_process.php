<?php
// register_process.php
$host = 'localhost';
$user = 'root';
$pass = ''; // Change this if needed
$db = 'skincare'; // Change this to your actual database name
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$fullName = $_POST['fullName'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt the password
$plan = $_POST['membershipPlan'];

$sql = "INSERT INTO customer (full_name, email, phone, password, membership_plan)
        VALUES ('$fullName', '$email', '$phone', '$password', '$plan')";

if ($conn->query($sql) === TRUE) {
  echo "<script>alert('Registration successful!'); window.location.href='login.html';</script>";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
