<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header("Location: goldCart.php");
    exit();
}

$userID = $_SESSION['user_id'];
$cart = $_SESSION['cart'];
$payDate = date('Y-m-d');
$paymentMethod = "Card";

// Apply 20% discount
function applyDiscount($price) {
    return $price * 0.80;
}

// Insert payment into database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cardName'])) {
    foreach ($cart as $item) {
        $prodID = $item['prodID'];
        $quantity = $item['quantity'] ?? 1;
        $discountedPrice = applyDiscount($item['prodPrice']);
        $amount = $discountedPrice * $quantity;

        $stmt = $conn->prepare("INSERT INTO purchase (userID, prodID, Quantity, amount, payMethod, payDate) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiidss", $userID, $prodID, $quantity, $amount, $paymentMethod, $payDate);
        $stmt->execute();
    }

    unset($_SESSION['cart']);
    echo "<script>
        alert('✅ Successfully Payment!');
        window.location.href = 'gold.php';
    </script>";
    exit();
}

// Calculate total
$totalAmount = 0;
foreach ($cart as $item) {
    $qty = $item['quantity'] ?? 1;
    $totalAmount += applyDiscount($item['prodPrice']) * $qty;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Gold Payment</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="customer.css">
  <link rel="stylesheet" href="registerPayment.css">
  <style>
    .payment-container {
      max-width: 500px;
      margin: 50px auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .pay-button {
      display: block;
      margin: 0 auto 20px auto;
      padding: 10px 20px;
      background-color: #F2B97B;
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 5px;
    }

    .form-section {
      display: flex;
      flex-direction: column;
    }

    .card-icons {
      text-align: center;
      margin-bottom: 20px;
    }

    .card-icon {
      width: 48px;
      margin: 0 5px;
    }

    .input-group {
      margin-bottom: 15px;
    }

    .input-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .input-group input {
      width: 100%;
      padding: 10px;
      font-size: 14px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    .row {
      display: flex;
      gap: 10px;
    }

    .pay-card-btn {
      margin-top: 20px;
      background-color: #ccc;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }

    .pay-card-btn:enabled {
      background-color: #28a745;
    }

    .popup {
      display: none;
      position: fixed;
      top: 30%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: #fff;
      padding: 25px 30px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      text-align: center;
      z-index: 9999;
    }

    .popup h3 {
      color: #28a745;
      font-size: 20px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
<?php include('custHeaderGold.php'); ?>

<div class="payment-container">
  <button class="pay-button">Complete Your Payment</button>

  <form class="form-section" method="POST" onsubmit="return confirmPayment()">
    <div class="card-icons">
      <img src="https://img.icons8.com/color/48/visa.png" class="card-icon" alt="Visa">
      <img src="https://img.icons8.com/color/48/mastercard-logo.png" class="card-icon" alt="MasterCard">
    </div>

    <div class="input-group">
      <label>Name on card</label>
      <input type="text" name="cardName" id="name" minlength="2" required oninput="checkForm()">
    </div>

    <div class="input-group">
      <label>Card number</label>
      <input type="text" name="cardNumber" id="cardNumber" maxlength="19" placeholder="0000 1111 2222 3333" required oninput="formatCardNumber(); checkForm();">
    </div>

    <div class="row">
      <div class="input-group">
        <label>Expiry date</label>
        <input type="text" name="expiry" id="expiry" maxlength="5" placeholder="MM/YY" required oninput="formatExpiry(); checkForm();">
      </div>
      <div class="input-group">
        <label>CVV</label>
        <input type="text" name="cvv" id="cvv" maxlength="4" placeholder="3 or 4 digits" required oninput="formatCVV(); checkForm();">
      </div>
    </div>

    <button type="submit" class="pay-card-btn" id="payBtn" disabled>Pay RM <?php echo number_format($totalAmount, 2); ?></button>
  </form>
</div>

<div class="popup" id="successPopup">
  <h3>✅ Successfully Payment!</h3>
</div>

<script>
  function formatCardNumber() {
    const input = document.getElementById('cardNumber');
    let value = input.value.replace(/\D/g, '').slice(0, 16);
    input.value = value.replace(/(.{4})/g, '$1 ').trim();
  }

  function formatExpiry() {
    const input = document.getElementById('expiry');
    let value = input.value.replace(/\D/g, '').slice(0, 4);
    if (value.length >= 3) {
      value = value.slice(0, 2) + '/' + value.slice(2);
    }
    input.value = value;
  }

  function formatCVV() {
    const input = document.getElementById('cvv');
    input.value = input.value.replace(/\D/g, '').slice(0, 4);
  }

  function checkForm() {
    const name = document.getElementById('name').value.trim();
    const cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, '');
    const expiry = document.getElementById('expiry').value;
    const cvv = document.getElementById('cvv').value;
    const btn = document.getElementById('payBtn');

    const validCard = /^[0-9]{16}$/.test(cardNumber);
    const validExpiry = /^(0[1-9]|1[0-2])\/\d{2}$/.test(expiry);
    const validCVV = /^[0-9]{3,4}$/.test(cvv);
    const validName = name.length >= 2;

    btn.disabled = !(validCard && validExpiry && validCVV && validName);
  }

  function confirmPayment() {
    const popup = document.getElementById('successPopup');
    popup.style.display = 'block';
    return true;
  }
</script>

</body>
</html>
