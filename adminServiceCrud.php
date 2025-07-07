<?php
include('db.php');
include('adminHeader.php');

// Initialize variables
$serviceID = '';
$serviceName = '';
$serviceImage = '';
$serviceDescription = '';

// Load service for editing
if (isset($_GET['id'])) {
    $serviceID = $_GET['id'];
    $query = "SELECT * FROM services WHERE serviceID = $serviceID";
    $result = mysqli_query($conn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        $serviceName = $row['serviceName'];
        $serviceImage = $row['serviceImage'];
        $serviceDescription = $row['serviceDesc'];
    }
}

// Handle form submission
if (isset($_POST['submit'])) {
    $serviceName = $_POST['serviceName'];
    $serviceDescription = $_POST['serviceDescription'];
    $image = $serviceImage;

    // Handle image upload
    if (!empty($_FILES['serviceImage']['name'])) {
        $image = $_FILES['serviceImage']['name'];
        $target = "uploads/" . basename($image);
        move_uploaded_file($_FILES['serviceImage']['tmp_name'], $target);
    }

    // Update or insert
    if (!empty($_POST['serviceID'])) {
        $update = "UPDATE services SET 
            serviceName = '$serviceName', 
            
            serviceDesc = '$serviceDescription', 
            serviceImage = '$image' 
            WHERE serviceID = " . $_POST['serviceID'];
        mysqli_query($conn, $update);
    } else {
        $insert = "INSERT INTO services (serviceName,  serviceDesc, serviceImage) 
                   VALUES ('$serviceName', '$serviceDescription', '$image')";
        mysqli_query($conn, $insert);
    }

    header("Location: adminService.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $serviceID ? 'Edit' : 'Add'; ?> Service</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .main-content {
            padding: 40px;
        }

        .form-container {
            background: #fff;
            padding: 30px;
            max-width: 700px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #444;
        }

        input[type='text'],
        input[type='number'],
        input[type='file'],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button[type='submit'] {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            color: white;
            cursor: pointer;
        }

        button[type='submit']:hover {
            background-color: #218838;
        }

        img {
            margin-top: 10px;
            max-height: 100px;
            border-radius: 6px;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }

            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Main Content -->
    <div class="main-content">
        <div class="form-container">
            <h2><?php echo $serviceID ? 'Edit' : 'Add'; ?> Service</h2>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="serviceID" value="<?php echo htmlspecialchars($serviceID); ?>">

                <label for="serviceName">Service Name:</label>
                <input type="text" id="serviceName" name="serviceName" required value="<?php echo htmlspecialchars($serviceName); ?>">

                <label for="serviceDescription">Description:</label>
                <textarea id="serviceDescription" name="serviceDescription" rows="4" required><?php echo htmlspecialchars($serviceDescription); ?></textarea>

                <label for="serviceImage">Image:</label>
                <input type="file" id="serviceImage" name="serviceImage">
                <?php if ($serviceImage): ?>
                    <img src="uploads/<?php echo htmlspecialchars($serviceImage); ?>" alt="Service Image">
                <?php endif; ?>

                <button type="submit" name="submit">Save</button>
            </form>
        </div>
    </div>

</body>
</html>
