<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (!isset($_SESSION['cspid']) || empty($_SESSION['cspid'])) {
    header('location: index.php');
    exit;
}

if (isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $mobnumber = $_POST['mobnumber'];
    $email = $_POST['email'];
    $custid = $_SESSION['cspid'];

    // Prevent email change to existing one
    $check = mysqli_query($con, "SELECT ID FROM tblcustomerlogin WHERE Email='$email' AND ID != '$custid'");
    if (mysqli_num_rows($check) > 0) {
        $msg = "This email is already used by another account!";
    } else {
        $query = mysqli_query($con, "UPDATE tblcustomerlogin SET FullName='$fullname', MobileNumber='$mobnumber', Email='$email' WHERE ID='$custid'");
        if ($query) {
            $msg = "Profile updated successfully!";
            // Update session name
            $_SESSION['customername'] = $fullname;
        } else {
            $msg = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Customer Profile - PMS</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
</head>
<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" 
             style="min-height: 30px; background-image: url(../assets/img/theme/profile-cover.jpg); background-size: cover; background-position: center top;">
            <span class="mask bg-gradient-default opacity-8"></span>
        </div>

        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col-xl-12 order-xl-1">
                    <div class="card bg-secondary shadow">
                        <div class="card-header bg-white border-0">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h3 class="mb-0">My Profile</h3>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <p style="font-size:16px; color:red" align="center">
                                    <?php if($msg) echo $msg; ?>
                                </p>

                                <?php
                                $custid = $_SESSION['cspid'];
                                $ret = mysqli_query($con, "SELECT * FROM tblcustomerlogin WHERE ID='$custid'");
                                while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                <h6 class="heading-small text-muted mb-4">Personal Information</h6>
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Full Name</label>
                                                <input type="text" name="fullname" class="form-control" 
                                                       value="<?php echo $row['FullName']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Email Address</label>
                                                <input type="email" name="email" class="form-control" 
                                                       value="<?php echo $row['Email']; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Mobile Number</label>
                                                <input type="text" name="mobnumber" class="form-control" 
                                                       value="<?php echo $row['MobileNumber']; ?>" maxlength="10" 
                                                       pattern="[0-9]{10}" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label">Registration Date</label>
                                                <input type="text" class="form-control" 
                                                       value="<?php echo date('d M Y', strtotime($row['RegDate'])); ?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4">
                                <div class="text-center">
                                    <button type="submit" name="submit" class="btn btn-primary my-4">
                                        Update Profile
                                    </button>
                                </div>
                                <?php } ?>
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