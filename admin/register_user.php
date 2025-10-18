<?php
$conn = new mysqli('localhost', 'root', '', 'eapp_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name  = $_POST['last_name'];
    $email      = $_POST['email'];
    $phone      = $_POST['phone'];
    $username   = $_POST['username'];
    $password   = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (first_name, last_name, email, phone, username, password) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $first_name, $last_name, $email, $phone, $username, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('User registered successfully');</script>";
    } else {
        echo "<script>alert('Failed to register user: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register Users - Online Kitchen</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<style>
    * { margin:0; padding:0; box-sizing:border-box;font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;  }
    body, html { height: 100%; background: url('../img/background.jpg') no-repeat center/cover; color: white; }
    
    /* Sidebar */
    .sidebar {
        position: fixed;
        top: 0; left: 0;
        width: 250px; height: 100%;
        background: goldenrod;
        display: flex; flex-direction: column;
        padding: 20px;
        transition: width 0.3s;
    }
    .sidebar .logo { 
    font-size: 22px; 
    text-align:center; 
    padding:20px 0; 
    color: rgba(0,0,0); 
    font-weight:bold; 
    white-space: nowrap;
}
    .sidebar:hover { width: 300px; }
    .sidebar h2 { margin-bottom: 30px; color: black; text-align: center; }
    .sidebar ul { list-style:none; flex:1;margin-top:110px; }
    .sidebar ul li { margin: 20px 0;display:flex; align-items:center;
    padding:15px;
    border-radius: 10px;
    border: 1px solid rgba(0,0,0);
    margin: 15px; }
    .sidebar ul li a {
        text-decoration:none; color:black; display:flex; align-items:center; gap:10px; transition: all 0.3s;
    }
    .sidebar ul li a:hover { color:white; transform: scale(1.05); }
    
    /* Content */
    .content { margin-left:250px; padding: 50px 40px; transition: margin-left 0.3s; }
    .sidebar:hover ~ .content { margin-left:300px; }
    
    /* Form */
    .register-form {
        background: rgba(0,0,0,0.7);
        padding: 60px;
        border-radius: 15px;
        max-width: 900px;
        margin: auto;
        box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        animation: fadeIn 1s ease forwards;
    }
    .register-form h1 { text-align:center; color: goldenrod; margin-bottom: 30px; }
    .register-form input {
        width:100%; padding: 12px 15px; margin: 10px 0; border-radius: 8px; border:none;
    }
    .register-form button {
        width:100%; padding: 12px; background: goldenrod; color:black; border:none; border-radius:10px;
        font-weight:bold; cursor:pointer; transition: all 0.3s;
    }
    .register-form button:hover { background: orange; transform: scale(1.05); }

    @keyframes fadeIn { from { opacity:0; transform: translateY(20px);} to {opacity:1; transform: translateY(0);} }
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
    <form class="register-form" method="POST" action="">
        <h1>Register New User</h1>
        <input type="text" name="first_name" placeholder="First Name" required>
        <input type="text" name="last_name" placeholder="Last Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit"><i class="fa-solid fa-user-plus"></i> Register User</button>
    </form>
</div>

</body>
</html>
