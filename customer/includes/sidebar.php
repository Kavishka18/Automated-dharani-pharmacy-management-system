<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
    <div class="container-fluid">
        <!-- Brand / Page Title -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="dashboard.php">
            Customer Dashboard
        </a>

        <?php
        // === SECURITY CHECK ===
        if (!isset($_SESSION['cspid'])) {
            header('location: index.php');
            exit;
        }

        $custid = $_SESSION['cspid'];
        include('dbconnection.php'); 

        $ret = mysqli_query($con, "SELECT FullName FROM tblcustomerlogin WHERE ID='$custid'");
        $row = mysqli_fetch_array($ret);
        $customerName = $row['FullName'] ?? 'Customer';
        ?>

        <!-- User Dropdown -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
            <li class="nav-item dropdown">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            <img alt="Customer Avatar" src="../assets/img/theme/icons8-customer-48.png">
                        </span>
                        <div class="media-body ml-2 d-none d-lg-block">
                            <span class="mb-0 text-sm font-weight-bold"><?php echo htmlspecialchars($customerName); ?></span>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome, <?php echo htmlspecialchars($customerName); ?>!</h6>
                    </div>
                    <a href="profile.php" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>My Profile</span>
                    </a>
                    <a href="change-password.php" class="dropdown-item">
                        <i class="ni ni-settings-gear-65"></i>
                        <span>Change Password</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="logout.php" class="dropdown-item">
                        <i class="ni ni-user-run"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>