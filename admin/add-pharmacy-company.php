<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['pmsaid'] == 0)) {
    header('location:logout.php');
} else {
if(isset($_POST['submit'])) {
    $compname=$_POST['compname'];
    $query=mysqli_query($con, "insert into tblcompany(CompanyName) value('$compname')");
    if ($query) {
        echo "<script>alert('Company has been added.');</script>";
        echo "<script>window.location.href ='manage-company.php'</script>";
    } else {
        $msg="Something Went Wrong. Please try again";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Company - Dharani PMS</title>
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
            background: linear-gradient(87deg, #667eea 0%, #764ba2 100%);
            padding: 80px 0 60px;
            text-align: center;
            color: white;
            border-radius: 0 0 40px 40px;
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.07"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
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
            border-radius: 28px;
            border: none;
            box-shadow: 0 20px 55px rgba(0,0,0,0.12);
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(14px);
            overflow: hidden;
            margin: 20px auto;
            max-width: 95%;
        }

        .card-body {
            padding: 60px 50px;
            text-align: center;
        }

        /* Form Styling */
        .form-control, .form-control:focus {
            border-radius: 20px;
            border: 2.5px solid #e2e8f0;
            padding: 16px 22px;
            font-size: 0.98rem;
            font-weight: 500;
            transition: all 0.3s ease;
            background: #fafbff;
            text-align: center;
            box-shadow: inset 0 2px 8px rgba(0,0,0,0.05);
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 6px rgba(102, 126, 234, 0.22), inset 0 2px 8px rgba(0,0,0,0.05);
            background: white;
        }
        .form-group label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        /* Submit Button */
        .btn-add {
            background: linear-gradient(87deg, #667eea, #764ba2);
            border: none;
            color: white;
            padding: 16px 60px;
            font-size: 1.02rem;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 14px 35px rgba(102, 126, 234, 0.4);
            transition: all 0.4s ease;
            text-transform: uppercase;
            letter-spacing: 1.4px;
        }
        .btn-add:hover {
            transform: translateY(-6px);
            box-shadow: 0 22px 50px rgba(102, 126, 234, 0.5);
        }

        /* Icon Circle */
        .icon-circle {
            width: 78px;
            height: 78px;
            background: linear-gradient(87deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
            box-shadow: 0 15px 38px rgba(102, 126, 234, 0.38);
        }

        /* Alert */
        .alert-msg {
            font-size: 0.96rem;
            padding: 16px 26px;
            border-radius: 20px;
            margin: 20px auto;
            max-width: 500px;
            font-weight: 500;
        }
        .alert-error { background: #f8d7da; color: #721c24; border: 1.5px solid #f5c6cb; }

        /* Footer Brand */
        .brand-footer {
            background: linear-gradient(87deg, #1a1a2e, #16213e);
            color: white;
            padding: 30px;
            border-radius: 24px;
            text-align: center;
            margin: 80px auto 30px;
            max-width: 95%;
            box-shadow: 0 20px 45px rgba(0,0,0,0.32);
        }

        @media (max-width: 768px) {
            .page-header { padding: 60px 0 50px; }
            .page-header h1 { font-size: 2.1rem; }
            .card-body { padding: 40px 25px; }
            .form-control { font-size: 0.94rem; }
            .btn-add { padding: 14px 50px; }
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
                <h1>Add New Company</h1>
                <p>Register pharmaceutical companies instantly</p>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-6">

                    <div class="content-card">
                        <div class="card-body">

                            <div class="icon-circle">
                                <i class="fas fa-building fa-2x text-white"></i>
                            </div>

                            <h3 style="background: linear-gradient(87deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; margin-bottom: 8px;">
                                Register Company
                            </h3>
                            <p class="text-muted" style="font-size: 0.94rem;">Enter company name to add</p>

                            <?php if($msg): ?>
                                <div class="alert-msg alert-error">
                                    <?= $msg; ?>
                                </div>
                            <?php endif; ?>

                            <form method="post" class="mt-5">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" name="compname" class="form-control" 
                                           placeholder="e.g. GlaxoSmithKline, Pfizer, Cipla" required>
                                </div>

                                <div class="mt-5">
                                    <button type="submit" name="submit" class="btn btn-add">
                                        Add Company
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>

                    <!-- BRAND FOOTER -->
                    <div class="brand-footer">
                        <h4 style="margin:0; font-weight:600; font-size:1.2rem;">DHARANI PHARMACY</h4>
                        <p style="margin:6px 0 0; font-size:0.92rem; opacity:0.9;">
                            Gampaha • Sri Lanka • Trusted by 100+ Companies
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