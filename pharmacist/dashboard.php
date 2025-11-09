<?php
session_start();
error_reporting(0); // Change to E_ALL for debugging
include('includes/dbconnection.php');

// === SECURITY CHECK ===
if (!isset($_SESSION['pmspid']) || empty($_SESSION['pmspid'])) {
    header('Location: logout.php');
    exit;
}

$phid = $_SESSION['pmspid'];

// === DEFAULTS ===
$pharmaName = "Pharmacist";
$todays_sale = $yesterdaysale = $tseven = $totalsale = 0;
$lastSaleTime = $lastSaleAmount = "No sale today";

// === AUTO-DETECT PHARMACIST TABLE ===
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

// === TODAY'S SALE ===
$sql = "SELECT COALESCE(SUM(c.ProductQty * m.Priceperunit), 0) as total
        FROM tblcart c 
        JOIN tblmedicine m ON m.ID = c.ProductId 
        WHERE DATE(c.CartDate) = CURDATE() 
          AND c.PharmacistId = '$phid' 
          AND c.IsCheckOut = '1'";
$res = mysqli_query($con, $sql);
$todays_sale = ($res && $row = mysqli_fetch_assoc($res)) ? $row['total'] : 0;

// === YESTERDAY'S SALE ===
$sql = "SELECT COALESCE(SUM(c.ProductQty * m.Priceperunit), 0) as total
        FROM tblcart c 
        JOIN tblmedicine m ON m.ID = c.ProductId 
        WHERE DATE(c.CartDate) = CURDATE() - INTERVAL 1 DAY 
          AND c.PharmacistId = '$phid' 
          AND c.IsCheckOut = '1'";
$res = mysqli_query($con, $sql);
$yesterdaysale = ($res && $row = mysqli_fetch_assoc($res)) ? $row['total'] : 0;

// === LAST 7 DAYS ===
$sql = "SELECT COALESCE(SUM(c.ProductQty * m.Priceperunit), 0) as total
        FROM tblcart c 
        JOIN tblmedicine m ON m.ID = c.ProductId 
        WHERE c.CartDate >= DATE(NOW()) - INTERVAL 7 DAY 
          AND c.PharmacistId = '$phid' 
          AND c.IsCheckOut = '1'";
$res = mysqli_query($con, $sql);
$tseven = ($res && $row = mysqli_fetch_assoc($res)) ? $row['total'] : 0;

// === TOTAL SALE ===
$sql = "SELECT COALESCE(SUM(c.ProductQty * m.Priceperunit), 0) as total
        FROM tblcart c 
        JOIN tblmedicine m ON m.ID = c.ProductId 
        WHERE c.PharmacistId = '$phid' 
          AND c.IsCheckOut = '1'";
$res = mysqli_query($con, $sql);
$totalsale = ($res && $row = mysqli_fetch_assoc($res)) ? $row['total'] : 0;

// === LAST SALE TODAY ===
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
  <title>Pharmacist Dashboard - Dharani PMS</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
  <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
  <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
  <style>
    .card-stats .card-body { padding: 1.5rem; }
    .last-sale { font-size: 0.9rem; }
    .text-success { color: #28a745 !important; }
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

            <!-- Today's Sale -->
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">
                        <a href="pharmacist-report-ds.php" class="text-muted">Today's Sale</a>
                      </h5>
                      <span class="h2 font-weight-bold mb-0">Rs. <?php echo number_format($todays_sale, 2); ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                        <!-- <i class="fas fa-rupee-sign"></i> -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Yesterday's Sale -->
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">
                        <a href="pharmacist-report-ds.php" class="text-muted">Yesterday's Sale</a>
                      </h5>
                      <span class="h2 font-weight-bold mb-0">Rs. <?php echo number_format($yesterdaysale, 2); ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                        <i class="fas fa-calendar-day"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Last 7 Days -->
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">
                        <a href="pharmacist-report-ds.php" class="text-muted">Last 7 Days</a>
                      </h5>
                      <span class="h2 font-weight-bold mb-0">Rs. <?php echo number_format($tseven, 2); ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                        <i class="fas fa-calendar-week"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Total Sale -->
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">
                        <a href="pharmacist-report-ds.php" class="text-muted">Total Sale</a>
                      </h5>
                      <span class="h2 font-weight-bold mb-0">Rs. <?php echo number_format($totalsale, 2); ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                        <i class="fas fa-chart-line"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>

          <!-- LAST SALE CARD -->
          <div class="row mt-4">
            <div class="col-xl-12">
              <div class="card shadow">
                <div class="card-header bg-transparent">
                  <h3 class="mb-0">Last Sale Today</h3>
                </div>
                <div class="card-body text-center py-5">
                  <?php if ($lastSaleAmount !== "No sale today"): ?>
                    <h1 class="display-4 font-weight-bold text-success"><?php echo $lastSaleAmount; ?></h1>
                    <p class="last-sale text-muted">
                      <i class="fas fa-user mr-1"></i> <strong><?php echo $pharmaName; ?></strong><br>
                      <i class="fas fa-clock mr-1"></i> <?php echo $lastSaleTime; ?>
                    </p>
                  <?php else: ?>
                    <p class="text-muted h5">
                      <i class="fas fa-info-circle"></i> No sales completed today yet.
                    </p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
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