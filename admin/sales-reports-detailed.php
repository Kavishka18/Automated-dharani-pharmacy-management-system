<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// ---------- 1. Load TCPDF ----------
include('/Applications/XAMPP/xamppfiles/htdocs/pms/vendor/tecnickcom/tcpdf/tcpdf.php');

if (strlen($_SESSION['pmsaid'] == 0)) {
    header('location:logout.php');
    exit;
}

// ---------- 2. Get POST values ----------
$fdate   = $_POST['fromdate'] ?? '';
$tdate   = $_POST['todate']   ?? '';
$rtype   = $_POST['requesttype'] ?? '';

// ---------- 3. PDF DOWNLOAD REQUEST ----------
if (isset($_POST['download_pdf'])) {

    // ---- Re-create the same query for PDF ----
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetAutoPageBreak(true, 15);
    $pdf->AddPage();

    // ---- Header (Dharani Pharmacy Management System) ----
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->Cell(0, 10, 'Dharani Pharmacy Management System', 0, 1, 'C');
    $pdf->Ln(5);

    $html = '';

    if ($rtype == 'mtwise') {
        $month1 = strtotime($fdate); $month2 = strtotime($tdate);
        $m1 = date('F', $month1); $m2 = date('F', $month2);
        $y1 = date('Y', $month1); $y2 = date('Y', $month2);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'Sales Report – Month Wise', 0, 1, 'C');
        $pdf->Cell(0, 8, "From $m1-$y1 to $m2-$y2", 0, 1, 'C');
        $pdf->Ln(5);

        $sql = "SELECT MONTH(tblcart.CartDate) AS lmonth,
                       YEAR(tblcart.CartDate)  AS lyear,
                       tblmedicine.MedicineName,
                       tblmedicine.MedicineBatchno,
                       SUM(tblcart.ProductQty) AS ProductQty,
                       tblmedicine.Priceperunit
                FROM tblcart
                JOIN tblmedicine ON tblmedicine.ID = tblcart.ProductId
                WHERE DATE(tblcart.CartDate) BETWEEN '$fdate' AND '$tdate'
                GROUP BY lmonth, lyear, tblmedicine.MedicineName";

        $ret = mysqli_query($con, $sql);
        $gtotal = 0; $cnt = 1;

        $html .= '<table border="1" cellpadding="4" cellspacing="0" width="100%">
                  <thead>
                    <tr style="background-color:#f5f5f5;">
                      <th><b>S.NO</b></th>
                      <th><b>Month / Year</b></th>
                      <th><b>Medicine (Batch no.)</b></th>
                      <th><b>Qty Sold</b></th>
                      <th><b>Per Unit Price</b></th>
                      <th><b>Total</b></th>
                    </tr>
                  </thead><tbody>';

        while ($row = mysqli_fetch_array($ret)) {
            $qty    = $row['ProductQty'];
            $price  = $row['Priceperunit'];
            $total  = $qty * $price;
            $gtotal += $total;

            $html .= "<tr>
                        <td>$cnt</td>
                        <td>{$row['lmonth']}/{$row['lyear']}</td>
                        <td>{$row['MedicineName']} ({$row['MedicineBatchno']})</td>
                        <td>$qty</td>
                        <td>$price</td>
                        <td>$total</td>
                      </tr>";
            $cnt++;
        }
        $html .= "<tr><th colspan='5' style='text-align:center;'>Grand Total</th><th>$gtotal</th></tr>";
        $html .= '</tbody></table>';

    } else {   // year-wise
        $y1 = date('Y', strtotime($fdate));
        $y2 = date('Y', strtotime($tdate));

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 8, 'Sales Report – Year Wise', 0, 1, 'C');
        $pdf->Cell(0, 8, "From $y1 to $y2", 0, 1, 'C');
        $pdf->Ln(5);

        $sql = "SELECT YEAR(tblcart.CartDate) AS lyear,
                       tblmedicine.MedicineName,
                       tblmedicine.MedicineBatchno,
                       SUM(tblcart.ProductQty) AS ProductQty,
                       tblmedicine.Priceperunit
                FROM tblcart
                JOIN tblmedicine ON tblmedicine.ID = tblcart.ProductId
                WHERE DATE(tblcart.CartDate) BETWEEN '$fdate' AND '$tdate'
                GROUP BY lyear, tblmedicine.MedicineName";

        $ret = mysqli_query($con, $sql);
        $gtotal = 0; $cnt = 1;

        $html .= '<table border="1" cellpadding="4" cellspacing="0" width="100%">
                  <thead>
                    <tr style="background-color:#f5f5f5;">
                      <th><b>S.NO</b></th>
                      <th><b>Year</b></th>
                      <th><b>Medicine (Batch no.)</b></th>
                      <th><b>Qty Sold</b></th>
                      <th><b>Per Unit Price</b></th>
                      <th><b>Total</b></th>
                    </tr>
                  </thead><tbody>';

        while ($row = mysqli_fetch_array($ret)) {
            $qty    = $row['ProductQty'];
            $price  = $row['Priceperunit'];
            $total  = $qty * $price;
            $gtotal += $total;

            $html .= "<tr>
                        <td>$cnt</td>
                        <td>{$row['lyear']}</td>
                        <td>{$row['MedicineName']} ({$row['MedicineBatchno']})</td>
                        <td>$qty</td>
                        <td>$price</td>
                        <td>$total</td>
                      </tr>";
            $cnt++;
        }
        $html .= "<tr><th colspan='5' style='text-align:center;'>Grand Total</th><th>$gtotal</th></tr>";
        $html .= '</tbody></table>';
    }

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('sales_report.pdf', 'D');   // D = force download
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pharmacy Management System - Sales Reports</title>
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

