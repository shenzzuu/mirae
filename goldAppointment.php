<?php
session_start();
include('custHeaderGold.php');
include('db.php');

if (!isset($_SESSION['user_id'])) {
    echo "<p>Please <a href='login.html'>login</a> first.</p>";
    exit();
}

$userID = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointmentDate'], $_POST['serviceID'])) {

    $appointmentDate = trim($_POST['appointmentDate']);
    $timeSlot = trim($_POST['timeSlot']);
    $serviceID = intval($_POST['serviceID']);
    $status = 'pending';

    if (!$appointmentDate || !$timeSlot || !$serviceID) {
        echo "<p>Error: Missing required fields.</p>";
        exit();
    }

    // Check if this service is full for the date
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM appointments WHERE serviceID = ? AND appointmentDate = ?");
    $stmt->bind_param("is", $serviceID, $appointmentDate);
    $stmt->execute();
    $countResult = $stmt->get_result()->fetch_assoc();

    if ($countResult['total'] >= 3) {
        echo "<script>alert('This treatment is fully booked for this date.'); window.location.href='gold_services.php';</script>";
        exit();
    }

    // Check if this time slot is already booked for this service/date
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM appointments WHERE appointmentDate = ? AND timeSlot = ?");
    $stmt->bind_param("ss", $appointmentDate, $timeSlot);
    $stmt->execute();
    $slotResult = $stmt->get_result()->fetch_assoc();

    if ($slotResult['total'] >= 1) {
        echo "<script>alert('This time slot is already taken. Please choose another.'); window.location.href='gold_services.php';</script>";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO appointments (userID, serviceID, appointmentDate, timeSlot, status)
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $userID, $serviceID, $appointmentDate, $timeSlot, $status);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Booking created successfully.'); window.location.href='goldAppointment.php';</script>";
}

// Cancel or update status
if (isset($_POST['updateStatus'], $_POST['appointmentID'])) {
    $newStatus = $_POST['updateStatus'];
    $apptID = intval($_POST['appointmentID']);

    if ($newStatus === 'canceled') {
        $stmt = $conn->prepare("DELETE FROM appointments WHERE appointmentID = ? AND userID = ?");
        $stmt->bind_param("ii", $apptID, $userID);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Appointment canceled.'); window.location.href='goldAppointment.php';</script>";
    } else {
        $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE appointmentID = ? AND userID = ?");
        $stmt->bind_param("sii", $newStatus, $apptID, $userID);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Status updated.'); window.location.href='goldAppointment.php';</script>";
    }
}

// Show all bookings for this user
$stmt = $conn->prepare("SELECT a.*, s.serviceName FROM appointments a JOIN services s ON a.serviceID = s.serviceID WHERE a.userID = ? ORDER BY a.appointmentDate DESC");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>My Gold Appointments</h2>

<table>
  <thead>
    <tr>
      <th>Service</th>
      <th>Date</th>
      <th>Time</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['serviceName']) ?></td>
          <td><?= htmlspecialchars($row['appointmentDate']) ?></td>
          <td><?= htmlspecialchars($row['timeSlot']) ?></td>
          <td>
            <form method="POST" class="status-form" onsubmit="return confirmCancel(this);">
              <input type="hidden" name="appointmentID" value="<?= $row['appointmentID'] ?>">
              <select name="updateStatus" required>
                <option value="pending" <?= $row['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="completed" <?= $row['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="canceled">Cancel</option>
              </select>
              <button type="submit">Update</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="4">No appointments found.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<script>
function confirmCancel(form) {
  const select = form.querySelector("select[name='updateStatus']");
  if (select.value === 'canceled') {
    return confirm("Are you sure you want to cancel this appointment?");
  }
  return true;
}
</script>
<link rel="stylesheet" href="customer.css">

<style>
h2 { text-align: center; margin: 30px 0 20px; font-family: Arial, sans-serif; }
table { width: 90%; max-width: 800px; margin: 0 auto 50px; border-collapse: collapse; font-family: Arial, sans-serif; }
table th, table td { border: 1px solid #ddd; padding: 12px 15px; text-align: center; }
table th { background: #333; color: #fff; }
table tr:nth-child(even) { background: #f9f9f9; }
.status-form select, .status-form button { padding: 6px 8px; font-size: 14px; border-radius: 4px; border: 1px solid #ccc; }
.status-form button { background: #333; color: #fff; border: none; cursor: pointer; }
.status-form button:hover { background: #555; }
</style>

<?php $stmt->close(); ?>
