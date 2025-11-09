<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['pmsaid'] == 0)) {
    header('location:logout.php');
} else {
if(isset($_POST['submit'])) {
    $compname=$_POST['compname'];
    $eid=$_GET['editid'];
    $query=mysqli_query($con, "update tblcompany set CompanyName = '$compname' where ID='$eid'");
    if ($query) {
        $msg="Company has been updated successfully.";
    } else {
        $msg="Something Went Wrong. Please try again";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Company - Dharani PMS</title>
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

        /* Modern Header */
        .page-header {
            background: linear-gradient(87deg, #a18cd1 0%, #fbc2eb 100%);
            padding: 80px 0 60px;
            text-align: center;
            color: white;
            border-radius: 0 0 40px 40px;
            box-shadow: 0 20px 40px rgba(161, 140, 209, 0.35);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.08"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        .page-header h1 {
            font-size: 2.6rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .page-header p {
            font-size: 1.05rem;
            opacity: 0.92;
            margin-top: 8px;
        }

        /* Glass Card */
        .content-card {
            border-radius: 30px;
            border: none;
            box-shadow: 0 22px 60px rgba(0,0,0,0.14);
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(16px);
            overflow: hidden;
            margin: 20px auto;
            max-width: 95%;
        }

        .card-body {
            padding: 65px 55px;
            text-align: center;
        }

        /* Form Styling */
        .form-control, .form-control:focus {
            border-radius: 22px;
            border: 2.8px solid #e2e8f0;
            padding: 18px 24px;
            font-size: 1rem;
            font-weight: 500;
            transition: all 0.35s ease;
            background: #fafbff;
            text-align: center;
            box-shadow: inset 0 3px 10px rgba(0,0,0,0.06);
        }
        .form-control:focus {
            border-color: #a18cd1;
            box-shadow: 0 0 0 7px rgba(161, 140, 209, 0.25), inset 0 3px 10px rgba(0,0,0,0.06);
            background: white;
        }
        .form-group label {
            font-size: 0.92rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.9px;
        }

        /* Submit Button */
        .btn-update {
            background: linear-gradient(87deg, #a18cd1, #fbc2eb);
            border: none;
            color: white;
            padding: 18px 70px;
            font-size: 1.05rem;
            font-weight: 600;
            border-radius: 60px;
            box-shadow: 0 16px 38px rgba(161, 140, 209, 0.45);
            transition: all 0.45s ease;
            text-transform: uppercase;
            letter-spacing: 1.6px;
        }
        .btn-update:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 55px rgba(161, 140, 209, 0.55);
        }

        /* Icon Circle */
        .icon-circle {
            width: 82px;
            height: 82px;
            background: linear-gradient(87deg, #a18cd1, #fbc2eb);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            box-shadow: 0 16px 40px rgba(161, 140, 209, 0.42);
        }

        /* Alert */
        .alert-msg {
            font-size: 0.97rem;
            padding: 18px 28px;
            border-radius: 22px;
            margin: 25px auto;
            max-width: 520px;
            font-weight: 500;
            text-align: center;
        }
        .alert-success { background: #d4edda; color: #155724; border: 2px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 2px solid #f5c6cb; }

        /* Footer Brand */
        .brand-footer {
            background: linear-gradient(87deg, #1a1a2e, #16213e);
            color: white;
            padding: 35px;
            border-radius: 28px;
            text-align: center;
            margin: 90px auto 30px;
            max-width: 95%;
            box-shadow: 0 25px 55px rgba(0,0,0,0.38);
        }

        @media (max-width: 768px) {
            .page-header { padding: 60px 0 50px; }
            .page-header h1 { font-size: 2.1rem; }
            .card-body { padding: 45px 30px; }
            .form-control { font-size: 0.96rem; }
            .btn-update { padding: 16px 55px; }
        }
    </style>
</head>
<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- MODERN HEADER -->
        <div class="page-header">
            <div class="container-fluid">
                <h1>Edit Company</h1>
                <p>Update pharmaceutical company name</p>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-6">

                    <div class="content-card">
                        <div class="card-body">

                            <div class="icon-circle">
                                <i class="fas fa-edit fa-2x text-white"></i>
                            </div>

                            <h3 style="background: linear-gradient(87deg, #a18cd1, #fbc2eb); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; margin-bottom: 8px;">
                                Update Company Name
                            </h3>
                            <p class="text-muted" style="font-size: 0.95rem;">Make changes and save</p>

                            <?php if($msg): ?>
                                <div class="alert-msg <?= (strpos($msg, 'successfully') !== false) ? 'alert-success' : 'alert-error'; ?>">
                                    <?= $msg; ?>
                                </div>
                            <?php endif; ?>

                            <?php
                            $cid=$_GET['editid'];
                            $ret=mysqli_query($con,"select * from tblcompany where ID='$cid'");
                            while ($row=mysqli_fetch_array($ret)) {
                            ?>

                            <form method="post" class="mt-5">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" name="compname" class="form-control" 
                                           value="<?php echo htmlentities($row['CompanyName']); ?>" 
                                           placeholder="e.g. Pfizer, GSK, Cipla" required>
                                </div>

                                <div class="mt-5">
                                    <button type="submit" name="submit" class="btn btn-update">
                                        Update Company
                                    </button>
                                </div>
                            </form>
                            <?php } ?>

                        </div>
                    </div>

                    <!-- BRAND FOOTER -->
                    <div class="brand-footer">
                        <h4 style="margin:0; font-weight:600; font-size:1.3rem;">DHARANI PHARMACY</h4>
                        <p style="margin:8px 0 0; font-size:0.96rem; opacity:0.9;">
                            Gampaha • Sri Lanka • Managing 200+ Global Companies
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