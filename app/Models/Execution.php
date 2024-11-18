<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Execution extends Model
{
    const PURCHASED_TYPE = 'P';
    const SOLD_TYPE = 'S';

    protected $fillable = [
        'code',
        'quantity',
        'type',
        'purchase_value',
        'purchase_dollar_value',
        'sale_dollar_value',
        'average_value',
        'sale_value',
        'start_at',
        'end_at',
        'operation_id'
    ];

    public static function toInteger(string $value)
    {
        $value = preg_replace('/[,.]/', '', $value);
        return intval($value);
    }

    public static function toFloat($value): string
    {
        return number_format($value / 100, 2, ',', '.');
    }

    public static function translateDateToBRL($date): string
    {
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        return $dateTime->format('d/m/Y');
    }

    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class);
    }

}
