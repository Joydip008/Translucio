<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionModel extends Model
{
    // 
    protected $table = 'subscriptions';
    protected $fillable = [
      'user_id',
      'name',
      'stripe_id',
      'stripe_status',
      'stripe_plan',
      'quantity',
      'trial_ends_at',
      'ends_at',
      'created_at',
      'updated_at',  
    ];
}
