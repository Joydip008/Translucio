<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectData extends Model
{
    // 
    protected $table = 'project_data';
    protected $fillable = [
        'p_id',
        'source_code_id',
        'file_name',
        'original_data',
        'translated_data',
        'language_id',
        'status',
        'content_original_data',
        'content_translated_data',
        'data_section' // 1 = Header , 2 = Content , 3 = Footer  Documents ,,,,, 4 = MetaData 5= Body Website 
    ];
}
