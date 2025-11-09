<?php
session_start();
if(strlen($_SESSION['pmsaid']) == 0) {
    header('location:../logout.php');
    exit;
}

require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');
include('includes/dbconnection.php');

$bill = mysqli_real_escape_string($con, $_GET['bill']);
if(empty($bill)) die("Invalid invoice");

$customer = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tblcustomer WHERE BillingNumber='$bill'"));
if(!$customer) die("Invoice not found");

$medicines = mysqli_query($con, "SELECT m.MedicineName, c.ProductQty, m.Priceperunit 
    FROM tblcart c JOIN tblmedicine m ON c.ProductId = m.ID WHERE c.BillingId='$bill'");

// CREATE PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('Dharani PMS');
$pdf->SetAuthor('Dharani Pharmacy');
$pdf->SetTitle('Invoice '.$bill);
$pdf->SetMargins(15, 20, 15);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->AddPage();

// LOGO
$logo = '../assets/img/brand/pharmacy-logo.png';
if(file_exists($logo)) {
    $pdf->Image($logo, 15, 10, 40, '', 'PNG');
}

// HEADER
$pdf->SetFont('helvetica', 'B', 22);
$pdf->SetTextColor(23, 43, 77);
$pdf->Cell(0, 15, 'DHARANI PHARMACY', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 11);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 8, 'Main Street, Gampaha, Sri Lanka', 0, 1, 'C');
$pdf->Cell(0, 8, 'Tel: 033-222-1234 • info@dharanipharmacy.lk', 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('helvetica', 'B', 26);
$pdf->SetTextColor(0, 123, 255);
$pdf->Cell(0, 15, 'TAX INVOICE', 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFillColor(240, 248, 255);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 12, 'Invoice #'.$bill, 0, 1, 'C', true);
$pdf->Ln(8);

// CUSTOMER
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, 'Customer Details', 0, 1);
$html = '<table border="1" cellpadding="8"><tr bgcolor="#f8f9fa">
    <td width="30%"><strong>Name</strong></td><td>'.$customer['CustomerName'].'</td></tr>
    <tr><td><strong>Mobile</strong></td><td>'.$customer['MobileNumber'].'</td></tr>
    <tr><td><strong>Date</strong></td><td>'.date('d M Y, h:i A', strtotime($customer['BillingDate'])).'</td></tr>
    <tr><td><strong>Payment</strong></td><td>'.$customer['ModeofPayment'].'</td></tr>
</table>';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Ln(10);

// MEDICINES
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 8, 'Purchased Items', 0, 1);
$html = '<table border="1" cellpadding="10"><tr bgcolor="#11cdef" style="color:white;">
    <th width="10%">#</th><th width="45%">Medicine</th><th width="15%">Qty</th>
    <th width="15%">Price</th><th width="15%">Total</th></tr>';
$cnt = 1; $gtotal = 0;
while($m = mysqli_fetch_array($medicines)) {
    $total = $m['ProductQty'] * $m['Priceperunit'];
    $gtotal += $total;
    $html .= '<tr><td>'.$cnt++.'</td><td>'.$m['MedicineName'].'</td><td>'.$m['ProductQty'].'</td>
        <td>Rs. '.number_format($m['Priceperunit'],2).'</td>
        <td>Rs. '.number_format($total,2).'</td></tr>';
}
$html .= '<tr bgcolor="#f0f8ff"><td colspan="4" align="right"><strong>GRAND TOTAL</strong></td>
    <td><strong>Rs. '.number_format($gtotal,2).'</strong></td></tr></table>';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Ln(15);

$pdf->SetFont('helvetica', 'I', 10);
$pdf->Cell(0, 10, 'Thank you for choosing Dharani Pharmacy!', 0, 1, 'C');
$pdf->Cell(0, 10, 'This is a computer-generated invoice • Powered by Dharani PMS', 0, 1, 'C');

// FORCE DOWNLOAD!!!
$pdf->Output('Invoice_'.$bill.'.pdf', 'D');
?>