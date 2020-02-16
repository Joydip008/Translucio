<?php $__env->startSection('title', 'Translucio|Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard .
        <small>Clients</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border"> 
              <h3 class="box-title">Clients</h3>

              <div class="box-tools pull-right">
                  <a href="javascript:void(0)"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
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
        <th class="th-lg">Contract Type</th>
        <th class="th-lg">Credits Included</th>
       <th class="th-lg">Credits Available</th>
       <th class="th-lg">Creation Date</th>
       <th class="th-lg">Last Use Date</th>
      </tr>
    </thead>
    <!--Table head-->

    <!--Table body-->
    <tbody>
        <?php $__currentLoopData = $userDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr>
        <td><a href="<?php echo e(url('/admin/client-details/'.$user['id'])); ?>"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/user.png')); ?>" class="m-righ"><?php echo e($user['name']); ?> <?php echo e($user['last_name']); ?></a></td>
        <td><?php echo e($user['email']); ?></td>
        <td class="td-green"><?php echo e($user['PlanName']); ?> / <?php echo e($user['PlanPeriod']); ?></td>
        <td class="td-green"><?php echo e($user['CreditIncludes']); ?></td>
        <?php if($user['AvailableCredits'] < 0): ?>
          <td class="td-red"><?php echo e($user['AvailableCredits']); ?></td>
        <?php elseif($user['AvailableCredits'] >= 0): ?>
          <td class="td-green"><?php echo e($user['AvailableCredits']); ?></td>
        <?php endif; ?>
        <td><?php echo e($user['created_at']); ?> </td>
        <td><?php echo e($user['last_login_at']); ?> </td>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
    <!--Table body-->

    </table>
        <div class="col-md-12">
      <div class="pagination_right">
      <?php echo $__env->make('vendor.pagination.custom', ['paginator' => $results, 'link_limit' => 3], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                   
                  </div>
    </div>
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\XAMPP1\htdocs\translucio\translucio\resources\views/admin/clients/client_list.blade.php ENDPATH**/ ?>