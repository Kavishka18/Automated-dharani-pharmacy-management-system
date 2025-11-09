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
    <meta charset="utf-8" />
    <title>Invoice Search - Dharani PMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8f9fe; }
        .invoice-card { 
            border: 3px solid #5e72e4; 
            border-radius: 22px; 
            overflow: hidden; 
            box-shadow: 0 25px 50px rgba(94,114,228,0.25); 
            transition: all 0.4s;
        }
        .invoice-card:hover { transform: translateY(-10px); box-shadow: 0 35px 70px rgba(94,114,228,0.35); }
        .invoice-header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            color: white; 
            padding: 40px 30px; 
            text-align: center; 
        }
        .logo-img { 
            height: 100px; 
            border-radius: 20px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.4); 
            border: 4px solid white;
        }
        .table th { background: #f1f3ff !important; font-weight: 700; color: #5e72e4; }
        .total-row { 
            background: linear-gradient(87deg, #11cdef, #1171ef) !important; 
            color: white !important; 
            font-size: 1.6rem; 
            font-weight: 800;
        }
        .btn-download { 
            background: linear-gradient(87deg, #fb6340, #f5365c); 
            border: none; 
            font-weight: 700; 
            padding: 12px 30px;
            box-shadow: 0 10px 20px rgba(251,99,64,0.4);
        }
        .btn-download:hover { 
            transform: translateY(-3px); 
            box-shadow: 0 15px 30px rgba(251,99,64,0.6);
        }
        .btn-refresh { 
            background: linear-gradient(87deg, #2dce89, #1e7e34); 
            color: white;
        }
        .btn-print { 
            background: linear-gradient(87deg, #172b4d, #1a1f36);
        }
        @media print {
            .no-print, .card { box-shadow: none !important; border: none !important; }
            body { background: white !important; }
            .invoice-card { border: 2px solid #000 !important; }
        }
    </style>
</head>
<body>
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- Header -->
        <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h1 class="display-2 text-white mb-0">Invoice Search</h1>
                            <p class="text-white opacity-9">Search by Billing Number or Mobile Number</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt--7">
            <div class="row justify-content-center">
                <div class="col-xl-11">

                    <!-- Search Form + Refresh -->
                    <div class="card shadow-lg border-0 mb-5">
                        <div class="card-body p-5">
                            <form method="post" action="" id="searchForm">
                                <div class="row align-items-end">
                                    <div class="col-md-7">
                                        <label class="form-control-label font-weight-bold">
                                            <i class="fas fa-search text-primary"></i> Search Invoice
                                        </label>
                                        <input type="text" name="searchdata" id="searchInput" class="form-control form-control-lg" 
                                               placeholder="Enter Billing Number or Mobile Number" 
                                               value="<?php echo isset($_POST['searchdata']) ? htmlspecialchars($_POST['searchdata']) : ''; ?>" 
                                               required autofocus>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" name="search" class="btn btn-primary btn-lg btn-block">
                                            <i class="fas fa-search"></i> Search
                                        </button>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="button" onclick="clearSearch()" class="btn btn-refresh btn-lg btn-block">
                                            <i class="fas fa-sync-alt"></i> New Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php if(isset($_POST['search'])) { 
                        $sdata = mysqli_real_escape_string($con, $_POST['searchdata']);
                        $sql = "SELECT DISTINCT tblcustomer.* FROM tblcart 
                                JOIN tblcustomer ON tblcustomer.BillingNumber = tblcart.BillingId 
                                WHERE tblcustomer.BillingNumber='$sdata' OR tblcustomer.MobileNumber='$sdata'";
                        $query = mysqli_query($con, $sql);
                        $num = mysqli_num_rows($query);
                        
                        if($num > 0) {
                            $customer = mysqli_fetch_array($query);
                    ?>
                    <!-- INVOICE -->
                    <div class="card invoice-card">
                        <div class="invoice-header">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    <img src="../assets/img/brand/provider.png" class="logo-img" alt="Dharani Pharmacy">
                                </div>
                                <div class="col-6 text-center">
                                    <h1 class="mb-1 text-white">DHARANI PHARMACY</h1>
                                    <p class="mb-0">Main Street, Gampaha, Sri Lanka</p>
                                    <p class="mb-0">Tel: 033-222-1234 • info@dharanipharmacy.lk</p>
                                    <h2 class="mt-4 mb-0">TAX INVOICE</h2>
                                </div>
                                <div class="col-3 text-right">
                                    <h2 class="mb-0">#<?php echo $customer['BillingNumber']; ?></h2>
                                    <p class="mb-0"><?php echo date('d M Y', strtotime($customer['BillingDate'])); ?></p>
                                    <p class="mb-0"><?php echo date('h:i A', strtotime($customer['BillingDate'])); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-5">
                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <h4 class="text-primary">Customer Details</h4>
                                    <p><strong>Name:</strong> <?php echo $customer['CustomerName']; ?></p>
                                    <p><strong>Mobile:</strong> <?php echo $customer['MobileNumber']; ?></p>
                                    <p><strong>Payment Mode:</strong> <?php echo $customer['ModeofPayment']; ?></p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <h4 class="text-primary">Invoice Summary</h4>
                                    <p><strong>Date:</strong> <?php echo date('d F Y', strtotime($customer['BillingDate'])); ?></p>
                                    <p><strong>Time:</strong> <?php echo date('h:i A', strtotime($customer['BillingDate'])); ?></p>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered align-items-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Medicine Name</th>
                                            <th>Qty</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $med_query = mysqli_query($con, "SELECT m.MedicineName, c.ProductQty, m.Priceperunit 
                                            FROM tblcart c JOIN tblmedicine m ON c.ProductId = m.ID 
                                            WHERE c.BillingId = '{$customer['BillingNumber']}'");
                                        $cnt = 1;
                                        $gtotal = 0;
                                        while($med = mysqli_fetch_array($med_query)) {
                                            $total = $med['ProductQty'] * $med['Priceperunit'];
                                            $gtotal += $total;
                                        ?>
                                        <tr>
                                            <td><?php echo $cnt++; ?></td>
                                            <td><strong><?php echo $med['MedicineName']; ?></strong></td>
                                            <td><?php echo $med['ProductQty']; ?></td>
                                            <td>Rs. <?php echo number_format($med['Priceperunit'], 2); ?></td>
                                            <td>Rs. <?php echo number_format($total, 2); ?></td>
                                        </tr>
                                        <?php } ?>
                                        <tr class="total-row">
                                            <td colspan="4" class="text-right pr-5"><strong>GRAND TOTAL</strong></td>
                                            <td><strong>Rs. <?php echo number_format($gtotal, 2); ?></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row mt-5 no-print">
                                <div class="col-md-6">
                                    <p class="lead text-success"><em>Thank you for choosing Dharani Pharmacy!</em></p>
                                    <p class="text-muted">Your health is our priority</p>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button onclick="window.print()" class="btn btn-print text-white btn-lg mr-3">
                                        <i class="fas fa-print"></i> Print Invoice
                                    </button>
                                    <a href="generate-pharmacy-invoice.php?bill=<?php echo $customer['BillingNumber']; ?>" 
                                       class="btn btn-download text-white btn-lg">
                                        <i class="fas fa-download"></i> Download PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php } else { ?>
                        <div class="alert alert-danger text-center shadow-lg">
                            <h3>No invoice found for "<strong><?php echo htmlspecialchars($sdata); ?></strong>"</h3>
                            <p>Please check the billing number or mobile number and try again.</p>
                        </div>
                    <?php } } ?>

                </div>
            </div>
        </div>
    </div>

    <script>
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            document.getElementById('searchForm').submit();
        }
    </script>

    <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>
</body>
</html>
<?php } ?>