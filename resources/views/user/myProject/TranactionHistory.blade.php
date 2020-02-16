@extends('layouts.home')
@section('title')
TRANSLICIO | Transaction History
@endsection
@section('add-meta')
<style type="text/css">
  .t-red{
     font-size: 80%;
     color: #dc3545;
  }
</style>
@endsection

@section('content')

<div class="translate-document-main personal-main">
         <div class="container custom-con">
            <div class="row">
               <div class="col-md-12 text-center">
                  <h2 class="translate-heading profile-heading">Transaction History</h2>
               </div>
               <div class="col-md-12">
                  <div class="credit_history">
                     <h2 class="credit-history-heading">Please check your credit invoice</h2>
                     <div class="credit_all_histohry">
                        <div class="history_table credit_purchase_width">
                           <div class="table-responsive">
                              <!--Table-->
                              <table id="example" class="table table-striped">
                                 <thead>
                                    <tr>
                                       <th class="th-lg">Date</th>
                                       <th class="th-lg">Invoice Number</th>
                                       <th class="th-lg">Invoiced item</th>
                                       <th class="th-lg">Amount in Euro (€)</th>
                                       <th class="th-lg">Action (pdf download)</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                 @foreach($SubscriptionsHistory as $Subscriptions)
                                    <tr>
                                       <td>{{$Subscriptions['created_at']}}</td>
                                       <td>--TRA145887--</td>
                                       <td>{{$Subscriptions['planName']}} / {{$Subscriptions['period']}} - Exceeded volume - 150 translated characters</td>
                                       <td>€ {{$Subscriptions['price']}}</td>
                                       <td>
                                          <div class="credit_dwn p-left-40">
                                             <a href="javascript:void(0)" class="pdf_dwn" download>
                                             <img src="assets/images/icons/pdf.png" class="img-fluid">
                                             <span><img src="assets/images/icons/download.png" class="img-fluid"></span>
                                             Download
                                             </a>
                                          </div>
                                       </td>
                                    </tr>
                                 @endforeach
                                 </tbody>
                              </table>
                              <!--Table-->
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- end my profile section your document section -->

@endsection
