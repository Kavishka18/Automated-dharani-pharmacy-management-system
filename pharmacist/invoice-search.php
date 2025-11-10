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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dharani Pharmacy - Search Invoice</title>
    
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
            border-radius: 0 0 2.5rem 2.5rem;
            overflow: hidden;
        }
        .header::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: url('https://images.unsplash.com/photo-1580281773044-0b5577c7a2b2?ixlib=rb-4.0.3&auto=format&fit=crop&q=80') center/cover;
            opacity: 0.2;
        }
        .card {
            border: none; border-radius: 1.8rem; 
            box-shadow: 0 25px 60px rgba(0,0,0,0.2);
            backdrop-filter: blur(15px);
            background: rgba(255,255,255,0.95);
            overflow: hidden;
        }
        .search-box {
            position: relative;
            max-width: 500px;
            margin: 0 auto;
        }
        .search-box input {
            padding-left: 50px;
            border-radius: 50px;
            border: 3px solid #e2e8f0;
            font-size: 1.1rem;
            transition: all 0.4s ease;
        }
        .search-box input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.3rem rgba(102,126,234,0.3);
            transform: translateY(-3px);
        }
        .search-box i {
            position: absolute;
            left: 18px;
            top: 14px;
            color: #667eea;
            font-size: 1.3rem;
        }
        .btn-search {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 50px;
            padding: 12px 35px;
            font-weight: 600;
            box-shadow: 0 10px 30px rgba(102,126,234,0.4);
            transition: all 0.4s ease;
        }
        .btn-search:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(102,126,234,0.5);
        }
        .refresh-btn {
            background: rgba(102,126,234,0.1);
            border: 2px solid #667eea;
            color: #667eea;
            width: 50px; height: 50px;
            border-radius: 50%;
            font-size: 1.3rem;
            transition: all 0.4s ease;
        }
        .refresh-btn:hover {
            background: #667eea;
            color: white;
            transform: rotate(360deg);
        }
        #receipt {
            max-width: 420px;
            margin: 30px auto;
            padding: 30px;
            border: 3px dashed #667eea;
            border-radius: 1.5rem;
            background: white;
            box-shadow: 0 20px 50px rgba(102,126,234,0.2);
            font-family: 'Courier New', monospace;
            animation: fadeIn 0.8s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .receipt-header {
            text-align: center;
            background: linear-gradient(45deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .line {
            border-top: 2px dashed #667eea;
            margin: 15px 0;
        }
        .bold { font-weight: 700; }
        .text-right { text-align: right; }
        .grand-total {
            background: linear-gradient(45deg, #11998e, #38ef7d);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.5rem !important;
        }
        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(45deg, #e74c3c, #c0392b);
            color: white;
            width: 70px; height: 70px;
            border-radius: 50%;
            font-size: 2rem;
            box-shadow: 0 15px 35px rgba(231,76,60,0.5);
            transition: all 0.4s ease;
            z-index: 999;
        }
        .print-btn:hover {
            transform: scale(1.15);
            box-shadow: 0 25px 50px rgba(231,76,60,0.7);
        }
        .time-info {
            background: rgba(102,126,234,0.1);
            border-radius: 1rem;
            padding: 1rem;
            border-left: 5px solid #667eea;
            margin: 20px 0;
        }
        @media print {
            body * { visibility: hidden; }
            #receipt, #receipt * { visibility: visible; }
            #receipt { 
                position: absolute; left: 50%; top: 50%; 
                transform: translate(-50%, -50%); 
                width: 100%; max-width: 380px;
                border: 2px dashed #000;
            }
            .no-print, .print-btn { display: none !important; }
        }
        @media (max-width: 768px) {
            #receipt { margin: 20px 10px; padding: 20px; }
            .print-btn { bottom: 20px; right: 20px; width: 60px; height: 60px; font-size: 1.8rem; }
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
                        <i class="fas fa-search-dollar mr-4"></i>
                        Search Invoice
                    </h1>
                    <p class="lead">Find any bill by number, mobile or customer name</p>
                </div>
            </div>
        </div>

        <div class="container-fluid mt--8">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="card">
                        <div class="card-header text-center bg-gradient-primary text-white">
                            <h2 class="mb-0">
                                <i class="fas fa-receipt mr-3"></i>
                                Invoice Lookup System
                            </h2>
                            <div class="time-info mt-3">
                                <small class="text-white">
                                    <i class="fas fa-clock"></i>
                                    Sri Lanka Time: <?php echo date('d M Y, h:i A'); ?> (UTC+5:30)
                                </small>
                            </div>
                        </div>

                        <div class="card-body p-5">
                            <?php if ($msg): ?>
                                <div class="alert alert-danger alert-dismissible fade show text-center">
                                    <i class="fas fa-exclamation-triangle"></i> <?php echo $msg; ?>
                                </div>
                            <?php endif; ?>

                            <form method="post" id="searchForm" class="no-print">
                                <div class="search-box">
                                    <i class="fas fa-search"></i>
                                    <input type="text" name="searchdata" id="searchInput" class="form-control form-control-lg"
                                           placeholder="Enter Billing # / Mobile / Customer Name" 
                                           <?php echo $searchResult ? 'value="' . htmlspecialchars($_POST['searchdata']) . '"' : ''; ?> required>
                                </div>
                                <div class="text-center mt-4">
                                    <button type="submit" name="search" class="btn btn-search btn-lg text-white">
                                        <i class="fas fa-search mr-2"></i> Search Invoice
                                    </button>
                                    <button type="button" id="refreshBtn" class="btn refresh-btn ml-3">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- RECEIPT PREVIEW -->
                    <div id="receipt" style="display: <?php echo $searchResult ? 'block' : 'none'; ?>;">
                        <div class="text-center">
                            <h2 class="receipt-header">DHARANI PHARMACY</h2>
                            <p>
                                <small>
                                    Gampaha, Sri Lanka<br>
                                    Tel: +94 33 222 1234<br>
                                    Email: dharani@gmail.com
                                </small>
                            </p>
                            <div class="line"></div>
                            <h4><strong>INVOICE</strong></h4>
                        </div>

                        <table width="100%" style="font-size:15px;">
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

                        <table width="100%" style="font-size:14px;">
                            <thead>
                                <tr style="background:#f8f9ff;">
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

                        <table width="100%" style="font-size:18px;">
                            <tr>
                                <td class="bold grand-total">GRAND TOTAL</td>
                                <td class="text-right bold grand-total">Rs. <?php echo number_format($gtotal, 2); ?></td>
                            </tr>
                        </table>
                        <div class="line"></div>

                        <div class="text-center mt-4">
                            <p><em>Thank you for your purchase!</em></p>
                            <p><small>Come again • Powered by Dharani PMS</small></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Floating Print Button -->
            <?php if ($searchResult): ?>
            <button onclick="window.print()" class="btn print-btn no-print">
                <i class="fas fa-print"></i>
            </button>
            <?php endif; ?>

            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>

    <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
   <script>
    // Refresh Button - Clear form & hide receipt
    document.getElementById('refreshBtn').addEventListener('click', function() {
        document.getElementById('searchForm').reset();
        document.getElementById('searchInput').focus();
        document.getElementById('receipt').style.display = 'none';
        
        // Remove any alert message
        const alert = document.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    });

    // Auto focus on search box when page loads
    window.onload = function() {
        document.getElementById('searchInput').focus();
    };
</script>
</body>
</html>