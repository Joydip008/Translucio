@extends('layouts.admin')
@section('title', 'Translucio|Dashboard')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard .
        <small>Dashboard</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
      <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12 p-r-6">
          <div class="credit-boxes mb-20">
          <div class="dropdown select_days">
            <button class="dropdown-toggle btn-last" type="button" data-toggle="dropdown">Last 28 Days
            <span class="car_down"><img src="{{ asset('assets/admin_assets/dist/img/credit-images/arrow-down.png')}}"></span>
          </button>
            <ul class="dropdown-menu">
              <li><a href="#">Last 28 Days</a></li>
              <li><a href="#">Last 27 Days</a></li>
              <li><a href="#">Last 26 Days</a></li>
            </ul>
          </div>
          <div class="price"><img src="{{ asset('assets/admin_assets/dist/img/credit-images/round1.png')}}"></div>
          <div class="pricek-amount">
            <h2>56.5k <small><img src="{{ asset('assets/admin_assets/dist/img/credit-images/redarrow.png')}}">-0.4%</small></h2>
            <span class="credit_title">Credit Bought</span>
          </div>
        </div>
          </div>
        <div class="col-md-4 col-sm-6 col-xs-12 p-r-6">
                <div class="credit-boxes mb-20">
          <div class="dropdown select_days">
            <button class="dropdown-toggle btn-last" type="button" data-toggle="dropdown">Last 24 Hours
            <span class="car_down"><img src="{{ asset('assets/admin_assets/dist/img/credit-images/arrow-down.png')}}"></span>
          </button>
            <ul class="dropdown-menu">
              <li><a href="#">Last 28 Days</a></li>
              <li><a href="#">Last 27 Days</a></li>
              <li><a href="#">Last 26 Days</a></li>
            </ul>
          </div>
          <div class="price"><img src="{{ asset('assets/admin_assets/dist/img/credit-images/round2.png')}}"></div>
          <div class="pricek-amount">
            <h2>€178 <small style="color: #219c17;"><img src="{{ asset('assets/admin_assets/dist/img/credit-images/greenarrow.png')}}">15%</small></h2>
            <span class="credit_title">Average Buying Price</span>
          </div>
        </div>
        </div>

        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="credit-boxes mb-20">
          <div class="dropdown select_days">
            <button class="dropdown-toggle btn-last" type="button" data-toggle="dropdown">Last 7 Days
            <span class="car_down"><img src="{{ asset('assets/admin_assets/dist/img/credit-images/arrow-down.png')}}"></span>
          </button>
            <ul class="dropdown-menu">
              <li><a href="#">Last 28 Days</a></li>
              <li><a href="#">Last 27 Days</a></li>
              <li><a href="#">Last 26 Days</a></li>
            </ul>
          </div>
          <div class="price"><img src="{{ asset('assets/admin_assets/dist/img/credit-images/round3.png')}}"></div>
          <div class="pricek-amount">
            <h2>12.8k <small style="color: #219c17;"><img src="{{ asset('assets/admin_assets/dist/img/credit-images/greenarrow.png')}}">15%</small></h2>
            <span class="credit_title">Credit Consumed</span>
          </div>
        </div>
        </div>
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">New Customer Signups</h3>

              <div class="box-tools pull-right">
                  <a href="{{url('/admin/client-list')}}"><button type="button" class="btn_customer_details">View All Customer</button></a>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                  <div class="col-md-12">
                    <div class="credit_all_histohry">
                      <div class="history_table history_table_diff">
                   <div class="history_table_part">

  <!--Table-->
  <table class="table">
    <thead>
      <tr>
        <th class="th-lg">Client Name</th>
        <th class="th-lg">Email</th>
        <th class="th-lg">Credit Bought</th>
        <th class="th-lg">Credits Consumed</th>
       <th class="th-lg">Credits Available</th>
       <th class="th-lg">Creation Date</th>
       <th class="th-lg">Last Use Date</th>
      </tr>
    </thead>
    <!--Table head-->

    <!--Table body-->
    <tbody>
      @if(isset($userDetails))
        @foreach($userDetails as $user)
      <tr>
        <td><img src="{{ asset('assets/admin_assets/dist/img/dashboard-icons/user.png')}}" class="m-righ">{{$user['name']}} {{$user['last_name']}}</td>
        <td>{{$user['email']}}</td>
        <td class="td-green">6540</td>
        <td class="td-red">3000</td>
        <td>3540</td>
        <td>{{$user['created_at']}} </td>
        <td>{{$user['last_login_at']}} </td>
      </tr>
    
      @endforeach
    @endif

    </tbody>
    <!--Table body-->

    </table>
    <!--Table-->
    </div>
                  </div>
               </div>
                  </div>
              </div>
              <!-- /.row -->
            </div>
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection