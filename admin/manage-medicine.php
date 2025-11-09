<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['pmsaid'] == 0)) {
    header('location:logout.php');
} else {
if($_GET['del']){
    $cid=$_GET['id'];
    mysqli_query($con,"delete from tblmedicine where ID ='$cid'");
    echo "<script>alert('Medicine Deleted');</script>";
    echo "<script>window.location.href='manage-medicine.php'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Medicines - Dharani PMS</title>
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
            padding-top: 20px !important; /* FIXED OVERLAP */
        }

        /* FIXED HEADER - NOW PROPER SPACING */
        .page-header {
            background: linear-gradient(87deg, #ff6b6b 0%, #ee5a52 100%);
            padding: 80px 0 60px;
            text-align: center;
            color: white;
            border-radius: 0 0 40px 40px;
            box-shadow: 0 20px 40px rgba(238, 90, 82, 0.3);
            margin-bottom: 30px;
        }
        .page-header h1 {
            font-size: 2.6rem;
            font-weight: 700;
            margin: 0;
        }
        .page-header p {
            font-size: 1.05rem;
            opacity: 0.92;
            margin-top: 8px;
        }

        /* CARD NOW STARTS BELOW HEADER - NO OVERLAP */
        .content-card {
            border-radius: 26px;
            border: none;
            box-shadow: 0 18px 50px rgba(0,0,0,0.1);
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(12px);
            overflow: hidden;
            margin: 20px auto;
            max-width: 95%;
        }

        .card-header {
            background: transparent;
            border: none;
            padding: 35px 40px 10px;
            text-align: center;
        }

        /* TABLE - FULLY VISIBLE */
        .table-container {
            padding: 0 40px 40px;
            overflow-x: auto;
        }
        .table {
            font-size: 0.92rem;
            margin: 0;
            border-collapse: separate;
            border-spacing: 0 14px;
        }
        .table tbody tr {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        .table tbody tr:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(0,0,0,0.16);
        }
        .table td {
            padding: 20px;
            border: none;
            vertical-align: middle;
        }
        .table td:first-child {
            border-radius: 20px 0 0 20px;
            font-weight: 500;
            color: #ff6b6b;
        }
        .table td:last-child {
            border-radius: 0 20px 20px 0;
        }

        .btn {
            padding: 9px 18px;
            font-size: 0.86rem;
            border-radius: 14px;
            font-weight: 600;
            margin: 0 5px;
            transition: all 0.3s;
        }
        .btn-edit {
            background: linear-gradient(87deg, #11cdef, #1171ef);
            color: white;
        }
        .btn-delete {
            background: linear-gradient(87deg, #f5365c, #d61e3f);
            color: white;
        }
        .btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.25);
        }

        .icon-circle {
            width: 70px;
            height: 70px;
            background: linear-gradient(87deg, #ff6b6b, #ee5a52);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            box-shadow: 0 12px 30px rgba(238, 90, 82, 0.3);
        }

        .brand-footer {
            background: linear-gradient(87deg, #1a1a2e, #16213e);
            color: white;
            padding: 25px;
            border-radius: 20px;
            text-align: center;
            margin: 60px auto 30px;
            max-width: 95%;
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
        }

        @media (max-width: 768px) {
            .page-header { padding: 60px 0 50px; }
            .page-header h1 { font-size: 2.1rem; }
            .content-card { margin: 15px 10px; }
            .table-container { padding: 0 20px 30px; }
            .table td { padding: 14px 10px; font-size: 0.86rem; }
        }
    </style>
</head>
<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">

        <?php include_once('includes/sidebar.php'); ?>

        <!-- FIXED HEADER -->
        <div class="page-header">
            <div class="container-fluid">
                <h1>Manage Medicines</h1>
                <p>Edit or remove medicines • Full inventory control</p>
            </div>
        </div>

        <!-- MAIN CONTENT - NOW FULLY VISIBLE -->
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-11">

                    <div class="content-card">
                        <div class="card-header">
                            <div class="icon-circle">
                                <i class="fas fa-pills fa-2x text-white"></i>
                            </div>
                            <h3>All Registered Medicines</h3>
                            <p class="text-muted" style="font-size:0.94rem; margin-top:8px;">
                                Total: <?php echo mysqli_num_rows(mysqli_query($con,"SELECT * FROM tblmedicine")); ?> medicines
                            </p>
                        </div>

                        <div class="table-container">
                            <table class="table align-items-center">
                                <tbody>
                                    <?php
                                    $ret = mysqli_query($con, "SELECT * FROM tblmedicine ORDER BY MedicineName");
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_array($ret)) {
                                    ?>
                                    <tr>
                                        <td>#<?php echo $cnt; ?></td>
                                        <td><strong><?php echo htmlentities($row['MedicineCompany']); ?></strong></td>
                                        <td><?php echo htmlentities($row['MedicineName']); ?></td>
                                        <td><?php echo htmlentities($row['MedicineBatchno']); ?></td>
                                        <td>
                                            <a href="edit-medicine.php?editid=<?php echo $row['ID']; ?>" 
                                               class="btn btn-edit">Edit</a>
                                            <a href="manage-medicine.php?id=<?php echo $row['ID']?>&del=delete" 
                                               onClick="return confirm('Are you sure you want to delete?')" 
                                               class="btn btn-delete">Delete</a>
                                        </td>
                                    </tr>
                                    <?php $cnt++; } 

                                    if(mysqli_num_rows($ret) == 0): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="fas fa-prescription-bottle-alt fa-4x text-muted mb-4"></i>
                                            <h4 class="text-muted">No medicines added yet</h4>
                                            <p>Start by adding your first medicine</p>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- BRAND FOOTER -->
                    <div class="brand-footer">
                        <h4 style="margin:0; font-weight:600;">DHARANI PHARMACY</h4>
                        <p style="margin:5px 0 0; font-size:0.9rem; opacity:0.9;">
                            Gampaha • Sri Lanka • Professional Medicine Management
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