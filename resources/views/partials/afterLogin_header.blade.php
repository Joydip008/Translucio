<div class="top-header">
        <div class="container custom-con"> 
          <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 m-w-40">
              <div class="header-logo-left">
                <a href="{{url('/home')}}"><img src="{!! asset('assets/images/logo/logo.png') !!}" class="img-responsive"></a>
              </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-9 m-w-60">
              <div class="header-content-left">
				<a href="{{url('/home')}}" class="header_link">My Projects</a>
                
              
               
                @if(Auth::user()->profile_image)
                <div class="people mb-none"><img src="{!! asset('assets/upload/user/'.Auth::user()->profile_image) !!}" class="img-responsive"></div>
                @else
                <div class="people mb-none"><img src="{!! asset('assets/images/user.png') !!}" class="img-responsive"></div>
                @endif
                <div class="people-title mb-none">
                 {{Auth::user()->name}} {{Auth::user()->last_name}}
                  @php
                    {{
                      $CurrentPlans = [];

                      $CurrentSubscriptionsDetails = App\Models\Subscription::where('user_id',Auth::user()->id)->orderBy('created_at','DESC')->first(); // Current Plans Details Of that User 
                      $CurrentPlansDetails = App\Models\Plans::where('stripe_plan_id',$CurrentSubscriptionsDetails['stripe_plan'])->first();

                      $CurrentPlans['plan_name'] = $CurrentPlansDetails['plan_name'];
                      $CurrentPlans['period_time'] = $CurrentPlansDetails['period_time'];
                      $CurrentPlans['created_at'] = $CurrentPlansDetails['created_at']; // Created At is that start date of the plans 
                      $CurrentPlans['cost'] = $CurrentPlansDetails['monthly_cost'];
                    }}
                  @endphp 
                  <!--<h4>Current subscription : {{$CurrentPlans['plan_name']}}</h4>-->
                </div>
				
                   <!-- <div class="langset language-dropdown">
                        <div class="customSlbox">
                            <span class="selectValue">
                            <span><img src="{!! asset('assets/images/icons/flag.png') !!}" /></span> En
                              </span>
                          <ul class="countryList" style="display:none;">
                            <li><a href="javascript:void(0)"><span class="active"><img src="{!! asset('assets/images/icons/flag.png') !!}"  alt=""/></span> En</a></li>
                            <li><a href="javascript:void(0)"><span class="active"><img src="{!! asset('assets/images/icons/indian-flag.png') !!}"  alt=""/></span> India</a></li>
                            <li><a href="javascript:void(0)"><span class="active"><img src="{!! asset('assets/images/icons/china-flag.png') !!}"  alt=""/></span> China</a></li>
                          </ul>
                        </div>
                   </div>
                   <a href="#" class="open_menu menuMainBtn"><i class="fa fa-bars" aria-hidden="true"></i></a>-->
             
			<button class="menu-btn open_menu"></button>
			 </div>
            </div>
          </div>
        </div>
      </div>

       <!-- start add project popup -->
    <!-- start add project popup -->
   <div class="modal fade add_new_modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body add_mid">
		  <a href="{{url('/edit-project-web')}}" class="add_project_btn"><i class="material-icons">add</i>Add Web Project</a>
          <a href="{{route('add_new_doc_project')}}" class="add_project_btn"><i class="material-icons">add</i>Add API Project</a>
		  <a href="{{route('add_new_doc_project')}}" class="add_project_btn"><i class="material-icons">add</i>Add Documents Project</a>
        </div>
        
      </div>
    </div>
  </div>
  <!-- end add project popup -->
  <!-- end add project popup -->
