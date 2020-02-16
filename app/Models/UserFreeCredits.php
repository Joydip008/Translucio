<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFreeCredits extends Model
{
    //
    protected $table = 'user_free_credits';
    protected $fillable = [
        's_id',
        'r_id',
        'user_id',
        'credit',
        'credit_status',
        'description',
        'date',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
