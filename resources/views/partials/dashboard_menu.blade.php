<div class="menuoverlay"></div>
    <div class="menu_div">
        <div class="box-inner">
          <div class="profile-header"> 
            @if(AUth::user()->profile_image)
            <div class="people peo_profile"><img src="{!! asset('assets/upload/user/'.Auth::user()->profile_image) !!}" class="img-responsive"></div>
            @else
            <div class="people peo_profile"><img src="{!! asset('assets/images/user.png') !!}" class="img-responsive"></div>
            @endif
            
            <div class="people-title peo_menu_title">
                  <h3 class="peo_title"><a href="{{route('my_profile')}}" class="slide-toggle">{{Auth::user()->name}} {{Auth::user()->last_name}}</a></h3>
                  <h4 class="peo_credit_points">Credit Available: {{$TotalCredit}}</h4>    
            </div>
            <div class="hide_button">
              <button type="button" onclick="location.href='translate1.html';" class="for-mobile">Translate</button>
              <button type="button" onclick="location.href='buy_credits.html';" class="for-mobile for_credits">Buy Credits</button>
            </div>
          </div>

            <div class="user_bottom_section"> 
            <ul>
                <li ><a href="{{route('my_profile')}}" ><span><img src="{{ asset('assets/images/icons/my-pro.png')}}" class="img-fluid"></span>My Profile</a></li>
                <li><a href="{{route('my_project')}}"><span><img src="{{ asset('assets/images/icons/projects.png')}}" class="img-fluid"></span>My Projects</a></li>
				<li><a href="{{url('/buy-plan')}}"><span><img src="{{ asset('assets/images/icons/upgrade-plan.png')}}" class="img-fluid"></span>Upgrade Plan</a></li>
                
                
                <!-- <button type="button" onclick="location.href='{{url('/buy-plan')}}';" class="button btn_credits f-mobile">Buy Credits</button> -->
                <li><a href="{{route('transaction_history')}}"><span><img src="{{ asset('assets/images/icons/rupee.png')}}" class="img-fluid"></span>Transaction History</a></li>
                
                <!-- <li><a href="credit_history.html"><span><img src="assets/images/icons/clock.png" class="img-fluid"></span>Credit History</a></li>
                 <li><a href="string-corrections.html"><span><img src="assets/images/icons/tax.png" class="img-fluid"></span>String Corrections</a></li> -->
                <!-- <li style="border-bottom:1px solid #E2E2E2; border-top:1px solid #E2E2E2;"><a href="settings.html"><span><img src="{{ asset('assets/images/icons/settings.png')}}" class="img-fluid"></span>Settings</a></li> -->
                <li><a href="{{route('help_and_faq')}}"><span><img src="{{ asset('assets/images/icons/question.png')}}" class="img-fluid"></span>Help & Faq</a></li>
                <li><a href="{{route('become_Proofreader')}}"><span><img src="{{ asset('assets/images/icons/profeader.png')}}" class="img-fluid"></span>Become a Proofreader</a></li>
                <li><a href="{{route('invite_friend')}}"><span><img src="{{ asset('assets/images/icons/envelope.png')}}" class="img-fluid"></span>Invite a Friend</a></li>
              </ul>
               <div class="log_btn_center"><a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); 
                                                     document.getElementById('logout-form').submit();" class="btn_log_out"><img src="{!! asset('assets/images/icons/logout.png') !!}" class="img-fluid">Logout</a></div>
               

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
            
              </div>
        </div>
    </div>
