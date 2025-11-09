<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['pmspid'] ?? '') == 0) {
    header('location:logout.php');
    exit;
}

$pmspid = $_SESSION['pmspid'];

// === PDF GENERATION ===
if (isset($_POST['generate_pdf'])) {
    require_once '../vendor/autoload.php'; // Composer
    // OR: require_once '../vendor/tcpdf/tcpdf.php';

    $fdate = $_POST['fromdate'];
    $tdate = $_POST['todate'];
    $rtype = $_POST['requesttype'];

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator('Dharani PMS');
    $pdf->SetAuthor('Dharani Pharmacy');
    $pdf->SetTitle('Sales Report');
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(TRUE, 10);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->AddPage();

    $html = '<h2 style="text-align:center;color:#5e72e4;">DHARANI PHARMACY</h2>
             <p style="text-align:center;">Gampaha, Sri Lanka | Tel: +94 33 222 1234</p>
             <h3 style="text-align:center;">Sales Report</h3>';

    if ($rtype == 'mtwise') {
        $m1 = date("F Y", strtotime($fdate));
        $m2 = date("F Y", strtotime($tdate));
        $html .= "<h4 style='text-align:center;color:#172b4d;'>$m1 to $m2</h4>";
    } else {
        $y1 = date("Y", strtotime($fdate));
        $y2 = date("Y", strtotime($tdate));
        $html .= "<h4 style='text-align:center;color:#172b4d;'>$y1 to $y2</h4>";
    }

    $html .= '<table border="1" cellpadding="5" cellspacing="0" style="font-size:10px;">
                <thead>
                    <tr style="background-color:#f6f9fc;">
                        <th><strong>S.NO</strong></th>
                        <th><strong>' . ($rtype == 'mtwise' ? 'Month/Year' : 'Year') . '</strong></th>
                        <th><strong>Medicine (Batch)</strong></th>
                        <th><strong>Qty</strong></th>
                        <th><strong>Price/Unit</strong></th>
                        <th><strong>Total</strong></th>
                    </tr>
                </thead><tbody>';

    $query = ($rtype == 'mtwise') ?
        "SELECT MONTH(tblcart.CartDate) as lmonth, YEAR(tblcart.CartDate) as lyear,
                tblmedicine.MedicineName, tblmedicine.MedicineBatchno,
                SUM(tblcart.ProductQty) as ProductQty, tblmedicine.Priceperunit
         FROM tblcart 
         JOIN tblmedicine ON tblmedicine.ID = tblcart.ProductId
         WHERE DATE(tblcart.CartDate) BETWEEN '$fdate' AND '$tdate'
         GROUP BY lmonth, lyear, tblmedicine.MedicineName" :
        "SELECT YEAR(tblcart.CartDate) as lyear,
                tblmedicine.MedicineName, tblmedicine.MedicineBatchno,
                SUM(tblcart.ProductQty) as ProductQty, tblmedicine.Priceperunit
         FROM tblcart 
         JOIN tblmedicine ON tblmedicine.ID = tblcart.ProductId
         WHERE DATE(tblcart.CartDate) BETWEEN '$fdate' AND '$tdate'
         GROUP BY lyear, tblmedicine.MedicineName";

    $ret = mysqli_query($con, $query);
    $cnt = 1; $gtotal = 0;

    while ($row = mysqli_fetch_array($ret)) {
        $total = $row['ProductQty'] * $row['Priceperunit'];
        $gtotal += $total;
        $date = $rtype == 'mtwise' ? $row['lmonth'] . '/' . $row['lyear'] : $row['lyear'];
        $html .= "<tr>
                    <td>$cnt</td>
                    <td>$date</td>
                    <td>{$row['MedicineName']} ({$row['MedicineBatchno']})</td>
                    <td>{$row['ProductQty']}</td>
                    <td>Rs. " . number_format($row['Priceperunit'], 2) . "</td>
                    <td>Rs. " . number_format($total, 2) . "</td>
                  </tr>";
        $cnt++;
    }

    $html .= "<tr style='background-color:#f6f9fc;font-weight:bold;'>
                <td colspan='5' style='text-align:center;'>Grand Total</td>
                <td>Rs. " . number_format($gtotal, 2) . "</td>
              </tr></tbody></table>";

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('sales-report.pdf', 'D');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sales Report - Dharani Pharmacy</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
  <link href="assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
  <link href="assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
  <style>
    .table-modern {
      font-size: 0.9rem;
      border-collapse: separate;
      border-spacing: 0;
    }
    .table-modern th {
      background: #f6f9fc;
      color: #525f7f;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 0.75rem;
      padding: 12px 15px;
      border-bottom: 2px solid #dee2e6;
    }
    .table-modern td {
      padding: 12px 15px;
      vertical-align: middle;
      border-bottom: 1px solid #dee2e6;
    }
    .table-modern tr:hover {
      background-color: #f8f9fa;
    }
    .badge-rs {
      font-weight: bold;
      color: #28a745;
    }
    .report-header {
      background: linear-gradient(87deg, #5e72e4, #825ee4);
      color: white;
      padding: 1.5rem;
      border-radius: 0.5rem;
      margin-bottom: 1.5rem;
    }
  </style>
</head>
<body>
  <?php include_once('includes/navbar.php'); ?>
  <div class="main-content">
    <?php include_once('includes/sidebar.php'); ?>

    <div class="header pb-8 pt-5 pt-md-8" style="background: linear-gradient(87deg, #11cdef, #1171ef);">
      <div class="container-fluid">
        <div class="header-body"></div>
      </div>
    </div>

    <div class="container-fluid mt--7">
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3 class="mb-0">Sales Report</h3>
            </div>

            <div class="card-body">
              <form method="post">
                <div class="row">
                  <div class="col-md-3">
                    <label>From Date</label>
                    <input type="date" name="fromdate" class="form-control" required>
                  </div>
                  <div class="col-md-3">
                    <label>To Date</label>
                    <input type="date" name="todate" class="form-control" required>
                  </div>
                  <div class="col-md-3">
                    <label>Report Type</label>
                    <select name="requesttype" class="form-control" required>
                      <option value="">Choose...</option>
                      <option value="mtwise">Month Wise</option>
                      <option value="yrwise">Year Wise</option>
                    </select>
                  </div>
                  <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" name="submit" class="btn btn-primary mr-2">
                      Generate
                    </button>
                    <button type="submit" name="generate_pdf" class="btn btn-success" 
                            <?php echo !isset($_POST['submit']) ? 'disabled' : ''; ?>>
                      PDF
                    </button>
                  </div>
                </div>
              </form>
            </div>

            <?php if (isset($_POST['submit'])): 
              $fdate = $_POST['fromdate'];
              $tdate = $_POST['todate'];
              $rtype = $_POST['requesttype'];
            ?>
            <div class="px-4 pb-4">
              <div class="report-header text-center">
                <h4 class="mb-0">Sales Report - <?php echo $rtype == 'mtwise' ? 'Month Wise' : 'Year Wise'; ?></h4>
                <p class="mb-0">
                  <?php echo $rtype == 'mtwise' 
                    ? date("F Y", strtotime($fdate)) . ' to ' . date("F Y", strtotime($tdate))
                    : date("Y", strtotime($fdate)) . ' to ' . date("Y", strtotime($tdate)); ?>
                </p>
              </div>

              <div class="table-responsive">
                <table class="table table-modern">
                  <thead>
                    <tr>
                      <th>S.NO</th>
                      <th><?php echo $rtype == 'mtwise' ? 'Month/Year' : 'Year'; ?></th>
                      <th>Medicine (Batch)</th>
                      <th>Qty Sold</th>
                      <th>Price/Unit</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $query = ($rtype == 'mtwise') ?
                        "SELECT MONTH(tblcart.CartDate) as lmonth, YEAR(tblcart.CartDate) as lyear,
                                tblmedicine.MedicineName, tblmedicine.MedicineBatchno,
                                SUM(tblcart.ProductQty) as ProductQty, tblmedicine.Priceperunit
                         FROM tblcart 
                         JOIN tblmedicine ON tblmedicine.ID = tblcart.ProductId
                         WHERE DATE(tblcart.CartDate) BETWEEN '$fdate' AND '$tdate'
                         GROUP BY lmonth, lyear, tblmedicine.MedicineName" :
                        "SELECT YEAR(tblcart.CartDate) as lyear,
                                tblmedicine.MedicineName, tblmedicine.MedicineBatchno,
                                SUM(tblcart.ProductQty) as ProductQty, tblmedicine.Priceperunit
                         FROM tblcart 
                         JOIN tblmedicine ON tblmedicine.ID = tblcart.ProductId
                         WHERE DATE(tblcart.CartDate) BETWEEN '$fdate' AND '$tdate'
                         GROUP BY lyear, tblmedicine.MedicineName";

                    $ret = mysqli_query($con, $query);
                    $num = mysqli_num_rows($ret);
                    if ($num > 0):
                      $cnt = 1; $gtotal = 0;
                      while ($row = mysqli_fetch_array($ret)):
                        $total = $row['ProductQty'] * $row['Priceperunit'];
                        $gtotal += $total;
                        $date = $rtype == 'mtwise' ? $row['lmonth'] . '/' . $row['lyear'] : $row['lyear'];
                    ?>
                    <tr>
                      <td><?php echo $cnt++; ?></td>
                      <td><strong><?php echo $date; ?></strong></td>
                      <td><?php echo $row['MedicineName']; ?> <small class="text-muted">(<?php echo $row['MedicineBatchno']; ?>)</small></td>
                      <td><span class="badge badge-info"><?php echo $row['ProductQty']; ?></span></td>
                      <td>Rs. <?php echo number_format($row['Priceperunit'], 2); ?></td>
                      <td><strong class="badge-rs">Rs. <?php echo number_format($total, 2); ?></strong></td>
                    </tr>
                    <?php endwhile; ?>
                    <tr class="table-success">
                      <th colspan="5" class="text-right">Grand Total</th>
                      <th>Rs. <?php echo number_format($gtotal, 2); ?></th>
                    </tr>
                    <?php else: ?>
                    <tr>
                      <td colspan="6" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-3x mb-3"></i><br>
                        No sales data found for the selected period.
                      </td>
                    </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php include_once('includes/footer.php'); ?>
    </div>
  </div>

  <script src="assets/js/plugins/jquery/dist/jquery.min.js"></script>
  <script src="assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/argon-dashboard.min.js?v=1.1.0"></script>
</body>
</html>