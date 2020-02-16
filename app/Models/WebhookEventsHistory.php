<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookEventsHistory extends Model
{
    // 
    protected $table = 'webhook_events_history';
    protected $fillable = [
      'user_id',
     'event',
      'created_at',
      'updated_at',  
    ];
}
