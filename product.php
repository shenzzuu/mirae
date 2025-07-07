

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Best Seller - Miraé</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin: 40px 0 20px;
            font-size: 28px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            justify-content: center;
            padding: 20px;
        }

        .product-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
            padding: 20px;
            position: relative;
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .product-badge {
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

        .product-info {
            padding: 15px;
        }

        .product-name {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .product-rating {
            margin-bottom: 10px;
            color: #FFD700;
        }

        .product-price {
            font-size: 20px;
            font-weight: bold;
            color: #F2B97B;
            margin-bottom: 15px;
        }

        .add-to-cart {
            display: block;
            width: 80%;
            margin: 0 auto;
            padding: 12px 0;
            background-color: #3b7ddd;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .add-to-cart:hover {
            background-color: #3069B7;
        }
    </style>
</head>
<body>

<h2>Our Best Seller</h2>

<section class="products">
    <div class="products-grid">
        <?php while ($product = $result->fetch_assoc()): ?>
            <div class="product-card">
                <div class="product-badge">ON SALE</div>
                <img src="<?= htmlspecialchars($product['prodImage']) ?>" alt="<?= htmlspecialchars($product['prodName']) ?>">
                <div class="product-info">
                    <h3 class="product-name"><?= htmlspecialchars($product['prodName']) ?></h3>
                    <div class="product-rating">⭐⭐⭐⭐⭐ <span>(4.9)</span></div>
                    <div class="product-price">RM <?= number_format($product['prodPrice'], 2) ?></div>
                    <a href="login.php" class="add-to-cart">Add to Cart</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

</body>
</html>
