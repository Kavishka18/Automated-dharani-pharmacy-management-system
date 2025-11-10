<?php
session_start();
include('includes/dbconnection.php');
error_reporting(0);
if (strlen($_SESSION['pmspid'] == 0)) {
    header('location:logout.php');
} else {
if (isset($_POST['submit'])) {
    $pid = $_SESSION['pmspid'];
    $cpassword = md5($_POST['currentpassword']);
    $newpassword = md5($_POST['newpassword']);
    $query = mysqli_query($con, "SELECT ID FROM tblpharmacist WHERE ID='$pid' AND Password='$cpassword'");
    $row = mysqli_fetch_array($query);
    if ($row > 0) {
        $ret = mysqli_query($con, "UPDATE tblpharmacist SET Password='$newpassword' WHERE ID='$pid'");
        $msg = "Your password has been successfully changed!";
    } else {
        $msg = "Your current password is wrong!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dharani Pharmacy - Change Password</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            border-radius: 0 0 3rem 3rem;
            overflow: hidden;
        }
        .header::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: url('https://images.unsplash.com/photo-1559839915-09a43de83d4f?ixlib=rb-4.0.3&auto=format&fit=crop&q=80') center/cover;
            opacity: 0.2;
        }
        .card {
            border: none; border-radius: 2rem; 
            box-shadow: 0 30px 80px rgba(0,0,0,0.25);
            backdrop-filter: blur(20px);
            background: rgba(255,255,255,0.97);
            overflow: hidden;
        }
        .lock-icon {
            font-size: 4.5rem;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: float 4s ease-in-out infinite;
        }
        @keyframes float {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        .form-control {
            border-radius: 1.5rem;
            padding: 1rem 1.4rem;
            border: 2px solid #e2e8f0;
            transition: all 0.4s ease;
            font-size: 1.1rem;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.3rem rgba(102,126,234,0.25);
            transform: translateY(-5px);
        }
        .input-group-text {
            border-radius: 1.5rem 0 0 1.5rem;
            background: #667eea;
            color: white;
            border: none;
            font-size: 1.2rem;
        }
        .btn-change {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            border: none;
            border-radius: 50px;
            padding: 16px 60px;
            font-weight: 700;
            font-size: 1.2rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            box-shadow: 0 15px 35px rgba(231,76,60,0.4);
            transition: all 0.5s ease;
        }
        .btn-change:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(231,76,60,0.6);
        }
        .alert-success {
            background: linear-gradient(45deg, #56ab2f, #a8e6cf);
            color: white;
            border: none;
            border-radius: 2rem;
            padding: 1.8rem;
            text-align: center;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .alert-danger {
            background: linear-gradient(45deg, #e74c3c, #f8b5b5);
            color: white;
            border: none;
            border-radius: 2rem;
            padding: 1.8rem;
            text-align: center;
            font-weight: 600;
            font-size: 1.1rem;
        }
        .password-toggle {
            cursor: pointer;
            color: #667eea;
        }
        .time-info {
            background: rgba(102,126,234,0.1);
            border-radius: 1.5rem;
            padding: 1.2rem;
            border-left: 6px solid #667eea;
            margin: 25px 0;
        }
        .security-tips {
            background: #f8f9ff;
            border-radius: 1.5rem;
            padding: 1.5rem;
            border-left: 5px solid #667eea;
        }
        @media (max-width: 768px) {
            .header { min-height: 250px; }
            .card { margin: 1rem; }
            .lock-icon { font-size: 3.5rem; }
        }
    </style>
</head>
<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- Header -->
        <div class="header pb-8 pt-5 pt-lg-8">
            <div class="container-fluid">
                <!-- <div class="header-body text-center text-white">
                    <h1 class="display-3 font-weight-bold">
                        <i class="fas fa-shield-alt mr-4"></i>
                        Change Password
                    </h1>
                    <p class="lead">Keep your account secure with a strong password</p>
                </div> -->
            </div>
        </div>

        <div class="container-fluid mt--9">
            <div class="row justify-content-center">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body p-5">
                            <div class="text-center mb-5">
                                <i class="fas fa-lock lock-icon"></i>
                                <h2 class="mt-4">Secure Password Update</h2>
                            </div>

                            <div class="time-info text-center">
                                <small>
                                    Sri Lanka Time: <?php echo date('d M Y, h:i A'); ?> (UTC+5:30)
                                </small>
                            </div>

                            <?php if($msg): ?>
                                <div class="alert <?php echo ($row > 0) ? 'alert-success' : 'alert-danger'; ?> mt-4">
                                    <i class="fas <?php echo ($row > 0) ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?> mr-3"></i>
                                    <?php echo $msg; ?>
                                </div>
                            <?php endif; ?>

                            <form method="post" name="changepassword" onsubmit="return checkpass();">
                                <div class="form-group">
                                    <label class="font-weight-bold text-primary">
                                        Current Password
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <input type="password" name="currentpassword" class="form-control" required 
                                               placeholder="Enter current password" id="currentpassword">
                                        <div class="input-group-append">
                                            <span class="input-group-text password-toggle" onclick="togglePass('currentpassword')">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold text-primary">
                                        New Password
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        </div>
                                        <input type="password" name="newpassword" class="form-control" required 
                                               placeholder="Enter new password" id="newpassword">
                                        <div class="input-group-append">
                                            <span class="input-group-text password-toggle" onclick="togglePass('newpassword')">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold text-primary">
                                        Confirm New Password
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                        </div>
                                        <input type="password" name="confirmpassword" class="form-control" required 
                                               placeholder="Confirm new password" id="confirmpassword">
                                        <div class="input-group-append">
                                            <span class="input-group-text password-toggle" onclick="togglePass('confirmpassword')">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="security-tips mt-4">
                                    <small class="text-muted">
                                        <strong>Password Tips:</strong><br>
                                        • Use at least 8 characters<br>
                                        • Include numbers & symbols<br>
                                        • Avoid common words
                                    </small>
                                </div>

                                <div class="text-center mt-5">
                                    <button type="submit" name="submit" class="btn btn-change text-white">
                                        <i class="fas fa-shield-alt mr-3"></i>
                                        Change Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>

    <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>

    <script type="text/javascript">
        function checkpass() {
            if (document.changepassword.newpassword.value != document.changepassword.confirmpassword.value) {
                alert('New Password and Confirm Password do not match!');
                document.changepassword.confirmpassword.focus();
                return false;
            }
            return true;
        }

        function togglePass(id) {
            const field = document.getElementById(id);
            const icon = field.parentElement.querySelector('.password-toggle i');
            if (field.type === "password") {
                field.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
<?php } ?>