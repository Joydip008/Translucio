@extends('layouts.account')

@section('content')
<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
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
                <a href="{{url('/')}}"><img src="{!! asset('assets/images/logo/logo.png') !!}" class="img-fluid"></a>
                      <div class="langset language-dropdown lang-right">
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
      </div>
		 </div>
	   </div>
        <div class="row customflex">
		 <div class="col-md-6 m_order_2">
            <div class="login-left">
              
              <div class="login_img">
                <img src="{!! asset('assets/images/logo/signup.png') !!}" class="img-fluid login_img">
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
                <form class="col-12" method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="code" value={{$code}}>
                        <div class="form-group">
                          <select class="form-control signselect" id="mrandmrs" name="title">
                              <option>Mr.</option>
                              <option>Mrs.</option>
                            </select>
                        </div>
                           <div class="form-group">
                          <input type="text" class="form-control logname @error('name') is-invalid @enderror" onkeypress="return lettersOnly(event)" placeholder="John" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus >
                          @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                          <input type="text" class="form-control logname @error('last_name') is-invalid @enderror" onkeypress="return lettersOnly(event)" placeholder="Doe" value="{{ old('last_name') }}" required autocomplete="last_name" name="last_name">

                        </div>
                        <div class="form-group">
                          <input type="email" class="form-control logEmail  @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email">
                          @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        
                        </div>

                        <div class="form-group">
                          <input type="password" class="form-control logPassword  @error('password') is-invalid @enderror" placeholder="Password" name="password" required autocomplete="new-password">
                        
                          @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        
                        </div>

                         <div class="form-group">
                          <input type="password" class="form-control logPassword" placeholder="Confirm Password" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="row">
                           <div class="col-md-12 text-right">
                            <button type="submit" class="btn btn_signIn">Signup</button>
                          </div>
                          <p class="dont">Already have an account?<span><a href="{{ route('login') }}" class="register_link">Sign In</a></span></p>
                        </div>

                      </form>
              </div>
            </div>
          </div>

		
		</div>
      </div>
    </div>
@endsection



<script language=Javascript>
 function lettersOnly() 
{
            var charCode = event.keyCode;

            if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 8 || charCode == 32)

                return true;
            else
                return false;
}

</script>