<?php if ($rtype == 'mtwise') {
    $month1 = strtotime($fdate); $month2 = strtotime($tdate);
    $m1 = date('F', $month1); $m2 = date('F', $month2);
    $y1 = date('Y', $month1); $y2 = date('Y', $month2);
?>
    <h4 class="header-title m-t-0 m-b-30">Sales Report Month Wise</h4>
    <h4 align="center" style="color:blue">
        Sales Report from <?php echo $m1 . '-' . $y1; ?> to <?php echo $m2 . '-' . $y2; ?>
    </h4>

    <table class="table align-items-center" border="2">
        <thead class="thead-light">
            <tr>
                <th>S.NO</th>
                <th>Month / Year</th>
                <th>Medicine (Batch no.)</th>
                <th>Qty Sold</th>
                <th>Per Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
<?php
    $ret = mysqli_query($con,
        "SELECT MONTH(tblcart.CartDate) AS lmonth,
                YEAR(tblcart.CartDate)  AS lyear,
                tblmedicine.MedicineName,
                tblmedicine.MedicineBatchno,
                SUM(tblcart.ProductQty) AS ProductQty,
                tblmedicine.Priceperunit
         FROM tblcart
         JOIN tblmedicine ON tblmedicine.ID = tblcart.ProductId
         WHERE DATE(tblcart.CartDate) BETWEEN '$fdate' AND '$tdate'
         GROUP BY lmonth, lyear, tblmedicine.MedicineName");

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
                <td><?php echo $row['lmonth'] . '/' . $row['lyear']; ?></td>
                <td><?php echo $row['MedicineName']; ?> (<?php echo $row['MedicineBatchno']; ?>)</td>
                <td><?php echo $qty; ?></td>
                <td><?php echo $price; ?></td>
                <td><?php echo $total; ?></td>
            </tr>
<?php
        }
?>
            <tr>
                <th colspan="5" style="text-align:center;">Grand Total</th>
                <th><?php echo $gtotal; ?></th>
            </tr>
<?php
    }
?>
        </tbody>
    </table>

<?php } else {   // ---------------- YEAR WISE ---------------- ?>
    <?php
    $y1 = date('Y', strtotime($fdate));
    $y2 = date('Y', strtotime($tdate));
    ?>
    <h4 class="header-title m-t-0 m-b-30">Sales Report Year Wise</h4>
    <h4 align="center" style="color:blue">
        Sales Report from <?php echo $y1; ?> to <?php echo $y2; ?>
    </h4>

    <table class="table align-items-center" border="2">
        <thead class="thead-light">
            <tr>
                <th>S.NO</th>
                <th>Year</th>
                <th>Medicine (Batch no.)</th>
                <th>Qty Sold</th>
                <th>Per Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
<?php
    $ret = mysqli_query($con,
        "SELECT YEAR(tblcart.CartDate) AS lyear,
                tblmedicine.MedicineName,
                tblmedicine.MedicineBatchno,
                SUM(tblcart.ProductQty) AS ProductQty,
                tblmedicine.Priceperunit
         FROM tblcart
         JOIN tblmedicine ON tblmedicine.ID = tblcart.ProductId
         WHERE DATE(tblcart.CartDate) BETWEEN '$fdate' AND '$tdate'
         GROUP BY lyear, tblmedicine.MedicineName");

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
                <td><?php echo $row['lyear']; ?></td>
                <td><?php echo $row['MedicineName']; ?> (<?php echo $row['MedicineBatchno']; ?>)</td>
                <td><?php echo $qty; ?></td>
                <td><?php echo $price; ?></td>
                <td><?php echo $total; ?></td>
            </tr>
<?php
        }
?>
            <tr>
                <th colspan="5" style="text-align:center;">Grand Total</th>
                <th><?php echo $gtotal; ?></th>
            </tr>
<?php
    }
?>
        </tbody>
    </table>
<?php } ?>

                            </div> <!-- /.table-responsive -->

                            <!-- ==================== DOWNLOAD BUTTON ==================== -->
                            <div class="card-footer py-4">
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="fromdate" value="<?php echo htmlspecialchars($fdate); ?>">
                                    <input type="hidden" name="todate"   value="<?php echo htmlspecialchars($tdate); ?>">
                                    <input type="hidden" name="requesttype" value="<?php echo htmlspecialchars($rtype); ?>">
                                    <button type="submit" name="download_pdf" class="btn btn-success">
                                        <i class="fa fa-file-pdf"></i> Download as PDF
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