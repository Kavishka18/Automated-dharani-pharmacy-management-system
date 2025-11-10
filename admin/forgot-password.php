<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['submit'])) {
    $contactno = $_POST['contactno'];
    $email = $_POST['email'];
    $query = mysqli_query($con,"SELECT ID FROM tbladmin WHERE Email='$email' AND MobileNumber='$contactno'");
    $ret = mysqli_fetch_array($query);
    if($ret > 0) {
        $_SESSION['contactno'] = $contactno;
        $_SESSION['email'] = $email;
        header('location:reset-password.php');
    } else {
        $msg = "Invalid Details. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recover Access - DHARANI PMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary: #8b5cf6;
            --accent: #ec4899;
            --dark: #0f172a;
            --glass: rgba(15, 23, 42, 0.9);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 100%);
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Animated Recovery Background */
        .recovery-bg {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(139, 92, 246, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(236, 72, 153, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(139, 92, 246, 0.1) 0%, transparent 70%);
            animation: pulse-bg 15s infinite ease-in-out;
            z-index: 1;
        }
        @keyframes pulse-bg {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }

        /* Recovery Vault */
        .vault {
            position: relative;
            z-index: 10;
            width: 430px;
            background: var(--glass);
            backdrop-filter: blur(28px);
            border-radius: 42px;
            padding: 55px 48px;
            box-shadow: 
                0 40px 90px rgba(0,0,0,0.7),
                inset 0 0 70px rgba(139, 92, 246, 0.2);
            border: 2.5px solid rgba(139, 92, 246, 0.4);
            text-align: center;
            animation: vaultRise 1.2s ease-out;
        }
        @keyframes vaultRise {
            from { transform: translateY(60px) scale(0.95); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }

        /* Recovery Key Icon */
        .key-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
            box-shadow: 0 25px 60px rgba(139, 92, 246, 0.6);
            border: 6px solid rgba(255,255,255,0.25);
            animation: keyFloat 4s infinite ease-in-out;
        }
        @keyframes keyFloat {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-12px) rotate(8deg); }
        }

        /* Titles */
        .title {
            font-size: 2.4rem;
            font-weight: 800;
            background: linear-gradient(87deg, #c084fc, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 1rem;
            opacity: 0.92;
            margin-bottom: 38px;
            font-weight: 500;
            line-height: 1.5;
        }

        /* Input Fields */
        .input-group {
            position: relative;
            margin-bottom: 28px;
        }
        .input-group input {
            width: 100%;
            padding: 20px 58px;
            border-radius: 30px;
            border: 3px solid rgba(255,255,255,0.25);
            background: rgba(255,255,255,0.12);
            color: white;
            font-size: 1.02rem;
            font-weight: 500;
            transition: all 0.45s ease;
        }
        .input-group input::placeholder {
            color: rgba(255,255,255,0.65);
        }
        .input-group input:focus {
            outline: none;
            border-color: #ec4899;
            box-shadow: 0 0 0 10px rgba(236, 72, 153, 0.35);
            background: rgba(255,255,255,0.18);
        }
        .input-group i {
            position: absolute;
            left: 22px;
            top: 50%;
            transform: translateY(-50%);
            color: #c084fc;
            font-size: 1.35rem;
        }

        /* Alert */
        .alert {
            padding: 18px 26px;
            border-radius: 22px;
            margin: 22px 0;
            font-size: 0.96rem;
            font-weight: 600;
            backdrop-filter: blur(12px);
            border: 2px solid rgba(239, 68, 68, 0.5);
            background: rgba(239, 68, 68, 0.25);
            color: #fca5a5;
        }

        /* Recover Button */
        .btn-recover {
            width: 100%;
            padding: 22px;
            background: linear-gradient(87deg, #8b5cf6, #ec4899);
            border: none;
            border-radius: 70px;
            color: white;
            font-size: 1.2rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            cursor: pointer;
            box-shadow: 0 22px 55px rgba(236, 72, 153, 0.55);
            transition: all 0.5s ease;
        }
        .btn-recover:hover {
            transform: translateY(-10px);
            box-shadow: 0 35px 80px rgba(236, 72, 153, 0.75);
        }

        /* Links */
        .links {
            margin-top: 28px;
            font-size: 0.94rem;
        }
        .links a {
            color: #c084fc;
            text-decoration: none;
            font-weight: 600;
        }
        .links a:hover {
            color: #f472b6;
        }

        /* Brand Seal */
        .seal {
            margin-top: 55px;
            padding: 35px;
            background: rgba(15, 23, 42, 0.95);
            border-radius: 35px;
            border: 2px solid rgba(139, 92, 246, 0.4);
        }
        .seal h3 {
            font-size: 1.9rem;
            font-weight: 800;
            background: linear-gradient(87deg, #c084fc, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
        }
        .seal p {
            font-size: 0.96rem;
            opacity: 0.9;
            margin: 12px 0 0;
        }

        @media (max-width: 480px) {
            .vault { width: 92%; padding: 45px 35px; }
            .title { font-size: 2rem; }
            .key-icon { width: 100px; height: 100px; }
        }
    </style>
</head>
<body>
    <div class="recovery-bg"></div>

    <div class="vault">
        <div class="key-icon">
            <i class="fas fa-key fa-3x"></i>
        </div>

        <h1 class="title">Recover Access</h1>
        <p class="subtitle">Enter your registered email and mobile number to reset your password securely</p>

        <?php if($msg): ?>
            <div class="alert"><?= $msg; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Registered Email" required>
            </div>

            <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="text" name="contactno" placeholder="Mobile Number" required>
            </div>

            <button type="submit" name="submit" class="btn-recover">
                Recover Account
            </button>
        </form>

        <div class="links">
            <a href="index.php">Back to Login</a>
        </div>

        <div class="seal">
            <h3>DHARANI PHARMACY</h3>
            <p>Secure Recovery System • Gampaha, Sri Lanka • 2025</p>
        </div>
    </div>

</body>
</html>