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

<!-- start my profile section css your document section -->
<div class="translate-document-main personal-main">
        <div class="container custom-con">
          <div class="row">
            <div class="col-md-12 text-center"> 
            <form action="<?php echo e(url('/profile-update')); ?>" method="post" enctype="multipart/form-data">
           
                       <?php echo csrf_field(); ?>
              <h2 class="translate-heading profile-heading">My Profile</h2>
            </div>
              <div class="col-lg-3 col-md-12 p-r">
                <div class="my-profile-left">
                  <div class="part_1">
                    
                    <?php if(Auth::user()->profile_image): ?> 
                  <div class="people people-profile-img"><img src="<?php echo asset('assets/upload/user/'.Auth::user()->profile_image); ?>" class="img-responsive"></div>
                 
                  <?php else: ?>
                  <div class="people people-profile-img"><img src="<?php echo e(url('/assets/images/user.png')); ?>" class="img-responsive"></div>
                 <?php endif; ?>
                  <div class="people-title people-profile">
                  <h3><?php echo e(Auth::user()->name); ?> <?php echo e(Auth::user()->last_name); ?></h3>
                  <span class="people-id">#555FREDS8HFT</span>
                </div>
                </div>

                <div class="part_2">
                  <div><label>Email:</label> <span><?php echo e(Auth::user()->email); ?></span></div>
                  <?php if(Auth::user()->company_name): ?>
                  <div><label>Company Name</label> <span><?php echo e(Auth::user()->company_name); ?></span></div>
                  <?php endif; ?>
                  <!-- <div><label>Phone:</label> <span>+61 29192 0995</span></div> -->
                  <div><label>Location:</label> <span><?php echo e(Auth::user()->city); ?> - <?php echo e($country_name['name']); ?></span></div>
                </div>
 
              <div class="part_3">
                  <h3 class="overview_title">My Profile</h3>
                  <button type="button" onclick="location.href='<?php echo e(route('my_profile')); ?>';" class="btn_personal_information"><img src="<?php echo e(url('/assets/images/card/user.png')); ?>" class="img-responsive">My Profile</button>
                  <button type="button" onclick="location.href='<?php echo e(route('change_password')); ?>'" class="btn_change_information"><img src="<?php echo e(url('/assets/images/card/lock1.png')); ?>" class="img-responsive">Change Password</button>
                  <!-- <button type="button" onclick="location.href='';" class="btn_api_information"><img src="<?php echo e(url('/assets/images/card/api-key.png')); ?>" class="img-responsive">Api Key</button> -->
                </div>
               </div>
              </div>
              <div class="col-lg-9 col-md-12">
                <div class="my-profile-right"> 
                  <div class="registration-form personal_form">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="profile_pic_upload">
                          
  <?php if(session()->has('message')): ?>
 <div class="alert alert-danger alert-dismissible" runat ="server" id="modalEditError" visible ="false">
  <button class="close" type="button" data-dismiss="alert">×</button>
        <?php echo e(session()->get('message')); ?>

    </div>
  <?php endif; ?>
                                <div class="avatar-upload personal-upload">
                                  <div class="avatar-edit personal-edit">
                                      <input type="file" onchange="readURL(this)" id="imageUpload" accept="image*" name="profile_image" value="<?php echo e(old('profile_image')); ?>" autocomplete="profile_image" autofocus>
                                      <label for="imageUpload">
                                        <img src="<?php echo e(url('/assets/images/camera.png')); ?>" class="img-fluid">
                                      </label>
                                  </div>
                                  <div class="avatar-preview"> 
                                  <?php if(Auth::user()->profile_image): ?>

                                  <div id="imagePreview" style="background-image: url(assets/upload/user/<?php echo e(Auth::user()->profile_image); ?>);">
                                      </div>
                            
                                      <?php else: ?>
                                      <div id="imagePreview" style="background-image: url(assets/images/user.png);">
                                      </div>
                                      <?php endif; ?>
                                  </div>
                              </div>
                           </div> 
                         </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <input type="hidden" name="email" value="<?php echo e(Auth::user()->email); ?>">
                            <label for="name">Full Name</label>
                            <select class="form-control custom-w" id="exampleFormControlSelect1" name="honorific" value="<?php echo e(Auth::user()->title); ?>">
                              <!-- <option>Mr.</option> -->
                              <option value="Mr." <?php echo e(( Auth::user()->title == "Mr.") ? 'selected' : ''); ?>> Mr. </option>
                              <!-- <option>Mrs.</option>  -->
                              <option value="Mrs." <?php echo e(( Auth::user()->title == "Mrs.") ? 'selected' : ''); ?>> Mrs. </option>
                            </select>
                             <input type="text" class="form-control c-width" id="fname" name="fname" value="<?php echo e(Auth::user()->name); ?>">
                             <input type="text" class="form-control c-width" id="lname" name="lname" value="<?php echo e(Auth::user()->last_name); ?>">
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
                            <label for="name">Company Name </label>
                            <?php if($errors->has('company_name')): ?>
                            <input type="text" class="form-control" id="company_name" name="company_name" value="">
                            <?php else: ?>
                            <input type="text" class="form-control" id="company_name" name="company_name" value="<?php echo e(Auth::user()->company_name); ?>">
                            <?php endif; ?>
                          </div>
                          <?php if($errors->has('company_name')): ?>
                          <span class="help-block">
                              <strong class="error-msg"><?php echo e($errors->first('company_name')); ?></strong>
                            </span>
                          <?php endif; ?>
                        </div>


                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="name">Company VAT Number </label>
                            <?php if($errors->has('company_vat_number')): ?>
                            <input type="text" class="form-control" id="company_vat_number" name="company_vat_number" value="">
                            <?php else: ?>
                            <input type="text" class="form-control" id="company_vat_number" name="company_vat_number" value="<?php echo e(Auth::user()->vat_number); ?>">
                            <?php endif; ?>
                          </div>
                          <?php if($errors->has('company_vat_number')): ?>
                          <span class="help-block">
                              <strong class="error-msg"><?php echo e($errors->first('company_vat_number')); ?></strong>
                            </span>
                          <?php endif; ?>
                        </div>

                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="addressline1">Address line 1 <i class="fa fa-star star"></i></label>
                            <?php if($errors->has('address1')): ?>
                            <input type="text" class="form-control" id="address1" name="address1" value="<?php echo e(Auth::user()->address_line1); ?>"></input>
                            <?php else: ?>
                            <input type="text" class="form-control" id="address1" name="address1" value="<?php echo e(Auth::user()->address_line1); ?>"></input>
                            <?php endif; ?>
                          </div>
                          <?php if($errors->has('address1')): ?>
                          <span class="help-block">
                              <strong class="error-msg"><?php echo e($errors->first('address1')); ?></strong>
                            </span>
                          <?php endif; ?>
                        </div>

                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="addressline1">Address line 2</label>
                            <input type="text" class="form-control" id="address2" name="address2" value="<?php echo e(Auth::user()->address_line2); ?>"></input>
                          </div>
                        </div>
                          <div class="col-md-6 col-sm-6 p-right">
                          <div class="form-group">
                            <label for="name">City <i class="fa fa-star star"></i></label>
                            <?php if($errors->has('city')): ?>
                            <input type="text" class="form-control" id="city" name ="city" value="">
                            <?php else: ?>
                            <input type="text" class="form-control" id="city" name ="city" value="<?php echo e(Auth::user()->city); ?>">
                            <?php endif; ?>
                          </div>
                          <?php if($errors->has('city')): ?>
                          <span class="help-block">
                              <strong class="error-msg"><?php echo e($errors->first('city')); ?></strong>
                            </span>
                          <?php endif; ?>
                        </div>

                          <div class="col-md-6 col-sm-6">
                          <div class="form-group">
                            <label for="name">Region <i class="fa fa-star star"></i></label>
                            <?php if($errors->has('region')): ?>
                            <input type="text" class="form-control" id="region" name="region" value="">
                            <?php else: ?>
                            <input type="text" class="form-control" id="region" name="region" value="<?php echo e(Auth::user()->region); ?>">
                            <?php endif; ?>
                          </div>
                          <?php if($errors->has('region')): ?>
                          <span class="help-block">
                              <strong class="error-msg"><?php echo e($errors->first('region')); ?></strong>
                            </span>
                          <?php endif; ?>
                        </div>


                          <div class="col-md-6 col-sm-6">
                          <div class="form-group">
                            <label for="exampleFormControlSelect1">Country <i class="fa fa-star star"></i></label>
                           
                            <select class="form-control" id="exampleFormControlSelect1" name ="country" >
                              
                            <option value=""> -- Select One --</option>
                              <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <?php if($errors->has('country')): ?>
                                <option value="<?php echo e($country->id); ?>"><?php echo e($country->name); ?></option>
                                <?php else: ?>
                                <option value="<?php echo e($country->id); ?>" <?php echo e(($country->id == Auth::user()->country) ? 'selected' : ''); ?>><?php echo e($country->name); ?></option>
                                <?php endif; ?> 
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>  
                              
                            
                            </select>
                          </div>
                          <?php if($errors->has('country')): ?>
                          <span class="help-block">
                              <strong class="error-msg"><?php echo e($errors->first('country')); ?></strong>
                            </span>
                          <?php endif; ?>
                        </div>

                        <div class="frm_submit text-center">
                          <!-- <button type="button" class="but_sub btn_save">Save</button> -->
                          <button type="submit" class="btn btn-primary" name="submit" id="submit" >Save</button>
                       
                        </div>

                        
                     </div></form>
                   </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    <!-- end my profile section your document section -->

<?php $__env->stopSection(); ?>
<?php $__env->startSection('add-js'); ?>
<script src="assets/js/jquery-1.9.1.min.js" ></script>
    <script src="assets/js/bootstrap.js" ></script>
    <script src="assets/js/custom.js"></script>
    <?php $__env->stopSection(); ?>

    <script>
        function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
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
<?php echo $__env->make('layouts.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\XAMPP1\htdocs\translucio\translucio\resources\views/user/information.blade.php ENDPATH**/ ?>