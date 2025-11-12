<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
   <div class="container-fluid">
      <!-- Toggler -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
         <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Brand -->
      <h2 class="navbar-brand pt-0" style="color: Green"><i class="fa fa-plus-square"></i> Dharani Pharmacy</h2>

      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
         <!-- Collapse header (mobile) -->
         <div class="navbar-collapse-header d-md-none">
            <div class="row">
               <div class="col-6 collapse-brand">
                  <a href="index.html">
                     <img src="assets/img/brand/pharmacylogo.jpg">
                  </a>
               </div>
               <div class="col-6 collapse-close">
                  <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                     <span></span>
                     <span></span>
                  </button>
               </div>
            </div>
         </div>

         <!-- Search Form (mobile) -->
         <form class="mt-4 mb-3 d-md-none">
            <div class="input-group input-group-rounded input-group-merge">
               <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="Search" aria-label="Search">
               <div class="input-group-prepend">
                  <div class="input-group-text">
                     <span class="fa fa-search"></span>
                  </div>
               </div>
            </div>
         </form>

         <!-- Navigation -->
         <ul class="navbar-nav">

            <!-- BUBBLE: DASHBOARD -->
            <li class="nav-item">
               <a class="nav-link active d-flex align-items-center position-relative overflow-hidden" href="dashboard.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
                         box-shadow: 0 4px 15px rgba(33, 150, 243, 0.2); transition: all 0.3s ease; border: 1px solid #64b5f6;">
                  <i  style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #1565c0;">Dashboard</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #42a5f5, #1e88e5);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="dashboard.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(33, 150, 243, 0.3) !important; }
                  .nav-item a[href="dashboard.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="dashboard.php"]:hover span:nth-child(2), .nav-item a[href="dashboard.php"]:hover i { color: white !important; }
               </style>
            </li>

            <!-- BUBBLE: SALES HISTORY -->
            <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'sales-history.php') ? 'active' : ''; ?>">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="sales-history.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
                         box-shadow: 0 4px 15px rgba(156, 39, 176, 0.2); transition: all 0.3s ease; border: 1px solid #ce93d8;">
                  <i class="" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #7b1fa2;">Sales History</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #ab47bc, #8e24aa);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="sales-history.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(156, 39, 176, 0.3) !important; }
                  .nav-item a[href="sales-history.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="sales-history.php"]:hover span:nth-child(2), .nav-item a[href="sales-history.php"]:hover i { color: white !important; }
               </style>
            </li>

            <!-- BUBBLE: ISSUE INSURANCE INVOICE -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="issue-insurance-invoice.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #fff8e1 0%, #ffe0b2 100%);
                         box-shadow: 0 4px 15px rgba(255, 152, 0, 0.2); transition: all 0.3s ease; border: 1px solid #ffb74d;">
                  <i class="" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #e65100;">Issue Insurance Invoice</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #ffcc80, #ffb74d);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="issue-insurance-invoice.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(255, 152, 0, 0.3) !important; }
                  .nav-item a[href="issue-insurance-invoice.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="issue-insurance-invoice.php"]:hover span:nth-child(2), .nav-item a[href="issue-insurance-invoice.php"]:hover i { color: white !important; }
               </style>
            </li>

            <!-- BUBBLE: INSURANCE APPROVED HISTORY -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="insurance-approved-history.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
                         box-shadow: 0 4px 15px rgba(76, 175, 80, 0.2); transition: all 0.3s ease; border: 1px solid #a5d6a7;">
                  <i class="" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #2e7d32;">Insurance Approved History</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #66bb6a, #388e3c);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="insurance-approved-history.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3) !important; }
                  .nav-item a[href="insurance-approved-history.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="insurance-approved-history.php"]:hover span:nth-child(2), .nav-item a[href="insurance-approved-history.php"]:hover i { color: white !important; }
               </style>
            </li>

            <!-- BUBBLE: INVENTORY -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="manage-medicine.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #fff8e1 0%, #ffe0b2 100%);
                         box-shadow: 0 4px 15px rgba(255, 152, 0, 0.2); transition: all 0.3s ease; border: 1px solid #ffb74d;">
                  <i class="" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #e65100;">Inventory</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #ffcc80, #ffb74d);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="manage-medicine.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(255, 152, 0, 0.3) !important; }
                  .nav-item a[href="manage-medicine.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="manage-medicine.php"]:hover span:nth-child(2), .nav-item a[href="manage-medicine.php"]:hover i { color: white !important; }
               </style>
            </li>

            <!-- BUBBLE: SEARCH -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="search.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
                         box-shadow: 0 4px 15px rgba(244, 67, 54, 0.2); transition: all 0.3s ease; border: 1px solid #ef9a9a;">
                  <i class="" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #c62828;">Search</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #f44336, #d32f2f);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="search.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(244, 67, 54, 0.3) !important; }
                  .nav-item a[href="search.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="search.php"]:hover span:nth-child(2), .nav-item a[href="search.php"]:hover i { color: white !important; }
               </style>
            </li>

            <!-- BUBBLE: CART (with badge) -->
            <?php
            $cartCount = 0;
            if (isset($_SESSION['pmspid'])) {
               $pmid = $_SESSION['pmspid'];
               $countQ = mysqli_query($con, "SELECT COUNT(*) AS c FROM tblcart WHERE PharmacistId='$pmid' AND IsCheckOut=0");
               $countR = mysqli_fetch_assoc($countQ);
               $cartCount = $countR['c'];
            }
            ?>
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="cart.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
                         box-shadow: 0 4px 15px rgba(33, 150, 243, 0.2); transition: all 0.3s ease; border: 1px solid #64b5f6;">
                  <i class="" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #1565c0;">Cart</span>
                  <?php if ($cartCount > 0): ?>
                     <span class="badge badge-danger ml-2" style="font-size: 0.7rem; z-index: 2; position: relative; top: -1px;"><?php echo $cartCount; ?></span>
                  <?php endif; ?>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #42a5f5, #1e88e5);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="cart.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(33, 150, 243, 0.3) !important; }
                  .nav-item a[href="cart.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="cart.php"]:hover span:nth-child(2), .nav-item a[href="cart.php"]:hover i, .nav-item a[href="cart.php"]:hover .badge { color: white !important; }
               </style>
            </li>

            <!-- BUBBLE: INVOICE SEARCH -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="invoice-search.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
                         box-shadow: 0 4px 15px rgba(244, 67, 54, 0.2); transition: all 0.3s ease; border: 1px solid #ef9a9a;">
                  <i class="" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #c62828;">Invoice Search</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #f44336, #d32f2f);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="invoice-search.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(244, 67, 54, 0.3) !important; }
                  .nav-item a[href="invoice-search.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="invoice-search.php"]:hover span:nth-child(2), .nav-item a[href="invoice-search.php"]:hover i { color: white !important; }
               </style>
            </li>

            <!-- BUBBLE: SALES REPORT -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="pharmacist-report-ds.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
                         box-shadow: 0 4px 15px rgba(76, 175, 80, 0.2); transition: all 0.3s ease; border: 1px solid #a5d6a7;">
                  <i class="" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #2e7d32;">Sales Report</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #66bb6a, #388e3c);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="pharmacist-report-ds.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3) !important; }
                  .nav-item a[href="pharmacist-report-ds.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="pharmacist-report-ds.php"]:hover span:nth-child(2), .nav-item a[href="pharmacist-report-ds.php"]:hover i { color: white !important; }
               </style>
            </li>

            <!-- BUBBLE: AI -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="upload_prescription.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
                         box-shadow: 0 4px 15px rgba(0, 188, 212, 0.2); transition: all 0.3s ease; border: 1px solid #80deea;">
                  <i class="" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #006064;">AI</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #00bcd4, #00838f);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="upload_prescription.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0, 188, 212, 0.3) !important; }
                  .nav-item a[href="upload_prescription.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="upload_prescription.php"]:hover span:nth-child(2), .nav-item a[href="upload_prescription.php"]:hover i { color: white !important; }
               </style>
            </li>

         </ul>
      </div>
   </div>
</nav>