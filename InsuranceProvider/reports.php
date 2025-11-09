<?php
session_start();
if(!isset($_SESSION['insid'])) {
    header('location:index.php');
    exit;
}
include('includes/dbconnection.php');
$provider_name = $_SESSION['providername'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Claim Reports - <?php echo htmlspecialchars($provider_name); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    <style>
        body { font-family: 'Inter', sans-serif; }
        .report-card { 
            border-left: 6px solid #5e72e4; 
            transition: all 0.4s ease; 
            background: #fff;
        }
        .report-card:hover { 
            transform: translateY(-10px); 
            box-shadow: 0 25px 50px rgba(0,0,0,0.18); 
        }
        .status-approved { 
            background: linear-gradient(87deg, #1de9b6, #00bfa5) !important; 
            color: white !important;
        }
        .status-rejected { 
            background: linear-gradient(87deg, #ff6b6b, #ee5a52) !important; 
            color: white !important;
        }
        .btn-pdf {
            background: linear-gradient(87deg, #fb6340, #f5365c);
            border: none;
            color: white;
            font-weight: 600;
        }
        .btn-pdf:hover {
            background: linear-gradient(87deg, #f5365c, #ee5a52);
            transform: scale(1.05);
        }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
        }
    </style>
</head>
<body>
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- Header -->
        <div class="header bg-gradient-success pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h1 class="display-2 text-white mb-0"><?php echo htmlspecialchars($provider_name); ?></h1>
                            <p class="text-white opacity-8">Official Claim Processing & Payment Report</p>
                        </div>
                        <div class="col-auto no-print">
                            <button onclick="window.print()" class="btn btn-white btn-lg shadow-lg">
                                <i class="fas fa-print"></i> Print Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="container-fluid mt--7">
            <div class="row">

                <!-- Summary Cards -->
                <?php
                $total = mysqli_num_rows(mysqli_query($con, "SELECT c.ID FROM tblclaims c
                    JOIN tblinsurance_policies p ON c.PolicyID = p.ID
                    WHERE p.ProviderName = '$provider_name'"));

                $approved = mysqli_num_rows(mysqli_query($con, "SELECT c.ID FROM tblclaims c
                    JOIN tblinsurance_policies p ON c.PolicyID = p.ID
                    WHERE p.ProviderName = '$provider_name' AND c.Status = 'INSURER_APPROVED'"));

                $rejected = mysqli_num_rows(mysqli_query($con, "SELECT c.ID FROM tblclaims c
                    JOIN tblinsurance_policies p ON c.PolicyID = p.ID
                    WHERE p.ProviderName = '$provider_name' AND c.Status = 'INSURER_REJECTED'"));

                $total_amount = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(ApprovedAmount) as total FROM tblclaims c
                    JOIN tblinsurance_policies p ON c.PolicyID = p.ID
                    WHERE p.ProviderName = '$provider_name' AND c.Status = 'INSURER_APPROVED'"));
                ?>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats shadow-lg">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Claims</h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo $total; ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
                                        <i class="fas fa-file-medical"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats shadow-lg">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Approved</h5>
                                    <span class="h2 font-weight-bold mb-0 text-success"><?php echo $approved; ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-success text-white rounded-circle shadow">
                                        <i class="fas fa-check-double"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats shadow-lg">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Rejected</h5>
                                    <span class="h2 font-weight-bold mb-0 text-danger"><?php echo $rejected; ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-danger text-white rounded-circle shadow">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6">
                    <div class="card card-stats shadow-lg">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Paid Out</h5>
                                    <span class="h2 font-weight-bold mb-0 text-warning">
                                        Rs. <?php echo number_format($total_amount['total'] ?: 0, 2); ?>
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-warning text-white rounded-circle shadow">
                                        <i class="fas fa-rupee-sign"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Report Table -->
            <div class="card shadow-lg border-0">
                <div class="card-header bg-transparent border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0">Complete Claim History</h3>
                            <p class="text-sm text-muted mb-0">Only claims processed by <?php echo htmlspecialchars($provider_name); ?></p>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0">
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Claim #</th>
                                    <th scope="col">Customer</th>
                                    <th scope="col">Claim Amount</th>
                                    <th scope="col">Approved Amount</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Processed On</th>
                                    <th scope="col">Notes</th>
                                    <th scope="col" class="no-print">Action</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php
                                $q = mysqli_query($con, "SELECT c.*, cust.FullName, h.NewStatus, h.Notes, h.ChangedAt
                                    FROM tblclaims c
                                    JOIN tblcustomerlogin cust ON c.CustomerID = cust.ID
                                    JOIN tblinsurance_policies p ON c.PolicyID = p.ID
                                    LEFT JOIN tblclaim_status_history h ON c.ID = h.ClaimID 
                                        AND h.NewStatus IN ('INSURER_APPROVED','INSURER_REJECTED')
                                    WHERE p.ProviderName = '$provider_name'
                                      AND c.Status IN ('INSURER_APPROVED','INSURER_REJECTED')
                                    ORDER BY COALESCE(h.ChangedAt, c.UpdatedAt) DESC");

                                if(mysqli_num_rows($q) == 0) {
                                    echo "<tr><td colspan='8' class='text-center py-5'><h4>No claims processed yet</h4></td></tr>";
                                }

                                while($r = mysqli_fetch_assoc($q)) {
                                    $is_approved = $r['Status'] == 'INSURER_APPROVED';
                                    $status_class = $is_approved ? 'success status-approved' : 'danger status-rejected';
                                    $status_text = $is_approved ? 'APPROVED' : 'REJECTED';
                                    $icon = $is_approved ? 'check-circle' : 'times-circle';
                                    $processed_time = !empty($r['ChangedAt']) ? $r['ChangedAt'] : $r['UpdatedAt'];
                                ?>
                                <tr class="report-card">
                                    <td>
                                        <strong class="text-primary"><?php echo htmlspecialchars($r['ClaimNumber']); ?></strong>
                                    </td>
                                    <td><?php echo htmlspecialchars($r['FullName']); ?></td>
                                    <td>Rs. <?php echo number_format($r['ClaimAmount'], 2); ?></td>
                                    <td>
                                        <strong class="text-success">
                                            Rs. <?php echo number_format($r['ApprovedAmount'], 2); ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-pill badge-<?php echo $status_class; ?> badge-lg">
                                            <i class="fas fa-<?php echo $icon; ?>"></i> 
                                            <?php echo $status_text; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo date('d M Y', strtotime($processed_time)); ?><br>
                                            <strong><?php echo date('h:i A', strtotime($processed_time)); ?></strong>
                                        </small>
                                    </td>
                                    <td>
                                        <small>
                                            <?php echo !empty($r['Notes']) ? htmlspecialchars($r['Notes']) : 
                                                     (!empty($r['RejectionReason']) ? '<span class="text-danger">'.htmlspecialchars($r['RejectionReason']).'</span>' : '<em>No notes</em>'); ?>
                                        </small>
                                    </td>
                                    <td class="no-print">
                                        <?php if($is_approved) { ?>
                                            <a href="generate-invoice.php?id=<?php echo $r['ID']; ?>" 
                                               target="_blank" 
                                               class="btn btn-pdf btn-sm shadow-sm">
                                                <i class="fas fa-file-pdf"></i> Official Invoice
                                            </a>
                                        <?php } else { ?>
                                            <span class="text-muted">—</span>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 mb-5">
                <h2 class="text-gradient text-success">Thank You for Choosing</h2>
                <h1 class="display-3 font-weight-bold"><?php echo htmlspecialchars($provider_name); ?></h1>
                <p class="lead text-muted">
                    Powered by <strong>Dharani Pharmacy Management System</strong><br>
                    Gampaha • Sri Lanka • © <?php echo date('Y'); ?> All Rights Reserved
                </p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>
</body>
</html>