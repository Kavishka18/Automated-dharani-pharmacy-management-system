<?php
session_start();
if (!isset($_SESSION['cspid'])) header('location: index.php');
include('includes/dbconnection.php');
$custid = $_SESSION['cspid'];

$claimid = intval($_GET['id']);
$claim = mysqli_fetch_assoc(mysqli_query($con, "
    SELECT c.*, p.ProviderName, p.PolicyNumber 
    FROM tblclaims c 
    JOIN tblinsurance_policies p ON c.PolicyID = p.ID 
    WHERE c.ID = '$claimid' AND c.CustomerID = '$custid'
"));
if(!$claim) { echo "Claim not found!"; exit; }

$base = "http://localhost/pms";
$pres = $base . "/customer/" . $claim['PrescriptionFile'];
$inv  = $base . "/customer/" . $claim['InvoiceFile'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Claim #<?php echo $claim['ClaimNumber']; ?> - Dharani PMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
</head>
<body>
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <div class="header <?php echo $claim['Status']=='INSURER_APPROVED'?'bg-gradient-success':($claim['Status']=='INSURER_REJECTED'?'bg-gradient-danger':'bg-gradient-warning'); ?> pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h1 class="display-2 text-white">Claim #<?php echo $claim['ClaimNumber']; ?></h1>
                            <p class="text-white">Status: <strong><?php echo str_replace('_', ' ', $claim['Status']); ?></strong></p>
                        </div>
                        <div class="col-auto">
                            <span class="badge badge-white badge-pill badge-lg">
                                <?php echo date('d M Y', strtotime($claim['SubmittedAt'])); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col-xl-10 mx-auto">
                    <!-- Summary -->
                    <div class="card shadow-lg mb-5">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <h4>Total Claim</h4>
                                    <h2 class="text-primary">Rs. <?php echo number_format($claim['ClaimAmount'],2); ?></h2>
                                </div>
                                <div class="col-md-4">
                                    <h4>Estimated</h4>
                                    <h2 class="text-info">Rs. <?php echo number_format($claim['EstimatedCoverage'],2); ?></h2>
                                </div>
                                <div class="col-md-4">
                                    <h4>Approved Amount</h4>
                                    <h2 class="<?php echo $claim['ApprovedAmount']>0?'text-success':'text-muted'; ?>">
                                        Rs. <?php echo $claim['ApprovedAmount']>0 ? number_format($claim['ApprovedAmount'],2) : 'Pending'; ?>
                                    </h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card shadow">
                                <div class="card-header bg-info text-white text-center">
                                    <h3><i class="fas fa-file-prescription"></i> Prescription</h3>
                                </div>
                                <div class="card-body p-0">
                                    <iframe src="<?php echo $pres; ?>" width="100%" height="500"></iframe>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="<?php echo $pres; ?>" target="_blank" class="btn btn-info">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow">
                                <div class="card-header bg-warning text-white text-center">
                                    <h3><i class="fas fa-file-invoice-dollar"></i> Invoice</h3>
                                </div>
                                <div class="card-body p-0">
                                    <iframe src="<?php echo $inv; ?>" width="100%" height="500"></iframe>
                                </div>
                                <div class="card-footer text-center">
                                    <a href="<?php echo $inv; ?>" target="_blank" class="btn btn-warning">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <?php if($claim['InsurerNotes'] || $claim['RejectionReason']) { ?>
                    <div class="card shadow mt-5">
                        <div class="card-header bg-dark text-white">
                            <h3>Insurance Provider Response</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Notes:</strong> <?php echo $claim['InsurerNotes'] ?: 'No notes'; ?></p>
                            <?php if($claim['RejectionReason']) { ?>
                                <p class="text-danger"><strong>Rejection Reason:</strong> <?php echo $claim['RejectionReason']; ?></p>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>