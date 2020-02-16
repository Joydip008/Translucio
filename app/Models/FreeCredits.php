<?php

namespace App\Models;
use Auth;
use DB;

use Illuminate\Database\Eloquent\Model;

class FreeCredits extends Model
{
    protected $table = 'free_credits';
    protected $fillable = [
        'credit_amount',
        'credit_purpose',
        'credit_status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    /* Total Credit Calculations */
    public static function TotalCredit(){
        $SubscriptionDetails = SubscriptionsHistory::where('user_id',Auth::user()->id)->where('status','Y')->first();
         
        //$PlanDetails = Plans::where('stripe_plan_id',$SubscriptionDetails['stripe_plan'])->first();
        // $NumberOfLanguage = $PlanDetails['max_languages'];
        $creditUsed=static::creditUsed(Auth::user()->id);
        $FreeCredit=static::FreeCredit(Auth::user()->id);
       
        $TotalCredit = $SubscriptionDetails['plan_credit']-$creditUsed+$FreeCredit;
        return $TotalCredit;
    }


    /* Credit Calculation Function */
    public static function creditUsed($user_id)
    {
        //$res=  DB::table('text_count')->select( DB::raw('SUM(credit_used) as credit_used'))->where('user_id',$user_id)->whereBetween('translate_date', [$SubscriptionDetails['start_date'], $SubscriptionDetails['end_date']])->get();
        $res=  DB::table('text_count')->select( DB::raw('SUM(credit_used) as credit_used'))->where('user_id',$user_id)->get();
        return $res[0]->credit_used;
    }
    public static function FreeCredit($user_id)
    {
        
        //$res=  DB::table('text_count')->select( DB::raw('SUM(credit_used) as credit_used'))->where('user_id',$user_id)->whereBetween('translate_date', [$SubscriptionDetails['start_date'], $SubscriptionDetails['end_date']])->get();
        $res=  DB::table('user_free_credits')->select( DB::raw('SUM(credit) as FreeCredit'))->where('s_id',$user_id)->orWhere('r_id',$user_id)->get();
        return $res[0]->FreeCredit;
    }
}
