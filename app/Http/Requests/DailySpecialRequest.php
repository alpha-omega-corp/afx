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
 * French only, on purpose. The card is written in a hurry most days, and the
 * English follows on its own; because no English is sent at all, rather than
 * being sent blank, anything a person has corrected on the carte survives an
 * edit made from here. See App\Support\Translations.
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

            'daily_descriptions' => ['array'],
            'daily_descriptions.*' => ['nullable', 'string', 'max:255'],

            'daily_prices' => ['required', 'array', 'min:1'],
            'daily_prices.*' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * The rows, zipped back together and in the order they were sent.
     *
     * @return array<int, array{id: ?int, menu_section_id: int, price: float, fr: array<string, ?string>}>
     */
    public function dishes(): array
    {
        $data = $this->validated();

        return collect($data['daily_titles'])
            ->map(fn (string $title, int $row): array => [
                'id' => $data['daily_ids'][$row] ?? null,
                'menu_section_id' => (int) $data['daily_sections'][$row],
                'price' => (float) $data['daily_prices'][$row],
                'fr' => [
                    'title' => $title,
                    'description' => $data['daily_descriptions'][$row] ?? null,
                ],
            ])
            ->values()
            ->all();
    }
}
