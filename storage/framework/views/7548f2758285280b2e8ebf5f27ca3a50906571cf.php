<?php $__env->startSection('title'); ?>
TRANSLICIO | Invite Friend
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

<!-- start invite friend friend top section -->
 <div class="invite-main">
   <div class="container">
     <div class="row">
       <div class="col-md-12 text-center">
         <div class="invite-heading">
           <h1>Invite Your Friend</h1>
           <p>Share link invite a friend to signup and earn more points</p>
         </div>
       </div>
     </div>
   </div>
 </div>
<!-- end invite friend top section -->
      <!-- start invite friend section css -->
        <div class="copy-section-text">
          <div class="container">
            <div class="row">
              <div class="col-md-12">
                <div class="copy-sub-text">
                  <p class="cpy-heading">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has 
                  been the industry's standard dummy text ever since the 1500s, when an unknown printer took </p>
                  <div class="search_sec">
                    <!-- <form action=""> -->
                    <input type="text" value="dev.transluc.io" id="link" readonly>
                    <button type="submit" class="btn_cpy_ser" onclick="CopyScript()"><i class="fa fa-clone" aria-hidden="true"></i>Copy Link</button>
                    <!-- <a href="javascript:void(0)" onclick="CopyScript()" type="text"  class="btn_cpy_ser ser_1"><i class="fa fa-clone" aria-hidden="true"></i>Copy</a> -->
                  <!-- </form> -->
                  </div>

                  <h3 class="share">Share Your Referral Link</h3>
                  <div class="share_div">
                  <div class="mail-section">
                    <h4>Email</h4>
                    <span>Invite Your Friends</span>
                    <div class="friend">
                    <!-- <form action="" > -->
                    <input type="text" placeholder="Insert your friend's email id" id="email">
                    <button type="button" class="fly" onclick="ShareFriend()"><img src="assets/images/icons/fly.png" class="img-fluid">Send</button>
                   <!-- </form> -->
                   </div>
                  </div>
                  <div class="social-section">
                     <h4>Social Media</h4>
                      <span>Share On Facebook and Whatsapp</span>
                      <div id="social-links">
                      

		<!-- <a href="https://www.facebook.com/sharer/sharer.php?u=dev.transluc.io" class="common_link facebook_plugin" id="my-id"><i class="fa fa-facebook"></i>Share on Facebook</a> -->


	<!-- <ul>
		<li><a href="https://www.facebook.com/sharer/sharer.php?u=http://dev.transluc.io" class="social-button " id=""><span class="fa fa-facebook-official"></span></a></li>
		<li><a href="https://twitter.com/intent/tweet?text=my share text&amp;url=http://jorenvanhocht.be" class="social-button " id=""><span class="fa fa-twitter"></span></a></li>
		<li><a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=http://jorenvanhocht.be&amp;title=my share text&amp;summary=dit is de linkedin summary" class="social-button " id=""><span class="fa fa-linkedin"></span></a></li>
		<li><a href="https://wa.me/?text=http://jorenvanhocht.be" class="social-button " id=""><span class="fa fa-whatsapp"></span></a></li>    
	</ul> -->
</div>
                      <a href="https://www.facebook.com/sharer/sharer.php?u=dev.transluc.io" class="common_link facebook_plugin social-button"><i class="fa fa-facebook"></i>Share on Facebook</a>
                      <button type="button" onclick="WhatsAppSend()" class="common_link whatsapp_link"><i class="fa fa-whatsapp"></i>Share on Whatsapp</button>
                      <a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=dev.transluc.io&amp;title=my share text&amp;summary=dit is de linkedin summary" class="common_link linkedin social-button"><i class="fa fa-linkedin"></i>Share on  Linkedin </a>
                      <a href="https://twitter.com/intent/tweet?text=my share text&amp;url=dev.transluc.io" class="common_link twitter social-button"><i class="fa fa-twitter"></i>Share on Twitter</a>
                  </div>
                  </div>
                </div>
                </div>
            </div>
          </div>
        </div>
      <!-- end invite friend section css -->

      <!-- start copy secktion design -->
        <div class="copy-section-main">
          <div class="container">
            <div class="row">
              <div class="copy-sub">
                
              </div>
            </div>
          </div>
        </div>
      <!-- end copy section design -->

<?php $__env->stopSection(); ?>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="<?php echo e(asset('js/share.js')); ?>"></script>
<script>
function WhatsAppSend(){
    $.ajax({
        type: "POST",
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
        dataType: "json",
        data: {'Link' : 'dev.transluc.io'},
        url: "<?php echo e(route('InviteWhatsApp')); ?>",
        success:function(message){
            location.reload(); 
        }
    });
}

function FaceBookSend(){

  window.location.href = "<?php echo e(url('/invite-facebbok')); ?>";
    // $.ajax({
    //     type: "POST",
    //     headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
    //     dataType: "json",
    //     data: {'Link' : 'dev.transluc.io'},
    //     url: "<?php echo e(route('InviteFaceBook')); ?>",
    //     success:function(message){
    //         location.reload();  
    //     }
    // });
}
function CopyScript()
{

  var copyText = document.getElementById("link");
    
    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /*For mobile devices*/
  
    /* Copy the text inside the text field */
    document.execCommand("copy");
  
    /* Alert the copied text */
    // alert("Copied the text: " + copyText.value);
}

function ShareFriend(){
  var email = document.getElementById("email").value;
 
  // alert(email);
  $.ajax({
        type: "POST",
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
        dataType: "json",
        data: {'email' : email},
        url: "<?php echo e(route('MailSend')); ?>",
        success:function(message){
            // location.reload(); 
            alert("Send Successfully");
        }
    });
}

</script>

<?php echo $__env->make('layouts.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\XAMPP1\htdocs\translucio\translucio\resources\views/user/myProject/InviteFriend.blade.php ENDPATH**/ ?>