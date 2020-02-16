<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectLanguages extends Model
{
    // 
    protected $table = 'project_language';
    protected $fillable = [
        'p_id',
        'user_id',
        'language_id',
        'visibility_status',
    ];
}
