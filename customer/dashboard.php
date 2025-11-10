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
$today = date('l, F j, Y');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customer Dashboard - PMS</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
<link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
<link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        color: white;
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        transition: all 0.4s ease;
    }
    .glass-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
    }
    .icon-shape {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    .welcome-card {
        background: linear-gradient(135deg, #ff6bd6, #ff8c8c);
    }
    .date-card {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
    }
    .hero-section {
        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6)), url('../assets/img/theme/dashboard-bg.jpg');
        background-size: cover;
        background-position: center;
        border-radius: 30px;
        padding: 4rem 2rem;
        text-align: center;
        margin: 2rem 0;
        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
    }
    .display-4 {
        font-weight: 800;
        font-size: 4.5rem;
        background: linear-gradient(45deg, #ffd700, #ff8c00);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 5px 20px rgba(0,0,0,0.3);
    }
    .pulse {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    .header {
        background: transparent !important;
        padding-top: 6rem !important;
    }
    .card-stats {
        border-radius: 24px;
        overflow: hidden;
    }
    .card-title {
        font-weight: 600;
        letter-spacing: 2px;
        font-size: 0.9rem;
    }
    .h2 {
        font-weight: 800;
        font-size: 2.2rem;
        letter-spacing: 1px;
    }
    .text-sm {
        font-size: 1rem;
        opacity: 0.9;
    }
</style>
</head>
<body class="">
<?php include_once('includes/navbar.php'); ?>
<div class="main-content">
<?php include_once('includes/sidebar.php'); ?>

<!-- Premium Header Area -->
<div class="header pt-8">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row">

                <!-- CARD 1: Welcome Customer -->
                <div class="col-xl-6 col-lg-6 mb-4">
                    <div class="card card-stats glass-card welcome-card">
                        <div class="card-body p-5">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="card-title text-white mb-2">
                                        Welcome Back
                                    </h5>
                                    <div class="h2 font-weight-bold mb-0 text-white">
                                        <?php echo htmlspecialchars($customerName); ?>!
                                    </div>
                                    <p class="mt-3 mb-0 text-white text-sm">
                                        <i class="fas fa-heart mr-2"></i> Have an amazing day ahead!
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <div class="icon-shape bg-white text-pink shadow-lg pulse">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: Today's Date & Time -->
                <div class="col-xl-6 col-lg-6 mb-4">
                    <div class="card card-stats glass-card date-card">
                        <div class="card-body p-5">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="card-title text-white mb-2">
                                        Today's Date
                                    </h5>
                                    <div class="h2 font-weight-bold mb-0 text-white">
                                        <?php echo $today; ?>
                                    </div>
                                    <p class="mt-3 mb-0 text-white text-sm">
                                        <i class="fas fa-clock mr-2"></i> <?php echo date('h:i A'); ?> (Sri Lanka Time)
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <div class="icon-shape bg-white text-info shadow-lg pulse">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Hero Welcome Section -->
<div class="container-fluid mt--6">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="hero-section glass-card">
                <h1 class="display-4 mb-3">
                    <i class="fas fa-crown text-yellow"></i>
                </h1>
                <p class="display-4 mb-3">Welcome to Your Dashboard!</p>
                <p class="h4 text-white opacity-9">Everything is ready for you</p>
                <div class="mt-4">
                    <i class="fas fa-star text-yellow" style="font-size: 2rem;"></i>
                    <i class="fas fa-star text-yellow" style="font-size: 2.5rem;"></i>
                    <i class="fas fa-star text-yellow" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Extra Space -->
<div class="container-fluid mt-5 pb-5">
    <div class="row">
        <div class="col-xl-12 text-center">
            <p class="text-white opacity-6">Start Intrect with pharmacy</p>
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