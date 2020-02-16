<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataSection extends Model
{
    //
    protected $table = 'data_section';
    protected $fillable = [

        'p_id',
        'paragraph_id',
        'source_code_id',
        'language_id',
        'data_section',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
