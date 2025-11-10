<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $query = mysqli_query($con, "SELECT ID, FullName FROM tblcustomerlogin WHERE Email='$email' && Password='$password'");
    $ret = mysqli_fetch_array($query);
    if($ret > 0) {
        $_SESSION['cspid'] = $ret['ID'];
        $_SESSION['customername'] = $ret['FullName'];
        header('location:dashboard.php');
    } else {
        $msg = "Invalid Email or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Patient Portal • DHARANI PMS</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
<style>
    :root{
        --bg: #0a0e1a;
        --card: rgba(255,255,255,0.045);
        --border: rgba(59,130,246,0.2);
        --blue: #3b82f6;
        --text: #e0e7ff;
        --muted: #94a3b8;
        --danger: #f87171;
    }
    *{margin:0;padding:0;box-sizing:border-box;font-family:'Inter',sans-serif;}
    body{
        background:linear-gradient(135deg,#0a0e1a,#1e293b);
        color:var(--text);
        min-height:100vh;
        display:flex;
        align-items:center;
        justify-content:center;
        overflow:hidden;
    }
    .bg-particles{
        position:absolute;
        top:0;left:0;width:100%;height:100%;
        background:
            radial-gradient(circle at 20% 80%, rgba(59,130,246,0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(99,102,241,0.12) 0%, transparent 50%);
        animation:pulse 20s infinite;
    }
    @keyframes pulse{0%,100%{opacity:0.6;}50%{opacity:1;}}
    
    /* Portal Card */
    .portal{
        width:420px;
        background:var(--card);
        backdrop-filter:blur(20px);
        border:1px solid var(--border);
        border-radius:24px;
        padding:48px 40px;
        box-shadow:0 30px 80px rgba(0,0,0,0.4);
        position:relative;
        z-index:10;
        animation:fadeIn 1s ease-out;
    }
    @keyframes fadeIn{
        from{opacity:0;transform:translateY(30px);}
        to{opacity:1;transform:translateY(0);}
    }
    
    /* Logo */
    .logo{
        width:90px;height:90px;
        background:linear-gradient(135deg,#3b82f6,#6366f1);
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        margin:0 auto 24px;
        box-shadow:0 20px 50px rgba(59,130,246,0.4);
        border:5px solid rgba(255,255,255,0.2);
    }
    
    /* Titles */
    .title{
        font-size:1.9rem;
        font-weight:700;
        text-align:center;
        color:white;
        margin-bottom:8px;
    }
    .subtitle{
        font-size:0.88rem;
        text-align:center;
        color:var(--muted);
        margin-bottom:32px;
        letter-spacing:0.5px;
    }
    
    /* Form */
    .form-group{
        margin-bottom:20px;
    }
    label{
        font-size:0.76rem;
        font-weight:600;
        color:var(--muted);
        text-transform:uppercase;
        letter-spacing:1.2px;
        margin-bottom:8px;
        display:block;
    }
    input{
        width:100%;
        padding:14px 18px;
        border:1px solid rgba(255,255,255,0.12);
        background:rgba(255,255,255,0.06);
        border-radius:14px;
        color:white;
        font-size:0.95rem;
        transition:0.3s;
    }
    input:focus{
        outline:none;
        border-color:var(--blue);
        box-shadow:0 0 0 4px rgba(59,130,246,0.25);
    }
    input::placeholder{color:rgba(255,255,255,0.5);}
    
    /* Alert */
    .alert{
        background:rgba(248,113,113,0.15);
        border:1px solid rgba(248,113,113,0.3);
        color:#fca5a5;
        padding:12px 16px;
        border-radius:12px;
        font-size:0.88rem;
        text-align:center;
        margin:20px 0;
    }
    
    /* Button */
    .login-btn{
        width:100%;
        padding:16px;
        background:linear-gradient(135deg,#3b82f6,#6366f1);
        border:none;
        border-radius:16px;
        color:white;
        font-weight:600;
        font-size:0.98rem;
        cursor:pointer;
        transition:0.4s;
        margin:20px 0;
    }
    .login-btn:hover{
        transform:translateY(-3px);
        box-shadow:0 20px 40px rgba(59,130,246,0.4);
    }
    
    /* Links */
    .links{
        display:flex;
        justify-content:space-between;
        font-size:0.84rem;
        margin-top:20px;
    }
    .links a{
        color:var(--blue);
        text-decoration:none;
        font-weight:500;
    }
    .links a:hover{color:#93c5fd;}
    
    /* Footer */
    .footer{
        text-align:center;
        margin-top:40px;
        font-size:0.82rem;
        color:var(--muted);
    }
    .footer a{color:var(--blue);}
</style>
</head>
<body>
<div class="bg-particles"></div>

<div class="portal">
    <div class="logo">
        <i class="fas fa-heartbeat fa-2x"></i>
    </div>
    
    <h1 class="title">Customer Portal</h1>
    <p class="subtitle">DHARANI PHARMACY • Secure Access</p>
    
    <?php if($msg): ?>
        <div class="alert"><?php echo $msg; ?></div>
    <?php endif; ?>
    
    <form method="post">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="you@example.com" required>
        </div>
        
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        
        <button type="submit" name="login" class="login-btn">
            Sign In to Portal
        </button>
    </form>
    
    <div class="links">
        <a href="forgot-password.php">Forgot password?</a>
        <a href="register.php">Create account</a>
    </div>
    
    <div class="footer">
        <a href="../index.php">Back to Home</a>
    </div>
</div>
</body>
</html>