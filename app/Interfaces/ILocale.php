<?php

namespace App\Interfaces;

use App\Enums\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;

interface ILocale
{
    public function scopeOfLang(Builder $query, Language $locale): void;

    public function locale(): HasOne;
}
