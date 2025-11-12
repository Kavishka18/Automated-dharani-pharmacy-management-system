<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['login'])) {
    $pharmauser = $_POST['username'];
    $password = md5($_POST['password']);
    $query = mysqli_query($con,"SELECT ID FROM tblpharmacist WHERE UserName='$pharmauser' && Password='$password'");
    $ret = mysqli_fetch_array($query);
    if($ret > 0) {
        $_SESSION['pmspid'] = $ret['ID'];
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
    <title>Pharmacist Login - DHARANI PMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary: #10b981;
            --accent: #34d399;
            --dark: #0f172a;
            --glass: rgba(15, 23, 42, 0.92);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #065f46 0%, #0f172a 100%);
            color: #ecfdf5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Animated Medical Background */
        .medical-bg {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: 
                radial-gradient(circle at 15% 70%, rgba(16, 185, 129, 0.2) 0%, transparent 50%),
                radial-gradient(circle at 85% 30%, rgba(52, 211, 153, 0.18) 0%, transparent 50%),
                radial-gradient(circle at 50% 90%, rgba(16, 185, 129, 0.15) 0%, transparent 70%);
            animation: breathe 18s infinite ease-in-out;
            z-index: 1;
        }
        @keyframes breathe {
            0%, 100% { opacity: 0.6; }
            50% { opacity: 1; }
        }

        /* Pharmacist Portal */
        .portal {
            position: relative;
            z-index: 10;
            width: 440px;
            background: var(--glass);
            backdrop-filter: blur(30px);
            border-radius: 45px;
            padding: 60px 50px;
            box-shadow: 
                0 45px 100px rgba(0,0,0,0.7),
                inset 0 0 80px rgba(16, 185, 129, 0.25);
            border: 3px solid rgba(16, 185, 129, 0.5);
            text-align: center;
            animation: portalRise 1.3s ease-out;
        }
        @keyframes portalRise {
            from { transform: translateY(70px) scale(0.93); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }

        /* Medical Cross Icon */
        .cross-icon {
            width: 130px;
            height: 130px;
            background: linear-gradient(135deg, #10b981, #34d399);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            box-shadow: 0 28px 70px rgba(16, 185, 129, 0.6);
            border: 7px solid rgba(255,255,255,0.3);
            animation: pulse-green 3s infinite;
        }
        @keyframes pulse-green {
            0%, 100% { box-shadow: 0 28px 70px rgba(16, 185, 129, 0.6); }
            50% { box-shadow: 0 28px 90px rgba(16, 185, 129, 0.85); }
        }

        /* Titles */
        .title {
            font-size: 2.6rem;
            font-weight: 800;
            background: linear-gradient(87deg, #6ee7b7, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 12px;
        }
        .role {
            font-size: 1.3rem;
            font-weight: 700;
            color: #86efac;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        .subtitle {
            font-size: 1.02rem;
            opacity: 0.92;
            margin-bottom: 40px;
            font-weight: 500;
            line-height: 1.6;
        }

        /* Input Fields */
        .input-group {
            position: relative;
            margin-bottom: 30px;
        }
        .input-group input {
            width: 100%;
            padding: 22px 62px;
            border-radius: 32px;
            border: 3.5px solid rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.15);
            color: white;
            font-size: 1.05rem;
            font-weight: 500;
            transition: all 0.5s ease;
        }
        .input-group input::placeholder {
            color: rgba(255,255,255,0.7);
        }
        .input-group input:focus {
            outline: none;
            border-color: #34d399;
            box-shadow: 0 0 0 12px rgba(52, 211, 153, 0.4);
            background: rgba(255,255,255,0.22);
        }
        .input-group i {
            position: absolute;
            left: 24px;
            top: 50%;
            transform: translateY(-50%);
            color: #6ee7b7;
            font-size: 1.4rem;
        }

        /* Alert */
        .alert {
            padding: 20px 28px;
            border-radius: 24px;
            margin: 25px 0;
            font-size: 0.98rem;
            font-weight: 600;
            backdrop-filter: blur(14px);
            border: 2.5px solid rgba(239, 68, 68, 0.6);
            background: rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        /* Login Button */
        .btn-pharma {
            width: 100%;
            padding: 24px;
            background: linear-gradient(87deg, #10b981, #34d399);
            border: none;
            border-radius: 80px;
            color: white;
            font-size: 1.25rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 3px;
            cursor: pointer;
            box-shadow: 0 25px 60px rgba(16, 185, 129, 0.65);
            transition: all 0.6s ease;
        }
        .btn-pharma:hover {
            transform: translateY(-12px);
            box-shadow: 0 40px 90px rgba(16, 185, 129, 0.85);
        }

        /* Links */
        .links {
            margin-top: 30px;
            font-size: 0.96rem;
        }
        .links a {
            color: #6ee7b7;
            text-decoration: none;
            font-weight: 600;
        }
        .links a:hover {
            color: #86efac;
        }

        /* Brand Seal */
        .seal {
            margin-top: 60px;
            padding: 38px;
            background: rgba(15, 23, 42, 0.95);
            border-radius: 38px;
            border: 3px solid rgba(16, 185, 129, 0.5);
        }
        .seal h3 {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(87deg, #6ee7b7, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
        }
        .seal p {
            font-size: 0.98rem;
            opacity: 0.92;
            margin: 14px 0 0;
        }

        @media (max-width: 480px) {
            .portal { width: 93%; padding: 50px 38px; }
            .title { font-size: 2.2rem; }
            .cross-icon { width: 110px; height: 110px; }
        }
    </style>
</head>
<body>
    <div class="medical-bg"></div>

    <div class="portal">
        <div class="cross-icon">
            <i class="fas fa-user-md fa-3x"></i>
        </div>

        <h1 class="title">PHARMACIST</h1>
        <div class="role">DHARANI PMS</div>
        <p class="subtitle">Secure access for authorized pharmacy staff only<br>Gampaha • Sri Lanka</p>

        <?php if($msg): ?>
            <div class="alert"><?= $msg; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Pharmacist Username" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Your Password" required>
            </div>

            <button type="submit" name="login" class="btn-pharma">
                Enter Pharmacy
            </button>
        </form>

        <div class="links">
            <!-- <a href="forgot-password.php">Forgot Password?</a> -->
            <span style="margin:0 18px; opacity:0.6;">•</span>
            <a href="../index.php">Back to Home</a>
        </div>

        <div class="seal">
            <h3>DHARANI PHARMACY</h3>
            <p>Pharmacist Portal • Military-Grade Security • 2025</p>
        </div>
    </div>

</body>
</html>