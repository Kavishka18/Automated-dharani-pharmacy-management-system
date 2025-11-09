<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['pmsaid'] == 0)) {
  header('location:logout.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pharmacy Management System - Admin Dashboard</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
  <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
  <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
</head>
<body class="">
  <?php include_once('includes/navbar.php'); ?>
  <div class="main-content">
    <?php include_once('includes/sidebar.php'); ?>

    <!-- Header -->
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
      <div class="container-fluid">
        <div class="header-body">

          <!-- Row 1: Pharmacist, Company, Medicine -->
          <div class="row">
            <!-- Total Pharmacist -->
            <div class="col-xl-4 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <?php 
                    $query1 = mysqli_query($con, "SELECT * FROM tblpharmacist");
                    $pharcount = mysqli_num_rows($query1);
                    ?>
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">
                        <a href="manage-pharmacist.php" class="text-decoration-none">Total Pharmacist</a>
                      </h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $pharcount; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Total Medical Company -->
            <div class="col-xl-4 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <?php 
                    $query = mysqli_query($con, "SELECT * FROM tblcompany");
                    $compcount = mysqli_num_rows($query);
                    ?>
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">
                        <a href="manage-company.php" class="text-decoration-none">Total Medical Company</a>
                      </h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $compcount; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                        <i class="fa fa-building"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Total Medicine -->
            <div class="col-xl-4 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <?php 
                    $query2 = mysqli_query($con, "SELECT * FROM tblmedicine");
                    $medcount = mysqli_num_rows($query2);
                    ?>
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">
                        <a href="manage-medicine.php" class="text-decoration-none">Total Medicine</a>
                      </h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $medcount; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                        <i class="fas fa-plus-square"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Row 2: Sales (Today, Yesterday, 7 Days) -->
          <div class="row" style="margin-top:2%">
            <!-- Today's Sale -->
            <div class="col-xl-4 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <?php
                    $todysale = 0;
                    $query4 = mysqli_query($con, "
                        SELECT tblcart.ProductQty, tblmedicine.Priceperunit
                        FROM tblcart 
                        JOIN tblmedicine ON tblmedicine.ID = tblcart.ProductId 
                        WHERE DATE(CartDate) = CURDATE() AND tblcart.IsCheckOut = '1'
                    ");
                    while ($row = mysqli_fetch_array($query4)) {
                        $todysale += $row['ProductQty'] * $row['Priceperunit'];
                    }
                    ?>
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">
                        <a href="sales-reports.php" class="text-decoration-none">Today's Sale</a>
                      </h5>
                      <span class="h2 font-weight-bold mb-0">Rs. <?php echo $todysale > 0 ? number_format($todysale, 2) : '0.00'; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                        <i class="fas fa-rupee-sign"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Yesterday's Sale -->
            <div class="col-xl-4 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <?php
                    $yesterdaysale = 0;
                    $query5 = mysqli_query($con, "
                        SELECT tblcart.ProductQty, tblmedicine.Priceperunit
                        FROM tblcart 
                        JOIN tblmedicine ON tblmedicine.ID = tblcart.ProductId 
                        WHERE DATE(CartDate) = CURDATE() - 1 AND IsCheckOut = '1'
                    ");
                    while ($row5 = mysqli_fetch_array($query5)) {
                        $yesterdaysale += $row5['ProductQty'] * $row5['Priceperunit'];
                    }
                    ?>
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">
                        <a href="sales-reports.php" class="text-decoration-none">Yesterday's Sale</a>
                      </h5>
                      <span class="h2 font-weight-bold mb-0">Rs. <?php echo $yesterdaysale > 0 ? number_format($yesterdaysale, 2) : '0.00'; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                        <i class="fas fa-rupee-sign"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Last 7 Days Sale -->
            <div class="col-xl-4 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <?php
                    $tseven = 0;
                    $query6 = mysqli_query($con, "
                        SELECT tblcart.ProductQty, tblmedicine.Priceperunit
                        FROM tblcart 
                        JOIN tblmedicine ON tblmedicine.ID = tblcart.ProductId 
                        WHERE CartDate >= DATE(NOW()) - INTERVAL 7 DAY AND IsCheckOut = '1'
                    ");
                    while ($row2 = mysqli_fetch_array($query6)) {
                        $tseven += $row2['ProductQty'] * $row2['Priceperunit'];
                    }
                    ?>
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">
                        <a href="sales-reports.php" class="text-decoration-none">Last 7 Days Sale</a>
                      </h5>
                      <span class="h2 font-weight-bold mb-0">Rs. <?php echo $tseven > 0 ? number_format($tseven, 2) : '0.00'; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                        <i class="fas fa-chart-line"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Row 3: Total Sale + NEW: Expired Medicines -->
          <div class="row" style="margin-top:2%">
            <!-- Total Sale -->
            <div class="col-xl-6 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <?php
                    $totalsale = 0;
                    $query7 = mysqli_query($con, "
                        SELECT tblcart.ProductQty, tblmedicine.Priceperunit
                        FROM tblcart 
                        JOIN tblmedicine ON tblmedicine.ID = tblcart.ProductId 
                        WHERE IsCheckOut = '1'
                    ");
                    while ($row7 = mysqli_fetch_array($query7)) {
                        $totalsale += $row7['ProductQty'] * $row7['Priceperunit'];
                    }
                    ?>
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">
                        <a href="sales-reports.php" class="text-decoration-none">Total Sale (All Time)</a>
                      </h5>
                      <span class="h2 font-weight-bold mb-0">Rs. <?php echo $totalsale > 0 ? number_format($totalsale, 2) : '0.00'; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                        <i class="fas fa-chart-pie"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- NEW: Expired Medicines Count -->
            <div class="col-xl-6 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <?php 
                    $expiredQuery = mysqli_query($con, "
                        SELECT COUNT(*) as expired_count 
                        FROM tblmedicine 
                        WHERE ExpiryDate <= CURDATE()
                    ");
                    $expiredRow = mysqli_fetch_assoc($expiredQuery);
                    $expiredCount = $expiredRow['expired_count'];
                    ?>
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">
                        <a href="expired-alerts.php" class="text-decoration-none text-danger">Expired Medicines</a>
                      </h5>
                      <span class="h2 font-weight-bold mb-0 text-danger"><?php echo $expiredCount; ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                        <i class="fas fa-exclamation-triangle"></i>
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

    <div class="container-fluid mt--7">
      <?php include_once('includes/footer.php'); ?>
    </div>
  </div>

  <!-- Core JS -->
  <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
  <script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>
</body>
</html>
<?php  ?>