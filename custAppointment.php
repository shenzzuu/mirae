<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $serviceID = $_POST['serviceID'];
    $appointmentDate = $_POST['date'];
    $userID = $_SESSION['user_id'];

    $conn = new mysqli("localhost" , "root", "", "skincare");

    // Insert the appointment into the Appointments table
    $query = "INSERT INTO appointments (userID, serviceID, appointmentDate, status) VALUES (?, ?, ?, 'pending')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $userID, $serviceID, $appointmentDate);
    $stmt->execute();

    echo "<script>alert('Your appointment is confirmed for $name on $appointmentDate'); window.location.href='custHistory.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="customer.css">
</head>
<body>
    <?php include('custGold.php'); ?>

    <div class="container">
        <div class="content">
            <h1>Your Appointment is Confirmed</h1>
            <p>Thank you for booking with us! You have successfully scheduled the service.</p>
        </div>
    </div>
</body>
</html>
