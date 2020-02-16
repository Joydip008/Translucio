@extends('layouts.account')

@section('content')
<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

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
                                    {{ __('Reset Password') }}
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
                <a href="javascript:void(0)"><img src="{!! asset('assets/images/logo/logo.png') !!}" class="img-fluid"></a>
              </div>
     </div>
     </div>
        <div class="row customflex">
     <div class="col-md-6 m_order_2">
            <div class="login-left">
              
              <div class="login_img">
                <img src="{!! asset('assets/images/logo/reset.png') !!}" class="img-fluid login_img">
              </div>
              <p class="login_copy">Copyright &copy 2019 Transluc.io</p>
            </div>
          </div>
        
          <div class="col-md-6 m_order_1">
            <div class="login-right pt-100">
              <div class="row">
                <div class="lolgin_header">
                  <h3>Reset Password</h3>
                </div>
                <form class="col-12" method="POST" action="{{ route('password.update') }}">
                @csrf

                    <!-- <input type="hidden" name="token" value="{{ $token }}">    -->
                    <div class="form-group">
                        <input id="email" type="hidden" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                
                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                    </div>
                <div class="form-group">
              
                 <!-- <input type="password" class="form-control logPassword @error('password') is-invalid @enderror" placeholder="New Password" name="password" required autocomplete="new-password"> -->
                 <input id="password" type="password" class="form-control  logPassword @error('password') is-invalid @enderror" name="password" placeholder="New Password" required autocomplete="new-password">  
                 @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror    
                </div>

                         <div class="form-group">
                          <input type="password" class="form-control logPassword" placeholder="Confirm New Password" name="password_confirmation" required autocomplete="new-password">
                         
                          
                        </div>

                        <div class="row">
                           <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn_signIn">Save Password</button>
                          </div>
                        </div>

                      </form>
              </div>
            </div>
          </div>

    
    </div>
      </div>
    </div>
@endsection
