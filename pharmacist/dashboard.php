<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (!isset($_SESSION['pmspid']) || empty($_SESSION['pmspid'])) {
    header('Location: logout.php');
    exit;
}
$phid = $_SESSION['pmspid'];

$pharmaName = "Pharmacist";
$todays_sale = $yesterdaysale = $tseven = $totalsale = 0;
$lastSaleTime = $lastSaleAmount = "No sale today";

$possibleTables = ['tblpharmacy', 'tblpharmacist', 'tblpharma', 'users'];
$nameQuery = null;
foreach ($possibleTables as $table) {
    $check = mysqli_query($con, "SHOW TABLES LIKE '$table'");
    if (mysqli_num_rows($check) > 0) {
        $nameQuery = mysqli_query($con, "SELECT FullName FROM `$table` WHERE ID = '$phid' LIMIT 1");
        if ($nameQuery && mysqli_num_rows($nameQuery) > 0) {
            $row = mysqli_fetch_assoc($nameQuery);
            $pharmaName = htmlspecialchars($row['FullName'] ?? 'Pharmacist');
            break;
        }
    }
}

$sql = "SELECT COALESCE(SUM(c.ProductQty * m.Priceperunit), 0) as total
        FROM tblcart c
        JOIN tblmedicine m ON m.ID = c.ProductId
        WHERE DATE(c.CartDate) = CURDATE()
        AND c.PharmacistId = '$phid'
        AND c.IsCheckOut = '1'";
$res = mysqli_query($con, $sql);
$todays_sale = ($res && $row = mysqli_fetch_assoc($res)) ? $row['total'] : 0;

$sql = "SELECT COALESCE(SUM(c.ProductQty * m.Priceperunit), 0) as total
        FROM tblcart c
        JOIN tblmedicine m ON m.ID = c.ProductId
        WHERE DATE(c.CartDate) = CURDATE() - INTERVAL 1 DAY
        AND c.PharmacistId = '$phid'
        AND c.IsCheckOut = '1'";
$res = mysqli_query($con, $sql);
$yesterdaysale = ($res && $row = mysqli_fetch_assoc($res)) ? $row['total'] : 0;

$sql = "SELECT COALESCE(SUM(c.ProductQty * m.Priceperunit), 0) as total
        FROM tblcart c
        JOIN tblmedicine m ON m.ID = c.ProductId
        WHERE c.CartDate >= DATE(NOW()) - INTERVAL 7 DAY
        AND c.PharmacistId = '$phid'
        AND c.IsCheckOut = '1'";
$res = mysqli_query($con, $sql);
$tseven = ($res && $row = mysqli_fetch_assoc($res)) ? $row['total'] : 0;

$sql = "SELECT COALESCE(SUM(c.ProductQty * m.Priceperunit), 0) as total
        FROM tblcart c
        JOIN tblmedicine m ON m.ID = c.ProductId
        WHERE c.PharmacistId = '$phid'
        AND c.IsCheckOut = '1'";
$res = mysqli_query($con, $sql);
$totalsale = ($res && $row = mysqli_fetch_assoc($res)) ? $row['total'] : 0;

$sql = "SELECT c.CartDate, SUM(c.ProductQty * m.Priceperunit) as amount
        FROM tblcart c
        JOIN tblmedicine m ON m.ID = c.ProductId
        WHERE DATE(c.CartDate) = CURDATE()
        AND c.PharmacistId = '$phid'
        AND c.IsCheckOut = '1'
        GROUP BY c.BillingId
        ORDER BY c.CartDate DESC
        LIMIT 1";
