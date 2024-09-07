<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasLocaleScope
{
    public function scopeOfLocale(Builder $query, string $locale): void
    {
        $query->with(['locale' => function ($query) use ($locale) {
            $query
                ->where('locale', $locale)
                ->withoutGlobalScope('locale');
        }])->find($this->id);
    }
}
