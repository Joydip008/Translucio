<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SourceCode extends Model
{
    // 
    protected $table = 'source_code';
    protected $fillable = [
        'p_id',
        'page_url',
        'html_code',
        'translated_html_code',
        'lang_code',
        'status',
        'created_at',
        'updated_at',
        'deleted_at', 
    ];
}
