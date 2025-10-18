<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'eapp_db');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if(!isset($_SESSION['username'])){
    header("Location: login.php"); exit();

}if(isset($_POST['confirm_delivery']) && isset($_POST['order_id'])){
    $order_id = intval($_POST['order_id']);
    $admin_name = $_SESSION['username'];
    
    $update = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $status_text = "Delivered by " . $admin_name;
    $update->bind_param("si", $status_text, $order_id);
    $update->execute();
    
    // Refresh page to show updated status
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}


// Fetch stats
$totalMenu = $conn->query("SELECT COUNT(*) as total FROM services")->fetch_assoc()['total'];
$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$query = "SELECT * FROM services";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Digital Admin Dashboard - Barbra's Kitchen</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<style>
/* Global */
* {margin:0; padding:0; box-sizing:border-box; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;}
body, html {height:100%; background: rgba(0,0,0,0.8); color:#fff; overflow:hidden;}
a {text-decoration:none;}

/* Sidebar */
.sidebar {
    position: fixed;
    left:0; top:0; height:100%;
    width:220px; /* default width: readable */
    background: goldenrod;
    display:flex;
    flex-direction:column;
    transition: width 0.3s ease;
    overflow:hidden;
}
.sidebar.expanded { width:230px; } /* slightly bigger on hover */

.sidebar .logo { 
    font-size: 22px; 
    text-align:center; 
    padding:20px 0; 
    color: rgba(0,0,0); 
    font-weight:bold; 
    white-space: nowrap;
}
.sidebar ul { list-style:none; flex:1;margin-top: 100px; }
.sidebar ul li {
    display:flex; align-items:center;
    padding:15px;
    border-radius: 10px;
    border: 1px solid rgba(0,0,0);
    margin: 15px;
    transition: background 0.2s;
}
.sidebar ul li:hover { background: rgba(255,255,255,0.1); }
.sidebar ul li a {
    display:flex; align-items:center;
    color: rgba(0,0,0); /* icons + text color */
    font-weight:500;
    width:100%;
}
.sidebar ul li i { 
    font-size:18px; 
    width:30px; 
    text-align:center;  
}
.sidebar ul li span { 
    margin-left:15px; 
    display:inline; /* always visible now */
}

/* Main content */
.main {
    margin-left:200px; /* matches sidebar */
    padding:20px;
    transition: margin-left 0.3s ease;
}
.sidebar.expanded ~ .main { margin-left:230px; }


/* Logout button */
.sidebar .logout {
    margin:20px; 
    padding:10px; 
    border:none; 
    border-radius:6px; 
    background:red; 
    color:#fff; 
    cursor:pointer; 
    transition:0.3s; 
}
.sidebar .logout:hover { background:#c70000; }
/* Main content */
.main {
    margin-left:200px;
    padding:20px;
    transition: margin-left 0.3s ease;
}
.sidebar.expanded ~ .main { margin-left:270px; }

/* Top Navbar */
.navbar {
    display:flex; justify-content:flex-end; align-items:center; padding:10px 20px; background: rgba(0,0,0,0.2); border-radius:10px;
}
.navbar span { margin-right:20px; font-weight:500; }
.navbar i { cursor:pointer; font-size:18px; margin-left:15px; }

/* Dashboard Cards */
.cards {
    display:flex; flex-wrap:wrap; gap:20px; margin-top:20px;
}
.card {
    flex:1; min-width:200px; background:linear-gradient(145deg,rgba(0,0,0,0.7),rgba(0,0,0,0.7));
    color:goldenrod; padding:20px; border-radius:10px;border: 1px solid goldenrod;
    display:flex; flex-direction:column; justify-content:center; align-items:center;
    box-shadow:0 10px 20px rgba(0,0,0,0.3);
    transition: transform 0.3s, box-shadow 0.3s;
}
.card:hover { transform: translateY(-5px) scale(1.03); box-shadow:0 15px 30px rgba(0,0,0,0.5); }
.card h2 { font-size:32px; margin-bottom:5px; }
.card p { font-weight:500; }

/* Product Table */
.table-container {
    margin-top:30px;
    background: rgba(255,255,255,0.05); border-radius:15px; padding:20px; overflow-x:auto;
}
.product-table { width:100%; border-collapse:collapse; }
.product-table th, .product-table td { padding:12px; text-align:center; border-bottom:1px solid rgba(255,255,255,0.2); }
.product-table th { background:goldenrod; color:#000; font-weight:500; }
.product-table td { background:rgba(255,255,255,0.1); transition:0.3s; }
.product-table tr:hover td { background:rgba(255,215,0,0.3); transform: scale(1.02); }

.btn-action { padding:6px 12px; border:none; border-radius:6px; cursor:pointer; margin:0 3px; transition:0.3s; font-weight:500; font-size:12px;}
.update-btn { background:#4CAF50; color:#fff; }
.update-btn:hover { background:#45a049; transform: scale(1.1);}
.view-btn { background:#008CBA; color:#fff; }
.view-btn:hover { background:#007bb5; transform: scale(1.1);}
.delete-btn { background:#e60000; color:#fff; }
.delete-btn:hover { background:#c70000; transform: scale(1.1);}

/* Scrollbar */
.main::-webkit-scrollbar { width:8px; }
.main::-webkit-scrollbar-thumb { background: goldenrod; border-radius:4px; }
.main::-webkit-scrollbar-track { background: rgba(255,255,255,0.1); }

@media(max-width:768px){
    .cards { flex-direction:column; }
    .sidebar { width:60px; }
    .sidebar.expanded { width:200px; }
    .main { margin-left:60px; }
    .sidebar.expanded ~ .main { margin-left:200px; }
}
</style>
</head>
<body>

<div class="sidebar" id="sidebar">
    <div class="logo"><i class="fa-solid fa-utensils"></i> <span>Barbra'sKitchen</span></div>
    <ul>
    <li><a href="admin_home.php"><i class="fa-solid fa-house"></i> <span>Dashboard</span></a></li>
    <li><a href="register_user.php"><i class="fa-solid fa-user-plus"></i> <span>Register Users</span></a></li>
    <li><a href="users.php"><i class="fa-solid fa-id-card"></i> <span>Users</span></a></li>
    <li><a href="upload_product.php"><i class="fa-solid fa-upload"></i> <span>Upload Menu</span></a></li>
</ul>

    <form action="logout.php" method="POST">
        <button type="submit" class="logout"><i class="fa-solid fa-arrow-left"></i> Logout</button>
    </form>
</div>

<div class="main">
    <div class="navbar">
        <i class="fa-solid fa-user" style="border: 2px solid goldenrod;padding: 10px;border-radius: 50%;margin-right: 10px;"></i>
        <span> <?= htmlspecialchars($_SESSION['username']); ?></span>
    </div>

    <?php

    // Get today's date
$today = date('Y-m-d');

// Calculate total revenue for today
$totalTodayResult = $conn->query("SELECT SUM(total_price) as total_today FROM orders WHERE DATE(created_at) = '$today'");
$totalToday = $totalTodayResult->fetch_assoc()['total_today'] ?? 0;

?>
    <div class="cards">
        <div class="card">
            <h2><?= $totalMenu ?></h2>
            <p>Total Menu Items</p>
        </div>
        <div class="card">
            <h2><?= $totalUsers ?></h2>
            <p>Total Users</p>
        </div>
        <div class="card">
            <h2><?= 'Tsh. ' . number_format($totalToday, 0, '.', ',') . '/='; ?></h2>
        <p>Total Today</p>
        </div>
        <div class="card">
            <h2><?= date('d M Y'); ?></h2>
            <p>Today</p>
        </div>
    </div>

    <div class="table-container">
        <table class="product-table">
            <thead>
                <tr>
                    <th>Food Name</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if($result && $result->num_rows>0): ?>
                    <?php while($row=$result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']); ?></td>
                            <td><?= htmlspecialchars($row['brand']); ?></td>
                            <td>Tsh. <?= htmlspecialchars($row['price']); ?></td>
                            <td><?= htmlspecialchars($row['quantity']); ?></td>
                            <td>
                                <button class="btn-action update-btn"><i class="fa-solid fa-pen"></i></button>
                                <button class="btn-action view-btn"><i class="fa-solid fa-eye"></i></button>
                                <button class="btn-action delete-btn"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5">No menu items found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
// Fetch orders
$ordersQuery = "SELECT * FROM orders ORDER BY created_at DESC";
$ordersResult = $conn->query($ordersQuery);
?>
<div class="table-container">
    <h2>Orders</h2>
    <table class="product-table">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Phone</th>
                <th>Delivery Location</th>
                <th>Food Name</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Order Date</th>
                <th>Order Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if($ordersResult && $ordersResult->num_rows > 0): ?>
                <?php while($order = $ordersResult->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['customer_name']); ?></td>
                        <td><?= htmlspecialchars($order['customer_phone']); ?></td>
                        <td><?= htmlspecialchars($order['delivery_location']); ?></td>
                        <td><?= htmlspecialchars($order['food_name']); ?></td>
                        <td><?= htmlspecialchars($order['quantity']); ?></td>
                        <td>
                            <?= 'Tsh. ' . number_format($order['total_price'], 0, '.', ',') . '/='; ?>
                        </td>
                        <td>
                            <?= date('d M Y, h:i A', strtotime($order['created_at'])); ?>
                        </td>
                        <td>
                        <?php if($order['status'] == 'Pending'): ?>
                            <form method="POST" style="margin:0;">
                                <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                <button type="submit" name="confirm_delivery" class="btn-action update-btn">
                                    Confirm Delivery
                                </button>
                            </form>
                        <?php else: ?>
                            <span style="color:lightgreen; font-weight:bold;"><?= htmlspecialchars($order['status']); ?></span>
                        <?php endif; ?>
                    </td>

                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7">No orders found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</div>

<script>
const sidebar = document.getElementById('sidebar');
sidebar.addEventListener('mouseenter',()=>sidebar.classList.add('expanded'));
sidebar.addEventListener('mouseleave',()=>sidebar.classList.remove('expanded'));
</script>

</body>
</html>
