<?php
session_start();
include('db.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include('custHeaderGold.php');

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'Gold Member';
// Fetch all products (this is correct, as gold.php should show all)
$query = "SELECT * FROM products";
$result = $conn->query($query);

// Handle add-to-cart (existing logic)
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="customer.css">
    <title>Miraé -  Gold Member</title>
    <style>
        /* Specific styles for the success popup */
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
            display: none; /* Hidden by default */
        }

        .success-popup.show {
            opacity: 1;
            display: block;
        }
        /* Adjusted .products-grid for a consistent layout (e.g., 3 columns) */
        .products-grid { /* Removed .best-seller-grid class as all products will use this */
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); /* Responsive grid */
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        /* Specific styling for the badges for consistent look */
        .product-card .product-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #F2B97B; /* Orange color from your image */
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Adjust product-info padding and text alignment to match the image */
        .product-card .product-info {
            padding: 15px; /* Increase padding for more space */
            text-align: center; /* Ensure text is centered */
        }

        .product-card .product-name {
            font-size: 18px;
            margin-bottom: 5px; /* Space between name and rating */
        }

        .product-card .product-rating {
            margin-bottom: 10px; /* Space between rating and price */
        }

        .product-card .product-price {
            font-size: 20px; /* Make price more prominent */
            font-weight: bold;
            color: #F2B97B; /* Orange price color */
            margin-bottom: 15px; /* Space before add to cart button */
        }

        .product-card .add-to-cart {
            width: 80%; /* Make button wider */
            padding: 12px 0; /* Increase button padding */
            font-size: 16px;
            display: block; /* Make it a block element to center with margin auto */
            margin: 0 auto; /* Center the button */
        }

        /* Additional styling for the stars */
        .product-rating .stars {
            color: #FFD700; /* Gold color for stars */
            letter-spacing: 2px; /* Space out the stars */
        }

        /* Styling for the View Cart button */
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
                            <img src="<?php echo htmlspecialchars($product['prodImage']); ?>" alt="<?php echo htmlspecialchars($product['prodName']); ?>" style="width: 100%; height: 200px; object-fit: cover;">
                        </div>
                        <div class="product-info">
                            <h3 class="product-name"><?php echo htmlspecialchars($product['prodName']); ?></h3>
                            <div class="product-rating">
                                <span class="stars">⭐⭐⭐⭐⭐</span>
                                <span>(4.9)</span>
                            </div>
                            <div class="product-price">RM <?php echo number_format($product['prodPrice'], 2); ?></div>
                            <a href="gold.php?prodID=<?php echo htmlspecialchars($product['prodID']); ?>" class="add-to-cart">Add to Cart</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <script>
        // Hide popup after 3 seconds
        setTimeout(() => {
            const popup = document.getElementById('success-popup');
            if (popup) {
                popup.classList.remove('show');
            }
        }, 3000);
    </script>
</body>
</html>