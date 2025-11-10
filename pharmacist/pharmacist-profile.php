<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['pmspid'] == 0)) {
    header('location:logout.php');
} else {
if (isset($_POST['submit'])) {
    $fname = $_POST['fullname'];
    $mobno = $_POST['mobnumber'];
    $email = $_POST['email'];
    $uname = $_POST['username'];
    $gender = $_POST['gender'];
    $pid = $_SESSION['pmspid'];
    $query = mysqli_query($con, "UPDATE tblpharmacist SET FullName='$fname', MobileNumber='$mobno', Email='$email', UserName='$uname', Gender='$gender' WHERE ID='$pid'");
    if ($query) {
        $msg = "Pharmacist profile has been updated successfully!";
    } else {
        $msg = "Something went wrong. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dharani Pharmacy - My Profile</title>
    
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
        .profile-avatar {
            width: 150px; height: 150px;
            border-radius: 50%;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto -75px;
            position: relative;
            z-index: 10;
            box-shadow: 0 20px 40px rgba(102,126,234,0.4);
        }
        .profile-avatar i {
            font-size: 4rem; color: white;
        }
        .form-control {
            border-radius: 1.2rem;
            padding: 0.9rem 1.3rem;
            border: 2px solid #e2e8f0;
            transition: all 0.4s ease;
            font-size: 1rem;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.3rem rgba(102,126,234,0.25);
            transform: translateY(-4px);
        }
        .input-group-text {
            border-radius: 1.2rem 0 0 1.2rem;
            background: #667eea;
            color: white;
            border: none;
        }
        .btn-update {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 50px;
            padding: 14px 50px;
            font-weight: 700;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            box-shadow: 0 15px 35px rgba(102,126,234,0.4);
            transition: all 0.5s ease;
        }
        .btn-update:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(102,126,234,0.6);
        }
        .gender-radio {
            background: #f8f9ff;
            padding: 1.5rem;
            border-radius: 1.5rem;
            border: 2px solid #e2e8f0;
        }
        .custom-radio .custom-control-label::before {
            border-radius: 50%;
            border: 2px solid #667eea;
        }
        .custom-radio .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #667eea;
        }
        .readonly-field {
            background-color: #f1f3f7 !important;
            cursor: not-allowed;
            color: #6c757d;
        }
        .alert-success {
            background: linear-gradient(45deg, #56ab2f, #a8e6cf);
            color: white;
            border: none;
            border-radius: 1.5rem;
            padding: 1.5rem;
            text-align: center;
            font-weight: 600;
        }
        .alert-danger {
            background: linear-gradient(45deg, #e74c3c, #f8b5b5);
            color: white;
            border: none;
            border-radius: 1.5rem;
            padding: 1.5rem;
            text-align: center;
            font-weight: 600;
        }
        .time-info {
            background: rgba(102,126,234,0.1);
            border-radius: 1.5rem;
            padding: 1.2rem;
            border-left: 6px solid #667eea;
            margin: 20px 0;
        }
        @media (max-width: 768px) {
            .header { min-height: 250px; }
            .card { margin: 1rem; }
            .profile-avatar { width: 120px; height: 120px; }
            .profile-avatar i { font-size: 3rem; }
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
                        <i class="fas fa-user-cog mr-4"></i>
                        My Profile
                    </h1>
                    <p class="lead">Keep your information up to date</p>
                </div> -->
            </div>
        </div>

        <div class="container-fluid mt--9">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header text-center bg-transparent pt-7">
                            <div class="profile-avatar">
                                <i class="fas fa-user-md"></i>
                            </div>
                        </div>

                        <div class="card-body pt-2">
                            <div class="text-center mb-4">
                                <h2 class="mt-5">
                                    <?php
                                    $pid = $_SESSION['pmspid'];
                                    $ret = mysqli_query($con, "SELECT FullName FROM tblpharmacist WHERE ID='$pid'");
                                    $row = mysqli_fetch_array($ret);
                                    echo htmlspecialchars($row['FullName']);
                                    ?>
                                </h2>
                                <p class="text-muted">Pharmacist • Dharani Pharmacy</p>
                            </div>

                            <div class="time-info text-center">
                                <small>
                                    <i class="fas fa-clock mr-2"></i>
                                    Sri Lanka Time: <?php echo date('d M Y, h:i A'); ?> (UTC+5:30)
                                </small>
                            </div>

                            <?php if($msg): ?>
                                <div class="alert <?php echo ($query) ? 'alert-success' : 'alert-danger'; ?> mt-4">
                                    <i class="fas <?php echo ($query) ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?> mr-2"></i>
                                    <?php echo $msg; ?>
                                </div>
                            <?php endif; ?>

                            <form method="post" class="mt-5">
                                <?php
                                $ret = mysqli_query($con, "SELECT * FROM tblpharmacist WHERE ID='$pid'");
                                while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">
                                                <i class="fas fa-user mr-2"></i> Full Name
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                </div>
                                                <input type="text" name="fullname" class="form-control" 
                                                       value="<?php echo $row['FullName'];?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">
                                                <i class="fas fa-phone mr-2"></i> Mobile Number
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                                                </div>
                                                <input type="text" name="mobnumber" class="form-control" 
                                                       value="<?php echo $row['MobileNumber'];?>" required 
                                                       maxlength="10" pattern="[0-9]+">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">
                                                <i class="fas fa-envelope mr-2"></i> Email Address
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                </div>
                                                <input type="email" name="email" class="form-control" 
                                                       value="<?php echo $row['Email'];?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">
                                                <i class="fas fa-user-tag mr-2"></i> Username
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                                </div>
                                                <input type="text" name="username" class="form-control readonly-field" 
                                                       value="<?php echo $row['UserName'];?>" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">
                                                <i class="fas fa-calendar-check mr-2"></i> Joining Date
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                                </div>
                                                <input type="text" class="form-control readonly-field" 
                                                       value="<?php echo date('d M Y', strtotime($row['JoingDate'])); ?>" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">
                                                <i class="fas fa-venus-mars mr-2"></i> Gender
                                            </label>
                                            <div class="gender-radio">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="male" name="gender" class="custom-control-input" 
                                                           value="Male" <?php echo ($row['Gender'] == 'Male') ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label font-weight-bold" for="male">
                                                        Male
                                                    </label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="female" name="gender" class="custom-control-input" 
                                                           value="Female" <?php echo ($row['Gender'] == 'Female') ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label font-weight-bold" for="female">
                                                        Female
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php } ?>

                                <div class="text-center mt-5">
                                    <button type="submit" name="submit" class="btn btn-update text-white">
                                        <i class="fas fa-save mr-3"></i>
                                        Update Profile
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
<?php } ?>