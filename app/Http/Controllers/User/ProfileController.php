<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use App\User;
use App\Models\Country;
use DB;
use Illuminate\Support\Facades\Hash;
use Crypt;
use Illuminate\Support\Str;
use File;
use Stripe\Subscription;
use App\Models\SubscriptionModel;
use App\Models\SubscriptionsHistory;
use App\Models\SubscriptionLogs;
use App\Models\Plans;
use App\Models\UserCreditHistory;
use App\Models\FreeCredits;

class ProfileController extends Controller
{
    /* Profile Active As Per Email Link Send*/

    public function AccountActive($token=null){

        $checkUserDetails=User::where('email_verify_token',$token)->first();
        if(!empty($checkUserDetails)){
            $checkUserDetails->update([
                'status' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
            ]);
	        }
            /* Free Trial Plan Create */

                $s_key=env('STRIPE_SECRET');
            \Stripe\Stripe::setApiKey($s_key);
            $user=User::where('email_verify_token',$token)->first();
	
            $StripeCustomerCreateResponse=$user->createAsStripeCustomer();
            
            $stripe_customer_id=$StripeCustomerCreateResponse['id'];

            $user->update([
                'stripe_response' => json_encode($StripeCustomerCreateResponse),
            ]);

            

            
            /* Free plan Month */
           
            $freePlanMonthId = Plans::where('plan_name','Free')->where('period_time','M')->first();
           
            $SubscriptionDetailsResponse = \Stripe\Subscription::create([
                'customer' => $stripe_customer_id,
                'items' => [['plan' => $freePlanMonthId['stripe_plan_id']]],
                

            ]);
            //echo $SubscriptionDetailsResponse['current_period_start'];
//dd($SubscriptionDetailsResponse);
$current_period_start= date('Y-m-d',$SubscriptionDetailsResponse['current_period_start']);
$current_period_end= date('Y-m-d',$SubscriptionDetailsResponse['current_period_end']);
//exit;
            
            $subscription = new SubscriptionModel();
           
            $subscription->user_id = $user['id'];
            $subscription->name = 'Test';
            $subscription->stripe_id = $SubscriptionDetailsResponse['id'];
         $subscription->stripe_plan= $SubscriptionDetailsResponse['plan']['id'];
           $subscription->quantity = $SubscriptionDetailsResponse['quantity'];
        $subscription->stripe_status = 'Active';//$SubscriptionDetailsResponse['status'];
            $subscription->save();



           



            // $UserCreditHistory = new UserCreditHistory();
            // $UserCreditHistory->user_id = $user['id'];
            // $UserCreditHistory->credit = $freePlanMonthId['translation_credits'];
            // $UserCreditHistory->credit_status = $user['affiliated_status'] == 1 ? 1 : 0;
            // $UserCreditHistory->description = $user['affiliated_status'] == 0 ? 'Normal' : 'Affiliated';
            // $UserCreditHistory->start_date = date('Y-m-d',$SubscriptionDetailsResponse['current_period_start']);
            // $UserCreditHistory->end_date = date('Y-m-d',$SubscriptionDetailsResponse['current_period_end']);
            // $UserCreditHistory->status = 'Y';
            // $UserCreditHistory->save();

            // $subscriptionHistory = new SubscriptionsHistory();
            // $subscriptionHistory->user_id=$user['id'];
            // $subscriptionHistory->start_date=$current_period_start;
            // $subscriptionHistory->end_date=$current_period_end;
            // $subscriptionHistory->status='Y';
            // $subscriptionHistory->stripe_id=$SubscriptionDetailsResponse['id'];
            // $subscriptionHistory->stripe_plan=$SubscriptionDetailsResponse['plan']['id'];
            // $subscriptionHistory->save();
            

        //     $subscriptionLogs = new SubscriptionLogs();
           
        //     $subscriptionLogs->user_id = $user['id'];
        //  $subscriptionLogs->stripe_id = $SubscriptionDetailsResponse['id'];
        //  $subscriptionLogs->stripe_plan= $SubscriptionDetailsResponse['plan']['id'];
        //  $subscriptionLogs->current_period_start= $SubscriptionDetailsResponse['current_period_start'];
        //  $subscriptionLogs->current_period_end= $SubscriptionDetailsResponse['current_period_end'];
        //  $subscriptionLogs->status = 1;//$SubscriptionDetailsResponse['status'];

        //  $subscriptionLogs->save();

            return redirect('/successful-account-activation');
        //}
    }
    /* View My Profile or Profile Information form as per profile_status*/

    /* view profile details*/
    public function myprofile(){
        $profile_status=Auth::user()->profile_status;
        $countries = DB::table('countries')->get();

        $country_name=Country::where('id',Auth::user()->country)->first('name');

        $TotalCredit = FreeCredits::TotalCredit();
        
        if($profile_status==0){
            return view('user.myprofile')->with('countries',$countries)->with('TotalCredit',$TotalCredit);
        }
        elseif($profile_status==1){
            return view('user.information')->with('countries',$countries)->with('country_name',$country_name)->with('TotalCredit',$TotalCredit);
        }
    }

