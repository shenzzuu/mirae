<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin'])) {
  header("Location: admin_login.html");
  exit();
}

$action = $_GET['action'] ?? null;
$prodID = $_GET['id'] ?? null;

// ✅ DELETE PRODUCT + IMAGE + PURCHASE RECORDS
if ($action === 'delete' && $prodID) {
  // Delete related purchases first
  $stmt = $conn->prepare("DELETE FROM purchase WHERE prodID = ?");
  $stmt->bind_param("i", $prodID);
  $stmt->execute();

  // Get image path
  $stmt = $conn->prepare("SELECT prodImage FROM products WHERE prodID = ?");
  $stmt->bind_param("i", $prodID);
  $stmt->execute();
  $result = $stmt->get_result();
  $product = $result->fetch_assoc();

  // Delete image file
  if ($product && !empty($product['prodImage']) && file_exists($product['prodImage'])) {
    unlink($product['prodImage']);
  }

  // Delete product
  $stmt = $conn->prepare("DELETE FROM products WHERE prodID = ?");
  $stmt->bind_param("i", $prodID);
  $stmt->execute();

  header("Location: product-list.php");
  exit();
}

// ✅ ADD / EDIT PRODUCT
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $qty = $_POST['qty'];
  $editID = $_POST['prodID'] ?? null;
  $prodImage = null;

  // ✅ Handle Image Upload
  if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
    $uploadDir = "uploads/";

    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0755, true);
    }

    $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $tempName = uniqid('prod_') . '.' . $ext;
    $tempPath = $uploadDir . $tempName;

    // Remove old image if editing
    if ($editID) {
      $stmt = $conn->prepare("SELECT prodImage FROM products WHERE prodID = ?");
      $stmt->bind_param("i", $editID);
      $stmt->execute();
      $result = $stmt->get_result();
      $oldProduct = $result->fetch_assoc();

      if ($oldProduct && !empty($oldProduct['prodImage']) && file_exists($oldProduct['prodImage'])) {
        unlink($oldProduct['prodImage']);
      }
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $tempPath)) {
      $prodImage = $tempPath;
    } else {
      die("❌ Failed to move uploaded file. Check folder permissions or path.");
    }
  }

  // ✅ Update or Insert
  if ($editID) {
    $fields = "prodName=?, prodPrice=?, prodQty=?";
    $types = "sdi";
    $params = [$name, $price, $qty];

    if ($prodImage) {
      $fields .= ", prodImage=?";
      $types .= "s";
      $params[] = $prodImage;
    }

    $params[] = $editID;

    $stmt = $conn->prepare("UPDATE products SET $fields WHERE prodID=?");
    $stmt->bind_param($types . "i", ...$params);
    $stmt->execute();
  } else {
    $cols = "prodName, prodPrice, prodQty";
    $placeholders = "?, ?, ?";
    $types = "sdi";
    $params = [$name, $price, $qty];

    if ($prodImage) {
      $cols .= ", prodImage";
      $placeholders .= ", ?";
      $types .= "s";
      $params[] = $prodImage;
    }

    $stmt = $conn->prepare("INSERT INTO products ($cols) VALUES ($placeholders)");
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    $newID = $stmt->insert_id;

    if ($prodImage) {
      $finalName = "product_$newID." . pathinfo($prodImage, PATHINFO_EXTENSION);
      $finalPath = $uploadDir . $finalName;
      if (rename($prodImage, $finalPath)) {
        $stmt = $conn->prepare("UPDATE products SET prodImage=? WHERE prodID=?");
        $stmt->bind_param("si", $finalPath, $newID);
        $stmt->execute();
      }
    }
  }

  header("Location: product-list.php");
  exit();
}

// ✅ Load Product for Edit
$productToEdit = null;
if ($action === 'edit' && $prodID) {
  $stmt = $conn->prepare("SELECT * FROM products WHERE prodID = ?");
  $stmt->bind_param("i", $prodID);
  $stmt->execute();
  $result = $stmt->get_result();
  $productToEdit = $result->fetch_assoc();
}
?>

<!-- ✅ HTML & Form UI -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title><?= $productToEdit ? "Edit Product" : "Add New Product" ?> - Miraé Admin</title>
  <link rel="stylesheet" href="adminDash.css">
  <style>
    .main-content {
      padding: 20px;
      flex-grow: 1;
    }
    h1 { margin-bottom: 20px; }
    .product-form {
      display: flex;
      flex-direction: column;
      max-width: 500px;
    }
    .product-form label {
      margin-bottom: 10px;
      font-weight: bold;
    }
    .product-form input[type="text"],
    .product-form input[type="number"],
    .product-form input[type="file"] {
      padding: 8px;
      margin-top: 5px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
      width: 100%;
    }
    .product-form button {
      padding: 10px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
      margin-bottom: 10px;
    }
    .product-form button:hover {
      background-color: #0056b3;
    }
    .cancel-link {
      color: #6c757d;
      text-decoration: none;
      font-size: 14px;
    }
    .cancel-link:hover {
      text-decoration: underline;
    }
    .toggle-btn {
      position: absolute;
      top: 15px;
      left: 15px;
      background: #333;
      color: white;
      padding: 10px 15px;
      font-size: 18px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    img.preview {
      margin-top: 10px;
      max-width: 100px;
      border-radius: 5px;
    }
  </style>
</head>
<body>

<div class="dashboard-container">
  <?php include 'adminHeader.php'; ?>
  <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

  <div class="main-content">
    <h1><?= $productToEdit ? 'Edit Product' : 'Add New Product' ?></h1>

    <form method="POST" enctype="multipart/form-data" class="product-form">
      <?php if ($productToEdit): ?>
        <input type="hidden" name="prodID" value="<?= htmlspecialchars($productToEdit['prodID']) ?>">
      <?php endif; ?>

      <label>Product Name:
        <input type="text" name="name" value="<?= htmlspecialchars($productToEdit['prodName'] ?? '') ?>" required>
      </label>

      <label>Price (RM):
        <input type="number" name="price" step="0.01" value="<?= htmlspecialchars($productToEdit['prodPrice'] ?? '') ?>" required>
      </label>

      <label>Quantity:
        <input type="number" name="qty" value="<?= htmlspecialchars($productToEdit['prodQty'] ?? '') ?>" required>
      </label>

      <label>Image:
        <input type="file" name="image" accept="image/*">
        <?php if ($productToEdit && !empty($productToEdit['prodImage'])): ?>
          <br>Current: <img src="<?= htmlspecialchars($productToEdit['prodImage']) ?>" alt="Current Image" class="preview">
        <?php endif; ?>
      </label>

      <button type="submit"><?= $productToEdit ? 'Update Product' : 'Add Product' ?></button>
      <?php if ($productToEdit): ?>
        <a class="cancel-link" href="product-list.php">Cancel</a>
      <?php endif; ?>
    </form>
  </div>
</div>

<script>
function toggleSidebar() {
  const sidebar = document.querySelector('.sidebar');
  sidebar.classList.toggle('collapsed');
}
</script>

</body>
</html>
