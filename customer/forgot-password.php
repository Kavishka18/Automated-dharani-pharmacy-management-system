<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['submit'])) {
    $email = $_POST['email'];
    $mobile = $_POST['contactno'];
    $query = mysqli_query($con, "SELECT ID FROM tblcustomerlogin WHERE Email='$email' AND MobileNumber='$mobile'");
    $ret = mysqli_fetch_array($query);
    if($ret > 0) {
        $_SESSION['customer_email'] = $email;
        $_SESSION['customer_mobile'] = $mobile;
        header('location: reset-password.php');
        exit;
    } else {
        $msg = "Invalid Email or Mobile Number. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Account Recovery • DHARANI PMS</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
<style>
    :root{
        --bg: #0a0e1a;
        --card: rgba(255,255,255,0.045);
        --border: rgba(59,130,246,0.22);
        --blue: #3b82f6;
        --text: #e0e7ff;
        --muted: #94a3b8;
        --danger: #f87171;
        --success: #10b981;
    }
    *{margin:0;padding:0;box-sizing:border-box;font-family:'Inter',sans-serif;}
    body{
        background:linear-gradient(135deg,#0a0e1a,#1e3a8a);
        color:var(--text);
        min-height:100vh;
        display:flex;
        align-items:center;
        justify-content:center;
        overflow:hidden;
        position:relative;
    }
    .bg-glow{
        position:absolute;
        top:0;left:0;width:100%;height:100%;
        background:
            radial-gradient(circle at 30% 70%, rgba(59,130,246,0.18) 0%, transparent 60%),
            radial-gradient(circle at 70% 30%, rgba(99,102,241,0.15) 0%, transparent 60%);
        animation:glow 18s infinite ease-in-out;
    }
    @keyframes glow{0%,100%{opacity:0.7;}50%{opacity:1;}}

    /* Recovery Lab */
    .lab{
        width:440px;
        background:var(--card);
        backdrop-filter:blur(22px);
        border:1px solid var(--border);
        border-radius:28px;
        padding:52px 44px;
        box-shadow:0 35px 90px rgba(0,0,0,0.5);
        position:relative;
        z-index:10;
        animation:rise 1.2s ease-out;
    }
    @keyframes rise{
        from{opacity:0;transform:translateY(40px) scale(0.95);}
        to{opacity:1;transform:translateY(0) scale(1);}
    }

    /* Shield Icon */
    .shield{
        width:100px;height:100px;
        background:linear-gradient(135deg,#3b82f6,#6366f1);
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        margin:0 auto 28px;
        box-shadow:0 25px 60px rgba(59,130,246,0.5);
        border:6px solid rgba(255,255,255,0.25);
    }

    /* Titles */
    .title{
        font-size:2rem;
        font-weight:700;
        text-align:center;
        color:white;
        margin-bottom:10px;
    }
    .subtitle{
        font-size:0.86rem;
        text-align:center;
        color:var(--muted);
        margin-bottom:36px;
        line-height:1.6;
        letter-spacing:0.5px;
    }

    /* Form */
    .form-group{
        margin-bottom:22px;
    }
    label{
        font-size:0.74rem;
        font-weight:600;
        color:var(--muted);
        text-transform:uppercase;
        letter-spacing:1.3px;
        margin-bottom:8px;
        display:block;
    }
    input{
        width:100%;
        padding:15px 20px;
        border:1px solid rgba(255,255,255,0.14);
        background:rgba(255,255,255,0.07);
        border-radius:16px;
        color:white;
        font-size:0.96rem;
        transition:0.4s;
    }
    input:focus{
        outline:none;
        border-color:var(--blue);
        box-shadow:0 0 0 5px rgba(59,130,246,0.3);
        background:rgba(255,255,255,0.1);
    }
    input::placeholder{color:rgba(255,255,255,0.5);}

    /* Alert */
    .alert{
        background:rgba(248,113,113,0.18);
        border:1px solid rgba(248,113,113,0.35);
        color:#fca5a5;
        padding:14px 18px;
        border-radius:14px;
        font-size:0.88rem;
        text-align:center;
        margin:24px 0;
        backdrop-filter:blur(10px);
    }

    /* Button */
    .recover-btn{
        width:100%;
        padding:17px;
        background:linear-gradient(135deg,#3b82f6,#6366f1);
        border:none;
        border-radius:18px;
        color:white;
        font-weight:600;
        font-size:1rem;
        cursor:pointer;
        transition:0.5s;
        margin:24px 0;
    }
    .recover-btn:hover{
        transform:translateY(-4px);
        box-shadow:0 25px 50px rgba(59,130,246,0.5);
    }

    /* Back Link */
    .back{
        text-align:center;
        font-size:0.86rem;
        margin-top:20px;
    }
    .back a{
        color:var(--blue);
        text-decoration:none;
        font-weight:500;
    }
    .back a:hover{color:#93c5fd;}

    /* Seal */
    .seal{
        margin-top:50px;
        text-align:center;
        padding:20px;
        background:rgba(255,255,255,0.03);
        border-radius:16px;
        border:1px solid rgba(59,130,246,0.2);
    }
    .seal p{
        font-size:0.78rem;
        color:var(--muted);
        margin:8px 0 0;
    }
</style>
</head>
<body>
<div class="bg-glow"></div>

<div class="lab">
    <div class="shield">
        <i class="fas fa-shield-alt fa-2x"></i>
    </div>

    <h1 class="title">Account Recovery</h1>
    <p class="subtitle">Verify your identity to regain access<br>DHARANI PHARMACY • Customer Portal</p>

    <?php if($msg): ?>
        <div class="alert"><?php echo $msg; ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="you@example.com" required>
        </div>

        <div class="form-group">
            <label>Mobile Number</label>
            <input type="text" name="contactno" placeholder="077xxxxxxx" maxlength="10" pattern="[0-9]{10}" required>
        </div>

        <button type="submit" name="submit" class="recover-btn">
            Send Recovery Link
        </button>
    </form>

    <div class="back">
        <a href="index.php">Back to Login</a>
    </div>

    <div class="seal">
        <strong>DHARANI PHARMACY</strong>
        <p>Patient Recovery Lab • Gampaha • November 10, 2025</p>
    </div>
</div>
</body>
</html>