<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['submit'])) {
    $contactno = $_POST['contactno'];
    $email = $_POST['email'];
    $query = mysqli_query($con,"SELECT ID FROM tblpharmacist WHERE Email='$email' AND MobileNumber='$contactno'");
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
    <title>Pharmacist Recovery - DHARANI PMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary: #10b981;
            --accent: #34d399;
            --dark: #0f172a;
            --glass: rgba(15, 23, 42, 0.94);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #064e3b 0%, #0f172a 100%);
            color: #ecfdf5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Medical Recovery Background */
        .recovery-bg {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: 
                radial-gradient(circle at 25% 75%, rgba(16, 185, 129, 0.22) 0%, transparent 55%),
                radial-gradient(circle at 75% 25%, rgba(52, 211, 153, 0.2) 0%, transparent 55%),
                radial-gradient(circle at 50% 50%, rgba(16, 185, 129, 0.12) 0%, transparent 75%);
            animation: pulse-medical 16s infinite ease-in-out;
            z-index: 1;
        }
        @keyframes pulse-medical {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }

        /* Recovery Lab */
        .lab {
            position: relative;
            z-index: 10;
            width: 450px;
            background: var(--glass);
            backdrop-filter: blur(32px);
            border-radius: 48px;
            padding: 65px 55px;
            box-shadow: 
                0 50px 110px rgba(0,0,0,0.75),
                inset 0 0 90px rgba(16, 185, 129, 0.3);
            border: 3.5px solid rgba(16, 185, 129, 0.6);
            text-align: center;
            animation: labRise 1.4s ease-out;
        }
        @keyframes labRise {
            from { transform: translateY(80px) scale(0.92); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }

        /* Recovery Icon */
        .recovery-icon {
            width: 140px;
            height: 140px;
            background: linear-gradient(135deg, #10b981, #34d399);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 32px;
            box-shadow: 0 30px 80px rgba(16, 185, 129, 0.7);
            border: 8px solid rgba(255,255,255,0.35);
            animation: float-recovery 5s infinite ease-in-out;
        }
        @keyframes float-recovery {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(10deg); }
        }

        /* Titles */
        .title {
            font-size: 2.8rem;
            font-weight: 800;
            background: linear-gradient(87deg, #6ee7b7, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 14px;
        }
        .role {
            font-size: 1.35rem;
            font-weight: 700;
            color: #86efac;
            margin-bottom: 12px;
            letter-spacing: 1.2px;
        }
        .subtitle {
            font-size: 1.05rem;
            opacity: 0.94;
            margin-bottom: 45px;
            font-weight: 500;
            line-height: 1.7;
        }

        /* Input Fields */
        .input-group {
            position: relative;
            margin-bottom: 32px;
        }
        .input-group input {
            width: 100%;
            padding: 24px 68px;
            border-radius: 34px;
            border: 4px solid rgba(255,255,255,0.35);
            background: rgba(255,255,255,0.18);
            color: white;
            font-size: 1.08rem;
            font-weight: 500;
            transition: all 0.55s ease;
        }
        .input-group input::placeholder {
            color: rgba(255,255,255,0.75);
        }
        .input-group input:focus {
            outline: none;
            border-color: #34d399;
            box-shadow: 0 0 0 14px rgba(52, 211, 153, 0.45);
            background: rgba(255,255,255,0.25);
        }
        .input-group i {
            position: absolute;
            left: 26px;
            top: 50%;
            transform: translateY(-50%);
            color: #6ee7b7;
            font-size: 1.45rem;
        }

        /* Alert */
        .alert {
            padding: 22px 30px;
            border-radius: 26px;
            margin: 28px 0;
            font-size: 1rem;
            font-weight: 600;
            backdrop-filter: blur(16px);
            border: 3px solid rgba(239, 68, 68, 0.7);
            background: rgba(239, 68, 68, 0.35);
            color: #fca5a5;
        }

        /* Recover Button */
        .btn-recover {
            width: 100%;
            padding: 26px;
            background: linear-gradient(87deg, #10b981, #34d399);
            border: none;
            border-radius: 90px;
            color: white;
            font-size: 1.3rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 3.5px;
            cursor: pointer;
            box-shadow: 0 28px 70px rgba(16, 185, 129, 0.7);
            transition: all 0.7s ease;
        }
        .btn-recover:hover {
            transform: translateY(-14px);
            box-shadow: 0 45px 100px rgba(16, 185, 129, 0.9);
        }

        /* Links */
        .links {
            margin-top: 35px;
            font-size: 0.98rem;
        }
        .links a {
            color: #6ee7b7;
            text-decoration: none;
            font-weight: 600;
        }
        .links a:hover {
            color: #86efac;
        }

        /* Medical Seal */
        .seal {
            margin-top: 70px;
            padding: 42px;
            background: rgba(15, 23, 42, 0.97);
            border-radius: 42px;
            border: 4px solid rgba(16, 185, 129, 0.6);
        }
        .seal h3 {
            font-size: 2.1rem;
            font-weight: 800;
            background: linear-gradient(87deg, #6ee7b7, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
        }
        .seal p {
            font-size: 1rem;
            opacity: 0.94;
            margin: 16px 0 0;
        }

        @media (max-width: 480px) {
            .lab { width: 94%; padding: 55px 40px; }
            .title { font-size: 2.3rem; }
            .recovery-icon { width: 120px; height: 120px; }
        }
    </style>
</head>
<body>
    <div class="recovery-bg"></div>

    <div class="lab">
        <div class="recovery-icon">
            <i class="fas fa-user-shield fa-3x"></i>
        </div>

        <h1 class="title">RECOVERY</h1>
        <div class="role">PHARMACIST ACCESS</div>
        <p class="subtitle">Verify your identity to regain access<br>Only authorized pharmacy staff</p>

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
                Recover Access
            </button>
        </form>

        <div class="links">
            <a href="index.php">Back to Login</a>
        </div>

        <div class="seal">
            <h3>DHARANI PHARMACY</h3>
            <p>Pharmacist Recovery Lab • Gampaha • November 10, 2025</p>
        </div>
    </div>

</body>
</html>