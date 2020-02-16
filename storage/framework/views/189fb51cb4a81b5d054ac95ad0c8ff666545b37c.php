<?php $__env->startSection('title'); ?>
TRANSLICIO | My Page
<?php $__env->stopSection(); ?>
<?php $__env->startSection('add-meta'); ?>
<style type="text/css">
  .t-red{
     font-size: 80%;
     color: #dc3545;
  }
</style>
<?php $__env->stopSection(); ?> 
<?php $__env->startSection('content'); ?>

<div class="translate-document-main personal-main">
        <div class="container custom-con">
          <div class="row">
            <div class="col-md-12 text-center">
              <h2 class="translate-heading profile-heading">My Profile</h2>
              <div class="my_profile_back">
                <div class="row"> 
                 <div class="col-md-4">
                 <form action="<?php echo e(url('/profile-update')); ?>" method="post" enctype="multipart/form-data">
                 <?php if(session()->has('message')): ?>
 <div class="alert alert-danger alert-dismissible" runat ="server" id="modalEditError" visible ="false">
  <button class="close" type="button" data-dismiss="alert">×</button>
        <?php echo e(session()->get('message')); ?>

    </div>
  <?php endif; ?>
                       <?php echo csrf_field(); ?>
                   <div class="profile_pic_upload">
                      <div class="avatar-upload">
        <div class="avatar-edit">
            <input type="file" onchange="readURL(this)" id="imageUpload" accept="image*" / name="profile_image" value="<?php echo e(old('profile_image')); ?>" autocomplete="profile_image" autofocus>
           
            <label for="imageUpload">
              <img src="<?php echo e(url('assets/images/camera.png')); ?>" class="img-fluid">
            </label>
        </div>

        <div class="avatar-preview">
          <?php if(Auth::user()->profile_image): ?> 
            <div style="background-image: url(assets/upload/user/<?php echo e(Auth::user()->profile_image); ?>;">
            <?php else: ?>
            <div id="imagePreview" style="background-image: url('assets/images/dummy.jpg');">
            <?php endif; ?>
            </div> 
        </div>
    </div>
                   </div>
                 </div>
                 <div class="col-md-8">
                     <div class="registration-form">
                     
                        <div class="row">
						          <!-- <div class="col-md-12">
                          <div class="form-group">
                            <label for="exampleFormControlSelect1">Title<i class="fa fa-star star"></i></label>
                            <select class="form-control" id="exampleFormControlSelect1" name = "title" value=<?php echo e(Auth::user()->title); ?>>
                              <option>Title1</option>
                              <option>Title2</option>
                              <option>Title3</option>
                              <option>Title4</option>
                              <option>Title5</option>
                            </select>
                          </div>
                          <?php if($errors->has('title')): ?>
                            <span class="invalid-feedback" role="alert">
                                <strong><?php echo e($errors->first('title')); ?></strong>
                            </span>
                        <?php endif; ?>
                        </div> -->
						
                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="name">Full Name<i class="fa fa-star star"></i></label>
                            <select class="form-control custom-w" id="exampleFormControlSelect1" name="honorific" value="<?php echo e(Auth::user()->title); ?>">
                              <!-- <option>Mr.</option> -->
                              <option value="Mr." <?php echo e(( Auth::user()->title == "Mr.") ? 'selected' : ''); ?>> Mr. </option>
                              <!-- <option>Mrs.</option>  -->
                              <option value="Mrs." <?php echo e(( Auth::user()->title == "Mrs.") ? 'selected' : ''); ?>> Mrs. </option>
                            </select>
                            <?php if($errors->has('fname')): ?>
                             <input type="text" class="form-control c-width" name="fname" id="fname" value=>
                             <?php else: ?>
                             <input type="text" class="form-control c-width" name="fname" id="fname" value="<?php echo e(Auth::user()->name); ?>">
                             <?php endif; ?>
                            <?php if($errors->has('lname')): ?>
                             <input type="text" class="form-control c-width" name="lname" id="lname" value=>
                            <?php else: ?>
                            <input type="text" class="form-control c-width" name="lname" id="lname" value="<?php echo e(Auth::user()->last_name); ?>">
                            <?php endif; ?>
                            </div>
                          <?php if($errors->has('fname')): ?>
                          <span class="help-block">
                              <strong class="error-msg"><?php echo e($errors->first('fname')); ?></strong>
                            </span>
                          <?php endif; ?>
                          <?php if($errors->has('lname')): ?>
                          <span class="help-block">
                              <strong class="error-msg"><?php echo e($errors->first('lname')); ?></strong>
                            </span>
                          <?php endif; ?>
                        </div>

                        <div class="col-md-12">
                        <div class="form-group">
                          <label>Company Name&nbsp;</span></label>
                          <div class="input-group">
                              <div class="">
                              <i class=""></i>
                              </div>
                              <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo e(old('company_name')); ?>" autocomplete="company_name" autofocus></input>
                              </div>
                                </div>
                         </div>

                        <div class="col-md-12">
                        <div class="form-group">
                          <label>Company Vat Number&nbsp;</span></label>
                          <div class="input-group">
                              <div class="">
                              <i class=""></i>
                              </div>
                              <input type="text" class="form-control" id="company_vat_number" name="company_vat_number" value="<?php echo e(old('company_vat_number')); ?>" autocomplete="company_vat_number" autofocus></input>
                              </div>
                                
                                </div>
                         </div>
                       
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Address Line 1&nbsp;<i class="fa fa-star star"></i></span></label>
                          <div class="input-group">
                              <div class="">
                              <i class=""></i>
                              </div>
                              <?php if($errors->has('address1')): ?>
                              <input type="text" class="form-control" id="address1" name="address1" value="" required autocomplete="address1" autofocus></input>
                              <?php else: ?>
                              <input type="text" class="form-control" id="address1" name="address1" value="<?php echo e(old('address1')); ?>" required autocomplete="address1" autofocus></input>
                             
                              <?php endif; ?>
                            </div>
							                    <?php if($errors->has('address1')): ?>
                                <span class="help-block">
                                    <strong class="error-msg mb-2"><?php echo e($errors->first('address1')); ?></strong>
                                 </span>
                                <?php endif; ?>
                                </div>
                         </div>
                        

                        <div class="col-md-12"> 
                          <div class="form-group">
                            <label for="addressline1">Address line 2</label>
                            <input type="text" class="form-control" id="address2" name="address2" value="<?php echo e(old('address2')); ?>" autocomplete="address2" autofocus></input>
                          </div>
                        </div>

                        <div class="col-md-6">
                        <div class="form-group">
                          <label>City&nbsp;<i class="fa fa-star star"></i></span></label>
                          <div class="input-group">
                              <div class=""> 
                              <i class=""></i>
                              </div>
                              <?php if($errors->has('city')): ?>
                              <input type="text" class="form-control" id="city" name="city" value="" required autocomplete="city" autofocus></input>
                              <?php else: ?>
                              <input type="text" class="form-control" id="city" name="city" value="<?php echo e(old('city')); ?>" required autocomplete="city" autofocus></input>
                    
                              <?php endif; ?>
                            
                            </div>
							                    <?php if($errors->has('city')): ?>
                                <span class="help-block">
                                    <strong class="error-msg mb-2"><?php echo e($errors->first('city')); ?></strong>
                                 </span>
                                <?php endif; ?>
                                </div>
                         </div>
                        
                         <div class="col-md-6">
                        <div class="form-group">
                          <label>Region&nbsp;<i class="fa fa-star star"></i></span></label>
                          <div class="input-group">
                              <div class="">
                              <i class=""></i>
                              </div>
                              <?php if($errors->has('region')): ?>
                              <input type="text" class="form-control" id="region" name="region" value="" required autocomplete="region" autofocus></input>
                            <?php else: ?>
                            <input type="text" class="form-control" id="region" name="region" value="<?php echo e(old('region')); ?>" required autocomplete="region" autofocus></input>
                        
                            <?php endif; ?>
                            </div>
							                    <?php if($errors->has('region')): ?>
                                <span class="help-block">
                                    <strong class="error-msg mb-2"><?php echo e($errors->first('region')); ?></strong>
                                 </span>
                                <?php endif; ?>
                                </div>
                         </div>


                         <div class="col-md-6">
                        <div class="form-group">
                          <label>Country&nbsp;<i class="fa fa-star star"></i></span></label>
                          <div class="input-group">
                              <div class="">
                              <i class=""></i>
                              </div>
                              <!-- <input type="text" class="form-control" id="country" name="country"></input> -->
                              <select class="form-control" id="exampleFormControlSelect1" name="country" value="<?php echo e(old('country')); ?>" required autocomplete="country" autofocus>
                
                              
                              <option value=""> -- Select One --</option>
                              <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <!-- <option value="<?php echo e($country->id); ?>"><?php echo e($country->name); ?></option> -->
                                  <?php if($errors->has('country')): ?>
                                <option value="<?php echo e($country->id); ?>"><?php echo e($country->name); ?></option>
                                <?php else: ?>
                                  <option value="<?php echo e($country->id); ?>" <?php echo e(($country->id == old('country')) ? 'selected' : ''); ?>><?php echo e($country->name); ?></option>
                            <?php endif; ?>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                             
                            </select>
                            </div>
							                    <?php if($errors->has('country')): ?>
                                <span class="help-block">
                                    <strong class="error-msg mb-2"><?php echo e($errors->first('country')); ?></strong>
                                 </span>
                                <?php endif; ?>
                                </div>
                         </div>
                        <div class="frm_submit">
                          <!-- <button type="button" class="but_sub">Submit</button> -->
                          <button type="submit" class="btn btn-primary" name="submit" id="submit" >Save</button>
                        </div>
                        </form>
                     </div>
                   </div>
                 </div>
               </div>  
            </div> 
            </div>
          </div>
        </div>
        

