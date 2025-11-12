<?php
session_start();
if (!isset($_SESSION['cspid'])) {
    header('Location: index.php');
    exit;
}
include('includes/dbconnection.php');
$custid = $_SESSION['cspid'];

// Get customer details for pre-fill
$ret = mysqli_query($con, "SELECT FullName, Email FROM tblcustomerlogin WHERE ID='$custid'");
$cust = mysqli_fetch_assoc($ret);
$fullName = htmlspecialchars($cust['FullName'] ?? '');
$email    = htmlspecialchars($cust['Email'] ?? '');

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Web3Forms will handle validation + email delivery
    // We just forward the data
    $postData = [
        'access_key' => 'fdf74da1-b7a0-4bb0-a7d9-a722bea52325',
        'name'       => $_POST['name'],
        'email'      => $_POST['email'],
        'subject'    => $_POST['subject'],
        'message'    => $_POST['message'],
        'redirect'   => 'inquiries.php?sent=1'   // optional redirect
    ];

    $ch = curl_init('https://api.web3forms.com/submit');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    $response = curl_exec($ch);
    curl_close($ch);

    $resp = json_decode($response, true);
    if ($resp && $resp['success']) {
        $success = 'Your inquiry has been sent successfully!';
    } else {
        $error = 'Something went wrong. Please try again later.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>Customer Inquiries - Dharani PMS</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
<link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
<link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
<link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
<style>
    .inquiry-card { transition: all .3s; border-left:5px solid #5e72e4; }
    .inquiry-card:hover { transform:translateY(-5px); box-shadow:0 15px 35px rgba(0,0,0,.15); }
    .form-control { border-radius: .5rem; }
    .btn-submit { background: linear-gradient(135deg,#5e72e4,#324cdd); border:none; }
    .alert { border-radius: .5rem; }
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
                    <h1 class="display-2 text-white">Customer Inquiries</h1>
                    <p class="text-white">Drop us a message – we’ll reply to <strong>Quick as possible.</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page Content -->
<div class="container-fluid mt--7">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> <?= $success ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> <?= $error ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <div class="card inquiry-card shadow-lg">
                <div class="card-header bg-transparent">
                    <h3 class="mb-0 text-primary"><i class="fas fa-envelope mr-2"></i> Send Inquiry</h3>
                </div>
                <div class="card-body">
                    <form action="https://api.web3forms.com/submit" method="POST" id="inquiryForm">
                        <input type="hidden" name="access_key" value="fdf74da1-b7a0-4bb0-a7d9-a722bea52325">
                        <!-- Optional: redirect after success -->
                        <input type="hidden" name="redirect" value="inquiries.php?sent=1">

                        <div class="form-group">
                            <label for="name">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required
                                   value="<?= $fullName ?>" placeholder="Enter your name">
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" required
                                   value="<?= $email ?>" placeholder="you@example.com">
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" id="subject" class="form-control" required
                                   placeholder="Brief subject line">
                        </div>

                        <div class="form-group">
                            <label for="message">Message <span class="text-danger">*</span></label>
                            <textarea name="message" id="message" rows="6" class="form-control" required
                                      placeholder="Describe your inquiry in detail..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-submit text-white px-5">
                            <i class="fas fa-paper-plane mr-2"></i> Send Inquiry
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
<script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>
</body>
</html>