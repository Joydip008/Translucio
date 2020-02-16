<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectCatagories extends Model
{
    // 
    protected $table = 'project_catagories';
    protected $fillable = [
       'catagories',
       'description',
       'status',
    ];
}
