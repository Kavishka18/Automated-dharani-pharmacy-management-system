<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['pmspid'] == 0)) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pharmacy Management System - Sold Report</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <!-- Argon Dashboard -->
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f0f2f5; }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 280px;
            position: relative;
            border-radius: 0 0 2rem 2rem;
            overflow: hidden;
        }
        .header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('https://images.unsplash.com/photo-1559839915-09a43de83d4f?ixlib=rb-4.0.3&auto=format&fit=crop&q=80') center/cover;
            opacity: 0.15;
        }
        .card {
            border: none;
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.95);
        }
        .card-header {
            background: linear-gradient(120deg, #a8edea 0%, #fed6e3 100%);
            border-bottom: none;
            padding: 2rem;
        }
        .form-control {
            border-radius: 1rem;
            padding: 0.8rem 1rem;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            transform: translateY(-2px);
        }
        .input-group-text {
            border-radius: 1rem 0 0 1rem;
            background: #667eea;
            color: white;
            border: none;
        }
        .btn-submit {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 50px;
            padding: 0.8rem 2.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.4s ease;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .btn-submit:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.4);
        }
        .report-icon {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 1rem;
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        .date-info {
            background: #f8f9fa;
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-left: 5px solid #667eea;
        }
        .text-gradient {
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
        }
        @media (max-width: 768px) {
            .header { min-height: 200px; }
            .card-header { padding: 1.5rem; }
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
                <div class="header-body">
                    <div class="row align-items-center text-center text-md-left">
                        <div class="col">
                            <h1 class="display-3 text-white font-weight-bold mb-2">
                                <i class="fas fa-chart-line mr-3"></i> Sold Report
                            </h1>
                            <p class="text-white opacity-8">Generate detailed sales reports between specific dates</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page content -->
        <div class="container-fluid mt--8">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10">
                    <div class="card">
                        <div class="card-header text-center">
                            <div class="report-icon">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <h2 class="text-gradient mb-2">Sales Report Generator</h2>
                            <p class="text-muted">Select date range to view medicines sold</p>
                        </div>

                        <div class="card-body p-5">
                            <?php if($msg ?? '') { ?>
                                <div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <i class="fas fa-info-circle"></i> <?php echo $msg; ?>
                                    <button type="button" class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                </div>
                            <?php } ?>

                            <div class="date-info">
                                <small class="text-muted">
                                    <i class="fas fa-clock mr-2"></i>
                                    Current Time (Sri Lanka): 
                                    <strong><?php echo date('d M Y, h:i A'); ?> (UTC+5:30)</strong>
                                </small>
                            </div>

                            <form name="bwdatesreport" action="pharmacist-reports-details.php" method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">
                                                <i class="fas fa-calendar-alt mr-2"></i> From Date
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar-day"></i>
                                                    </span>
                                                </div>
                                                <input type="date" name="fromdate" class="form-control" required 
                                                       value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                            <small class="text-muted">Start date for report</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">
                                                <i class="fas fa-calendar-alt mr-2"></i> To Date
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="fas fa-calendar-check"></i>
                                                    </span>
                                                </div>
                                                <input type="date" name="todate" class="form-control" required
                                                       value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>">
                                            </div>
                                            <small class="text-muted">End date for report</small>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-5">

                                <div class="text-center">
                                    <button type="submit" name="submit" class="btn btn-submit text-white">
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        Generate Report
                                    </button>
                                </div>

                                <div class="text-center mt-4">
                                    <small class="text-muted">
                                        <i class="fas fa-shield-alt text-success"></i>
                                        Your data is secure • Reports generated in real-time
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Quick Tips Card -->
                    <div class="card mt-4 bg-light border-0">
                        <div class="card-body text-center py-4">
                            <h5><i class="fas fa-lightbulb text-warning mr-2"></i> Pro Tips</h5>
                            <div class="row text-left mt-3">
                                <div class="col-md-4">
                                    <p class="mb-2"><i class="fas fa-check text-success"></i> Use monthly ranges for best insights</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-2"><i class="fas fa-check text-success"></i> Export reports for accounting</p>
                                </div>
                                <div class="col-md-4">
                                    <p class="mb-2"><i class="fas fa-check text-success"></i> Compare with stock levels</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>

    <script>
        // Auto-fill today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.querySelector('input[name="fromdate"]').value = today;
            document.querySelector('input[name="todate"]').value = today;
        });

        // Prevent future dates
        document.querySelectorAll('input[type="date"]').forEach(input => {
            input.max = new Date().toISOString().split('T')[0];
        });
    </script>
</body>
</html>
<?php } ?>