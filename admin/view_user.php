<?php 
// Database connection
$conn = new mysqli('localhost', 'root', '', 'eapp_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data
$query = "SELECT * FROM users"; 
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: url(../img/background2.jpg);
            background-size: cover;
            background-position: center;
            font-family: Arial, Helvetica, sans-serif;
            color: white;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: goldenrod;
            font-size: 36px;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            text-align: center;
            background-color: rgba(255, 215, 0, 0.9); /* Goldenrod with slight transparency */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        th {
            background-color: goldenrod;
            color: black;
            font-size: 18px;
        }

        td {
            background-color: #f9f9f9;
            color: black;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .view-btn {
            background-color: #008CBA;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .view-btn:hover {
            background-color: #007bb5;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Registered Users</h1>
    <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Username</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['first_name']); ?></td>
                        <td><?= htmlspecialchars($row['last_name']); ?></td>
                        <td><?= htmlspecialchars($row['username']); ?></td>
                        <td><?= htmlspecialchars($row['phone']); ?></td>
                        <td>
                            <a href="view_user_details.php?id=<?= $row['id']; ?>" class="view-btn">View Details</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No users found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
