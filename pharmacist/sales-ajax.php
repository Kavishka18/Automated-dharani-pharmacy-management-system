<?php
session_start();
include('includes/dbconnection.php');
if (!isset($_SESSION['pmspid'])) exit;

$pmspid = $_SESSION['pmspid'];
$today = date('Y-m-d');
$search = $_POST['search_query'] ?? '';
$filter_date = $_POST['filter_date'] ?? $today;

ob_start();

// TODAY'S SALES
$where = "ct.PharmacistId = '$pmspid' AND ct.IsCheckOut = 1 AND DATE(ct.SaleDate) = '$today'";
if ($search) $where .= " AND (cust.CustomerName LIKE '%$search%' OR ct.BillingId LIKE '%$search%')";

$q = "SELECT ct.BillingId AS invoice, COALESCE(cust.CustomerName, 'Walk-in') AS customer,
      COALESCE(cust.MobileNumber, '-') AS mobile, COALESCE(cust.ModeofPayment, 'cash') AS mode,
      SUM(ct.ProductQty * m.Priceperunit) AS total
      FROM tblcart ct
      JOIN tblmedicine m ON ct.ProductId = m.ID
      LEFT JOIN tblcustomer cust ON ct.BillingId = cust.BillingNumber
      WHERE $where
      GROUP BY ct.BillingId ORDER BY ct.SaleDate DESC";

$res = mysqli_query($con, $q);
$today_total = 0;
$today_html = '<p class="text-center text-muted">No sales today</p>';

if (mysqli_num_rows($res) > 0) {
    $today_html = '<table class="table"><thead class="thead-light"><tr><th>Invoice</th><th>Customer</th><th>Mobile</th><th>Amount</th><th>Pay</th><th>View</th></tr></thead><tbody>';
    while ($r = mysqli_fetch_assoc($res)) {
        $today_total += $r['total'];
        $today_html .= "<tr>
            <td><span class='badge badge-primary'>#{$r['invoice']}</span></td>
            <td>" . htmlspecialchars($r['customer']) . "</td>
            <td>{$r['mobile']}</td>
            <td>Rs. " . number_format($r['total'], 2) . "</td>
            <td>" . ($r['mode']=='cash' ? 'Cash' : 'Card') . "</td>
            <td><a href='invoice.php?bid={$r['invoice']}' class='btn btn-sm btn-info'>View</a></td>
        </tr>";
    }
    $today_html .= '</tbody></table>';
}

// PAYMENT SPLIT
$split = mysqli_query($con, "SELECT COALESCE(cust.ModeofPayment, 'cash') AS mode, SUM(ct.ProductQty * m.Priceperunit) AS amt
                             FROM tblcart ct JOIN tblmedicine m ON ct.ProductId = m.ID
                             LEFT JOIN tblcustomer cust ON ct.BillingId = cust.BillingNumber
                             WHERE ct.PharmacistId = '$pmspid' AND ct.IsCheckOut = 1
                             GROUP BY mode");
$cash = $card = 0;
while ($s = mysqli_fetch_assoc($split)) {
    if ($s['mode'] == 'cash') $cash = $s['amt'];
    else $card = $s['amt'];
}

ob_end_clean();
echo json_encode([
    'html' => "
        <div class='card mb-4'><div class='card-body'>$today_html</div></div>
        <div class='row'>
            <div class='col-lg-8'><div class='card'><div class='card-body'><canvas id='paymentChart'></canvas></div></div></div>
            <div class='col-lg-4'><div class='card'><div class='card-body text-center'><h5>Rs. " . number_format($today_total, 2) . " Total</h5></div></div></div>
        </div>
    ",
    'today_total' => number_format($today_total, 2),
    'cash_total' => $cash,
    'card_total' => $card
]);