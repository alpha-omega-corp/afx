<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

/**
 * The card of the day, written as a whole.
 *
 * The plan holds several dishes, each with days of its own, so the payload is
 * parallel arrays — one entry per row of the editor, in the order they are
 * shown. Every dish carries its first and last day on show (both optional: a
 * bound left empty is no bound), which is what lets staff write next week's
 * dishes today. A plan with no rows is a plan with nothing on it, not a
 * mistake: every dish taken off goes back to the carte.
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
            // The days each dish is on the home page, both ends included.
            // That the last is not before the first is checked in after().
            'daily_froms' => ['array'],
            'daily_froms.*' => ['nullable', 'date'],
            'daily_untils' => ['array'],
            'daily_untils.*' => ['nullable', 'date'],

            // Blank for a dish being written for the first time.
            'daily_ids' => ['array'],
            'daily_ids.*' => ['nullable', 'integer', 'exists:menu_items,id'],

            // Each dish belongs to a section of the carte like any other: the
            // card is a second view of dishes that live there, not a list of
            // its own.
            'daily_sections' => ['array'],
            'daily_sections.*' => ['required', 'integer', 'exists:menu_sections,id'],

            'daily_titles' => ['array'],
            'daily_titles.*' => ['required', 'string', 'max:255'],
            'daily_titles_en' => ['array'],
            'daily_titles_en.*' => ['nullable', 'string', 'max:255'],

            'daily_descriptions' => ['array'],
            'daily_descriptions.*' => ['nullable', 'string', 'max:255'],
            'daily_descriptions_en' => ['array'],
            'daily_descriptions_en.*' => ['nullable', 'string', 'max:255'],

            'daily_prices' => ['array'],
            'daily_prices.*' => ['required', 'numeric', 'min:0'],
        ];
    }

    /** A row whose last day comes before its first is refused, row by row. */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $froms = (array) $this->input('daily_froms', []);

                foreach ((array) $this->input('daily_untils', []) as $row => $until) {
                    $from = $froms[$row] ?? null;

                    if (filled($from) && filled($until) && strtotime((string) $until) < strtotime((string) $from)) {
                        $validator->errors()->add("daily_untils.{$row}", __('validation.after_or_equal', [
                            'attribute' => __('admin.field.daily_until'),
                            'date' => __('admin.field.daily_from'),
                        ]));
                    }
                }
            },
        ];
    }

    /**
     * The rows, zipped back together and in the order they were sent.
     *
     * @return array<int, array{id: ?int, menu_section_id: int, price: float, from: ?string, until: ?string, fr: array<string, ?string>, en: array<string, ?string>}>
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

        $day = fn (string $key, int $row): ?string => filled($data[$key][$row] ?? null)
            ? Carbon::parse($data[$key][$row])->toDateString()
            : null;

        return collect($data['daily_titles'] ?? [])
            ->map(fn (string $title, int $row): array => [
                'id' => $data['daily_ids'][$row] ?? null,
                'menu_section_id' => (int) $data['daily_sections'][$row],
                'price' => (float) $data['daily_prices'][$row],
                'from' => $day('daily_froms', $row),
                'until' => $day('daily_untils', $row),
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
