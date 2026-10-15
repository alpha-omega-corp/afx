<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * The card of the day, written as a whole.
 *
 * The card holds several dishes now, so the payload is parallel arrays — one
 * entry per row of the repeater, in the order they are shown. The date and
 * the "show on the home page" switch belong to the card rather than to any
 * one dish, and are sent once.
 *
 * Both languages, as the carte editor does: a blank English field is not an
 * omission but the request to translate that one field, and it keeps
 * following the French from then on. See App\Support\Translations.
 */
class DailySpecialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            // The day the card is for. Optional: a card with no date shown is
            // better than one carrying yesterday's.
            'daily_on' => ['nullable', 'date'],
            // An unchecked box is absent from the payload, never false.
            'daily' => ['nullable', 'boolean'],

            // Blank for a dish being written for the first time.
            'daily_ids' => ['array'],
            'daily_ids.*' => ['nullable', 'integer', 'exists:menu_items,id'],

            // Each dish belongs to a section of the carte like any other: the
            // card is a second view of dishes that live there, not a list of
            // its own.
            'daily_sections' => ['required', 'array', 'min:1'],
            'daily_sections.*' => ['required', 'integer', 'exists:menu_sections,id'],

            'daily_titles' => ['required', 'array', 'min:1'],
            'daily_titles.*' => ['required', 'string', 'max:255'],
            'daily_titles_en' => ['array'],
            'daily_titles_en.*' => ['nullable', 'string', 'max:255'],

            'daily_descriptions' => ['array'],
            'daily_descriptions.*' => ['nullable', 'string', 'max:255'],
            'daily_descriptions_en' => ['array'],
            'daily_descriptions_en.*' => ['nullable', 'string', 'max:255'],

            'daily_prices' => ['required', 'array', 'min:1'],
            'daily_prices.*' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * The rows, zipped back together and in the order they were sent.
     *
     * @return array<int, array{id: ?int, menu_section_id: int, price: float, fr: array<string, ?string>, en: array<string, ?string>}>
     */
    public function dishes(): array
    {
        $data = $this->validated();

        // Whether English was on screen at all. Sending it blank means "you
        // translate it"; not sending it means "I am not the editor for that
        // language, leave what is there alone". The two must not collapse
        // into one, or a form without English fields would quietly undo a
        // correction someone typed. See App\Support\Translations::resolve().
        $writesEnglish = $this->has('daily_titles_en') || $this->has('daily_descriptions_en');

        return collect($data['daily_titles'])
            ->map(fn (string $title, int $row): array => [
                'id' => $data['daily_ids'][$row] ?? null,
                'menu_section_id' => (int) $data['daily_sections'][$row],
                'price' => (float) $data['daily_prices'][$row],
                'fr' => [
                    'title' => $title,
                    'description' => $data['daily_descriptions'][$row] ?? null,
                ],
                'en' => self::english($data, $row, $writesEnglish),
            ])
            ->values()
            ->all();
    }

    /**
     * The English a row is actually asking to write.
     *
     * A row that arrived with an id was drawn from a dish the editor had in
     * front of it, English included. A field left blank there was cleared on
     * purpose, and means "translate this for me".
     *
     * A row with no id is different. It is a dish being written for the first
     * time — or, and this is the case that bites, an existing dish being
     * promoted onto the card by name, which is what MenuController::dishNamed
     * does. Its English boxes were never filled in from anything, so blank
     * there means "I did not write one", not "throw away whatever that dish
     * already says in English". Only the fields actually typed are sent, and
     * the rest are left unmanaged so a correction survives.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, string>|null
     */
    private static function english(array $data, int $row, bool $writesEnglish): ?array
    {
        if (! $writesEnglish) {
            return null;
        }

        $given = [
            'title' => $data['daily_titles_en'][$row] ?? null,
            'description' => $data['daily_descriptions_en'][$row] ?? null,
        ];

        if (($data['daily_ids'][$row] ?? null) !== null) {
            return $given;
        }

        $typed = array_filter($given, fn (?string $value): bool => filled($value));

        return $typed === [] ? null : $typed;
    }
}
