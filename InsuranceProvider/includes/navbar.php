<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
   <div class="container-fluid">
      <!-- Toggler -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main">
         <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Brand -->
      <a class="navbar-brand" href="dashboard.php">
         <h2 style="color: #28a745; font-weight: 700; margin: 0; padding: 10px 0;">
            Insurance Provider
         </h2>
      </a>

      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
         <!-- Navigation -->
         <ul class="navbar-nav">

            <!-- BUBBLE: DASHBOARD -->
            <li class="nav-item">
               <a class="nav-link active d-flex align-items-center position-relative overflow-hidden" href="dashboard.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
                         box-shadow: 0 4px 15px rgba(40, 167, 69, 0.2); transition: all 0.3s ease; border: 1px solid #a5d6a7;">
                  <i class="" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #2e7d32;">Dashboard</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #66bb6a, #388e3c);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="dashboard.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3) !important; }
                  .nav-item a[href="dashboard.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="dashboard.php"]:hover span:nth-child(2), .nav-item a[href="dashboard.php"]:hover i { color: white !important; }
               </style>
            </li>

            <!-- BUBBLE: REPORTS & HISTORY -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="reports.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%);
                         box-shadow: 0 4px 15px rgba(156, 39, 176, 0.2); transition: all 0.3s ease; border: 1px solid #ce93d8;">
                  <i class="" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #7b1fa2;">Reports & History</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #ab47bc, #8e24aa);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="reports.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(156, 39, 176, 0.3) !important; }
                  .nav-item a[href="reports.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="reports.php"]:hover span:nth-child(2), .nav-item a[href="reports.php"]:hover i { color: white !important; }
               </style>
            </li>

                        <!-- BUBBLE: LOGOUT -->
            <li class="nav-item">
               <a class="nav-link d-flex align-items-center position-relative overflow-hidden" href="logout.php"
                  style="color:#000; text-decoration:none; padding: 12px 20px; border-radius: 50px; background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
                         box-shadow: 0 4px 15px rgba(244, 67, 54, 0.2); transition: all 0.3s ease; border: 1px solid #ef9a9a;">
                  <i class="" style="font-size: 1.2rem; z-index: 2;"></i>
                  <span style="font-weight: 600; z-index: 2; color: #c62828;">Logout</span>
                  <span class="position-absolute" style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #f44336, #d32f2f);
                        border-radius: 50px; transform: translateX(-100%); transition: transform 0.4s ease; z-index: 1;"></span>
               </a>
               <style>
                  .nav-item a[href="logout.php"]:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(244, 67, 54, 0.3) !important; }
                  .nav-item a[href="logout.php"]:hover span:last-child { transform: translateX(0%) !important; }
                  .nav-item a[href="logout.php"]:hover span:nth-child(2), .nav-item a[href="logout.php"]:hover i { color: white !important; }
               </style>
            </li>

         </ul>
      </div>
   </div>
</nav>