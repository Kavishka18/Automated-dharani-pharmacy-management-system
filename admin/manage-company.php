<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['pmsaid'] == 0)) {
    header('location:logout.php');
} else {
if($_GET['del']){
    $cid=$_GET['id'];
    mysqli_query($con,"delete from tblcompany where ID ='$cid'");
    echo "<script>alert('Company Deleted');</script>";
    echo "<script>window.location.href='manage-company.php'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Companies - Dharani PMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="../assets/js/plugins/nucleo/css/nucleo.css" rel="stylesheet" />
    <link href="../assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
    <link href="../assets/css/argon-dashboard.css?v=1.1.0" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f4ff 0%, #e6f0ff 100%);
            color: #2d3748;
        }
        .main-content { 
            background: transparent; 
            padding-top: 20px !important;
        }

        /* Modern Header */
        .page-header {
            background: linear-gradient(87deg, #4facfe 0%, #00f2fe 100%);
            padding: 80px 0 60px;
            text-align: center;
            color: white;
            border-radius: 0 0 40px 40px;
            box-shadow: 0 20px 40px rgba(79, 172, 254, 0.35);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.08"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        .page-header h1 {
            font-size: 2.6rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .page-header p {
            font-size: 1.05rem;
            opacity: 0.92;
            margin-top: 8px;
        }

        /* Glass Card */
        .content-card {
            border-radius: 28px;
            border: none;
            box-shadow: 0 20px 55px rgba(0,0,0,0.12);
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(14px);
            overflow: hidden;
            margin: 20px auto;
            max-width: 95%;
        }

        .card-header {
            background: transparent;
            border: none;
            padding: 35px 45px 10px;
            text-align: center;
        }

        /* Table Styling */
        .table-container {
            padding: 0 45px 45px;
            overflow-x: auto;
        }
        .table {
            font-size: 0.93rem;
            margin: 0;
            border-collapse: separate;
            border-spacing: 0 15px;
        }
        .table tbody tr {
            background: white;
            border-radius: 22px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.09);
            transition: all 0.3s ease;
        }
        .table tbody tr:hover {
            transform: translateY(-7px);
            box-shadow: 0 20px 45px rgba(0,0,0,0.18);
        }
        .table td {
            padding: 22px 25px;
            border: none;
            vertical-align: middle;
        }
        .table td:first-child {
            border-radius: 22px 0 0 22px;
            font-weight: 600;
            color: #4facfe;
        }
        .table td:last-child {
            border-radius: 0 22px 22px 0;
        }
        .table .company-name {
            font-weight: 600;
            font-size: 1.02rem;
            color: #2d3748;
        }

        .btn {
            padding: 10px 20px;
            font-size: 0.88rem;
            border-radius: 16px;
            font-weight: 600;
            margin: 0 6px;
            transition: all 0.3s;
        }
        .btn-edit {
            background: linear-gradient(87deg, #a8edea, #4facfe);
            color: white;
        }
        .btn-delete {
            background: linear-gradient(87deg, #ff9a9e, #fad0c4);
            color: #d62976;
        }
        .btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(0,0,0,0.22);
        }

        /* Icon Circle */
        .icon-circle {
            width: 75px;
            height: 75px;
            background: linear-gradient(87deg, #4facfe, #00f2fe);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            box-shadow: 0 15px 38px rgba(79, 172, 254, 0.4);
        }

        /* Footer Brand */
        .brand-footer {
            background: linear-gradient(87deg, #1a1a2e, #16213e);
            color: white;
            padding: 32px;
            border-radius: 26px;
            text-align: center;
            margin: 80px auto 30px;
            max-width: 95%;
            box-shadow: 0 22px 50px rgba(0,0,0,0.35);
        }

        @media (max-width: 768px) {
            .page-header { padding: 60px 0 50px; }
            .page-header h1 { font-size: 2.1rem; }
            .content-card { margin: 15px 10px; }
            .table-container { padding: 0 20px 35px; }
            .table td { padding: 16px 12px; font-size: 0.88rem; }
            .btn { padding: 8px 14px; font-size: 0.82rem; }
        }
    </style>
</head>
<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- MODERN HEADER -->
        <div class="page-header">
            <div class="container-fluid">
                <h1>Manage Companies</h1>
                <p>All registered pharmaceutical companies</p>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-10">

                    <div class="content-card">
                        <div class="card-header">
                            <div class="icon-circle">
                                <i class="fas fa-building fa-2x text-white"></i>
                            </div>
                            <h3>Registered Companies</h3>
                            <p class="text-muted" style="font-size:0.94rem; margin-top:8px;">
                                Total: <?php echo mysqli_num_rows(mysqli_query($con,"SELECT * FROM tblcompany")); ?> companies
                            </p>
                        </div>

                        <div class="table-container">
                            <table class="table align-items-center">
                                <tbody>
                                    <?php
                                    $ret = mysqli_query($con, "SELECT * FROM tblcompany ORDER BY CompanyName");
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_array($ret)) {
                                    ?>
                                    <tr>
                                        <td>#<?php echo $cnt; ?></td>
                                        <td class="company-name"><?php echo htmlentities($row['CompanyName']); ?></td>
                                        <td>
                                            <a href="edit-company.php?editid=<?php echo $row['ID']; ?>" 
                                               class="btn btn-edit">Edit</a>
                                            <a href="manage-company.php?id=<?php echo $row['ID']?>&del=delete" 
                                               onClick="return confirm('Are you sure you want to delete?')" 
                                               class="btn btn-delete">Delete</a>
                                        </td>
                                    </tr>
                                    <?php $cnt++; } 

                                    if(mysqli_num_rows($ret) == 0): ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-5">
                                            <i class="fas fa-building fa-4x text-muted mb-4"></i>
                                            <h4 class="text-muted">No companies added yet</h4>
                                            <p>Add your first company to get started</p>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- BRAND FOOTER -->
                    <div class="brand-footer">
                        <h4 style="margin:0; font-weight:600; font-size:1.25rem;">DHARANI PHARMACY</h4>
                        <p style="margin:7px 0 0; font-size:0.94rem; opacity:0.9;">
                            Gampaha • Sri Lanka • Trusted by Global Pharma Giants
                        </p>
                    </div>

                </div>
            </div>
        </div>

        <?php include_once('includes/footer.php'); ?>
    </div>

    <script src="../assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="../assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/argon-dashboard.min.js?v=1.1.0"></script>
</body>
</html>
<?php } ?>