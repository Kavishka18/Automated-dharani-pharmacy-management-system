<?php
session_start();
if(!isset($_SESSION['cspid'])) {
    header('location:index.php');
    exit;
}
include('includes/dbconnection.php');
$custid = $_SESSION['cspid'];
$msg = "";

if(isset($_POST['submit'])) {
    $policyid = $_POST['policy'];
    $amount = $_POST['claimamount'];
    $claimno = "CLAIM-" . date('Y') . "-" . rand(1000,9999);

    $dir = "uploads/claims/";
    if(!is_dir($dir)) mkdir($dir, 0777, true);

    $pres = $dir . "PRES_" . time() . "_" . basename($_FILES["prescription"]["name"]);
    $inv  = $dir . "INV_" . time() . "_" . basename($_FILES["invoice"]["name"]);

    $allowed = ['pdf','jpg','jpeg','png'];
    $ext1 = strtolower(pathinfo($_FILES["prescription"]["name"], PATHINFO_EXTENSION));
    $ext2 = strtolower(pathinfo($_FILES["invoice"]["name"], PATHINFO_EXTENSION));

    if(!in_array($ext1, $allowed) || !in_array($ext2, $allowed)) {
        $msg = "<div class='alert alert-danger alert-dismissible fade show'><strong>Error!</strong> Only PDF/JPG/PNG allowed!</div>";
    }
    elseif($_FILES["prescription"]["size"] > 10000000 || $_FILES["invoice"]["size"] > 10000000) {
        $msg = "<div class='alert alert-danger alert-dismissible fade show'><strong>Error!</strong> Max 10MB per file!</div>";
    }
    else {
        if(move_uploaded_file($_FILES["prescription"]["tmp_name"], $pres) && 
           move_uploaded_file($_FILES["invoice"]["tmp_name"], $inv)) {

            $pol = mysqli_fetch_assoc(mysqli_query($con, "SELECT CoveragePercentage,Deductible,Copay FROM tblinsurance_policies WHERE ID='$policyid'"));
            $coverage = $amount * ($pol['CoveragePercentage']/100);
            $estimated = max(0, $coverage - $pol['Deductible'] - $pol['Copay']);

            $sql = "INSERT INTO tblclaims 
                    (CustomerID,PolicyID,ClaimNumber,PrescriptionFile,InvoiceFile,ClaimAmount,EstimatedCoverage,Status) 
                    VALUES 
                    ('$custid','$policyid','$claimno','$pres','$inv','$amount','$estimated','PENDING_INSURER')";

            if(mysqli_query($con, $sql)) {
                $claimid = mysqli_insert_id($con);
                mysqli_query($con, "INSERT INTO tblclaim_status_history (ClaimID,ChangedBy,NewStatus) VALUES ('$claimid','Customer','PENDING_INSURER')");
                $msg = "<div class='alert alert-success alert-dismissible fade show'>
                          <strong>SUCCESS!</strong> Claim <strong>$claimno</strong> submitted!<br>
                          Estimated Coverage: <strong>Rs. " . number_format($estimated, 2) . "</strong>
                        </div>";
            } else {
                $msg = "<div class='alert alert-danger'>Database error!</div>";
            }
        } else {
            $msg = "<div class='alert alert-danger alert-dismissible fade show'>Upload failed! (Permissions fixed)</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Submit Insurance Claim - Dharani PMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    <style>
        .card-submit { background: linear-gradient(87deg, #11cdef, #1171ef); }
        .form-control-lg { height: calc(3.5rem + 2px) !important; font-size: 1.1rem; }
    </style>
</head>
<body class="bg-default">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- Header -->
        <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row">
                        <div class="col">
                            <h1 class="display-2 text-white">Submit Insurance Claim</h1>
                            <p class="text-white">Upload prescription + invoice for instant processing</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page content -->
        <div class="container-fluid mt--7">
            <div class="row justify-content-center">
                <div class="col-xl-8">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-transparent">
                            <div class="text-center">
                                <h2 class="mb-0">New Claim Application</h2>
                            </div>
                        </div>
                        <div class="card-body px-lg-5 py-lg-5">

                            <?php echo $msg; ?>

                            <form method="post" enctype="multipart/form-data">

                                <!-- Policy Selection -->
                                <div class="form-group">
                                    <label class="form-control-label">Select Insurance Policy</label>
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                                        </div>
                                        <select name="policy" class="form-control form-control-lg" required>
                                            <option value="">Choose Policy</option>
                                            <?php
                                            $q = mysqli_query($con, "SELECT * FROM tblinsurance_policies WHERE CustomerID='$custid' AND Status='Active'");
                                            while($r = mysqli_fetch_assoc($q)) {
                                                echo "<option value='{$r['ID']}'>{$r['PolicyNumber']} - {$r['ProviderName']} ({$r['CoveragePercentage']}% Coverage)</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Claim Amount -->
                                <div class="form-group">
                                    <label class="form-control-label">Total Claim Amount (Rs.)</label>
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class=""></i></span>
                                        </div>
                                        <input type="number" step="0.01" name="claimamount" class="form-control form-control-lg" placeholder="2500.00" required>
                                    </div>
                                </div>

                                <!-- Prescription Upload -->
                                <div class="form-group">
                                    <label class="form-control-label">Prescription (PDF/JPG/PNG)</label>
                                    <div class="custom-file">
                                        <input type="file" name="prescription" class="custom-file-input" id="prescription" required accept=".pdf,.jpg,.jpeg,.png">
                                        <label class="custom-file-label" for="prescription">Choose prescription file</label>
                                    </div>
                                    <small class="text-muted">Max 10MB</small>
                                </div>

                                <!-- Invoice Upload -->
                                <div class="form-group">
                                    <label class="form-control-label">Pharmacy Invoice (PDF/JPG/PNG)</label>
                                    <div class="custom-file">
                                        <input type="file" name="invoice" class="custom-file-input" id="invoice" required accept=".pdf,.jpg,.jpeg,.png">
                                        <label class="custom-file-label" for="invoice">Choose invoice file</label>
                                    </div>
                                    <small class="text-muted">Max 10MB</small>
                                </div>

                                <div class="text-center">
                                    <button type="submit" name="submit" class="btn btn-success btn-lg btn-block mt-4">
                                        <i class="fas fa-paper-plane"></i> SUBMIT CLAIM FOR APPROVAL
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <?php include_once('includes/footer.php'); ?>
        </div>
    </div>

    <!-- Core JS -->
    <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>

    <!-- Custom File Input -->
    <script>
        $(document).on('change', '.custom-file-input', function () {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    </script>
</body>
</html>