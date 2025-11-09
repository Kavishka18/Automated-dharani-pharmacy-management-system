<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['pmsaid'] == 0)) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Medicines Inventory - Dharani PMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #e6f0ff 100%);
            color: #2d3748;
        }
        .main-content { 
            background: transparent; 
            padding-top: 20px !important;
        }

        /* Modern Header */
        .page-header {
            background: linear-gradient(87deg, #11998e 0%, #38ef7d 100%);
            padding: 85px 0 65px;
            text-align: center;
            color: white;
            border-radius: 0 0 45px 45px;
            box-shadow: 0 22px 50px rgba(17, 153, 142, 0.4);
            margin-bottom: 35px;
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.09"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        .page-header h1 {
            font-size: 2.7rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.6px;
        }
        .page-header p {
            font-size: 1.08rem;
            opacity: 0.93;
            margin-top: 10px;
        }

        /* Glass Card */
        .content-card {
            border-radius: 32px;
            border: none;
            box-shadow: 0 25px 65px rgba(0,0,0,0.15);
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(18px);
            overflow: hidden;
            margin: 25px auto;
            max-width: 96%;
        }

        .card-header {
            background: transparent;
            border: none;
            padding: 40px 50px 15px;
            text-align: center;
        }

        /* Table Styling */
        .table-container {
            padding: 0 50px 50px;
            overflow-x: auto;
        }
        .table {
            font-size: 0.94rem;
            margin: 0;
            border-collapse: separate;
            border-spacing: 0 16px;
        }
        .table thead {
            display: none;
        }
        .table tbody tr {
            background: white;
            border-radius: 24px;
            box-shadow: 0 12px 35px rgba(0,0,0,0.1);
            transition: all 0.35s ease;
        }
        .table tbody tr:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 55px rgba(0,0,0,0.2);
        }
        .table td {
            padding: 24px 28px;
            border: none;
            vertical-align: middle;
        }
        .table td:first-child {
            border-radius: 24px 0 0 24px;
            font-weight: 700;
            color: #11998e;
            font-size: 1.05rem;
        }
        .table td:last-child {
            border-radius: 0 24px 24px 0;
        }
        .medicine-name {
            font-weight: 600;
            font-size: 1.08rem;
            color: #1a202c;
        }
        .qty-total {
            font-weight: 600;
            color: #2dce89;
        }
        .qty-remaining {
            font-weight: 700;
            font-size: 1.15rem;
            color: #f5365c;
        }
        .qty-low {
            background: #fff5f5;
            color: #c53030;
            padding: 6px 14px;
            border-radius: 12px;
            font-size: 0.88rem;
        }

        /* Icon Circle */
        .icon-circle {
            width: 85px;
            height: 85px;
            background: linear-gradient(87deg, #11998e, #38ef7d);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
            box-shadow: 0 18px 45px rgba(17, 153, 142, 0.45);
        }

        /* Footer Brand */
        .brand-footer {
            background: linear-gradient(87deg, #1a1a2e, #16213e);
            color: white;
            padding: 38px;
            border-radius: 30px;
            text-align: center;
            margin: 100px auto 30px;
            max-width: 96%;
            box-shadow: 0 28px 60px rgba(0,0,0,0.4);
        }

        @media (max-width: 768px) {
            .page-header { padding: 65px 0 55px; }
            .page-header h1 { font-size: 2.2rem; }
            .content-card { margin: 15px 8px; }
            .table-container { padding: 0 25px 40px; }
            .table td { padding: 18px 14px; font-size: 0.89rem; }
            .medicine-name { font-size: 1rem; }
        }
    </style>
</head>
<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- MODERN HEADER -->
        <div class="page-header">
            <div class="container-fluid">
                <h1>Medicines Inventory</h1>
                <p>Real-time stock levels • Total vs Remaining</p>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-11">

                    <div class="content-card">
                        <div class="card-header">
                            <div class="icon-circle">
                                <i class="fas fa-clipboard-list fa-2x text-white"></i>
                            </div>
                            <h3>Current Stock Overview</h3>
                            <p class="text-muted" style="font-size:0.96rem; margin-top:10px;">
                                Total Medicines: 
                                <strong><?php echo mysqli_num_rows(mysqli_query($con,"SELECT DISTINCT MedicineName FROM tblmedicine")); ?></strong>
                            </p>
                        </div>

                        <div class="table-container">
                            <table class="table align-items-center">
                                <tbody>
                                    <?php
                                    $ret = mysqli_query($con, "SELECT MedicineName, sum(tblcart.ProductQty) as selledqty, tblmedicine.* 
                                                              FROM tblmedicine 
                                                              LEFT JOIN tblcart ON tblmedicine.ID = tblcart.ProductId 
                                                              GROUP BY MedicineName 
                                                              ORDER BY MedicineName");
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_array($ret)) {
                                        $total_qty = $row['Quantity'];
                                        $sold_qty = $row['selledqty'] ?? 0;
                                        $remaining = $total_qty - $sold_qty;
                                    ?>
                                    <tr>
                                        <td>#<?php echo $cnt; ?></td>
                                        <td><strong><?php echo htmlentities($row['MedicineCompany']); ?></strong></td>
                                        <td class="medicine-name"><?php echo htmlentities($row['MedicineName']); ?></td>
                                        <td><?php echo htmlentities($row['MedicineBatchno']); ?></td>
                                        <td class="qty-total"><?php echo $total_qty; ?></td>
                                        <!-- <td class="qty-remaining">
                                            <?php echo $remaining; ?>
                                            <?php if($remaining <= 10): ?>
                                                <span class="qty-low">LOW STOCK!</span>
                                            <?php endif; ?>
                                        </td> -->
                                    </tr>
                                    <?php $cnt++; } 

                                    if(mysqli_num_rows($ret) == 0): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-6">
                                            <i class="fas fa-prescription-bottle fa-5x text-muted mb-4"></i>
                                            <h4 class="text-muted">No inventory data available</h4>
                                            <p>Add medicines to see stock levels</p>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- BRAND FOOTER -->
                    <div class="brand-footer">
                        <h4 style="margin:0; font-weight:600; font-size:1.35rem;">DHARANI PHARMACY</h4>
                        <p style="margin:8px 0 0; font-size:0.98rem; opacity:0.9;">
                            Gampaha • Sri Lanka • Real-Time Inventory Control
                        </p>
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
<?php } ?>