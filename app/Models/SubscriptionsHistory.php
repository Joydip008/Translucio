<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionsHistory extends Model
{
    // 
    protected $table = 'subscriptions_history';
    protected $fillable = [
      'user_id',
      'start_date',
      'end_date',
      'status',
      'stripe_id',
      'stripe_plan',
      'created_at',
      'updated_at',  
    ];
}
