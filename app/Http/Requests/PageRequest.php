<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // An image and nothing else: it is stored under public storage and
            // served as it is, so any other file would be published too.
            'image' => 'nullable|image|max:16384',
            'title_fr' => 'required|string',
            'title_en' => 'required|string',
            'content_fr' => 'required|string',
            'content_en' => 'required|string',
        ];
    }
}
