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
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    }
    .form-control {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        border-radius: 14px;
        padding: 16px 20px;
        color: white;
        font-size: 15px;
        transition: all 0.3s ease;
    }
    .form-control::placeholder { color: rgba(255,255,255,0.7); }
    .form-control:focus {
        background: rgba(255, 255, 255, 0.3);
        box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.25);
        color: white;
    }
    .btn-primary {
        background: linear-gradient(45deg, #4facfe, #00f2fe);
        border: none;
        border-radius: 50px;
        padding: 14px 48px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        font-size: 14px;
        transition: all 0.4s ease;
        box-shadow: 0 10px 25px rgba(79, 172, 254, 0.5);
    }
    .btn-primary:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 35px rgba(79, 172, 254, 0.6);
    }
    .alert {
        border-radius: 16px;
        padding: 16px 24px;
        font-weight: 500;
        backdrop-filter: blur(10px);
        border: none;
        margin-bottom: 25px;
    }
    .alert-success { background: rgba(40, 167, 69, 0.3); color: #fff; }
    .alert-danger { background: rgba(220, 53, 69, 0.3); color: #fff; }
    .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(255,255,255,0.2);
        padding: 1.5rem 2rem;
    }
    h3.mb-0 {
        color: white;
        font-weight: 700;
        font-size: 1.8rem;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }
    .floating-label {
        position: relative;
        margin-bottom: 1.8rem;
    }
    .floating-label input {
        padding-top: 26px;
    }
    .floating-label label {
        position: absolute;
        top: 20px;
        left: 20px;
        color: rgba(255,255,255,0.8);
        font-size: 14px;
        transition: all 0.3s ease;
        pointer-events: none;
    }
    .floating-label input:focus ~ label,
    .floating-label input:not(:placeholder-shown) ~ label {
        top: 8px;
        font-size: 11px;
        color: #00f2fe;
        font-weight: 600;
    }
    .readonly-field {
        background: rgba(255,255,255,0.12) !important;
        color: #a0e7ff !important;
        cursor: not-allowed;
    }
</style>
</head>
<body class="">
<?php include_once('includes/navbar.php'); ?>
<div class="main-content">
<?php include_once('includes/sidebar.php'); ?>

<!-- Simple Clean Header -->
<div class="header pb-8 pt-6" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-12 text-center">
                    <h3 class="mb-0 text-white">My Profile</h3>
                    <p class="text-white opacity-8">Update your personal information</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="container-fluid mt--7 pb-6">
<div class="row justify-content-center">
<div class="col-xl-10 col-lg-11">
<div class="card glass-card">
<div class="card-header">
    <h3 class="mb-0">Edit Profile</h3>
</div>
<div class="card-body p-5">

<form method="post">
    <?php if($msg): ?>
    <div class="alert <?php echo strpos($msg, 'successfully') !== false ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
        <strong><?php echo htmlentities($msg); ?></strong>
        <button type="button" class="close" data-dismiss="alert">×</button>
    </div>
    <?php endif; ?>

    <?php
    $custid = $_SESSION['cspid'];
    $ret = mysqli_query($con, "SELECT * FROM tblcustomerlogin WHERE ID='$custid'");
    while ($row = mysqli_fetch_array($ret)) {
    ?>
    <div class="pl-lg-4">
        <div class="row">
            <div class="col-lg-6">
                <div class="floating-label">
                    <input type="text" name="fullname" class="form-control" value="<?php echo $row['FullName']; ?>" required placeholder=" ">
                    <label>Full Name</label>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="floating-label">
                    <input type="email" name="email" class="form-control" value="<?php echo $row['Email']; ?>" required placeholder=" ">
                    <label>Email Address</label>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="floating-label">
                    <input type="text" name="mobnumber" class="form-control" value="<?php echo $row['MobileNumber']; ?>" maxlength="10" pattern="[0-9]{10}" required placeholder=" ">
                    <label>Mobile Number</label>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="floating-label">
                    <input type="text" class="form-control readonly-field" value="<?php echo date('d M Y', strtotime($row['RegDate'])); ?>" readonly placeholder=" ">
                    <label>Registration Date</label>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-5 border-white opacity-3">

    <div class="text-center">
        <button type="submit" name="submit" class="btn btn-primary btn-lg px-5">
            <i class="fas fa-user-edit mr-2"></i> Update Profile
        </button>
    </div>
    <?php } ?>
</form>

</div>
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