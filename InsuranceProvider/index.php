<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['login'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = md5($_POST['password']);
    $query = mysqli_query($con, "SELECT ID, ProviderName FROM tblinsuranceprovider WHERE Username='$username' AND Password='$password'");
    $ret = mysqli_fetch_array($query);
    if($ret) {
        $_SESSION['insid'] = $ret['ID'];
        $_SESSION['providername'] = $ret['ProviderName'];
        header('location:dashboard.php');
        exit();
    } else {
        $msg = "Invalid credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Insurance Provider Portal • DHARANI PMS</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
<style>
    :root{
        --bg: #0f172a;
        --card: rgba(15, 23, 42, 0.92);
        --border: rgba(34, 197, 94, 0.28);
        --green: #22c55e;
        --text: #f1f5f9;
        --muted: #94a3b8;
        --danger: #f87171;
    }
    *{margin:0;padding:0;box-sizing:border-box;font-family:'Inter',sans-serif;}
    body{
        background:linear-gradient(135deg,#0f172a,#166534);
        color:var(--text);
        min-height:100vh;
        display:flex;
        align-items:center;
        justify-content:center;
        overflow:hidden;
        position:relative;
    }
    .bg-pulse{
        position:absolute;
        top:0;left:0;width:100%;height:100%;
        background:
            radial-gradient(circle at 25% 75%, rgba(34,197,94,0.22) 0%, transparent 60%),
            radial-gradient(circle at 75% 25%, rgba(74,222,128,0.18) 0%, transparent 60%);
        animation:pulse 16s infinite ease-in-out;
    }
    @keyframes pulse{0%,100%{opacity:0.7;}50%{opacity:1;}}

    /* Corporate Portal */
    .portal{
        width:460px;
        background:var(--card);
        backdrop-filter:blur(28px);
        border:2px solid var(--border);
        border-radius:34px;
        padding:60px 50px;
        box-shadow:0 45px 110px rgba(0,0,0,0.6);
        position:relative;
        z-index:10;
        animation:portalEntry 1.4s ease-out;
    }
    @keyframes portalEntry{
        from{opacity:0;transform:translateY(60px) scale(0.93);}
        to{opacity:1;transform:translateY(0) scale(1);}
    }

    /* Shield Badge */
    .shield-badge{
        width:120px;height:120px;
        background:linear-gradient(135deg,#22c55e,#4ade80);
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        margin:0 auto 32px;
        box-shadow:0 32px 80px rgba(34,197,94,0.65);
        border:8px solid rgba(255,255,255,0.3);
    }

    /* Titles */
    .title{
        font-size:2.3rem;
        font-weight:800;
        text-align:center;
        background:linear-gradient(87deg,#86efac,#4ade80);
        -webkit-background-clip:text;
        -webkit-text-fill-color:transparent;
        margin-bottom:12px;
        letter-spacing:-1px;
    }
    .subtitle{
        font-size:0.89rem;
        text-align:center;
        color:var(--muted);
        margin-bottom:40px;
        line-height:1.7;
        letter-spacing:0.7px;
    }

    /* Form */
    .form-group{
        margin-bottom:26px;
    }
    label{
        font-size:0.72rem;
        font-weight:600;
        color:var(--muted);
        text-transform:uppercase;
        letter-spacing:1.5px;
        margin-bottom:10px;
        display:block;
    }
    input{
        width:100%;
        padding:17px 24px;
        border:2px solid rgba(255,255,255,0.18);
        background:rgba(255,255,255,0.09);
        border-radius:20px;
        color:white;
        font-size:0.98rem;
        transition:0.5s;
    }
    input:focus{
        outline:none;
        border-color:var(--green);
        box-shadow:0 0 0 7px rgba(34,197,94,0.38);
        background:rgba(255,255,255,0.14);
    }
    input::placeholder{color:rgba(255,255,255,0.5);}

    /* Alert */
    .alert{
        background:rgba(248,113,113,0.22);
        border:2px solid rgba(248,113,113,0.45);
        color:#fca5a5;
        padding:16px 22px;
        border-radius:18px;
        font-size:0.89rem;
        text-align:center;
        margin:28px 0;
        backdrop-filter:blur(14px);
    }

    /* Login Button */
    .login-btn{
        width:100%;
        padding:19px;
        background:linear-gradient(135deg,#22c55e,#4ade80);
        border:none;
        border-radius:22px;
        color:white;
        font-weight:700;
        font-size:1.05rem;
        cursor:pointer;
        transition:0.7s;
        margin:28px 0;
        text-transform:uppercase;
        letter-spacing:2px;
    }
    .login-btn:hover{
        transform:translateY(-6px);
        box-shadow:0 35px 70px rgba(34,197,94,0.7);
    }

    /* Brand Seal */
    .seal{
        margin-top:60px;
        text-align:center;
        padding:28px;
        background:rgba(15,23,42,0.98);
        border-radius:24px;
        border:3px solid rgba(34,197,94,0.5);
    }
    .seal strong{
        font-size:1.3rem;
        font-weight:800;
        background:linear-gradient(87deg,#86efac,#4ade80);
        -webkit-background-clip:text;
        -webkit-text-fill-color:transparent;
        display:block;
        margin-bottom:8px;
    }
    .seal p{
        font-size:0.81rem;
        color:var(--muted);
        margin:0;
        letter-spacing:0.5px;
    }
</style>
</head>
<body>
<div class="bg-pulse"></div>

<div class="portal">
    <div class="shield-badge">
        <i class="fas fa-hospital fa-2x"></i>
    </div>

    <h1 class="title">INSURANCE</h1>
    <p class="subtitle">Corporate Health Partner Portal<br>DHARANI PHARMACY • Authorized Access Only</p>

    <?php if($msg): ?>
        <div class="alert"><?php echo $msg; ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label>Provider Username</label>
            <input type="text" name="username" placeholder="Enter your username" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

        <button type="submit" name="login" class="login-btn">
            Enter Portal
        </button>
    </form>

    <div class="seal">
        <strong>DHARANI PHARMACY</strong>
        <p>Insurance Provider Gateway • Gampaha • November 10, 2025</p>
    </div>
</div>
</body>
</html>