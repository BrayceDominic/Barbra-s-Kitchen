<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'eapp_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    // Fetch user details by ID
    $query = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        die("User not found.");
    }
} else {
    die("Invalid or missing user ID.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url(../img/background2.jpg);
            background-size: cover;
            background-position: center;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            color: white;
        }

        .container {
            width: 50%;
            margin: 50px auto;
            background-color: goldenred; /* Goldenrod background */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 20px;
            text-align: center;
        }

        h1 {
            font-size: 32px;
            color: goldenrod;
            margin-bottom: 40px;
        }

        p {
            font-size: 18px;
            color: white;
            margin: 30px;
        }

        img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-top: 20px;
            border: 2px solid goldenrod;
        }

        .back-btn {
            background-color: #008CBA;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s;
            margin-top: 20px;
            display: inline-block;
        }

        .back-btn:hover {
            background-color: #007bb5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Details</h1>
        <p><strong>First Name:</strong> <?= htmlspecialchars($user['first_name']); ?></p>
        <p><strong>Last Name:</strong> <?= htmlspecialchars($user['last_name']); ?></p>
        <p><strong>Username:</strong> <?= htmlspecialchars($user['username']); ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']); ?></p>
        
        <?php if (!empty($user['images'])): ?>
            <img src="<?= htmlspecialchars($user['images']); ?>" alt="User Image">
        <?php else: ?>
            <p>No image available.</p>
        <?php endif; ?>

        <a href="users.php" class="back-btn">Back to User List</a>
    </div>
</body>
</html>
