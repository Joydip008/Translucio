<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\DowngradeSubscription;
use App\User;
class SubscriptionChange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily subscription Status Check';

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
        //
       
       $downgradeSubscription=new DowngradeSubscription;
       $all=$downgradeSubscription->where('status',0)->whereDate('action_date', '=', date('Y-m-d'))->get();
       //print_r($all);
       foreach($all as $each)
       {
           $user=User::find($each['user_id']);
           //print_r($user);
           $user->subscription('Test')->noProrate()->swapAndInvoice($each['stripe_plan']);
           DB::table('downgrade_subscriptions')
            ->where('id', $each['id'])
            ->update(['status' => 1]);
       }
           // DB::table('recent_users')->delete();
        
    }
}
