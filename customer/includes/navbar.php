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

            <!-- BUBBLE: CHAT WITH AI -->
            <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'customer-chat.php' ? 'active' : ''; ?>">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="customer-chat.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
                         box-shadow: 0 4px 15px rgba(0, 188, 212, 0.2); transition: all 0.3s ease; border: 1px solid #80deea;">
                  <i class="fas fa-robot text-info mr-3" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #006064;">Chat with AI</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #00bcd4, #00838f);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="customer-chat.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0, 188, 212, 0.3) !important; }
                  .nav-item a[href="customer-chat.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="customer-chat.php"]:hover span:nth-child(2), .nav-item a[href="customer-chat.php"]:hover i { color: white !important; }
               </style>
            </li>

            <!-- BUBBLE: SUBMIT INSURANCE CLAIM -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="submit-claim.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
                         box-shadow: 0 4px 15px rgba(76, 175, 80, 0.2); transition: all 0.3s ease; border: 1px solid #a5d6a7;">
                  <i class="fas fa-file-medical text-green mr-3" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #2e7d32;">Submit Insurance Claim</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #66bb6a, #388e3c);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="submit-claim.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3) !important; }
                  .nav-item a[href="submit-claim.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="submit-claim.php"]:hover span:nth-child(2), .nav-item a[href="submit-claim.php"]:hover i { color: white !important; }
               </style>
            </li>

            <!-- BUBBLE: MY CLAIMS -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="my-claims.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #fff8e1 0%, #ffe0b2 100%);
                         box-shadow: 0 4px 15px rgba(255, 152, 0, 0.2); transition: all 0.3s ease; border: 1px solid #ffb74d;">
                  <i class="fas fa-list-alt text-orange mr-3" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #e65100;">My Claims</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #ffcc80, #ffb74d);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="my-claims.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(255, 152, 0, 0.3) !important; }
                  .nav-item a[href="my-claims.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="my-claims.php"]:hover span:nth-child(2), .nav-item a[href="my-claims.php"]:hover i { color: white !important; }
               </style>
            </li>

         </ul>
      </div>
   </div>
</nav>