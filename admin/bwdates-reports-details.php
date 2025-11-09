<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// ---- Load TCPDF with YOUR absolute path ----
include('/Applications/XAMPP/xamppfiles/htdocs/pms/vendor/tecnickcom/tcpdf/tcpdf.php');

if (strlen($_SESSION['pmsaid'] == 0)) {
    header('location:logout.php');
    exit;
}

// ---------- GET POST VALUES ----------
$fdate = $_POST['fromdate'] ?? '';
$tdate = $_POST['todate'] ?? '';

// ---------- PDF DOWNLOAD ----------
if (isset($_POST['download_pdf'])) {

    // ---- Run Query for PDF ----
    $ret = mysqli_query($con,
        "SELECT tblmedicine.MedicineCompany,
                tblmedicine.MedicineName,
                tblmedicine.MedicineBatchno,
                tblmedicine.Quantity,
                COALESCE(SUM(tblcart.ProductQty), 0) AS totalsellqty
         FROM tblmedicine
         LEFT JOIN tblcart ON tblmedicine.ID = tblcart.ProductId
           AND DATE(tblcart.CartDate) BETWEEN '$fdate' AND '$tdate'
         WHERE DATE(tblmedicine.EntryDate) BETWEEN '$fdate' AND '$tdate'
         GROUP BY tblmedicine.ID, tblmedicine.MedicineName, tblmedicine.MedicineBatchno");

    // ---- Build PDF ----
    $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false); // Landscape for wide table
    $pdf->SetMargins(10, 15, 10);
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddPage();

    // Header
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Dharani Pharmacy Management System', 0, 1, 'C');
    $pdf->Ln(5);

    // Title
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 8, 'Stock & Sales Report Between Dates', 0, 1, 'C');
    $pdf->Cell(0, 8, "From $fdate to $tdate", 0, 1, 'C');
    $pdf->Ln(8);

    // Table HTML
    $html = '<table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr style="background-color:#f5f5f5;">
                <th width="5%"><b>S.NO</b></th>
                <th width="20%"><b>Company</b></th>
                <th width="25%"><b>Name</b></th>
                <th width="15%"><b>Batch Number</b></th>
                <th width="10%"><b>Qty</b></th>
                <th width="15%"><b>Sold Qty</b></th>
            </tr>
        </thead><tbody>';

    $cnt = 1;
    $hasData = false;

    while ($row = mysqli_fetch_array($ret)) {
        $hasData = true;
        $sold = $row['totalsellqty'] > 0 ? $row['totalsellqty'] : '0';

        $html .= "<tr>
                    <td width='5%'>$cnt</td>
                    <td width='20%'>{$row['MedicineCompany']}</td>
                    <td width='25%'>{$row['MedicineName']}</td>
                    <td width='15%'>{$row['MedicineBatchno']}</td>
                    <td width='10%' align='right'>{$row['Quantity']}</td>
                    <td width='15%' align='right'>$sold</td>
                  </tr>";
        $cnt++;
    }

    if (!$hasData) {
        $html .= '<tr><td colspan="6" align="center" style="color:red;">No record found.</td></tr>';
    }

    $html .= '</tbody></table>';

    $pdf->writeHTML($html, true, false, true, false, '');

    // Download
    $pdf->Output('between_dates_report.pdf', 'D');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pharmacy Management System - B/W Dates Reports</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
</head>
<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <div class="header-body"></div>
            </div>
        </div>

        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col">
                    <div class="card shadow">
                        <div class="card-header border-0">
                            <div class="table-responsive">
                                <h4 class="m-t-0 header-title">Between Dates Reports</h4>

<?php if ($fdate && $tdate): ?>
<h5 align="center" style="color:blue">
    Report from <?php echo htmlspecialchars($fdate); ?> to <?php echo htmlspecialchars($tdate); ?>
</h5>
<?php endif; ?>

<table class="table align-items-center table-flush" border="2">
    <thead class="thead-light">
        <tr>
            <th>S.NO</th>
            <th>Company</th>
            <th>Name</th>
            <th>Batch Number</th>
            <th>Qty</th>
            <th>Sold Qty</th>
        </tr>
    </thead>
    <tbody>
<?php
$ret = mysqli_query($con,
    "SELECT tblmedicine.MedicineCompany,
            tblmedicine.MedicineName,
            tblmedicine.MedicineBatchno,
            tblmedicine.Quantity,
            COALESCE(SUM(tblcart.ProductQty), 0) AS totalsellqty
     FROM tblmedicine
     LEFT JOIN tblcart ON tblmedicine.ID = tblcart.ProductId
       AND DATE(tblcart.CartDate) BETWEEN '$fdate' AND '$tdate'
     WHERE DATE(tblmedicine.EntryDate) BETWEEN '$fdate' AND '$tdate'
     GROUP BY tblmedicine.ID, tblmedicine.MedicineName, tblmedicine.MedicineBatchno");

$num = mysqli_num_rows($ret);

if ($num > 0) {
    $cnt = 1;
    while ($row = mysqli_fetch_array($ret)) {
        $sold = $row['totalsellqty'] > 0 ? $row['totalsellqty'] : '0';
?>
        <tr>
            <td><?php echo $cnt++; ?></td>
            <td><?php echo htmlspecialchars($row['MedicineCompany']); ?></td>
            <td><?php echo htmlspecialchars($row['MedicineName']); ?></td>
            <td><?php echo htmlspecialchars($row['MedicineBatchno']); ?></td>
            <td><?php echo $row['Quantity']; ?></td>
            <td><?php echo $sold; ?></td>
        </tr>
<?php
    }
} else {
?>
        <tr>
            <td colspan="6" style="text-align:center; color:red;">No record found.</td>
        </tr>
<?php
}
?>
    </tbody>
</table>

                            </div>

                            <!-- ==================== DOWNLOAD PDF BUTTON ==================== -->
                            <div class="card-footer py-4 text-center">
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="fromdate" value="<?php echo htmlspecialchars($fdate); ?>">
                                    <input type="hidden" name="todate"   value="<?php echo htmlspecialchars($tdate); ?>">
                                    <button type="submit" name="download_pdf" class="btn btn-primary">
                                        <i class="fa fa-file-pdf"></i> Download PDF
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once('includes/footer.php'); ?>
    </div>

    <!-- Core JS -->
    <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>
</body>
</html>
<?php  ?>