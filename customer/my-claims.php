<?php
session_start();
if (!isset($_SESSION['cspid'])) header('location: index.php');
include('includes/dbconnection.php');
$custid = $_SESSION['cspid'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>My Claims - Dharani PMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    <style>
        .claim-card { transition: all 0.3s; border-left: 5px solid #5e72e4; }
        .claim-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.15); }
        .status-badge { font-size: 0.9rem; padding: 8px 15px; border-radius: 50px; }
    </style>
</head>
<body>
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- Header -->
        <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h1 class="display-2 text-white">My Insurance Claims</h1>
                            <p class="text-white">Track all your submitted claims in real-time</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="container-fluid mt--7">
            <div class="row">
                <?php
                $q = mysqli_query($con, "SELECT c.*, p.ProviderName FROM tblclaims c 
                                        JOIN tblinsurance_policies p ON c.PolicyID=p.ID 
                                        WHERE c.CustomerID='$custid' ORDER BY SubmittedAt DESC");
                if(mysqli_num_rows($q) == 0) {
                    echo '<div class="col-12"><div class="alert alert-info text-center">No claims submitted yet.</div></div>';
                }
                while($r = mysqli_fetch_assoc($q)) {
                    $status = $r['Status'];
                    $badge_color = $status == 'PENDING_INSURER' ? 'warning' :
                                  ($status == 'INSURER_APPROVED' ? 'success' :
                                  ($status == 'INSURER_REJECTED' ? 'danger' : 'info'));
                    $icon = $status == 'INSURER_APPROVED' ? 'check-circle' : 
                           ($status == 'INSURER_REJECTED' ? 'times-circle' : 'clock');
                ?>
                <div class="col-xl-6 mb-4">
                    <div class="card claim-card shadow-lg">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h3 class="mb-0 text-primary"><?php echo $r['ClaimNumber']; ?></h3>
                                    <small class="text-muted">Provider: <?php echo $r['ProviderName']; ?></small>
                                </div>
                                <div class="col-auto">
                                    <span class="badge badge-<?php echo $badge_color; ?> status-badge">
                                        <i class="fas fa-<?php echo $icon; ?>"></i> <?php echo str_replace('_', ' ', $status); ?>
                                    </span>
                                </div>
                            </div>
                            <hr>
                            <div class="row text-center">
                                <div class="col">
                                    <p class="mb-1 text-muted">Claim Amount</p>
                                    <h4>Rs. <?php echo number_format($r['ClaimAmount'], 2); ?></h4>
                                </div>
                                <div class="col">
                                    <p class="mb-1 text-muted">Estimated Coverage</p>
                                    <h4 class="text-success">Rs. <?php echo number_format($r['EstimatedCoverage'], 2); ?></h4>
                                </div>
                            </div>
                            <hr>
                            <div class="row align-items-center">
                                <div class="col">
                                    <small class="text-muted">
                                        <i class="far fa-calendar-alt"></i> <?php echo date('d M Y, h:i A', strtotime($r['SubmittedAt'])); ?>
                                    </small>
                                </div>
                                <div class="col-auto">
                                    <a href="view-claim.php?id=<?php echo $r['ID']; ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>
</body>
</html>