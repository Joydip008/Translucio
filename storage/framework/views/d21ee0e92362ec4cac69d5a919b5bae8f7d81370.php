<?php $__env->startSection('title', 'Translucio|Dashboard'); ?> 

<?php $__env->startSection('content'); ?>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard .
        <small>Project Category</small> 
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-8">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Project Category</h3>
              <button type="button" class="lng_add_button add-category"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/plus.png')); ?>">Add New</button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                  <div class="col-md-12">
                    <div class="credit_all_histohry">
                      <div class="history_table language_pair_table">
                   <div class="history_table_part">

  <!--Table-->
  <table class="table">
     <thead>
      <tr>
       <th class="th-lg">Project Category</th>
       <th class="th-lg">Description</th>
       <th class="th-lg">Status</th>
       <th class="th-lg">Edit</th>
       <th class="th-lg">Delete</th>
      </tr>
    </thead>
    <!--Table head-->

    <!--Table body-->
    <tbody>
    <?php $__currentLoopData = $projectCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr> 
        <td><?php echo e($project['catagories']); ?></td>
        <td>
        <?php echo e($project['description']); ?>

        </td>
        <?php if($project['status']==1): ?>
        <td class="td-green">Active</td>
        <?php else: ?>
        <td class="td-red">Inactive</td>
        <?php endif; ?>
        <td>
          <span><a href="<?php echo e(url('/admin/add-project-category/'.$project['id'])); ?>" class="plan-edit_button"><img
            src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/edit.png')); ?>"></a></span>                                       
        </td>
        <td>
          <span><a href="#" class="plan-edit_button" onclick = "DeleteProjectCategory('<?php echo e($project['id']); ?>')"><img
            src="<?php echo e(asset('assets/images/icons/delete.png')); ?>"></a></span>
        </td>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
    <!--Table body-->

    </table>
    <!--Table-->
    </div>
    <div class="col-md-12">
      <div class="pagination_right"> 
      
        <!-- <ul>
          <li><a href="javascript:void(0)"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
          </li>
          <li><a href="javascript:void(0)"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
          </li>
          <li><span class="activea">1</span>
          </li>
          <li><a href="javascript:void(0)" class="bg-none">2</a>
          </li>
          <li><a href="javascript:void(0)" class="bg_dark"><i class="fa fa-angle-right"
                aria-hidden="true"></input></a>
          </li>
          <li><a href="javascript:void(0)" class="bg_dark"><i class="fa fa-angle-double-right"
                aria-hidden="true"></input></a>
          </li>
        </ul> -->
      </div>
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

        

        <div class="col-md-4 p-l p-right-30">
          <div class="box box-success bx-grey category_hide" id="category_hide">
            <div class="box-header">
              <h3 class="box-title">Add Project Category</h3>
            </div>
            <div class="box-body bx-form lng_frm">
        <form method="post" action="<?php echo e(route('add_project_category')); ?>">
        <?php echo csrf_field(); ?>
           <div class="form-row">
            <div class="form-group col-md-12">
              <label for="credit multiplier">Project Category</label>
              <input type="text" class="form-control" id="" value="" name="name" required>
              <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/category_right.png')); ?>"></span>
            </div>
           </div>

          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="credit multiplier">Description</label>
              <textarea class="form-control area_text" rows="5" name="description" required></textarea>
            </div>
           </div>

           <div class="form-row">
             <div class="check_left">
             <label class="form_radio">
              <input type="radio" name="status" value="1"/>
               <span class="checkmark">Active</span>
             </label>
             <label class="form_radio">
              <input type="radio" name="status" value="2"/>
               <span class="checkmark">Inactive</span>
             </label>
             </div>

              
          </div>
          <div class="form-row">
              <button type="submit" class="btn-default add_to_credit">Save</button>
          </div>

          </form>
            </div>
          </div>
          
      <?php if(!empty($ProjectCatagoriesDetails)): ?>
      <div class="box box-success bx-grey ">
            <div class="box-header">
              <h3 class="box-title">Update Project Category</h3>
            </div>
            <div class="box-body bx-form lng_frm">
        <form method="post" action="<?php echo e(route('add_project_category')); ?>">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="id" value="<?php echo e($ProjectCatagoriesDetails['id']); ?>">
           <div class="form-row">
            <div class="form-group col-md-12">
              <label for="credit multiplier">Project Category</label>
              <input type="text" class="form-control" id="" value="<?php echo e($ProjectCatagoriesDetails['catagories']); ?>" name="name" required>
              <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/category_right.png')); ?>"></span>
            </div>
           </div>

          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="credit multiplier">Description</label>
              <textarea class="form-control area_text" rows="5" name="description" required><?php echo e($ProjectCatagoriesDetails['description']); ?></textarea>
            </div>
           </div>

           <div class="form-row">
             <div class="check_left">
             <label class="form_radio">
              <input type="radio" name="status" value="1" value="1" <?php if( "1" == $ProjectCatagoriesDetails['status']): ?> ? checked : null <?php endif; ?>>
               <span class="checkmark">Active</span>
             </label>
             <label class="form_radio">
              <input type="radio" name="status" value="2" value="1" <?php if( "2" == $ProjectCatagoriesDetails['status']): ?> ? checked : null <?php endif; ?>>
               <span class="checkmark">Inactive</span>
             </label>
             </div>

              
          </div>
          <div class="form-row">
              <button type="submit" class="btn-default add_to_credit">Save</button>
          </div>

          </form>
            </div>
          </div>

<?php endif; ?>
        </div>
      </div>

  </div>
  </section>

  
  <?php $__env->stopSection(); ?>
<!-- jQuery 3 -->
<script>




function DeleteProjectCategory(id){
  // alert(id);

  var r = confirm("Are you sure to delete!");
      if (r == true) {
        $.ajax({
          type: "GET", 
          headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
          dataType: "json",
          data: {'id' : id},
          url: "<?php echo e(route('delete_project_category')); ?>", 
          success:function(message){ 
            console.log(message);
              if(message['success']){
                  location.reload();
              }
              else{
                  alert('Can not delete due to Restriction, It is already link to some project types!!');
              }
          }
        });
      }
      else {
        txt = "You pressed Cancel!";
      }
  }



</script>



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\XAMPP1\htdocs\translucio\translucio\resources\views/admin/projectCategory/project_category.blade.php ENDPATH**/ ?>