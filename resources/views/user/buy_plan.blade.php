@extends('layouts.home')
@section('title')
TRANSLICIO | Buy Plan
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
<center>
<div id='loadingmessage' style='display:none'>
  <img src="assets/upload/loading.gif">
</div>
<center>

<div class="translate-document-main personal-main" id="main">
    <div class="container custom-con">
      <div class="row">
        <div class="col-md-12 text-center">
          <h2 class="translate-heading profile-heading">Pricing Plan</h2>
          <!-- @if(session()->has('message'))
                          <div class="alert alert-success">
                            {{ session()->get('message') }}
                          </div>
                      @endif -->

                      @if(session('success'))
    					<div class="alert alert-success">
      						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      						{!! session('success') !!}
    					</div>
            @endif
            @if($errors->any())
  						<div class="alert alert-danger">
    							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    							@foreach($errors->all() as $error)
    								<p>{!! $error !!}</p>
    							@endforeach
  						</div>
  					@endif
          <div class="buy-credit-main">
            <!-- start tab section -->
           <ul id="myTabs" class="nav nav-pills nav-justified pricing_tabs" role="tablist" data-tabs="tabs">
              <li class="active annual_plan"><a href="#annual" data-toggle="tab" class="{{(($currentPlanDetails[0]['period_time']=='Y')?'active':'')}}">Annual</a></li>
             <?php if($currentPlanDetails[0]['period_time']!='Y')
             {?>
              <li class="annual_plan monthly"><a href="#monthly" data-toggle="tab" class="{{(($currentPlanDetails[0]['period_time']=='M')?'active':'')}}">Monthly</a></li>
            <?php
             }
             ?>
            </ul>
  <div class="tab-content">
    
    <div role="tabpanel" class="tab-pane {{(($currentPlanDetails[0]['period_time']=='Y')?'active':'fade')}}" id="annual">

            <!-- start pricing plan section -->
             <div class="plan-block-wraper">
               @foreach($planDetailsYear as $plan)
                      <div class="plan-block-outer">
                        <div class="plan-block"> 
                        @if($ActivePlanId == $plan['stripe_plan_id'] )
                        <h4 class="most_heading">Subscribed</h4>
                        @elseif($plan['plan_name']=="Silver")
                        <!-- <h4 class="most_heading">Most Popular</h4> -->
                        @endif
                          <div class="plan-block-body">
                              <h3 class="price-title">{{$plan['plan_name']}} <span>€{{$plan['monthly_cost']}}</span></h3>
                              <span class="current_plan">save 2 months</span>
                              <ul class="price-listing">
                                <li>Max no. of Language <span>{{$plan['max_languages']}}</span></li>
                                <li>Pageview<span>{{$plan['included_pageviews']}}</span></li>
                                <li>Extra Pageview<span>€{{$plan['extra_cost_pageviews']}}</span><br><small>per {{config('constants.EXTRA_COST_OF_PAGE_VIEWS_PER')}}</small></li>
                                <li>Characters Included<span>{{$plan['translation_credits']}}</span></li>
                                <li>Additional Character <span>€{{$plan['additional_characters']}}</span><br> <small>per {{config('constants.ADDITIONAL_CHARACTER_PER_PAGE')}}</small></li>
                              </ul>
                              @if($ActivePlanPrice > $plan['monthly_cost'])
                              <a href="javascript:void(0);" onclick="openPaymentForm('<?php echo $plan['id'];?>')"><button class="select-plan">Downgrade Plan</button></a>
                                @elseif($ActivePlanPrice < $plan['monthly_cost'])
                                <a href="javascript:void(0);" onclick="openPaymentForm('<?php echo $plan['id'];?>')"><button class="select-plan">Upgrade Plan</button></a>
                                @endif
                            
                            </div>
                        </div>
                      </div>
                @endforeach
                      
                    </div>
            <!-- end pricing plan section -->
  </div>
    <div role="tabpanel" class="tab-pane  {{(($currentPlanDetails[0]['period_time']=='M')?'active':'fade')}}" id="monthly">
      
            <!-- start pricing plan section -->
             <div class="plan-block-wraper">
               @foreach($planDetailsMonth as $plan)
                      <div class="plan-block-outer">
                        <div class="plan-block">
                       
                        @if($ActivePlanId == $plan['stripe_plan_id'] )
                        <h4 class="most_heading">Subscribed</h4>
                        @elseif($plan['plan_name']=="Silver")
                        <!-- <h4 class="most_heading">Most Popular</h4> -->
                        @endif
                          <div class="plan-block-body">
                              <h3 class="price-title">{{$plan['plan_name']}} <span>€{{$plan['monthly_cost']}}</span></h3>
                              <ul class="price-listing">
                                <li>Max no. of Language <span>{{$plan['max_languages']}}</span></li>
                                <li>Pageview <span>{{$plan['included_pageviews']}}</span></li>
                                <li>Extra Pageview <span>€{{$plan['extra_cost_pageviews']}}</span><br><small>per {{config('constants.EXTRA_COST_OF_PAGE_VIEWS_PER')}}</small></li>
                                <li>Characters Included<span>{{$plan['translation_credits']}}</span></li>
                                <li>Additional Character <span>€{{$plan['additional_characters']}}</span><br> <small>per {{config('constants.ADDITIONAL_CHARACTER_PER_PAGE')}}</small></li>
                              </ul>
                               @if($ActivePlanPrice > $plan['monthly_cost'])
                              <a href="javascript:void(0);" onclick="openPaymentForm('<?php echo $plan['id'];?>')"><button class="select-plan">Downgrade Plan</button></a>
                                @elseif($ActivePlanPrice < $plan['monthly_cost'])
                                <a href="javascript:void(0);" onclick="openPaymentForm('<?php echo $plan['id'];?>')"><button class="select-plan">Upgrade Plan</button></a>
                                @endif
                            </div>
                        </div>
                      </div>
                @endforeach
                    </div>
            <!-- end pricing plan section -->
    </div>
  </div>
