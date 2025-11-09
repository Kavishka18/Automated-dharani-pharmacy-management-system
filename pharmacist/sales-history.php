<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('includes/dbconnection.php');

if (!isset($_SESSION['pmspid']) || empty($_SESSION['pmspid'])) {
    header('location:logout.php');
    exit;
}

$pmspid = $_SESSION['pmspid'];
$today = date('Y-m-d');

$searchQuery = '';
$filterDate = $today;
if (isset($_POST['search'])) {
    $searchQuery = mysqli_real_escape_string($con, $_POST['search_query'] ?? '');
    $filterDate = mysqli_real_escape_string($con, $_POST['filter_date'] ?? $today);
}

// TODAY'S SALES
$todayQuery = "
    SELECT 
        ct.BillingId AS invoice,
        COALESCE(cust.CustomerName, 'Walk-in') AS customer,
        COALESCE(cust.MobileNumber, 'N/A') AS mobile,
        COALESCE(cust.ModeofPayment, 'cash') AS mode,
        SUM(ct.ProductQty * m.Priceperunit) AS total
    FROM tblcart ct
    JOIN tblmedicine m ON ct.ProductId = m.ID
    LEFT JOIN tblcustomer cust ON ct.BillingId = cust.BillingNumber
    WHERE ct.PharmacistId = '$pmspid' 
      AND ct.IsCheckOut = 1
      AND DATE(ct.SaleDate) = '$today'
";

if ($searchQuery) {
    $todayQuery .= " AND (cust.CustomerName LIKE '%$searchQuery%' OR ct.BillingId LIKE '%$searchQuery%')";
}

$todayQuery .= " GROUP BY ct.BillingId ORDER BY ct.SaleDate DESC";

$todayResult = mysqli_query($con, $todayQuery);
$todaySales = [];
$todayTotal = 0;
if ($todayResult) {
    while ($row = mysqli_fetch_assoc($todayResult)) {
        $todaySales[] = $row;
        $todayTotal += $row['total'];
    }
}

