<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['pmsaid'] == 0)) {
    header('location:logout.php');
} else {
if(isset($_POST['submit'])) {
    $adminid = $_SESSION['pmsaid'];
    $aname = $_POST['adminname'];
    $mobno = $_POST['contactnumber'];
    $query = mysqli_query($con, "UPDATE tbladmin SET AdminName='$aname', MobileNumber='$mobno' WHERE ID='$adminid'");
    if ($query) {
        $msg = "Admin profile has been updated successfully.";
    } else {
        $msg = "Something Went Wrong. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile - Dharani PMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #e6f0ff 100%);
            color: #2d3748;
        }
        .main-content { 
            background: transparent; 
            padding-top: 20px !important;
        }

        /* Luxury Header */
        .page-header {
            background: linear-gradient(87deg, #667eea 0%, #764ba2 100%);
            padding: 90px 0 70px;
            text-align: center;
            color: white;
            border-radius: 0 0 50px 50px;
            box-shadow: 0 25px 60px rgba(102, 126, 234, 0.45);
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.35;
        }
        .page-header h1 {
            font-size: 2.9rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.8px;
        }
        .page-header p {
            font-size: 1.12rem;
            opacity: 0.94;
            margin-top: 12px;
        }

        /* Glass Profile Card */
        .profile-card {
            border-radius: 35px;
            border: none;
            box-shadow: 0 28px 70px rgba(0,0,0,0.16);
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            overflow: hidden;
            margin: 30px auto;
            max-width: 95%;
        }

        .card-body {
            padding: 70px 60px;
            text-align: center;
        }

        /* Avatar */
        .admin-avatar {
            width: 140px;
            height: 140px;
            background: linear-gradient(87deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 32px;
            box-shadow: 0 20px 50px rgba(102, 126, 234, 0.5);
            border: 6px solid white;
        }

        /* Form Styling */
        .form-control, .form-control:focus {
            border-radius: 24px;
            border: 3px solid #e2e8f0;
            padding: 18px 26px;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.4s ease;
            background: #fafbff;
            box-shadow: inset 0 3px 12px rgba(0,0,0,0.07);
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 8px rgba(102, 126, 234, 0.28), inset 0 3px 12px rgba(0,0,0,0.07);
            background: white;
        }
        .form-group label {
            font-size: 0.94rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Readonly Fields */
        .form-control[readonly] {
            background: #f1f5f9;
            color: #64748b;
            cursor: not-allowed;
        }

        /* Update Button */
        .btn-update {
            background: linear-gradient(87deg, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 20px 80px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 70px;
            box-shadow: 0 18px 45px rgba(102, 126, 234, 0.5);
            transition: all 0.5s ease;
            text-transform: uppercase;
            letter-spacing: 1.8px;
        }
        .btn-update:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 65px rgba(102, 126, 234, 0.6);
        }

        /* Alert */
        .alert-msg {
            font-size: 0.98rem;
            padding: 20px 30px;
            border-radius: 24px;
            margin: 30px auto;
            max-width: 600px;
            font-weight: 500;
            text-align: center;
        }
        .alert-success { background: #d4edda; color: #155724; border: 2.5px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 2.5px solid #f5c6cb; }

        /* Footer Brand */
        .brand-footer {
            background: linear-gradient(87deg, #1a1a2e, #16213e);
            color: white;
            padding: 40px;
            border-radius: 32px;
            text-align: center;
            margin: 110px auto 30px;
            max-width: 95%;
            box-shadow: 0 30px 70px rgba(0,0,0,0.45);
        }

        @media (max-width: 768px) {
            .page-header { padding: 70px 0 60px; }
            .page-header h1 { font-size: 2.3rem; }
            .card-body { padding: 50px 35px; }
            .admin-avatar { width: 110px; height: 110px; }
            .btn-update { padding: 18px 60px; }
        }
    </style>
</head>
<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- LUXURY HEADER -->
        <div class="page-header">
            <div class="container-fluid">
                <h1>Admin Profile</h1>
                <p>Manage your personal information</p>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-7">

                    <div class="profile-card">
                        <div class="card-body">

                            <div class="admin-avatar">
                                <i class="fas fa-user-md fa-4x text-white"></i>
                            </div>

                            <h3 style="background: linear-gradient(87deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; margin-bottom: 10px;">
                                Administrator Account
                            </h3>
                            <p class="text-muted" style="font-size: 0.96rem;">Update your details below</p>

                            <?php if($msg): ?>
                                <div class="alert-msg <?= (strpos($msg, 'successfully') !== false) ? 'alert-success' : 'alert-error'; ?>">
                                    <?= $msg; ?>
                                </div>
                            <?php endif; ?>

                            <?php
                            $adminid = $_SESSION['pmsaid'];
                            $ret = mysqli_query($con,"SELECT * FROM tbladmin WHERE ID='$adminid'");
                            while ($row = mysqli_fetch_array($ret)) {
                            ?>

                            <form method="post" class="mt-5">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Full Name</label>
                                            <input type="text" name="adminname" class="form-control" 
                                                   value="<?php echo htmlentities($row['AdminName']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Username</label>
                                            <input type="text" class="form-control" 
                                                   value="<?php echo htmlentities($row['UserName']); ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Contact Number</label>
                                            <input type="text" name="contactnumber" class="form-control" 
                                                   value="<?php echo htmlentities($row['MobileNumber']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="email" class="form-control" 
                                                   value="<?php echo htmlentities($row['Email']); ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <button type="submit" name="submit" class="btn btn-update">
                                        Update Profile
                                    </button>
                                </div>
                            </form>
                            <?php } ?>

                        </div>
                    </div>

                    <!-- BRAND FOOTER -->
                    <div class="brand-footer">
                        <h4 style="margin:0; font-weight:600; font-size:1.4rem;">DHARANI PHARMACY</h4>
                        <p style="margin:9px 0 0; font-size:1rem; opacity:0.9;">
                            Gampaha • Sri Lanka • Admin Portal • Secure & Modern
                        </p>
                    </div>

                </div>
            </div>
        </div>

        <?php include_once('includes/footer.php'); ?>
    </div>

    <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>
</body>
</html>
<?php } ?>