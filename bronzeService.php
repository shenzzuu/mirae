<?php
session_start();
include('db.php');


// Fetch services
$query = "SELECT * FROM services ORDER BY serviceID ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bronze Member Services</title>
     <link rel="stylesheet" href="customer.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin: 40px 0 20px;
            font-size: 28px;
        }

        .services-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            padding-bottom: 40px;
        }

        .card {
            background: white;
            width: 260px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
            padding: 20px;
            position: relative;
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: scale(1.02);
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
            width: 100%;
            padding: 10px 15px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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

<?php include('custHeader.php'); ?>
<h2><span style="color: #8C7853;">▪</span> Services for Bronze Membership</h2>

<div class="services-container">
    <?php while ($row = mysqli_fetch_assoc($result)):
        $serviceName = strtolower($row['serviceName']);
        $isFacial = strpos($serviceName, 'facial') !== false;

        $image = $row['serviceImage'] ?: 'default.jpg';
        $imagePath = file_exists("uploads/$image") ? "uploads/$image" : "assets/default.jpg"; // fallback image
    ?>
        <div class="card">
            <?php if (!$isFacial): ?>
                <div class="unavailable-label">Unavailable</div>
            <?php endif; ?>

            <div class="<?= !$isFacial ? 'blur' : '' ?>">
                <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($row['serviceName']) ?>">
                <h3><?= htmlspecialchars($row['serviceName']) ?></h3>
                <p><?= htmlspecialchars($row['serviceDesc']) ?></p>
            </div>

            <button
                class="<?= $isFacial ? 'book-btn' : 'disabled-btn' ?>"
                <?= $isFacial ? "onclick=\"location.href='bronzeBook.php?serviceID={$row['serviceID']}'\"" : 'disabled aria-disabled="true"' ?>>
                <?= $isFacial ? 'Book Now' : 'Unavailable for Bronze' ?>
            </button>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
