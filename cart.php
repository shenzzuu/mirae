<?php
session_start();

// Add to cart logic
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['prodID'])) {
    $item = [
        'prodID' => $_POST['prodID'],
        'name' => $_POST['prodName'],
        'price' => $_POST['price'],
        'qty' => $_POST['qty']
    ];
    
    $_SESSION['cart'][] = $item;
    $message = "Item added to cart!";
}

$cart = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Your Cart</title>
  <link rel="stylesheet" href="productcrud.css">
</head>
<body>
<div class="list-container">
  <h1>Your Shopping Cart</h1>
  <?php if (isset($message)) echo "<p style='color:green;'>$message</p>"; ?>
  
  <?php if (count($cart) === 0): ?>
    <p>Your cart is empty.</p>
  <?php else: ?>
    <form action="checkout.php" method="POST">
      <table class="product-table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Price (RM)</th>
            <th>Qty</th>
            <th>Total (RM)</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $grandTotal = 0;
          foreach ($cart as $item):
            $lineTotal = $item['price'] * $item['qty'];
            $grandTotal += $lineTotal;
          ?>
            <tr>
              <td><?= htmlspecialchars($item['name']) ?></td>
              <td><?= number_format($item['price'], 2) ?></td>
              <td><?= htmlspecialchars($item['qty']) ?></td>
              <td><?= number_format($lineTotal, 2) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <h3>Total: RM <?= number_format($grandTotal, 2) ?></h3>
      <input type="hidden" name="grand_total" value="<?= number_format($grandTotal, 2, '.', '') ?>">
      <button type="submit" class="checkout-btn">Proceed to Payment</button>
    </form>
  <?php endif; ?>

  <a class="back-link" href="customer_product.php">← Continue Shopping</a>
</div>
</body>
</html>
