<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Transluc.io - Translate & localise your websites & apps with unmatched quality & productivity.</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{!! asset('assets/css/font-awesome.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/css/bootstrap.min.css') !!}">
    <!-- custom css -->
   
    <link rel="stylesheet" href="{!! asset('assets/css/style.css') !!}">
     <link rel="stylesheet" type="text/css" href="{!! asset('assets/css/responsive.css') !!}">
     
  </head>
  
  <body>
    <!-- start top header section -->
       
    @include('partials.afterLogin_header')
    
  <!-- menu -->
    @include('partials.dashboard_menu')

    @yield('content')

  @include('partials.afterLogin_footer')
    <!-- end footer section -->
<!-- start js section -->
    <script src="{!! asset('assets/js/jquery-1.9.1.min.js') !!}" ></script>
    
    <script src="{!! asset('assets/js/bootstrap.js') !!}" ></script>
    <script src="{!! asset('assets/js/custom.js') !!}"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script> 
    
   
    
    
    </body>
</html>