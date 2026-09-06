<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DailySpecialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // The section the dish belongs to on the carte: the special is a
            // dish there like any other, so it has to live somewhere.
            'menu_section_id' => 'required|integer|exists:menu_sections,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            // The day the dish is for. Optional: a special with no date shown
            // is better than one carrying yesterday's.
            'daily_on' => 'nullable|date',
            // An unchecked box is absent from the payload, never false.
            'daily' => 'nullable|boolean',
        ];
    }
}
