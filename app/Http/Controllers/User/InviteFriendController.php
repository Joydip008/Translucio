<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use App\Models\SubscriptionsHistory;
use App\Models\Plans;
use App\Models\AffiliatedRegister;
use Auth;
use DB;
//use Twilio\Rest\Client;
use Mail;
use App\Mail\ShareFriend;
use GuzzleHttp\Client;
use Share;

use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;

use App\Models\FreeCredits;

class InviteFriendController extends Controller
{
    public function InviteFriend(){

        $TotalCredit = FreeCredits::TotalCredit();

        return view('user.myProject.InviteFriend',compact('TotalCredit'));

    }

    /* Invite A Friend By Whatsapp */

    public function InviteFriendWhatsApp(Request $request)
    {
        $input = $request->all();
        
        $sid = env('WHATSAPP_SID');
        $token = env('WHATSAPP_TOKEN');
        $WhatsAppNumber = '+14155238886';//env('PNT_NUMBER');9831494410
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
                    ->create(
                        "whatsapp:+91" . '7278257481', // to
                        array(
                            "from" => "whatsapp:" . $WhatsAppNumber,
                            "body" => "Hello Friend Click On This Link and translate Your Docs webSite " . $input['Link'],
                            //public/assets/images/logo/login.png
                            //"mediaUrl" => "http://homepages.cae.wisc.edu/~ece533/images/airplane.png"
                            "mediaUrl" => "https://tr1.cbsistatic.com/hub/i/r/2017/12/22/2a18828e-59c3-49a4-98aa-4eed6e41dc7c/resize/770x/eb95fb422ba4f65da42e9ab18f322f15/hero-translate-google-doc.jpg",
                        )
                    );
        return;
    }


    public function MailSend(Request $request){
    
      $input = $request->input(); 
      $userId = Auth::user()->id;
      $Code = base64_encode($userId);
      $data = [
        'email' => $input['email'], 
        'message' => 'Thank You!',
        'Code' => $Code,
      ]; 
      Mail::to($data['email'])->send(new ShareFriend($data)); 
    }
}
