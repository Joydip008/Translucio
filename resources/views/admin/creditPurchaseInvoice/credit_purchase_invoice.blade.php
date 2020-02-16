@extends('layouts.admin')
@section('title', 'Translucio|Dashboard')

@section('content')


   <!-- Content Wrapper. Contains page content -->
   <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard .
        <small>Invoices</small>
      </h1>
    </section>

    <!-- Main content --> 
    <section class="content">
      <!-- Info boxes -->
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Invoices</h3>

              <div class="box-tools pull-right">
                <div class="search-fleld">
                    <div class="search_sec">
                        <form action="{{route('credit_purchase_invoice_list')}}" method="post">
                        @csrf
                        <input type="text" placeholder="Search Client" name="search">
                        <button type="submit" class="btn_cpy_ser"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
                      </form>
                      </div>
                </div>
                  <!-- <a href="javascript:void(0)"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></a> -->
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
        <th class="th-lg">Invoice Number</th>
        <th class="th-lg">Date & Time</th>
        <th class="th-lg">Customer Name</th>
        <th class="th-lg">Customer Email</th>
        <th class="th-lg">Amount</th>
       <th class="th-lg"></th>
      </tr>
    </thead>
    <!--Table head-->

    <!--Table body-->
    <tbody>
    @foreach($InvoiceDetails as $Invoice)
      <tr>
        <td>{{$Invoice['invoice_number']}}</td>
        <td>{{$Invoice['created_at']}}</td>
        @if(empty($Invoice['profile_image']))
          <td><a href="javacsript:;"><img src="{{ asset('assets/images/user.png')}}" hieght="30" width="30" class="m-righ">{{$Invoice['customer_name']}}</a></td>
        @else
          <td><a href="javacsript:;"><img src="{{ asset('assets/upload/user/'.$Invoice['profile_image'])}}" hieght="30" width="30" class="m-righ">{{$Invoice['customer_name']}}</a></td> 
        @endif
        <td>{{$Invoice['customer_email']}}</td>
        <td>$ {{$Invoice['amount']}}</td>
        <td class="text-center">
          <button class="download-btn"><i class="fa fa-download" aria-hidden="true"></i> &nbsp;Download</button>
        </td>
      </tr>
    @endforeach
    </tbody>
    <!--Table body-->

    </table>

    <div class="col-md-12">
      <div class="pagination_right">
      @include('vendor.pagination.custom', ['paginator' => $results, 'link_limit' => 3])
                    <!-- <ul>
                      <li><a href="javascript:void(0)"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
                      </li>
                      <li><a href="javascript:void(0)"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                      </li>
                      <li><a href="javascript:void(0)"><span class="activea">1</span></a>
                      </li>
                      <li><a href="javascript:void(0)" class="bg-none">2</a>
                      </li>
                      <li><a href="javascript:void(0)" class="bg_dark"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                      </li>
                      <li><a href="javascript:void(0)" class="bg_dark"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                      </li>
                    </ul> -->
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








  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>

      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <div class="control-sidebar-bg"></div>

   <!-- The Modal -->
   <div class="modal fade invoice-etails-modal" id="client-detailsModal">
      <div class="modal-dialog">
        <div class="modal-content">
        
          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Client Details</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          
          <!-- Modal body -->
          <div class="modal-body">
          <p class="client-name">Steven V. Youngs</p>
          <p class="client-email"><span>Email :</span> example@example.com</p>
          <p class="client-email"><span>Invoice No. :</span> 1145106</p>
          <p class="client-email"><span>Billing date :</span> 01 Jun, 2019 at 05:00 AM</p>
          <p class="client-email"><span>Amount :</span>$3500</p>
          </div>
          
          <!-- Modal footer -->
          <div class="modal-footer">
            <button type="button" class="download-btn" data-dismiss="modal"><i class="fa fa-download" aria-hidden="true"></i> &nbsp; Download</button>
          </div>
          
        </div>
      </div>
    </div>
</div>


<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
@endsection