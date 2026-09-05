<?php

namespace App\Models;

use App\Models\Scopes\LocaleScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HolidayLocale extends Model
{
    protected $fillable = [
        'holiday_id',
        'lang',
        'reason',
    ];

    public static function booted(): void
    {
        static::addGlobalScope(new LocaleScope());
    }

    public function holiday(): BelongsTo
    {
        return $this->belongsTo(Holiday::class);
    }
}
