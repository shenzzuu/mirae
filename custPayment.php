<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Save the purchase in the database
    $user_id = $_SESSION['user_id'];
    $membership_plan = $_SESSION['membership_plan'];
    $cart_items = $_SESSION['cart_items'];

    $conn = new mysqli("localhost", "root", "", "skincare");

    // Insert into purchase table
    foreach ($cart_items as $item) {
        $prodID = $item['product_id'];
        $quantity = $item['quantity'];
        $total_price = $item['price'] * $quantity;

        $stmt = $conn->prepare("INSERT INTO purchase (userID, prodID, Quantity, amount) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $user_id, $prodID, $quantity, $total_price);
        $stmt->execute();
    }

    // Clear the cart after purchase
    unset($_SESSION['cart_items']);
    echo "<script>alert('Payment Successful!'); window.location.href='custHistory.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
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
            <h1>Payment Details</h1>
            <form action="custPayment.php" method="POST">
                <label for="card_name">Name on Card:</label>
                <input type="text" id="card_name" name="card_name" required><br><br>

                <label for="card_number">Card Number:</label>
                <input type="text" id="card_number" name="card_number" required><br><br>

                <label for="expiry_date">Expiry Date:</label>
                <input type="text" id="expiry_date" name="expiry_date" required><br><br>

                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" required><br><br>

                <button type="submit" name="checkout">Complete Payment</button>
            </form>
        </div>
    </div>
</body>
</html>
