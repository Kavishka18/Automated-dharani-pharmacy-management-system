<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['pmsaid'] == 0)) {
    header('location:logout.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dharani PMS - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    <style>
        :root {
            --primary: #4361ee;
            --success: #2dd4bf;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #0ea5e9;
            --dark: #1e293b;
            --light: #f8fafc;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #e2e8f0;
            min-height: 100vh;
        }
        .main-content {
            padding-top: 20px !important;
            background: transparent;
        }

        /* EPIC HEADER */
        .dashboard-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #3b82f6 100%);
            padding: 100px 0 80px;
            text-align: center;
            border-radius: 0 0 60px 60px;
            box-shadow: 0 30px 80px rgba(30, 58, 138, 0.6);
            position: relative;
            overflow: hidden;
        }
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.15"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.4;
        }
        .dashboard-header h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin: 0;
            letter-spacing: -1.5px;
            background: linear-gradient(87deg, #60a5fa, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .dashboard-header p {
            font-size: 1.3rem;
            opacity: 0.95;
            margin-top: 15px;
            font-weight: 500;
        }

        /* GLASS CARDS GRID */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 28px;
            padding: 40px 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 32px;
            padding: 32px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.25);
            border: 1.5px solid rgba(255, 255, 255, 0.2);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 6px;
            background: var(--card-gradient);
            border-radius: 32px 32px 0 0;
        }
        .stat-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 35px 80px rgba(0,0,0,0.4);
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            background: var(--card-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }

        .stat-title {
            font-size: 0.95rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            opacity: 0.9;
            margin-bottom: 8px;
        }
        .stat-title a {
            color: inherit;
            text-decoration: none;
            transition: all 0.3s;
        }
        .stat-title a:hover {
            color: white;
        }

        .stat-value {
            font-size: 2.8rem;
            font-weight: 800;
            background: linear-gradient(87deg, #60a5fa, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1;
        }

        /* EXPIRED CARD - RED ALERT */
        .expired-card {
            --card-gradient: linear-gradient(87deg, #ef4444, #dc2626);
            animation: pulse-red 3s infinite;
        }
        @keyframes pulse-red {
            0%, 100% { box-shadow: 0 20px 50px rgba(239, 68, 68, 0.4); }
            50% { box-shadow: 0 20px 70px rgba(239, 68, 68, 0.7); }
        }

        /* FOOTER BRAND */
        .brand-footer {
            background: linear-gradient(87deg, #0f172a, #1e293b);
            color: white;
            padding: 50px;
            border-radius: 40px;
            text-align: center;
            margin: 100px auto 40px;
            max-width: 95%;
            box-shadow: 0 40px 90px rgba(0,0,0,0.6);
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 768px) {
            .dashboard-header { padding: 80px 0 70px; }
            .dashboard-header h1 { font-size: 2.5rem; }
            .stats-grid { grid-template-columns: 1fr; padding: 20px 15px; }
            .stat-card { padding: 25px; }
            .stat-value { font-size: 2.4rem; }
        }
    </style>
</head>
<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- EPIC HEADER -->
        <div class="dashboard-header">
            <div class="container-fluid">
                <h1>DHARANI PMS</h1>
                <p>Real-Time Pharmacy Empire Control Center • Gampaha, Sri Lanka</p>
            </div>
        </div>

        <!-- STATS GRID -->
        <div class="stats-grid">

            <!-- Total Pharmacist -->
            <?php
            $query1 = mysqli_query($con, "SELECT * FROM tblpharmacist");
            $pharcount = mysqli_num_rows($query1);
            ?>
            <div class="stat-card" style="--card-gradient: linear-gradient(87deg, #f59e0b, #f97316);">
                <div class="stat-icon"><i class="fas fa-users fa-2x"></i></div>
                <div class="stat-title"><a href="manage-pharmacist.php">Total Pharmacist</a></div>
                <div class="stat-value"><?php echo $pharcount; ?></div>
            </div>

            <!-- Total Company -->
            <?php
            $query = mysqli_query($con, "SELECT * FROM tblcompany");
            $compcount = mysqli_num_rows($query);
            ?>
            <div class="stat-card" style="--card-gradient: linear-gradient(87deg, #8b5cf6, #a78bfa);">
                <div class="stat-icon"><i class="fa fa-building fa-2x"></i></div>
                <div class="stat-title"><a href="manage-company.php">Medical Companies</a></div>
                <div class="stat-value"><?php echo $compcount; ?></div>
            </div>

            <!-- Total Medicine -->
            <?php
            $query2 = mysqli_query($con, "SELECT * FROM tblmedicine");
            $medcount = mysqli_num_rows($query2);
            ?>
            <div class="stat-card" style="--card-gradient: linear-gradient(87deg, #0ea5e9, #22d3ee);">
                <div class="stat-icon"><i class="fas fa-prescription-bottle-alt fa-2x"></i></div>
                <div class="stat-title"><a href="manage-medicine.php">Total Medicines</a></div>
                <div class="stat-value"><?php echo $medcount; ?></div>
            </div>

            <!-- Today's Sale -->
            <?php
            $todysale = 0;
            $query4 = mysqli_query($con, "SELECT tblcart.ProductQty, tblmedicine.Priceperunit FROM tblcart JOIN tblmedicine ON tblmedicine.ID = tblcart.ProductId WHERE DATE(CartDate) = CURDATE() AND tblcart.IsCheckOut = '1'");
            while ($row = mysqli_fetch_array($query4)) {
                $todysale += $row['ProductQty'] * $row['Priceperunit'];
            }
            ?>
            <div class="stat-card" style="--card-gradient: linear-gradient(87deg, #10b981, #34d399);">
                <div class="stat-icon"><i class="fas fa-rupee-sign fa-2x"></i></div>
                <div class="stat-title"><a href="sales-reports.php">Today's Sale</a></div>
                <div class="stat-value">Rs. <?php echo number_format($todysale, 2); ?></div>
            </div>

            <!-- Last 7 Days -->
            <?php
            $tseven = 0;
            $query6 = mysqli_query($con, "SELECT tblcart.ProductQty, tblmedicine.Priceperunit FROM tblcart JOIN tblmedicine ON tblmedicine.ID = tblcart.ProductId WHERE CartDate >= DATE(NOW()) - INTERVAL 7 DAY AND IsCheckOut = '1'");
            while ($row2 = mysqli_fetch_array($query6)) {
                $tseven += $row2['ProductQty'] * $row2['Priceperunit'];
            }
            ?>
            <div class="stat-card" style="--card-gradient: linear-gradient(87deg, #6366f1, #8b5cf6);">
                <div class="stat-icon"><i class="fas fa-chart-line fa-2x"></i></div>
                <div class="stat-title"><a href="sales-reports.php">Last 7 Days</a></div>
                <div class="stat-value">Rs. <?php echo number_format($tseven, 2); ?></div>
            </div>

            <!-- Total Sale All Time -->
            <?php
            $totalsale = 0;
            $query7 = mysqli_query($con, "SELECT tblcart.ProductQty, tblmedicine.Priceperunit FROM tblcart JOIN tblmedicine ON tblmedicine.ID = tblcart.ProductId WHERE IsCheckOut = '1'");
            while ($row7 = mysqli_fetch_array($query7)) {
                $totalsale += $row7['ProductQty'] * $row7['Priceperunit'];
            }
            ?>
            <div class="stat-card" style="--card-gradient: linear-gradient(87deg, #8b5cf6, #ec4899);">
                <div class="stat-icon"><i class="fas fa-trophy fa-2x"></i></div>
                <div class="stat-title"><a href="sales-reports.php">Total Revenue</a></div>
                <div class="stat-value">Rs. <?php echo number_format($totalsale, 2); ?></div>
            </div>

            <!-- EXPIRED MEDICINES - RED ALERT -->
            <?php
            $expiredQuery = mysqli_query($con, "SELECT COUNT(*) as expired_count FROM tblmedicine WHERE ExpiryDate <= CURDATE()");
            $expiredRow = mysqli_fetch_assoc($expiredQuery);
            $expiredCount = $expiredRow['expired_count'];
            ?>
            <div class="stat-card expired-card">
                <div class="stat-icon"><i class="fas fa-exclamation-triangle fa-2x"></i></div>
                <div class="stat-title"><a href="expired-alerts.php" style="color: #fca5a5;">Expired Medicines</a></div>
                <div class="stat-value" style="background: linear-gradient(87deg, #fca5a5, #ef4444); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    <?php echo $expiredCount; ?>
                </div>
            </div>

        </div>

        <!-- BRAND FOOTER -->
        <div class="brand-footer">
            <h1 style="margin:0; font-size:2.2rem; font-weight:800; background: linear-gradient(87deg, #60a5fa, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                DHARANI PHARMACY
            </h1>
            <p style="margin:12px 0 0; font-size:1.1rem; opacity:0.9;">
                Gampaha • Sri Lanka • Built with Modern
            </p>
        </div>

        <?php include_once('includes/footer.php'); ?>
    </div>

    <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>
</body>
</html>
<?php ?>