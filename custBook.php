<?php
session_start();
include('database.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $serviceID = $_GET['serviceID']; // Get the service ID from URL
}

$conn = new mysqli("localhost", "root", "", "skincare");

$query = "SELECT * FROM services WHERE serviceID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $serviceID);
$stmt->execute();
$result = $stmt->get_result();
$service = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Service</title>
    <link rel="stylesheet" href="customer.css">
</head>
<body>
    <?php include('custHeader.php'); ?>

    <div class="container">
        <div class="content">
            <h1>Book Your Service: <?php echo $service['serviceName']; ?></h1>

            <form action="custAppointment.php" method="POST">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" required><br><br>

                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" required><br><br>

                <label for="date">Select Date:</label>
                <input type="date" id="date" name="date" required><br><br>

                <input type="hidden" name="serviceID" value="<?php echo $serviceID; ?>">

                <button type="submit">Book Now</button>
            </form>
        </div>
    </div>
</body>
</html>
