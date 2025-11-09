<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['pmsaid'] == 0)) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report - Dharani PMS</title>
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
        .main-content { background: transparent; }
        
        /* Modern Header */
        .page-header {
            background: linear-gradient(87deg, #5e72e4 0%, #825ee4 100%);
            padding: 70px 0 50px;
            text-align: center;
            color: white;
            border-radius: 0 0 35px 35px;
            box-shadow: 0 20px 40px rgba(94, 114, 228, 0.3);
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
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
        .card {
            border-radius: 26px;
            border: none;
            box-shadow: 0 18px 50px rgba(0,0,0,0.1);
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(12px);
            margin-top: -75px;
            position: relative;
            z-index: 10;
            overflow: hidden;
        }
        .card-body {
            padding: 45px 40px;
        }

        /* Form Styling */
        .form-control, .form-control:focus {
            border-radius: 16px;
            border: 2px solid #e2e8f0;
            padding: 13px 18px;
            font-size: 0.94rem;
            font-weight: 500;
            transition: all 0.3s ease;
            background: #fafbff;
        }
        .form-control:focus {
            border-color: #5e72e4;
            box-shadow: 0 0 0 4px rgba(94, 114, 228, 0.18);
            background: white;
        }
        .form-group label {
            font-size: 0.88rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        /* Radio Buttons - Modern Cards */
        .radio-group {
            display: flex;
            gap: 16px;
            margin-top: 10px;
        }
        .radio-card {
            flex: 1;
            padding: 16px;
            border-radius: 18px;
            background: #f8f9fe;
            border: 2px solid #e2e8f0;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.94rem;
            font-weight: 600;
        }
        .radio-card input[type="radio"] {
            display: none;
        }
        .radio-card input[type="radio"]:checked + label {
            background: linear-gradient(87deg, #5e72e4, #825ee4);
            color: white;
            border-color: #5e72e4;
        }
        .radio-card:hover {
            border-color: #5e72e4;
            transform: translateY(-3px);
        }

        /* Submit Button */
        .btn-submit {
            background: linear-gradient(87deg, #11cdef, #1171ef);
            border: none;
            color: white;
            padding: 14px 48px;
            font-size: 0.98rem;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 12px 28px rgba(17, 113, 239, 0.35);
            transition: all 0.4s ease;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }
        .btn-submit:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 38px rgba(17, 113, 239, 0.45);
        }

        /* Icon Circle */
        .icon-circle {
            width: 68px;
            height: 68px;
            background: linear-gradient(87deg, #5e72e4, #825ee4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 22px;
            box-shadow: 0 12px 30px rgba(94, 114, 228, 0.3);
        }

        /* Footer Brand */
        .brand-footer {
            background: linear-gradient(87deg, #1a1a2e, #16213e);
            color: white;
            padding: 22px;
            border-radius: 20px;
            text-align: center;
            margin-top: 50px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
        }

        @media (max-width: 768px) {
            .page-header h1 { font-size: 2.1rem; }
            .card-body { padding: 30px 25px; }
            .radio-group { flex-direction: column; }
        }
    </style>
</head>
<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- MODERN PAGE HEADER -->
        <div class="page-header">
            <div class="container-fluid">
                <h1>Sales Report</h1>
                <p>Analyze pharmacy revenue • Month-wise or Year-wise</p>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="container-fluid mt--9">
            <div class="row justify-content-center">
                <div class="col-xl-7">

                    <div class="card">
                        <div class="card-body text-center">

                            <div class="icon-circle">
                                <i class="fas fa-chart-bar fa-2x text-white"></i>
                            </div>

                            <h3 style="background: linear-gradient(87deg, #5e72e4, #825ee4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; margin-bottom: 8px;">
                                Generate Sales Report
                            </h3>
                            <p class="text-muted" style="font-size: 0.94rem;">Select date range and report type</p>

                            <form name="bwdatesreport" action="sales-reports-detailed.php" method="post" class="mt-5">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>From Date</label>
                                            <input type="date" name="fromdate" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>To Date</label>
                                            <input type="date" name="todate" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Report Type</label>
                                    <div class="radio-group">
                                        <div class="radio-card">
                                            <input type="radio" name="requesttype" value="mtwise" checked id="mtwise">
                                            <label for="mtwise">Month Wise</label>
                                        </div>
                                        <div class="radio-card">
                                            <input type="radio" name="requesttype" value="yrwise" id="yrwise">
                                            <label for="yrwise">Year Wise</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-5">
                                    <button type="submit" name="submit" class="btn btn-submit">
                                        Generate Report
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                    <!-- BRAND FOOTER -->
                    <div class="brand-footer">
                        <h4 style="margin:0; font-weight:600; font-size:1.1rem;">DHARANI PHARMACY</h4>
                        <p style="margin:4px 0 0; font-size:0.88rem; opacity:0.9;">
                            Gampaha • Sri Lanka • Next-Gen Pharmacy System
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