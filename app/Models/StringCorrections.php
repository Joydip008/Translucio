<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StringCorrections extends Model
{
    protected $table = 'string_corrections';
    protected $fillable = [
      'project_id',
      'do_not_translate_string',
      'always_translate_as_string',
      'type',
      'from_language',
      'to_language',
      'status',
      'created_at',
      'updated_at',
      'deleted_at', 
    ];
}
