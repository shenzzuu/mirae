<?php
include('db.php');
include('adminHeader.php');


// Get only pending appointments
$sql = "SELECT 
            a.appointmentID, 
            a.appointmentDate, 
            a.timeSlot, 
            a.status, 
            c.full_name AS customer_name,
            s.serviceName 
        FROM appointments a
        JOIN customer c ON a.userID = c.id
        JOIN services s ON a.serviceID = s.serviceID
        WHERE a.status = 'pending'
        ORDER BY a.appointmentDate DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pending Appointments</title>
    <link rel="stylesheet" href="adminDash.css">
    <style>
        .main-content {
            padding: 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .status {
            font-weight: bold;
            color: #ff8800;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }

            table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<div class="main-content">
    <h2> Appointments List</h2>

    <table>
        <thead>
            <tr>
                <th>Appointment ID</th>
                <th>Customer Name</th>
                <th>Service</th>
                <th>Date</th>
                <th>Time Slot</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['appointmentID']) ?></td>
                        <td><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td><?= htmlspecialchars($row['serviceName']) ?></td>
                        <td><?= htmlspecialchars($row['appointmentDate']) ?></td>
                        <td><?= htmlspecialchars($row['timeSlot']) ?></td>
                        <td class="status"><?= htmlspecialchars($row['status']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center;">No pending appointments found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script>
function toggleSidebar() {
  const sidebar = document.querySelector('.sidebar');
  sidebar.classList.toggle('collapsed');
}
</script>
<button class="toggle-btn" onclick="toggleSidebar()">☰</button>
</body>
</html>

<?php mysqli_close($conn); ?>
