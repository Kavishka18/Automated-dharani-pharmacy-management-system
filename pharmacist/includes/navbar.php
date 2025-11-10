 <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
   <div class="container-fluid">
     <!-- Toggler -->
     <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
       <span class="navbar-toggler-icon"></span>
     </button>
     <!-- Brand -->
     <h2 class="navbar-brand pt-0" style="color: red"><i class=" fa fa-plus-square"></i> PMS</h2>
     <!-- User -->

     <!-- Collapse -->
     <div class="collapse navbar-collapse" id="sidenav-collapse-main">
       <!-- Collapse header -->
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
       <!-- Form -->
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
         <li class="nav-item  class=" active=''>
           <a class=" nav-link active " href="dashboard.php"> <i class="ni ni-tv-2 text-primary"></i> Dashboard
           </a>
         </li>

         <!-- NEW: SALES HISTORY -->
         <li class="nav-item <?php echo (basename($_SERVER['PHP_SELF']) == 'sales-history.php') ? 'active' : ''; ?>">
           <a class="nav-link" href="sales-history.php">
             <i class="fas fa-history text-purple"></i>
             Sales History
           </a>
         </li>

         <li class="nav-item">
           <a class="nav-link" href="issue-insurance-invoice.php">
             <i class="fas fa-file-medical text-orange"></i>
             <span>Issue Insurance Invoice</span>
           </a>
         </li>

         <li class="nav-item">
           <a class="nav-link" href="insurance-approved-history.php">
             <i class="fas fa-shield-alt text-green"></i>
             <span>Insurance Approved History</span>
           </a>
         </li>



         <li class="nav-item">
           <a class="nav-link " href="manage-medicine.php">
             <i class="ni ni-palette text-orange"></i> Inventory
           </a>
         </li>
         <li class="nav-item">
           <a class="nav-link " href="search.php">
             <i class="fa fa-search text-red"></i> Search
           </a>
         </li>

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
           <a class="nav-link" href="cart.php">
             <i class="fas fa-shopping-cart text-primary"></i>
             Cart
             <?php if ($cartCount > 0): ?>
               <span class="badge badge-danger"><?php echo $cartCount; ?></span>
             <?php endif; ?>
           </a>
         </li>
         <li class="nav-item">
           <a class="nav-link " href="invoice-search.php">
             <i class="fa fa-search text-red"></i> Invoice Search
           </a>
         </li>
         <li class="nav-item">
           <a class="nav-link" href="pharmacist-report-ds.php">
             <i class="ni ni-ui-04 text-green"></i> Sales Report
           </a>
         </li>

         

    
     

       </ul>
       <!-- Divider -->

       <!-- Heading -->

       <!-- Navigation -->

     </div>
   </div>
 </nav>