<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GalleryRequest extends FormRequest
{
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
        // One request for two shapes: an upload carries files, a delete
        // carries ids. The files go to public storage as they are, so only
        // images are accepted.
        if ($this->isMethod('delete')) {
            return [
                'items' => 'required|array',
                'items.*' => 'integer|exists:gallery_items,id',
            ];
        }

        return [
            'items' => 'required|array',
            'items.*' => 'image|max:16384',
        ];
    }
}
