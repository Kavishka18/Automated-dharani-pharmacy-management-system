<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['pmspid'] ?? '') == 0) {
    header('location:logout.php');
    exit;
}

$pmspid = $_SESSION['pmspid'];
$msg = '';
$searchResult = null;
$items = [];
$gtotal = 0;

if (isset($_POST['search'])) {
    $sdata = mysqli_real_escape_string($con, $_POST['searchdata']);

    $sql = "SELECT DISTINCT 
                cust.BillingNumber, cust.CustomerName, cust.MobileNumber, 
                cust.ModeofPayment, cust.BillingDate
            FROM tblcustomer cust
            JOIN tblcart ct ON cust.BillingNumber = ct.BillingId
            WHERE ct.PharmacistId = '$pmspid' 
              AND (cust.BillingNumber = '$sdata' 
                   OR cust.MobileNumber = '$sdata' 
                   OR cust.CustomerName LIKE '%$sdata%')
            LIMIT 1";

    $ret = mysqli_query($con, $sql);
    if (mysqli_num_rows($ret) > 0) {
        $searchResult = mysqli_fetch_assoc($ret);

        $itemsSql = "SELECT m.MedicineName, ct.ProductQty, m.Priceperunit
                     FROM tblcart ct
                     JOIN tblmedicine m ON ct.ProductId = m.ID
                     WHERE ct.BillingId = '{$searchResult['BillingNumber']}'";

        $itemsRet = mysqli_query($con, $itemsSql);
        while ($row = mysqli_fetch_assoc($itemsRet)) {
            $total = $row['ProductQty'] * $row['Priceperunit'];
            $items[] = [
                'name' => $row['MedicineName'],
                'qty' => $row['ProductQty'],
                'price' => $row['Priceperunit'],
                'total' => $total
            ];
            $gtotal += $total;
        }
    } else {
        $msg = "No invoice found for '$sdata'";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice Search - Dharani Pharmacy</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
  <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
  <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
  <style>
    @media print {
      body * { visibility: hidden; }
      #receipt, #receipt * { visibility: visible; }
      #receipt { position: absolute; left: 0; top: 0; width: 100%; }
      .no-print { display: none !important; }
    }
    #receipt {
      max-width: 380px;
      margin: 20px auto;
      padding: 20px;
      border: 2px dashed #000;
      font-family: 'Courier New', monospace;
      background: #fff;
    }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .line { border-top: 1px dashed #000; margin: 10px 0; }
    .bold { font-weight: bold; }
    .refresh-btn {
      background: none;
      border: none;
      font-size: 1.2em;
      cursor: pointer;
      color: #5e72e4;
    }
    .refresh-btn:hover { color: #324cdd; }
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
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3 class="mb-0">Search Invoice</h3>

              <form method="post" class="mt-4" id="searchForm">
                <?php if ($msg): ?>
                  <div class="alert alert-danger"><?php echo $msg; ?></div>
                <?php endif; ?>

                <div class="row align-items-center">
                  <div class="col-lg-7">
                    <input type="text" name="searchdata" id="searchInput" class="form-control" 
                           placeholder="Billing # / Mobile / Name" <?php echo $searchResult ? 'value="' . htmlspecialchars($_POST['searchdata']) . '"' : ''; ?> required>
                  </div>
                  <div class="col-lg-3">
                    <button type="submit" name="search" class="btn btn-primary w-100">
                      Search
                    </button>
                  </div>
                  <div class="col-lg-2 text-center">
                    <button type="button" id="refreshBtn" class="refresh-btn" title="Clear & New Search">
                      Refresh
                    </button>
                  </div>
                </div>
              </form>
            </div>

            <!-- RECEIPT PREVIEW -->
            <?php if ($searchResult): ?>
            <div class="card-body no-print">
              <button onclick="window.print()" class="btn btn-success float-right">
                Print Receipt
              </button>
            </div>
            <?php endif; ?>

            <div id="receipt" class="p-3" style="display: <?php echo $searchResult ? 'block' : 'none'; ?>;">
              <!-- PHARMACY HEADER -->
              <div class="text-center">
                <h4 class="bold">DHARANI PHARMACY</h4>
                <p>
                  <small>
                    Gampaha, Sri Lanka<br>
                    Tel: +94 33 222 1234<br>
                    Email: dharani@gmail.com
                  </small>
                </p>
                <div class="line"></div>
                <p><strong>INVOICE</strong></p>
              </div>

              <!-- CUSTOMER INFO -->
              <table width="100%" style="font-size:14px;">
                <tr>
                  <td><strong>Bill #:</strong> <?php echo $searchResult['BillingNumber']; ?></td>
                  <td class="text-right"><strong>Date:</strong> <?php echo date('d/m/Y h:i A'); ?></td>
                </tr>
                <tr>
                  <td><strong>Name:</strong> <?php echo htmlspecialchars($searchResult['CustomerName']); ?></td>
                  <td class="text-right"><strong>Mobile:</strong> <?php echo $searchResult['MobileNumber']; ?></td>
                </tr>
                <tr>
                  <td colspan="2"><strong>Payment:</strong> <?php echo ucfirst($searchResult['ModeofPayment']); ?></td>
                </tr>
              </table>

              <div class="line"></div>

              <!-- ITEMS TABLE -->
              <table width="100%" style="font-size:14px;">
                <thead>
                  <tr>
                    <th class="text-left">Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($items as $item): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td class="text-center"><?php echo $item['qty']; ?></td>
                    <td class="text-right">Rs. <?php echo number_format($item['price'], 2); ?></td>
                    <td class="text-right">Rs. <?php echo number_format($item['total'], 2); ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>

              <div class="line"></div>

              <!-- TOTAL -->
              <table width="100%" style="font-size:16px;">
                <tr>
                  <td class="bold">GRAND TOTAL</td>
                  <td class="text-right bold">Rs. <?php echo number_format($gtotal, 2); ?></td>
                </tr>
              </table>

              <div class="line"></div>

              <!-- FOOTER -->
              <div class="text-center mt-3">
                <p><em>Thank you for your purchase!</em></p>
                <p><small>Come again</small></p>
                <p><small>Powered by Dharani PMS</small></p>
              </div>
            </div>

          </div>
        </div>
      </div>
      <?php include_once('includes/footer.php'); ?>
    </div>
  </div>

  <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
  <script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>

  <script>
    // Refresh Button - Clear form & receipt
    document.getElementById('refreshBtn').addEventListener('click', function() {
      document.getElementById('searchForm').reset();
      document.getElementById('searchInput').focus();
      document.getElementById('receipt').style.display = 'none';
      // Remove any alert
      const alert = document.querySelector('.alert');
      if (alert) alert.remove();
    });
  </script>
</body>
</html>