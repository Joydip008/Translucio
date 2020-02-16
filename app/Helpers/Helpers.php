<?php

use App\Models\SubscriptionsHistory;
use App\Models\Plans;

 function CreditCalculation(){
    $SubscriptionDetails = SubscriptionsHistory::where('user_id',Auth::user()->id)->where('status','Y')->first();

    $PlanDetails = Plans::where('stripe_plan_id',$SubscriptionDetails['stripe_plan'])->first();

    $res=  DB::table('text_count')->select( DB::raw('SUM(credit_used) as credit_used'))->where('user_id',Auth::user()->id)->whereBetween('translate_date', [$SubscriptionDetails['start_date'], $SubscriptionDetails['end_date']])->get();

    $TotalCredit = $PlanDetails['translation_credits']-$res[0]->credit_used;

    return $TotalCredit;

}


?>