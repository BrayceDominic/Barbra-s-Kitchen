<?php
// insert_order.php
$conn = new mysqli('localhost', 'root', '', 'eapp_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from AJAX POST
$cust_name = $_POST['cust_name'] ?? '';
$cust_phone = $_POST['cust_phone'] ?? '';
$cust_location = $_POST['cust_location'] ?? '';
$food_name = $_POST['food_name'] ?? '';
$quantity = (int) ($_POST['quantity'] ?? 0);
$total_price = (float) ($_POST['total_price'] ?? 0);

if ($cust_name && $cust_phone && $cust_location && $food_name && $quantity > 0) {
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_phone, delivery_location, food_name, quantity, total_price, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssssid", $cust_name, $cust_phone, $cust_location, $food_name, $quantity, $total_price);
    if($stmt->execute()) {
        echo json_encode(['status'=>'success']);
    } else {
        echo json_encode(['status'=>'error', 'msg'=>$stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['status'=>'error', 'msg'=>'Missing required fields']);
}

$conn->close();
