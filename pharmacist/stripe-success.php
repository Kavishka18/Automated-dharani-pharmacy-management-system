<?php
session_start();
include('includes/dbconnection.php');
require_once('/Applications/XAMPP/xamppfiles/htdocs/pms/vendor/stripe/stripe-php/init.php');

\Stripe\Stripe::setApiKey('sk_test_51S3cGaLyIcVwpOFyS059Bn4NnQMm4IXBtbCrRjODdqvh7O48PHyxglQevBfuOXApug8Oium6OgNpBKJfncEwvZNE00fX9vDRvt');

$session_id = $_GET['session_id'] ?? '';
$custname = $_GET['name'] ?? '';
$custmobile = $_GET['mobile'] ?? '';

if (!$session_id) {
    die("Invalid session.");
}

$session = \Stripe\Checkout\Session::retrieve($session_id);

if ($session->payment_status === 'paid') {
    $billiningnum = mt_rand(100000000, 999999999);
    $pmspid = $_SESSION['pmspid'];

    $query = "UPDATE tblcart SET BillingId='$billiningnum', IsCheckOut=1 WHERE IsCheckOut=0 AND PharmacistId='$pmspid';";
    $query .= "INSERT INTO tblcustomer(BillingNumber, CustomerName, MobileNumber, ModeofPayment) VALUES('$billiningnum', '$custname', '$custmobile', 'card');";

    if (mysqli_multi_query($con, $query)) {
        $_SESSION['invoiceid'] = $billiningnum;
        header("Location: invoice.php");
        exit;
    }
}
die("Payment failed.");
?>