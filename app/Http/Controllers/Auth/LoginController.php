<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Authenticatable;

use Validator;
use Auth;
use App\Models\User;
use Carbon\Carbon;

use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

protected function authenticated(Request $request, $user)
{
   
if ($user->is_admin()) {
   
    return redirect()->route('admin_dashboard');
}

 return redirect()->route('home');
}

public function login(Request $request)
    {
        $input = $request->input();
   
        $user_details = [];
        /* set validation rules here */
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        /* check validation here */
        if ($validator->fails()) {
            return back()->withInput($input)->withError($validator);
        } 
        else 
        {
            /* login by email */
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) 
            {
                $user_details = User::where('email',$request['email'])->first();//Auth::user();
            }
        }
        if(!empty($user_details))
        {
            
            /* Check It Is User Then Go To Home*/
            if($user_details['status']==1 && $user_details['role_id']==2)
            {
               $user_details->update([
                'last_login_at' => date('Y-m-d H:i:s'),
                'last_login_ip' => $request->getClientIp(),
               ]);
                return redirect()->route('my_project'); 
            }
            /* Check Here Below For Admin Login*/
            elseif($user_details['status']==1 && $user_details['role_id']==1){
               $user_details->update([
                'last_login_at' => date('Y-m-d H:i:s'),
                'last_login_ip' => $request->getClientIp(),
               ]);
               return redirect()->route('admin_dashboard');
            }
            else{
                Auth::logout();
                return \Redirect::back()->withInput($input)->withErrors(['message'=> 'User not active. Please check your email for activation!']);
            }
        }
        else{
            Auth::logout();
            return \Redirect::back()->withInput($input)->withErrors(['message'=> 'These credentials do not match our records.']);
        }
    }

    /* Login With Google Here */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
   
    public function handleGoogleCallback()
    {
        try {
  
            $user = Socialite::driver('google')->user();
   
            $finduser = User::where('google_id', $user->id)->first();
   
            if($finduser){
   
                Auth::login($finduser);
  
                return redirect('/home');
   
            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'role_id' =>2,
                    'role' => 'User',
                    'google_id'=> $user->id
                ]);
  
                Auth::login($newUser);
   
                return view('home');
            }
  
        } catch (Exception $e) {
            return redirect('auth/google');
        }
    }
}
