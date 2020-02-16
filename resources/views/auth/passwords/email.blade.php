@extends('layouts.account')

@section('content')
<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
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

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
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
              </div>
     </div>
     </div>
        <div class="row customflex">
     <div class="col-md-6 m_order_2">
            <div class="login-left">
              
              <div class="login_img">
                <img src="{!! asset('assets/images/logo/forget.png') !!}" class="img-fluid login_img">
              </div>
              <p class="login_copy">Copyright &copy 2019 Transluc.io</p>
            </div>
          </div>
        
          <div class="col-md-6 m_order_1">
            <div class="login-right pt-100">
              <div class="row">
                <div class="lolgin_header">
                  <h3>Forget Password?</h3>
                  <p class="recent">We Will Send You Instraction to Recent Your Password</p>
                </div>
                     @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(session()->has('message'))
 <div class="alert alert-danger alert-dismissible" runat ="server" id="modalEditError" visible ="false">
  <button class="close" type="button" data-dismiss="alert">×</button>
        {{ session()->get('message') }}
    </div>
  @endif
 
                   
                <!-- <form class="col-12" method="POST" action="{{ route('password.email') }}">  -->
                <form class="col-12" method="POST" action="{{ route('send_mail_link') }}"> 
                  @if (session()->has('success'))
                    <h4>{{ session('success') }}</h4>
                  @endif
                @csrf
                        <div class="form-group">
                          <input type="email" class="form-control logEmail @error('email') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                          @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>
                        <div class="row">
                           <div class="col-md-12 text-center">
                            <button type="submit"  class="btn btn_signIn">Send</button>
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
