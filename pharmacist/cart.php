<?php
ob_start();
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['pmspid'] ?? '') == 0) {
    header('location:logout.php');
    exit;
}

$pmspid = $_SESSION['pmspid'];

// DELETE ITEM
if (isset($_GET['delid'])) {
    $rid = intval($_GET['delid']);
    mysqli_query($con, "DELETE FROM tblcart WHERE ID='$rid' AND PharmacistId='$pmspid' AND IsCheckOut=0");
    echo "<script>alert('Item removed'); window.location='cart.php';</script>";
    exit;
}

// CASH PAYMENT
if (isset($_POST['submit']) && $_POST['modepayment'] == 'cash') {
    $custname = mysqli_real_escape_string($con, $_POST['customername']);
    $custmobilenum = mysqli_real_escape_string($con, $_POST['mobilenumber']);
    $billiningnum = mt_rand(100000000, 999999999);

    $query = "UPDATE tblcart SET BillingId='$billiningnum', IsCheckOut=1, SaleDate=NOW() 
              WHERE IsCheckOut=0 AND PharmacistId='$pmspid';";
    $query .= "INSERT INTO tblcustomer(BillingNumber, CustomerName, MobileNumber, ModeofPayment) 
               VALUES('$billiningnum', '$custname', '$custmobilenum', 'cash');";

    if (mysqli_multi_query($con, $query)) {
        $_SESSION['invoiceid'] = $billiningnum;
        header("Location: invoice.php");
        exit;
    } else {
        echo "<script>alert('Cash payment failed!');</script>";
    }
}

// CARD PAYMENT (STRIPE)
if (isset($_POST['submit']) && $_POST['modepayment'] == 'card') {
    $custname = mysqli_real_escape_string($con, $_POST['customername']);
    $custmobilenum = mysqli_real_escape_string($con, $_POST['mobilenumber']);

    $totalQ = mysqli_query($con, "
        SELECT SUM(ct.ProductQty * m.Priceperunit) AS total 
        FROM tblcart ct JOIN tblmedicine m ON ct.ProductId = m.ID 
        WHERE ct.IsCheckOut = 0 AND ct.PharmacistId = '$pmspid'
    ");
    $totalRow = mysqli_fetch_assoc($totalQ);
    $totalAmount = $totalRow['total'] ?? 0;

    if ($totalAmount <= 0) {
        echo "<script>alert('Cart is empty!'); window.location='cart.php';</script>";
        exit;
    }

    $stripePath = '../vendor/stripe/stripe-php/init.php';
    if (file_exists($stripePath)) {
        require_once $stripePath;
        \Stripe\Stripe::setApiKey('sk_test_51S3cGaLyIcVwpOFyS059Bn4NnQMm4IXBtbCrRjODdqvh7O48PHyxglQevBfuOXApug8Oium6OgNpBKJfncEwvZNE00fX9vDRvt'); // ← YOUR SECRET KEY

        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'inr',
                        'product_data' => ['name' => 'Pharmacy Bill'],
                        'unit_amount' => intval($totalAmount * 100),
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => 'http://localhost/pms/pharmacist/stripe-success.php?session_id={CHECKOUT_SESSION_ID}&name=' . urlencode($custname) . '&mobile=' . urlencode($custmobilenum),
                'cancel_url' => 'http://localhost/pms/pharmacist/cart.php?cancel=1',
            ]);
            header("Location: " . $session->url);
            exit;
        } catch (Exception $e) {
            echo "<script>alert('Card Error: " . addslashes($e->getMessage()) . "');</script>";
        }
    } else {
        echo "<script>alert('Stripe not installed!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cart - PMS</title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
  <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
  <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
</head>
<body class="">
  <?php include_once('includes/navbar.php');?>
  <div class="main-content">
    <?php include_once('includes/sidebar.php');?>
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
              <h3 class="mb-0">Cart</h3>

              <form method="post" id="payment-form" class="mt-4">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>Customer Name</label>
                      <input type="text" name="customername" class="form-control" required placeholder="Enter name">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>Mobile Number</label>
                      <input type="text" name="mobilenumber" class="form-control" required maxlength="10" pattern="[0-9]+" placeholder="9876543210">
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label>Payment Method</label>
                  <div class="d-flex">
                    <div class="custom-control custom-radio mr-4">
                      <input type="radio" id="cash" name="modepayment" value="cash" class="custom-control-input" checked>
                      <label class="custom-control-label" for="cash">Cash</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input type="radio" id="card" name="modepayment" value="card" class="custom-control-input">
                      <label class="custom-control-label" for="card">Card</label>
                    </div>
                  </div>
                </div>

                <div class="text-center">
                  <button type="submit" name="submit" class="btn btn-primary">
                    <span id="btn-text">Submit</span>
                  </button>
                </div>
              </form>
            </div>

            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th>S.NO</th>
                    <th>Medicine</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = mysqli_query($con, "SELECT c.ID, m.MedicineName, c.ProductQty, m.Priceperunit 
                                             FROM tblcart c JOIN tblmedicine m ON c.ProductId = m.ID 
                                             WHERE c.IsCheckOut=0 AND c.PharmacistId='$pmspid'");
                  $cnt = 1; $gtotal = 0;
                  if (mysqli_num_rows($ret) > 0):
                    while ($row = mysqli_fetch_array($ret)):
                      $total = $row['ProductQty'] * $row['Priceperunit'];
                      $gtotal += $total;
                  ?>
                  <tr>
                    <td><?php echo $cnt++; ?></td>
                    <td><strong><?php echo $row['MedicineName']; ?></strong></td>
                    <td><span class="badge badge-info"><?php echo $row['ProductQty']; ?></span></td>
                    <td>Rs.<?php echo number_format($row['Priceperunit'], 2); ?></td>
                    <td><strong>Rs.<?php echo number_format($total, 2); ?></strong></td>
                    <td>
                      <a href="cart.php?delid=<?php echo $row['ID']; ?>" class="text-danger" onclick="return confirm('Delete?')">
                        Delete
                      </a>
                    </td>
                  </tr>
                  <?php endwhile; ?>
                  <tr class="table-success">
                    <th colspan="4" class="text-right">Grand Total</th>
                    <th colspan="2">Rs.<?php echo number_format($gtotal, 2); ?></th>
                  </tr>
                  <?php else: ?>
                  <tr>
                    <td colspan="6" class="text-center text-danger">No items in cart</td>
                  </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
  <script>
    document.getElementById('payment-form').addEventListener('submit', function() {
      document.getElementById('btn-text').innerHTML = 'Processing...';
    });
  </script>
</body>
</html>
<?php ob_end_flush(); ?>