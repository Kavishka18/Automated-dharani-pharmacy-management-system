<?php
session_start();
error_reporting(E_ALL);
include('includes/dbconnection.php');

// === 1. Login Check ===
$adminId = $_SESSION['pmsaid'] ?? 0;
if ($adminId == 0) { header('location: logout.php'); exit(); }

// === 2. Fetch Inventory + Sales ===
$q = mysqli_query($con, "
    SELECT 
        m.ID, m.MedicineName, m.MedicineCompany, m.MedicineBatchno,
        m.MgfDate, m.ExpiryDate, m.Quantity AS total_qty,
        COALESCE(SUM(c.ProductQty), 0) AS sold_qty,
        (m.Quantity - COALESCE(SUM(c.ProductQty), 0)) AS remaining_qty
    FROM tblmedicine m
    LEFT JOIN tblcart c ON m.ID = c.ProductId AND c.IsCheckOut = '1'
    GROUP BY m.ID
    ORDER BY m.ExpiryDate ASC
");

$inventory = [];
while ($row = mysqli_fetch_assoc($q)) {
    $inventory[] = $row;
}

// === 3. LOCAL AI-LIKE ANALYSIS (NO API) ===
$now = time();

// 1. Expiring Soon (Less than or equal to 30 days)
$expiringSoon = [];
foreach ($inventory as $i) {
    $daysLeft = (strtotime($i['ExpiryDate']) - $now) / 86400;
    if ($daysLeft <= 30) {
        $expiringSoon[] = [
            'name' => $i['MedicineName'],
            'days_left' => round($daysLeft),
            'action' => $daysLeft <= 0 ? 'Expired!' : 'Use Soon',
            'MedicineBatchno' => $i['MedicineBatchno'],
            'ExpiryDate' => $i['ExpiryDate']
        ];
    }
}

// 2. Nearly Expired (31–90 days)
$nearlyExpired = [];
foreach ($inventory as $i) {
    $daysLeft = (strtotime($i['ExpiryDate']) - $now) / 86400;
    if ($daysLeft > 30 && $daysLeft <= 90) {
        $urgency = $daysLeft <= 60 ? 'high' : 'medium';
        $nearlyExpired[] = [
            'name' => $i['MedicineName'],
            'urgency' => $urgency
        ];
    }
}

// 3. Top 5 Sellers
usort($inventory, fn($a, $b) => $b['sold_qty'] <=> $a['sold_qty']);
$topSellers = array_slice($inventory, 0, 5);

// 4. Restock Risks (Less than 10 left)
$risks = [];
foreach ($inventory as $i) {
    if ($i['remaining_qty'] < 10) {
        $risks[] = $i['MedicineName'] . " ({$i['remaining_qty']} left)";
    }
}

// 5. Trends (Smart Insights)
$trends = [];
foreach ($topSellers as $ts) {
    if ($ts['remaining_qty'] < 20) {
        $trends[] = "{$ts['MedicineName']} is selling fast — restock soon!";
    }
}
foreach ($expiringSoon as $es) {
    $item = array_filter($inventory, fn($x) => $x['MedicineName'] === $es['name'])[0] ?? null;
    if ($item && $item['sold_qty'] == 0) {
        $trends[] = "{$es['name']} expires in {$es['days_left']} days — never sold!";
    }
}
if (empty($trends)) {
    $trends[] = "Inventory stable. No urgent actions.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PMS – Inventory Analysis (No API)</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet"/>
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet"/>
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet"/>
</head>
<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- Header Stats -->
        <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Total Medicines</h5>
                                            <span class="h2 font-weight-bold mb-0"><?php echo count($inventory); ?></span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                                <i class="fas fa-capsules"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Expiring Soon</h5>
                                            <span class="h2 font-weight-bold mb-0 text-danger"><?php echo count($expiringSoon); ?></span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                                <i class="ni ni-time-alarm"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Nearly Expired</h5>
                                            <span class="h2 font-weight-bold mb-0 text-warning"><?php echo count($nearlyExpired); ?></span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                                <i class="ni ni-watch-time"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Restock Risks</h5>
                                            <span class="h2 font-weight-bold mb-0 text-info"><?php echo count($risks); ?></span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                                                <i class="ni ni-chart-bar-32"></i>
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

        <!-- Main Content -->
        <div class="container-fluid mt--7">

            <!-- Expiring Soon -->
            <div class="row">
                <div class="col-xl-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-transparent">
                            <h3 class="mb-0">Expiring Soon (Less than or equal to 30 Days)</h3>
                        </div>
                        <div class="card-body">
                            <?php if (empty($expiringSoon)): ?>
                                <p class="text-success">No medicines expiring soon.</p>
                            <?php else: ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($expiringSoon as $item): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <div>
                                                <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                                                <br><small class="text-muted">Batch: <?php echo htmlspecialchars($item['MedicineBatchno']); ?></small>
                                            </div>
                                            <span class="badge badge-<?php echo $item['days_left'] <= 0 ? 'dark' : 'danger'; ?> badge-pill">
                                                <?php echo $item['days_left']; ?> days
                                                <br><small><?php echo $item['action']; ?></small>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Nearly Expired & Risks -->
                <div class="col-xl-6 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-transparent">
                            <h3 class="mb-0">Nearly Expired & Restock Risks</h3>
                        </div>
                        <div class="card-body">
                            <h6 class="heading-small text-muted mb-2">Nearly Expired (31–90 Days)</h6>
                            <?php if (empty($nearlyExpired)): ?>
                                <p class="text-muted mb-3">No near-expiry items.</p>
                            <?php else: ?>
                                <ul class="list-group list-group-flush mb-3">
                                    <?php foreach ($nearlyExpired as $item): ?>
                                        <li class="list-group-item px-0">
                                            <?php echo htmlspecialchars($item['name']); ?>
                                            <span class="float-right badge badge-<?php echo $item['urgency'] === 'high' ? 'danger' : 'warning'; ?>">
                                                <?php echo ucfirst($item['urgency']); ?>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <hr class="my-3">

                            <h6 class="heading-small text-muted mb-2">Restock Risks (Less than 10 Left)</h6>
                            <?php if (empty($risks)): ?>
                                <p Crucial="text-success">All medicines well-stocked.</p>
                            <?php else: ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach ($risks as $r): ?>
                                            <li><?php echo htmlspecialchars($r); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top 5 Sellers with Pie Chart -->
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Top 5 Best-Selling Medicines</h3>
                            <div style="width: 160px; height: 160px;">
                                <canvas id="pieChart"></canvas>
                            </div>
                        </div>
                        <div class="card-body">

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Medicine</th>
                                            <th>Sold Qty</th>
                                            <th>Stock Left</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($topSellers as $seller): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($seller['MedicineName']); ?></td>
                                                <td><span class="badge badge-success"><?php echo $seller['sold_qty']; ?></span></td>
                                                <td><small class="text-muted"><?php echo $seller['remaining_qty']; ?></small></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Insights -->
                            <hr class="my-4">
                            <h6 class="heading-small text-muted">Smart Insights</h6>
                            <div class="alert alert-info">
                                <?php foreach ($trends as $t): ?>
                                    <p class="mb-1">• <?php echo htmlspecialchars($t); ?></p>
                                <?php endforeach; ?>
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
    <script src="../assets/js/plugins/chart.js/dist/Chart.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>

    <script>
        // PIE CHART (Small, in header)
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode(array_column($topSellers, 'MedicineName')); ?>,
                datasets: [{
                    data: <?php echo json_encode(array_column($topSellers, 'sold_qty')); ?>,
                    backgroundColor: ['#f6c23e', '#e74a3b', '#1cc88a', '#36b9cc', '#858796'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true }
                },
                cutout: '70%'
            }
        });
    </script>
</body>
</html>