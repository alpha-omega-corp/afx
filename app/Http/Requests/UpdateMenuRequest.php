<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'ids' => 'array',
            'section_id' => 'integer',
            'section_titles' => 'array',
            'section_prices' => 'array',
            'section_descriptions' => 'array',
        ];
    }
}
