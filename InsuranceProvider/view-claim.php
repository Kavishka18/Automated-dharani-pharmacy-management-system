<?php
session_start();
if(!isset($_SESSION['insid'])) {
    header('location:index.php');
    exit;
}
include('includes/dbconnection.php');

// SECURITY: Only allow if claim belongs to this provider
$claim_check = mysqli_fetch_assoc(mysqli_query($con, "
    SELECT p.ProviderName FROM tblclaims c 
    JOIN tblinsurance_policies p ON c.PolicyID = p.ID 
    WHERE c.ID = '$claimid'
"));
if($claim_check['ProviderName'] !== $provider_name) {
    echo "<script>alert('Access Denied! This claim does not belong to your company.'); window.location='dashboard.php';</script>";
    exit;
}

$claimid = intval($_GET['id']);
$claim = mysqli_fetch_assoc(mysqli_query($con, "
    SELECT c.*, cust.FullName, cust.Email, cust.MobileNumber, 
           pol.PolicyNumber, pol.ProviderName, pol.CoveragePercentage
    FROM tblclaims c
    JOIN tblcustomerlogin cust ON c.CustomerID = cust.ID
    JOIN tblinsurance_policies pol ON c.PolicyID = pol.ID
    WHERE c.ID = '$claimid'
"));

$base = "http://localhost/pms";
$prescription_url = $base . "/customer/" . $claim['PrescriptionFile'];
$invoice_url = $base . "/customer/" . $claim['InvoiceFile'];

// Handle Actions
if(isset($_POST['approve'])) {
    $amount = $_POST['approved_amount'];
    $notes = mysqli_real_escape_string($con, $_POST['notes']);
    mysqli_query($con, "UPDATE tblclaims SET 
        ApprovedAmount='$amount', 
        InsurerNotes='$notes', 
        Status='INSURER_APPROVED', 
        UpdatedAt=NOW() 
        WHERE ID='$claimid'");
    mysqli_query($con, "INSERT INTO tblclaim_status_history 
        (ClaimID, ChangedBy, OldStatus, NewStatus, Notes) 
        VALUES ('$claimid', 'Insurer', 'PENDING_INSURER', 'INSURER_APPROVED', '$notes')");
    echo "<script>alert('Claim APPROVED successfully!'); window.location='dashboard.php';</script>";
}

if(isset($_POST['reject'])) {
    $reason = mysqli_real_escape_string($con, $_POST['reason']);
    mysqli_query($con, "UPDATE tblclaims SET 
        RejectionReason='$reason', 
        Status='INSURER_REJECTED', 
        UpdatedAt=NOW() 
        WHERE ID='$claimid'");
    mysqli_query($con, "INSERT INTO tblclaim_status_history 
        (ClaimID, ChangedBy, OldStatus, NewStatus, Notes) 
        VALUES ('$claimid', 'Insurer', 'PENDING_INSURER', 'INSURER_REJECTED', '$reason')");
    echo "<script>alert('Claim REJECTED!'); window.location='dashboard.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Review Claim #<?php echo $claim['ClaimNumber']; ?> - Dharani PMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    
    <!-- Icons -->
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    
    <!-- Argon CSS -->
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    
    <style>
        .doc-frame {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        .doc-frame:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
        .doc-header {
            padding: 20px;
            color: white;
            text-align: center;
            font-weight: bold;
            font-size: 1.3rem;
        }
        iframe { border: none; }
        .amount-box {
            background: linear-gradient(87deg, #5e72e4, #825ee4);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
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
                    <div class="row">
                        <div class="col">
                            <h1 class="display-2 text-white">Review Claim</h1>
                            <p class="text-white">Claim #<?php echo $claim['ClaimNumber']; ?> • Submitted on <?php echo date('d M Y', strtotime($claim['SubmittedAt'])); ?></p>
                        </div>
                        <div class="col text-right">
                            <span class="badge badge-pill badge-warning badge-lg">
                                <i class="fas fa-clock"></i> PENDING REVIEW
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page content -->
        <div class="container-fluid mt--7">
            <div class="row">
                <div class="col-xl-10 mx-auto">

                    <!-- Claim Info Card -->
                    <div class="card shadow-lg border-0 mb-5">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3><i class="fas fa-user text-primary"></i> Customer Information</h3>
                                    <p><strong>Name:</strong> <?php echo $claim['FullName']; ?></p>
                                    <p><strong>Email:</strong> <?php echo $claim['Email']; ?></p>
                                    <p><strong>Mobile:</strong> <?php echo $claim['MobileNumber']; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <h3><i class="fas fa-shield-alt text-success"></i> Policy Details</h3>
                                    <p><strong>Policy:</strong> <?php echo $claim['PolicyNumber']; ?></p>
                                    <p><strong>Provider:</strong> <?php echo $claim['ProviderName']; ?></p>
                                    <p><strong>Coverage:</strong> <?php echo $claim['CoveragePercentage']; ?>%</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Amount Summary -->
                    <div class="row mb-5">
                        <div class="col-md-6">
                            <div class="amount-box">
                                <h4>Total Claim Amount</h4>
                                <h2 class="mb-0">Rs. <?php echo number_format($claim['ClaimAmount'], 2); ?></h2>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="amount-box" style="background: linear-gradient(87deg, #11cdef, #1171ef);">
                                <h4>Estimated Coverage</h4>
                                <h2 class="mb-0">Rs. <?php echo number_format($claim['EstimatedCoverage'], 2); ?></h2>
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <h2 class="text-center mb-5"><i class="fas fa-file-medical text-primary"></i> Attached Documents</h2>
                    
                    <div class="row">
                        <!-- PRESCRIPTION -->
                        <div class="col-md-6 mb-4">
                            <div class="doc-frame">
                                <div class="doc-header bg-info">
                                    <i class="fas fa-file-prescription fa-3x"></i><br>
                                    PRESCRIPTION
                                </div>
                                <iframe src="<?php echo $prescription_url; ?>" width="100%" height="600"></iframe>
                            </div>
                            <div class="text-center mt-3">
                                <a href="<?php echo $prescription_url; ?>" target="_blank" class="btn btn-info btn-lg">
                                    <i class="fas fa-download"></i> Download Prescription
                                </a>
                            </div>
                        </div>

                        <!-- INVOICE -->
                        <div class="col-md-6 mb-4">
                            <div class="doc-frame">
                                <div class="doc-header bg-warning">
                                    <i class="fas fa-file-invoice-dollar fa-3x"></i><br>
                                    PHARMACY INVOICE
                                </div>
                                <iframe src="<?php echo $invoice_url; ?>" width="100%" height="600"></iframe>
                            </div>
                            <div class="text-center mt-3">
                                <a href="<?php echo $invoice_url; ?>" target="_blank" class="btn btn-warning btn-lg">
                                    <i class="fas fa-download"></i> Download Invoice
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Action Form -->
                    <div class="card shadow-lg border-0 mt-5">
                        <div class="card-header bg-transparent">
                            <h3 class="mb-0 text-center">Take Action</h3>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Approved Amount (Rs.)</label>
                                            <input type="number" step="0.01" name="approved_amount" 
                                                   class="form-control form-control-lg" 
                                                   value="<?php echo $claim['EstimatedCoverage']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Notes (Optional)</label>
                                            <textarea name="notes" class="form-control" rows="3" 
                                                      placeholder="e.g., Full amount approved after verification"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" name="approve" class="btn btn-success btn-lg px-5">
                                        <i class="fas fa-check-circle"></i> APPROVE CLAIM
                                    </button>
                                    <button type="button" class="btn btn-danger btn-lg px-5" data-toggle="modal" data-target="#rejectModal">
                                        <i class="fas fa-times-circle"></i> REJECT CLAIM
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Reject Claim - Provide Reason</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body">
                        <textarea name="reason" class="form-control" rows="6" 
                                  placeholder="Please explain why this claim is being rejected..." required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="reject" class="btn btn-danger btn-lg">
                            <i class="fas fa-ban"></i> Confirm Rejection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>
</body>
</html>