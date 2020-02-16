<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProofreadingAssociative extends Model
{
     protected $table = 'proofreading_associative';

     protected $fillable = [
         'p_id',
         'paragraph_id',
         'source_code_id',
         'created_at',
         'updated_at',
         'deleted_at',
     ];
}
