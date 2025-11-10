<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
include('/Applications/XAMPP/xamppfiles/htdocs/pms/vendor/tecnickcom/tcpdf/tcpdf.php');

if (strlen($_SESSION['pmspid'] == 0)) {
    header('location:logout.php');
    exit;
}

$fdate = $_POST['fromdate'] ?? '';
$tdate = $_POST['todate'] ?? '';
$pharname = $_POST['pharname'] ?? '';
$pid = $_SESSION['pmspid'];

// ========== PDF GENERATION (UNCHANGED LOGIC) ==========
if (isset($_POST['download_pdf'])) {
    $sql = mysqli_query($con, "SELECT FullName FROM tblpharmacist WHERE ID='$pid'");
    $row = mysqli_fetch_array($sql);
    $pname = $row['FullName'] ?? 'Pharmacist';

    $ret = mysqli_query($con,
    "SELECT tblmedicine.MedicineName,
            tblmedicine.MedicineBatchno,
            SUM(tblcart.ProductQty) AS ProductQty,
            tblmedicine.Priceperunit
     FROM tblcart
     JOIN tblmedicine ON tblmedicine.ID = tblcart.ProductId
     WHERE DATE(tblcart.CartDate) BETWEEN '$fdate' AND '$tdate'
     AND tblcart.PharmacistId = '$pid'
     GROUP BY tblmedicine.MedicineName, tblmedicine.MedicineBatchno");

    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetMargins(15, 20, 15);
    $pdf->SetAutoPageBreak(true, 15);
    $pdf->AddPage();

    $pdf->SetFont('helvetica', 'B', 18);
    $pdf->Cell(0, 10, 'Dharani Pharmacy Management System', 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 8, "Sales Report - $pname", 0, 1, 'C');
    $pdf->Cell(0, 8, "From $fdate to $tdate", 0, 1, 'C');
    $pdf->Ln(10);

    $html = '<table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr style="background-color:#667eea; color:white;">
                <th width="5%"><b>#</b></th>
                <th width="40%"><b>Medicine (Batch)</b></th>
                <th width="15%"><b>Qty Sold</b></th>
                <th width="20%"><b>Unit Price</b></th>
                <th width="20%"><b>Total</b></th>
            </tr>
        </thead><tbody>';

    $cnt = 1;
    $gtotal = 0;
    $hasData = false;

    while ($row = mysqli_fetch_array($ret)) {
        $hasData = true;
        $qty = $row['ProductQty'];
        $price = $row['Priceperunit'];
        $total = $qty * $price;
        $gtotal += $total;

        $html .= "<tr>
                    <td>$cnt</td>
                    <td><strong>{$row['MedicineName']}</strong><br><small>Batch: {$row['MedicineBatchno']}</small></td>
                    <td align='center'><span class='badge badge-success'>$qty</span></td>
                    <td align='right'>Rs. " . number_format($price, 2) . "</td>
                    <td align='right'><strong>Rs. " . number_format($total, 2) . "</strong></td>
                  </tr>";
        $cnt++;
    }

    if ($hasData) {
        $html .= "<tr style='background:#f0f2f5;'>
                    <th colspan='4' align='right'><h4>GRAND TOTAL</h4></th>
                    <th align='right'><h3>Rs. " . number_format($gtotal, 2) . "</h3></th>
                  </tr>";
    } else {
        $html .= '<tr><td colspan="5" align="center" style="color:red; padding:20px;">No sales record found for selected dates.</td></tr>';
    }
    $html .= '</tbody></table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('Dharani_Pharmacy_Sales_Report_' . date('d-m-Y') . '.pdf', 'D');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dharani Pharmacy - Sales Report</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />

    <style>
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; }
        .header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative; overflow: hidden; border-radius: 0 0 30px 30px;
        }
        .header::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: url('https://images.unsplash.com/photo-1580281773044-0b5577c7a2b2?ixlib=rb-4.0.3&auto=format&fit=crop&q=80') center/cover;
            opacity: 0.2;
        }
        .card {
            border: none; border-radius: 1.5rem; box-shadow: 0 20px 50px rgba(0,0,0,0.15);
            backdrop-filter: blur(12px); background: rgba(255,255,255,0.95);
            overflow: hidden;
        }
        .report-title {
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            font-weight: 700; font-size: 2.2rem;
        }
        .table {
            background: white; border-radius: 1rem; overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .table th {
            background: linear-gradient(45deg, #667eea, #764ba2) !important;
            color: white !important; font-weight: 600; text-transform: uppercase; font-size: 0.9rem;
        }
        .table tr:hover {
            background-color: #f8f9ff !important;
            transform: scale(1.01);
            transition: all 0.3s ease;
        }
        .grand-total {
            background: linear-gradient(45deg, #11998e, #38ef7d) !important;
            color: white !important; font-size: 1.3rem !important;
        }
        .btn-pdf {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            border: none; border-radius: 50px; padding: 12px 30px;
            font-weight: 600; box-shadow: 0 10px 30px rgba(231, 76, 60, 0.4);
            transition: all 0.4s ease;
        }
        .btn-pdf:hover {
            transform: translateY(-5px); box-shadow: 0 20px 40px rgba(231, 76, 60, 0.6);
        }
        .btn-print {
            background: linear-gradient(45deg, #3498db, #2980b9);
            border: none; border-radius: 50px; padding: 12px 25px;
        }
        .info-box {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white; border-radius: 1rem; padding: 1.5rem;
        }
        .badge-sold { background: #e74c3c; color: white; padding: 0.4em 0.8em; border-radius: 50px; }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .floating-icon { animation: float 4s ease-in-out infinite; }
    </style>
</head>
<body>

<?php include_once('includes/navbar.php'); ?>
<div class="main-content">
<?php include_once('includes/sidebar.php'); ?>

<!-- Header -->
<div class="header pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body text-center text-white position-relative">
            <h1 class="display-2 font-weight-bold">
                <i class="fas fa-file-invoice-dollar floating-icon mr-3"></i>
                Sales Report
            </h1>
            <p class="lead">Detailed sales performance by <?php 
                $sql = mysqli_query($con, "SELECT FullName FROM tblpharmacist WHERE ID='$pid'");
                $row = mysqli_fetch_array($sql);
                echo htmlspecialchars($row['FullName'] ?? 'Pharmacist');
            ?></p>
        </div>
    </div>
</div>

<div class="container-fluid mt--8">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card">
                <div class="card-header text-center info-box">
                    <h2 class="mb-0 text-white">
                        <i class="fas fa-calendar-alt mr-3"></i>
                        Report Period: <?php echo date('d M Y', strtotime($fdate)); ?> → <?php echo date('d M Y', strtotime($tdate)); ?>
                    </h2>
                    <p class="mb-0 mt-2">
                        <i class="fas fa-clock"></i> Generated on: <?php echo date('d M Y, h:i A'); ?> (Sri Lanka Time)
                    </p>
                </div>

                <div class="card-body p-4">
                    <?php
                    $ret = mysqli_query($con,
                    "SELECT tblmedicine.MedicineName,
                            tblmedicine.MedicineBatchno,
                            SUM(tblcart.ProductQty) AS ProductQty,
                            tblmedicine.Priceperunit
                     FROM tblcart
                     JOIN tblmedicine ON tblmedicine.ID = tblcart.ProductId
                     WHERE DATE(tblcart.CartDate) BETWEEN '$fdate' AND '$tdate'
                     AND tblcart.PharmacistId = '$pid'
                     GROUP BY tblmedicine.MedicineName, tblmedicine.MedicineBatchno");

                    $num = mysqli_num_rows($ret);
                    $gtotal = 0;
                    ?>

                    <?php if ($num > 0): ?>
                        <div class="table-responsive">
                            <table class="table align-items-center table-hover">
                                <thead>
                                    <tr>
                                        <th><strong>#</strong></th>
                                        <th><i class="fas fa-capsules"></i> Medicine (Batch)</th>
                                        <th><i class="fas fa-shopping-cart"></i> Qty Sold</th>
                                        <th><i class="fas fa-rupee-sign"></i> Unit Price</th>
                                        <th><i class="fas fa-money-bill-wave"></i> Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_array($ret)) {
                                        $qty = $row['ProductQty'];
                                        $price = $row['Priceperunit'];
                                        $total = $qty * $price;
                                        $gtotal += $total;
                                    ?>
                                    <tr>
                                        <td><strong class="text-primary"><?php echo $cnt++; ?></strong></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($row['MedicineName']); ?></strong><br>
                                            <small class="text-muted">Batch: <?php echo htmlspecialchars($row['MedicineBatchno']); ?></small>
                                        </td>
                                        <td><span class="badge badge-danger badge-pill px-3 py-2"><?php echo $qty; ?></span></td>
                                        <td>Rs. <?php echo number_format($price, 2); ?></td>
                                        <td><strong class="text-success">Rs. <?php echo number_format($total, 2); ?></strong></td>
                                    </tr>
                                    <?php } ?>
                                    <tr class="grand-total">
                                        <th colspan="4" class="text-right"><h4>GRAND TOTAL</h4></th>
                                        <th><h3>Rs. <?php echo number_format($gtotal, 2); ?></h3></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-5x text-warning mb-4"></i>
                            <h3>No sales found</h3>
                            <p class="text-muted">No medicines were sold between selected dates.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card-footer bg-light text-center py-4">
                    <form method="post" style="display:inline-block; margin:0 10px;">
                        <input type="hidden" name="fromdate" value="<?php echo htmlspecialchars($fdate); ?>">
                        <input type="hidden" name="todate" value="<?php echo htmlspecialchars($tdate); ?>">
                        <button type="submit" name="download_pdf" class="btn btn-pdf text-white btn-lg">
                            <i class="fa fa-file-pdf fa-2x align-middle"></i><br>
                            <span style="font-size:1.1rem;">Download PDF</span>
                        </button>
                    </form>

                    <button onclick="window.print()" class="btn btn-print text-white btn-lg">
                        <i class="fas fa-print fa-2x"></i><br>
                        <span style="font-size:1.1rem;">Print Report</span>
                    </button>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card bg-gradient-success text-white">
                        <div class="card-body text-center">
                            <h5><i class="fas fa-pills"></i> Total Items Sold</h5>
                            <h2><?php echo mysqli_num_rows($ret); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-gradient-info text-white">
                        <div class="card-body text-center">
                            <h5><i class="fas fa-calendar"></i> Date Range</h5>
                            <h4><?php echo date('d/m', strtotime($fdate)); ?> - <?php echo date('d/m/Y', strtotime($tdate)); ?></h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-gradient-warning text-white">
                        <div class="card-body text-center">
                            <h5><i class="fas fa-rupee-sign"></i> Total Revenue</h5>
                            <h2>Rs. <?php echo number_format($gtotal, 2); ?></h2>
                        </div>
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

<!-- <script>
    // Auto print styling
    @media print {
        body * { visibility: hidden; }
        .card, .card * { visibility: visible; }
        .card { position: absolute; left: 0; top: 0; width: 100%; }
        .card-footer { display: none; }
    } -->
</script>
</body>
</html>
<?php ?>