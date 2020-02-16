<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextCount extends Model
{
    //
    protected $table = 'text_count';
    protected $fillable = [
       'user_id',
       'p_id',
       'from_language',
       'to_language',
       'text',
       'length',
       'credit_used',
       'translate_date',
       'bill_status',
       'payment_status',
       'created_at',
       'updated_at',
       'deleted_at'
    ];
}
