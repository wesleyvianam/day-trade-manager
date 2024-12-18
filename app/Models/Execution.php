<?php

namespace App\Models;

use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mockery\Exception;

class Execution extends Model
{
    const PURCHASED_TYPE = 'P';
    const SOLD_TYPE = 'S';

    protected $fillable = [
        'code',
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
        if (!is_numeric($value)) {
            $value = 0;
        }

        return number_format($value / 100, 2, ',', '.');
    }

    public static function formatDollar($value): string
    {
        $formattedNumber = $value / pow(10, 3);
        return number_format($formattedNumber, 3, '.', '');
    }

    public static function translateDateToBRL($date): string
    {
        $date = new DateTime($date, new DateTimeZone('UTC'));
        $date->modify('-3 hours');
        return $date->format('d/m/Y H:i');
    }

    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class);
    }

}
