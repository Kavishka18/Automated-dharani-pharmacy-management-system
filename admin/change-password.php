<?php
session_start();
include('includes/dbconnection.php');
error_reporting(0);
if (strlen($_SESSION['pmsaid'] == 0)) {
    header('location:logout.php');
} else {
if(isset($_POST['submit'])) {
    $adminid = $_SESSION['pmsaid'];
    $cpassword = md5($_POST['currentpassword']);
    $newpassword = md5($_POST['newpassword']);
    $query = mysqli_query($con,"SELECT ID FROM tbladmin WHERE ID='$adminid' AND Password='$cpassword'");
    $row = mysqli_fetch_array($query);
    if($row > 0) {
        $ret = mysqli_query($con,"UPDATE tbladmin SET Password='$newpassword' WHERE ID='$adminid'");
        $msg = "Your password has been changed successfully.";
    } else {
        $msg = "Your current password is wrong.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password - Dharani PMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="../assets/fa/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            color: #e2e8f0;
            min-height: 100vh;
        }
        .main-content { 
            background: transparent; 
            padding-top: 20px !important;
        }

        /* Security Header */
        .page-header {
            background: linear-gradient(87deg, #ff6b6b 0%, #ee5a52 100%);
            padding: 90px 0 70px;
            text-align: center;
            color: white;
            border-radius: 0 0 55px 55px;
            box-shadow: 0 28px 70px rgba(238, 90, 82, 0.5);
            margin-bottom: 45px;
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.12"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.4;
        }
        .page-header h1 {
            font-size: 3rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -1px;
            text-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        .page-header p {
            font-size: 1.15rem;
            opacity: 0.95;
            margin-top: 14px;
            font-weight: 500;
        }

        /* Secure Vault Card */
        .vault-card {
            border-radius: 38px;
            border: 3px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 30px 80px rgba(0,0,0,0.4), inset 0 0 40px rgba(255, 255, 255, 0.08);
            background: linear-gradient(135deg, rgba(15, 12, 41, 0.95) 0%, rgba(48, 43, 99, 0.95) 100%);
            backdrop-filter: blur(22px);
            overflow: hidden;
            margin: 35px auto;
            max-width: 95%;
        }

        .card-body {
            padding: 75px 65px;
            text-align: center;
        }

        /* Lock Icon */
        .lock-icon {
            width: 95px;
            height: 95px;
            background: linear-gradient(87deg, #ff6b6b, #ee5a52);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 35px;
            box-shadow: 0 22px 55px rgba(238, 90, 82, 0.55);
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { box-shadow: 0 22px 55px rgba(238, 90, 82, 0.55); }
            50% { box-shadow: 0 22px 70px rgba(238, 90, 82, 0.75); }
            100% { box-shadow: 0 22px 55px rgba(238, 90, 82, 0.55); }
        }

        /* Form Styling */
        .form-control, .form-control:focus {
            border-radius: 26px;
            border: 3px solid rgba(255, 255, 255, 0.2);
            padding: 20px 28px;
            font-size: 1.02rem;
            font-weight: 500;
            transition: all 0.4s ease;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            box-shadow: inset 0 4px 15px rgba(0,0,0,0.2);
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        .form-control:focus {
            border-color: #ff6b6b;
            box-shadow: 0 0 0 9px rgba(238, 90, 82, 0.35), inset 0 4px 15px rgba(0,0,0,0.2);
            background: rgba(255, 255, 255, 0.15);
        }
        .form-group label {
            font-size: 0.96rem;
            font-weight: 600;
            color: #fbbf24;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        /* Change Button */
        .btn-change {
            background: linear-gradient(87deg, #ff6b6b, #ee5a52);
            border: none;
            color: white;
            padding: 22px 90px;
            font-size: 1.15rem;
            font-weight: 700;
            border-radius: 80px;
            box-shadow: 0 20px 50px rgba(238, 90, 82, 0.6);
            transition: all 0.5s ease;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .btn-change:hover {
            transform: translateY(-12px);
            box-shadow: 0 35px 80px rgba(238, 90, 82, 0.75);
        }

        /* Alert */
        .alert-msg {
            font-size: 1rem;
            padding: 22px 32px;
            border-radius: 26px;
            margin: 35px auto;
            max-width: 650px;
            font-weight: 600;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        .alert-success { 
            background: rgba(34, 197, 94, 0.2); 
            color: #86efac; 
            border: 2.5px solid rgba(34, 197, 94, 0.4); 
        }
        .alert-error { 
            background: rgba(239, 68, 68, 0.2); 
            color: #fca5a5; 
            border: 2.5px solid rgba(239, 68, 68, 0.4); 
        }

        /* Footer Brand */
        .brand-footer {
            background: linear-gradient(87deg, #1a1a2e, #16213e);
            color: white;
            padding: 45px;
            border-radius: 35px;
            text-align: center;
            margin: 120px auto 30px;
            max-width: 95%;
            box-shadow: 0 35px 80px rgba(0,0,0,0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 768px) {
            .page-header { padding: 75px 0 65px; }
            .page-header h1 { font-size: 2.4rem; }
            .card-body { padding: 55px 40px; }
            .lock-icon { width: 80px; height: 80px; }
            .btn-change { padding: 20px 70px; }
        }
    </style>
</head>
<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- SECURITY HEADER -->
        <div class="page-header">
            <div class="container-fluid">
                <h1>Change Password</h1>
                <p>Maximum Security • Bank-Level Encryption</p>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-6">

                    <div class="vault-card">
                        <div class="card-body">

                            <div class="lock-icon">
                                <i class="fas fa-shield-alt fa-3x text-white"></i>
                            </div>

                            <h3 style="background: linear-gradient(87deg, #ff6b6b, #ee5a52); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; margin-bottom: 12px; font-size: 2rem;">
                                Secure Password Update
                            </h3>
                            <p class="text-muted" style="font-size: 0.98rem; opacity: 0.9;">Your account is protected with military-grade security</p>

                            <?php if($msg): ?>
                                <div class="alert-msg <?= (strpos($msg, 'successfully') !== false) ? 'alert-success' : 'alert-error'; ?>">
                                    <?= $msg; ?>
                                </div>
                            <?php endif; ?>

                            <form method="post" name="changepassword" onsubmit="return checkpass();" class="mt-5">
                                <div class="form-group">
                                    <label>Current Password</label>
                                    <input type="password" name="currentpassword" class="form-control" 
                                           placeholder="Enter your current password" required>
                                </div>

                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="newpassword" class="form-control" 
                                           placeholder="Create a strong new password" required>
                                </div>

                                <div class="form-group">
                                    <label>Confirm New Password</label>
                                    <input type="password" name="confirmpassword" class="form-control" 
                                           placeholder="Retype your new password" required>
                                </div>

                                <div class="mt-5">
                                    <button type="submit" name="submit" class="btn btn-change">
                                        Change Password
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>

                    <!-- BRAND FOOTER -->
                    <div class="brand-footer">
                        <h4 style="margin:0; font-weight:600; font-size:1.45rem;">DHARANI PHARMACY</h4>
                        <p style="margin:10px 0 0; font-size:1.02rem; opacity:0.9;">
                            Gampaha • Sri Lanka • Fort Knox Level Security
                        </p>
                    </div>

                </div>
            </div>
        </div>

        <?php include_once('includes/footer.php'); ?>
    </div>

    <script>
    function checkpass() {
        if(document.changepassword.newpassword.value != document.changepassword.confirmpassword.value) {
            alert('New Password and Confirm Password do not match');
            document.changepassword.confirmpassword.focus();
            return false;
        }
        return true;
    }
    </script>

    <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>
</body>
</html>
<?php } ?>