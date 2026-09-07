<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            // French is the language the carte is written in; the English
            // heading is optional, and leaving it blank asks for it to be
            // translated. See App\Support\Translations.
            'title_fr' => ['required', 'string', 'max:255'],
            'title_en' => ['nullable', 'string', 'max:255'],
        ];
    }
}