    /* For profile_status =0 and also for =1 Profile Update */

    /* Update Profile*/
    public function ProfileUpdate(Request $request){
        $input = $request->input();
        $file = $request->file();
    
        //dd($input);
        /* Validator Check Here */   
        $validator = Validator::make($request->all(), [
            'honorific' => 'required',
            'fname' => 'required',
            'lname' => 'required',
            //'company_name' => 'required',
            //'company_vat_number' => 'required',
            'address1' => 'required|max:255',
            'city' => 'required',
            'region' => 'required',
            'country' => 'required',
        ]);
        /*Validator False */
        if($validator->fails()){
            return back()->withInput($input)->withErrors($validator);
        }
        else{
           /* Update Details*/
            if(Auth::check()){
                $user_details = Auth::user();
                $user_details = User::where('email',$user_details['email'])->first();
                 
                /* Profile Image Upload*/
                 
                 if (!empty($file)) { 
                    /* set validation rules here */
                    $imageValidator = Validator::make($request->all(), [
                        'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',//|max:2048|dimensions:max_width=350,max_height=600,min_width=100,min_height=100,aspectRatio:2/1',
                    ]);
                    /* return with validation error message */
                    if ($imageValidator->fails()) {
                        //return back()->withInput($input)->withErrors($validator);
                        return \Redirect::back()->withInput($input)->with('message', 'Image Max Size 2MB, Must Be jpeg, png, jpg, gif');
                    }
                    else {
                        $image = $request->file('profile_image');
                        $profile_image = 'profile_image'.time().'.'.$image->getClientOriginalExtension();
                        
                        /* set path of the file */
                        $destinationPath = public_path('/assets/upload/temp');
                        /* put the file on the destination */
                        $image->move($destinationPath, $profile_image);

                        $ext=$image->getClientOriginalExtension();

                        $thumb_name = $this->makethumbnail('./assets/upload/temp/', './assets/upload/user/', $profile_image,$ext,100, 100 );
                        $image_path=public_path('/assets/upload/temp/'.$profile_image);
                        
                        if(File::exists($image_path)) {
                            File::delete($image_path);
                        }
                    }
                }
                else {
                    $profile_image = Auth::user()->profile_image;
                }

                /* Update Here Details*/
                $user_details->update([ 
                    'title' => $input['honorific'],
                    'name' => Str::ucfirst($input['fname']),
                    'last_name' => Str::ucfirst($input['lname']),
                    'company_name' => $input['company_name'],
                    'vat_number' => $input['company_vat_number'],
                    'address_line1' => $input['address1'],
                    'address_line2' => $input['address2'],
                    'city' => $input['city'],
                    'region' => $input['region'],
                    'country' => $input['country'],
                    'profile_image' => $profile_image,
                ]);
                if(Auth::user()->profile_status==0){
                    $user_details->update([
                        'profile_status' => 1,
                    ]);
                }


                /* User Profile Details Update On Stripe */
                $country_name = Country::where('id',$input['country'])->first();
                $s_key=env('STRIPE_SECRET');
                \Stripe\Stripe::setApiKey($s_key);
                \Stripe\Customer::update(
                Auth::user()->stripe_id,
                    [
                        'address'=>[
                            'line1'=> $input['address1'],
                            'city' => $input['city'],
                            'country' => $country_name['name'],
                            'line2'=> $input['address2'],
                            ],
                    ]
                );

                // \Stripe\Customer::createTaxId(
                //     Auth::user()->stripe_id,
                //     ['type' => 'eu_vat', 'value' => 'BE0454865365']
                //   );
               
                return redirect()->route('home');
            }
        }
    }

    /* Change Password Form */
    public function ChangePassword(){

        $TotalCredit = FreeCredits::TotalCredit();
        return view('user.change_password',compact('TotalCredit'));
    }
    /* Change Password Process*/
    public function ChangePasswordProcess(Request $request){
        $input = $request->input();
        /* Validator Check Here */   
        $validator = Validator::make($request->all(),[
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8',
        ]);
        /*Validator False */
        if($validator->fails()){
            return back()->withInput($input)->withErrors($validator);
        }
        else{
            /* Change Password Here */
            $user_details = User::where('id',Auth::user()->id)->first();
            
            /* If Given current password Match*/
            if(Hash::check($input['current_password'],$user_details['password'])){

                /* Check current password And old password is same or not */
                if(Hash::check($input['new_password'],$user_details['password'])){
                    /* Return Back With the current Password is same as old password */
                    return \Redirect::back()->withErrors(['msg'=> 'current Password is same as old password']);
                }
                else{
                    /* Change Password */
                    $user_details->update([
                        'password' => Hash::make($input['new_password']),
                    ]);
                    return \Redirect::back()->withErrors(['msg'=> 'successful change password']);
                }
            }
            else{
                /* Return Back Enter Current Password Wrong */
                return \Redirect::back()->withErrors(['msg'=> 'Enter Current Password Wrong']);
            }
        }
    }

