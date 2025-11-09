<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['pmspid'] == 0)) {
    header('location:logout.php');
    exit;
}

// === ADD TO CART ===
if (isset($_POST['cart'])) {
    $pmid = $_SESSION['pmspid'];
    $pid  = intval($_POST['mid']);
    $pqty = intval($_POST['pqty']);

    if ($pqty <= 0) {
        echo "<script>alert('Enter valid quantity!'); window.location='search.php';</script>";
        exit;
    }

    // Get total stock
    $stockQ = mysqli_query($con, "SELECT Quantity FROM tblmedicine WHERE ID='$pid'");
    if (!$stockQ || mysqli_num_rows($stockQ) == 0) {
        echo "<script>alert('Medicine not found!'); window.location='search.php';</script>";
        exit;
    }
    $stockR = mysqli_fetch_assoc($stockQ);
    $totalStock = $stockR['Quantity'];

    // Get **SOLD** (only IsCheckOut=1)
    $soldQ = mysqli_query($con, "SELECT COALESCE(SUM(ProductQty), 0) AS sold FROM tblcart WHERE ProductId='$pid' AND IsCheckOut=1");
    $soldR = mysqli_fetch_assoc($soldQ);
    $sold = $soldR['sold'];

    $remaining = $totalStock - $sold;

    if ($pqty > $remaining) {
        echo "<script>alert('Only $remaining unit(s) available! You tried $pqty.'); window.location='search.php';</script>";
        exit;
    }

    // Insert into cart
    $insert = mysqli_query($con, "INSERT INTO tblcart (PharmacistId, ProductId, ProductQty, IsCheckOut) VALUES ('$pmid', '$pid', '$pqty', 0)");
    if ($insert) {
        echo "<script>alert('Added $pqty unit(s) to cart!'); window.location='search.php';</script>";
    } else {
        echo "<script>alert('Database error: " . mysqli_error($con) . "');</script>";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Pharmacy Management System - Search Medicines</title>
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
              <form method="post" class="mb-4">
                <div class="row align-items-center">
                  <div class="col-lg-8">
                    <div class="form-group mb-0">
                      <label class="form-control-label">
                        <i class="fas fa-search text-info"></i> Search Medicine
                      </label>
                      <input type="text" name="searchdata" class="form-control" required placeholder="Enter medicine name..." autofocus>
                    </div>
                  </div>
                  <div class="col-lg-4 text-lg-right">
                    <button type="submit" name="search" class="btn btn-primary">
                      <i class="fas fa-search"></i> Search
                    </button>
                  </div>
                </div>
              </form>

              <?php if (isset($_POST['search'])): 
                $sdata = mysqli_real_escape_string($con, $_POST['searchdata']);
              ?>
                <h4 class="text-center text-primary mb-3">
                  Results for "<strong><?php echo htmlspecialchars($sdata); ?></strong>"
                </h4>
              <?php endif; ?>
            </div>

            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th>S.No</th>
                    <th>Company</th>
                    <th>Name</th>
                    <th>Batch</th>
                    <th>Total Stock</th>
                    <th>Sold</th>
                    <th>Remaining</th>
                    <th>Buy Qty</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (isset($_POST['search'])) {
                      $sdata = mysqli_real_escape_string($con, $_POST['searchdata']);
                      $ret = mysqli_query($con, "SELECT * FROM tblmedicine WHERE MedicineName LIKE '%$sdata%'");
                      $num = mysqli_num_rows($ret);

                      if ($num > 0) {
                          $cnt = 1;
                          while ($row = mysqli_fetch_array($ret)) {
                              $mid = $row['ID'];
                              $totalStock = $row['Quantity'];

                              // SOLD = only checked-out items
                              $soldQ = mysqli_query($con, "SELECT COALESCE(SUM(ProductQty), 0) AS sold FROM tblcart WHERE ProductId='$mid' AND IsCheckOut=1");
                              $soldR = mysqli_fetch_assoc($soldQ);
                              $sold = $soldR['sold'];

                              $remaining = $totalStock - $sold;
                  ?>
                  <tr>
                    <form method="post">
                      <input type="hidden" name="mid" value="<?php echo $mid; ?>">
                      <td><?php echo $cnt++; ?></td>
                      <td><?php echo htmlspecialchars($row['MedicineCompany']); ?></td>
                      <td><strong><?php echo htmlspecialchars($row['MedicineName']); ?></strong></td>
                      <td><span class="badge badge-info"><?php echo $row['MedicineBatchno']; ?></span></td>
                      <td><?php echo $totalStock; ?></td>
                      <td><span class="text-danger font-weight-bold"><?php echo $sold; ?></span></td>
                      <td>
                        <span class="badge badge-success font-weight-bold" style="font-size:1.1em;">
                          <?php echo $remaining; ?>
                        </span>
                      </td>
                      <td>
                        <input type="number" name="pqty" value="1" min="1" max="<?php echo $remaining; ?>" 
                               class="form-control form-control-sm" style="width:70px;" required>
                      </td>
                      <td>
                        <button type="submit" name="cart" class="btn btn-sm btn-success">
                          <i class="fas fa-cart-plus"></i> Add
                        </button>
                      </td>
                    </form>
                  </tr>
                  <?php
                          }
                      } else {
                  ?>
                  <tr>
                    <td colspan="9" class="text-center text-danger py-4">
                      <i class="fas fa-exclamation-triangle fa-2x"></i><br>
                      No medicine found for "<strong><?php echo htmlspecialchars($sdata); ?></strong>"
                    </td>
                  </tr>
                  <?php
                      }
                  }
                  ?>
                </tbody>
              </table>
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
</body>
</html>
<?php  ?>