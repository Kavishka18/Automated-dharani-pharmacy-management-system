<?php
session_start();
if(!isset($_SESSION['insid'])) {
    header('location:index.php');
    exit;
}
include('includes/dbconnection.php');

$provider_id = $_SESSION['insid'];
$provider_name = $_SESSION['providername']; // This is SLIC, Ceylinco, etc.

// GET ONLY CLAIMS FOR THIS PROVIDER
$pending = mysqli_num_rows(mysqli_query($con, "SELECT c.ID FROM tblclaims c 
    JOIN tblinsurance_policies p ON c.PolicyID = p.ID 
    WHERE p.ProviderName = '$provider_name' AND c.Status = 'PENDING_INSURER'"));

$approved = mysqli_num_rows(mysqli_query($con, "SELECT c.ID FROM tblclaims c 
    JOIN tblinsurance_policies p ON c.PolicyID = p.ID 
    WHERE p.ProviderName = '$provider_name' AND c.Status = 'INSURER_APPROVED'"));

$rejected = mysqli_num_rows(mysqli_query($con, "SELECT c.ID FROM tblclaims c 
    JOIN tblinsurance_policies p ON c.PolicyID = p.ID 
    WHERE p.ProviderName = '$provider_name' AND c.Status = 'INSURER_REJECTED'"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - <?php echo $provider_name; ?></title>
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
</head>
<body>
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <div class="header bg-gradient-success pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <h1 class="text-white">Welcome, <?php echo $provider_name; ?></h1>
                <p class="text-white">Secure Claim Processing Portal</p>
            </div>
        </div>

        <div class="container-fluid mt--7">
            <!-- Stats -->
            <div class="row">
                <div class="col-xl-4">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0">Pending Review</h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo $pending; ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0">Approved</h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo $approved; ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-success text-white rounded-circle shadow">
                                        <i class="fas fa-check"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="text-uppercase text-muted mb-0">Rejected</h5>
                                    <span class="h2 font-weight-bold mb-0"><?php echo $rejected; ?></span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                                        <i class="fas fa-times"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Claims Table -->
            <div class="card shadow mt-4">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0">Claims Pending Your Review</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center">
                            <thead class="thead-light">
                                <tr>
                                    <th>Claim #</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $q = mysqli_query($con, "SELECT c.*, cust.FullName FROM tblclaims c 
                                    JOIN tblcustomerlogin cust ON c.CustomerID = cust.ID 
                                    JOIN tblinsurance_policies p ON c.PolicyID = p.ID 
                                    WHERE p.ProviderName = '$provider_name' 
                                      AND c.Status IN ('PENDING_INSURER','INSURER_NEED_INFO')
                                    ORDER BY c.SubmittedAt DESC");
                                while($r = mysqli_fetch_assoc($q)) {
                                    echo "<tr>
                                        <td><strong>{$r['ClaimNumber']}</strong></td>
                                        <td>{$r['FullName']}</td>
                                        <td>Rs. ".number_format($r['ClaimAmount'],2)."</td>
                                        <td>".date('d M Y', strtotime($r['SubmittedAt']))."</td>
                                        <td>
                                            <a href='view-claim.php?id={$r['ID']}' class='btn btn-primary btn-sm'>Review</a>
                                        </td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>