    /* Image Resize Function*/

    public function makethumbnail($target_folder, $thumb_folder , $fileName,$file_ext, $thumb_width , $thumb_height) {
		//folder path setup
		$target_path = $target_folder;
		$thumb_path = $thumb_folder;
		
		$upload_image = $target_path.$fileName;
        
		$thumbnail = $thumb_path.$fileName;
		
		list($width,$height) = getimagesize($upload_image);
		
		$thumb_create = imagecreatetruecolor($thumb_width,$thumb_height);
		switch($file_ext){
			case 'jpg':
				$source = imagecreatefromjpeg($upload_image);
				break;
            case 'jpeg':
				$source = imagecreatefromjpeg($upload_image);
				break;

			case 'png':
				$source = imagecreatefrompng($upload_image);
				break;
			case 'gif':
				$source = imagecreatefromgif($upload_image);
				break;
			default:
				$source = imagecreatefromjpeg($upload_image);
		}

		imagecopyresized($thumb_create,$source,0,0,0,0,$thumb_width,$thumb_height,$width,$height);
		switch($file_ext){
			case 'jpg' || 'jpeg':
				imagejpeg($thumb_create,$thumbnail,100);
				break;
			case 'png':
				imagepng($thumb_create,$thumbnail,100);
				break;

			case 'gif':
				imagegif($thumb_create,$thumbnail,100);
				break;
			default:
				imagejpeg($thumb_create,$thumbnail,100);
		}
		return $fileName;
    }


    function read_docx(){
        $fileName="New_TestTable1.docx";
        $filename = public_path('/assets/upload/'.$fileName);
        $destFile=public_path('assets/upload/'.'cd.docx');
        $details_arr=[];
        /* Duplicate File Create */
        //copy($filename,$destFile);
       // echo "Copied";
       // exit;
        $striped_content = '';
        $content = '';

        $zip = zip_open($filename);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {
            // echo zip_entry_name($zip_entry);
            // echo '<br>';

        
            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            if (zip_entry_name($zip_entry) != "word/document.xml") continue;

            $content.= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            
            zip_entry_close($zip_entry);
        }// end while

        zip_close($zip);

        
        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
         $content = str_replace('</w:r></w:p>', "\n", $content);
      
       $data= $striped_content = strip_tags($content);


       // Creating the new document...


        // dd($striped_content);
        // exit;

        $j=0;
        $nesArray = array();
        for($i=1; $i<strlen($data); $i++){
            if($data[$i] === "\n"){
                /* Now insert into Db */
                
                   
                    if(!empty($nesArray)){
                        $ProjectData = new ProjectData();
                        $ProjectData->project_id = '7777777';
                        $ProjectData->paragraph_id = uniqid();
                        $ProjectData->data = implode('', $nesArray);
                        $ProjectData->save();
                        $details_arr[implode('', $nesArray)]=implode('', $nesArray);

                    }
                
                $j=0;
                $nesArray = array();
            }
            else{
    
                if($data[$i]!= "\n"){
                    $nesArray[$j] = $data[$i];
                    $j++;
                }
            }
        }
        //dd($details_arr);
        foreach($details_arr as $key => $val)
        {
            
            $details_arr[$key]=$this->testApi($val);
            //exit;
        }


$zip = new \PhpOffice\PhpWord\Shared\ZipArchive;

//This is the main document in a .docx file.
$fileToModify = 'word/document.xml';

$file = public_path('/assets/upload/New_TestTable1.docx');
$temp_file = public_path('/assets/upload/cd.docx');
copy($file,$temp_file);

if ($zip->open($temp_file) === TRUE) {
    //Read contents into memory
    $oldContents = $zip->getFromName($fileToModify);
    $newContents=$oldContents;
    //echo $oldContents;

    //Modify contents:

        foreach($details_arr as $key => $val)
 {
     echo $key.'----------------->'.$val;
     echo '<br><br>';
    //  if(trim($key)=='The standard header will only be seen on the homepage, while subsequent pages will only see a header with the page title and an “X” on the left.')
    //  {
    //      echo'matchcccccccccccccccc';
    //  }
   // $newContents = str_replace('The standard header will only be seen on the homepage, while subsequent pages will only see a header with the page title and an “X” on the left', 'Der Standard-Header wird nur auf der Homepage angezeigt, während die Folgeseiten nur einen Header mit dem Seitentitel und einem "_ auf der linken Seite._ sehen', $oldContents);
    $newContents = str_replace(trim($key),$val, $newContents);

}
    //$newContents = str_replace('{name}', 'Santosh Achari', $newContents);

    //Delete the old...
    $zip->deleteName($fileToModify);
    //Write the new...
    $zip->addFromString($fileToModify, $newContents);
    //And write back to the filesystem.
    $return =$zip->close();
    If ($return==TRUE){
        echo "Success!";
    }
} else {
    echo 'failed';
}



      }
}







