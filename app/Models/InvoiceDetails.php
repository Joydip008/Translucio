<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    protected $table = 'invoice_details';
    protected $fillable = [
        'plan_id',
       'invoice_number',
       'amount',
       'customer_id',
       'customer_email',
       'total_amount',
       'created_at',
       'updated_at',
       'deleted_at',
    ];
}
