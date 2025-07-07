<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.html");
    exit();
}

require 'db.php';
// Fetch services
$result = $conn->query("SELECT * FROM services");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Service List - Miraé Admin</title>
  <link rel="stylesheet" href="adminDash.css" />
</head>
<body>

  <!-- Toggle Button -->
  <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

  <div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
      <h1>Miraé</h1>
      <ul>
        <li><a href="adminDashboard.php">Overview</a></li>
        <li><a href="memberAdmin.php">Member</a></li>
        <li><a href="product-list.php">Product List</a></li>
        <li><a href="service-list.php" class="active">Services</a></li>
        <li>About</li>
        <li>Settings</li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <h2>Service List</h2>

      <div class="top-bar">
        <a class="btn add-button" href="service-crud.php">+ Add Service</a>
      </div>

      <div class="cards">
        <div class="card">
          <h2><?= $result->num_rows ?></h2>
          <p>TOTAL SERVICES</p>
        </div>
      </div>

      <table class="product-table">
        <thead>
          <tr>
            <th>Service ID</th>
            <th>Name</th>
            <th>Price (RM)</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['serviceID']) ?></td>
              <td><?= htmlspecialchars($row['serviceName']) ?></td>
              <td>RM <?= number_format($row['servicePrice'], 2) ?></td>
              <td>
                <a href="service-crud.php?action=edit&id=<?= $row['serviceID'] ?>" class="btn edit-btn">Edit</a>
                <a href="service-crud.php?action=delete&id=<?= $row['serviceID'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this service?')">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById("sidebar").classList.toggle("collapsed");
    }
  </script>
</body>
</html>
