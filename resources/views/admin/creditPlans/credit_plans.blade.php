@extends('layouts.admin')
@section('title', 'Translucio|Dashboard')

@section('content')

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper" >
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          Dashboard .
          <small>Credit Products</small>
        </h1>
      </section>

      <!-- Main content -->
      <section class="content" > 
        <div class="row">
          <div class="col-md-8">
            <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title">Credit Plan</h3>
                @if(session()->has('message'))
                  <div class="alert alert-success">
                    {{ session()->get('message') }}
                  </div>
                @endif

                <button type="button" class="lng_add_button" onclick="myFunction()"><img src="{{ asset('assets/admin_assets/dist/img/dashboard-icons/plus.png')}}">Add New</button>
       
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

                            <!--Table body-->
                            <tbody>
                              @foreach($plansDetailsList as $plan)
                              <tr>
                                <td>{{$plan['plan_name']}}</td>
                                <td>€ {{$plan['monthly_cost']}}</td>
                                <td>Max no. of Language :{{$plan['max_languages']}},  Preview Included :  {{$plan['included_pageviews']}},  Extra Preview ( per 10,000):  €{{$plan['extra_cost_pageviews']}}  ,  Translation Credits:  {{$plan['translation_credits']}},  Additional Character ( per 10,000):  €{{$plan['additional_characters']}}</td>
                                <td>
                                <span><a href="{{url('/admin/credit-plans/'.$plan['id'])}}" class="plan-edit_button"><img
                                        src="{{ asset('assets/admin_assets/dist/img/dashboard-icons/edit.png')}}"></a></span>
<!--                                         <span><a href="javascript:void(0)"><img src="dist/img/dashboard-icons/close.png"></a></span>
 -->                                </td>
                              </tr>
                              @endforeach
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


          <!-- UPDATE PLAN  -->
          @if(!empty($updatePlansDetails))
          <div class="col-md-4 p-l p-right-30" id="add_show">
            <div class="box box-success bx-grey">
              <div class="loader" id="loader">
                <img src="{{ asset('assets/admin_assets/dist/img/dashboard-icons/833.gif')}}" />
              </div>
             <div class="form-inner-container" id="form-content">
                  <div class="box-header">
                      <h3 class="box-title">Plan Name : {{$updatePlansDetails['plan_name']}}</h3>
                    </div>
                    <div class="box-body bx-form">
                      <form action="{{url('/admin/save-plans/'.$updatePlansDetails['period_id'])}}" method="post">
                      @csrf
                      <input type="hidden" id="{{$updatePlansDetails['id']}}" value="{{$updatePlansDetails['id']}}">
                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="inputZip">Monthly cost</label>
                            <div class="price-details">
                             <input type="text" class="form-control amount_first" id="amount" value="{{$updatePlansDetails['monthly_cost']}}" name="monthly_cost" required pattern="^\d*(\.\d{0,2})?$" disabled>
                             <span class="symbol"><i class="fa fa-euro" aria-hidden="true"></i></span>
                            </div>
                            <span class="credit_img"><img src="{{ asset('assets/admin_assets/dist/img/dashboard-icons/note.png')}}"></span>
                          </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                              <label for="inputEmail4">Max number of languages</label>
                              <input type="text" class="form-control" id="cre" value="{{$updatePlansDetails['max_languages']}}" name="max_languages" required onkeypress="return isNumberKey(event)">
                              <span class="credit_img"><img src="{{ asset('assets/admin_assets/dist/img/dashboard-icons/language-new.png')}}"></span>
                            </div>
                          </div>


                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <label for="inputEmail4">Included pageviews</label>
                            <input type="text" class="form-control" id="cre" value="{{$updatePlansDetails['included_pageviews']}}" name="included_pageviews" required onkeypress="return isNumberKey(event)">
                            <span class="credit_img"><img src="{{ asset('assets/admin_assets/dist/img/dashboard-icons/data.png')}}"></span>
                          </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-12">
                              <label for="inputZip"> Extra cost of pageviews / {{config('constants.EXTRA_COST_OF_PAGE_VIEWS_PER')}}</label>
                            <div class="price-details">
                             <input type="text" class="form-control amount_first" id="amount" value="{{$updatePlansDetails['extra_cost_pageviews']}}" required name="extra_cost_pageviews" pattern="^\d*(\.\d{0,2})?$">
                             <span class="symbol"><i class="fa fa-euro" aria-hidden="true"></i></span>
                            </div>
                            <span class="credit_img"><img src="{{ asset('assets/admin_assets/dist/img/dashboard-icons/note.png')}}"></span>
                            </div>
        
                          </div>
                          <div class="form-row">
                              <div class="form-group col-md-12">
                                <label for="inputEmail4">Translation credits</label>
                                <input type="text" class="form-control" id="cre" value="{{$updatePlansDetails['translation_credits']}}" name="translation_credits" required onkeypress="return isNumberKey(event)">
                                <span class="credit_img"><img src="{{ asset('assets/admin_assets/dist/img/dashboard-icons/data.png')}}"></span>
                              </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                  <label for="inputEmail4">Additional cost per {{config('constants.ADDITIONAL_CHARACTER_PER_PAGE')}} credit</label>
                                  <div class="price-details">
                                  <input type="text" class="form-control amount_first" id="cre" value="{{$updatePlansDetails['additional_characters']}}" name="additional_characters" required onkeypress="return isNumberKey(event)">
                                    <span class="symbol"><i class="fa fa-euro" aria-hidden="true"></i></span>
                                  </div>
                                   <span class="credit_img"><img src="{{ asset('assets/admin_assets/dist/img/dashboard-icons/note.png')}}"></span>
                                  </div>
                              </div>
      
                        <div class="form-row">
                          <button type="submit" class="btn-default add_to_credit">Update Plan</button>
                        </div>
                      </form>
                    </div>
              </div>
            </div>
          </div>
          @endif

          <!-- Add NEW PLAN-->
          <div class="col-md-4 p-l p-right-30" id="add_new_plan">
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
                         <label for="inputZip">Plan Name</label>
                            <div class="price-details">
                             <input type="text" class="form-control amount_first" id="new_plan_name"  required name="new_plan_name" required>
                             <!-- <span class="symbol"><i class="fa fa-euro" aria-hidden="true"></i></span> -->
                            </div>
                            <!-- <span class="credit_img"><img src="{{ asset('assets/admin_assets/dist/img/dashboard-icons/note.png')}}"></span> -->
                          </div>
                        </div>
                        <div class="form-row">
                          <div class="form-group col-md-12">
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

        <!-- /.row -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection

<script>
window.onload = function() {
  $("#add_new_plan").hide();
};
function myFunction(id) {
  var x = document.getElementById("add_new_plan");
  if (x.style.display === "none") {
    x.style.display = "block";
    $("#add_show").hide();
  } else {
    x.style.display = "none";
  }
}
</script>
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