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
    <title>Pharmacist Sold Report - Dharani PMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7ff 0%, #e0e7ff 100%);
            color: #2d3748;
        }
        .main-content { background: transparent; }
        .page-header {
            background: linear-gradient(87deg, #667eea 0%, #764ba2 100%);
            padding: 70px 0 50px;
            text-align: center;
            color: white;
            position: relative;
            border-radius: 0 0 40px 40px;
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
        }
        .page-header h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -1px;
        }
        .page-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-top: 10px;
        }
        .card {
            border-radius: 24px;
            border: none;
            box-shadow: 0 15px 45px rgba(0,0,0,0.08);
            background: white;
            overflow: hidden;
            margin-top: -80px;
            position: relative;
            z-index: 10;
        }
        .card-body {
            padding: 40px;
        }
        .form-control, .form-control:focus {
            border-radius: 16px;
            border: 2px solid #e2e8f0;
            padding: 14px 18px;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: none;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        }
        .form-group label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        select.form-control {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23667eea' viewBox='0 0 16 16'%3e%3cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3e%3c/svg%3e");
            background-position: right 15px center;
            background-repeat: no-repeat;
            background-size: 12px;
        }
        .btn-submit {
            background: linear-gradient(87deg, #11998e, #38ef7d);
            border: none;
            color: white;
            padding: 14px 50px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 10px 25px rgba(56, 239, 125, 0.3);
            transition: all 0.4s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-submit:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(56, 239, 125, 0.4);
        }
        .icon-circle {
            width: 70px;
            height: 70px;
            background: linear-gradient(87deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        .text-gradient {
            background: linear-gradient(87deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }
        @media (max-width: 768px) {
            .page-header h1 { font-size: 2.2rem; }
            .card-body { padding: 25px; }
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
                <h1>Pharmacist Sold Report</h1>
                <p>Track individual pharmacist sales performance with precision</p>
            </div>
        </div>

        <!-- MAIN CARD -->
        <div class="container-fluid mt--9">
            <div class="row justify-content-center">
                <div class="col-xl-7">

                    <div class="card">
                        <div class="card-body text-center">
                            <div class="icon-circle">
                                <i class="fas fa-user-md fa-2x text-white"></i>
                            </div>
                            <h3 class="text-gradient mb-4">Generate Pharmacist Report</h3>
                            <p class="text-muted" style="font-size: 0.95rem;">Select date range and pharmacist to view detailed sales</p>

                            <form name="bwdatesreport" action="pharmacist-reports-details.php" method="post" class="mt-5">

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
                                    <label>Sold By Pharmacist</label>
                                    <select class="form-control" name="pharname" id="pharname" required>
                                        <option value="">Choose Pharmacist</option>
                                        <?php 
                                        $query = mysqli_query($con, "SELECT * FROM tblpharmacist ORDER BY FullName");
                                        while($row = mysqli_fetch_array($query)) { ?>
                                            <option value="<?php echo $row['ID']; ?>">
                                                <?php echo htmlentities($row['FullName']); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
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
                    <div class="text-center mt-5">
                        <div style="background: linear-gradient(87deg, #1a1a2e, #16213e); color: white; padding: 25px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.3);">
                            <h4 style="margin:0; font-weight:600;">DHARANI PHARMACY</h4>
                            <p style="margin:5px 0 0; font-size:0.9rem; opacity:0.9;">
                                Gampaha • Sri Lanka • Since 2025
                            </p>
                        </div>
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