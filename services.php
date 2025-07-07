<?php
include('db.php'); // Include database connection

// Fetch services from the database
$query = "SELECT * FROM services";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Services</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background-color: #f9f9f9;
    }

    .header {
      background-color: #2c2c2c;
      color: white;
      padding: 20px;
      text-align: center;
    }

    .services-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      margin: 40px 0;
      gap: 30px;
    }

    .service-card {
      background-color: white;
      width: 280px;
      text-align: center;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      padding: 20px;
    }

    .service-card img {
      width: 100%;
      height: auto;
      border-radius: 10px;
    }

    .service-card h3 {
      margin-top: 15px;
      font-size: 24px;
    }

    .service-card p {
      font-size: 16px;
      color: #555;
      margin-top: 10px;
    }

    .btn {
      display: inline-block;
      padding: 10px 20px;
      margin-top: 20px;
      background-color: #007bff;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-size: 16px;
    }

    .btn:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

<div class="header">
  <h1>Services</h1>
</div>

<div class="services-container">
  <?php while($row = $result->fetch_assoc()): ?>
    <div class="service-card">
      <img src="<?= htmlspecialchars($row['serviceImage'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($row['serviceName']) ?>">
      <h3><?= htmlspecialchars($row['serviceName']) ?></h3>
      <p>Price: RM <?= number_format($row['servicePrice'], 2) ?></p>
      <a href="bookService.php?serviceID=<?= $row['serviceID'] ?>" class="btn">Book Now</a>
    </div>
  <?php endwhile; ?>
</div>

</body>
</html>
