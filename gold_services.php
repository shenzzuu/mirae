<?php
session_start();
include('db.php');

// Ensure only Gold members can access
if (!isset($_SESSION['membership_plan']) || strtolower($_SESSION['membership_plan']) !== 'gold') {
    header("Location:profile.php");
    exit();
}

// Get all services
$query = "SELECT * FROM services ORDER BY serviceID ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gold Member Services</title>
    <link rel="stylesheet" href="customer.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 0;
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
            background: #fff;
            width: 260px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
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
            min-height: 80px;
        }

        .card button {
            margin-top: 15px;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 14px;
            background-color: #4CAF50;
            color: white;
        }

        .card button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<?php include('custHeaderGold.php'); ?>

<h2><span style="color: #DAA520;">▪</span> Premium Services for Gold Members</h2>

<div class="services-container">
    <?php while ($row = mysqli_fetch_assoc($result)): 
        $image = $row['serviceImage'] ? $row['serviceImage'] : 'default.jpg';
    ?>
        <div class="card">
            <img src="uploads/<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($row['serviceName']); ?>">
            <h3><?php echo htmlspecialchars($row['serviceName']); ?></h3>
            <p><?php echo htmlspecialchars($row['serviceDesc']); ?></p>
            <button onclick="location.href='goldBook.php?serviceID=<?php echo $row['serviceID']; ?>'">
                Book Now
            </button>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
