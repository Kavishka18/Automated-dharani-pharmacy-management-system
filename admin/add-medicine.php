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
    $query=mysqli_query($con, "insert into tblmedicine(MedicineCompany,MedicineName,MedicineBatchno,MgfDate,ExpiryDate,Quantity,Priceperunit) value('$compname','$medname','$batchnumber','$mgfdate','$expirydate','$quantity','$price')");
    if ($query) {
        echo "<script>alert('Medicine has been added.');</script>";
        echo "<script>window.location.href ='manage-medicine.php'</script>";
    } else {
        $msg="Something Went Wrong. Please try again";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Medicine - Dharani PMS</title>
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
        .main-content { background: transparent; }
        
        /* Modern Header */
        .page-header {
            background: linear-gradient(87deg, #2dce89 0%, #1e9e6a 100%);
            padding: 70px 0 50px;
            text-align: center;
            color: white;
            border-radius: 0 0 35px 35px;
            box-shadow: 0 20px 40px rgba(45, 206, 137, 0.3);
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
        .card {
            border-radius: 26px;
            border: none;
            box-shadow: 0 18px 50px rgba(0,0,0,0.1);
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(12px);
            margin-top: -75px;
            position: relative;
            z-index: 10;
            overflow: hidden;
        }
        .card-body {
            padding: 45px 40px;
        }

        /* Form Styling */
        .form-control, .form-control:focus {
            border-radius: 16px;
            border: 2px solid #e2e8f0;
            padding: 13px 18px;
            font-size: 0.94rem;
            font-weight: 500;
            transition: all 0.3s ease;
            background: #fafbff;
        }
        .form-control:focus {
            border-color: #2dce89;
            box-shadow: 0 0 0 4px rgba(45, 206, 137, 0.18);
            background: white;
        }
        select.form-control {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%232dce89' viewBox='0 0 16 16'%3e%3cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3e%3c/svg%3e");
            background-position: right 15px center;
            background-repeat: no-repeat;
            background-size: 12px;
        }
        .form-group label {
            font-size: 0.88rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        /* Submit Button */
        .btn-submit {
            background: linear-gradient(87deg, #2dce89, #1e9e6a);
            border: none;
            color: white;
            padding: 14px 48px;
            font-size: 0.98rem;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 12px 28px rgba(45, 206, 137, 0.35);
            transition: all 0.4s ease;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }
        .btn-submit:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 38px rgba(45, 206, 137, 0.45);
        }

        /* Icon Circle */
        .icon-circle {
            width: 68px;
            height: 68px;
            background: linear-gradient(87deg, #2dce89, #1e9e6a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 22px;
            box-shadow: 0 12px 30px rgba(45, 206, 137, 0.3);
        }

        /* Alert */
        .alert-msg {
            font-size: 0.94rem;
            padding: 14px 20px;
            border-radius: 16px;
            margin-bottom: 25px;
            font-weight: 500;
            text-align: center;
        }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* Footer Brand */
        .brand-footer {
            background: linear-gradient(87deg, #1a1a2e, #16213e);
            color: white;
            padding: 22px;
            border-radius: 20px;
            text-align: center;
            margin-top: 50px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.25);
        }

        @media (max-width: 768px) {
            .page-header h1 { font-size: 2.1rem; }
            .card-body { padding: 30px 25px; }
        }
    </style>
</head>
<body class="">
    <?php include_once('includes/navbar.php'); ?>
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>

        <!-- MODERN PAGE HEADER -->
        <div class="page-header">
            <div class="container-fluid">
                <h1>Add New Medicine</h1>
                <p>Register medicine with full batch & expiry details</p>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="container-fluid mt--9">
            <div class="row justify-content-center">
                <div class="col-xl-9">

                    <div class="card">
                        <div class="card-body text-center">

                            <div class="icon-circle">
                                <i class="fas fa-capsules fa-2x text-white"></i>
                            </div>

                            <h3 style="background: linear-gradient(87deg, #2dce89, #1e9e6a); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700; margin-bottom: 8px;">
                                Register Medicine Stock
                            </h3>
                            <p class="text-muted" style="font-size: 0.94rem;">Enter all required details accurately</p>

                            <?php if($msg): ?>
                                <div class="alert-msg alert-error">
                                    <?= $msg; ?>
                                </div>
                            <?php endif; ?>

                            <form method="post" class="mt-4">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Medicine Company</label>
                                            <select class="form-control" name="compname" required>
                                                <option value="">Choose Company</option>
                                                <?php 
                                                $query=mysqli_query($con,"select * from tblcompany ORDER BY CompanyName");
                                                while($row=mysqli_fetch_array($query)) { ?>
                                                    <option value="<?php echo $row['CompanyName'];?>">
                                                        <?php echo htmlentities($row['CompanyName']);?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Medicine Name</label>
                                            <input type="text" name="medname" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Batch Number</label>
                                            <input type="text" name="batchnumber" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Price per Unit</label>
                                            <input type="text" name="price" class="form-control" required placeholder="e.g. 125.00">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Manufacturing Date</label>
                                            <input type="date" name="mgfdate" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Expiry Date</label>
                                            <input type="date" name="expirydate" class="form-control" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Quantity (Total Units)</label>
                                    <input type="text" name="quantity" class="form-control" required placeholder="e.g. 100">
                                </div>

                                <div class="text-center mt-5">
                                    <button type="submit" name="submit" class="btn btn-submit">
                                        Add Medicine
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>

                    <!-- BRAND FOOTER -->
                    <div class="brand-footer">
                        <h4 style="margin:0; font-weight:600; font-size:1.1rem;">DHARANI PHARMACY</h4>
                        <p style="margin:4px 0 0; font-size:0.88rem; opacity:0.9;">
                            Gampaha • Sri Lanka • Smart Inventory System
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