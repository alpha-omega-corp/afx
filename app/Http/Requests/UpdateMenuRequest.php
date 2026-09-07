<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * One section and everything on it, written as a whole.
 *
 * The dishes arrive as parallel arrays — one entry per row of the repeater,
 * in the order they are shown — with a French and an English column for every
 * piece of text. A blank English field is not an omission: it is the request
 * to translate that field, and it is what makes the carte editor usable
 * without typing everything twice.
 */
class UpdateMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'section_id' => ['required', 'integer', 'exists:menu_sections,id'],
            'title_fr' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],

            'ids' => ['array'],
            'ids.*' => ['nullable', 'integer', 'exists:menu_items,id'],

            'section_titles_fr' => ['array'],
            'section_titles_fr.*' => ['required', 'string', 'max:255'],
            'section_titles_en' => ['array'],
            'section_titles_en.*' => ['nullable', 'string', 'max:255'],

            'section_descriptions_fr' => ['array'],
            'section_descriptions_fr.*' => ['nullable', 'string', 'max:255'],
            'section_descriptions_en' => ['array'],
            'section_descriptions_en.*' => ['nullable', 'string', 'max:255'],

            'section_prices' => ['array'],
            'section_prices.*' => ['required', 'numeric', 'min:0'],
        ];
    }

    /**
     * The rows, zipped back together and in the order they were sent.
     *
     * @return array<int, array{id: ?int, price: float, fr: array<string, ?string>, en: array<string, ?string>}>
     */
    public function dishes(): array
    {
        $data = $this->validated();

        return collect($data['section_titles_fr'] ?? [])
            ->map(fn (string $title, int $row): array => [
                'id' => $data['ids'][$row] ?? null,
                'price' => (float) ($data['section_prices'][$row] ?? 0),
                'fr' => [
                    'title' => $title,
                    'description' => $data['section_descriptions_fr'][$row] ?? null,
                ],
                'en' => [
                    'title' => $data['section_titles_en'][$row] ?? null,
                    'description' => $data['section_descriptions_en'][$row] ?? null,
                ],
            ])
            ->values()
            ->all();
    }
}
