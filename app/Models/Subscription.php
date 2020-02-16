<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
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

    /* Subscriptions table to Plan Table */
    /* Only Plan Per User */
    public function plans()
    {
      return $this->hasMany(Plans::class);
    }
}
