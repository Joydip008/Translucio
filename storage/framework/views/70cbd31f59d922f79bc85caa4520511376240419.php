<?php $__env->startSection('content'); ?>
<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><?php echo e(__('Register')); ?></div>

                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('register')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right"><?php echo e(__('Name')); ?></label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control <?php if ($errors->has('name')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('name'); ?> is-invalid <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>" name="name" value="<?php echo e(old('name')); ?>" required autocomplete="name" autofocus>

                                <?php if ($errors->has('name')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('name'); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right"><?php echo e(__('E-Mail Address')); ?></label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control <?php if ($errors->has('email')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('email'); ?> is-invalid <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email">

                                <?php if ($errors->has('email')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('email'); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right"><?php echo e(__('Password')); ?></label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control <?php if ($errors->has('password')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('password'); ?> is-invalid <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>" name="password" required autocomplete="new-password">

                                <?php if ($errors->has('password')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('password'); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right"><?php echo e(__('Confirm Password')); ?></label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo e(__('Register')); ?>

                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->

<div class="login-main">
      <div class="container">
	   <div class="row">
	     <div class="col-md-12">
			<div class="logo">
                <a href="<?php echo e(url('/')); ?>"><img src="<?php echo asset('assets/images/logo/logo.png'); ?>" class="img-fluid"></a>
                      <div class="langset language-dropdown lang-right">
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
      </div>
		 </div>
	   </div>
        <div class="row customflex">
		 <div class="col-md-6 m_order_2">
            <div class="login-left">
              
              <div class="login_img">
                <img src="<?php echo asset('assets/images/logo/signup.png'); ?>" class="img-fluid login_img">
              </div>
              <p class="login_copy">Copyright &copy 2019 Transluc.io</p>
            </div>
          </div>
        
          <div class="col-md-6 m_order_1">
            <div class="login-right signupform">
              <div class="row">
                <div class="lolgin_header">
                  <h3>Sign Up</h3>
                </div>
                <form class="col-12" method="POST" action="<?php echo e(route('register')); ?>">
                <?php echo csrf_field(); ?>
                        <div class="form-group">
                          <select class="form-control signselect" id="mrandmrs" name="title">
                              <option>Mr.</option>
                              <option>Mrs.</option>
                            </select>
                        </div>
                           <div class="form-group">
                          <input type="text" class="form-control logname <?php if ($errors->has('name')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('name'); ?> is-invalid <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>" onkeypress="return lettersOnly(event)" placeholder="John" name="name" value="<?php echo e(old('name')); ?>" required autocomplete="name" autofocus >
                          <?php if ($errors->has('name')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('name'); ?>
                            <span class="invalid-feedback" role="alert">
                                <strong><?php echo e($message); ?></strong>
                            </span>
                            <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>
                          <input type="text" class="form-control logname <?php if ($errors->has('last_name')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('last_name'); ?> is-invalid <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>" onkeypress="return lettersOnly(event)" placeholder="Doe" value="<?php echo e(old('last_name')); ?>" required autocomplete="last_name" name="last_name">

                        </div>
                        <div class="form-group">
                          <input type="email" class="form-control logEmail  <?php if ($errors->has('email')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('email'); ?> is-invalid <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>" placeholder="Email" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email">
                          <?php if ($errors->has('email')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('email'); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>
                        
                        </div>

                        <div class="form-group">
                          <input type="password" class="form-control logPassword  <?php if ($errors->has('password')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('password'); ?> is-invalid <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>" placeholder="Password" name="password" required autocomplete="new-password">
                        
                          <?php if ($errors->has('password')) :
if (isset($message)) { $messageCache = $message; }
$message = $errors->first('password'); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($messageCache)) { $message = $messageCache; }
endif; ?>
                        
                        </div>

                         <div class="form-group">
                          <input type="password" class="form-control logPassword" placeholder="Confirm Password" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="row">
                           <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn_signIn">Signup</button>
                          </div>
                          <p class="dont">Already have an account?<span><a href="<?php echo e(route('login')); ?>" class="register_link">Sign In</a></span></p>
                        </div>

                      </form>
              </div>
            </div>
          </div>

		
		</div>
      </div>
    </div>
<?php $__env->stopSection(); ?>



<script language=Javascript>
 function lettersOnly() 
{
            var charCode = event.keyCode;

            if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 8)

                return true;
            else
                return false;
}

</script>

<?php echo $__env->make('layouts.account', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\XAMPP1\htdocs\translucio\translucio\resources\views/auth/register.blade.php ENDPATH**/ ?>