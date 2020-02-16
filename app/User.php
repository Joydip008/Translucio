<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Cashier\Billable;
use Laravel\Cashier\PaymentMethod;
use Auth;
use App\Models\Country;
class User extends Authenticatable
{
    use Notifiable;
    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name', 'email', 'password','title','last_name','profile_status','role_id','role'
    // ];
    protected $fillable = [
        'title',
        'name',
        'last_name',
        'email', 
        'email_verify_at',
        'company_name',
        'vat_number',
        'address_line1',
        'address_line2',
        'city',
        'region',
        'country',
        'profile_status',
        'role_id',
        'role',
        'password',
        'profile_image', // User Profile Image Name
        'status', //At first 0 , 0= Inactive , 1 = Active
        'email_verify_token', // At register time token send 
        'google_id', //Login with Google

        'forgot_password_flag', // Forgot password Flag test
        'forgot_password_token', //Forgot password Token
        'forgot_password_time', //Forgot password email send time

        'last_login_at', // Last logged in Time
        'last_login_ip', // Last Login IP address


        'stripe_id',
        'card_brand',
        'card_last_four',
        'trial_ends_at',

        'stripe_response'
        
    ];
    protected $dates = ['deleted_at','trial_ends_at', 'subscription_ends_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function is_admin()
    {
        if($this->role_id==1)
        {
        return true;
        }
        return false;
    }





    // public function taxPercentage() {
    //     $s_key=env('STRIPE_SECRET');
    //     \Stripe\Stripe::setApiKey($s_key);
    //     $AllTaxRatesDetails = \Stripe\TaxRate::all();
    //     $user_country = Country::where('id',Auth::user()->country)->first();
    //     /* Match The Tax rates As per USer Country */
    //     foreach($AllTaxRatesDetails as $Tax){
    //         if($Tax['jurisdiction']==$user_country['name']){
    //             return $Tax['percentage'];
    //         }
    //         else{
    //             return 0;
    //         }
    //     }
    // }
}
