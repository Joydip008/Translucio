@extends('layouts.home')
@section('title')
TRANSLICIO | My Page
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

<!-- start my profile section css your document section -->
<div class="translate-document-main personal-main">
        <div class="container custom-con">
          <div class="row">
            <div class="col-md-12 text-center"> 
            <form action="{{url('/profile-update')}}" method="post" enctype="multipart/form-data">
           
                       @csrf
              <h2 class="translate-heading profile-heading">My Profile</h2>
            </div>
              <div class="col-lg-3 col-md-12 p-r">
                <div class="my-profile-left">
                  <div class="part_1">
                    
                    @if(Auth::user()->profile_image) 
                  <div class="people people-profile-img"><img src="{!! asset('assets/upload/user/'.Auth::user()->profile_image) !!}" class="img-responsive"></div>
                 
                  @else
                  <div class="people people-profile-img"><img src="{{url('/assets/images/user.png')}}" class="img-responsive"></div>
                 @endif
                  <div class="people-title people-profile">
                  <h3>{{Auth::user()->name}} {{Auth::user()->last_name}}</h3>
                  <span class="people-id">#555FREDS8HFT</span>
                </div>
                </div>

                <div class="part_2">
                  <div><label>Email:</label> <span>{{Auth::user()->email}}</span></div>
                  @if(Auth::user()->company_name)
                  <div><label>Company Name</label> <span>{{Auth::user()->company_name}}</span></div>
                  @endif
                  <!-- <div><label>Phone:</label> <span>+61 29192 0995</span></div> -->
                  <div><label>Location:</label> <span>{{Auth::user()->city}} - {{$country_name['name']}}</span></div>
                </div>
 
              <div class="part_3">
                  <h3 class="overview_title">My Profile</h3>
                  <button type="button" onclick="location.href='{{ route('my_profile') }}';" class="btn_personal_information"><img src="{{url('/assets/images/card/user.png')}}" class="img-responsive">My Profile</button>
                  <button type="button" onclick="location.href='{{ route('change_password') }}'" class="btn_change_information"><img src="{{url('/assets/images/card/lock1.png')}}" class="img-responsive">Change Password</button>
                  <!-- <button type="button" onclick="location.href='';" class="btn_api_information"><img src="{{url('/assets/images/card/api-key.png')}}" class="img-responsive">Api Key</button> -->
                </div>
               </div>
              </div>
              <div class="col-lg-9 col-md-12">
                <div class="my-profile-right"> 
                  <div class="registration-form personal_form">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="profile_pic_upload">
                          
  @if(session()->has('message'))
 <div class="alert alert-danger alert-dismissible" runat ="server" id="modalEditError" visible ="false">
  <button class="close" type="button" data-dismiss="alert">×</button>
        {{ session()->get('message') }}
    </div>
  @endif
                                <div class="avatar-upload personal-upload">
                                  <div class="avatar-edit personal-edit">
                                      <input type="file" onchange="readURL(this)" id="imageUpload" accept="image*" name="profile_image" value="{{ old('profile_image') }}" autocomplete="profile_image" autofocus>
                                      <label for="imageUpload">
                                        <img src="{{url('/assets/images/camera.png')}}" class="img-fluid">
                                      </label>
                                  </div>
                                  <div class="avatar-preview"> 
                                  @if(Auth::user()->profile_image)

                                  <div id="imagePreview" style="background-image: url(assets/upload/user/{{Auth::user()->profile_image}});">
                                      </div>
                            
                                      @else
                                      <div id="imagePreview" style="background-image: url(assets/images/user.png);">
                                      </div>
                                      @endif
                                  </div>
                              </div>
                           </div> 
                         </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <input type="hidden" name="email" value="{{Auth::user()->email}}">
                            <label for="name">Full Name</label>
                            <select class="form-control custom-w" id="exampleFormControlSelect1" name="honorific" value="{{Auth::user()->title}}">
                              <!-- <option>Mr.</option> -->
                              <option value="Mr." {{ ( Auth::user()->title == "Mr.") ? 'selected' : '' }}> Mr. </option>
                              <!-- <option>Mrs.</option>  -->
                              <option value="Mrs." {{ ( Auth::user()->title == "Mrs.") ? 'selected' : '' }}> Mrs. </option>
                            </select>
                             <input type="text" class="form-control c-width" id="fname" name="fname" value="{{Auth::user()->name}}">
                             <input type="text" class="form-control c-width" id="lname" name="lname" value="{{Auth::user()->last_name}}">
                          </div>
                          @if ($errors->has('fname'))
                          <span class="help-block">
                              <strong class="error-msg">{{ $errors->first('fname') }}</strong>
                            </span>
                          @endif
                          @if ($errors->has('lname'))
                          <span class="help-block">
                              <strong class="error-msg">{{ $errors->first('lname') }}</strong>
                            </span>
                          @endif
                        </div>

                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="name">Company Name </label>
                            @if ($errors->has('company_name'))
                            <input type="text" class="form-control" id="company_name" name="company_name" value="">
                            @else
                            <input type="text" class="form-control" id="company_name" name="company_name" value="{{Auth::user()->company_name}}">
                            @endif
                          </div>
                          @if ($errors->has('company_name'))
                          <span class="help-block">
                              <strong class="error-msg">{{ $errors->first('company_name') }}</strong>
                            </span>
                          @endif
                        </div>


                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="name">Company VAT Number </label>
                            @if ($errors->has('company_vat_number'))
                            <input type="text" class="form-control" id="company_vat_number" name="company_vat_number" value="">
                            @else
                            <input type="text" class="form-control" id="company_vat_number" name="company_vat_number" value="{{Auth::user()->vat_number}}">
                            @endif
                          </div>
                          @if ($errors->has('company_vat_number'))
                          <span class="help-block">
                              <strong class="error-msg">{{ $errors->first('company_vat_number') }}</strong>
                            </span>
                          @endif
                        </div>

                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="addressline1">Address line 1 <i class="fa fa-star star"></i></label>
                            @if($errors->has('address1'))
                            <input type="text" class="form-control" id="address1" name="address1" value="{{Auth::user()->address_line1}}"></input>
                            @else
                            <input type="text" class="form-control" id="address1" name="address1" value="{{Auth::user()->address_line1}}"></input>
                            @endif
                          </div>
                          @if ($errors->has('address1'))
                          <span class="help-block">
                              <strong class="error-msg">{{ $errors->first('address1') }}</strong>
                            </span>
                          @endif
                        </div>

                        <div class="col-md-12">
                          <div class="form-group">
                            <label for="addressline1">Address line 2</label>
                            <input type="text" class="form-control" id="address2" name="address2" value="{{Auth::user()->address_line2}}"></input>
                          </div>
                        </div>
                          <div class="col-md-6 col-sm-6 p-right">
                          <div class="form-group">
                            <label for="name">City <i class="fa fa-star star"></i></label>
                            @if($errors->has('city'))
                            <input type="text" class="form-control" id="city" name ="city" value="">
                            @else
                            <input type="text" class="form-control" id="city" name ="city" value="{{Auth::user()->city}}">
                            @endif
                          </div>
                          @if ($errors->has('city'))
                          <span class="help-block">
                              <strong class="error-msg">{{ $errors->first('city') }}</strong>
                            </span>
                          @endif
                        </div>

                          <div class="col-md-6 col-sm-6">
                          <div class="form-group">
                            <label for="name">Region <i class="fa fa-star star"></i></label>
                            @if($errors->has('region'))
                            <input type="text" class="form-control" id="region" name="region" value="">
                            @else
                            <input type="text" class="form-control" id="region" name="region" value="{{Auth::user()->region}}">
                            @endif
                          </div>
                          @if ($errors->has('region'))
                          <span class="help-block">
                              <strong class="error-msg">{{ $errors->first('region') }}</strong>
                            </span>
                          @endif
                        </div>


                          <div class="col-md-6 col-sm-6">
                          <div class="form-group">
                            <label for="exampleFormControlSelect1">Country <i class="fa fa-star star"></i></label>
                           
                            <select class="form-control" id="exampleFormControlSelect1" name ="country" >
                              
                            <option value=""> -- Select One --</option>
                              @foreach ($countries as $country)

                                @if($errors->has('country'))
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @else
                                <option value="{{ $country->id }}" {{ ($country->id == Auth::user()->country) ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endif 
                              @endforeach  
                              
                            
                            </select>
                          </div>
                          @if ($errors->has('country'))
                          <span class="help-block">
                              <strong class="error-msg">{{ $errors->first('country') }}</strong>
                            </span>
                          @endif
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

@endsection
@section('add-js')
<script src="assets/js/jquery-1.9.1.min.js" ></script>
    <script src="assets/js/bootstrap.js" ></script>
    <script src="assets/js/custom.js"></script>
    @endsection

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