<div id="profile_pic_modal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Change Profile Picture</h3>
    </div>
    <div class="modal-body">
      <form id="cropimage" method="post" enctype="multipart/form-data" action="change_pic.php">
        <strong>Upload Image:</strong> <br><br>
        <input type="file" name="profile-pic" id="profile-pic" />
        <input type="hidden" name="hdn-profile-id" id="hdn-profile-id" value="1" />
        <input type="hidden" name="hdn-x1-axis" id="hdn-x1-axis" value="" />
        <input type="hidden" name="hdn-y1-axis" id="hdn-y1-axis" value="" />
        <input type="hidden" name="hdn-x2-axis" value="" id="hdn-x2-axis" />
        <input type="hidden" name="hdn-y2-axis" value="" id="hdn-y2-axis" />
        <input type="hidden" name="hdn-thumb-width" id="hdn-thumb-width" value="" />
        <input type="hidden" name="hdn-thumb-height" id="hdn-thumb-height" value="" />
        <input type="hidden" name="action" value="" id="action" />
        <input type="hidden" name="image_name" value="" id="image_name" />
          <div id='preview-profile-pic'></div>
            <div id="thumbs" style="padding:5px; width:600p"></div>
      </form>
    </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" id="save_crop" class="btn btn-primary">Crop & Save</button>
  </div>
</div>
</div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('add-js'); ?>
    <script src="assets/js/jquery-1.9.1.min.js" ></script>
    <script src="assets/js/bootstrap.js" ></script>
    <script src="assets/js/custom.js"></script>
<?php $__env->stopSection(); ?>
 
<script language="javascript">
  
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        //console.log(e.target);
        $('#imagePreview').css('background-image', 'url('+e.target.result +')');
        $('#imagePreview').hide();
        $('#imagePreview').fadeIn(650);
      }
    reader.readAsDataURL(input.files[0]);
  }
}
// $("#imageUpload").change(function() {
//     readURL(this);
// });
</script>
<?php echo $__env->make('layouts.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\XAMPP1\htdocs\translucio\translucio\resources\views/user/myprofile.blade.php ENDPATH**/ ?>