<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectStringCorrections extends Model
{
    // 
    protected $table = 'projects_string_corrections';
    protected $fillable = [
        'user_id',
        'p_id',
        'others_pid',
        'status',
        'from_language',
        'to_language',
        'type',
    ];
}
