<?php $__env->startSection('title', 'Translucio|Dashboard'); ?>

<?php $__env->startSection('content'); ?>

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper" >
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Dashboard .
          <small>Credit Products</small>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content" > 
        <div class="row">
          <div class="col-md-8">
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">Credit Plan</h3>
                <?php if(session()->has('message')): ?>
                  <div class="alert alert-success">
                    <?php echo e(session()->get('message')); ?>

                  </div>
                <?php endif; ?>

                <button type="button" class="lng_add_button" onclick="myFunction()"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/plus.png')); ?>">Add New</button>
       
                <!-- <button type="button" class="btn-default add_new_button"><i class="fa fa-plus" aria-hidden="true"></i>
                  Create plan</button> -->
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="credit_all_histohry">
                      <div class="history_table credit_products_tbl">
                        <div class="history_table_part">

                          <!--Table-->
                          <table class="table">
                            <thead>
                              <tr>
                                <th class="th-lg">Plan Name</th>
                                <th class="th-lg">Amount</th>
                                <th class="th-lg">Description</th>
                                <th class="th-lg">Action</th> 
                              </tr>
                            </thead>
                            <!--Table head-->

                            <!--Table body-->
                            <tbody>
                              <?php $__currentLoopData = $plansDetailsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr>
                                <td><?php echo e($plan['plan_name']); ?></td>
                                <td>€ <?php echo e($plan['monthly_cost']); ?></td>
                                <td>Max no. of Language :<?php echo e($plan['max_languages']); ?>,  Preview Included :  <?php echo e($plan['included_pageviews']); ?>,  Extra Preview ( per 10,000):  €<?php echo e($plan['extra_cost_pageviews']); ?>  ,  Translation Credits:  <?php echo e($plan['translation_credits']); ?>,  Additional Character ( per 10,000):  €<?php echo e($plan['additional_characters']); ?></td>
                                <td>
                                <span><a href="<?php echo e(url('/admin/credit-plans/'.$plan['id'])); ?>" class="plan-edit_button"><img
                                        src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/edit.png')); ?>"></a></span>
