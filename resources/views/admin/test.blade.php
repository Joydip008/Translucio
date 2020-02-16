<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Translucio | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('admin_assets/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('admin_assets/bower_components/font-awesome/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('admin_assets/bower_components/Ionicons/css/ionicons.min.css')}}">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{ asset('admin_assets/bower_components/jvectormap/jquery-jvectormap.css')}}">
  <!-- Translucio style -->
  <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/AdminLTE.min.css')}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ asset('admin_assets/dist/css/skins/_all-skins.min.css')}}">


  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">

    <!-- Logo -->
    <a href="{{ asset('admin_assets/super_admin_dashboard.html')}}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>Tr</b>an</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><img src="{{ asset('admin_assets/dist/img/logo.png')}}" class="img-fluid"></span>
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
              <img src="{{ asset('admin_assets/dist/img/credit-images/notification.png')}}" class="notification-alert">
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
           <li><a href=""><img src="{{ asset('admin_assets/dist/img/credit-images/category-img.png')}}" class="cate"></a></li>
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="hidden-xs"><span style="color: #9598b7;">Hi, </span>John Doe</span>
              <img src="{{ asset('admin_assets/dist/img/credit-images/user.png')}}" class="user-image" alt="User Image">
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="{{ asset('admin_assets/dist/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">

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
                  <a href="#" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>

    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="active menu-open">
          <a href="{{ asset('admin_assets/super_admin_dashboard.html')}}">
            <img src="{{ asset('admin_assets/dist/img/dashboard-icons/home.png')}}">
            <span>Dashboard</span>
          </a>
        </li>
        <li class="">
          <a href="{{ asset('admin_assets/super_admin_clients.html')}}">
            <img src="{{ asset('admin_assets/dist/img/dashboard-icons/client.png')}}">
            <span>Clients</span>
          </a>
        </li>
          <li class="">
            <a href="credit_plans.html">
              <img src="{{ asset('admin_assets/dist/img/dashboard-icons/language.png')}}">
              <span>Credit Plan</span>
            </a>
          </li>
        <li>
          <a href="credit_purchase_invoices.html">
            <img src="{{ asset('admin_assets/dist/img/dashboard-icons/credit-purchased.png')}}">
            <span>Credit Purchase & Invoice</span>
          </a>
        </li>
        <li class="">
          <a href="language_pair.html">
             <img src="{{ asset('admin_assets/dist/img/dashboard-icons/language.png')}}">
            <span>Language Pair</span>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard .
        <small>Dashboard</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
      <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12 p-r-6">
          <div class="credit-boxes mb-20">
          <div class="dropdown select_days">
            <button class="dropdown-toggle btn-last" type="button" data-toggle="dropdown">Last 28 Days
            <span class="car_down"><img src="{{ asset('admin_assets/dist/img/credit-images/arrow-down.png')}}"></span>
          </button>
            <ul class="dropdown-menu">
              <li><a href="#">Last 28 Days</a></li>
              <li><a href="#">Last 27 Days</a></li>
              <li><a href="#">Last 26 Days</a></li>
            </ul>
          </div>
          <div class="price"><img src="{{ asset('admin_assets/dist/img/credit-images/round1.png')}}"></div>
          <div class="pricek-amount">
            <h2>56.5k <small><img src="{{ asset('admin_assets/dist/img/credit-images/redarrow.png')}}">-0.4%</small></h2>
            <span class="credit_title">Credit Bought</span>
          </div>
        </div>
          </div>
        <div class="col-md-4 col-sm-6 col-xs-12 p-r-6">
                <div class="credit-boxes mb-20">
          <div class="dropdown select_days">
            <button class="dropdown-toggle btn-last" type="button" data-toggle="dropdown">Last 24 Hours
            <span class="car_down"><img src="{{ asset('admin_assets/dist/img/credit-images/arrow-down.png')}}"></span>
          </button>
            <ul class="dropdown-menu">
              <li><a href="#">Last 28 Days</a></li>
              <li><a href="#">Last 27 Days</a></li>
              <li><a href="#">Last 26 Days</a></li>
            </ul>
          </div>
          <div class="price"><img src="{{ asset('admin_assets/dist/img/credit-images/round2.png')}]"></div>
          <div class="pricek-amount">
            <h2>€178 <small style="color: #219c17;"><img src="{{ asset('admin_assets/dist/img/credit-images/greenarrow.png')}}">15%</small></h2>
            <span class="credit_title">Average Buying Price</span>
          </div>
        </div>
        </div>

        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="credit-boxes mb-20">
          <div class="dropdown select_days">
            <button class="dropdown-toggle btn-last" type="button" data-toggle="dropdown">Last 7 Days
            <span class="car_down"><img src="{{ asset('admin_assets/dist/img/credit-images/arrow-down.png')}}"></span>
          </button>
            <ul class="dropdown-menu">
              <li><a href="#">Last 28 Days</a></li>
              <li><a href="#">Last 27 Days</a></li>
              <li><a href="#">Last 26 Days</a></li>
            </ul>
          </div>
          <div class="price"><img src="{{ asset('admin_assets/dist/img/credit-images/round3.png')}}"></div>
          <div class="pricek-amount">
            <h2>12.8k <small style="color: #219c17;"><img src="{{ asset('admin_assets/dist/img/credit-images/greenarrow.png')}}">15%</small></h2>
            <span class="credit_title">Credit Consumed</span>
          </div>
        </div>
        </div>
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">New Customer Signups</h3>

              <div class="box-tools pull-right">
                  <button type="button" class="btn_customer_details">View All Customer</button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                  <div class="col-md-12">
                    <div class="credit_all_histohry">
                      <div class="history_table history_table_diff">
                   <div class="history_table_part">

  <!--Table-->
  <table class="table">
    <thead>
      <tr>
        <th class="th-lg">Client Name</th>
        <th class="th-lg">Email</th>
        <th class="th-lg">Credit Bought</th>
        <th class="th-lg">Credits Consumed</th>
       <th class="th-lg">Credits Available</th>
       <th class="th-lg">Creation Date</th>
       <th class="th-lg">Last Use Date</th>
      </tr>
    </thead>
    <!--Table head-->

    <!--Table body-->
    <tbody>
      <tr>
        <td><img src="{{ asset('admin_assets/dist/img/dashboard-icons/user.png')}}" class="m-righ">Steven V. Youngs</td>
        <td>steven.youngs@gmail.com</td>
        <td class="td-green">6540</td>
        <td class="td-red">3000</td>
        <td>3540</td>
        <td>01 Jun, 2019 </td>
        <td>25 Jun, 2019 </td>
      </tr>
      <tr>
        <td><img src="{{ asset('admin_assets/dist/img/dashboard-icons/user.png')}}" class="m-righ">Steven V. Youngs</td>
        <td>steven.youngs@gmail.com</td>
        <td class="td-green">6540</td>
        <td class="td-red">3000</td>
        <td>3540</td>
        <td>01 Jun, 2019 </td>
        <td>25 Jun, 2019 </td>
      </tr>
      <tr>
        <td><img src="dist/img/dashboard-icons/user.png" class="m-righ">Steven V. Youngs</td>
        <td>steven.youngs@gmail.com</td>
        <td class="td-green">6540</td>
        <td class="td-red">3000</td>
        <td>3540</td>
        <td>01 Jun, 2019 </td>
        <td>25 Jun, 2019 </td>
      </tr>
       <tr>
        <td><img src="dist/img/dashboard-icons/user.png" class="m-righ">Steven V. Youngs</td>
        <td>steven.youngs@gmail.com</td>
        <td class="td-green">6540</td>
        <td class="td-red">3000</td>
        <td>3540</td>
        <td>01 Jun, 2019 </td>
        <td>25 Jun, 2019 </td>
      </tr>
        <tr>
        <td><img src="dist/img/dashboard-icons/user.png" class="m-righ">Steven V. Youngs</td>
        <td>steven.youngs@gmail.com</td>
        <td class="td-green">6540</td>
        <td class="td-red">3000</td>
        <td>3540</td>
        <td>01 Jun, 2019 </td>
        <td>25 Jun, 2019 </td>
      </tr>
    </tbody>
    <!--Table body-->

    </table>
    <!--Table-->
    </div>
                  </div>
               </div>
                  </div>
              </div>
              <!-- /.row -->
            </div>
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs def">
       <ul class="admin-cpy-list">
        <li><a href="javascript:void(0)">Terms Of Service</a></li>
        <li><a href="javascript:void(0)">Privacy Policy</a></li>
        <li><a href="javascript:void(0)">Contact Us</a></li>
      </ul>
    </div>
    <strong>&copy; 2019 <a href="javascript:void(0)">Transluc.io.</a></strong> All rights
    reserved.
  </footer>
  <!-- Control Sidebar -->
  <div class="control-sidebar-bg"></div>

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{ asset('admin_assets/bower_components/jquery/dist/jquery.min.js')}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('admin_assets/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
<!-- FastClick -->
<script src="{{ asset('admin_assets/bower_components/fastclick/lib/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('admin_assets/dist/js/adminlte.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{ asset('admin_assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')}}"></script>
<!-- jvectormap  -->
<script src="{{ asset('admin_assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{ asset('admin_assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- SlimScroll -->
<script src="{{ asset('admin_assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{ asset('admin_assets/bower_components/chart.js/Chart.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('admin_assets/dist/js/pages/dashboard2.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('admin_assets/dist/js/demo.js')}}"></script>
</body>
</html>
