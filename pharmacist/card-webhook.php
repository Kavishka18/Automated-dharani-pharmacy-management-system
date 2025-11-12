<?php
ob_start();
session_start();
include('includes/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['billingid'])) {
    die('Invalid access');
}

$billingId = mysqli_real_escape_string($con, $_POST['billingid']);

// === FIX: CAST BOTH TO STRING ===
if (!isset($_SESSION['qr_pending']) || (string)$_SESSION['qr_pending']['billingid'] !== (string)$billingId) {
    echo "<script>alert('Invalid transaction! Please try checkout again.'); window.location='cart.php';</script>";
    exit;
}

$data = $_SESSION['qr_pending'];
unset($_SESSION['qr_pending']);

$pmspid = $_SESSION['pmspid'] ?? null;
if (!$pmspid) {
    echo "<script>alert('Pharmacist session lost!'); window.location='logout.php';</script>";
    exit;
}

// Prevent double payment
$check = mysqli_query($con, "SELECT 1 FROM tblcart WHERE BillingId='$billingId' AND IsCheckOut=1 LIMIT 1");
if (mysqli_fetch_row($check)) {
    echo "<script>alert('Payment already processed!'); window.location='cart.php';</script>";
    exit;
}

$query  = "UPDATE tblcart SET BillingId='$billingId', IsCheckOut=1, SaleDate=NOW()
           WHERE IsCheckOut=0 AND PharmacistId='$pmspid';";
$query .= "INSERT INTO tblcustomer(BillingNumber, CustomerName, MobileNumber, ModeofPayment)
           VALUES('$billingId', '{$data['name']}', '{$data['mobile']}', 'card');";

if (mysqli_multi_query($con, $query)) {
    $_SESSION['invoiceid'] = $billingId;
    header("Location: invoice.php");
    exit;
} else {
    echo "<script>alert('Checkout failed! Please try again.'); window.location='cart.php';</script>";
}
?>