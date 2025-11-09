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
    <title>Pharmacy Management System - Customer Login</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
</head>
<body class="bg-default">
<div class="main-content">
    <div class="header bg-gradient-primary py-7 py-lg-6">
        <div class="container">
            <div class="header-body text-center mb-7">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-6">
                        <h1 class="text-white">Welcome Customer!</h1>
                        <p class="text-lead text-light">Pharmacy Management System</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="separator separator-bottom separator-skew zindex-100">
            <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
            </svg>
        </div>
    </div>

    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <h3>Customer Sign In</h3>
                        </div>
                        <form role="form" method="post">
                            <p style="font-size:16px; color:red" align="center">
                                <?php if($msg) echo $msg; ?>
                            </p>
                            <div class="form-group mb-3">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                    </div>
                                    <input class="form-control" type="email" name="email" placeholder="Email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
                                    <input class="form-control" type="password" name="password" placeholder="Password" required>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="login" class="btn btn-primary my-4">Sign In</button>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <a href="forgot-password.php"><small>Forgot password?</small></a>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="register.php"><small>Create new account</small></a>
                                </div>
                            </div>
                            <hr>
                            <div align="center">
                                <a href="../index.php">Back to Home</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
<script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>
</body>
</html>