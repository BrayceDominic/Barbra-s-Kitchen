<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password']) || $password === $user['password']) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    header("Location: admin_home.php");
                    exit();
                } else {
                    $error = "Invalid password. Please try again.";
                }
            } else {
                $error = "User not found! Please check your username.";
            }
        } else {
            $error = "Something went wrong. Please try again later.";
        }

        $stmt->close();
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin - Barbra's Kitchen</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<style>
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Gill Sans', sans-serif; }
    body, html {
        height: 100%;
        background: url('../img/background.jpg') no-repeat center center/cover;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .login-card {
        background: rgba(0,0,0,0.55);
        border-radius: 25px;
        padding: 50px 40px;
        width: 700px;
        backdrop-filter: blur(12px);
        box-shadow: 0 0 30px rgba(0,0,0,0.5);
        position: relative;
        overflow: hidden;
        animation: fadeIn 1s ease forwards;
    }

    .login-card h1 {
        text-align: center;
        margin-bottom: 30px;
        color: goldenrod;
        font-size: 36px;
        text-shadow: 1px 1px 5px rgba(0,0,0,0.7);
    }

    .input-box {
        position: relative;
        margin: 25px 0;
    }

    .input-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: goldenrod;
        font-size: 22px;
        transition: 0.3s;
    }

    .input-box input {
        width: 100%;
        padding: 15px 15px 15px 50px;
        border-radius: 7px;
        border: 2px solid goldenrod;
        outline: none;
        background: rgba(255,255,255,0.1);
        color: white;
        font-size: 16px;
        transition: 0.3s;
    }

    .input-box input::placeholder { color: rgba(255,255,255,0.7); }

    .input-box input:focus { background: rgba(255,255,255,0.15); border-color: #fff; }
    .input-box input:focus + i { color: black; transform: scale(1.1); }

    .remember-forgot {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        margin-top: -10px;
        margin-bottom: 20px;
    }

    .remember-forgot label input { accent-color: goldenrod; margin-right: 5px; }
    .remember-forgot a { color: goldenrod; text-decoration: none; transition: 0.3s; }
    .remember-forgot a:hover { text-decoration: underline; color: #fff; }

    .btn {
        width: 50%;
        padding: 12px;
        border: none;
        border-radius: 10px;
        background: goldenrod;
        color: black;
        font-weight: bold;
        font-size: 16px;
        cursor: pointer;
        box-shadow: 0 8px 15px rgba(0,0,0,0.3);
        transition: all 0.3s ease;
    }

    .btn:hover {
        background: black;
        color: goldenrod;
        transform: translateY(-3px);
        box-shadow: 0 12px 20px rgba(0,0,0,0.5);
    }

    .register-link {
        text-align: center;
        margin-top: 20px;
        font-size: 14px;
    }

    .register-link a {
        color: goldenrod;
        font-weight: bold;
        text-decoration: none;
        transition: 0.3s;
    }

    .register-link a:hover { color: #fff; text-decoration: underline; }

    /* Error message */
    .error-msg {
        background: rgba(255,0,0,0.6);
        color: white;
        padding: 10px;
        border-radius: 10px;
        text-align: center;
        margin-bottom: 20px;
        font-weight: bold;
    }

    /* Animations */
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-20px);} to { opacity:1; transform: translateY(0); } }

</style>
</head>
<body>

<div class="login-card">
    <?php if(isset($error)): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <h1>Barbra's Kitchen</h1>

        <div class="input-box">
            <input type="text" name="username" placeholder="Username" required autofocus>
            <i class="fa-solid fa-user"></i>
        </div>

        <div class="input-box">
            <input type="password" name="password" placeholder="Password" required>
            <i class="fa-solid fa-lock"></i>
        </div>

        <div class="remember-forgot">
            <label style="color:goldenrod;"><input type="checkbox"> Remember me</label>
            <a href="#">Forgot Password?</a>
        </div>

        <button type="submit" class="btn"><i class="fa-solid fa-right-to-bracket"></i> Login</button>

        <div class="register-link">
            <p><a href="../customer/home.php">Back to Home</a></p>
        </div>
    </form>
</div>

</body>
</html>
