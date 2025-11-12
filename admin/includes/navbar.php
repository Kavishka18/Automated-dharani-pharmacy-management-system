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
         <!-- Main Navigation -->
         <!-- <ul class="navbar-nav"> -->

            <!-- BUBBLE: DASHBOARD -->
            <li class="nav-item">
               <a class="nav-link active d-flex align-items-center position-relative overflow-hidden" href="dashboard.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
                         box-shadow: 0 4px 15px rgba(33, 150, 243, 0.2); transition: all 0.3s ease; border: 1px solid #64b5f6;">
                  <i class="ni ni-tv-2 text-primary mr-3" style="font-size: 1.2rem; z-index: 2;"></i>
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

            <!-- BUBBLE: INVOICE SEARCH -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="invoice-search.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #fff8e1 0%, #ffe0b2 100%);
                         box-shadow: 0 4px 15px rgba(255, 152, 0, 0.2); transition: all 0.3s ease; border: 1px solid #ffb74d;">
                  <i class="fa fa-search text-orange mr-3" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #e65100;">Invoice Search</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #ffcc80, #ffb74d);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="invoice-search.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(255, 152, 0, 0.3) !important; }
                  .nav-item a[href="invoice-search.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="invoice-search.php"]:hover span:nth-child(2), .nav-item a[href="invoice-search.php"]:hover i { color: white !important; }
               </style>
            </li>

            <!-- BUBBLE: MEDICINE INVENTORY -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="medicine-inventory.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
                         box-shadow: 0 4px 15px rgba(0, 188, 212, 0.2); transition: all 0.3s ease; border: 1px solid #80deea;">
                  <i class="ni ni-fat-add text-blue mr-3" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #006064;">Medicine Inventory</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #00bcd4, #00838f);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="medicine-inventory.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0, 188, 212, 0.3) !important; }
                  .nav-item a[href="medicine-inventory.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="medicine-inventory.php"]:hover span:nth-child(2), .nav-item a[href="medicine-inventory.php"]:hover i { color: white !important; }
               </style>
            </li>

            <!-- <hr class="my-3"> -->

            <!-- BUBBLE: EXPIRED ALERTS -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="expired-alerts.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
                         box-shadow: 0 4px 15px rgba(244, 67, 54, 0.2); transition: all 0.3s ease; border: 1px solid #ef9a9a;">
                  <i class="ni ni-bell-55 text-red mr-3" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #c62828;">Expired Alerts</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #f44336, #d32f2f);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="expired-alerts.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(244, 67, 54, 0.3) !important; }
                  .nav-item a[href="expired-alerts.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="expired-alerts.php"]:hover span:nth-child(2), .nav-item a[href="expired-alerts.php"]:hover i { color: white !important; }
               </style>
            </li>

            <!-- BUBBLE: MONTHLY REPORT -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="monthly-report.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
                         box-shadow: 0 4px 15px rgba(76, 175, 80, 0.2); transition: all 0.3s ease; border: 1px solid #a5d6a7;">
                  <i class="ni ni-chart-bar-32 text-info mr-3" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #2e7d32;">Monthly Report</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #66bb6a, #388e3c);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="monthly-report.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3) !important; }
                  .nav-item a[href="monthly-report.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="monthly-report.php"]:hover span:nth-child(2), .nav-item a[href="monthly-report.php"]:hover i { color: white !important; }
               </style>
            </li>

            <!-- BUBBLE: INSURANCE APPROVED HISTORY -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="insurance-approved-history.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #fce4ec 0%, #f8bbd0 100%);
                         box-shadow: 0 4px 15px rgba(233, 30, 99, 0.2); transition: all 0.3s ease; border: 1px solid #f48fb1;">
                  <i class="fas fa-shield-alt text-red mr-3" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #880e4f;">Insurance Approved History</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #ec407a, #c2185b);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="insurance-approved-history.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(233, 30, 99, 0.3) !important; }
                  .nav-item a[href="insurance-approved-history.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="insurance-approved-history.php"]:hover span:nth-child(2), .nav-item a[href="insurance-approved-history.php"]:hover i { color: white !important; }
               </style>
            </li>

         </ul>

         <!-- <hr class="my-3"> -->
         <!-- <h6 class="navbar-heading text-muted">Pharmacy Company</h6> -->
         <!-- <ul class="navbar-nav mb-md-3"> -->

            <!-- BUBBLE: ADD COMPANY -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="add-pharmacy-company.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
                         box-shadow: 0 4px 15px rgba(0, 188, 212, 0.2); transition: all 0.3s ease; border: 1px solid #80deea;">
                  <i class="ni ni-fat-add text-blue mr-3" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #006064;">Add Company</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #00bcd4, #00838f);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="add-pharmacy-company.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0, 188, 212, 0.3) !important; }
                  .nav-item a[href="add-pharmacy-company.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="add-pharmacy-company.php"]:hover span:nth-child(2), .nav-item a[href="add-pharmacy-company.php"]:hover i { color: white !important; }
               </style>
            </li>

            <!-- BUBBLE: MANAGE COMPANY -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="manage-company.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
                         box-shadow: 0 4px 15px rgba(244, 67, 54, 0.2); transition: all 0.3s ease; border: 1px solid #ef9a9a;">
                  <i class="ni ni-palette text-red mr-3" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #c62828;">Manage Company</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #f44336, #d32f2f);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="manage-company.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(244, 67, 54, 0.3) !important; }
                  .nav-item a[href="manage-company.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="manage-company.php"]:hover span:nth-child(2), .nav-item a[href="manage-company.php"]:hover i { color: white !important; }
               </style>
            </li>

         </ul>

         <!-- BUBBLE: INVENTORY ANALYSIS -->
         

         <!-- <hr class="my-3"> -->
         <!-- <h6 class="navbar-heading text-muted">Medicine</h6> -->
         <!-- <ul class="navbar-nav mb-md-3"> -->

            <!-- BUBBLE: ADD MEDICINE -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="add-medicine.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
                         box-shadow: 0 4px 15px rgba(0, 188, 212, 0.2); transition: all 0.3s ease; border: 1px solid #80deea;">
                  <i class="ni ni-fat-add text-blue mr-3" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #006064;">Add Medicine</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #00bcd4, #00838f);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="add-medicine.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0, 188, 212, 0.3) !important; }
                  .nav-item a[href="add-medicine.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="add-medicine.php"]:hover span:nth-child(2), .nav-item a[href="add-medicine.php"]:hover i { color: white !important; }
               </style>
            </li>

            <!-- BUBBLE: MANAGE MEDICINE -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="manage-medicine.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
                         box-shadow: 0 4px 15px rgba(244, 67, 54, 0.2); transition: all 0.3s ease; border: 1px solid #ef9a9a;">
                  <i class="ni ni-palette text-red mr-3" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #c62828;">Manage Medicine</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #f44336, #d32f2f);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="manage-medicine.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(244, 67, 54, 0.3) !important; }
                  .nav-item a[href="manage-medicine.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="manage-medicine.php"]:hover span:nth-child(2), .nav-item a[href="manage-medicine.php"]:hover i { color: white !important; }
               </style>
            </li>

         </ul>
         <!-- <hr class="my-3"> -->
         <!-- <h6 class="navbar-heading text-muted">Pharmacist</h6> -->
         <!-- <ul class="navbar-nav mb-md-3"> -->

            <!-- BUBBLE: ADD PHARMACIST -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="add-pharmacist.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
                         box-shadow: 0 4px 15px rgba(33, 150, 243, 0.2); transition: all 0.3s ease; border: 1px solid #64b5f6;">
                  <i class="ni ni-circle-08 text-blue mr-3" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #1565c0;">Add Pharmacist</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #42a5f5, #1e88e5);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="add-pharmacist.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(33, 150, 243, 0.3) !important; }
                  .nav-item a[href="add-pharmacist.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="add-pharmacist.php"]:hover span:nth-child(2), .nav-item a[href="add-pharmacist.php"]:hover i { color: white !important; }
               </style>
            </li>

         </ul>

         <li class="nav-item">
            <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="medicine-inventory-analysis.php"
               style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
                      box-shadow: 0 4px 15px rgba(255, 152, 0, 0.2); transition: all 0.3s ease; border: 1px solid #ffcc80;">
               <i class="ni ni-chart-bar-32 text-orange mr-3" style="font-size: 1.2rem; z-index: 2;"></i>
               <span style="font-weight: 600; z-index: 2; color: #e65100;">Inventory Analysis</span>
               <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #ffb74d, #ff8a65);
                     border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
            </a>
            <style>
               .nav-item a[href="medicine-inventory-analysis.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(255, 152, 0, 0.3) !important; }
               .nav-item a[href="medicine-inventory-analysis.php"]:hover span:last-child { transform: translateX(0%) !important; }
               .nav-item a[href="medicine-inventory-analysis.php"]:hover span:nth-child(2), .nav-item a[href="medicine-inventory-analysis.php"]:hover i { color: white !important; }
            </style>
         </li>

         <!-- <hr class="my-3"> -->
         <!-- <h6 class="navbar-heading text-muted">Reports</h6> -->
         <!-- <ul class="navbar-nav mb-md-3"> -->

            <!-- BUBBLE: STOCK REPORTS -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="bwdates-reports-ds.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
                         box-shadow: 0 4px 15px rgba(0, 188, 212, 0.2); transition: all 0.3s ease; border: 1px solid #80deea;">
                  <i class="ni ni-spaceship text-blue mr-3" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #006064;">Stock Reports</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #00bcd4, #00838f);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="bwdates-reports-ds.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0, 188, 212, 0.3) !important; }
                  .nav-item a[href="bwdates-reports-ds.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="bwdates-reports-ds.php"]:hover span:nth-child(2), .nav-item a[href="bwdates-reports-ds.php"]:hover i { color: white !important; }
               </style>
            </li>

            <!-- BUBBLE: PHARMACIST WISE REPORTS -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="pharmacist-report-ds.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
                         box-shadow: 0 4px 15px rgba(244, 67, 54, 0.2); transition: all 0.3s ease; border: 1px solid #ef9a9a;">
                  <i class="ni ni-palette text-red mr-3" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #c62828;">Pharmacist wise Reports</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #f44336, #d32f2f);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="pharmacist-report-ds.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(244, 67, 54, 0.3) !important; }
                  .nav-item a[href="pharmacist-report-ds.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="pharmacist-report-ds.php"]:hover span:nth-child(2), .nav-item a[href="pharmacist-report-ds.php"]:hover i { color: white !important; }
               </style>
            </li>

            <!-- BUBBLE: SALES REPORT -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="sales-reports.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
                         box-shadow: 0 4px 15px rgba(76, 175, 80, 0.2); transition: all 0.3s ease; border: 1px solid #a5d6a7;">
                  <i class="ni ni-ui-04 text-green mr-3" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #2e7d32;">Sales Report</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #66bb6a, #388e3c);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="sales-reports.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3) !important; }
                  .nav-item a[href="sales-reports.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="sales-reports.php"]:hover span:nth-child(2), .nav-item a[href="sales-reports.php"]:hover i { color: white !important; }
               </style>
            </li>

         </ul>
      </div>
   </div>
</nav>