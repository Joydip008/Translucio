<?php $__env->startSection('title', 'Translucio|Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" >
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard .
        <small>Language Pair</small>
      </h1>
    </section>
    <?php if(Session::has('message')): ?>
      <div class="alert alert-info"><?php echo e(Session::get('message')); ?></div>
    <?php endif; ?>
    <?php if(Session::has('message_languageHave')): ?>
      <div class="alert alert-danger"><?php echo e(Session::get('message_languageHave')); ?></div> 
    <?php endif; ?>

    <!-- Main content -->
    <section class="content" >
      <div class="row">
        <div class="col-md-8">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Language Pair</h3>
              <button type="button" class="lng_add_button add-category" ><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/plus.png')); ?>">Add New</button>
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
       <th class="th-lg">Language Pair</th>
       <th class="th-lg">API Used</th>
       <th class="th-lg">Credit Multiplier</th>
       <th class="th-lg">Do Not Translate</th>
       <th class="th-lg">Always Translate</th>
        <th class="th-lg">Action</th>
        <th class="th-lg">Edit</th>
       <th class="th-lg">Delete</th>
      </tr>
    </thead>
    <!--Table head-->

    <!--Table body-->
    
    <tbody>
    <?php $__currentLoopData = $LanguagePairDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $LanguagePair): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <tr> 
        <td><?php echo e($LanguagePair['from_language_name']); ?> - <?php echo e($LanguagePair['to_language_name']); ?></td>
        <?php if($LanguagePair['api']=='G'): ?>
        <td><span class="span_price"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/translate.png')); ?>"></span>Google</td>
        <?php else: ?>
        <td><span class="span_price"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/deeple.png')); ?>"></span>Deeple</td>
        <?php endif; ?>
        <td>
        <?php echo e($LanguagePair['credit_multiplier']); ?>

        </td>
        <?php if($LanguagePair['do_not_translate']==1): ?>
          <td class="td-green">Active</td>
        <?php else: ?>
          <td class="td-red">Inactive</td>
        <?php endif; ?>
        <?php if($LanguagePair['always_translate_as']==1): ?>
          <td class="td-green">Active</td>
        <?php else: ?>
          <td class="td-red">Inactive</td>
        <?php endif; ?>

        <?php if($LanguagePair['status']==1): ?>
        <td class="td-green">Active</td>
        <?php else: ?>
        <td class="td-red">Inactive</td>
        <?php endif; ?>

        <td>
          <span><a href="<?php echo e(url('/admin/add-language-pair/'.$LanguagePair['id'])); ?>" class="plan-edit_button"><img
            src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/edit.png')); ?>"></a></span>                                       
        </td>
        <td>
          <span><a href="#" class="plan-edit_button" onclick = "DeleteLanguagePair('<?php echo e($LanguagePair['id']); ?>')"><img
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
              <h3 class="box-title">Add Language Pair</h3>
            </div>
            <div class="box-body bx-form lng_frm">
        <form action="<?php echo e(url('/admin/add-language-pair')); ?>" method="post">
        <?php echo csrf_field(); ?>
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="from">From</label>
              <select class="form-control lang-custom-select" name="from" onchange="FromLanguageId(this.value,'ToLanguageId')" >
                <option value="">--Select One--</option> 
              <?php $__currentLoopData = $LanguageListDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $LanguageList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($LanguageList->id); ?>"><?php echo e($LanguageList->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/from_icon.png')); ?>"></span>
              <span class="slect_image"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/arrow-key.svg')); ?>"></span>
            </div>
          </div> 
           <div class="form-row">
            <div class="form-group col-md-12">
              <label for="to">To</label>
                <select class="form-control lang-custom-select" name="to" id="ToLanguageId">
                  <option>--Select One--</option>
              </select>
              <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/from_icon.png')); ?>"></span>
               <span class="slect_image"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/arrow-key.svg')); ?>"></span>
            </div>
          </div>
           <div class="form-row">
            <div class="form-group col-md-12">
              <label for="inputEmail4">API Used</label>
                <select class="form-control lang-custom-select" name="api" >
              	<option value="G">Google</option>
              	<option value="D">Deeple</option>
              </select>	
              <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/deeple-select.png')); ?>"></span>
              <span class="slect_image"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/arrow-key.svg')); ?>"></span>
            </div>
          </div>
           <div class="form-row">
            <div class="form-group col-md-12">
              <label for="credit multiplier">Credit Multiplier</label>
              <input type="text" class="form-control" id="" name="credit_multiplier" pattern="^\d*(\.\d{0,2})?$" required>
              <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/wallet.png')); ?>"></span>
            </div>
            <?php if($errors->has('credit_multiplier')): ?>
            <span class="help-block">
                <strong class="error-msg mb-2"><?php echo e($errors->first('credit_multiplier')); ?></strong>
            </span>
            <?php endif; ?>
          </div>
           <div class="form-row">
             <div class="check_left">
             <label class="form_radio">
              <input type="radio" name="status" value="1" />
               <span class="checkmark">Active</span>
             </label>
             <label class="form_radio">
              <input type="radio" name="status" value="2"/>
               <span class="checkmark">Inactive</span> 
             </label>
             </div>
          </div>
          <div class="custom_lang">
            <label class="dont_translate" >
              <p>Do Not Translate </p>
              <span><input type="checkbox" name="do_not_translate" value="1"></span></label>
            <label class="dont_translate"> 
            <p>Always Translate As </p>
                <span><input type="checkbox" name="always_translate_as" value="1"></span></label>
            </div>
          <div class="form-row">
              <button type="submit" class="btn-default add_to_credit">Save</button>
          </div>

          </form>
            </div>
          </div>
          <?php if(!empty($languagePairUpdate)): ?>



          <div class="box box-success bx-grey" id="UpdateLanguagePair">
            <div class="box-header">
              <h3 class="box-title">Update Language Pair</h3>
            </div>
            <div class="box-body bx-form lng_frm">
        <form action="<?php echo e(url('/admin/add-language-pair')); ?>" method="post">
        <?php echo csrf_field(); ?>
          <input type="hidden" name="id" value="<?php echo e($languagePairUpdate['id']); ?>">
          <div class="form-row">
            <div class="form-group col-md-12">
              <label for="from">From</label>
              <select class="form-control lang-custom-select" name="from" onchange="FromLanguageId(this.value,'ToLanguageIdEdit')" >
                <option>--Select One--</option> 
              <?php $__currentLoopData = $LanguageListDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $LanguageList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($LanguageList->id); ?>" <?php if( $LanguageList['id'] == $languagePairUpdate['from_language']): ?> ? selected : null <?php endif; ?>><?php echo e($LanguageList->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/from_icon.png')); ?>"></span>
              <span class="slect_image"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/arrow-key.svg')); ?>"></span>
            </div>
          </div> 
           <div class="form-row">
            <div class="form-group col-md-12">
              <label for="to">To</label>
                <select class="form-control lang-custom-select" name="to" id="ToLanguageIdEdit">
                  <option  value="">--Select One--</option>
                  <?php $__currentLoopData = $rest_language_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $LanguageList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($LanguageList->id); ?>" <?php if( $LanguageList['id'] == $languagePairUpdate['to_language']): ?> ? selected : null <?php endif; ?>><?php echo e($LanguageList->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/from_icon.png')); ?>"></span>
               <span class="slect_image"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/arrow-key.svg')); ?>"></span>
            </div>
          </div>
           <div class="form-row">
            <div class="form-group col-md-12">
              <label for="inputEmail4">API Used</label>
                <select class="form-control lang-custom-select" name="api">
              	<option value="G" <?php if( "G" == $languagePairUpdate['api']): ?> ? selected : null <?php endif; ?>>Google</option>
              	<option value="D" <?php if( "D" == $languagePairUpdate['api']): ?> ? selected : null <?php endif; ?>>Deeple</option>
              </select>	
              <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/deeple-select.png')); ?>"></span>
              <span class="slect_image"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/arrow-key.svg')); ?>"></span>
            </div>
          </div>
           <div class="form-row">
            <div class="form-group col-md-12">
              <label for="credit multiplier">Credit Multiplier</label>
              <input type="text" class="form-control" id="" name="credit_multiplier" pattern="^\d*(\.\d{0,2})?$" required value="<?php echo e($languagePairUpdate['credit_multiplier']); ?>">
              <span class="credit_img"><img src="<?php echo e(asset('assets/admin_assets/dist/img/dashboard-icons/wallet.png')); ?>"></span>
            </div>
            <?php if($errors->has('credit_multiplier')): ?>
            <span class="help-block">
                <strong class="error-msg mb-2"><?php echo e($errors->first('credit_multiplier')); ?></strong>
            </span>
            <?php endif; ?>
          </div>
           <div class="form-row">
             <div class="check_left">
             <label class="form_radio">
              <input type="radio" name="status" value="1" <?php if( "1" == $languagePairUpdate['status']): ?> ? checked : null <?php endif; ?>>
               <span class="checkmark">Active</span>
             </label>
             <label class="form_radio">
              <input type="radio" name="status" value="2" <?php if( "2" == $languagePairUpdate['status']): ?> ? checked : null <?php endif; ?>>
               <span class="checkmark">Inactive</span>
             </label>
             </div>
          </div>
          <div class="custom_lang">
            <label class="dont_translate" >
              <p>Do Not Translate </p>
              <span><input type="checkbox" name="do_not_translate" value="1" <?php if( "1" == $languagePairUpdate['do_not_translate']): ?> ? checked : null <?php endif; ?>></span></label>
            <label class="dont_translate"> 
            <p>Always Translate As </p>
                <span><input type="checkbox" name="always_translate_as" value="1" <?php if( "1" == $languagePairUpdate['always_translate_as']): ?> ? checked : null <?php endif; ?>></span></label>
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

      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <?php $__env->stopSection(); ?>
  
