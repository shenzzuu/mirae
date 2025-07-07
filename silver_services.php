<?php
session_start();
include('db.php');

// Only allow Silver members
if (!isset($_SESSION['membership_plan']) || strtolower($_SESSION['membership_plan']) !== 'silver') {
    header("Location: profile.php");
    exit();
}

// Get all services
$query = "SELECT * FROM services ORDER BY serviceID ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Silver Member Services</title>
  <link rel="stylesheet" href="customer.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f2f2f2;
    }
    h2 {
      margin: 40px 0 20px;
      font-size: 28px;
      text-align: center;
    }
    .services-container {
      display: flex;
      flex-wrap: wrap;
      gap: 30px;
      justify-content: center;
      padding-bottom: 40px;
    }
    .card {
      background: white;
      width: 260px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      overflow: hidden;
      position: relative;
      text-align: center;
      padding: 20px;
    }
    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
      border-radius: 8px;
    }
    .card h3 {
      margin-top: 15px;
      font-size: 18px;
    }
    .card p {
      font-size: 14px;
      color: #555;
      min-height: 60px;
    }
    .card button {
      margin-top: 15px;
      padding: 10px 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      width: 100%;
      font-size: 14px;
    }
    .book-btn {
      background-color: #4CAF50;
      color: white;
    }
    .disabled-btn {
      background-color: #ccc;
      color: #666;
      cursor: not-allowed;
    }
    .blur {
      filter: blur(2px);
      opacity: 0.6;
    }
    .unavailable-label {
      position: absolute;
      top: 10px;
      left: 10px;
      background: red;
      color: white;
      font-size: 12px;
      padding: 3px 6px;
      border-radius: 4px;
    }
  </style>
</head>
<body>

<?php include('custHeaderSilver.php'); ?>

<h2><span style="color: #8C7853;">▪</span> Services for Silver Membership</h2>

<div class="services-container">
  <?php while ($row = mysqli_fetch_assoc($result)):
    $name = strtolower($row['serviceName']);
    $image = $row['serviceImage'] ? $row['serviceImage'] : 'default.jpg';

    // For Silver
    $isAvailable = ($name === 'facial' || $name === 'chemical peel');
    $description = '';

    if ($name === 'facial') {
      $description = "1 Standard Facial every 3 months. Deep cleansing + mild exfoliation + hydration mask.";
    } elseif ($name === 'chemical peel') {
      $description = "1 Light Chemical Peel every 3 months. Brighten skin tone, mild spot reduction.";
    } elseif ($name === 'laser treatment') {
      $description = "Not included (upgrade to Gold for premium laser).";
    } else {
      $description = "Not available in Silver plan.";
    }
  ?>
    <div class="card">
      <?php if (!$isAvailable): ?>
        <div class="unavailable-label">Unavailable</div>
      <?php endif; ?>

      <div class="<?php echo !$isAvailable ? 'blur' : ''; ?>">
        <img src="uploads/<?php echo $image; ?>" alt="<?php echo $row['serviceName']; ?>">
        <h3><?php echo $row['serviceName']; ?></h3>
        <p><?php echo $description; ?></p>
      </div>

      <button 
        class="<?php echo $isAvailable ? 'book-btn' : 'disabled-btn'; ?>"
        <?php echo $isAvailable ? 'onclick="location.href=\'silverBook.php?serviceID=' . $row['serviceID'] . '\'"' : 'disabled'; ?>>
        <?php echo $isAvailable ? 'Book Now' : 'Unavailable for Silver'; ?>
      </button>
    </div>
  <?php endwhile; ?>
</div>

</body>
</html>
