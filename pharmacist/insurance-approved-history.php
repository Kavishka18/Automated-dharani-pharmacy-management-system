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

// Get pharmacist name
$pharma = mysqli_fetch_array(mysqli_query($con, "SELECT FullName FROM tblpharmacist WHERE ID='$pmspid'"));
$pharmacist_name = $pharma['FullName'] ?? 'Pharmacist';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Insurance Approved History - Dharani PMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card {
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
        }

        .header {
            background: linear-gradient(87deg, #11998e, #38ef7d) !important;
        }

        .status-approved {
            background: linear-gradient(87deg, #1de9b6, #00bfa5);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .claim-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            transition: all 0.4s;
            border-left: 8px solid #00bfa5;
        }

        .claim-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
        }

        .total-box {
            background: linear-gradient(87deg, #667eea, #764ba2);
            color: white;
            padding: 30px;
            border-radius: 25px;
            text-align: center;
            font-size: 1.5rem;
        }
    </style>
</head>

<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- HEADER -->
        <div class="header pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row align-items-center text-white">
                        <div class="col">
                            <h1 class="display-2">Insurance Claim Approved History</h1>
                            <p class="lead">All successfully approved insurance claims from our pharmacy</p>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shield-alt fa-5x opacity-8"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col-xl-12">

                    <?php
                    // GET TOTAL APPROVED AMOUNT
                    $totalQuery = mysqli_query($con, "
    SELECT SUM(c.ApprovedAmount) as total 
    FROM tblclaims c 
    JOIN tblcustomerlogin cust ON c.CustomerID = cust.ID 
    WHERE c.Status = 'INSURER_APPROVED'
");
                    $totalRow = mysqli_fetch_array($totalQuery);
                    $totalApproved = $totalRow['total'] ?? 0;
                    ?>

                    <div class="total-box mb-5">
                        <h2>Rs. <?php echo number_format($totalApproved, 2); ?></h2>
                        <p class="mb-0">Total Amount Recovered from Insurance</p>
                    </div>

                    <?php
                    $query = mysqli_query($con, "
    SELECT c.*, cust.FullName, cust.MobileNumber, pol.ProviderName, h.ChangedAt, h.Notes
    FROM tblclaims c
    JOIN tblcustomerlogin cust ON c.CustomerID = cust.ID
    JOIN tblinsurance_policies pol ON c.PolicyID = pol.ID
    LEFT JOIN tblclaim_status_history h ON c.ID = h.ClaimID AND h.NewStatus = 'INSURER_APPROVED'
    WHERE c.Status = 'INSURER_APPROVED'
    ORDER BY COALESCE(h.ChangedAt, c.UpdatedAt) DESC
");

                    if (mysqli_num_rows($query) > 0):
                        while ($row = mysqli_fetch_array($query)):
                            $approvedDate = !empty($row['ChangedAt']) ? $row['ChangedAt'] : $row['UpdatedAt'];
                    ?>
                            <div class="claim-card">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4><i class="fas fa-user-injured text-success"></i> <?php echo htmlspecialchars($row['FullName']); ?></h4>
                                        <p class="mb-1"><strong>Mobile:</strong> <?php echo htmlspecialchars($row['MobileNumber']); ?></p>
                                        <p class="mb-1"><strong>Insurance:</strong> <span class="text-primary"><?php echo htmlspecialchars($row['ProviderName']); ?></span></p>
                                        <p class="mb-1"><strong>Claim #:</strong> <?php echo $row['ClaimNumber']; ?></p>
                                        <p class="mb-0"><strong>Notes:</strong> <?php echo !empty($row['Notes']) ? htmlspecialchars($row['Notes']) : '<em>No notes</em>'; ?></p>
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <div class="status-approved d-inline-block">
                                            APPROVED
                                        </div>
                                        <h3 class="mt-3 text-success">Rs. <?php echo number_format($row['ApprovedAmount'], 2); ?></h3>
                                        <p class="text-muted small">
                                            <i class="fas fa-calendar-check"></i>
                                            <?php echo date('d M Y', strtotime($approvedDate)); ?><br>
                                            <span class="text-success"><?php echo date('h:i A', strtotime($approvedDate)); ?></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endwhile;
                    else:
                        ?>
                        <div class="text-center py-5">
                            <i class="fas fa-file-medical fa-5x text-muted mb-4"></i>
                            <h3>No approved claims yet</h3>
                            <p class="text-muted">Insurance approvals will appear here automatically</p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <!-- FOOTER -->
            <div class="text-center mt-8 mb-5">
                <div class="card bg-gradient-primary text-white p-5 rounded-xl shadow-lg">
                    <h1>DHARANI PHARMACY</h1>
                    <p class="lead mb-0">Trusted by Insurance Providers • Gampaha, Sri Lanka</p>
                </div>
            </div>

        </div>
    </div>

    <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>
</body>

</html>
<?php ob_end_flush(); ?>