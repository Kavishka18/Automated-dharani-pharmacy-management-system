<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if(isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = md5($_POST['password']);
    // Check if email already exists
    $check = mysqli_query($con, "SELECT ID FROM tblcustomerlogin WHERE Email='$email'");
    if(mysqli_num_rows($check) > 0) {
        $msg = "Email already registered!";
    } else {
        $query = mysqli_query($con, "INSERT INTO tblcustomerlogin (FullName, Email, MobileNumber, Password) VALUES ('$fullname', '$email', '$mobile', '$password')");
        if($query) {
            echo "<script>alert('Registration successful! Please login.');</script>";
            echo "<script>window.location.href='index.php'</script>";
        } else {
            $msg = "Something went wrong. Try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Join Patient Portal • DHARANI PMS</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
<style>
    :root{
        --bg: #0a0e1a;
        --card: rgba(255,255,255,0.05);
        --border: rgba(59,130,246,0.25);
        --blue: #3b82f6;
        --text: #e0e7ff;
        --muted: #94a3b8;
        --success: #10b981;
        --danger: #f87171;
    }
    *{margin:0;padding:0;box-sizing:border-box;font-family:'Inter',sans-serif;}
    body{
        background:linear-gradient(135deg,#0a0e1a,#1e40af);
        color:var(--text);
        min-height:100vh;
        display:flex;
        align-items:center;
        justify-content:center;
        overflow:hidden;
        position:relative;
    }
    .bg-wave{
        position:absolute;
        top:0;left:0;width:100%;height:100%;
        background:
            radial-gradient(circle at 15% 85%, rgba(59,130,246,0.2) 0%, transparent 55%),
            radial-gradient(circle at 85% 15%, rgba(99,102,241,0.18) 0%, transparent 55%),
            radial-gradient(circle at 50% 50%, rgba(59,130,246,0.1) 0%, transparent 70%);
        animation:wave 22s infinite ease-in-out;
    }
    @keyframes wave{0%,100%{opacity:0.65;}50%{opacity:1;}}

    /* Onboarding Portal */
    .portal{
        width:460px;
        background:var(--card);
        backdrop-filter:blur(24px);
        border:1px solid var(--border);
        border-radius:32px;
        padding:56px 48px;
        box-shadow:0 40px 100px rgba(0,0,0,0.55);
        position:relative;
        z-index:10;
        animation:portalRise 1.3s ease-out;
    }
    @keyframes portalRise{
        from{opacity:0;transform:translateY(50px) scale(0.94);}
        to{opacity:1;transform:translateY(0) scale(1);}
    }

    /* Welcome Badge */
    .badge{
        width:110px;height:110px;
        background:linear-gradient(135deg,#3b82f6,#6366f1);
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        margin:0 auto 30px;
        box-shadow:0 28px 70px rgba(59,130,246,0.55);
        border:7px solid rgba(255,255,255,0.28);
    }

    /* Titles */
    .title{
        font-size:2.1rem;
        font-weight:700;
        text-align:center;
        color:white;
        margin-bottom:12px;
        letter-spacing:-0.5px;
    }
    .subtitle{
        font-size:0.88rem;
        text-align:center;
        color:var(--muted);
        margin-bottom:38px;
        line-height:1.7;
        letter-spacing:0.6px;
    }

    /* Form */
    .form-group{
        margin-bottom:24px;
    }
    label{
        font-size:0.73rem;
        font-weight:600;
        color:var(--muted);
        text-transform:uppercase;
        letter-spacing:1.4px;
        margin-bottom:9px;
        display:block;
    }
    input{
        width:100%;
        padding:16px 22px;
        border:1px solid rgba(255,255,255,0.16);
        background:rgba(255,255,255,0.08);
        border-radius:18px;
        color:white;
        font-size:0.97rem;
        transition:0.45s;
    }
    input:focus{
        outline:none;
        border-color:var(--blue);
        box-shadow:0 0 0 6px rgba(59,130,246,0.35);
        background:rgba(255,255,255,0.12);
    }
    input::placeholder{color:rgba(255,255,255,0.5);}

    /* Alert */
    .alert{
        background:rgba(248,113,113,0.2);
        border:1px solid rgba(248,113,113,0.4);
        color:#fca5a5;
        padding:15px 20px;
        border-radius:16px;
        font-size:0.89rem;
        text-align:center;
        margin:26px 0;
        backdrop-filter:blur(12px);
    }

    /* Button */
    .create-btn{
        width:100%;
        padding:18px;
        background:linear-gradient(135deg,#3b82f6,#6366f1);
        border:none;
        border-radius:20px;
        color:white;
        font-weight:600;
        font-size:1.02rem;
        cursor:pointer;
        transition:0.6s;
        margin:26px 0;
    }
    .create-btn:hover{
        transform:translateY(-5px);
        box-shadow:0 30px 60px rgba(59,130,246,0.6);
    }

    /* Login Link */
    .login-link{
        text-align:center;
        font-size:0.87rem;
        margin-top:22px;
    }
    .login-link a{
        color:var(--blue);
        text-decoration:none;
        font-weight:500;
    }
    .login-link a:hover{color:#93c5fd;}

    /* Brand Seal */
    .seal{
        margin-top:55px;
        text-align:center;
        padding:24px;
        background:rgba(255,255,255,0.04);
        border-radius:20px;
        border:1px solid rgba(59,130,246,0.25);
    }
    .seal strong{
        font-size:1.1rem;
        color:white;
        display:block;
        margin-bottom:6px;
    }
    .seal p{
        font-size:0.79rem;
        color:var(--muted);
        margin:0;
    }
</style>
</head>
<body>
<div class="bg-wave"></div>

<div class="portal">
    <div class="badge">
        <i class="fas fa-user-plus fa-2x"></i>
    </div>

    <h1 class="title">Join Portal</h1>
    <p class="subtitle">Create your secure customer account<br>DHARANI PHARMACY • Gampaha, Sri Lanka</p>

    <?php if($msg): ?>
        <div class="alert"><?php echo $msg; ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="fullname" placeholder="Your full name" required>
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="you@example.com" required>
        </div>

        <div class="form-group">
            <label>Mobile Number</label>
            <input type="text" name="mobile" placeholder="077xxxxxxx" maxlength="10" pattern="[0-9]{10}" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Create strong password" required>
        </div>

        <button type="submit" name="submit" class="create-btn">
            Create My Account
        </button>
    </form>

    <div class="login-link">
        Already have an account? <a href="index.php">Sign In</a>
    </div>

    <div class="seal">
        <strong>DHARANI PHARMACY</strong>
        <p>Patient Onboarding Portal • Military-Grade Security • 2025</p>
    </div>
</div>
</body>
</html>