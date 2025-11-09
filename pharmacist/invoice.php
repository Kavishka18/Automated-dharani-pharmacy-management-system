<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (!isset($_SESSION['pmspid']) || empty($_SESSION['pmspid'])) {
    header('location:logout.php');
    exit;
}

$pmspid = $_SESSION['pmspid'];

// GET INVOICE ID FROM URL - THIS IS THE KEY FIX
$billingid = $_GET['bid'] ?? '';
if (empty($billingid)) {
    echo "<script>alert('Sucessfuly Generated Invoice!'); window.location='sales-history.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice #<?php echo htmlspecialchars($billingid); ?> - Dharani PMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
  <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
  <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
  <style>
    @media print {
      body * { visibility: hidden; }
      #invoice-print, #invoice-print * { visibility: visible; }
      #invoice-print { position: absolute; left: 0; top: 0; width: 100%; }
      .no-print { display: none !important; }
    }
    #invoice-print {
      max-width: 380px;
      margin: 20px auto;
      padding: 25px;
      border: 2px dashed #000;
      font-family: 'Courier New', monospace;
      background: white;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .text-center { text-align: center; }
    .line { border-top: 1px dashed #000; margin: 15px 0; }
    .bold { font-weight: bold; }
    .badge-rs { color: #28a745; font-weight: bold; }
  </style>
</head>
<body>
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
        <div class="col-xl-8 mx-auto">
          <div class="card shadow">
            <div class="card-header bg-transparent no-print">
              <div class="row align-items-center">
                <div class="col">
                  <h2 class="mb-0">Invoice #<?php echo htmlspecialchars($billingid); ?></h2>
                </div>
                <!-- <div class="col text-right">
                  <button onclick="window.print()" class="btn btn-success">
                    Print Receipt
                  </button>
                </div> -->
              </div>
            </div>

            <div id="invoice-print">
              <!-- HEADER -->
              <div class="text-center">
                <h3 class="bold">DHARANI PHARMACY</h3>
                <p>
                  <small>
                    Gampaha, Sri Lanka<br>
                    Tel: +94 33 222 1234<br>
                    <span id="print-date"></span>
                  </small>
                </p>
                <div class="line"></div>
                <h4>INVOICE</h4>
              </div>

              <!-- CUSTOMER INFO -->
              <?php
              $cust = mysqli_fetch_assoc(mysqli_query($con, "
                  SELECT CustomerName, MobileNumber, ModeofPayment, BillingDate 
                  FROM tblcustomer 
                  WHERE BillingNumber = '$billingid' 
                  LIMIT 1
              "));
              ?>
              <table width="100%" style="font-size:14px;">
                <tr  <td><strong>Bill #:</strong> <?php echo $billingid; ?></td>
                  <td class="text-right"><strong>Date:</strong> <?php echo date('d/m/Y h:i A', strtotime($cust['BillingDate'] ?? 'now')); ?></td>
                </tr>
                <tr>
                  <td><strong>Name:</strong> <?php echo htmlspecialchars($cust['CustomerName'] ?? 'Walk-in'); ?></td>
                  <td class="text-right"><strong>Mobile:</strong> <?php echo $cust['MobileNumber'] ?? 'N/A'; ?></td>
                </tr>
                <tr>
                  <td colspan="2"><strong>Payment:</strong> <?php echo ucfirst($cust['ModeofPayment'] ?? 'cash'); ?></td>
                </tr>
              </table>

              <div class="line"></div>

              <!-- ITEMS -->
              <table width="100%" style="font-size:14px;">
                <thead>
                  <tr>
                    <th>Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $items = mysqli_query($con, "
                      SELECT m.MedicineName, ct.ProductQty, m.Priceperunit
                      FROM tblcart ct
                      JOIN tblmedicine m ON ct.ProductId = m.ID
                      WHERE ct.BillingId = '$billingid' AND ct.PharmacistId = '$pmspid'
                  ");
                  $gtotal = 0;
                  while ($row = mysqli_fetch_assoc($items)):
                    $total = $row['ProductQty'] * $row['Priceperunit'];
                    $gtotal += $total;
                  ?>
                  <tr>
                    <td><?php echo htmlspecialchars($row['MedicineName']); ?></td>
                    <td class="text-center"><?php echo $row['ProductQty']; ?></td>
                    <td class="text-right">Rs. <?php echo number_format($row['Priceperunit'], 2); ?></td>
                    <td class="text-right">Rs. <?php echo number_format($total, 2); ?></td>
                  </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>

              <div class="line"></div>

              <table width="100%" style="font-size:16px;">
                <tr>
                  <td class="bold">GRAND TOTAL</td>
                  <td class="text-right bold badge-rs">Rs. <?php echo number_format($gtotal, 2); ?></td>
                </tr>
              </table>

              <div class="line"></div>

              <div class="text-center mt-3">
                <p><em>Thank you for your purchase!</em></p>
                <p><small>Come again • Powered by Dharani PMS</small></p>
              </div>
            </div>

            <div class="card-footer text-center no-print">
              <button onclick="window.print()" class="btn btn-success btn-lg">
                Print This Invoice
              </button>
            </div>
          </div>
        </div>
      </div>

      <?php include_once('includes/footer.php'); ?>
    </div>
  </div>

  <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
  <script>
    // Auto set current date/time in receipt
    document.getElementById('print-date').innerText = new Date().toLocaleString();
  </script>
</body>
</html>