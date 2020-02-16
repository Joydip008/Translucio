<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\SubscriptionsHistory;
use App\Models\InvoiceDetails;
use App\Models\Plans;
use App\Models\FreeCredits;
use Auth;
use DB;

class TransactionHistoryController extends Controller
{
    public function TransactionHistory(){
        
        $user_id = Auth::user()->id;
        $user_email = Auth::user()->email;


        //$SubscriptionsHistory = SubscriptionsHistory::where('user_id',$user_id)->orderBy('created_at','DESC')->get();
        $SubscriptionsHistory = InvoiceDetails::where('customer_email',$user_email)->orderBy('created_at','DESC')->get();
        foreach($SubscriptionsHistory as $Subscriptions){
            $PlanDetails = Plans::where('stripe_plan_id',$Subscriptions['plan_id'])->first();
            $Subscriptions['planName'] = $PlanDetails['plan_name'];
            $Subscriptions['period'] = $PlanDetails['period_time'];
            $Subscriptions['price'] = $PlanDetails['monthly_cost'];
        }
        //dd($SubscriptionsHistory);

        
        /* Credit Calculation */

        $TotalCredit = FreeCredits::TotalCredit();

        return view('user.myProject.TranactionHistory',compact('TotalCredit','SubscriptionsHistory'));
    }
}
