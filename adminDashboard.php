<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: admin_login.html");
  exit();
}

$conn = new mysqli("localhost", "root", "", "skincare");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Count summary
function getCount($conn, $query) {
  $res = $conn->query($query);
  return $res ? $res->fetch_assoc()['cnt'] : 0;
}

$customer_count    = getCount($conn, "SELECT COUNT(*) AS cnt FROM customer");
$appointments_count  = getCount($conn, "SELECT COUNT(*) AS cnt FROM appointments");
$services_count = getCount($conn, "SELECT COUNT(*) AS cnt FROM services");
$products_count    = getCount($conn, "SELECT COUNT(*) AS cnt FROM products");

$members_sql = "SELECT id, full_name, email, phone, membership_plan, registered_at FROM customer ORDER BY registered_at DESC";
$members_result = $conn->query($members_sql);

if (!$members_result) {
  die("<p style='color:red;'>Query failed: " . $conn->error . "</p>");
}
?>

<?php include 'adminHeader.php'; ?>
<button class="toggle-btn" onclick="toggleSidebar()">☰</button>

<div class="cards">
  <a href="appointment.php" class="card-link">
    <div class="card">
      <h2><?= $appointments_count ?></h2>
      <p>TOTAL APPOINTMENTS</p>
    </div>
  </a>
  
  <a href="adminService.php" class="card-link">
    <div class="card">
      <h2><?= $services_count ?></h2>
      <p>TOTAL SERVICES</p>
    </div>
  </a>
  
  <a href="adminDashboard.php" class="card-link">
    <div class="card">
      <h2><?= $customer_count ?></h2>
      <p>TOTAL MEMBERSHIP</p>
    </div>
  </a>
  
  <a href="product-list.php" class="card-link">
    <div class="card">
      <h2><?= $products_count ?></h2>
      <p>TOTAL PRODUCT</p>
    </div>
  </a>
</div>


  <h2 style="margin-top: 2rem;">Recent Registered Members</h2>
  <div class="table-responsive">
    <table class="product-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Membership Plan</th>
          <th>Registered At</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($members_result->num_rows > 0): ?>
          <?php while ($row = $members_result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['id']) ?></td>
              <td><?= htmlspecialchars($row['full_name']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['phone']) ?></td>
              <td><?= htmlspecialchars($row['membership_plan']) ?></td>
              <td><?= htmlspecialchars($row['registered_at']) ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="6" class="no-data">No members found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</div> <!-- End main-content -->
<script>
  function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('collapsed');
  }
</script>

</body>
</html>

<?php $conn->close(); ?>
