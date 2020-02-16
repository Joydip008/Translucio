<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li  class="<?php echo e((($left_menu=='dashboard')?'active menu-open':'')); ?>">
          <a href="<?php echo e(url('/admin/admin_dashboard')); ?>">
            <img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/home.png')); ?>">
            <span>Dashboard</span>
          </a>
        </li>
        <li class="<?php echo e((($left_menu=='client')?'active menu-open':'')); ?>">
       
          <a href="<?php echo e(route('client_list')); ?>">
            <img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/client.png')); ?>">
            <span>Clients</span>
          </a>
        </li>
          <li  class="<?php echo e((($left_menu=='credit_plans_list')?'active menu-open':'')); ?>">
            <a href="<?php echo e(route('Credit_Plans_List')); ?>">
              <img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/language.png')); ?>">
              <span>Credit Plan</span>
            </a>
          </li>
        <li  class="<?php echo e((($left_menu=='credit_purchase_invoice')?'active menu-open':'')); ?>">
          <a href="<?php echo e(route('credit_purchase_invoice_list')); ?>">
            <img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/credit-purchased.png')); ?>">
            <span>Credit Purchase & Invoice</span>
          </a>
        </li>
        <li  class="<?php echo e((($left_menu=='language_pair')?'active menu-open':'')); ?>">
          <a href="<?php echo e(route('language_list')); ?>">
             <img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/language.png')); ?>">
             <span>Language Pair</span>
          </a>
        </li>
        <li  class="<?php echo e((($left_menu=='project_category')?'active menu-open':'')); ?>">
          <a href="<?php echo e(route('project_category_list')); ?>">
             <img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/credit-purchased.png')); ?>">
             <span>Project category</span> 
          </a> 
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
<?php /**PATH E:\XAMPP1\htdocs\translucio\translucio\resources\views/partials/admin_sidebar.blade.php ENDPATH**/ ?>