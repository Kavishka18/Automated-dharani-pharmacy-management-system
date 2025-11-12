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

// LANKA QR PAYMENT (CARD)
if (isset($_POST['submit']) && $_POST['modepayment'] == 'card') {
    $custname      = mysqli_real_escape_string($con, $_POST['customername']);
    $custmobilenum = mysqli_real_escape_string($con, $_POST['mobilenumber']);

    // Calculate total
    $totalQ = mysqli_query($con, "
        SELECT SUM(ct.ProductQty * m.Priceperunit) AS total
        FROM tblcart ct JOIN tblmedicine m ON ct.ProductId = m.ID
        WHERE ct.IsCheckOut = 0 AND ct.PharmacistId = '$pmspid'
    ");
    $totalRow    = mysqli_fetch_assoc($totalQ);
    $totalAmount = $totalRow['total'] ?? 0;

    if ($totalAmount <= 0) {
        echo "<script>alert('Cart is empty!'); window.location='cart.php';</script>";
        exit;
    }

    // Generate unique Billing ID
    $billingId = mt_rand(100000000, 999999999);
    $ref = "INV" . $billingId;

    // === LANKA QR STRING (SLIPS Standard) ===
    $merchantName = "Dharani Pharmacy";
    $merchantCity = "Gampaha";
    $amountStr    = number_format($totalAmount, 2, '.', '');

    $lankaqr  = "000201"; // Payload Format
    $lankaqr .= "010211"; // Dynamic QR
    $lankaqr .= "26" . sprintf("%02d", strlen("0010A000000727")) . "0010A000000727";
    $lankaqr .= "01" . sprintf("%02d", strlen($merchantName)) . $merchantName;
    $lankaqr .= "52045929"; // MCC
    $lankaqr .= "5303144";  // LKR
    $lankaqr .= "54" . sprintf("%02d", strlen($amountStr)) . $amountStr;
    $lankaqr .= "58" . sprintf("%02d", strlen("LK")) . "LK";
    $lankaqr .= "59" . sprintf("%02d", strlen($merchantName)) . $merchantName;
    $lankaqr .= "60" . sprintf("%02d", strlen($merchantCity)) . $merchantCity;
    $lankaqr .= "62" . sprintf("%02d", strlen("07" . sprintf("%02d", strlen($ref)) . $ref));
    $lankaqr .= "07" . sprintf("%02d", strlen($ref)) . $ref;
    $lankaqr .= "6304XXXX"; // CRC placeholder

    // === Calculate CRC32B ===
    $crc = strtoupper(hash('crc32b', $lankaqr));
    $lankaqr = substr($lankaqr, 0, -8) . $crc;

    // === Generate QR Code ===
    $qrLibPath = '../vendor/phpqrcode/qrlib.php';
    if (!file_exists($qrLibPath)) {
        die("QR Library not found at: $qrLibPath");
    }
    require_once $qrLibPath;

    $qrFolder = '../qr_temp/';
    if (!is_dir($qrFolder)) mkdir($qrFolder, 0755, true);
    if (!is_writable($qrFolder)) {
        @chmod($qrFolder, 0777);
        if (!is_writable($qrFolder)) {
            die("<h3>qr_temp not writable!</h3><code>sudo chmod -R 777 /Applications/XAMPP/xamppfiles/htdocs/pms/qr_temp</code>");
        }
    }

    $qrFile = $qrFolder . $billingId . '.png';
    QRcode::png($lankaqr, $qrFile, QR_ECLEVEL_L, 8);

    // Save session
    $_SESSION['qr_pending'] = [
        'billingid' => $billingId,
        'amount'    => $totalAmount,
        'name'      => $custname,
        'mobile'    => $custmobilenum
    ];
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lanka QR Payment - Dharani Pharmacy</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
        <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                margin: 0;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .qr-container {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border-radius: 2rem;
                box-shadow: 0 30px 80px rgba(0, 0, 0, 0.3);
                padding: 3rem;
                max-width: 420px;
                width: 90%;
                text-align: center;
                position: relative;
                overflow: hidden;
                animation: fadeIn 0.6s ease-out;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .qr-container::before {
                content: '';
                position: absolute;
                top: -50%;
                left: -50%;
                width: 200%;
                height: 200%;
                background: radial-gradient(circle, rgba(102,126,234,0.1) 0%, transparent 70%);
                animation: pulse 8s infinite;
                z-index: 0;
            }
            @keyframes pulse {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.1); }
            }
            .header-title {
                background: linear-gradient(135deg, #667eea, #764ba2);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                font-weight: 700;
                font-size: 2.2rem;
                margin-bottom: 1rem;
                position: relative;
                z-index: 1;
            }
            .amount-display {
                font-size: 2.8rem;
                font-weight: 800;
                color: #2d3748;
                margin: 1rem 0;
                text-shadow: 0 4px 10px rgba(0,0,0,0.1);
                animation: bounce 2s infinite;
                position: relative;
                z-index: 1;
            }
            @keyframes bounce {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-8px); }
            }
            .info-row {
                display: flex;
                justify-content: space-between;
                padding: 0.8rem 1rem;
                background: #f8f9ff;
                border-radius: 1rem;
                margin: 0.6rem 0;
                font-size: 1rem;
                color: #4a5568;
                position: relative;
                z-index: 1;
            }
            .info-label {
                font-weight: 600;
                color: #667eea;
            }
            .qr-image {
                width: 260px;
                height: 260px;
                margin: 2rem auto;
                padding: 1rem;
                background: white;
                border-radius: 1.5rem;
                box-shadow: 0 15px 35px rgba(0,0,0,0.15);
                border: 6px solid #667eea;
                animation: float 3s ease-in-out infinite;
                position: relative;
                z-index: 1;
            }
            @keyframes float {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-10px); }
            }
            .btn-confirm {
                background: linear-gradient(45deg, #667eea, #764ba2);
                color: white;
                border: none;
                border-radius: 50px;
                padding: 1rem 3rem;
                font-weight: 600;
                font-size: 1.2rem;
                cursor: pointer;
                box-shadow: 0 15px 35px rgba(102,126,234,0.4);
                transition: all 0.4s ease;
                margin-top: 1.5rem;
                position: relative;
                z-index: 1;
            }
            .btn-confirm:hover {
                transform: translateY(-5px);
                box-shadow: 0 25px 50px rgba(102,126,234,0.5);
            }
            .btn-confirm:active {
                transform: translateY(-2px);
            }
            .note {
                font-size: 0.9rem;
                color: #718096;
                margin-top: 1.5rem;
                line-height: 1.5;
                position: relative;
                z-index: 1;
            }
            .bank-icons {
                display: flex;
                justify-content: center;
                gap: 1rem;
                margin: 1rem 0;
                flex-wrap: wrap;
            }
            .bank-icon {
                width: 40px;
                height: 40px;
                background: #e2e8f0;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.2rem;
                color: #4a5568;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            }
            .time-info {
                background: rgba(102,126,234,0.1);
                border-radius: 1rem;
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
                color: #667eea;
                margin-top: 1rem;
                display: inline-block;
            }
        </style>
    </head>
    <body>
        <div class="qr-container">
            <h1 class="header-title">
                Lanka QR Payment
            </h1>

            <div class="amount-display">
                Rs. <?=number_format($totalAmount, 2)?>
            </div>

            <div class="info-row">
                <span class="info-label">Customer:</span>
                <span><?=htmlspecialchars($custname)?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Mobile:</span>
                <span><?=htmlspecialchars($custmobilenum)?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Ref No:</span>
                <span><strong><?=$ref?></strong></span>
            </div>

            <div class="qr-image">
                <img src="../qr_temp/<?=$billingId?>.png" alt="Lanka QR" style="width:100%;height:100%;border-radius:1rem;">
            </div>

            <form method="post" action="card-webhook.php">
                <input type="hidden" name="billingid" value="<?=$billingId?>">
                <button type="submit" class="btn-confirm">
                    Payment Received
                </button>
            </form>

            <div class="bank-icons">
                <div class="bank-icon" title="Commercial Bank"><i class="fas fa-university"></i></div>
                <div class="bank-icon" title="HNB"><i class="fas fa-building"></i></div>
                <div class="bank-icon" title="BOC"><i class="fas fa-landmark"></i></div>
                <div class="bank-icon" title="People's Bank"><i class="fas fa-users"></i></div>
            </div>

            <p class="note">
                <strong>Customer can scan with any bank app:</strong><br>
                Commercial Bank • HNB • BOC • People's Bank • Sampath • NSB • NDB
            </p>

            <div class="time-info">
                <?=date('d M Y, h:i A')?> (Sri Lanka Time)
            </div>
        </div>

        <script>
            // Auto-refresh every 30 sec (optional)
            setTimeout(() => location.reload(), 30000);
        </script>
    </body>
    </html>
    <?php
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dharani Pharmacy - Cart</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
<style>
body {font-family: 'Poppins', sans-serif;background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);min-height: 100vh;}
.header {background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);position: relative;border-radius: 0 0 2rem 2rem;overflow: hidden;}
.header::before {content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;background: url('https://images.unsplash.com/photo-1585435469940-5a7abf5e8df6?ixlib=rb-4.0.3&auto=format&fit=crop&q=80') center/cover;opacity: 0.2;}
.card {border: none; border-radius: 1.5rem;box-shadow: 0 20px 50px rgba(0,0,0,0.2);backdrop-filter: blur(15px);background: rgba(255,255,255,0.95);overflow: hidden;}
.form-control {border-radius: 1rem;padding: 0.8rem 1.2rem;border: 2px solid #e2e8f0;transition: all 0.3s ease;}
.form-control:focus {border-color: #667eea;box-shadow: 0 0 0 0.2rem rgba(102,126,234,0.25);}
.btn-submit {background: linear-gradient(45deg, #667eea, #764ba2);border: none;border-radius: 50px;padding: 12px 40px;font-weight: 600;color: white;box-shadow: 0 10px 30px rgba(102,126,234,0.4);}
.btn-submit:hover {transform: translateY(-5px);}
.table {background: white;border-radius: 1rem;overflow: hidden;box-shadow: 0 10px 30px rgba(0,0,0,0.1);}
.table th {background: linear-gradient(45deg, #667eea, #764ba2);color: white;}
.table tr:hover {background: #f8f9ff;}
.grand-total {background: linear-gradient(45deg, #11998e, #38ef7d) !important;color: white !important;}
.delete-btn {color: #e74c3c;}
.delete-btn:hover {color: #c0392b;}
</style>
</head>
<body class="">
<?php include_once('includes/navbar.php'); ?>
<div class="main-content">
<?php include_once('includes/sidebar.php'); ?>
<div class="header pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body text-center text-white">
            <h1 class="display-3 font-weight-bold"><i class="fas fa-shopping-cart mr-4"></i>Your Cart</h1>
            <p class="lead">Review items and complete checkout</p>
        </div>
    </div>
</div>
<div class="container-fluid mt--8">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card">
                <div class="card-header text-center bg-gradient-primary text-white">
                    <h2 class="mb-0"><i class="fas fa-user mr-3"></i> Customer Details</h2>
                </div>
                <div class="card-body p-5">
                    <form method="post" id="payment-form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-primary"><i class="fas fa-user"></i> Customer Name</label>
                                    <input type="text" name="customername" class="form-control" required placeholder="Enter full name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-bold text-primary"><i class="fas fa-phone"></i> Mobile Number</label>
                                    <input type="text" name="mobilenumber" class="form-control" required maxlength="10" pattern="[0-9]+" placeholder="077xxxxxxx">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-4">
                            <label class="font-weight-bold text-primary"><i class="fas fa-credit-card"></i> Payment Method</label>
                            <div class="d-flex justify-content-center">
                                <div class="custom-control custom-radio mr-5">
                                    <input type="radio" id="cash" name="modepayment" value="cash" class="custom-control-input" checked>
                                    <label class="custom-control-label font-weight-bold" for="cash"><i class="fas fa-money-bill-wave text-success"></i> Cash</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="card" name="modepayment" value="card" class="custom-control-input">
                                    <label class="custom-control-label font-weight-bold" for="card"><i class="fas fa-qrcode text-info"></i> Lanka QR</label>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-5">
                            <button type="submit" name="submit" class="btn btn-submit btn-lg">
                                <span id="btn-text"><i class="fas fa-paper-plane mr-2"></i> Complete Checkout</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-gradient-info text-white text-center">
                    <h3 class="mb-0"><i class="fas fa-prescription-bottle-alt mr-3"></i> Medicines in Cart</h3>
                </div>
                <div class="table-responsive p-4">
                    <table class="table align-items-center table-hover">
                        <thead>
                            <tr>
                                <th><strong>#</strong></th>
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
                            <td><strong class="text-primary"><?php echo $cnt++; ?></strong></td>
                            <td><strong><?php echo htmlspecialchars($row['MedicineName']); ?></strong></td>
                            <td><span class="badge badge-info"><?php echo $row['ProductQty']; ?></span></td>
                            <td>Rs. <?php echo number_format($row['Priceperunit'], 2); ?></td>
                            <td><strong class="text-success">Rs. <?php echo number_format($total, 2); ?></strong></td>
                            <td><a href="cart.php?delid=<?php echo $row['ID']; ?>" class="delete-btn" onclick="return confirm('Remove?')"><i class="fas fa-trash-alt"></i> Remove</a></td>
                        </tr>
                        <?php endwhile; ?>
                        <tr class="grand-total">
                            <th colspan="4" class="text-right"><h4>GRAND TOTAL</h4></th>
                            <th colspan="2"><h3>Rs. <?php echo number_format($gtotal, 2); ?></h3></th>
                        </tr>
                        <?php else: ?>
                        <tr><td colspan="6" class="text-center text-muted py-5">Cart is empty</td></tr>
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