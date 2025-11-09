<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// ---- Load TCPDF with YOUR absolute path ----
include('/Applications/XAMPP/xamppfiles/htdocs/pms/vendor/tecnickcom/tcpdf/tcpdf.php');

if (strlen($_SESSION['pmspid'] == 0)) {
    header('location:logout.php');
    exit;
}

// ---------- GET POST VALUES ----------
$fdate    = $_POST['fromdate'] ?? '';
$tdate    = $_POST['todate'] ?? '';
$pharname = $_POST['pharname'] ?? '';
$pid      = $_SESSION['pmspid'];

// ---------- PDF DOWNLOAD ----------
if (isset($_POST['download_pdf'])) {

    // ---- Get pharmacist name (from session) ----
    $sql = mysqli_query($con, "SELECT FullName FROM tblpharmacist WHERE ID='$pid'");
    $row = mysqli_fetch_array($sql);
    $pname = $row['FullName'] ?? 'Pharmacist';

    // ---- Query for PDF ----
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

    // ---- Build PDF ----
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetMargins(15, 20, 15);
    $pdf->SetAutoPageBreak(true, 15);
    $pdf->AddPage();

    // Header
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Dharani Pharmacy Management System', 0, 1, 'C');
    $pdf->Ln(5);

    // Title
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 8, "Sales Report - $pname", 0, 1, 'C');
    $pdf->Cell(0, 8, "From $fdate to $tdate", 0, 1, 'C');
    $pdf->Ln(8);

    // Table HTML
    $html = '<table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr style="background-color:#f5f5f5;">
                <th width="5%"><b>S.NO</b></th>
                <th width="40%"><b>Medicine (Batch no.)</b></th>
                <th width="15%"><b>Qty Sold</b></th>
                <th width="20%"><b>Per Unit Price</b></th>
                <th width="20%"><b>Total</b></th>
            </tr>
        </thead><tbody>';

    $cnt     = 1;
    $gtotal  = 0;
    $hasData = false;

    while ($row = mysqli_fetch_array($ret)) {
        $hasData = true;
        $qty   = $row['ProductQty'];
        $price = $row['Priceperunit'];
        $total = $qty * $price;
        $gtotal += $total;

        $html .= "<tr>
                    <td width='5%'>$cnt</td>
                    <td width='40%'>{$row['MedicineName']} ({$row['MedicineBatchno']})</td>
                    <td width='15%' align='right'>$qty</td>
                    <td width='20%' align='right'>$price</td>
                    <td width='20%' align='right'>$total</td>
                  </tr>";
        $cnt++;
    }

    if ($hasData) {
        $html .= "<tr><th colspan='4' style='text-align:center;'>Grand Total</th><th align='right'>$gtotal</th></tr>";
    } else {
        $html .= '<tr><td colspan="5" align="center" style="color:red;">No record found.</td></tr>';
    }

    $html .= '</tbody></table>';

    $pdf->writeHTML($html, true, false, true, false, '');

    // Download
    $pdf->Output('pharmacist_sales_report.pdf', 'D');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pharmacy Management System - Sold Report</title>
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
                                <h4 class="m-t-0 header-title">Pharmacist Sales Report</h4>

<?php
// ---- Get pharmacist name from session ----
$sql = mysqli_query($con, "SELECT FullName FROM tblpharmacist WHERE ID='$pid'");
$row = mysqli_fetch_array($sql);
$pname = $row['FullName'] ?? 'Pharmacist';
?>
<h4 align="center" style="color:blue">
    Report from <?php echo htmlspecialchars($fdate); ?> to <?php echo htmlspecialchars($tdate); ?>  
    <br>Sold by: <?php echo htmlspecialchars($pname); ?>
</h4>

<table class="table align-items-center" border="2">
    <thead class="thead-light">
        <tr>
            <th>S.NO</th>
            <th>Medicine (Batch no.)</th>
            <th>Qty Sold</th>
            <th>Per Unit Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
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

if ($num > 0) {
    $cnt = 1;
    while ($row = mysqli_fetch_array($ret)) {
        $qty   = $row['ProductQty'];
        $price = $row['Priceperunit'];
        $total = $qty * $price;
        $gtotal += $total;
?>
        <tr>
            <td><?php echo $cnt++; ?></td>
            <td><?php echo htmlspecialchars($row['MedicineName']); ?> (<?php echo htmlspecialchars($row['MedicineBatchno']); ?>)</td>
            <td><?php echo $qty; ?></td>
            <td><?php echo $price; ?></td>
            <td><?php echo $total; ?></td>
        </tr>
<?php
    }
?>
        <tr>
            <th colspan="4" style="text-align:center;">Grand Total</th>
            <th><?php echo $gtotal; ?></th>
        </tr>
<?php
} else {
?>
        <tr>
            <td colspan="5" style="text-align:center; color:red;">No record found.</td>
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
                                    <input type="hidden" name="pharname" value="<?php echo htmlspecialchars($pid); ?>">
                                    <button type="submit" name="download_pdf" class="btn btn-success">
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