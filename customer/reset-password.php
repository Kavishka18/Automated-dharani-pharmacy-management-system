<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(!isset($_SESSION['customer_email']) || !isset($_SESSION['customer_mobile'])) {
    header('location: forgot-password.php');
    exit;
}

if(isset($_POST['submit'])) {
    $newpass = md5($_POST['newpassword']);
    $email = $_SESSION['customer_email'];

    $query = mysqli_query($con, "UPDATE tblcustomerlogin SET Password='$newpass' WHERE Email='$email'");
    if($query) {
        echo "<script>alert('Password changed successfully! Please login.');</script>";
        session_destroy();
        echo "<script>window.location.href='index.php'</script>";
    } else {
        $msg = "Something went wrong. Try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password - PMS</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    <script>
    function checkpass() {
        if(document.resetform.newpassword.value != document.resetform.confirmpassword.value) {
            alert('New Password and Confirm Password do not match!');
            return false;
        }
        return true;
    }
    </script>
</head>
<body class="bg-default">
<div class="main-content">
    <div class="header bg-gradient-primary py-7 py-lg-8">
        <div class="container">
            <div class="header-body text-center mb-7">
                <div class="row justify-content-center">
                    <div class="col-lg-5 col-md-6">
                        <h1 class="text-white">Set New Password</h1>
                        <p class="text-lead text-light">Create a strong password</p>
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
                            <h3>Create New Password</h3>
                        </div>
                        <form name="resetform" method="post" onsubmit="return checkpass();">
                            <p style="font-size:16px; color:red" align="center">
                                <?php if($msg) echo $msg; ?>
                            </p>

                            <div class="form-group">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
                                    <input type="password" name="newpassword" class="form-control" placeholder="New Password" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group input-group-alternative">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
                                    <input type="password" name="confirmpassword" class="form-control" placeholder="Confirm Password" required>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" name="submit" class="btn btn-primary my-4">
                                    Reset Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>