<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;


use Mail;
use App\Mail\RegisterEmail;
use App\Models\UserFreeCredits;
use App\Models\FreeCredits;
use Illuminate\Support\Str;

class RegisterController extends Controller 
{
  
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
   // protected $redirectTo = '/registration_success';
   protected $redirectTo = '/registration_success';
 
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data,$code='')
    {
        
        /* Mail Send TO User */

        // $data = [
        //     'email' => $data['email'],
        //     'name' => $data['name'],
        // ]; 
        $token = Str::random(60);
        $data1 = [
            'message' => 'Thank You!', 
            'name' => ucfirst($data['name']),
            'token' => $token, 
        ]; 


        //Mail::to($data['email'])->send(new RegisterEmail($data1));

        /* $code is the sender id */

        $User = new User();
        $User->title = $data['title'];
        $User->name = Str::ucfirst($data['name']);
        $User->last_name = Str::ucfirst($data['last_name']);
        $User->email = $data['email'];
        $User->password = Hash::make($data['password']);
        $User->status = 0;
        $User->profile_status = 0;
        $User->role_id = 2;
        $User->role = 'user';
        $User->email_verify_token = $token;
        $User->affiliated_status = !empty($code) ? 1 : 0;
        $User->affiliated_by = !empty($code) ? base64_decode($code) : null;
        $User->save();

        if(!empty($code)){
            $FreeCredits = FreeCredits::where('credit_status',0)->first();
            $UserFreeCredits = new UserFreeCredits();
            $UserFreeCredits->s_id = base64_decode($code);
            $UserFreeCredits->r_id = $User['id'];
            $UserFreeCredits->user_id = null;
            $UserFreeCredits->credit = $FreeCredits['credit_amount'];
            $UserFreeCredits->credit_status = 0;
            $UserFreeCredits->description = 'Register';
            $UserFreeCredits->date = date('Y-m-d');
            $UserFreeCredits->status = 0;
            $UserFreeCredits->save();
        }

        return back();

       
        // return  User::create([
        //     'title' =>$data['title'],
        //     'name' => Str::ucfirst($data['name']),
        //     'last_name' => Str::ucfirst($data['last_name']),
        //     'email' => $data['email'],
        //     'password' => Hash::make($data['password']),
        //     'status' => 0,
        //     'profile_status'=>0,
        //     'role_id'=>2,
        //     'role'=>'user',
        //     'email_verify_token' => $token,
        //     'affiliated_status' => !empty($code) ? 1 : 0,
        //     'affiliated_by' => !empty($code) ? base64_decode($code) : null,
        // ]);
        
    } 

    public function register(Request $request)
    {
        $code = $request['code'];

        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all(),$code))); 

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());

        // return view('auth.registration_successMessage')->with('email',$request['email']);
    }

    public function showRegistrationForm($code='')
{
    
    return view('auth.register')->with('code',$code);
}


//     protected function redirectTo()
// {
//     if (auth()->user()->role_id == 1) {
//         return '/home';
//     }
//     return '/registration_success';
// }



}
