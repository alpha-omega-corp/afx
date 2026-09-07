<?php

namespace App\Models;

use App\Models\Scopes\LocaleScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A section heading in one language.
 *
 * `auto` lists the fields a machine wrote, so the two can be told apart one
 * field at a time: machine text follows its French source whenever that
 * source changes, and text a person typed is never overwritten. Correcting
 * the name of a dish therefore does not freeze its description.
 * See App\Support\Translations.
 */
class MenuSectionLocale extends Model
{
    protected $fillable = [
        'menu_section_id',
        'lang',
        'title',
        'auto',
    ];

    protected function casts(): array
    {
        return ['auto' => 'array'];
    }

    /** True while this one field is still the machine's work. */
    public function isAuto(string $field): bool
    {
        return in_array($field, $this->auto ?? [], true);
    }

    public static function booted(): void
    {
        static::addGlobalScope(new LocaleScope());
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(MenuSection::class);
    }
}