// PAYMENT SPLIT
$paymentBreakdown = ['cash' => 0, 'card' => 0];
$payQ = mysqli_query($con, "
    SELECT COALESCE(cust.ModeofPayment, 'cash') AS mode, 
           SUM(ct.ProductQty * m.Priceperunit) AS amount
    FROM tblcart ct
    JOIN tblmedicine m ON ct.ProductId = m.ID
    LEFT JOIN tblcustomer cust ON ct.BillingId = cust.BillingNumber
    WHERE ct.PharmacistId = '$pmspid' AND ct.IsCheckOut = 1
    GROUP BY mode
");
if ($payQ) {
    while ($row = mysqli_fetch_assoc($payQ)) {
        $mode = strtolower($row['mode']);
        $paymentBreakdown[$mode] = $row['amount'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sales History - PMS</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
  <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
  <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
</head>
<body class="">
  <?php 
  if (file_exists('includes/navbar.php')) include_once('includes/navbar.php');
  else echo '<nav class="bg-danger text-white p-2">navbar.php not found!</nav>';
  ?>
  <div class="main-content">
    <?php 
    if (file_exists('includes/sidebar.php')) include_once('includes/sidebar.php');
    else echo '<aside class="bg-warning p-2">sidebar.php not found!</aside>';
    ?>

    <div class="header bg-gradient-success pb-8 pt-5 pt-md-8">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6">
              <h1 class="text-white mb-0">Sales History</h1>
              <p class="text-white opacity-8">Live sales & trends</p>
            </div>
            <div class="col-lg-6 text-right">
              <h2 class="text-white mb-0">
                Rs. <?php echo number_format($todayTotal, 2); ?> Today
              </h2>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid mt--7">
      <!-- FILTER -->
      <div class="card mb-4">
        <div class="card-header">
          <h3 class="mb-0">Search & Filter</h3>
        </div>
        <div class="card-body">
          <form method="post">
            <div class="row">
              <div class="col-md-4">
                <input type="text" name="search_query" class="form-control" placeholder="Customer / Invoice" value="<?php echo htmlspecialchars($searchQuery); ?>">
              </div>
              <div class="col-md-3">
                <input type="date" name="filter_date" class="form-control" value="<?php echo $filterDate; ?>">
              </div>
              <div class="col-md-5 text-right">
                <button type="submit" name="search" class="btn btn-primary">Search</button>
                <a href="sales-history.php" class="btn btn-secondary">Reset</a>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- TODAY + CHART -->
      <div class="row">
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header">
              <h3 class="mb-0">Today's Sales (<?php echo count($todaySales); ?>)</h3>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th>Invoice</th>
                    <th>Customer</th>
                    <th>Mobile</th>
                    <th>Amount</th>
                    <th>Pay</th>
                    <th>View</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (count($todaySales) > 0): ?>
                    <?php foreach ($todaySales as $s): ?>
                    <tr>
                      <td><span class="badge badge-primary">#<?php echo $s['invoice']; ?></span></td>
                      <td><strong><?php echo htmlspecialchars($s['customer']); ?></strong></td>
                      <td><?php echo $s['mobile']; ?></td>
                      <td><strong class="text-success">Rs. <?php echo number_format($s['total'], 2); ?></strong></td>
                      <td>
                        <?php echo $s['mode'] == 'cash' 
                            ? '<span class="badge badge-success">Cash</span>' 
                            : '<span class="badge badge-primary">Card</span>'; ?>
                      </td>
                      <td>
                        <a href="invoice.php?bid=<?php echo $s['invoice']; ?>" class="btn btn-sm btn-info">View</a>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="6" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-3x"></i><br>No sales today
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card">
            <div class="card-header">
              <h3 class="mb-0">Payment Split</h3>
            </div>
            <div class="card-body">
              <canvas id="paymentChart"></canvas>
              <div class="text-center mt-3">
                <h5>Rs. <?php echo number_format($todayTotal, 2); ?> Total</h5>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- FULL HISTORY -->
      <div class="row mt-4">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="mb-0">All Sales</h3>
            </div>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="thead-dark">
                  <tr>
                    <th>Date</th>
                    <th>Invoice</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Pay</th>
                    <th>View</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $histQ = "
                      SELECT ct.BillingId AS invoice, COALESCE(cust.CustomerName, 'Walk-in') AS customer,
                             COALESCE(cust.ModeofPayment, 'cash') AS mode, SUM(ct.ProductQty * m.Priceperunit) AS total,
                             ct.SaleDate
                      FROM tblcart ct
                      JOIN tblmedicine m ON ct.ProductId = m.ID
                      LEFT JOIN tblcustomer cust ON ct.BillingId = cust.BillingNumber
                      WHERE ct.PharmacistId = '$pmspid' AND ct.IsCheckOut = 1
                  ";
                  if ($filterDate !== $today) $histQ .= " AND DATE(ct.SaleDate) = '$filterDate'";
                  if ($searchQuery) $histQ .= " AND (cust.CustomerName LIKE '%$searchQuery%' OR ct.BillingId LIKE '%$searchQuery%')";
                  $histQ .= " GROUP BY ct.BillingId ORDER BY ct.SaleDate DESC LIMIT 100";

                  $histR = mysqli_query($con, $histQ);
                  if (mysqli_num_rows($histR) > 0):
                      while ($h = mysqli_fetch_assoc($histR)): ?>
                      <tr>
                        <td><?php echo date('M j', strtotime($h['SaleDate'])); ?></td>
                        <td><strong>#<?php echo $h['invoice']; ?></strong></td>
                        <td><?php echo htmlspecialchars($h['customer']); ?></td>
                        <td><strong class="text-success">Rs. <?php echo number_format($h['total'], 2); ?></strong></td>
                        <td>
                          <?php echo $h['mode'] == 'cash' 
                              ? '<span class="badge badge-success">Cash</span>' 
                              : '<span class="badge badge-primary">Card</span>'; ?>
                        </td>
                        <td>
                          <a href="invoice.php?bid=<?php echo $h['invoice']; ?>" class="btn btn-sm btn-outline-primary">View</a>
                        </td>
                      </tr>
                      <?php endwhile; ?>
                  <?php else: ?>
                      <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                          <i class="fas fa-search fa-3x"></i><br>No records found
                        </td>
                      </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    new Chart(document.getElementById('paymentChart'), {
      type: 'doughnut',
      data: {
        labels: ['Cash', 'Card'],
        datasets: [{
          data: [<?php echo $paymentBreakdown['cash']; ?>, <?php echo $paymentBreakdown['card']; ?>],
          backgroundColor: ['#28a745', '#007bff']
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
      }
    });
  </script>
</body>
</html>