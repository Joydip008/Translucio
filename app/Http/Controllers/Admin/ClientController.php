<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\ProjectData;
use App\Models\Projects;
use App\Models\Subscription; 
use App\Models\Plans;
use App\Models\SubscriptionsHistory;
use App\Models\InvoiceDetails;
use AUth;
use DB;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
$per_page = config('constants.PER_PAGE'); // Pagination Per Page
class ClientController extends Controller
{
    //
    /* List of All the Clients */
    public function index(Request $request){

        $userDetails=User::orderBy('updated_at','desc')->where('role_id',2)->paginate(config('constants.PER_PAGE'));

        /* */
        foreach($userDetails as $user){
            $CurrentSubscriptionsDetails = SubscriptionsHistory::where('user_id',$user['id'])->where('status','Y')->first();

            $PlansDetails = Plans::where('stripe_plan_id',$CurrentSubscriptionsDetails['stripe_plan'])->first();

            /* Credit Calculation */
            
           
            $creditUsed=$this->creditUsed($user['id'],$CurrentSubscriptionsDetails);
            $TotalCredit = $PlansDetails['translation_credits']-$creditUsed;


            $user['PlanName'] = $PlansDetails['plan_name'];
            $user['PlanPeriod'] = $PlansDetails['period_time'];
            $user['CreditIncludes'] = $PlansDetails['translation_credits'];
            
            $user['AvailableCredits'] = $TotalCredit;

        }
        


        $results = $userDetails;
        //This would contain all data to be sent to the view
        $data = array();

        //Get current page form url e.g. &page=6
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        //Create a new Laravel collection from the array data
        $collection = new Collection($results);

        //Define how many items we want to be visible in each page
        $per_page = 5;

        //Slice the collection to get the items to display in current page
        $currentPageResults = $collection->slice(($currentPage-1) * $per_page, $per_page)->all();

        //Create our paginator and add it to the data array
        $data['results'] = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);

        //Set base url for pagination links to follow e.g custom/url?page=6
        $data['results']->setPath($request->url());
        $left_menu='client';

        return view('admin.clients.client_list',compact('userDetails','results','left_menu'));
    }

    public function ClientDetails($id){ 

        $left_menu='client'; 
        
        /* Client Details */
        $clientDetails = User::where('id',$id)->first();

        /* Credit Calculations */
        $CurrentSubscriptionsDetails = SubscriptionsHistory::where('user_id',$id)->where('status','Y')->first();

        $PlansDetails = Plans::where('stripe_plan_id',$CurrentSubscriptionsDetails['stripe_plan'])->first();

        $creditUsed=$this->creditUsed($id,$CurrentSubscriptionsDetails);
        $TotalCredit = $PlansDetails['translation_credits']-$creditUsed;
        $CreditIncludes = $PlansDetails['translation_credits'];

        /* Credit Calculation */

        /* Project Details and number of Projects */

        $ProjectDetails = Projects::where('user_id',$id)->get();
        $TotalNumberProjects = sizeof($ProjectDetails);

        $TotalNumberParagraph = 0;

        /* ParagraphDetails and Number of para */
        foreach($ProjectDetails as $project){
            $ParagraphDetails = ProjectData::where('p_id',$project['id'])->get();
            $TotalNumberParagraph += sizeof($ParagraphDetails);
        }

        /* Current Plans Details Section*/

        $CurrentPlans = [];

        $CurrentSubscriptionsDetails = Subscription::where('user_id',$id)->orderBy('created_at','DESC')->first(); // Current Plans Details Of that User 
        $CurrentPlansDetails = Plans::where('stripe_plan_id',$CurrentSubscriptionsDetails['stripe_plan'])->first();


        $CurrentPlans['plan_name'] = $CurrentPlansDetails['plan_name'];
        $CurrentPlans['period_time'] = $CurrentPlansDetails['period_time'];
        $CurrentPlans['created_at'] = $CurrentPlansDetails['created_at']; // Created At is that start date of the plans 
        $CurrentPlans['cost'] = $CurrentPlansDetails['monthly_cost'];

        /* History Plans Details Section */

        $PlansDetailsHistory = [];
        $AllPlansDetails = [];

        //$AllSubscriptionsDetails = Subscription::where('user_id',$id)->orderBy('created_at','DESC')->get();  // Multiple Subscription 

        $AllSubscriptionsDetails = InvoiceDetails::where('customer_email',$clientDetails['email'])->orderBy('created_at','DESC')->get();  // Multiple Subscription 
      
        
        foreach($AllSubscriptionsDetails as $sub){

            $AllPlansDetails = Plans::where('stripe_plan_id',$sub['stripe_plan'])->first();

            $sub['plan_name'] = $AllPlansDetails['plan_name'];
            $sub['cost'] = $AllPlansDetails['monthly_cost'];
           

        }
        // dd($AllSubscriptionsDetails);
        $LatsPaymentDetails = InvoiceDetails::where('customer_email',$clientDetails['email'])->max('created_at');

        $LastPaymentDate = $LatsPaymentDetails['created_at'];
        //dd($LastPaymentDate);
        //$LastPaymentDate = '2020-01-24 12:38:20';
        if($CurrentPlansDetails['period_time'] == 'M'){
            $daystosum = '30'; 
            $NextPaymentDate = date('d-m-Y', strtotime($LastPaymentDate.' + '.$daystosum.' days'));
        }
        elseif($CurrentPlansDetails['period_time'] == 'Y'){
            $daystosum = '365';
            $NextPaymentDate = date('d-m-Y', strtotime($LastPaymentDate.' + '.$daystosum.' days'));
        }
       

        /* Order History */
        
        return view('admin.clients.client_details',compact('clientDetails','left_menu','TotalNumberProjects','TotalNumberParagraph','CurrentPlans','AllSubscriptionsDetails','CreditIncludes','TotalCredit','LastPaymentDate','NextPaymentDate'));
    }


    

    /* Download Invoice */
    public function DownloadInvoice($id=null){
        //  dd("OKAY");

        \Stripe\Stripe::setApiKey('sk_test_UChReUOnF08uCbJOzsNE04SU00TFIzBUCa');

        $invoice = \Stripe\Invoice::retrieve(
        'in_1FmvwdIb3VBZILqwrJzv4j4k'
        );
        // dd($invoice);
        return back();       
    }


    /* Credit Calculation Function */
    function creditUsed($user_id,$SubscriptionDetails)
    {
        $res=  DB::table('text_count')->select( DB::raw('SUM(credit_used) as credit_used'))->where('user_id',$user_id)->whereBetween('translate_date', [$SubscriptionDetails['start_date'], $SubscriptionDetails['end_date']])->get();
        return $res[0]->credit_used;
    }
}
