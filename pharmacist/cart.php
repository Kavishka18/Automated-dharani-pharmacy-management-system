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
        \Stripe\Stripe::setApiKey('sk_test_51S3cGaLyIcVwpOFyS059Bn4NnQMm4IXBtbCrRjODdqvh7O48PHyxglQevBfuOXApug8Oium6OgNpBKJfncEwvZNE00fX9vDRvt');
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dharani Pharmacy - Cart</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            border-radius: 0 0 2rem 2rem;
            overflow: hidden;
        }
        .header::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: url('https://images.unsplash.com/photo-1585435469940-5a7abf5e8df6?ixlib=rb-4.0.3&auto=format&fit=crop&q=80') center/cover;
            opacity: 0.2;
        }
        .card {
            border: none; border-radius: 1.5rem; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
            backdrop-filter: blur(15px);
            background: rgba(255,255,255,0.95);
            overflow: hidden;
        }
        .cart-icon {
            font-size: 4rem;
            color: #667eea;
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        .form-control {
            border-radius: 1rem;
            padding: 0.8rem 1.2rem;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);
            transform: translateY(-3px);
        }
        .custom-radio .custom-control-label::before {
            border-radius: 50%;
        }
        .btn-submit {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 50px;
            padding: 12px 40px;
            font-weight: 600;
            color: white;
            box-shadow: 0 10px 30px rgba(102,126,234,0.4);
            transition: all 0.4s ease;
        }
        .btn-submit:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(102,126,234,0.5);
        }
        .table {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .table th {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        .table tr:hover {
            background: #f8f9ff;
            transform: scale(1.02);
            transition: all 0.3s ease;
        }
        .grand-total {
            background: linear-gradient(45deg, #11998e, #38ef7d) !important;
            color: white !important;
            font-size: 1.4rem !important;
        }
        .delete-btn {
            color: #e74c3c;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .delete-btn:hover {
            color: #c0392b;
            transform: scale(1.1);
        }
        .empty-cart {
            text-align: center;
            padding: 60px 20px;
            color: #718096;
        }
        .empty-cart i {
            font-size: 5rem;
            color: #e2e8f0;
            margin-bottom: 20px;
        }
        .time-info {
            background: rgba(102,126,234,0.1);
            border-radius: 1rem;
            padding: 1rem;
            border-left: 5px solid #667eea;
        }
        @media (max-width: 768px) {
            .header { min-height: 200px; }
            .card { margin: 1rem; }
        }
    </style>
</head>
<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- Header -->
        <div class="header pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <div class="header-body text-center text-white">
                    <h1 class="display-3 font-weight-bold">
                        <i class="fas fa-shopping-cart cart-icon mr-4"></i>
                        Your Cart
                    </h1>
                    <p class="lead">Review items and complete checkout</p>
                </div>
            </div>
        </div>

        <div class="container-fluid mt--8">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="card">
                        <div class="card-header text-center bg-gradient-primary text-white">
                            <h2 class="mb-0">
                                <i class="fas fa-user mr-3"></i> Customer Details
                            </h2>
                            <div class="time-info mt-3">
                                <small>
                                    <i class="fas fa-clock"></i>
                                    Sri Lanka Time: <?php echo date('d M Y, h:i A'); ?> (UTC+5:30)
                                </small>
                            </div>
                        </div>

                        <div class="card-body p-5">
                            <form method="post" id="payment-form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">
                                                <i class="fas fa-user"></i> Customer Name
                                            </label>
                                            <input type="text" name="customername" class="form-control" required 
                                                   placeholder="Enter full name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-primary">
                                                <i class="fas fa-phone"></i> Mobile Number
                                            </label>
                                            <input type="text" name="mobilenumber" class="form-control" required 
                                                   maxlength="10" pattern="[0-9]+" placeholder="077xxxxxxx">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <label class="font-weight-bold text-primary">
                                        <i class="fas fa-credit-card"></i> Payment Method
                                    </label>
                                    <div class="d-flex justify-content-center">
                                        <div class="custom-control custom-radio mr-5">
                                            <input type="radio" id="cash" name="modepayment" value="cash" 
                                                   class="custom-control-input" checked>
                                            <label class="custom-control-label font-weight-bold" for="cash">
                                                <i class="fas fa-money-bill-wave text-success"></i> Cash
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="card" name="modepayment" value="card" 
                                                   class="custom-control-input">
                                            <label class="custom-control-label font-weight-bold" for="card">
                                                <i class="fas fa-credit-card text-info"></i> Card
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-5">
                                    <button type="submit" name="submit" class="btn btn-submit btn-lg">
                                        <span id="btn-text">
                                            <i class="fas fa-paper-plane mr-2"></i>
                                            Complete Checkout
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Cart Items Table -->
                    <div class="card mt-4">
                        <div class="card-header bg-gradient-info text-white text-center">
                            <h3 class="mb-0">
                                <i class="fas fa-prescription-bottle-alt mr-3"></i>
                                Medicines in Cart
                            </h3>
                        </div>

                        <div class="table-responsive p-4">
                            <table class="table align-items-center table-hover">
                                <thead>
                                    <tr>
                                        <th><strong>#</strong></th>
                                        <th><i class="fas fa-capsules"></i> Medicine</th>
                                        <th><i class="fas fa-box"></i> Qty</th>
                                        <th><i class="fas fa-rupee-sign"></i> Price</th>
                                        <th><i class="fas fa-calculator"></i> Total</th>
                                        <th><i class="fas fa-trash"></i> Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = mysqli_query($con, "SELECT c.ID, m.MedicineName, c.ProductQty, m.Priceperunit
                                        FROM tblcart c JOIN tblmedicine m ON c.ProductId = m.ID
                                        WHERE c.IsCheckOut=0 AND c.PharmacistId='$pmspid'");
                                    $cnt = 1;
                                    $gtotal = 0;
                                    if (mysqli_num_rows($ret) > 0):
                                        while ($row = mysqli_fetch_array($ret)):
                                            $total = $row['ProductQty'] * $row['Priceperunit'];
                                            $gtotal += $total;
                                    ?>
                                    <tr>
                                        <td><strong class="text-primary"><?php echo $cnt++; ?></strong></td>
                                        <td><strong><?php echo htmlspecialchars($row['MedicineName']); ?></strong></td>
                                        <td><span class="badge badge-info badge-pill px-3 py-2"><?php echo $row['ProductQty']; ?></span></td>
                                        <td>Rs. <?php echo number_format($row['Priceperunit'], 2); ?></td>
                                        <td><strong class="text-success">Rs. <?php echo number_format($total, 2); ?></strong></td>
                                        <td>
                                            <a href="cart.php?delid=<?php echo $row['ID']; ?>" 
                                               class="delete-btn" onclick="return confirm('Remove this item?')">
                                                <i class="fas fa-trash-alt"></i> Remove
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <tr class="grand-total">
                                        <th colspan="4" class="text-right">
                                            <h4>GRAND TOTAL</h4>
                                        </th>
                                        <th colspan="2">
                                            <h3>Rs. <?php echo number_format($gtotal, 2); ?></h3>
                                        </th>
                                    </tr>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="empty-cart">
                                            <i class="fas fa-shopping-cart"></i><br><br>
                                            <h3>Your cart is empty</h3>
                                            <p class="text-muted">Add medicines to proceed with checkout</p>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
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
    <script>
        document.getElementById('payment-form').addEventListener('submit', function() {
            document.getElementById('btn-text').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
        });
    </script>
</body>
</html>
<?php ob_end_flush(); ?>