<?php
session_start();
if(!isset($_POST['generate_pdf'])) exit;

require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

$customer_name = $_POST['customer_name'];
$mobile = $_POST['mobile'];
$provider = $_POST['insurance_provider'];
$date = $_POST['invoice_date'];
$inv_no = $_POST['invoice_no'];
$meds = $_POST['medicine'];
$prices = $_POST['price'];
$qty = $_POST['qty'];

$total = 0;
$items = [];
for($i=0; $i<count($meds); $i++) {
    $amt = $prices[$i] * $qty[$i];
    $total += $amt;
    $items[] = ['name'=>$meds[$i], 'price'=>$prices[$i], 'qty'=>$qty[$i], 'amt'=>$amt];
}

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(15, 20, 15);
$pdf->SetAutoPageBreak(TRUE, 20);
$pdf->AddPage();

// BACKGROUND GRADIENT EFFECT
$pdf->SetFillColor(255, 107, 107);
$pdf->Rect(0, 0, 210, 50, 'F');
$pdf->SetFillColor(238, 90, 82);
$pdf->Rect(0, 50, 210, 20, 'F');

// LOGO
$logo = '../assets/img/brand/dharani-logo.png';
if(file_exists($logo)) {
    $pdf->Image($logo, 15, 12, 45);
}

// TITLE
$pdf->SetFont('helvetica', 'B', 28);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(0, 20, 'DHARANI PHARMACY', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 8, 'Gampaha | Tel: 033-222-5678', 0, 1, 'C');
$pdf->Ln(10);

// INVOICE BOX
$pdf->SetFillColor(17, 113, 239);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('helvetica', 'B', 22);
$pdf->Cell(0, 15, 'INSURANCE INVOICE', 0, 1, 'C', true);
$pdf->Ln(5);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->SetFillColor(240, 248, 255);
$pdf->Cell(0, 10, "Invoice: $inv_no • Date: ".date('d M Y', strtotime($date)), 0, 1, 'C', true);
$pdf->Ln(8);

// CUSTOMER INFO
$html = '<table border="1" cellpadding="12" cellspacing="0" style="border-color:#e0e0e0;">
<tr style="background:#f8f9fa;">
    <td width="50%"><strong>Patient Name</strong></td>
    <td width="50%"><strong>' . htmlspecialchars($customer_name) . '</strong></td>
</tr>
<tr>
    <td><strong>Mobile</strong></td>
    <td>' . htmlspecialchars($mobile) . '</td>
</tr>
<tr style="background:#e8f5e8;">
    <td><strong>Insurance Provider</strong></td>
    <td style="color:#28a745; font-weight:bold;">' . htmlspecialchars($provider) . '</td>
</tr>
<tr style="background:#fff3cd;">
    <td><strong>Payment Type</strong></td>
    <td style="color:#ff6b6b; font-weight:bold;">INSURANCE CLAIM</td>
</tr>
</table>';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Ln(15);

// MEDICINES
$html = '<h3 style="color:#1171ef;">Medicine Details</h3>
<table border="1" cellpadding="10" cellspacing="0">
<tr style="background:#11cdef; color:white; font-weight:bold;">
    <th width="45%">Medicine</th>
    <th width="15%">Price</th>
    <th width="15%">Qty</th>
    <th width="25%">Amount</th>
</tr>';

foreach($items as $it) {
    $html .= '<tr>
        <td>' . htmlspecialchars($it['name']) . '</td>
        <td align="right">Rs. ' . number_format($it['price'], 2) . '</td>
        <td align="center">' . $it['qty'] . '</td>
        <td align="right">Rs. ' . number_format($it['amt'], 2) . '</td>
    </tr>';
}

$html .= '<tr style="background:#f0f8ff; font-size:18px;">
    <td colspan="3" align="right"><strong>TOTAL PAYABLE</strong></td>
    <td align="right"><strong>Rs. ' . number_format($total, 2) . '</strong></td>
</tr>
</table>';
$pdf->writeHTML($html, true, false, true, false, '');

// FOOTER
$pdf->Ln(20);
$pdf->SetFont('helvetica', 'I', 11);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 10, 'For Insurance Claim Only • Please upload this invoice to your insurance portal', 0, 1, 'C');
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 8, 'Powered by Dharani PMS • Gampaha, Sri Lanka • © 2025', 0, 1, 'C');

// FORCE DOWNLOAD
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="Insurance_Invoice_'.$inv_no.'.pdf"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');
$pdf->Output('Insurance_Invoice_'.$inv_no.'.pdf', 'D');
exit;
?>