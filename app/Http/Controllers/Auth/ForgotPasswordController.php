<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

use Validator;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Mail;
use Session;

use Str;
use DB;
use Carbon;
class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendMailLink(Request $request){
        $input = $request->input();
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);

        /* check validation and return with error message */
        if ($validator->fails()) {
            return back()->withInput($input)->withErrors($validator);
        }
        else{
            $userDetails= User::where('email',$request['email'])->first();

            $token=Str::random(60);
            if(!empty($userDetails)){
                $data = [
                    'token' => $token,
                    'email' => $request['email'],
                ];
                $CurrentTime= Carbon\Carbon::now();
                $SendMailTime=$userDetails['forgot_password_time'];
                if(!empty($SendMailTime)){
                    /* Mail Send By Check time */
                    $diff_in_minutes = $CurrentTime->diffInMinutes($SendMailTime);
                    if($diff_in_minutes>=60){
                        $userDetails->update([
                            'forgot_password_token' => $token,
                            'forgot_password_flag' => 1,
                            'forgot_password_time' => Carbon\Carbon::now(),
                        ]);
                        Mail::send('auth.email.forgotpassword_email', ['data' => $data], function ($message) use ($data) {
                            $message->to($data['email'])->subject('RESET PASSWORD.');
                        });
                        
                        return view('auth.LinkSendSuccess');
                    }
                    else{
                        /* Already Send Mail */
                       
                        return back()->with('message' , 'Mail Already Send ! Please Try Again after 60min');
                    }
                }
                else{
                    /* send mail to User */
                    $userDetails->update([ 
                        'forgot_password_token' => $token,
                        'forgot_password_flag' => 1,
                        'forgot_password_time' => Carbon\Carbon::now(),
                    ]);
                    Mail::send('auth.email.forgotpassword_email', ['data' => $data], function ($message) use ($data) {
                        $message->to($data['email'])->subject('RESET PASSWORD.');
                    });
                    
                    return redirect()->back()->with('success', 'We have e-mailed your password reset link!');
                }
            }
            else{
                return back()->with('message' , 'These credentials do not match our records.');
            }
        }
    }

    public function resetPassword(Request $request){
        $input = $request->input();
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
        ]);

        /* check validation and return with error message */
        if ($validator->fails()) {
            return back()->withInput($input)->withErrors($validator);
        }
        else{
            $userDetails= User::where('email',$input['email'])->first();
            if(!empty($userDetails)){
                $password = bcrypt($input['password']);
                $userDetails->update([ 
                    'password' => $password,
                    'forgot_password_flag' => 0,
                ]);
                //$request->session()->flush();
                //return redirect('/')->withSuccess("Password has been changed successfully");
                return redirect('/')->with('success', 'Password has been changed successfully');
            }
            else{
                return redirect('/')->withErrors("Error");
            }
        }
    }

    public function CheckLink($token=null){

        $checkUserDetails=User::where('forgot_password_token',$token)->first();

        if(!empty($checkUserDetails)){
            $forgot_password_time=$checkUserDetails['forgot_password_time'];
            $CurrentTime = Carbon\Carbon::now();
            $diff_in_minutes = $CurrentTime->diffInMinutes($forgot_password_time);
            $diff_in_minutes++;
            if(!empty($diff_in_minutes)){
                if($diff_in_minutes>60 || $diff_in_minutes==0){
                    return redirect('/')->withErrors("Link Expired");
                }
                else{
                    if($checkUserDetails['forgot_password_flag']==1){
                        return view('user.password_reset')->with('email',$checkUserDetails['email']);
                    }
                    else{
                        //return redirect('/');
                        $checkUserDetails->update([ 
                            'forgot_password_flag' => 0,
                        ]);
                        return view('user.LinkExpired');
                    }
                }
            }
            else{
                //return redirect('/');
                $checkUserDetails->update([ 
                    'forgot_password_flag' => 0,
                ]);
                return view('user.View404');
            }
        }
        else{
            //return redirect('/');
            $checkUserDetails->update([ 
                'forgot_password_flag' => 0,
            ]);
            return view('user.ViewWrongUser');
        }
    }
}
