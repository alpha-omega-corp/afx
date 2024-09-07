<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;

interface ILocale
{
    public function scopeOfLocale(Builder $query, string $locale): void;

    public function locale(): HasOne;
}
