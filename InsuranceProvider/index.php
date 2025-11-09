<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['login'])) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = md5($_POST['password']);
    
    $query = mysqli_query($con, "SELECT ID, ProviderName FROM tblinsuranceprovider WHERE Username='$username' AND Password='$password'");
    $ret = mysqli_fetch_array($query);
    
    if($ret) {
        $_SESSION['insid'] = $ret['ID'];
        $_SESSION['providername'] = $ret['ProviderName'];
        header('location:dashboard.php');
        exit();
    } else {
        $msg = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Insurance Provider Login</title>
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
</head>
<body class="bg-default">
<div class="main-content">
    <div class="header bg-gradient-success py-7 py-lg-8">
        <div class="container">
            <div class="header-body text-center mb-7">
                <h1 class="text-white">Insurance Provider Portal</h1>
                <p class="text-lead text-light">Dharani Pharmacy Management System</p>
            </div>
        </div>
    </div>

    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow border-0">
                    <div class="card-header bg-transparent text-center">
                        <h2>Login</h2>
                    </div>
                    <div class="card-body px-lg-5 py-lg-5">
                        <?php if($msg): ?>
                            <div class="alert alert-danger"><?php echo $msg; ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-circle-08"></i></span>
                                    </div>
                                    <input class="form-control" placeholder="Username" type="text" name="username" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                    </div>
                                    <input class="form-control" placeholder="Password" type="password" name="password" required>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="login" class="btn btn-success btn-lg">Sign In</button>
                            </div>
                        </form>

                        <div class="mt-4 text-center">
                            <small class="text-muted">
                                <strong>Default Logins:</strong><br>
                                slic / slic123<br>
                                ceylinco / cey123<br>
                                janashakthi / jana123<br>
                                union / union123
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>