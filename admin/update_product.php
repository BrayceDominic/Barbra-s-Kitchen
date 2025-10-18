<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'eapp_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Debugging: Check if ID is received
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    echo "Debug: ID received = " . htmlspecialchars($id) . "<br>";
} else {
    die("No ID received in the URL.");
}

// Check if ID is numeric
if (is_numeric($id)) {
    $id = $conn->real_escape_string($id);

    // Fetch product details
    $query = "SELECT * FROM services WHERE id='$id'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die("Product not found in the database.");
    }

    // Check if the form is submitted to update the product details
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the updated data from the form
        $name = $conn->real_escape_string($_POST['name']);
        $brand = $conn->real_escape_string($_POST['brand']);
        $price = $conn->real_escape_string($_POST['price']);
        $quantity = $conn->real_escape_string($_POST['quantity']);

        // Update the product details in the database
        $updateQuery = "UPDATE services SET name='$name', brand='$brand', price='$price', quantity='$quantity' WHERE id='$id'";

        if ($conn->query($updateQuery)) {
            echo "<script>alert('Menu updated succesfully');</script>";
            // Redirect after successful update to avoid resubmitting the form on refresh
            header("Location: admin_home.php?id=$id");
            exit();
        } else {
            echo "<script>alert('Failed to upload product');</script>";
        }
    }
} else {
    die("Invalid product ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Product</title>
    <style>
        body {
            justify-content: center;
            text-align: center;
            background-color: gray;
            color: white;
            background-image: url(../img/background2.jpg);
            background-size: cover;
        }

        h1 {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 45px;
            text-align: left;
        }

        input, textarea, button {
            width: 75%;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            border: none;
        }

        button {
            background-color: goldenrod;
            color: black;
            font-size: large;
            font-weight: bold;
        }

        form {
            border: 5px double goldenrod;
            padding: 30px;
            border-radius: 10px;
            width: 50%;
            margin: auto;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Update Menu</h1>
    <form method="post">
        <label for="name">Food Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>" required><br>
        <label for="brand">Brand:</label>
        <input type="text" name="brand" value="<?php echo htmlspecialchars($product['brand'], ENT_QUOTES); ?>" required><br>
        <label for="price">Price:</label>
        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price'], ENT_QUOTES); ?>" required><br>
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" value="<?php echo htmlspecialchars($product['quantity'], ENT_QUOTES); ?>" required><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
