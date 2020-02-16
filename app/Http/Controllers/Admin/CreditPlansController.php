<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Plans;
use Validator;

class CreditPlansController extends Controller
{
    //
    // Start Section For Add New Plans

    /* Add Plans Form */

    public function index()
    {
        $s_key=env('STRIPE_SECRET');
        \Stripe\Stripe::setApiKey($s_key);
        $product = \Stripe\Product::retrieve('prod_FvfTtSSrHChder');
        $product->delete();
    }
    public function addPlansForm(){
        $left_menu='credit_plans_list';
        return view('admin.creditPlans.add_plan','left_menu');
    }

    public function addPlan(Request $request){
        $input = $request->input();
        //dd($input);
        /* Validator Rules */
        $validator = Validator::make($request->all(), [
            'new_plan_name' => 'required',
            'monthly_cost' => 'required',
        ]);

        /* If Validator Fails Return */

        if($validator->fails()){
            return back()->withInput($input)->withErrors($validator);
        }
        else{
            /* Add New Plans Here */

            /* Check If Plans Names are stored or not */

            $plansDetails = Plans::where('plan_name',$input['new_plan_name'])->first();
            $check= Plans::get();
            $max_period_id = $check->max('period_id');
            if($max_period_id==0 || $max_period_id==null){
                $max_period_id=0;
            }
            if(!empty($plansDetails) || $plansDetails=null){

                /* The Name is in List then return with errors that name already exist */
                return redirect()->back()->with('message', 'The Plan Name Already Exist Please Try Again With Another Name!');
            }
            else{
                /* Add here */

                /* Hit To Stripe ANd Response Back */
                $s_key=env('STRIPE_SECRET');
                \Stripe\Stripe::setApiKey($s_key);

                /* New Plans For Monthly Wise */
                $data = \Stripe\Plan::create(array(
                    "amount" => $input['monthly_cost'] * 100,
                    "interval" => "day",
                    "product" => array(
                      "name" => $input['new_plan_name'],
                    ),
                    "currency" => "eur",
                ));

                /* New Plans For Yearly Wise */
                $data1 = \Stripe\Plan::create(array(
                    "amount" => $input['monthly_cost'] * 10 *100,
                    "interval" => "day",
                    "product" => array(
                      "name" => $input['new_plan_name'],
                    ),
                    "currency" => "eur",
                ));
                if(!empty($data) && !empty($data1)){
                    /* Add Data In Local */
                    /* New Plans For Monthly Wise */
                    $plans = new Plans();
                    $plans->stripe_response = json_encode($data);
                    $plans->stripe_plan_id = $data['id'];
                    $plans->product_id = $data['product'];
                    $plans->plan_name = $input['new_plan_name'];
                    $plans->period_id = $max_period_id+1;
                    $plans->period_time = 'M';
                    $plans->status = 1;//$data['status'];
                    $plans->monthly_cost = $input['monthly_cost'];
                    $plans->max_languages = $input['max_languages'];
                    $plans->included_pageviews = $input['included_pageviews'];
                    $plans->extra_cost_pageviews = $input['extra_cost_pageviews'];
                    $plans->translation_credits = $input['translation_credits'];
                    $plans->additional_characters = $input['additional_characters'];
                    $plans->save();

                    /* New Plans For Yearly Wise */
                    $plans = new Plans();
                    $plans->stripe_response = json_encode($data);
                    $plans->stripe_plan_id = $data1['id'];
                    $plans->product_id = $data1['product']; 
                    $plans->plan_name = $input['new_plan_name'];
                    $plans->period_id = $max_period_id+1;
                    $plans->period_time = 'Y';
                    $plans->status = 1;//$data['status'];
                    //$plans->description = '';
                    $plans->monthly_cost = $input['monthly_cost'] * 10;
                    $plans->max_languages = $input['max_languages'];
                    $plans->included_pageviews = $input['included_pageviews'];
                    $plans->extra_cost_pageviews = $input['extra_cost_pageviews'];
                    $plans->translation_credits = $input['translation_credits'];
                    $plans->additional_characters = $input['additional_characters'];
                    $plans->save();
                    return redirect()->back()->with('message', 'Added Successfully!');
                }
                else{
                    return redirect()->back()->with('message', 'Something is error ! Please Try Agin Later!');
                }
            }
        }
    }

    // End Section For Add New Plans






    // Start Update Plans Section and List Of Plans */
    public function CreditPlansList(){
        $plansDetailsList = Plans::where('period_time','M')->orderBy('monthly_cost','ASC')->get();
        $updatePlansDetails = '';
        $left_menu='credit_plans_list';
        return view('admin.creditPlans.credit_plans',compact('plansDetailsList','updatePlansDetails','left_menu'));
    }

    /* Update Plans */
    public function updatePlans($id=null){
        $plansDetailsList = Plans::where('period_time','M')->get();
        $updatePlansDetails = Plans::where('id',$id)->first();
        $left_menu='credit_plans_list';
        return view('admin.creditPlans.credit_plans',compact('plansDetailsList','updatePlansDetails','left_menu'));
    }

    /* Save Plans */
    public function savePlan(Request $request,$id){
        $input = $request->input();
        //dd($input);
        /* validation rules here */
        $validator = Validator::make($request->all(), [
           // 'monthly_cost' => 'required',
            'max_languages' => 'required',
            'included_pageviews' => 'required',
            'translation_credits' => 'required',
            'additional_characters' => 'required',
            'extra_cost_pageviews' => 'required',
        ]);
        /* If Validation fails */
        if($validator->fails()){
            return back()->withInput($input)->withErrors($validator);
        }
        else{
            /* Update Here */
            $planDetails_month = Plans::where('period_id',$id)->where('period_time','M')->first();
            $planDetails_year = Plans::where('period_id',$id)->where('period_time','Y')->first();
            if(!empty($planDetails_month) && !empty($planDetails_year)){
                $planDetails_month->update([
                    //'monthly_cost' => $input['monthly_cost'],
                    'max_languages' => $input['max_languages'],
                    'included_pageviews' => $input['included_pageviews'],
                    'translation_credits' => $input['translation_credits'],
                    'additional_characters' => $input['additional_characters'],
                    'extra_cost_pageviews' => $input['extra_cost_pageviews'],
                ]);
                $planDetails_year->update([
                    //'monthly_cost' => $input['monthly_cost']*10,
                    'max_languages' => $input['max_languages'],
                    'included_pageviews' => $input['included_pageviews'],
                    'translation_credits' => $input['translation_credits'],
                    'additional_characters' => $input['additional_characters'],
                    'extra_cost_pageviews' => $input['extra_cost_pageviews'],
                ]);
               
                return redirect()->route('Credit_Plans_List')->with('message', 'Updated Successfully!');
            }
        }
    }
}
