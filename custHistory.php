<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$conn = new mysqli("sql200.byethost17.com", "root", "", "skincare");

$stmt = $conn->prepare("SELECT * FROM purchase WHERE userID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase History</title>
    <link rel="stylesheet" href="customer.css"> <!-- Updated link -->
</head>
<body>
    <header class="nav-container">
        <div class="logo">Miraé</div>
        <div>
            <a href="custDashboard.php">Dashboard</a>
            <a href="services.php">Services</a>
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <div class="container">
        <div class="content">
            <h1>Your Purchase History</h1>
            <table class="cart-table">
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Amount</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['prodID']; ?></td>
                    <td><?php echo $row['Quantity']; ?></td>
                    <td>RM <?php echo number_format($row['amount'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>
