<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Operation extends Model
{
    protected $fillable = [
        'code',
        'start_at',
        'average_value',
        'gain',
        'end_at',
        'user_id'
    ];

    public static function inProgress($query)
    {
        return $query->whereNull('end_at');
    }

    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class);
    }

    public function executions(): HasMany
    {
        return $this->hasMany(Execution::class);
    }
}
