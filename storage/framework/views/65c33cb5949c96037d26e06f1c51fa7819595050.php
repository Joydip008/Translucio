<div class="top-header">
        <div class="container custom-con"> 
          <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 m-w-40">
              <div class="header-logo-left">
                <a href="<?php echo e(url('/home')); ?>"><img src="<?php echo asset('assets/images/logo/logo.png'); ?>" class="img-responsive"></a>
              </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 m-w-60">
              <div class="header-content-left">

                <button type="button" data-toggle="modal" data-target=".add_new_modal" class="button btn_translate f-mobile">Translate</button>
               
                <button type="button" onclick="location.href='<?php echo e(url('/buy-plan')); ?>';" class="button btn_credits f-mobile">Buy Credits</button>
                <a href="javascript:void(0)" class="notification"><img src="<?php echo asset('assets/images/icons/notification.png'); ?>" class="img-responsive"></a>
                <?php if(Auth::user()->profile_image): ?>
                <div class="people mb-none"><img src="<?php echo asset('assets/upload/user/'.Auth::user()->profile_image); ?>" class="img-responsive"></div>
                <?php else: ?>
                <div class="people mb-none"><img src="<?php echo asset('assets/images/user.png'); ?>" class="img-responsive"></div>
                <?php endif; ?>
                <div class="people-title mb-none">
                  <h3><a href="javascript:void(0)" class="open_menu"><?php echo e(Auth::user()->name); ?> <?php echo e(Auth::user()->last_name); ?></a></h3>
                  <?php
                    {{
                      $CurrentPlans = [];

                      $CurrentSubscriptionsDetails = App\Models\Subscription::where('user_id',Auth::user()->id)->orderBy('created_at','DESC')->first(); // Current Plans Details Of that User 
                      $CurrentPlansDetails = App\Models\Plans::where('stripe_plan_id',$CurrentSubscriptionsDetails['stripe_plan'])->first();

                      $CurrentPlans['plan_name'] = $CurrentPlansDetails['plan_name'];
                      $CurrentPlans['period_time'] = $CurrentPlansDetails['period_time'];
                      $CurrentPlans['created_at'] = $CurrentPlansDetails['created_at']; // Created At is that start date of the plans 
                      $CurrentPlans['cost'] = $CurrentPlansDetails['monthly_cost'];
                    }}
                  ?> 
                  <h4>Current subscription : <?php echo e($CurrentPlans['plan_name']); ?></h4>
                </div>
                    <div class="langset language-dropdown">
                        <div class="customSlbox">
                            <span class="selectValue">
                            <span><img src="<?php echo asset('assets/images/icons/flag.png'); ?>" /></span> En
                              </span>
                          <ul class="countryList" style="display:none;">
                            <li><a href="javascript:void(0)"><span class="active"><img src="<?php echo asset('assets/images/icons/flag.png'); ?>"  alt=""/></span> En</a></li>
                            <li><a href="javascript:void(0)"><span class="active"><img src="<?php echo asset('assets/images/icons/indian-flag.png'); ?>"  alt=""/></span> India</a></li>
                            <li><a href="javascript:void(0)"><span class="active"><img src="<?php echo asset('assets/images/icons/china-flag.png'); ?>"  alt=""/></span> China</a></li>
                          </ul>
                        </div>
                   </div>
                   <a href="#" class="open_menu menuMainBtn"><i class="fa fa-bars" aria-hidden="true"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>

       <!-- start add project popup -->
   <div class="modal fade add_new_modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body add_mid"> <a href="<?php echo e(url('/edit-project-web')); ?>" class="btn_new"><i class="material-icons">add</i>New Webproject</a>
          <a href="<?php echo e(route('add_new_doc_project')); ?>" class="btn_new btn_diff"><i class="material-icons">add</i>New Document</a>
        </div>
        <div class="modal-footer"></div>
      </div>
    </div>
  </div>
  <!-- end add project popup -->
<?php /**PATH E:\XAMPP1\htdocs\translucio\translucio\resources\views/partials/afterLogin_header.blade.php ENDPATH**/ ?>