$res = mysqli_query($con, $sql);
if ($res && $row = mysqli_fetch_assoc($res)) {
    $lastSaleTime = date('h:i A', strtotime($row['CartDate']));
    $lastSaleAmount = "Rs. " . number_format($row['amount'], 2);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dharani PMS - Dashboard</title>
    
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
            border-radius: 0 0 3.5rem 3.5rem;
            overflow: hidden;
        }
        .header::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: url('https://images.unsplash.com/photo-1580281773044-0b5577c7a2b2?ixlib=rb-4.0.3&auto=format&fit=crop&q=80') center/cover;
            opacity: 0.25;
        }
        .welcome-card {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border-radius: 2rem;
            padding: 3rem;
            box-shadow: 0 30px 80px rgba(0,0,0,0.3);
            position: relative;
            overflow: hidden;
        }
        .welcome-card::before {
            content: ''; position: absolute; top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle, rgba(102,126,234,0.1) 0%, transparent 70%);
            animation: pulse 8s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .stat-card {
            background: white;
            border-radius: 1.8rem;
            padding: 2rem;
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }
        .stat-card:hover {
            transform: translateY(-15px) scale(1.03);
            box-shadow: 0 30px 70px rgba(0,0,0,0.25);
        }
        .stat-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 6px;
        }
        .card-today::before { background: linear-gradient(45deg, #f093fb, #f5576c); }
        .card-yesterday::before { background: linear-gradient(45deg, #4facfe, #00f2fe); }
        .card-7days::before { background: linear-gradient(45deg, #43e97b, #38f9d7); }
        .card-total::before { background: linear-gradient(45deg, #fa709a, #fee140); }

        .icon-circle {
            width: 70px; height: 70px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .last-sale-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 2rem;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 30px 80px rgba(102,126,234,0.4);
            position: relative;
            overflow: hidden;
        }
        .last-sale-card::before {
            content: ''; position: absolute; top: -100%; left: -50%;
            width: 200%; height: 300%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
            animation: shine 6s infinite;
        }
        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(30deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(30deg); }
        }
        .amount-display {
            font-size: 4rem;
            font-weight: 800;
            text-shadow: 0 10px 20px rgba(0,0,0,0.3);
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .time-info {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border-radius: 1.5rem;
            padding: 1rem 2rem;
            display: inline-block;
            border: 1px solid rgba(255,255,255,0.3);
        }
        .greeting {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        @media (max-width: 768px) {
            .stat-card { margin-bottom: 2rem; }
            .amount-display { font-size: 2.8rem; }
            .greeting { font-size: 2rem; }
        }
    </style>
</head>
<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- Header -->
        <div class="header pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <div class="header-body text-center text-white position-relative">
                    <!-- <h1 class="display-2 font-weight-bold greeting">
                        Welcome back, <?php echo $pharmaName; ?>!
                    </h1> -->
                    <p class="lead">Here's <?php echo $pharmaName; ?> sales performance today</p>
                    <div class="time-info mt-3">
                        <i class="fas fa-clock mr-2"></i>
                        Sri Lanka Time: <?php echo date('d M Y, h:i A'); ?> (UTC+5:30)
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt--9">
            <!-- Welcome Card -->
            <div class="row justify-content-center mb-5">
                <!-- <div class="col-xl-10">
                    <div class="welcome-card text-center">
                        <h2>Pharmacist Dashboard</h2>
                        <p class="text-muted">Real-time sales tracking • Secure • Fast • Beautiful</p>
                    </div>
                </div> -->
            </div>

            <!-- Stats Cards -->
            <div class="row">
                <div class="col-xl-3 col-lg-6">
                    <div class="card stat-card card-today">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-gradient-pink text-white">
                                <i class="fas fa-sun"></i>
                            </div>
                            <div class="ml-4">
                                <h5 class="text-muted text-uppercase mb-1">
                                    <a href="pharmacist-report-ds.php" class="text-muted">Today's Sale</a>
                                </h5>
                                <h2 class="font-weight-bold mb-0">Rs. <?php echo number_format($todays_sale, 2); ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card stat-card card-yesterday">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-gradient-info text-white">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div class="ml-4">
                                <h5 class="text-muted text-uppercase mb-1">
                                    <a href="pharmacist-report-ds.php" class="text-muted">Yesterday</a>
                                </h5>
                                <h2 class="font-weight-bold mb-0">Rs. <?php echo number_format($yesterdaysale, 2); ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card stat-card card-7days">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-gradient-success text-white">
                                <i class="fas fa-calendar-week"></i>
                            </div>
                            <div class="ml-4">
                                <h5 class="text-muted text-uppercase mb-1">
                                    <a href="pharmacist-report-ds.php" class="text-muted">Last 7 Days</a>
                                </h5>
                                <h2 class="font-weight-bold mb-0">Rs. <?php echo number_format($tseven, 2); ?></h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card stat-card card-total">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-gradient-warning text-white">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="ml-4">
                                <h5 class="text-muted text-uppercase mb-1">
                                    <a href="pharmacist-report-ds.php" class="text-muted">Total Career Sale</a>
                                </h5>
                                <h2 class="font-weight-bold mb-0">Rs. <?php echo number_format($totalsale, 2); ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Last Sale Spotlight -->
            <div class="row mt-5">
                <div class="col-xl-12">
                    <div class="last-sale-card">
                        <h3 class="mb-4">
                            <i class="fas fa-star mr-3"></i> Your Last Sale Today
                        </h3>
                        <?php if ($lastSaleAmount !== "No sale today"): ?>
                            <div class="amount-display"><?php echo $lastSaleAmount; ?></div>
                            <p class="mt-4">
                                <i class="fas fa-user-tie mr-2"></i> <strong><?php echo $pharmaName; ?></strong>
                            </p>
                            <p>
                                <i class="fas fa-clock mr-2"></i> <?php echo $lastSaleTime; ?>
                            </p>
                        <?php else: ?>
                            <h2>No sales yet today</h2>
                            <p class="mt-3">Your first sale will shine here!</p>
                        <?php endif; ?>
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