<!-- end tab section -->


        
      
        <!-- class="pricing-details collapse" -->
        <div class="pricing-details" id="pricing_details" style="display:none" >
              <div class="total-amount">
              <h2 class="total_am_price">Total Payble : <span id='payable_span_id'></span></h2>
              </div>
              <div id="card-element">
              <div class="buy-credit-fillup">
                <ul class="card_list clearfix">
                  <li class="float-left">Make your payment</li>
                  <li class="float-right"><img src="{{ asset('assets/images/card/master-card.png')}}" class="img-fluid"> &nbsp;
                    <img src="{{ asset('assets/images/card/visa.png')}}" class="img-fluid">&nbsp;
                    <img src="{{ asset('assets/images/card/discover.png')}}" class="img-fluid"> &nbsp;
                    <img src="{{ asset('assets/images/card/american.png')}}" class="img-fluid">
                  </li>

                </ul>
                <div class="form_field_picker">
                <form action="{{url('/payment')}}" method="post"  id="payment-form">
                @csrf
                <span class="payment-errors text-danger"></span>
                <input type="hidden"  name="plan_id" id="plan_id">

                <div id="card-element">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group text-box-group mb-0 card-box-c">
                          <label for="credit">Credit Card Number:</label>
                          <!-- <input type="text" class="form-control creditCardText" id="card_number" data-stripe="number" onkeyup="GetCardType(this.value)" required>
                        <div id="card_company" class=""></div> -->

                        <input type="text" name="card_number" id="card_number" data-stripe="number" class="form-control creditCardText" placeholder="Card Number"  onkeypress="return numeric_with_dash(event)" onkeyup="formatCardNumberToShow(this, '-'); GetCardTypeNew(this, '-')" maxlength="19" oncopy="return false" oncut="return false" onpaste="return false" required>
											<span class="cardIco"></span>
                        </div>
                      </div>
                      <div class="col-md-7">
                        <div class="form-group">
                          <label for="credit">Expiration Date:</label>
                          <div class="custom_box">
                            <input type="text" class="form-control" id="card_month" placeholder="MM" data-stripe="exp_month" maxlength="2"  onkeypress="return isNumberKey(event)" required>
                            <span class="calander"><img src="{{ asset('assets/images/card/calander.png')}}" class="img-fluid"></span>
                            <input type="text" class="date-own form-control" id="card_year"
                              placeholder="YY" data-stripe="exp_year" onkeypress="return isNumberKey(event)" maxlength="2" required>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="form-group">
                          <label for="credit">Security Code</label>
                          <input type="text" class="form-control" id="card_cvv" placeholder="CVV Code" data-stripe="cvc" onkeypress="return isNumberKey(event)" maxlength="4" required>
                          <span class="question1"><img src="{{ asset('assets/images/card/question.png')}}" class="img-fluid"></span>
                        </div>
                      </div>
                    </div>
                </div>
                 
                  <!-- onclick="location.href='translate-step5.html';" -->
                  <p class="credit" id="credit_id"></p>
                  <div class="btn_center"><button type="submit"  id="submit_id" 
                      class="button btn_download btn_pay submit" onclick="createTokenCust()">Pay &amp; Translate</button></div>
                </div>
              </div>
            </div>
            </form>
            <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
           
          </div>
        </div>
      </div>
    </div>
  </div>
  
   <!-- start js section -->
  

   <script src="{{ asset('assets/assets/js/jquery-1.9.1.min.js')}}"></script>
  
  
  <script src="{{ asset('assets/assets/js/bootstrap.js')}}"></script>
  <script src="{{ asset('assets/assets/js/custom.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>

  <!-- <script>
    $('.date-own').datepicker({
      minViewMode: 2,
      format: 'yyyy'
    });
  </script> -->

