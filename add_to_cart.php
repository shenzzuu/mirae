<?php
session_start();

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Get the product ID from the URL
$prodID = isset($_GET['prodID']) ? (int) $_GET['prodID'] : 0;

// Connect to the database
include('db.php');

// Query the product based on the provided prodID
$query = "SELECT * FROM products WHERE prodID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $prodID);
$stmt->execute();
$result = $stmt->get_result();

if ($product = $result->fetch_assoc()) {
    // Add product to the session cart
    $_SESSION['cart'][$prodID] = [
        'prodName' => $product['prodName'],
        'prodPrice' => $product['prodPrice'],
        'prodQty' => 1, // default quantity is 1
        'prodImage' => $product['prodImage']
    ];
}

// Redirect back to the page (optional)
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>
