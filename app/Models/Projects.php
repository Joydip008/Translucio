<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    //
    protected $table = 'projects';
    protected $fillable = [
        'user_id',
        'project_name',
        'status',
        'website_url',
        'current_language_id',
        'metadata_translation',
        'media_translation',

        'project_category', // As per List form Admin Created 
        'project_type', // 0=webSite Project 1= Document Project
        'documentation_name',
        'translated_time',
        'project_view', // 1= VIEW 
    ];
}