<script>
  //Stripe.setPublishableKey('pk_test_MYoAp2m6XpkXxFEvOeUn7nD800gU2XzXvz');
  Stripe.setPublishableKey('{!! env('STRIPE_KEY') !!}');
  
  function createTokenCust() {
    
  var $form = $('#payment-form');
 // $form.find('.submit').html('Processing...');

  $form.submit(function(event) {
    // Disable the submit button to prevent repeated clicks:
    $form.find('.submit').prop('disabled', true);
    

    // Request a token from Stripe:
    Stripe.card.createToken($form, stripeResponseHandler);

    // Prevent the form from being submitted:
    return false;
  });
  }


function stripeResponseHandler(status, response) {
  // Grab the form:
 
  var $form = $('#payment-form');

  if (response.error) { // Problem!

    // Show the errors on the form:
    $form.find('.payment-errors').text(response.error.message);
    $form.find('.submit').prop('disabled', false); // Re-enable submission

  } else { // Token was created!

    // Get the token ID:
    var token = response.id;
    $form.find('.submit').html('Processing...');
    $form.find('.submit').prop('disabled', true);
    // Insert the token ID into the form so it gets submitted to the server:
    $form.append($('<input type="hidden" name="stripeToken">').val(token));

    //Submit the form:
    $form.get(0).submit();
    
    // For this demo, we're simply showing the token:
    
  }
};
function GetCardType(number) {
      //let number = $("#ccno").val();
      var re = new RegExp("^4");
      var cardName='';
      if (number.match(re) != null) 
      {
        
      cardName = "Visa";
      $('#card_company').removeAttr('class').addClass('fa fa-cc-visa fa-2x');
     //$('#card_company').removeAttr('class');
      }
      re = new RegExp("^(34|37)");
      if(number.match(re) != null) 
      {
        cardName = "American Express";
        $('#card_company').removeAttr('class').addClass('fa fa-cc-amex fa-2x');
      }
      re = new RegExp("^5[1-5]");
      if(number.match(re) != null)
      {
       cardName = "MasterCard";
       $('#card_company').removeAttr('class').addClass('fa fa-cc-mastercard fa-2x');
      }

      re = new RegExp("^6011");
   if(number.match(re) != null)
      {
       cardName = "Discover";
       $('#card_company').removeAttr('class').addClass('fa fa-cc-discover fa-2x');
      }
      if(number=='')
      {
        $('#card_company').removeAttr('class');
      }
//       else{
// $('#card_company').removeAttr('class');
// }

//$('#card-company').html(cardName);
      $(".creditCardText").keyup(function() {
        var foo = $(this)
          .val()
          .split("-")
          .join(""); // remove hyphens
        if (foo.length > 0) {
          foo = foo.match(new RegExp(".{1,4}", "g")).join("-");
        }
        $(this).val(foo);
      });
    }
   </script>

  <script>
    $('#data').datepicker({
      format: "dd-MM",
      todayHighlight: true,
      autoclose: true,
      clearBtn: true
    });
  </script>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<SCRIPT language=Javascript>

  function openPaymentForm(plan_id)
  {
   
    // window.scrollBy('#pricing_details');
    //$('#loadingmessage').show(); 
    // $('html, body').animate({
    //     scrollTop: $("#pricing_details").offset().top
    // }, 'slow');

    $(document).ajaxStart(function(){
  // Show image container
  $("#main").hide();
   $("#loadingmessage").show();
  //alert("OKAY");
});
$(document).ajaxComplete(function(){
  // Hide image container
  $("#loadingmessage").hide();
  $("#main").show();
  $('html, body').animate({
        scrollTop: $("#pricing_details").offset().top
    }, 'slow');
});

   

    $("#card_number").val('');
    $("#card_month").val('');
    $("#card_cvv").val('');
    $("#card_year").val('');
    
    $.ajax({
			
      url:"{{url('/buy-plan/payment-form')}}"+'/'+plan_id,
		  type: "GET",
		  data: {},
        dataType : 'json',
		  success: function(response){
		  //  console.log(response);
      //  alert(response);
       $("#plan_id").val(plan_id);
    $('#payable_span_id').html('£ '+response.payable_cost+ ' (EUR)' );
    $('#credit_id').html('Pay - £ '+response.payable_cost);
    $("#pricing_details").show();
		    }
		});
    

}

