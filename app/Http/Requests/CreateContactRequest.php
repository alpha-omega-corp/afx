<?php

namespace App\Http\Requests;

use App\Support\Captcha;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:190',
            'phone' => 'required|string|max:40',
            'message' => 'required|string|max:5000',

            // The question. `bail` so a blank field says it is blank rather
            // than also spending the answer and calling it wrong.
            'captcha' => ['bail', 'required', function (string $attribute, mixed $value, \Closure $fail): void {
                if (! Captcha::check($value)) {
                    $fail(__('form.captcha_failed'));
                }
            }],

            // The honeypot: must arrive missing or empty, which is what a
            // person's browser sends and a form-filling script does not.
            'website' => 'prohibited',
        ];
    }

    /**
     * The message stored is the message written: neither guard is part of it.
     */
    public function validated($key = null, $default = null): array
    {
        return collect(parent::validated())
            ->except(['captcha', 'website'])
            ->all();
    }

    public function messages(): array
    {
        return [
            'website.prohibited' => __('form.captcha_failed'),
        ];
    }
}