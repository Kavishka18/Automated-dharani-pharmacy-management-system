<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
    <div class="container-fluid">
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="dashboard.php">
            Dashboard
        </a>
        <ul class="navbar-nav align-items-center d-none d-md-flex">
            <li class="nav-item dropdown">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown">
                    <div class="media align-items-center position-relative">
   <!-- Avatar with Gradient Border -->
   <div class="avatar avatar-sm rounded-circle mr-3 position-relative" 
        style="background: linear-gradient(135deg, #42a5f5, #1e88e5); padding: 2px; box-shadow: 0 4px 12px rgba(33,150,243,0.3);">
      <img alt="User Avatar" src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['providername']); ?>&background=1e88e5&color=fff&bold=true&size=64" 
           class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
      <!-- Online Status Dot -->
      <span class="position-absolute" 
            style="bottom: 2px; right: 2px; width: 10px; height: 10px; background: #4caf50; border: 2px solid white; border-radius: 50%; box-shadow: 0 0 8px rgba(76,175,80,0.6);">
      </span>
   </div>

   <!-- User Name with Hover Effect -->
   <div class="media-body ml-2 d-none d-lg-block">
      <span class="mb-0 text-sm font-weight-bold position-relative overflow-hidden d-inline-block"
            style="color: #2c3e50; padding: 4px 8px; border-radius: 20px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                   transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
         <?php echo htmlspecialchars($_SESSION['providername']); ?>
         <span class="position-absolute" 
               style="top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #42a5f5, #1e88e5);
                      border-radius: 20px; transform: translateX(-100%); transition: transform 0.3s ease; z-index: -1;"></span>
      </span>
   </div>
</div>

<!-- Hover Animation Style -->
<style>
   .media:hover .media-body span {
      color: white !important;
      transform: translateY(-1px);
   }
   .media:hover .media-body span > span:last-child {
      transform: translateX(0%) !important;
   }
   .media:hover .avatar {
      transform: scale(1.08);
      box-shadow: 0 6px 16px rgba(33,150,243,0.4) !important;
   }
</style>
                </a>
            </li>
        </ul>
    </div>
</nav>