<!--                                         <span><a href="javascript:void(0)"><img src="dist/img/dashboard-icons/close.png"></a></span>
 -->                                </td>
                              </tr>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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


          <!-- UPDATE PLAN  -->
          <?php if(!empty($updatePlansDetails)): ?>
          <div class="col-md-4 p-l p-right-30" id="add_show">
            <div class="box box-success bx-grey">
              <div class="loader" id="loader">
                <img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/833.gif')); ?>" />
              </div>
             <div class="form-inner-container" id="form-content">
                  <div class="box-header">
                      <h3 class="box-title">Plan Name : <?php echo e($updatePlansDetails['plan_name']); ?></h3>
                    </div>
                    <div class="box-body bx-form">
                      <form action="<?php echo e(url('/admin/save-plans/'.$updatePlansDetails['period_id'])); ?>" method="post">
                      <?php echo csrf_field(); ?>
                      <input type="hidden" id="<?php echo e($updatePlansDetails['id']); ?>" value="<?php echo e($updatePlansDetails['id']); ?>">
                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="inputZip">Monthly cost</label>
                            <div class="price-details">
                             <input type="text" class="form-control amount_first" id="amount" value="<?php echo e($updatePlansDetails['monthly_cost']); ?>" name="monthly_cost" required pattern="^\d*(\.\d{0,2})?$" disabled>
                             <span class="symbol"><i class="fa fa-euro" aria-hidden="true"></i></span>
                            </div>
                            <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/note.png')); ?>"></span>
                          </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                              <label for="inputEmail4">Max number of languages</label>
                              <input type="text" class="form-control" id="cre" value="<?php echo e($updatePlansDetails['max_languages']); ?>" name="max_languages" required onkeypress="return isNumberKey(event)">
                              <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/language-new.png')); ?>"></span>
                            </div>
                          </div>


                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="inputEmail4">Included pageviews</label>
                            <input type="text" class="form-control" id="cre" value="<?php echo e($updatePlansDetails['included_pageviews']); ?>" name="included_pageviews" required onkeypress="return isNumberKey(event)">
                            <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/data.png')); ?>"></span>
                          </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-12">
                              <label for="inputZip"> Extra cost of pageviews / <?php echo e(config('constants.EXTRA_COST_OF_PAGE_VIEWS_PER')); ?></label>
                            <div class="price-details">
                             <input type="text" class="form-control amount_first" id="amount" value="<?php echo e($updatePlansDetails['extra_cost_pageviews']); ?>" required name="extra_cost_pageviews" pattern="^\d*(\.\d{0,2})?$">
                             <span class="symbol"><i class="fa fa-euro" aria-hidden="true"></i></span>
                            </div>
                            <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/note.png')); ?>"></span>
                            </div>
        
                          </div>
                          <div class="form-row">
                              <div class="form-group col-md-12">
                                <label for="inputEmail4">Translation credits</label>
                                <input type="text" class="form-control" id="cre" value="<?php echo e($updatePlansDetails['translation_credits']); ?>" name="translation_credits" required onkeypress="return isNumberKey(event)">
                                <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/data.png')); ?>"></span>
                              </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                  <label for="inputEmail4">Additional cost per <?php echo e(config('constants.ADDITIONAL_CHARACTER_PER_PAGE')); ?> credit</label>
                                  <div class="price-details">
                                  <input type="text" class="form-control amount_first" id="cre" value="<?php echo e($updatePlansDetails['additional_characters']); ?>" name="additional_characters" required onkeypress="return isNumberKey(event)">
                                    <span class="symbol"><i class="fa fa-euro" aria-hidden="true"></i></span>
                                  </div>
                                   <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/note.png')); ?>"></span>
                                  </div>
                              </div>
      
                        <div class="form-row">
                          <button type="submit" class="btn-default add_to_credit">Update Plan</button>
                        </div>
                      </form>
                    </div>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <!-- Add NEW PLAN-->
          <div class="col-md-4 p-l p-right-30" id="add_new_plan">
            <div class="box box-success bx-grey">
              <div class="loader" id="loader">
                <img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/833.gif')); ?>" />
              </div>
             <div class="form-inner-container" id="form-content">
                  <div class="box-header">
                    </div>
                    <div class="box-body bx-form">
                      <form action="<?php echo e(url('/admin/add-plans_success')); ?>" method="post">
                      <?php if(session()->has('message')): ?>
                          <div class="alert alert-success">
                            <?php echo e(session()->get('message')); ?>

                          </div>
                      <?php endif; ?>
                      <?php echo csrf_field(); ?>
                      <div class="form-row">
                          <div class="form-group col-md-12">
                         <label for="inputZip">Plan Name</label>
                            <div class="price-details">
                             <input type="text" class="form-control amount_first" id="new_plan_name"  required name="new_plan_name" required>
                             <!-- <span class="symbol"><i class="fa fa-euro" aria-hidden="true"></i></span> -->
                            </div>
                            <!-- <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/note.png')); ?>"></span> -->
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-12">
                         <label for="inputZip">Monthly cost</label>
                            <div class="price-details">
                             <input type="text" class="form-control amount_first" id="amount"  required name="monthly_cost" pattern="^\d*(\.\d{0,2})?$">
                             <span class="symbol"><i class="fa fa-euro" aria-hidden="true"></i></span>
                            </div>
                            <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/note.png')); ?>"></span>
                          </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                              <label for="inputEmail4">Max number of languages</label>
                              <input type="text" class="form-control" id="cre"  required name="max_languages" onkeypress="return isNumberKey(event)">
                              <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/language-new.png')); ?>"></span>
                            </div>
                          </div>
                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="inputEmail4">Included pageviews</label>
                            <input type="text" class="form-control" id="cre" required name="included_pageviews" onkeypress="return isNumberKey(event)">
                            <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/data.png')); ?>"></span>
                          </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                              <label for="inputZip"> Extra cost of pageviews / <?php echo e(config('constants.EXTRA_COST_OF_PAGE_VIEWS_PER')); ?></label>
                            <div class="price-details">
                             <input type="text" class="form-control amount_first" required id="amount" name="extra_cost_pageviews" pattern="^\d*(\.\d{0,2})?$">
                             <span class="symbol"><i class="fa fa-euro" aria-hidden="true"></i></span>
                            </div>
                            <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/note.png')); ?>"></span>
                            </div>
                          </div>
                          <div class="form-row">
                              <div class="form-group col-md-12">
                                <label for="inputEmail4">Translation credits</label>
                                <input type="text" class="form-control" id="cre" required name="translation_credits" onkeypress="return isNumberKey(event)">
                                <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/data.png')); ?>"></span>
                              </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                  <label for="inputEmail4">Additional cost per <?php echo e(config('constants.ADDITIONAL_CHARACTER_PER_PAGE')); ?> credit</label>
                                  <div class="price-details">
                                  <input type="text" class="form-control amount_first" required id="cre" name="additional_characters" onkeypress="return isNumberKey(event)">
                                    <span class="symbol"><i class="fa fa-euro" aria-hidden="true"></i></span>
                                  </div>
                                   <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/note.png')); ?>"></span>
                                </div>
                              </div>
                        <div class="form-row">
                          <button type="submit" class="btn-default add_to_credit">Add Plan</button>
                        </div>
                      </form>
                    </div>
              </div>
            </div>
          </div>
          
        </div>

        <!-- /.row -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

<?php $__env->stopSection(); ?>

<script>
window.onload = function() {
  $("#add_new_plan").hide();
};
function myFunction(id) {
  var x = document.getElementById("add_new_plan");
  if (x.style.display === "none") {
    x.style.display = "block";
    $("#add_show").hide();
  } else {
    x.style.display = "none";
  }
}
</script>
<SCRIPT language=Javascript>
      <!--
      function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }
      //-->
   </SCRIPT>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\XAMPP1\htdocs\translucio\translucio\resources\views/admin/creditPlans/credit_plans.blade.php ENDPATH**/ ?>