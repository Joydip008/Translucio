<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DowngradeSubscription extends Model
{
    // 
    protected $table = 'downgrade_subscriptions';
    protected $fillable = [
      'id',
      'user_id',
      'stripe_plan',
      'action_date',
     'created_at',
      'updated_at',  
    ];
}
