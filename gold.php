<?php
session_start();
include('db.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'Gold Member';

// Handle add-to-cart logic BEFORE any output
$product_added = false;

if (isset($_GET['prodID'])) {
    $prodID = $_GET['prodID'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $query_product_details = "SELECT * FROM products WHERE prodID = ?";
    $stmt_product_details = $conn->prepare($query_product_details);
    $stmt_product_details->bind_param("i", $prodID);
    $stmt_product_details->execute();
    $res = $stmt_product_details->get_result();
    $product = $res->fetch_assoc();

    if ($product) {
        $found = false;
        foreach ($_SESSION['cart'] as &$cartItem) {
            if ($cartItem['prodID'] == $prodID) {
                if ($cartItem['quantity'] < $product['prodQty']) {
                    $cartItem['quantity'] += 1;
                } else {
                    $cartItem['quantity'] = $product['prodQty'];
                }
                $found = true;
                break;
            }
        }

        if (!$found) {
            $product['quantity'] = 1;
            $_SESSION['cart'][] = $product;
        }

        header("Location: gold.php?product_added=true");
        exit();
    }
}

if (isset($_GET['product_added'])) {
    $product_added = true;
}

// ✅ Include header after redirect logic
include('custHeaderGold.php');

// Fetch all products
$query = "SELECT * FROM products";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="customer.css">
    <title>Miraé - Gold Member</title>
    <style>
        .success-popup {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4CAF50;
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            z-index: 1001;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
            display: none;
        }

        .success-popup.show {
            opacity: 1;
            display: block;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        .product-card .product-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #F2B97B;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .product-card .product-info {
            padding: 15px;
            text-align: center;
        }

        .product-card .product-name {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .product-card .product-rating {
            margin-bottom: 10px;
        }

        .product-card .product-price {
            font-size: 20px;
            font-weight: bold;
            color: #F2B97B;
            margin-bottom: 15px;
        }

        .product-card .add-to-cart {
            width: 80%;
            padding: 12px 0;
            font-size: 16px;
            display: block;
            margin: 0 auto;
        }

        .product-rating .stars {
            color: #FFD700;
            letter-spacing: 2px;
        }

        .view-cart-button {
            display: flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
            gap: 5px;
            min-width: 120px;
            padding: 10px 15px;
            background-color:#3b7ddd;
            color:#fff;
            text-decoration:none;
            border-radius:5px;
            transition: background-color 0.3s;
        }

        .view-cart-button:hover {
            background-color: #3069B7;
        }
    </style>
</head>
<body>

<div style="display: flex; justify-content: space-between; align-items: center; padding: 20px;">
    <h2 style="margin: 0 auto; text-align: center; flex-grow: 1;">
        Welcome, <?= htmlspecialchars($user_name) ?> - Miraé Gold Member
    </h2>
    <div style="flex-shrink: 0;">
        <a href="goldCart.php" class="view-cart-button">🛒 View Cart</a>
    </div>
</div>

<?php if ($product_added): ?>
    <div id="success-popup" class="success-popup show">
        <p>Product successfully added to your cart!</p>
    </div>
<?php endif; ?>

<section class="products" id="products">
    <div class="products-container">
        <h2 class="section-title">Our Best Seller</h2>
        <div class="products-grid">
            <?php while ($product = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <div class="product-image">
                        <div class="product-badge">ON SALE</div>
                        <img src="<?= htmlspecialchars($product['prodImage']) ?>" alt="<?= htmlspecialchars($product['prodName']) ?>" style="width: 100%; height: 200px; object-fit: cover;">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name"><?= htmlspecialchars($product['prodName']) ?></h3>
                        <div class="product-rating">
                            <span class="stars">⭐⭐⭐⭐⭐</span>
                            <span>(4.9)</span>
                        </div>
                        <div class="product-price">RM <?= number_format($product['prodPrice'], 2) ?></div>
                        <a href="gold.php?prodID=<?= htmlspecialchars($product['prodID']) ?>" class="add-to-cart">Add to Cart</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<script>
    setTimeout(() => {
        const popup = document.getElementById('success-popup');
        if (popup) {
            popup.classList.remove('show');
        }
    }, 3000);
</script>
</body>
</html>