<!-- jQuery 3 -->



<script>

function FromLanguageId(val,toId){

// alert(val);
    $.ajax({
        headers: {'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'}, 
        url:"<?php echo e(url('/admin/rest-languagePair-list')); ?>",
		    type: "POST",
		    data: {'id':val},
      
		   success: function(response){
        var obj = $.parseJSON(response);
       $("#ToLanguageId").empty();
       $("#ToLanguageIdEdit").empty();
         $.each(obj, function(index, element) {  
         
           $("#"+toId).append("<option value='"+ element.id +"' >" + element.name + "</option>");
         });
      
		    }
		});
  
}



function DeleteLanguagePair(id){
  // alert(id);

  var r = confirm("Are you sure to delete!");
      if (r == true) {
        $.ajax({
          type: "GET", 
          headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
          dataType: "json",
          data: {'id' : id},
          url: "<?php echo e(route('delete_language_pair')); ?>", 
          success:function(message){ 
            console.log(message);
              if(message['success']){
                  location.reload();
              }
              else{
                  alert('Can not delete due to Restriction, It is already link to some projects!!');
              }
          }
        });
      }
      else {
        txt = "You pressed Cancel!";
      }
  }


  </script> 



<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\XAMPP1\htdocs\translucio\translucio\resources\views/admin/language_pair.blade.php ENDPATH**/ ?>