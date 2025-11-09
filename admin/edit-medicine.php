<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['pmsaid'] == 0)) {
    header('location:logout.php');
} else {
if(isset($_POST['submit'])) {
    $compname=$_POST['compname'];
    $medname=$_POST['medname'];
    $batchnumber=$_POST['batchnumber'];
    $mgfdate=$_POST['mgfdate'];
    $expirydate=$_POST['expirydate'];
    $quantity=$_POST['quantity'];
    $price=$_POST['price'];
    $eid=$_GET['editid'];
    $query=mysqli_query($con,"update tblmedicine set MedicineCompany='$compname',MedicineName='$medname',MedicineBatchno='$batchnumber',MgfDate='$mgfdate',ExpiryDate='$expirydate',Quantity='$quantity',Priceperunit='$price' where ID='$eid'");
    if ($query) {
        $msg="Medicine detail has been updated successfully.";
    } else {
        $msg="Something Went Wrong. Please try again";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Medicine - Dharani PMS</title>
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
            background: linear-gradient(87deg, #f093fb 0%, #f5576c 100%);
            padding: 80px 0 60px;
            text-align: center;
            color: white;
            border-radius: 0 0 40px 40px;
            box-shadow: 0 20px 40px rgba(245, 87, 108, 0.3);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.07"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
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

        .card-body {
            padding: 50px 45px;
        }

        /* Form Styling */
        .form-control, .form-control:focus {
            border-radius: 18px;
            border: 2.5px solid #e2e8f0;
            padding: 14px 20px;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
            background: #fafbff;
        }
        .form-control:focus {
            border-color: #f5576c;
            box-shadow: 0 0 0 5px rgba(245, 87, 108, 0.2);
            background: white;
        }
        select.form-control {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23f5576c' viewBox='0 0 16 16'%3e%3cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3e%3c/svg%3e");
            background-position: right 18px center;
            background-repeat: no-repeat;
            background-size: 14px;
        }
        .form-group label {
            font-size: 0.89rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        /* Submit Button */
        .btn-update {
            background: linear-gradient(87deg, #f093fb, #f5576c);
            border: none;
            color: white;
            padding: 16px 55px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 14px 32px rgba(245, 87, 108, 0.4);
            transition: all 0.4s ease;
            text-transform: uppercase;
            letter-spacing: 1.3px;
        }
        .btn-update:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 45px rgba(245, 87, 108, 0.5);
        }

        /* Icon Circle */
        .icon-circle {
            width: 72px;
            height: 72px;
            background: linear-gradient(87deg, #f093fb, #f5576c);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            box-shadow: 0 14px 35px rgba(245, 87, 108, 0.35);
        }

        /* Alert */
        .alert-msg {
            font-size: 0.95rem;
            padding: 16px 24px;
            border-radius: 18px;
            margin-bottom: 30px;
            font-weight: 500;
            text-align: center;
        }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* Footer Brand */
        .brand-footer {
            background: linear-gradient(87deg, #1a1a2e, #16213e);
            color: white;
            padding: 28px;
            border-radius: 22px;
            text-align: center;
            margin: 70px auto 30px;
            max-width: 95%;
            box-shadow: 0 18px 40px rgba(0,0,0,0.3);
        }

        @media (max-width: 768px) {
            .page-header { padding: 60px 0 50px; }
            .page-header h1 { font-size: 2.1rem; }
            .card-body { padding: 35px 25px; }
            .form-control { font-size: 0.92rem; }
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
                <h1>Edit Medicine</h1>
                <p>Update medicine details with precision</p>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-xl-9">

                    <div class="content-card">
                        <div class="card-body text-center">

                            <div class="icon-circle">
                                <i class="fas fa-edit fa-2x text-white"></i>
                            </div>

                            <h3 style="background: linear-gradient(87deg, #f093fb, #f5576c); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; margin-bottom: 8px;">
                                Update Medicine Information
                            </h3>
                            <p class="text-muted" style="font-size: 0.94rem;">Make changes and save</p>

                            <?php if($msg): ?>
                                <div class="alert-msg <?= (strpos($msg, 'successfully') !== false) ? 'alert-success' : 'alert-error'; ?>">
                                    <?= $msg; ?>
                                </div>
                            <?php endif; ?>

                            <?php
                            $cid=$_GET['editid'];
                            $ret=mysqli_query($con,"select * from tblmedicine where ID='$cid'");
                            while ($row=mysqli_fetch_array($ret)) {
                            ?>

                            <form method="post" class="mt-5">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Medicine Company</label>
                                            <select class="form-control" name="compname" required>
                                                <option value="<?php echo $row['MedicineCompany'];?>">
                                                    <?php echo $row['MedicineCompany'];?>
                                                </option>
                                                <?php 
                                                $query1=mysqli_query($con,"select * from tblcompany ORDER BY CompanyName");
                                                while($row1=mysqli_fetch_array($query1)) { ?>
                                                    <option value="<?php echo $row1['CompanyName'];?>">
                                                        <?php echo $row1['CompanyName'];?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Medicine Name</label>
                                            <input type="text" name="medname" class="form-control" 
                                                   value="<?php echo $row['MedicineName'];?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Batch Number</label>
                                            <input type="text" name="batchnumber" class="form-control" 
                                                   value="<?php echo $row['MedicineBatchno'];?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Price per Unit</label>
                                            <input type="text" name="price" class="form-control" 
                                                   value="<?php echo $row['Priceperunit'];?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Manufacturing Date</label>
                                            <input type="date" name="mgfdate" class="form-control" 
                                                   value="<?php echo $row['MgfDate'];?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Expiry Date</label>
                                            <input type="date" name="expirydate" class="form-control" 
                                                   value="<?php echo $row['ExpiryDate'];?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Quantity (Units)</label>
                                    <input type="text" name="quantity" class="form-control" 
                                           value="<?php echo $row['Quantity'];?>" required>
                                </div>

                                <div class="text-center mt-5">
                                    <button type="submit" name="submit" class="btn btn-update">
                                        Update Medicine
                                    </button>
                                </div>

                            </form>
                            <?php } ?>

                        </div>
                    </div>

                    <!-- BRAND FOOTER -->
                    <div class="brand-footer">
                        <h4 style="margin:0; font-weight:600; font-size:1.15rem;">DHARANI PHARMACY</h4>
                        <p style="margin:6px 0 0; font-size:0.9rem; opacity:0.9;">
                            Gampaha • Sri Lanka • Precision Medicine Control
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