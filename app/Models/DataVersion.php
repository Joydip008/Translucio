<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataVersion extends Model
{
    //
    protected $table = 'data_version';
    protected $fillable = [
        'paragraph_id',
        'original_data',
        'change_data',
        'change_type',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
