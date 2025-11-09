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



         <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) == 'pharmacist-chat.php' ? 'active' : ''; ?>">
           <a class="nav-link" href="customer-chat.php">
             <i class="fas fa-robot text-info"></i> Chat with AI
           </a>
         </li>

         <li class="nav-item">
           <a class="nav-link" href="submit-claim.php">
             <i class="fas fa-file-medical text-green"></i> Submit Insurance Claim
           </a>
         </li>
         <li class="nav-item">
           <a class="nav-link" href="my-claims.php">
             <i class="fas fa-list-alt text-orange"></i> My Claims
           </a>
         </li>

       </ul>
       <!-- Divider -->

       <!-- Heading -->

       <!-- Navigation -->

     </div>
   </div>
 </nav>