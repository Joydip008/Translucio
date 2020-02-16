<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Translucio')); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo asset('assets/css/font-awesome.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/bootstrap.min.css'); ?>">
    <!-- custom css -->
   
    <link rel="stylesheet" href="<?php echo asset('assets/css/style.css'); ?>">
     <link rel="stylesheet" type="text/css" href="<?php echo asset('assets/css/responsive.css'); ?>">
     
  </head>
  
  <body>
    <!-- start top header section -->
       
    <?php echo $__env->make('partials.afterLogin_header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
  <!-- menu -->
    <?php echo $__env->make('partials.dashboard_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->yieldContent('content'); ?>

  <?php echo $__env->make('partials.afterLogin_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- end footer section -->
<!-- start js section -->
    <script src="<?php echo asset('assets/js/jquery-1.9.1.min.js'); ?>" ></script>
    
    <script src="<?php echo asset('assets/js/bootstrap.js'); ?>" ></script>
    <script src="<?php echo asset('assets/js/custom.js'); ?>"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script> 
    
   
    
    
    </body>
</html><?php /**PATH E:\XAMPP1\htdocs\translucio\translucio\resources\views/layouts/home.blade.php ENDPATH**/ ?>