<?php

namespace App\Traits;

use App\Enums\Language;
use App\Models\Scopes\LocaleScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * A record whose text lives in a sibling table, one row per language.
 *
 * `locale` is the row for the language being browsed — the global LocaleScope
 * on the locale model picks it — and is what every guest-facing view reads.
 * `locales` is all of them at once, which is what the editor and the
 * translator need and what the scope must therefore be kept off.
 *
 * The model using this declares two things: the class holding its text, and
 * which of that class's columns carry text worth translating.
 */
trait HasTranslations
{
    /** @return class-string<Model> */
    abstract public static function localeModel(): string;

    /** @return array<int, string> */
    abstract public static function translatable(): array;

    /** The text in the language being browsed. */
    public function locale(): HasOne
    {
        return $this->hasOne(static::localeModel());
    }

    /** Every language, whichever one is being browsed. */
    public function locales(): HasMany
    {
        return $this->hasMany(static::localeModel())->withoutGlobalScope(LocaleScope::class);
    }

    /**
     * The row for one language, made but not saved when it is not there yet.
     *
     * Reads the loaded collection when there is one, so the editor drawing
     * seven sections in two languages does not spend fourteen queries on it.
     */
    public function localeIn(Language $lang): Model
    {
        $existing = $this->relationLoaded('locales')
            ? $this->locales->firstWhere('lang', $lang->value)
            : $this->locales()->where('lang', $lang->value)->first();

        return $existing ?? $this->locales()->make(['lang' => $lang->value]);
    }
}
