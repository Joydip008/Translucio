<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calculations extends Model
{
    //
    protected $table = 'calculations';
    protected $fillable = [
        'user_id',
        'plan_id',
        'used_characters',
        'execed_characters',
        'used_pageviews',
        'execed_pageviews',
        
    ];
}
