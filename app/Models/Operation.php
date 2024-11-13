<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;

class Operation extends Model
{
    const PURCHASED_TYPE = 'P';
    const SOLD_TYPE = 'S';

    protected $fillable = [
        'code',
        'quantity',
        'type',
        'purchase_value',
        'avarage_value',
        'sale_value',
        'start_at',
        'end_at',
        'user_id'
    ];

    public static function toInteger(string $value) 
    {
        $value = preg_replace('/[,.]/', '', $value);
        return intval($value);
    }

    public static function toFloat($value) 
    {
        return number_format($value / 100, 2, ',', '.');
    }

    public static function translateDateToBRL($date) 
    {
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        return $dateTime->format('d/m/Y');
    }
}
