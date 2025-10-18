<?php 
// Database connection
$conn = new mysqli('localhost', 'root', '', 'eapp_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch users
$query = "SELECT * FROM users"; 
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registered Users - Barbra's Kitchen</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<style>
    * { margin:0; padding:0; box-sizing:border-box;font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;}
    body, html { min-height:100%; background: url('../img/background2.jpg') no-repeat center/cover; color:white; }

    /* Sidebar */
    .sidebar {
        position: fixed;
        top: 0; left: 0;
        width: 250px; height: 100%;
        background: goldenrod;
        display: flex; flex-direction: column;
        padding: 20px;
        transition: width 0.3s;
        z-index: 10;
    }
    .sidebar:hover { width: 300px; }
    .sidebar h2 { margin-bottom: 30px; color: black; text-align: center; }
    .sidebar ul { list-style:none; flex:1;margin-top:110px; }
    .sidebar .logo { 
    font-size: 22px; 
    text-align:center; 
    padding:20px 0; 
    color: rgba(0,0,0); 
    font-weight:bold; 
    white-space: nowrap;
}
    .sidebar ul li { margin: 20px 0;margin: 20px 0;display:flex; align-items:center;
    padding:15px;
    border-radius: 10px;
    border: 1px solid rgba(0,0,0);
    margin: 15px; }
    .sidebar ul li a {
        text-decoration:none; color:black; display:flex; align-items:center; gap:10px; transition: all 0.3s;
    }
    .sidebar ul li a:hover { color:white; transform: scale(1.05); }

    /* Content */
    .content { margin-left:250px; padding: 40px 20px; transition: margin-left 0.3s; }
    .sidebar:hover ~ .content { margin-left:300px; }

    h1 { text-align:center; margin:20px 0 40px 0; color: goldenrod; font-size: 36px; text-shadow: 1px 1px 5px rgba(0,0,0,0.7); }

    .users-container {
        display: flex;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 25px;
        max-width: 1200px;
        margin: 0 auto 50px auto;
        padding: 40px;
    }

    .profile-card {
        background: goldenrod;
        border-radius: 50px;
        width: 500px;
        text-align: center;
        padding: 25px;
        color: black;
        box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        transition: transform 0.4s, box-shadow 0.4s;
        position: relative;
        overflow: hidden;
    }

    .profile-card:hover {
        transform: translateY(-10px) scale(1.05);
        box-shadow: 0 15px 30px rgba(0,0,0,0.6);
        background: linear-gradient(135deg, white, goldenrod);
        color: black;
    }

    .avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: #333;
        margin: 0 auto 15px auto;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        color: white;
        text-transform: uppercase;
    }

    .profile-card h3 {
        margin-bottom: 8px;
        font-size: 20px;
    }

    .profile-card p {
        font-size: 14px;
        margin-bottom: 8px;
    }

    .view-btn {
        display: inline-block;
        padding: 8px 20px;
        margin-top: 10px;
        border-radius: 10px;
        background: black;
        color: goldenrod;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s ease;
    }

    .view-btn:hover {
        background: goldenrod;
        color: black;
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.5);
    }

    @media(max-width:768px){
        .users-container { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); }
        .sidebar { width: 200px; }
        .sidebar:hover { width: 240px; }
        .content { margin-left: 200px; }
        .sidebar:hover ~ .content { margin-left: 240px; }
    }
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="logo"><i class="fa-solid fa-utensils"></i> <span>Barbra'sKitchen</span></div>
    <ul>
        <li><a href="admin_home.php"><i class="fa-solid fa-house"></i> <span>Dashboard</span></a></li>
        <li><a href="register_user.php"><i class="fa-solid fa-user-plus"></i> <span>Register Users</span></a></li>
        <li><a href="users.php"><i class="fa-solid fa-id-card"></i> <span>Users</span></a></li>
        <li><a href="upload_product.php"><i class="fa-solid fa-upload"></i> <span>Upload Menu</span></a></li>
    </ul>
</div>

<!-- Content -->
<div class="content">
    <h1>Registered Users</h1>
    <div class="users-container">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="profile-card">
                    <div class="avatar"><?= strtoupper(substr($row['first_name'],0,1).substr($row['last_name'],0,1)); ?></div>
                    <h3><?= htmlspecialchars($row['first_name'].' '.$row['last_name']); ?></h3>
                    <p><i class="fa-solid fa-user"></i> <?= htmlspecialchars($row['username']); ?></p>
                    <p><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($row['phone']); ?></p>
                    <a href="view_user_details.php?id=<?= $row['id']; ?>" class="view-btn"><i class="fa-solid fa-eye"></i> View Profile</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center; font-size:20px; color:goldenrod;">No users found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
