<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// === SECURITY CHECK ===
if (!isset($_SESSION['cspid']) || empty($_SESSION['cspid'])) {
    header('Location: index.php');
    exit;
}

$custid = $_SESSION['cspid'];

// Get Customer Name
$ret = mysqli_query($con, "SELECT FullName FROM tblcustomerlogin WHERE ID='$custid'");
$row = mysqli_fetch_array($ret);
$customerName = $row['FullName'] ?? 'Dear Customer';

// Today's Date
$today = date('l, F j, Y'); // Example: Wednesday, November 06, 2025
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Dashboard - PMS</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    <style>
        .card-stats .card-body { padding: 1.5rem; }
        .icon-shape { width: 3rem; height: 3rem; }
    </style>
</head>
<body>

    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row">

                        <!-- CARD 1: Welcome Customer -->
                        <div class="col-xl-6 col-lg-6">
                            <div class="card card-stats mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">
                                                Welcome Back
                                            </h5>
                                            <span class="h2 font-weight-bold mb-0">
                                                <?php echo htmlspecialchars($customerName); ?>
                                            </span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-pink text-white rounded-circle shadow">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-muted text-sm">
                                        <i class="fas fa-heart text-red"></i> Have a great day!
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- CARD 2: Today's Date -->
                        <div class="col-xl-6 col-lg-6">
                            <div class="card card-stats mb-4">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">
                                                Today's Date
                                            </h5>
                                            <span class="h2 font-weight-bold mb-0">
                                                <?php echo $today; ?>
                                            </span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                                <i class="fas fa-calendar-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-muted text-sm">
                                        <i class="fas fa-clock"></i> <?php echo date('h:i A'); ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- You can add more cards later here -->
        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow">
                        <div class="card-body text-center py-5">
                            <h1 class="display-4">
                                <i class="fas fa-smile text-yellow"></i>
                            </h1>
                            <p class="h3">Your dashboard is ready!</p>
                            <!-- <p>Start browsing medicines or check your orders.</p> -->
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