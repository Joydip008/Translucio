<header class="main-header">

<!-- Logo -->
<a href="<?php echo e(asset('assets/admin_assets/super_admin_dashboard.html')); ?>" class="logo">
  <!-- mini logo for sidebar mini 50x50 pixels -->
  <span class="logo-mini"><b>Tr</b>an</span>
  <!-- logo for regular state and mobile devices -->
  <span class="logo-lg"><img src="<?php echo e(asset('assets/admin_assets/dist/img/logo.png')); ?>" class="img-fluid"></span>
</a>

<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top">
  <!-- Sidebar toggle button-->
  <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
    <span class="sr-only">Toggle navigation</span>
  </a>
  <!-- Navbar Right Menu -->
  <div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
      <!-- Notifications: style can be found in dropdown.less -->
      <li class="dropdown notifications-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
          <img src="<?php echo e(asset('assets/admin_assets/dist/img/credit-images/notification.png')); ?>" class="notification-alert">
        </a>
        <ul class="dropdown-menu">
          <li class="header">You have 10 notifications</li>
          <li>
            <!-- inner menu: contains the actual data -->
            <ul class="menu">
              <li>
                <a href="#">
                  <i class="fa fa-users text-aqua"></i> 5 new members joined today
                </a>
              </li>
              <li>
                <a href="#">
                  <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
                  page and may cause design problems
                </a>
              </li>
              <li>
                <a href="#">
                  <i class="fa fa-users text-red"></i> 5 new members joined
                </a>
              </li>
              <li>
                <a href="#">
                  <i class="fa fa-shopping-cart text-green"></i> 25 sales made
                </a>
              </li>
              <li>
                <a href="#">
                  <i class="fa fa-user text-red"></i> You changed your username
                </a>
              </li>
            </ul>
          </li>
          <li class="footer"><a href="#">View all</a></li>
        </ul>
      </li>
       <li><a href=""><img src="<?php echo e(asset('assets/admin_assets/dist/img/credit-images/category-img.png')); ?>" class="cate"></a></li>
      <li class="dropdown user user-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <span class="hidden-xs"><span style="color: #9598b7;">Hi, </span>John Doe</span>
          <img src="<?php echo e(asset('assets/admin_assets/dist/img/credit-images/user.png')); ?>" class="user-image" alt="User Image">
        </a>
        <ul class="dropdown-menu">
          <!-- User image -->
          <li class="user-header">
            <img src="<?php echo e(asset('assets/admin_assets/dist/img/user2-160x160.jpg')); ?>" class="img-circle" alt="User Image">

            <p>
              John Doe
              <small>Member since Nov. 2019</small>
            </p>
          </li>
          <!-- Menu Body -->
          <li class="user-body">
            <div class="row">
              <div class="col-xs-4 text-center">
                <a href="#">Followers</a>
              </div>
              <div class="col-xs-4 text-center">
                <a href="#">Sales</a>
              </div>
              <div class="col-xs-4 text-center">
                <a href="#">Friends</a>
              </div>
            </div>
            <!-- /.row -->
          </li>
          <!-- Menu Footer-->
          <li class="user-footer">
            <div class="pull-left">
              <a href="#" class="btn btn-default btn-flat">Profile</a>
            </div>
            <div class="pull-right">
              <!-- <a class="btn btn-default btn-flat">Sign out</a> -->
             
             <a href="<?php echo e(route('logout')); ?>"
                      onclick="event.preventDefault();
                          document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">Sign out</a>
              <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                  <?php echo csrf_field(); ?>
              </form>
            </div>
          </li>
        </ul>
      </li>
    </ul>
  </div>

</nav>
</header>
<script src="<?php echo e(asset('assets/admin_assets/bower_components/jquery/dist/jquery.min.js')); ?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo e(asset('assets/admin_assets/bower_components/bootstrap/dist/js/bootstrap.min.js')); ?>"></script>
<!-- FastClick -->
<script src="<?php echo e(asset('assets/admin_assets/bower_components/fastclick/lib/fastclick.js')); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo e(asset('assets/admin_assets/dist/js/adminlte.min.js')); ?>"></script>
<!-- Sparkline -->
<script src="<?php echo e(asset('assets/admin_assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')); ?>"></script>
<!-- jvectormap  -->
<script src="<?php echo e(asset('assets/admin_assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/admin_assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')); ?>"></script>
<!-- SlimScroll -->
<script src="<?php echo e(asset('assets/admin_assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')); ?>"></script>
<!-- ChartJS -->
<script src="<?php echo e(asset('assets/admin_assets/bower_components/chart.js/Chart.js')); ?>"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo e(asset('assets/admin_assets/dist/js/pages/dashboard2.js')); ?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo e(asset('assets/admin_assets/dist/js/demo.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/admin_custom.js')); ?>"></script>

<?php /**PATH E:\XAMPP1\htdocs\translucio\translucio\resources\views/partials/admin_header.blade.php ENDPATH**/ ?>