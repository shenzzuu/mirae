<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: admin_login.html");
  exit();
}
require 'db.php';

$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Product List - Miraé Admin</title>
  <link rel="stylesheet" href="adminDash.css">
  <style>
    .main-content {
      padding: 20px;
      flex-grow: 1;
    }

    .top-bar {
      margin-bottom: 20px;
      display: flex;
      justify-content: flex-end;
    }

    .product-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    .product-table th, .product-table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    .product-table th {
      background-color: #f2f2f2;
      font-weight: bold;
    }

    .product-table tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .product-table tr:hover {
      background-color: #f1f1f1;
    }

    .btn.add-button {
      padding: 10px 15px;
      background-color: #007bff;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-size: 14px;
    }

    .btn.add-button:hover {
      background-color: #0056b3;
    }

    .btn.edit-btn,
    .btn.delete-btn {
      padding: 5px 10px;
      border-radius: 3px;
      text-decoration: none;
      font-size: 13px;
      margin-right: 5px;
    }

    .btn.edit-btn {
      background-color: #ffc107;
      color: #212529;
    }

    .btn.edit-btn:hover {
      background-color: #e0a800;
    }

    .btn.delete-btn {
      background-color: #dc3545;
      color: white;
    }

    .btn.delete-btn:hover {
      background-color: #c82333;
    }

    .thumb {
      width: 80px;
      height: auto;
      border-radius: 5px;
    }

    .no-image {
      color: #999;
      font-style: italic;
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
  </style>
</head>
<body>

<div class="dashboard-container">
  <?php include 'adminHeader.php'; ?>

  <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

  <div class="main-content">
    <h2>Product List</h2>

    <div class="top-bar">
      <a class="btn add-button" href="product-crud.php">+ Add Product</a>
    </div>

    <table class="product-table">
      <thead>
        <tr>
          <th>Image</th>
          <th>Name</th>
          <th>Price (RM)</th>
          <th>Quantity</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td>
                <?php if (!empty($row['prodImage'])): ?>
                  <img src="<?= htmlspecialchars($row['prodImage']) ?>" class="thumb" alt="Product Image">
                <?php else: ?>
                  <span class="no-image">No image</span>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($row['prodName']) ?></td>
              <td>RM <?= number_format($row['prodPrice'], 2) ?></td>
              <td><?= htmlspecialchars($row['prodQty']) ?></td>
              <td>
                <a href="product-crud.php?action=edit&id=<?= htmlspecialchars($row['prodID']) ?>" class="btn edit-btn">Edit</a>
                <a href="product-crud.php?action=delete&id=<?= htmlspecialchars($row['prodID']) ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" style="text-align:center;">No products available.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
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
