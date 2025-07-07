<?php
include('custHeader.php');
include('db.php');
session_start();

$userID = $_SESSION['userID'] ?? 1; // fallback for demo only
$serviceID = 5; // Bronze = Facial

// Get fully booked dates for this service (3 or more)
$query = "SELECT appointmentDate FROM appointments 
          WHERE serviceID = ? 
          GROUP BY appointmentDate HAVING COUNT(*) >= 3";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $serviceID);
$stmt->execute();
$result = $stmt->get_result();
$disabledDates = [];
while ($row = $result->fetch_assoc()) {
  $disabledDates[] = $row['appointmentDate'];
}
$stmt->close();

// Get all booked slots for this service and date for JS
$slotsQuery = "SELECT appointmentDate, timeSlot FROM appointments WHERE serviceID = ?";
$stmt = $conn->prepare($slotsQuery);
$stmt->bind_param("i", $serviceID);
$stmt->execute();
$slotsResult = $stmt->get_result();
$bookedSlots = [];
while ($row = $slotsResult->fetch_assoc()) {
  $date = $row['appointmentDate'];
  $slot = $row['timeSlot'];
  if (!isset($bookedSlots[$date])) {
    $bookedSlots[$date] = [];
  }
  $bookedSlots[$date][] = $slot;
}
$stmt->close();
?>

<div class="form-wrapper">
  <h2>Book Bronze Spa Treatment</h2>

  <form action="bronzeAppointment.php" method="POST" class="spa-form">
    <input type="hidden" name="serviceID" value="<?= $serviceID ?>">

    <label>Appointment Date</label>
    <input type="date" name="appointmentDate" id="appointmentDate" required>

    <label>Time Slot</label>
    <select name="timeSlot" id="timeSlot" required>
      <option value="">Select Time</option>
      <?php
      $slots = ["10:00 AM", "11:30 AM", "1:00 PM", "2:30 PM", "4:30 PM", "6:30 PM", "7:30 PM", "9:00 PM", "10:30 PM"];
      foreach ($slots as $slot) echo "<option value='$slot'>$slot</option>";
      ?>
    </select>

    <label>Full Name</label>
    <input type="text" name="fullName" required>

    <label>Phone</label>
    <input type="text" name="phone" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <input type="hidden" name="userID" value="<?= $userID ?>">
    <button type="submit">Book Now</button>
  </form>
</div>

<script>
const disabledDates = <?= json_encode($disabledDates) ?>;
const bookedSlots = <?= json_encode($bookedSlots) ?>;
const dateInput = document.getElementById('appointmentDate');
const timeSlot = document.getElementById('timeSlot');

dateInput.addEventListener('input', () => {
  const picked = dateInput.value;
  if (disabledDates.includes(picked)) {
    alert('This treatment is fully booked for the selected day. Please choose another date.');
    dateInput.value = '';
    return;
  }

  // Reset timeSlot options
  const options = timeSlot.querySelectorAll('option');
  options.forEach(opt => opt.disabled = false);

  if (bookedSlots[picked]) {
    const takenSlots = bookedSlots[picked];
    options.forEach(opt => {
      if (takenSlots.includes(opt.value)) {
        opt.disabled = true;
      }
    });
  }
});
</script>

<link rel="stylesheet" href="customer.css">
<style>
.form-wrapper {
  max-width: 450px;
  margin: 50px auto;
  padding: 30px;
  border: 1px solid #ddd;
  border-radius: 8px;
  background: #f9f9f9;
  box-shadow: 0 0 10px #eee;
}
.form-wrapper h2 {
  text-align: center;
  margin-bottom: 20px;
}
.spa-form label {
  display: block;
  margin-top: 15px;
  font-weight: bold;
}
.spa-form input,
.spa-form select {
  width: 100%;
  padding: 8px;
  margin-top: 5px;
  border: 1px solid #aaa;
  border-radius: 4px;
}
.spa-form button {
  width: 100%;
  padding: 10px;
  margin-top: 20px;
  background: #333;
  color: #fff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
.spa-form button:hover {
  background: #555;
}
</style>
