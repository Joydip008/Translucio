<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ExtraChargeHistory;
use App\Models\SubscriptionsHistory;
use App\Models\Plans;
use DB;
class Plans extends Model
{
    //
    protected $table = 'plans'; 
    protected $fillable = [
      'stripe_response',
      'stripe_plan_id',
      'product_id',
      'plan_name',
      'period_id',
      'period_time',
      'status',  // 1= Active , 2= Inactive
      'monthly_cost',
      'max_languages',
      'included_pageviews',
      'extra_cost_pageviews',
      'included_characters',
      'additional_characters',  
    ];

    public static function ExtraChargeCalculation($user_id){

        $CurrentSubscriptionDetails = SubscriptionsHistory::where('user_id',$user_id)->where('status','Y')->first();
        $PlanDetails = Plans::where('stripe_plan_id',$CurrentSubscriptionDetails['stripe_plan'])->first();

        $TotalCreditAvailable = $PlanDetails['translation_credits'];  // 4000

  
        /* Total Credit used */
    
        $PlanDetails = Plans::where('stripe_plan_id',$CurrentSubscriptionDetails['stripe_plan'])->first();

        $res=  DB::table('text_count')->select( DB::raw('SUM(credit_used) as credit_used'))->where('user_id',$user_id)->whereBetween('translate_date', [$CurrentSubscriptionDetails['start_date'], $CurrentSubscriptionDetails['end_date']])->get();
    
        $TotalCredit = $res[0]->credit_used; // 3900 //6100 = 9000  //Total Credit Used

        /* Already Charged Characters */

        $AlreadyCharged=  DB::table('extra_charge_history')->select( DB::raw('SUM(extra_credit_used) as extra_characters_charged'))->where('subscription_history_id',$CurrentSubscriptionDetails['id'])->get();
        if(!empty($AlreadyCharged)){
          $TotalAlreadyCharged = $AlreadyCharged[0]->extra_characters_charged;  //0000 //2000  // 5000
        }
        else{
          $TotalAlreadyCharged = 0;
        }
        
        // HAND = 100

        
        if($TotalCreditAvailable<$TotalCredit){
          $CurrentExtraCharge = ($TotalCredit - $TotalCreditAvailable) - $TotalAlreadyCharged;


          $UserDetails = User::where('id',$user_id)->first();

          $UserStripeId = $UserDetails['stripe_id'];

          $TotalExtraCharge = ($CurrentExtraCharge / 10000) * $PlanDetails['additional_characters'];

          /* Stripe Charge Here */
          $responsePayment = \Stripe\Stripe::setApiKey('sk_test_UChReUOnF08uCbJOzsNE04SU00TFIzBUCa'); 


          \Stripe\InvoiceItem::create([ 
            'amount' => 100 * 100, 
            'currency' => 'eur', 
            'customer' => $UserStripeId,
            'description' => 'One-time setup fee', 
          ]);
          // $charge = \Stripe\Charge::create([
          //   'amount' => 100,   // Static value , Original Value => $TotalExtraCharge
          //   'currency' => 'eur',
          //   'customer' => $UserStripeId,  
          // ]);

         
          $ExtraChargeHistory = new ExtraChargeHistory();
          $ExtraChargeHistory->subscription_history_id=$CurrentSubscriptionDetails['id'];
          $ExtraChargeHistory->extra_credit_used = $CurrentExtraCharge;
          $ExtraChargeHistory->status = 1;
          $ExtraChargeHistory->save();
        }
       
        return ;

    }

}
