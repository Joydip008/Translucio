<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SubscriptionsHistory;
use App\Models\WebhookEventsHistory;
use App\Models\User;
use DB;
use App\Models\InvoiceDetails;
use App\Models\Plans;
use App\Models\UserCreditHistory;
use App\Models\FreeCredits;

class WebhookHandleController extends Controller
{ 
    public function WebhookHandle(Request $request)
    {
        $payload=$request->all();
         $object=$payload['data']['object'];
         $type=$payload['type'];

       
         $UserDetails = User::where('stripe_id',$object['customer'])->first();
         
         switch($type)
         {
            case 'customer.subscription.created':
                $PlanDetails = $payload['data']['object']['items']['data'][0];
                $PlanId = $PlanDetails['plan']['id'];

                $current_period_start= date('Y-m-d',$object['current_period_start']);
                $current_period_end= date('Y-m-d',$object['current_period_end']);
                $subscriptionHistory = new SubscriptionsHistory();
                $subscriptionHistory->user_id=$UserDetails['id'];
                $subscriptionHistory->start_date=$current_period_start;
                $subscriptionHistory->end_date=$current_period_end;
                $subscriptionHistory->status='Y';
                $subscriptionHistory->stripe_id=$object['id'];
                $subscriptionHistory->stripe_plan=$PlanId;

                $PlanDetails = Plans::where('stripe_plan_id',$PlanId)->first();
                $subscriptionHistory->plan_credit=$PlanDetails['translation_credits'];

                $subscriptionHistory->save();

               

                break;
            
            case 'customer.subscription.updated':
                $PlanDetails = $payload['data']['object']['items']['data'][0];
                $PlanId = $PlanDetails['plan']['id'];
                
                $UserDetails = User::where('stripe_id',$object['customer'])->first();
                $current_period_start= date('Y-m-d',$object['current_period_start']);
                $current_period_end= date('Y-m-d',$object['current_period_end']);
                $subscriptionHistoryDetails = SubscriptionsHistory::where('user_id', $UserDetails['id'])->get();
                foreach($subscriptionHistoryDetails as $subData){
                    $subData->update([
                        'status' => 'N',
                    ]);
                }
                $subscriptionHistory = new SubscriptionsHistory();
                $subscriptionHistory->user_id=$UserDetails['id'];
                $subscriptionHistory->start_date=$current_period_start;
                $subscriptionHistory->end_date=$current_period_end;
                $subscriptionHistory->status='Y';
                $subscriptionHistory->stripe_id=$object['id'];
                $subscriptionHistory->stripe_plan=$PlanId;

                $PlanDetails = Plans::where('stripe_plan_id',$PlanId)->first();
                $subscriptionHistory->plan_credit=$PlanDetails['translation_credits'];

                $subscriptionHistory->save();

                // $AffiliatedUserDetails = User::where('id',$UserDetails['id'])->first();
                // if($AffiliatedUserDetails['affiliated_status']  ==  1){
                //     $FreeCredits = FreeCredits::where('credit_status',1)->first();

                //     $UserCreditHistoryDetails = UserCreditHistory::where('user_id',$UserDetails['id'])->first();
                //     if(!empty($UserCreditHistoryDetails)){
                //         $UserCreditHistory = new UserCreditHistory();
                //         $UserCreditHistory->s_id = $AffiliatedUserDetails['affiliated_by'];
                //         $UserCreditHistory->r_id = 0;
                //         $UserCreditHistory->user_id = $UserDetails['id'];
                //         $UserCreditHistory->credit = $FreeCredits['credit_amount'];
                //         $UserCreditHistory->credit_status = 0;
                //         $UserCreditHistory->description = 'Upgrade';
                //         $UserCreditHistory->date = date('Y-m-d');
                //         $UserCreditHistory->status = 0;
                //         $UserCreditHistory->save();
                //     }
                // }
                
                break;
            case 'charge.succeeded':
                DB::table('text_count')->where('user_id',$UserDetails['id'])
                    ->chunkById(100, function ($text_count) {
                    foreach ($text_count as $text) {
                        DB::table('text_count')
                            ->where('id', $text->id)
                            ->update(['bill_status' => 'Y' , 'payment_status' => 'Y']);
                    }
                });
                break;
            case 'invoice.payment_succeeded':
                /* Save The All Details About Invoices */
                $InvoiceDetails = new InvoiceDetails();
                $PlanId = $payload['data']['object']['lines']['data'][0];
                $PlanId = $PlanId['plan']['id'];

                $InvoiceDetails->plan_id = $PlanId;//$object['data']['object']['lines']['data'][0];
                $InvoiceDetails->invoice_number = $object['id'];
                $InvoiceDetails->amount = $object['amount_paid'];
                $InvoiceDetails->customer_id = $object['customer'];
                $InvoiceDetails->customer_email = $object['customer_email'];
                $InvoiceDetails->total_amount = $object['total'];
                $InvoiceDetails->save();
                break;
            case 'charge.failed':
                // Mail To User To Inform Charge Failed
                break;
            case 'charge.pending':
                // Mail To User To Inform Charge Pending
                break;
            case 'charge.expired':
                // Mail To User To Inform Charge Expired
                break;
            default:
                 break;
        }
        $WebhookEventsHistory = new WebhookEventsHistory();
        $WebhookEventsHistory->event=$payload['type'];
        $WebhookEventsHistory->user_id=$UserDetails['id'];
        $WebhookEventsHistory->save();

    }
}
