<?php
session_start();
if(!isset($_SESSION['insid'])) {
    exit('Access denied');
}

require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');
include('includes/dbconnection.php');

$claimid = intval($_GET['id']);
$provider_name = $_SESSION['providername'];

// SECURITY: Only allow if claim belongs to this provider and is APPROVED
$claim = mysqli_fetch_assoc(mysqli_query($con, "
    SELECT c.*, cust.FullName, cust.Email, cust.MobileNumber, 
           pol.PolicyNumber, pol.ProviderName
    FROM tblclaims c
    JOIN tblcustomerlogin cust ON c.CustomerID = cust.ID
    JOIN tblinsurance_policies pol ON c.PolicyID = pol.ID
    WHERE c.ID = '$claimid' 
      AND pol.ProviderName = '$provider_name' 
      AND c.Status = 'INSURER_APPROVED'
"));

if(!$claim) {
    die('<h1>Invalid or Unauthorized Access</h1><p>This claim does not exist or is not approved.</p>');
}

// CREATE PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('Dharani PMS');
$pdf->SetAuthor($provider_name);
$pdf->SetTitle('Payment Voucher - ' . $claim['ClaimNumber']);
$pdf->SetSubject('Insurance Claim Payment');
$pdf->SetMargins(15, 20, 15);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->SetFont('helvetica', '', 11);
$pdf->AddPage();

// === LOGO - ONE LOGO FOR ALL PROVIDERS ===
$logo_path = '../assets/img/brand/provider.png';
if (!file_exists($logo_path)) {
    $logo_path = '../assets/img/brand/default.png'; // backup
}

// HEADER
$pdf->Image($logo_path, 15, 10, 35, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

$pdf->SetFont('helvetica', 'B', 18);
$pdf->SetTextColor(23, 43, 77);
$pdf->Cell(0, 15, $provider_name, 0, 1, 'R');

$pdf->SetFont('helvetica', '', 10);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 5, 'No. 125, Main Street, Gampaha, Sri Lanka', 0, 1, 'R');
$pdf->Cell(0, 5, 'Tel: 033-222-1234 | Email: claims@insurance.lk', 0, 1, 'R');
$pdf->Ln(5);

// INVOICE TITLE
$pdf->SetFont('helvetica', 'B', 24);
$pdf->SetTextColor(0, 123, 255);
$pdf->Cell(0, 15, 'PAYMENT VOUCHER', 0, 1, 'C');
$pdf->Ln(5);

// CLAIM INFO BOX
$pdf->SetFillColor(240, 248, 255);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Claim Reference: ' . $claim['ClaimNumber'], 0, 1, 'C', true);
$pdf->Ln(5);

// CUSTOMER DETAILS
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetTextColor(23, 43, 77);
$pdf->Cell(0, 8, 'Beneficiary Details', 0, 1);
$pdf->SetFont('helvetica', '', 11);

$tbl = '
<table border="1" cellpadding="8" cellspacing="0" style="border-color:#e9ecef;">
<tr style="background-color:#f8f9fa;">
    <td width="35%"><strong>Policy Holder</strong></td>
    <td width="65%">'.$claim['FullName'].'</td>
</tr>
<tr>
    <td><strong>Policy Number</strong></td>
    <td>'.$claim['PolicyNumber'].'</td>
</tr>
<tr>
    <td><strong>Email</strong></td>
    <td>'.$claim['Email'].'</td>
</tr>
<tr>
    <td><strong>Mobile</strong></td>
    <td>'.$claim['MobileNumber'].'</td>
</tr>
</table>';
$pdf->writeHTML($tbl, true, false, true, false, '');
$pdf->Ln(10);

// PAYMENT DETAILS
$pdf->SetFont('helvetica', 'B', 12);
$pdf->SetTextColor(23, 43, 77);
$pdf->Cell(0, 8, 'Payment Summary', 0, 1);

$tbl = '
<table border="1" cellpadding="10" cellspacing="0" style="border-color:#e9ecef;">
<tr style="background-color:#11cdef; color:white; font-weight:bold;">
    <th width="65%">Description</th>
    <th width="35%">Amount (Rs.)</th>
</tr>
<tr>
    <td>Original Claim Amount</td>
    <td align="right">'.number_format($claim['ClaimAmount'], 2).'</td>
</tr>
<tr>
    <td>Insurance Coverage Applied</td>
    <td align="right">'.number_format($claim['EstimatedCoverage'], 2).'</td>
</tr>
<tr style="background-color:#f0f8ff; font-size:16px;">
    <td><strong>FINAL APPROVED & PAYABLE AMOUNT</strong></td>
    <td align="right"><strong>Rs. '.number_format($claim['ApprovedAmount'], 2).'</strong></td>
</tr>
</table>';
$pdf->writeHTML($tbl, true, false, true, false, '');
$pdf->Ln(15);

// STATUS BOX
$pdf->SetFillColor(232, 245, 233);
$pdf->SetDrawColor(76, 175, 80);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->MultiCell(0, 15, 'PAYMENT STATUS: APPROVED & PROCESSED', 1, 'C', true);
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(0, 8, 'Processed Date: ' . date('d M Y, h:i A', strtotime($claim['UpdatedAt'])), 0, 1);
$pdf->Cell(0, 8, 'Processed By: ' . $provider_name . ' Insurance', 0, 1);
if(!empty($claim['InsurerNotes'])) {
    $pdf->Ln(5);
    $pdf->SetFont('helvetica', 'I', 10);
    $pdf->MultiCell(0, 10, 'Notes: ' . $claim['InsurerNotes'], 0, 'L');
}

// SIGNATURE
$pdf->Ln(20);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell(90, 10, '_________________________', 0, 0, 'C');
$pdf->Cell(90, 10, '_________________________', 0, 1, 'C');
$pdf->Cell(90, 10, 'Customer Signature', 0, 0, 'C');
$pdf->Cell(90, 10, 'Authorized Signatory', 0, 1, 'C');
$pdf->Cell(90, 5, '', 0, 0, 'C');
$pdf->Cell(90, 5, $provider_name, 0, 1, 'C');

// FOOTER
$pdf->Ln(15);
$pdf->SetFont('helvetica', 'I', 9);
$pdf->SetTextColor(150, 150, 150);
$pdf->Cell(0, 10, 'This is a computer-generated document • Powered by Dharani PMS • © 2025 All Rights Reserved', 0, 1, 'C');

// OUTPUT PDF
$pdf->Output('Payment_Voucher_' . $claim['ClaimNumber'] . '.pdf', 'I');
?>