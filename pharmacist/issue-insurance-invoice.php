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
$pharma = mysqli_fetch_array(mysqli_query($con, "SELECT FullName FROM tblpharmacist WHERE ID='$pmspid'"));
$pharmacist_name = $pharma['FullName'] ?? 'Pharmacist';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Issue Insurance Invoice - Dharani PMS</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
<link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
<link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
<style>
    body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .card { border-radius: 25px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.2); }
    .header { background: linear-gradient(87deg, #ff6b6b, #ee5a52) !important; }
    .btn-generate {
        background: linear-gradient(87deg, #11998e, #38ef7d);
        border: none;
        padding: 18px 50px;
        font-size: 1.4rem;
        font-weight: 700;
        border-radius: 50px;
        box-shadow: 0 15px 35px rgba(56, 239, 125, 0.4);
        transition: all 0.3s;
    }
    .btn-generate:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(56, 239, 125, 0.6); }
    .medicine-item {
        background: white;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: 0.3s;
    }
    .medicine-item:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
    .form-control { border-radius: 15px; }
</style>
</head>
<body class="">
<?php include_once('includes/navbar.php'); ?>
<div class="main-content">
<?php include_once('includes/sidebar.php'); ?>

<div class="header pb-8 pt-5 pt-md-8">
<div class="container-fluid">
<div class="header-body">
<div class="row align-items-center text-white">
<div class="col">
<h1 class="display-2">Issue Insurance Invoice</h1>
<p class="lead">Professional invoice for insurance claim</p>
</div>
<div class="col-auto">
<div class="avatar avatar-xl">
<img src="../assets/img/brand/provider.png" class="rounded-circle shadow-lg">
</div>
</div>
</div>
</div>
</div>
</div>

<div class="container-fluid mt--7">
<div class="row justify-content-center">
<div class="col-xl-10">
<div class="card">
<div class="card-body p-5">

<form method="post" action="generate-insurance-invoice-pdf.php" target="_blank" id="invoiceForm">

<div class="row">
<div class="col-md-6">
<div class="form-group">
<label class="font-weight-bold">Customer Name</label>
<input type="text" name="customer_name" class="form-control form-control-lg" required placeholder="Enter full name">
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<label class="font-weight-bold">Mobile Number</label>
<input type="text" name="mobile" class="form-control form-control-lg" required placeholder="077XXXXXXX">
</div>
</div>
</div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<label class="font-weight-bold">Insurance Provider</label>
<select name="insurance_provider" class="form-control form-control-lg" required>
<option value="">Select Provider</option>
<option>Sri Lanka Insurance (SLIC)</option>
<option>Ceylinco Insurance</option>
<option>Janashakthi Insurance</option>
<option>Union Assurance</option>
<!-- <option>Allianz Insurance</option> -->
</select>
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<label class="font-weight-bold">Invoice Date</label>
<input type="date" name="invoice_date" class="form-control form-control-lg" value="<?php echo date('Y-m-d'); ?>" required>
</div>
</div>
</div>

<div class="form-group">
<label class="font-weight-bold">Invoice Number</label>
<input type="text" name="invoice_no" class="form-control form-control-lg" value="INS-<?php echo date('Ymd-His'); ?>" readonly style="background:#f0f8ff; font-weight:bold; color:#1171ef;">
</div>

<hr style="border: 3px solid #667eea; border-radius: 5px; margin: 40px 0;">

<h3 class="text-center text-primary mb-4">Medicine List</h3>
<div id="medicine-list">
<div class="medicine-item">
<div class="row align-items-center">
<div class="col-md-5">
<input type="text" name="medicine[]" class="form-control form-control-lg" placeholder="Medicine Name" required>
</div>
<div class="col-md-3">
<input type="number" step="0.01" name="price[]" class="form-control form-control-lg" placeholder="Price" required>
</div>
<div class="col-md-3">
<input type="number" name="qty[]" class="form-control form-control-lg" value="1" required>
</div>
<div class="col-md-1">
<button type="button" class="btn btn-danger btn-lg rounded-circle remove-med">X</button>
</div>
</div>
</div>
</div>

<button type="button" class="btn btn-info btn-lg rounded-pill mb-4" onclick="addMedicine()">
Add Medicine
</button>

<div class="text-center">
<button type="submit" name="generate_pdf" class="btn btn-generate text-white">
GENERATE & DOWNLOAD PDF
</button>
</div>

</form>

</div>
</div>

<div class="text-center mt-5">
<div class="card bg-gradient-success text-white p-5 rounded-xl">
<h2>PDF will auto-download</h2>
<!-- <p class="lead">After download → You will be redirected to Dashboard</p> -->
</div>
</div>

</div>
</div>
</div>
</div>

<script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
<script>
function addMedicine() {
    const list = document.getElementById('medicine-list');
    const item = document.createElement('div');
    item.className = 'medicine-item';
    item.innerHTML = `
        <div class="row align-items-center">
            <div class="col-md-5">
                <input type="text" name="medicine[]" class="form-control form-control-lg" placeholder="Medicine Name" required>
            </div>
            <div class="col-md-3">
                <input type="number" step="0.01" name="price[]" class="form-control form-control-lg" placeholder="Price" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="qty[]" class="form-control form-control-lg" value="1" required>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-lg rounded-circle remove-med">X</button>
            </div>
        </div>
    `;
    list.appendChild(item);
}

document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('remove-med')) {
        e.target.closest('.medicine-item').remove();
    }
});

// AUTO REDIRECT AFTER PDF DOWNLOAD
document.getElementById('invoiceForm').addEventListener('submit', function() {
    setTimeout(function() {
        window.location.href = 'dashboard.php';
    }, 3000);
});
</script>
</body>
</html>
<?php ob_end_flush(); ?>