@extends('layouts.admin')
@section('title', 'Translucio|Dashboard')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard .
        <small>Clients</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border"> 
              <h3 class="box-title">Clients</h3>

              <div class="box-tools pull-right">
                  <a href="javascript:void(0)"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a>
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
        <th class="th-lg">Contract Type</th>
        <th class="th-lg">Credits Included</th>
       <th class="th-lg">Credits Available</th>
       <th class="th-lg">Creation Date</th>
       <th class="th-lg">Last Use Date</th>
      </tr>
    </thead>
    <!--Table head-->

    <!--Table body-->
    <tbody>
        @foreach($userDetails as $user)
      <tr>
        <td><a href="{{url('/admin/client-details/'.$user['id'])}}"><img src="{{ asset('assets/admin_assets/dist/img/dashboard-icons/user.png')}}" class="m-righ">{{$user['name']}} {{$user['last_name']}}</a></td>
        <td>{{$user['email']}}</td>
        <td class="td-green">{{$user['PlanName']}} / {{$user['PlanPeriod']}}</td>
        <td class="td-green">{{$user['CreditIncludes']}}</td>
        @if($user['AvailableCredits'] < 0)
          <td class="td-red">{{$user['AvailableCredits']}}</td>
        @elseif($user['AvailableCredits'] >= 0)
          <td class="td-green">{{$user['AvailableCredits']}}</td>
        @endif
        <td>{{$user['created_at']}} </td>
        <td>{{$user['last_login_at']}} </td>
      </tr>
      @endforeach
    </tbody>
    <!--Table body-->

    </table>
        <div class="col-md-12">
      <div class="pagination_right">
      @include('vendor.pagination.custom', ['paginator' => $results, 'link_limit' => 3])
                   
                  </div>
    </div>
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

@endsection
