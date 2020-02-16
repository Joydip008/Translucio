<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;
use Laravel\Cashier\PaymentMethod;
class User extends Model
{    use Billable;
    protected $table = 'users';
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

        'stripe_response',

        'affiliated_status', // 0 = Normal User, 1 = Register By Affiliated Link, 2= Subscribe a Plan By Affiliated Link,
        'affiliated_by', // Who Send 
        
        
    ];
    
    protected $dates = ['deleted_at','trial_ends_at', 'subscription_ends_at'];

    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
}
