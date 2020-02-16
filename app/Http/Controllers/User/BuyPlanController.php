<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
 use App\User;
//use App\Models\User;
use App\Models\Plans;
use App\Models\Subscription;
use App\Models\SubscriptionsHistory;
use App\Models\FreeCredits;
use App\Models\UserFreeCredits;
use DB;
use Validator;
use Auth;
use Carbon\Carbon;
use App\Models\DowngradeSubscription;
class BuyPlanController extends Controller
{
    /* Display The List Of Plans Of Form */
    public function planList(){

        /* Buy Plans List Send */
        $s_key=env('STRIPE_SECRET');
        \Stripe\Stripe::setApiKey($s_key);


        // $sub = Subscription::where('user_id',Auth::user()->id)->first();
        // //dd($sub);
        //     $SubDetails=\Stripe\Subscription::retrieve($sub['stripe_id']);
        //     $sub->update([
        //         'ends_at' => $SubDetails['ended_at'],
        //     ]);


        /* For Select The Subscription Data */
        $subscriptionDetails = Subscription :: where('user_id',Auth::user()->id)->first();
    //print_r($subscriptionDetails);exit;
        if(!empty($subscriptionDetails)){
            $ActivePlanId = $subscriptionDetails['stripe_plan'];
            $ActivePlanPrice = Plans::where('stripe_plan_id',$ActivePlanId)->first();
            $ActivePlanPrice = $ActivePlanPrice['monthly_cost'];
        }
        else{
            $ActivePlanId = '';
        }

        $planDetailsMonth = Plans::where('period_time','m')->where('monthly_cost','>',0)->orderBy('monthly_cost','ASC')->get(); // Months List
        $planDetailsYear = Plans::where('period_time','y')->where('monthly_cost','>',0)->orderBy('monthly_cost','ASC')->get(); //Years List
        $currentPlanDetails=Plans::where('stripe_plan_id',$subscriptionDetails['stripe_plan'])->get();
      $current_plan_type= $currentPlanDetails[0]['period_time'];
        $planDetails='';
        $user= User::where('email',AUth::user()->email)->first();
        $intent=$user->createSetupIntent();

        $TotalCredit = FreeCredits::TotalCredit();
        return view('user.buy_plan',compact('TotalCredit','current_plan_type','currentPlanDetails','planDetailsMonth','planDetailsYear','planDetails','intent','ActivePlanId','ActivePlanPrice'));
    }

