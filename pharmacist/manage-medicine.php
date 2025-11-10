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
    <title>Pharmacy Management System - Manage Medicines</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <!-- Argon Dashboard CSS -->
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f9fe; }
        .page-header { background: linear-gradient(87deg, #11cdef, #1171ef); }
        .card { border: none; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.08); overflow: hidden; }
        .card-header { background: transparent; border-bottom: 1px solid #e9ecef; padding: 1.5rem; }
        .search-box {
            position: relative;
            max-width: 400px;
        }
        .search-box input {
            padding-left: 45px;
            border-radius: 50px;
            border: 1px solid #ced4da;
        }
        .search-box i {
            position: absolute;
            left: 15px;
            top: 12px;
            color: #6c757d;
        }
        .table {
            margin-bottom: 0;
            background: #fff;
        }
        .table th {
            font-weight: 600;
            color: #5a5a5a;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top: none;
        }
        .table td {
            vertical-align: middle;
            font-size: 0.95rem;
            color: #333;
        }
        .table tr {
            transition: all 0.3s ease;
        }
        .table tr:hover {
            background-color: #f1f5f9 !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .badge-stock {
            padding: 0.4em 0.8em;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .stock-high { background: #d4edda; color: #155724; }
        .stock-medium { background: #fff3cd; color: #856404; }
        .stock-low { background: #f8d7da; color: #721c24; }
        .stock-critical { background: #f5c6cb; color: #721c24; animation: pulse 2s infinite; }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
        }
        .medicine-name {
            font-weight: 600;
            color: #2d3748;
        }
        .company-name {
            color: #5a67d8;
            font-size: 0.9rem;
        }
        .no-records {
            text-align: center;
            padding: 60px 20px;
            color: #718096;
            font-size: 1.1rem;
        }
        .no-records i {
            font-size: 4rem;
            color: #e2e8f0;
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .search-box { max-width: 100%; margin-bottom: 1rem; }
            .table-responsive { border-radius: 1rem; overflow: hidden; }
        }
    </style>
</head>
<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- Header -->
        <div class="header page-header pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row align-items-center py-4">
                        <div class="col-lg-6 col-7">
                            <h6 class="h2 text-white d-inline-block mb-0">
                                <i class="fas fa-pills mr-3"></i> Manage Medicines
                            </h6>
                        </div>
                        <div class="col-lg-6 col-5 text-right">
                            <div class="search-box float-right">
                                <i class="fas fa-search"></i>
                                <input type="text" class="form-control" placeholder="Search medicines..." id="searchInput">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page content -->
        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="mb-0 text-primary">
                                        <i class="fas fa-prescription-bottle-alt"></i> Medicine Stock Overview
                                    </h3>
                                    <p class="text-sm text-muted mb-0">Real-time inventory with remaining quantities</p>
                                </div>
                                <div class="col text-right">
                                    <span class="badge badge-info badge-pill px-3 py-2">
                                        Total: <strong id="totalRecords">0</strong> Items
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-items-center table-flush" id="medicineTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col"><strong>#</strong></th>
                                        <th scope="col"><i class="fas fa-building mr-2"></i>Company</th>
                                        <th scope="col"><i class="fas fa-capsules mr-2"></i>Medicine Name</th>
                                        <th scope="col"><i class="fas fa-barcode mr-2"></i>Batch No</th>
                                        <th scope="col"><i class="fas fa-boxes mr-2"></i>Total Qty</th>
                                        <th scope="col"><i class="fas fa-chart-line mr-2"></i>Sold</th>
                                        <th scope="col"><i class="fas fa-cubes mr-2"></i>Remaining</th>
                                        <th scope="col"><i class="fas fa-exclamation-triangle mr-2"></i>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = mysqli_query($con, "SELECT MedicineName, SUM(tblcart.ProductQty) as selledqty, tblmedicine.* 
                                                              FROM tblmedicine 
                                                              LEFT JOIN tblcart ON tblmedicine.ID = tblcart.ProductId 
                                                              GROUP BY MedicineName");
                                    $num = mysqli_num_rows($ret);
                                    if ($num > 0) {
                                        $cnt = 1;
                                        while ($row = mysqli_fetch_array($ret)) {
                                            $selled = $row['selledqty'] ?? 0;
                                            $total = $row['Quantity'];
                                            $remaining = $total - $selled;
                                            $percent = $total > 0 ? ($remaining / $total) * 100 : 0;

                                            // Stock status
                                            if ($remaining <= 0) {
                                                $statusClass = "stock-critical";
                                                $statusText = "OUT OF STOCK";
                                            } elseif ($remaining < 10) {
                                                $statusClass = "stock-low";
                                                $statusText = "LOW ($remaining left)";
                                            } elseif ($remaining < 30) {
                                                $statusClass = "stock-medium";
                                                $statusText = "Medium";
                                            } else {
                                                $statusClass = "stock-high";
                                                $statusText = "In Stock";
                                            }
                                    ?>
                                    <tr data-name="<?php echo strtolower($row['MedicineName']); ?>">
                                        <input type="hidden" name="mid" value="<?php echo $row['ID']; ?>">
                                        <td><strong><?php echo $cnt; ?></strong></td>
                                        <td>
                                            <span class="company-name">
                                                <i class="fas fa-industry text-muted mr-1"></i>
 ><?php echo htmlentities($row['MedicineCompany']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="medicine-name"><?php echo htmlentities($row['MedicineName']); ?></span>
                                        </td>
                                        <td>
                                            <code class="text-xs"><?php echo htmlentities($row['MedicineBatchno']); ?></code>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary badge-pill"><?php echo $total; ?></span>
                                        </td>
                                        <td>
                                            <span class="text-danger font-weight-bold"><?php echo $selled; ?></span>
                                        </td>
                                        <td>
                                            <strong class="text-success"><?php echo $remaining; ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-stock <?php echo $statusClass; ?>">
                                                <?php echo $statusText; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php
                                            $cnt++;
                                        }
                                    } else {
                                    ?>
                                    <tr>
                                        <td colspan="8" class="no-records">
                                            <i class="fas fa-prescription-bottle"></i><br><br>
                                            <strong>No medicines found</strong><br>
                                            <small>Add new medicines to see them here</small>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer py-4 bg-light">
                            <div class="row">
                                <div class="col text-muted">
                                    <small>
                                        <i class="fas fa-info-circle"></i>
                                        Last updated: <?php echo date('d M Y, h:i A'); ?>
                                    </small>
                                </div>
                                <div class="col text-right">
                                    <button class="btn btn-sm btn-outline-primary" onclick="window.print()">
                                        <i class="fas fa-print"></i> Print Report
                                    </button>
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
        // Live Search
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#medicineTable tbody tr[data-name]');
            let visible = 0;

            rows.forEach(row => {
                let name = row.getAttribute('data-name');
                if (name.includes(filter)) {
                    row.style.display = '';
                    visible++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('totalRecords').textContent = visible;
        });

        // Set initial count
        window.onload = function() {
            let total = document.querySelectorAll('#medicineTable tbody tr[data-name]').length;
            document.getElementById('totalRecords').textContent = total;
        };
    </script>
</body>
</html>
<?php } ?>