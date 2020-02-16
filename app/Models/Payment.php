<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $table = 'payment_details';
    protected $fillable = [
       'user_id',
       'invoice_id',
       'amount',
       'status',
    ];
}
