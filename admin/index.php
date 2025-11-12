<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['login'])) {
    $adminuser = $_POST['username'];
    $password = md5($_POST['password']);
    $query = mysqli_query($con,"SELECT ID FROM tbladmin WHERE UserName='$adminuser' && Password='$password'");
    $ret = mysqli_fetch_array($query);
    if($ret > 0) {
        $_SESSION['pmsaid'] = $ret['ID'];
        header('location:dashboard.php');
    } else {
        $msg = "Invalid Details.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DHARANI PMS - Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary: #4361ee;
            --success: #10b981;
            --danger: #ef4444;
            --dark: #0f172a;
            --light: #f8fafc;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Animated Background */
        .bg-anim {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="g"><stop offset="0%" stop-color="%234361ee"/><stop offset="100%" stop-color="%238b5cf6"/></radialGradient></defs><circle cx="200" cy="200" r="300" fill="url(%23g)" opacity="0.1"/><circle cx="800" cy="300" r="400" fill="url(%23g)" opacity="0.08"/><circle cx="600" cy="700" r="350" fill="url(%23g)" opacity="0.12"/></svg>');
            animation: float 20s infinite ease-in-out;
            z-index: 1;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(5deg); }
        }

        /* Login Fortress */
        .login-fortress {
            position: relative;
            z-index: 10;
            width: 420px;
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(25px);
            border-radius: 38px;
            padding: 50px 45px;
            box-shadow: 
                0 35px 80px rgba(0,0,0,0.6),
                inset 0 0 60px rgba(67, 97, 238, 0.15);
            border: 2px solid rgba(67, 97, 238, 0.3);
            text-align: center;
            animation: slideUp 1s ease-out;
        }
        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Logo & Title */
        .logo-circle {
            width: 110px;
            height: 110px;
            background: linear-gradient(135deg, #4361ee, #8b5cf6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            box-shadow: 0 20px 50px rgba(67, 97, 238, 0.5);
            border: 5px solid rgba(255,255,255,0.2);
        }

        .title {
            font-size: 2.2rem;
            font-weight: 800;
            background: linear-gradient(87deg, #60a5fa, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }
        .subtitle {
            font-size: 0.98rem;
            opacity: 0.9;
            margin-bottom: 35px;
            font-weight: 500;
        }

        /* Input Fields */
        .input-group {
            position: relative;
            margin-bottom: 25px;
        }
        .input-group input {
            width: 100%;
            padding: 18px 55px;
            border-radius: 28px;
            border: 2.5px solid rgba(255,255,255,0.2);
            background: rgba(255,255,255,0.1);
            color: white;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.4s ease;
            backdrop-filter: blur(10px);
        }
        .input-group input::placeholder {
            color: rgba(255,255,255,0.6);
        }
        .input-group input:focus {
            outline: none;
            border-color: #4361ee;
            box-shadow: 0 0 0 8px rgba(67, 97, 238, 0.3);
            background: rgba(255,255,255,0.15);
        }
        .input-group i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #60a5fa;
            font-size: 1.3rem;
        }

        /* Alert */
        .alert {
            padding: 16px 24px;
            border-radius: 20px;
            margin: 20px 0;
            font-size: 0.95rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }
        .alert-error {
            background: rgba(239, 68, 68, 0.25);
            color: #fca5a5;
            border: 2px solid rgba(239, 68, 68, 0.4);
        }

        /* Login Button */
        .btn-login {
            width: 100%;
            padding: 20px;
            background: linear-gradient(87deg, #4361ee, #8b5cf6);
            border: none;
            border-radius: 60px;
            color: white;
            font-size: 1.15rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            box-shadow: 0 20px 50px rgba(67, 97, 238, 0.5);
            transition: all 0.5s ease;
        }
        .btn-login:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 70px rgba(67, 97, 238, 0.7);
        }

        /* Links */
        .links {
            margin-top: 25px;
            font-size: 0.92rem;
        }
        .links a {
            color: #60a5fa;
            text-decoration: none;
            font-weight: 600;
        }
        .links a:hover {
            color: #c084fc;
        }

        /* Brand Footer */
        .brand {
            margin-top: 50px;
            padding: 30px;
            background: rgba(15, 23, 42, 0.9);
            border-radius: 30px;
            border: 1px solid rgba(67, 97, 238, 0.3);
        }
        .brand h3 {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(87deg, #60a5fa, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
        }
        .brand p {
            font-size: 0.95rem;
            opacity: 0.9;
            margin: 10px 0 0;
        }

        @media (max-width: 480px) {
            .login-fortress { width: 92%; padding: 40px 30px; }
            .title { font-size: 1.9rem; }
        }
    </style>
</head>
<body>
    <div class="bg-anim"></div>

    <div class="login-fortress">
        <div class="logo-circle">
            <i class="fas fa-shield-alt fa-3x"></i>
        </div>

        <h1 class="title">DHARANI PMS</h1>
        <p class="subtitle">Admin Portal • Gampaha, Sri Lanka</p>

        <?php if($msg): ?>
            <div class="alert alert-error"><?= $msg; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit" name="login" class="btn-login">
                Secure Login
            </button>
        </form>

        <div class="links">
            <!-- <a href="forgot-password.php">Forgot Password?</a> -->
            <span style="margin:0 15px; opacity:0.5;">•</span>
            <a href="../index.php">Back to Home</a>
        </div>

        <div class="brand">
            <h3>DHARANI PHARMACY</h3>
            <p>Protected by Military-Grade Security • Built in 2025</p>
        </div>
    </div>

    <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>