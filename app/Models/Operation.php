<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = [
        'code',
        'quantity',
        'type',
        'purchase_value',
        'avarage_value',
        'sale_value',
        'start_at',
        'end_at'
    ];
}
