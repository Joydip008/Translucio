<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatData extends Model
{
    //
    protected $table = 'chat_data';
    protected $fillable = [
        'from_language',
        'to_language',
        'text',
        'translated_text',
        'api',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