    public function PaymentForm($id){
     $id=trim($id);  
$payable_cost=0;
$flag=0;
        /* Check User Already have a Plan Subscribed or not */
        $subscriptionDetails = Subscription :: where('user_id',Auth::user()->id)->first();
       // if(!empty($subscriptionDetails)){
            //return redirect('/buy-plan')->with('message', 'You Have Already A plan Subscribed!');
        // }
        // else{
                /* For Select The Subscription Data */
                $subscriptionDetails = Subscription :: where('user_id',Auth::user()->id)->first();
              
                //dd($subscriptionDetails);
                if(!empty($subscriptionDetails)){
                    $ActivePlanId = $subscriptionDetails['stripe_plan'];
                }
                else{
                    $ActivePlanId = '';
                }

                $buyPlanDetails = Plans::where('id',$id)->first();
                //dd($buyPlanDetails);
                //$intent => createSetupIntent();

                // extra cost strat//

               
        //dd($sub);
        $user= User::where('email',AUth::user()->email)->first();
           // $SubDetails=\Stripe\Subscription::retrieve($sub['stripe_id']);

                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

                // Set proration date to this moment:
                $stripe_customer_id=$user['stripe_id'];
                if($stripe_customer_id!='' || $stripe_customer_id!=NUll)
                {
                $customerDetailsStripe=\Stripe\Customer::retrieve($stripe_customer_id);
                
                if($customerDetailsStripe['subscriptions']['total_count']==1)
                {
                    $sub = Subscription::where('user_id',Auth::user()->id)->first();
                $proration_date = time();
                
                $subscription = \Stripe\Subscription::retrieve($sub['stripe_id']);
                $planDetails = Plans::where('id',$id)->first();
               
                
                
                // See what the next invoice would look like with a plan switch
                // and proration set:
                $items = [
                    [
                        'id' => $subscription->items->data[0]->id,
                        'plan' => $planDetails['stripe_plan_id'], # Switch to new plan
                    ],
                ];
                
                $invoice = \Stripe\Invoice::upcoming([
                    'customer' => $stripe_customer_id,
                    'subscription' => $sub['stripe_id'],
                    'subscription_items' => $items,
                    'subscription_proration_date' => $proration_date,
                ]);
                // echo $proration_date;
                // print_r($invoice);exit;
                // Calculate the proration cost:
                $cost = 0;
                $current_prorations = [];
                foreach ($invoice->lines->data as $line) {
                    if ($line->period->start == $proration_date) {
                        array_push($current_prorations, $line);
                        $cost += $line->amount;
                        $response['cost']=$cost;
                        $flag=1;
                    }
                }
                        
                if($cost >0)
                {
                    $response['flag']=$flag;
                $payable_cost= $cost/100;
                }
                else
                {
                    $response['flag']=$flag;
                $payable_cost= $planDetails['monthly_cost'];
                }

            }
        }
$response['payable_cost']=$payable_cost;
 echo json_encode($response);

                //extra cost end//
// if(empty($subscriptionDetails)){
//     $payable_cost='';
// }


                // $planDetailsMonth = Plans::where('period_time','m')->get(); // Months List
                // $planDetailsYear = Plans::where('period_time','y')->get(); //Years List
                // $currentPlanDetails=Plans::where('stripe_plan_id',$planDetails['stripe_plan_id'])->get();
                // $current_plan_type= $currentPlanDetails[0]['period_time'];
                // $intent=$user->createSetupIntent();

                // return view('user.buy_plan',compact('currentPlanDetails','current_plan_type','planDetailsMonth','planDetailsYear','buyPlanDetails','intent','ActivePlanId','payable_cost'));   
       // }
    }

