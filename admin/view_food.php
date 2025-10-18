<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'eapp_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if an ID is provided for viewing details
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM services WHERE id = $id";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $food = $result->fetch_assoc();
    } else {
        die("Food item not found.");
    }
} else {
    die("Invalid or missing food ID.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Details</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url(../img/background2.jpg);
            background-size: cover;
            background-position: center;
            font-family: Arial, Helvetica, sans-serif;
            color: white;
            text-align: center;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        h1 {
            color: goldenrod;
            font-size: 32px;
        }

        img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            margin: 10px 0;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: goldenrod;
            color: black;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn-back:hover {
            background-color: darkgoldenrod;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($food['name']); ?></h1>
        <?php if (!empty($food['image'])): ?>
            <img src="../<?= htmlspecialchars($food['image']); ?>" alt="<?= htmlspecialchars($food['name']); ?>">
        <?php else: ?>
            <img src="../img/head-cook-throwing-fresh-chopped-herbs-pan-improve-taste-meal-while-professional-kitchen-master-chef-seasoning-dish-prepared-food-contest-held-fine-dining-restaurant.jpg" alt="No Image Available">
        <?php endif; ?>
        <p><strong>Brand:</strong> <?= htmlspecialchars($food['brand']); ?></p>
        <p><strong>Price:</strong> Tsh. <?= htmlspecialchars($food['price']); ?></p>
        <p><strong>Quantity:</strong> <?= htmlspecialchars($food['quantity']); ?></p>
        <a href="menu.php" class="btn-back">Back to Menu</a>
    </div>
</body>
</html>
