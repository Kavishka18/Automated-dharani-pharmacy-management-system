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
    <title>Customer Registration - PMS</title>
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
                        <h1 class="text-white">Create Account</h1>
                        <p class="text-lead text-light">Join Pharmacy Management System</p>
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
            <div class="col-lg-6 col-md-8">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <h3>Customer Registration</h3>
                        </div>
                        <form method="post">
                            <p style="font-size:16px; color:red" align="center"><?php if($msg) echo $msg; ?></p>
                            
                            <div class="form-group">
                                <input type="text" name="fullname" class="form-control" placeholder="Full Name" required>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                            </div>
                            <div class="form-group">
                                <input type="text" name="mobile" class="form-control" placeholder="Mobile Number" maxlength="10" pattern="[0-9]{10}" required>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control" placeholder="Password" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="submit" class="btn btn-primary mt-4">Create Account</button>
                            </div>
                            <div class="mt-3 text-center">
                                <small>Already have an account? <a href="index.php">Sign In</a></small>
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