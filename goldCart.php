<?php
session_start();
include('db.php');
 include('custHeaderGold.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize quantity for all products in cart if missing
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as &$item) {
        if (!isset($item['quantity'])) {
            $item['quantity'] = 1;
        }
    }
    unset($item); // end reference
}

// Handle + or - actions
if (isset($_GET['action']) && isset($_GET['prodID'])) {
    $prodID = $_GET['prodID'];

    foreach ($_SESSION['cart'] as $key => &$item) {
        if ($item['prodID'] == $prodID) {
            if ($_GET['action'] == 'increase') {
                if ($item['quantity'] < $item['prodQty']) {
                    $item['quantity']++;
                }
            } elseif ($_GET['action'] == 'decrease') {
                $item['quantity']--;
                if ($item['quantity'] <= 0) {
                    unset($_SESSION['cart'][$key]);
                }
            }
            break;
        }
    }
    unset($item);
    header("Location: goldCart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gold Member Cart - Miraé</title>
    <link rel="stylesheet" href="customer.css">
    <link rel="stylesheet" href="cartbronze.css">
    <style>
        /* Inline fix if needed */
        .qty-btn {
            padding: 5px 10px;
            margin: 0 5px;
            background-color: #ccc;
            color: #000;
            text-decoration: none;
            border-radius: 5px;
        }

        .qty-btn:hover {
            background-color: #999;
        }
    </style>
</head>
<body>



<!-- Dropdown script -->
<script>
    function toggleDropdown(event) {
        event.stopPropagation();
        document.getElementById('userDropdown').classList.toggle('show');
    }

    document.addEventListener('click', function () {
        const dropdown = document.getElementById('userDropdown');
        if (dropdown.classList.contains('show')) {
            dropdown.classList.remove('show');
        }
    });
</script>

<!-- Cart Section -->
<section class="cart">
    <div class="cart-container">
        <h2>Your Cart</h2>

        <?php if (!empty($_SESSION['cart'])): ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $grandTotal = 0; ?>
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                        <?php $quantity = isset($item['quantity']) ? $item['quantity'] : 1; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['prodName']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($item['prodImage']); ?>" alt="" width="80"></td>
                            <td>RM <?php echo number_format($item['prodPrice'], 2); ?></td>
                            <td>
                                <a href="goldCart.php?action=decrease&prodID=<?php echo $item['prodID']; ?>" class="qty-btn">-</a>
                                <?php echo $quantity; ?>
                                <a href="goldCart.php?action=increase&prodID=<?php echo $item['prodID']; ?>" class="qty-btn">+</a>
                            </td>
                            <td>RM <?php 
                                $total = $item['prodPrice'] * $quantity;
                                $grandTotal += $total;
                                echo number_format($total, 2); 
                            ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="cart-summary">
                <h3>Grand Total: RM <?php echo number_format($grandTotal, 2); ?></h3>
                <a href="goldCheckout.php" class="checkout-btn">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <p>Your cart is currently empty.</p>
        <?php endif; ?>
    </div>
</section>

</body>
</html>
