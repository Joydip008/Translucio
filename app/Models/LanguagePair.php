<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LanguagePair extends Model
{
    // 
    protected $table = 'language_pair';
    protected $fillable = [
       'from_language',
       'to_language',
       'api',
       'credit_multiplier',
       'status',
       'do_not_translate',  // 1= User Cane , 2 = User can not 
       'always_translate_as', // 1= User Cane , 2 = User can not 
    ];
}
