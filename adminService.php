<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: admin_login.html");
  exit();
}
require 'db.php';

// Delete service
if (isset($_GET['delete'])) {
  $id = (int) $_GET['delete'];
  $deleteQuery = "DELETE FROM services WHERE serviceID = $id";
  mysqli_query($conn, $deleteQuery);
  header("Location: adminService.php");
  exit();
}

$query = "SELECT * FROM services";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Services - Miraé Admin</title>
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

    .service-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    .service-table th, .service-table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    .service-table th {
      background-color: #f2f2f2;
      font-weight: bold;
    }

    .service-table tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .service-table tr:hover {
      background-color: #f1f1f1;
    }

    .btn {
      display: inline-block;
      padding: 5px 10px;
      border-radius: 3px;
      text-decoration: none;
      font-size: 13px;
      margin-right: 5px;
    }

    .btn.add-button {
      padding: 10px 15px;
      background-color: #007bff;
      color: white;
      border-radius: 5px;
      font-size: 14px;
    }

    .btn.add-button:hover {
      background-color: #0056b3;
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
    <h2>Manage Services</h2>

    <div class="top-bar">
      <a class="btn add-button" href="adminServiceCrud.php">+ Add Service</a>
    </div>

    <table class="service-table">
      <thead>
        <tr>
          <th>Image</th>
          <th>Name</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td>
                <?php if (!empty($row['serviceImage'])): ?>
                  <img src="uploads/<?= htmlspecialchars($row['serviceImage']) ?>" class="thumb" alt="Service Image">
                <?php else: ?>
                  <span class="no-image">No image</span>
                <?php endif; ?>
              </td>
              <td><?= htmlspecialchars($row['serviceName']) ?></td>
              <td><?= nl2br(htmlspecialchars($row['serviceDesc'])) ?></td>
              <td>
                <a href="adminServiceCrud.php?id=<?= $row['serviceID'] ?>" class="btn edit-btn">Edit</a>
                <a href="adminService.php?delete=<?= $row['serviceID'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure you want to delete this service?')">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" style="text-align:center;">No services available.</td>
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
