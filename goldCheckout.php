<?php
session_start();
include('db.php');

// Redirect if cart is empty or not logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header("Location: goldCart.php");
    exit();
}

// Apply 20% discount
function applyDiscount($price) {
    return $price * 0.80;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gold Checkout - Miraé</title>
    <link rel="stylesheet" href="customer.css">
    <style>
        .checkout-container {
            max-width: 800px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .checkout-container h2 {
            margin-bottom: 20px;
        }

        .checkout-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .checkout-table th, .checkout-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        .checkout-form label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        .checkout-form input, .checkout-form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .checkout-btn {
            margin-top: 20px;
            background-color: #F2B97B;
            color: white;
            padding: 12px 20px;
            text-decoration: none;
            display: inline-block;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .checkout-btn:hover {
            background-color: #F1A84D;
        }
    </style>
</head>
<body>
include('custHeaderGold.php');

<div class="checkout-container">
    <h2>Checkout</h2>

    <table class="checkout-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Original Price</th>
                <th>Discounted Price (20%)</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $grandTotal = 0; ?>
            <?php foreach ($_SESSION['cart'] as $item): ?>
                <?php
                    $quantity = isset($item['quantity']) ? $item['quantity'] : 1;
                    $originalPrice = $item['prodPrice'];
                    $discountedPrice = applyDiscount($originalPrice);
                    $total = $discountedPrice * $quantity;
                    $grandTotal += $total;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['prodName']); ?></td>
                    <td><?php echo $quantity; ?></td>
                    <td>RM <?php echo number_format($originalPrice, 2); ?></td>
                    <td>RM <?php echo number_format($discountedPrice, 2); ?></td>
                    <td>RM <?php echo number_format($total, 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Grand Total after Discount: RM <?php echo number_format($grandTotal, 2); ?></h3>

    <!-- Checkout Form -->
    <form class="checkout-form" action="goldPayment.php" method="POST">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" required>

        <label for="phone">Phone Number</label>
        <input type="tel" id="phone" name="phone" required>

        <label for="address">Address</label>
        <textarea id="address" name="address" rows="3" required></textarea>

        <input type="hidden" name="totalAmount" value="<?php echo number_format($grandTotal, 2, '.', ''); ?>">

        <button type="submit" class="checkout-btn">Proceed to Payment</button>
    </form>
</div>

</body>
</html>