function isNumberKey(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

         return true;  
  }



function formatCardNumberToShow(txt, separator = '-') {
		var str = txt.value;
		//console.log(str);
		if (str == null || str == 'undefined' || str == '') {
			txt.value = str;
		}
		if (str.length >= 1) {
			str = str.split(' ').join('');
			str = str.split(separator).join('');
		}
		if (str.length > 16) {
			str = str.substring(0, 16);
		}
		var v = str.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
		var matches = v.match(/\d{4,16}/g);
		var match = matches && matches[0] || '';
		var parts = [];

		for (var i = 0, len = match.length; i < len; i += 4) {
			parts.push(match.substring(i, i + 4));
		}

		if (parts.length) {
			txt.value = parts.join(separator);
		} else {
			txt.value = str;
		}
	}

  function numeric_with_dash(event) {
		var charCode = (event.which) ? event.which : event.keyCode;
		if (charCode != 45 && (charCode < 48 || charCode > 57))
		return false;
	}


  function GetCardTypeNew(ext, separator)
	{
		var number = ext.value.split(separator).join('');
		if(number==null || number=='undefined' || number.length<=2)
		{
			$(ext).parent().removeAttr("class");
			$(ext).parent().attr("class", "form-group text-box-group mb-0 card-box-c");
		}
	
		var re = new RegExp("^4");
		var type = '';
		re = new RegExp("^4[0-9]{0,}$");
		if (number.match(re) != null)
		{
			type = "visa";
		}
	
		re = new RegExp("^(5[1-5]|222[1-9]|22[3-9]|2[3-6]|27[01]|2720)[0-9]{0,}$");
		if (number.match(re) != null)
		{
			type = "masterCard";
		}
	
		re = new RegExp("^(5[06789]|6)[0-9]{0,}$");
		if (number.match(re) != null)
		{
			type = "maestro";
		}
	
		// AMEX
		re = new RegExp("^3[47]");
		if (number.match(re) != null)
		{
			type = "amex";
		}
	
		// Discover
		re = new RegExp("^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)");
		if (number.match(re) != null)
		{
			type = "discover";
		}
	
		// Diners
		re = new RegExp("^36");
		if (number.match(re) != null)
		{
			type = "diners";
		}
	
		// Diners - Carte Blanche
		re = new RegExp("^30[0-5]");
		if (number.match(re) != null)
		{
			//type = "Diners - Carte Blanche";
			type = "diners";
		}
	
		// JCB
		re = new RegExp("^35(2[89]|[3-8][0-9])");
		if (number.match(re) != null)
		{
			type = "jcb";
		}
	
		// Visa Electron
		re = new RegExp("^(4026|417500|4508|4844|491(3|7))");
		if (number.match(re) != null)
		{
			type = "visa";
		}
		console.log(type);
		if(type!="")
		{
			$(ext).parent().addClass(type);
		}
		else
		{
			$(ext).parent().removeAttr("class");
			$(ext).parent().attr("class", "form-group text-box-group mb-0 card-box-c");
		}
		//return type;
	}
</SCRIPT>
