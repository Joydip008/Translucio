<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


use App\Models\SubscriptionsHistory;
use App\Models\Plans;
use DB;
use App\Models\User;
use App\Models\Payment;
use App\Models\ExtraChargeHistory;


class ExtraCharge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'charge:month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extra charge at the end of month';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ToDay = date("Y-m-d");

        // dd($ToDay);

        $UserDetails = SubscriptionsHistory::where('end_date',$ToDay)->where('status','Y')->get();
       
        foreach($UserDetails as $User){

            $user_id = $User['user_id'];
        
            $CurrentSubscriptionDetails = SubscriptionsHistory::where('user_id',$user_id)->where('status','Y')->where('end_date',$ToDay)->first();
            $PlanDetails = Plans::where('stripe_plan_id',$CurrentSubscriptionDetails['stripe_plan'])->first();

            $TotalCreditAvailable = $PlanDetails['translation_credits'];  // 4000

            $ResultFreeCredit =  DB::table('user_free_credits')->select( DB::raw('SUM(credit) as FreeCredit'))->where('s_id',$user_id)->orWhere('r_id',$user_id)->get();
            $TotalCreditAvailable = $TotalCreditAvailable + $FreeCredit[0]->FreeCredit;
            

    
            /* Total Credit used */
        
            $PlanDetails = Plans::where('stripe_plan_id',$CurrentSubscriptionDetails['stripe_plan'])->first();

            //$res=  DB::table('text_count')->select( DB::raw('SUM(credit_used) as credit_used'))->where('user_id',$user_id)->whereBetween('translate_date', [$CurrentSubscriptionDetails['start_date'], $CurrentSubscriptionDetails['end_date']])->get();
        
            $res=  DB::table('text_count')->select( DB::raw('SUM(credit_used) as credit_used'))->where('user_id',$user_id)->get();
        
            $TotalCredit = $res[0]->credit_used; // 3900 //6100 = 9000  //Total Credit Used

            /* Already Charged Characters */

            // $AlreadyCharged=  DB::table('extra_charge_history')->select( DB::raw('SUM(extra_credit_used) as extra_characters_charged'))->where('subscription_history_id',$CurrentSubscriptionDetails['id'])->get();
            // if(!empty($AlreadyCharged)){
            // $TotalAlreadyCharged = $AlreadyCharged[0]->extra_characters_charged;  //0000 //2000  // 5000
            // }
            // else{
            // $TotalAlreadyCharged = 0;
            // }
            
            // HAND = 100

            if($TotalCreditAvailable<$TotalCredit){
            $CurrentExtraCharge = ($TotalCredit - $TotalCreditAvailable);

            //10000 // 5000 // 5000

            $UserDetails = User::where('id',$user_id)->first();

            $UserStripeId = $UserDetails['stripe_id'];

            // 10E/10K  ..... extra 5K char = (10/10000) * 5000

            $TotalExtraCharge = ceil(($PlanDetails['additional_characters'] / 10000) * $CurrentExtraCharge);

            //$TotalExtraCharge = ceil(($CurrentExtraCharge / 10000) * $PlanDetails['additional_characters']);

            /* Stripe Charge Here */
            $responsePayment = \Stripe\Stripe::setApiKey('sk_test_UChReUOnF08uCbJOzsNE04SU00TFIzBUCa'); 


            //10.50 = 10D 50CENT 
            $ResponseDetails = \Stripe\InvoiceItem::create([ 
                'amount' => $TotalExtraCharge * 100, 
                'currency' => 'eur', 
                'customer' => $UserStripeId,
                'description' => 'Extra Translated Charge', 
            ]);
            // $charge = \Stripe\Charge::create([
            //   'amount' => 100,   // Static value , Original Value => $TotalExtraCharge
            //   'currency' => 'eur',
            //   'customer' => $UserStripeId,  
            // ]);

            if(!empty($ResponseDetails['id'])){
                
                // $PaymentDetails = new Payment();
                // $PaymentDetails->user_id = $user_id;
                // $PaymentDetails->invoice_id = $ResponseDetails['id'];
                // $PaymentDetails->amount = $TotalExtraCharge;
                // $PaymentDetails->status = 'Done';
                // $PaymentDetails->save();

                $ExtraChargeHistory = new ExtraChargeHistory();
                $ExtraChargeHistory->subscription_history_id=$CurrentSubscriptionDetails['id'];
                $ExtraChargeHistory->extra_credit_used = $CurrentExtraCharge;
                $ExtraChargeHistory->status = 1;
                $ExtraChargeHistory->save();

                 DB::table('text_count')->where('user_id',$user_id)
                    ->chunkById(100, function ($text_count) {
                    foreach ($text_count as $text) {
                        DB::table('text_count')
                            ->where('id', $text->id)
                            ->update(['bill_status' => 'Y' , 'payment_status' => 'N']);
                    }
                });

            }

            }
        }
    }
}
