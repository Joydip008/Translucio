@extends('layouts.admin')
@section('title', 'Translucio|Dashboard')

@section('content')

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Dashboard . 
          <small>Credit Products</small>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content"> 
        <div class="row">
          <div class="col-md-8">
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">Credit Plan</h3>
                <!-- <button type="button" class="btn-default add_new_button"><i class="fa fa-plus" aria-hidden="true"></i>
                  Create plan</button> -->
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <div class="row">
                  <div class="col-md-12">
                    <div class="credit_all_histohry">
                      <div class="history_table credit_products_tbl">
                        <div class="history_table_part">

                          <!--Table-->
                          <table class="table">
                            <thead>
                              <tr>
                                <th class="th-lg">Plan Name</th>
                                <th class="th-lg">Amount</th>
                                <th class="th-lg">Description</th>
                                <th class="th-lg">Action</th>
                              </tr>
                            </thead>
                            <!--Table head-->

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

          <div class="col-md-4 p-l p-right-30" id="add_show">
            <div class="box box-success bx-grey">
              <div class="loader" id="loader">
                <img src="{{ asset('assets/admin_assets/dist/img/dashboard-icons/833.gif')}}" />
              </div>
             <div class="form-inner-container" id="form-content">
                  <div class="box-header">
                    </div>
                    <div class="box-body bx-form">
                      <form action="{{url('/admin/add-plans_success')}}" method="post">
                      @if(session()->has('message'))
                          <div class="alert alert-success">
                            {{ session()->get('message') }}
                          </div>
                      @endif
                      @csrf
                        <div class="form-row">
                          <div class="form-group col-md-12">
                          <h3 class="box-title">Plan Name</h3>
                      <input type="text" class="form-control amount_first" id="plan_name_" name="plan_name_" required>
                            <label for="inputZip">Monthly cost</label>
                            <div class="price-details">
                             <input type="text" class="form-control amount_first" id="amount"  required name="monthly_cost" pattern="^\d*(\.\d{0,2})?$">
                             <span class="symbol"><i class="fa fa-euro" aria-hidden="true"></i></span>
                            </div>
                            <span class="credit_img"><img src="{{ asset('assets/admin_assets/dist/img/dashboard-icons/note.png')}}"></span>
                          </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                              <label for="inputEmail4">Max number of languages</label>
                              <input type="text" class="form-control" id="cre"  required name="max_languages" onkeypress="return isNumberKey(event)">
                              <span class="credit_img"><img src="{{ asset('assets/admin_assets/dist/img/dashboard-icons/language-new.png')}}"></span>
                            </div>
                          </div>
                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="inputEmail4">Included pageviews</label>
                            <input type="text" class="form-control" id="cre" required name="included_pageviews" onkeypress="return isNumberKey(event)">
                            <span class="credit_img"><img src="{{ asset('assets/admin_assets/dist/img/dashboard-icons/data.png')}}"></span>
                          </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                              <label for="inputZip"> Extra cost of pageviews / {{config('constants.EXTRA_COST_OF_PAGE_VIEWS_PER')}}</label>
                            <div class="price-details">
                             <input type="text" class="form-control amount_first" required id="amount" name="extra_cost_pageviews" pattern="^\d*(\.\d{0,2})?$">
                             <span class="symbol"><i class="fa fa-euro" aria-hidden="true"></i></span>
                            </div>
                            <span class="credit_img"><img src="{{ asset('assets/admin_assets/dist/img/dashboard-icons/note.png')}}"></span>
                            </div>
                          </div>
                          <div class="form-row">
                              <div class="form-group col-md-12">
                                <label for="inputEmail4">Translation credits</label>
                                <input type="text" class="form-control" id="cre" required name="translation_credits" onkeypress="return isNumberKey(event)">
                                <span class="credit_img"><img src="{{ asset('assets/admin_assets/dist/img/dashboard-icons/data.png')}}"></span>
                              </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                  <label for="inputEmail4">Additional cost per {{config('constants.ADDITIONAL_CHARACTER_PER_PAGE')}} credit</label>
                                  <div class="price-details">
                                  <input type="text" class="form-control amount_first" required id="cre" name="additional_characters" onkeypress="return isNumberKey(event)">
                                    <span class="symbol"><i class="fa fa-euro" aria-hidden="true"></i></span>
                                  </div>
                                   <span class="credit_img"><img src="{{ asset('assets/admin_assets/dist/img/dashboard-icons/note.png')}}"></span>
                                </div>
                              </div>
                        <div class="form-row">
                          <button type="submit" class="btn-default add_to_credit">Add Plan</button>
                        </div>
                      </form>
                    </div>
              </div>
            </div>
          </div>
        </div>

@endsection
<SCRIPT language=Javascript>
      <!--
      function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;
      }
      //-->
   </SCRIPT>
