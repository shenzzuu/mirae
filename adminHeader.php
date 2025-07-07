<!-- adminHeader.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>

  <!-- ✅ External CSS -->
  <link rel="stylesheet" href="adminDash.css" />
</head>

<body>
  <!-- ✅ Toggle button -->
  <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

  <!-- ✅ Sidebar -->
 <div class="sidebar">
  <div class="sidebar-header">
    <h1>Miraé</h1>
  </div>
  <nav class="nav-menu">
  <a href="adminDashboard.php">🏠 Overview</a>
  <a href="appointment.php">📅 Appointments</a>
  <a href="product-list.php">🧴 Product List</a>
  <a href="adminService.php">💆 Services</a>
  <a href="aboutUs.php">ℹ️ About</a>
  <a href="logout.php">🚪 Logout</a>
</nav>
</div>


  <!-- ✅ JavaScript for toggle -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const mainContent = document.querySelector('.main-content');
      const toggleBtn = document.querySelector('.toggle-btn');

      sidebar.classList.toggle('collapsed');
      mainContent.classList.toggle('collapsed');

      // Adjust button position
      if (sidebar.classList.contains('collapsed')) {
        toggleBtn.style.left = '80px';
      } else {
        toggleBtn.style.left = '270px';
      }
    }
  </script>

  <!-- ✅ Main content starts here -->
  <div class="main-content">
