@extends('layouts.account')

@section('content')
<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

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
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->
@if(session()->has('message'))
 <div class="alert alert-success alert-dismissible" runat ="server" id="modalEditError" visible ="false">
  <button class="close" type="button" data-dismiss="alert">×</button>
        {{ session()->get('message') }}
    </div>
  @endif
<div class="login-main">
      <div class="container">
	   <div class="row">
	     <div class="col-md-12">
			<div class="logo">
                <a href="javascript:void(0)"><img src="{!! asset('assets/images/logo/logo.png') !!}" class="img-fluid"></a>
              </div>
		 </div>
	   </div>
        <div class="row customflex">
		 <div class="col-md-6 m_order_2">
            <div class="login-left">
              
              <div class="login_img">
                <img src="{!! asset('assets/images/logo/login.png') !!}" class="img-fluid login_img">
              </div>
              <p class="login_copy">Copyright &copy 2019 Transluc.io</p>
            </div>
          </div>
        
          <div class="col-md-6 m_order_1">
            <div class="login-right">
              <div class="row">
                <div class="lolgin_header">
                  <h3>Log In</h3>
                </div>
              

                <form class="col-12" method="POST" action="{{ route('login') }}">
                @if($errors->any())
                  <h4 class="error-msg mb-2">{{$errors->first()}}</h4>
                  @endif 
                @csrf
                        <div class="form-group">
                          <input type="email" class="form-control logEmail @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus >
                          @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="form-group">
                          <input type="password" id="password" class="form-control logPassword @error('password') is-invalid @enderror" placeholder="Password" name="password" required autocomplete="current-password">
                          <!-- <button type="button" class="unView" id="password_show"></button> -->
                          @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        
                        </div>

                        <div class="row">
                          <div class="col-md-6 text-left forgetLink">
                          @if (Route::has('password.request'))
                              <a href="{{ route('password.request') }}" class="btn btn_forgetLink">Forgot Password?</a> 
                              
                              @endif
                            </div>
                           <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn_signIn">Login</button>
                          </div>
                         <!-- <div class="col-md-12 line">
                            <hr>
                            <span class="or">Or</span>
                          </div> -->
                          <!-- <div class="col-md-12">
                            <a href=""><button type="button" class="btn_google">login with google</button></a>
                            <span class="g-plus g-plus-first"><i class="fa fa-google" aria-hidden="true"></i></span>
                          </div> -->
                          <p class="dont">Don't hava an account yet?<span><a href="{{ route('register') }}" class="register_link">Register</a></span></p>
                        </div>

                      </form>
              </div>
            </div>
          </div>

		
		</div>
      </div>
    </div>
@endsection
<script>

$("#password_show").click(function () {
        if($("#password").attr('type') === "password") {
          $("#password").attr('type','text');
          $("#password_show").removeClass('unView');
          $("#password_show").addClass('view');
        }
        else{
          $("#password").attr('type','password');
          $("#password_show").addClass('unView');
          $("#password_show").removeClass('view');
        }
      });
  </script>