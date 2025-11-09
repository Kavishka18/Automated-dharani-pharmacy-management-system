<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Security check
if (!isset($_SESSION['cspid']) || empty($_SESSION['cspid'])) {
    header('location: index.php');
    exit;
}

$msg = "";
if (isset($_POST['submit'])) {
    $custid = $_SESSION['cspid'];
    $currentpass = md5($_POST['currentpassword']);
    $newpass = md5($_POST['newpassword']);

    // Verify current password
    $check = mysqli_query($con, "SELECT ID FROM tblcustomerlogin WHERE ID='$custid' AND Password='$currentpass'");
    
    if (mysqli_num_rows($check) > 0) {
        $update = mysqli_query($con, "UPDATE tblcustomerlogin SET Password='$newpass' WHERE ID='$custid'");
        if ($update) {
            $msg = "Password changed successfully!";
        } else {
            $msg = "Something went wrong. Please try again.";
        }
    } else {
        $msg = "Current password is incorrect!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Change Password - Customer | PMS</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    
    <script>
    function checkpass() {
        if (document.changepassword.newpassword.value != document.changepassword.confirmpassword.value) {
            alert('New Password and Confirm Password do not match!');
            document.changepassword.confirmpassword.focus();
            return false;
        }
        return true;
    }
    </script>
</head>
<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- Header -->
        <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" 
             style="min-height: 30px; background-image: url(../assets/img/theme/profile-cover.jpg); background-size: cover; background-position: center top;">
            <span class="mask bg-gradient-default opacity-8"></span>
        </div>

        <!-- Page content -->
        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col-xl-8 order-xl-1 mx-auto">
                    <div class="card bg-secondary shadow">
                        <div class="card-header bg-white border-0">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">Change Password</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form name="changepassword" method="post" onsubmit="return checkpass();">
                                <?php if($msg): ?>
                                <div class="alert <?php echo ($msg == "Password changed successfully!") ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                                    <strong><?php echo $msg; ?></strong>
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                </div>
                                <?php endif; ?>

                                <h6 class="heading-small text-muted mb-4">Update your password</h6>
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="form-control-label">Current Password</label>
                                                <input type="password" name="currentpassword" class="form-control" required placeholder="Enter current password">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label">New Password</label>
                                                <input type="password" name="newpassword" class="form-control" required placeholder="Create new password">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Confirm New Password</label>
                                                <input type="password" name="confirmpassword" class="form-control" required placeholder="Re-type new password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4">
                                <div class="text-center">
                                    <button type="submit" name="submit" class="btn btn-primary btn-lg">
                                        Update Password
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
</body>
</html>