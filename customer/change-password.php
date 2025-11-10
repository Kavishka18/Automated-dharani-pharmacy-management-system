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
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
<link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
<link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    .form-control {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 12px;
        padding: 12px 16px;
        color: white;
        transition: all 0.3s ease;
    }
    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }
    .form-control:focus {
        background: rgba(255, 255, 255, 0.3);
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
        color: white;
    }
    .input-group-text {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 12px;
        color: white;
        cursor: pointer;
    }
    .btn-primary {
        background: linear-gradient(45deg, #4facfe, #00f2fe);
        border: none;
        border-radius: 50px;
        padding: 12px 40px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.4s ease;
        box-shadow: 0 10px 20px rgba(79, 172, 254, 0.4);
    }
    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(79, 172, 254, 0.6);
    }
    .alert {
        border-radius: 15px;
        border: none;
        padding: 15px 20px;
        font-weight: 500;
    }
    .alert-success {
        background: rgba(40, 167, 69, 0.3);
        color: #fff;
        backdrop-filter: blur(10px);
    }
    .alert-danger {
        background: rgba(220, 53, 69, 0.3);
        color: #fff;
        backdrop-filter: blur(10px);
    }
    .page-header {
        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url(../assets/img/theme/profile-cover.jpg);
        background-size: cover;
        background-position: center;
        border-radius: 20px 20px 0 0;
        height: 180px;
        position: relative;
    }
    h3.mb-0 {
        color: white;
        font-weight: 700;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }
    .floating-label {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .floating-label input {
        padding-top: 20px;
    }
    .floating-label label {
        position: absolute;
        top: 15px;
        left: 16px;
        color: rgba(255,255,255,0.7);
        transition: all 0.3s ease;
        pointer-events: none;
        font-size: 14px;
    }
    .floating-label input:focus ~ label,
    .floating-label input:not(:placeholder-shown) ~ label {
        top: 8px;
        font-size: 12px;
        color: #00f2fe;
    }
</style>
</head>
<body class="">
<?php include_once('includes/navbar.php'); ?>
<div class="main-content">
<?php include_once('includes/sidebar.php'); ?>

<!-- Enhanced Header -->
<div class="page-header">
    <div class="container-fluid h-100 d-flex align-items-center">
        <!-- <div>
            <h3 class="mb-0 display-4">Change Password</h3>
            <p class="text-white opacity-8">Keep your account secure with a strong password</p>
        </div> -->
    </div>
</div>

<!-- Page content -->
<div class="container-fluid mt--7 pb-5">
<div class="row justify-content-center">
<div class="col-xl-8 col-lg-10">
<div class="card glass-card shadow-lg">
<div class="card-body p-5">

<form name="changepassword" method="post" onsubmit="return checkpass();">
    <?php if($msg): ?>
    <div class="alert <?php echo ($msg == "Password changed successfully!") ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
        <strong><?php echo htmlentities($msg); ?></strong>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php endif; ?>

    <h6 class="text-white mb-5 opacity-9">Update your password</h6>

    <div class="pl-lg-4">
        <!-- Current Password -->
        <div class="floating-label">
            <div class="input-group">
                <input type="password" name="currentpassword" class="form-control" required placeholder=" " id="currentpass">
                <div class="input-group-append">
                    <span class="input-group-text" onclick="togglePass('currentpass')">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <label>Current Password</label>
            </div>
        </div>

        <!-- New Password -->
        <div class="row">
            <div class="col-lg-6">
                <div class="floating-label">
                    <div class="input-group">
                        <input type="password" name="newpassword" class="form-control" required placeholder=" " id="newpass">
                        <div class="input-group-append">
                            <span class="input-group-text" onclick="togglePass('newpass')">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        <label>New Password</label>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="floating-label">
                    <div class="input-group">
                        <input type="password" name="confirmpassword" class="form-control" required placeholder=" " id="confirmpass">
                        <div class="input-group-append">
                            <span class="input-group-text" onclick="togglePass('confirmpass')">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        <label>Confirm New Password</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-5 border-white opacity-3">

    <div class="text-center">
        <button type="submit" name="submit" class="btn btn-primary btn-lg px-5">
            <i class="fas fa-shield-alt mr-2"></i> Update Password
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

<script>
function checkpass() {
    if (document.changepassword.newpassword.value != document.changepassword.confirmpassword.value) {
        alert('New Password and Confirm Password do not match!');
        document.changepassword.confirmpassword.focus();
        return false;
    }
    return true;
}

function togglePass(id) {
    var x = document.getElementById(id);
    var icon = x.nextElementSibling.querySelector('i');
    if (x.type === "password") {
        x.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        x.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
</body>
</html>