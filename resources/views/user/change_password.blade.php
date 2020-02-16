@extends('layouts.home')
@section('title')
TRANSLICIO | Change Password
@endsection
@section('add-meta')
<style type="text/css">
  .t-red{
     font-size: 80%;
     color: #dc3545;
  }
</style>
@endsection
@section('content')

<div class="translate-document-main personal-main">
        <div class="container custom-con">
          <div class="row">
            <div class="col-md-12 text-center">
              <h2 class="translate-heading profile-heading">Reset Password</h2>
            </div>
              <div class="col-lg-3 col-md-12 p-r">
                <div class="my-profile-left">
                  <div class="part_1">
                      @if(Auth::user()->profile_image)
                  <div class="people people-profile-img"><img src="{!! asset('assets/upload/user/'.Auth::user()->profile_image) !!}" class="img-responsive"></div>
                  @else
                  <div class="people people-profile-img"><img src="assets/images/user.png" class="img-responsive"></div>
                  @endif
                  <div class="people-title people-profile">
                  <h3>{{Auth::user()->name}}  {{Auth::user()->last_name}}</h3>
                  <span class="people-id">#555FREDS8HFT</span>
                </div>
                </div>

                <div class="part_2">
                  <div><label>Email:</label> <span>{{AUth::user()->email}}</span></div>
                  <div><label>Phone:</label> <span>+61 29192 0995</span></div>
                  <div><label>Location:</label> <span>{{Auth::user()->city}}</span></div>
                </div>

                <div class="part_3">
                  <h3 class="overview_title">My Profile</h3>
                  <button type="button" onclick="location.href='{{ route('my_profile') }}';" class="btn_personal_information"><img src="assets/images/card/user.png" class="img-responsive">My Profile</button>
                  <button type="button" onclick="location.href='{{ route('change_password') }}';" class="btn_change_information"><img src="assets/images/card/lock1.png" class="img-responsive">Change Password</button>
                  <!-- <button type="button" onclick="location.href='';" class="btn_api_information"><img src="assets/images/card/api-key.png" class="img-responsive">Api Key</button> -->
                </div>
               </div>
              </div>
              <div class="col-lg-9 col-md-12">
                <div class="my-profile-right">
                   <div class="inlformation-main">
                     <h2 class="reset_title">Reset Password <span>update your personal informaiton</span></h2>
                   </div>
                   <hr class="def">
                   <div class="row p-top">
                   <div class="col-md-6"> 
                   <div class="password-left">
                  
                    <form action="{{url('/change-password-process')}}" method="post">
                    @if($errors->any())
                  <h4 class="error-msg mb-2">{{$errors->first()}}</h4>
                  @endif 
                    @csrf
                      <div class="form-group">
                        <label for="pwd">Current Password:</label>
                        <input type="password" class="form-control" id="pwd" name="current_password">
                        @if ($errors->has('current_password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('current_password') }}</strong>
                            </span>
                        @endif
                      </div>

                       <div class="form-group">
                        <label for="pwd">New Password:</label>
                        <input type="password" class="form-control" id="npwd" name="new_password" onkeyup="myFunction()" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                        @if ($errors->has('new_password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('new_password') }}</strong>
                            </span>
                        @endif
                      </div>
                    
                   </div>
                   </div>
                   <div class="col-md-6">
                   <div class="password-right">
                      <h2 class="pass_heading">Password Must</h2>
                      <ul class="password_terms_list">

                      
                        <li id="length_active"  style="display: none"><span><img src="assets/images/card/tick.png" class="img-fluid" id="length"></span><p>Be atleast 8 character</p></li>
                        <li id="length_inactive"  style="display: flex"><span><img src="assets/images/card/tick1.png" class="img-fluid" id="length"></span><p>Be atleast 8 character</p></li>
                     
                        <li id="number_active" style="display: none"><span><img src="assets/images/card/tick.png" class="img-fluid" id="number"></span><p>Have atleast number</p></li>
                        <li id="number_inactive" style="display: flex"><span><img src="assets/images/card/tick1.png" class="img-fluid" id="number"></span><p>Have atleast number</p></li>
                        
                        <li id="capital_active" style="display: none"><span><img src="assets/images/card/tick.png" class="img-fluid" id="capital"></span><p>Have atleast uppercase character</p></li>
                        <li id="capital_inactive" style="display: flex"><span><img src="assets/images/card/tick1.png" class="img-fluid" id="capital"></span><p>Have atleast uppercase character</p></li>
                        
                        <li id="letter_active" style="display: none"><span><img src="assets/images/card/tick.png" class="img-fluid" id="letter"></span><p>Have atleast lowercase character</p></li>
                        <li id="letter_inactive" style="display: flex"><span><img src="assets/images/card/tick1.png" class="img-fluid" id="letter"></span><p>Have atleast lowercase character</p></li>

                        <!-- <li id="special_active" style="display: none"><span><img src="assets/images/card/tick.png" class="img-fluid" id="letter"></span><p>Have atleast lowercase character</p></li>
                        <li id="special_inactive" style="display: flex"><span><img src="assets/images/card/tick1.png" class="img-fluid" id="letter"></span><p>Have atleast lowercase character</p></li> -->
                     
                      </ul>
                   </div>
                   </div>
                   </div>
                    <hr class="def">
                    <div class="col-md-12 text-center">
                      <div class="profile_submit">
                      <!-- <button type="button" class="btn_submit_profile">Submit</button> -->
                      <button type="submit" class="btn_submit_profile" name="submit" id="submit" >Submit</button>
                      <a href="{{route('home')}}"><button type="button" class="btn_submit_profile btn_can">Cancel</button></a>
                      </div>
                    </div>
                </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        @endsection
@section('add-js')
<script src="assets/js/jquery-1.9.1.min.js" ></script>
<script src="assets/js/bootstrap.js" ></script>
<script src="assets/js/custom.js"></script>
@endsection

<script>

function myFunction() {
      var value  = $("#npwd").val();
     
      /* Length Validation */
      if( value.length >= 8){
        $("#length_active").css("display","flex");
        $("#length_inactive").css("display","none");
      }
      else{
        $("#length_active").css("display","none");
        $("#length_inactive").css("display","flex");
      }


      $flag_number=0;
      $flag_letter=0;
      $flag_capital=0;
      //$flag_special=0;
      var iChars = "!`@#$%^&*()+=-[]\\\';,./{}|\":<>?~_";

      for($i = 0; $i<value.length; $i++){
        
        if ((value.charCodeAt($i) >= 65 && value.charCodeAt($i) <= 90) 
        || (value.charCodeAt($i) >= 97 && value.charCodeAt($i) <= 122)
          || (value.charCodeAt($i) >= 48 && value.charCodeAt($i) <= 57) )  {
        /* Number Validation*/
       
          if(!isNaN(value[$i])) {
            $flag_number+=1;
          }

        /* Capital Validation*/
          if (value[$i] == value[$i].toUpperCase() && isNaN(value[$i])){
            $flag_capital+=1;
          }
        /* Lower Case Validation*/
          if (value[$i] == value[$i].toLowerCase() && isNaN(value[$i])){
            $flag_letter+=1;
          }
      }
      else{
        // /* Special Character */
        //   if (iChars.indexOf(value.charAt(i)) != -1 && isNaN(value[$i])){
        //     $flag_special+=1;
        //   }
      }
    }
      if($flag_number>=1){
        $("#number_active").css("display","flex");
        $("#number_inactive").css("display","none");
      }
      else{
        $("#number_active").css("display","none");
        $("#number_inactive").css("display","flex");
      }

      if($flag_capital>=1){
        $("#capital_active").css("display","flex");
            $("#capital_inactive").css("display","none");
      }
      else{
        $("#capital_active").css("display","none");
            $("#capital_inactive").css("display","flex");
      }

      if($flag_letter>=1){
        $("#letter_active").css("display","flex");
            $("#letter_inactive").css("display","none");
      }
      else{
        $("#letter_active").css("display","none");
            $("#letter_inactive").css("display","flex");
      }

      // if($flag_special>=1){
      //   $("#special_active").css("display","flex");
      //       $("#special_inactive").css("display","none");
      // }
      // else{
      //   $("#special_active").css("display","none");
      //       $("#special_inactive").css("display","flex");
      // }
}
</script>

				