    public function Payment(Request $request){
       
        $input = $request->input();
        //dd($input);
    
        $s_key=env('STRIPE_SECRET');
            \Stripe\Stripe::setApiKey($s_key);
           
        /* Validation Rules */
        $validator = Validator::make($request->all(),[
            // 'card_number' => 'required',
            // 'cvv' => 'required',
            // 'exp_month' => 'required',
            // 'exp_year' => 'required',

        ]);
        if($validator->fails()){
            return back()->withInput($input)->withErrors($validator);
        }
        else{
            /* Payment Part Done Here */
            
            /* Stripe Key */

            
            $s_key=env('STRIPE_SECRET');
            \Stripe\Stripe::setApiKey($s_key);

            /* Plan Details */
            $planDetails = Plans::where('id',$request['plan_id'])->first();

            /* Local Customer Details */

            $user= User::where('email',AUth::user()->email)->first();
            
            /* Customer Check */
            if(empty($user['stripe_id']) || $user['stripe_id']==null){
                /* Create New Customer */
                $StripeCustomerCreateResponse=$user->createAsStripeCustomer();


                $stripe_customer_id=$StripeCustomerCreateResponse['id'];

                /* Return Response Save Into Local DB in JSON form */
                $user->update([
                    'stripe_response' => json_encode($StripeCustomerCreateResponse),
                ]);

              }
            else{
                $stripe_customer_id=Auth::user()->stripe_id;
            }
            
            /* Fetch Stripe Customer Details From Stripe*/
           
            $customerDetailsStripe=\Stripe\Customer::retrieve($stripe_customer_id);

            

            $timestamp_sub = $user -> asStripeCustomer()["subscriptions"] -> data[0]["current_period_end"];

            // Cast to Carbon instance and return
    // \Carbon\Carbon::createFromTimeStamp($timestamp) -> toFormattedDateString()
            $current_period_end= date('Y-m-d',$timestamp_sub);
            $action_date=date('Y-m-d', strtotime($current_period_end . ' +1 day'));

            /* Check Card Is Added Or Not */

            if($user['card_brand'] == null || empty($user['card_brand'])){
                /* ADD card For That customer */
                $card = \Stripe\Customer::update(
                    $stripe_customer_id,
                    [
                        'source' => $input['stripeToken'],
                    ]
                );
                $source = $card['default_source'];
            }
            else{
                $source = $customerDetailsStripe['default_source'];
            }

            /* If Already Have a plan Subscribed */
            if($customerDetailsStripe['subscriptions']['total_count']==0){
                $user->newSubscription('Test', $planDetails['stripe_plan_id'])->create($source);
           
            }
            /* Switching Plans */
            elseif($customerDetailsStripe['subscriptions']['total_count']==1){

                $user= User::where('email',AUth::user()->email)->first();
                //proration end//
                 $swapPlan_from_id=$user->subscription('Test')->stripe_plan;
                
                 $swapPlan_to_id=$planDetails['stripe_plan_id'];
                if($this->CheckValidUpgrade($swapPlan_from_id,$swapPlan_to_id))
                {
               $response=$user->subscription('Test')->swapAndInvoice($planDetails['stripe_plan_id']);

               /* Free Credit Calculations */
               $AffiliatedUserDetails = User::where('id',$user['id'])->first();
               if($AffiliatedUserDetails['affiliated_status']  ==  1){
                   $FreeCredits = FreeCredits::where('credit_status',1)->first();
                   $UserFreeCreditsDetails = UserFreeCredits::where('user_id',$user['id'])->first();
                   if(($UserFreeCreditsDetails) == null){
                       $UserFreeCredits = new UserFreeCredits();
                       $UserFreeCredits->s_id = $AffiliatedUserDetails['affiliated_by'];
                       $UserFreeCredits->r_id = 0;
                       $UserFreeCredits->user_id = $AffiliatedUserDetails['id'];
                       $UserFreeCredits->credit = $FreeCredits['credit_amount'];
                       $UserFreeCredits->credit_status = 0;
                       $UserFreeCredits->description = 'Upgrade';
                       $UserFreeCredits->date = date('Y-m-d');
                       $UserFreeCredits->status = 0;
                       $UserFreeCredits->save();
                   }
               }

               
               $msg='You have Updated to New Plan!';
                }
                else{
                    //$user->subscription('Test')->noProrate()->swapAndInvoice($planDetails['stripe_plan_id']);
                   
                    $pending_downgrade_request_count= DowngradeSubscription::where('status',1)->where('user_id',AUth::user()->id)->get();
                   
                   if(count($pending_downgrade_request_count)>0)
                   {
                       $msg='You have already made a request for downgrade, You cannot downgrade to another plan now.';
                    return redirect('/buy-plan')->withErrors($msg);
                   }
                    \Stripe\Stripe::setApiKey($s_key);

                    \Stripe\Subscription::update(
                      $user->subscription('Test')->stripe_id,
                      [
                        'cancel_at_period_end' => true,
                      ]
                    );
                  
                    $subscriptionLogs=new DowngradeSubscription();
                   $subscriptionLogs->user_id=AUth::user()->id;
                   $subscriptionLogs->stripe_plan=$planDetails['stripe_plan_id'];
                   $subscriptionLogs->action_date=$action_date;
                   $subscriptionLogs->status=0;
                   $subscriptionLogs->save();
                    $msg='You will be downgraded to plan after current period end.';
                }
                return redirect('/buy-plan')->withSuccess($msg);
            }
            else{
                return redirect('/buy-plan')->withSuccess('You Have Already A plan Subscribed!');
            }
            return redirect('/buy-plan')->withSuccess('Subscription Added Successfully!');
        }
    }

    function CheckValidUpgrade($form_plan,$to_plan)
    {
        $formPlanDetails=Plans::where('stripe_plan_id',$form_plan)->first()->toArray();
        $toPlanDetails=Plans::where('stripe_plan_id',$to_plan)->first()->toArray();

        if($toPlanDetails['monthly_cost'] > $formPlanDetails['monthly_cost'])
        return true;
        else
        return false;
    }
}