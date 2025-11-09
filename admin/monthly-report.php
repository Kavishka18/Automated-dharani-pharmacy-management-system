<?php
session_start();
ob_start();  // ← FIX: Prevent blank page
error_reporting(0);
include('includes/dbconnection.php');

// === ADMIN LOGIN CHECK ===
if (strlen($_SESSION['pmsaid'] ?? '') == 0) {
    header('location: logout.php');
    exit();
}

// === 1. FILTER LOGIC ===
$filter = $_GET['filter'] ?? 'thismonth';
$startDate = $endDate = date('Y-m-d');
$reportTitle = "Sales Report";

switch ($filter) {
    case 'thismonth':
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        $reportTitle = "This Month Report - " . date('F Y');
        break;
    case 'lastmonth':
        $startDate = date('Y-m-01', strtotime('-1 month'));
        $endDate = date('Y-m-t', strtotime('-1 month'));
        $reportTitle = "Last Month Report - " . date('F Y', strtotime('-1 month'));
        break;
    case '2months':
        $startDate = date('Y-m-d', strtotime('-2 months'));
        $reportTitle = "Last 2 Months Report";
        break;
    case '3months':
        $startDate = date('Y-m-d', strtotime('-3 months'));
        $reportTitle = "Last 3 Months Report";
        break;
    case 'custom':
        $startDate = $_GET['start'] ?? date('Y-m-01');
        $endDate = $_GET['end'] ?? date('Y-m-d');
        $reportTitle = "Custom Report: " . date('d M Y', strtotime($startDate)) . " to " . date('d M Y', strtotime($endDate));
        break;
    default:
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        $reportTitle = "This Month Report - " . date('F Y');
        break;
}

