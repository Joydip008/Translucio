<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraChargeHistory extends Model
{

    protected $table = 'extra_charge_history';
    protected $fillable = [

        'subscription_history_id',
        'extra_credit_used', 
        'status', // 0 = Not payment , 1= Payment 
    ];
}