// === 2. TOTAL EARNINGS ===
$earningsQuery = mysqli_query($con, "
    SELECT SUM(c.ProductQty * m.Priceperunit) AS total_earnings
    FROM tblcart c
    JOIN tblmedicine m ON m.ID = c.ProductId
    WHERE c.IsCheckOut = '1'
      AND DATE(c.CartDate) BETWEEN '$startDate' AND '$endDate'
");
$earningsRow = mysqli_fetch_assoc($earningsQuery);
$totalEarnings = $earningsRow['total_earnings'] ?? 0;

// === 3. LOW STOCK (Global) ===
$lowStockQuery = mysqli_query($con, "
    SELECT 
        m.MedicineName,
        (m.Quantity - COALESCE(SUM(c.ProductQty), 0)) AS remaining_qty
    FROM tblmedicine m
    LEFT JOIN tblcart c ON m.ID = c.ProductId AND c.IsCheckOut = '1'
    GROUP BY m.ID
    HAVING remaining_qty < 10
    ORDER BY remaining_qty ASC
");
$lowStock = mysqli_fetch_all($lowStockQuery, MYSQLI_ASSOC);

// === 4. TOP 5 SELLING MEDICINES ===
$topMedsQuery = mysqli_query($con, "
    SELECT 
        m.MedicineName,
        SUM(c.ProductQty) AS qty_sold,
        (SUM(c.ProductQty) * m.Priceperunit) AS revenue
    FROM tblcart c
    JOIN tblmedicine m ON m.ID = c.ProductId
    WHERE c.IsCheckOut = '1'
      AND DATE(c.CartDate) BETWEEN '$startDate' AND '$endDate'
    GROUP BY m.ID
    ORDER BY qty_sold DESC
    LIMIT 5
");
$topMeds = [];
$pieLabels = [];
$pieData = [];
$pieColors = ['#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'];

while ($row = mysqli_fetch_assoc($topMedsQuery)) {
    $topMeds[] = $row;
    $pieLabels[] = $row['MedicineName'];
    $pieData[] = $row['revenue'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Report - PMS</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet"/>
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet"/>
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet"/>
    <script src="../assets/js/plugins/chart.js/dist/Chart.min.js"></script>
</head>
<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- Header -->
        <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row align-items-center py-4">
                        <div class="col-lg-6">
                            <h6 class="h2 text-white d-inline-block mb-0">Monthly Report</h6>
                        </div>
                    </div>

                    <!-- Filter -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <form method="get">
                                <div class="row align-items-end">
                                    <div class="col-md-3">
                                        <label>Filter</label>
                                        <select name="filter" class="form-control" onchange="this.form.submit()">
                                            <option value="thismonth" <?php echo ($filter == 'thismonth') ? 'selected' : ''; ?>>This Month</option>
                                            <option value="lastmonth" <?php echo ($filter == 'lastmonth') ? 'selected' : ''; ?>>Last Month</option>
                                            <option value="2months" <?php echo ($filter == '2months') ? 'selected' : ''; ?>>Last 2 Months</option>
                                            <option value="3months" <?php echo ($filter == '3months') ? 'selected' : ''; ?>>Last 3 Months</option>
                                            <option value="custom" <?php echo ($filter == 'custom') ? 'selected' : ''; ?>>Custom Range</option>
                                        </select>
                                    </div>
                                    <?php if ($filter == 'custom'): ?>
                                    <div class="col-md-3">
                                        <label>From</label>
                                        <input type="date" name="start" class="form-control" value="<?php echo $startDate; ?>" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label>To</label>
                                        <input type="date" name="end" class="form-control" value="<?php echo $endDate; ?>" required>
                                    </div>
                                    <?php endif; ?>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary">Apply</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card card-stats">
                                <div class="card-body">
                                    <h5 class="text-muted">Total Earnings</h5>
                                    <span class="h2">Rs.<?php echo number_format($totalEarnings, 2); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card card-stats">
                                <div class="card-body">
                                    <h5 class="text-muted">Low Stock</h5>
                                    <span class="h2"><?php echo count($lowStock); ?> items</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="container-fluid mt--7">
            <!-- Pie Chart + Low Stock -->
            <div class="row">
                <div class="col-xl-6 mb-5">
                    <div class="card shadow">
                        <div class="card-header bg-transparent">
                            <h3 class="mb-0">Top 5 Medicines by Revenue</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="revenuePieChart" height="280"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 mb-5">
                    <div class="card shadow">
                        <div class="card-header bg-transparent">
                            <h3 class="mb-0">Low Stock Medicines</h3>
                            <p class="text-sm text-muted mb-0">Less than 10 units remaining</p>
                        </div>
                        <div class="card-body">
                            <?php if (empty($lowStock)): ?>
                                <div class="text-center py-4">
                                    <i class="ni ni-check-bold text-success" style="font-size: 3rem;"></i>
                                    <p class="mt-2 text-success">All medicines are well-stocked!</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Medicine Name</th>
                                                <th>Remaining Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($lowStock as $item): ?>
                                            <tr>
                                                <td><strong><?php echo htmlspecialchars($item['MedicineName']); ?></strong></td>
                                                <td><span class="badge badge-danger badge-pill"><?php echo $item['remaining_qty']; ?></span></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top 5 Table -->
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">Top 5 Best-Selling Medicines</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th>Medicine</th>
                                    <th>Qty Sold</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($topMeds)): ?>
                                    <tr><td colspan="3" class="text-center text-muted">No sales in this period</td></tr>
                                <?php else: ?>
                                    <?php foreach ($topMeds as $med): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($med['MedicineName']); ?></td>
                                        <td><span class="badge badge-success"><?php echo $med['qty_sold']; ?></span></td>
                                        <td><strong>Rs.<?php echo number_format($med['revenue'], 2); ?></strong></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once('includes/footer.php'); ?>
    </div>

    <!-- Scripts -->
    <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>
    <script>
        const ctx = document.getElementById('revenuePieChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($pieLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($pieData); ?>,
                    backgroundColor: <?php echo json_encode($pieColors); ?>,
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true } },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': Rs.' + context.parsed.toFixed(2);
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    </script>
</body>
</html>
