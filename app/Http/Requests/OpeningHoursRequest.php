<?php

namespace App\Http\Requests;

use App\Models\OpeningHour;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * The serving week, written in one go.
 *
 * Seven rows arrive whether or not they changed, keyed by Carbon's day
 * number, so the form is always a complete picture of the week rather than a
 * patch on it.
 */
class OpeningHoursRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * A day marked closed keeps whatever hours it had in the boxes, so
     * reopening it does not mean typing them again — but they are cleared
     * here rather than saved, because a closed day with hours behind it is a
     * row two readers would disagree about.
     */
    protected function prepareForValidation(): void
    {
        $days = collect($this->input('days', []))
            ->map(function (mixed $day): array {
                $day = is_array($day) ? $day : [];
                $closed = filter_var($day['closed'] ?? false, FILTER_VALIDATE_BOOL);

                return [
                    'closed' => $closed,
                    'lunch_from' => $closed ? null : self::time($day['lunch_from'] ?? null),
                    'lunch_to' => $closed ? null : self::time($day['lunch_to'] ?? null),
                    'dinner_from' => $closed ? null : self::time($day['dinner_from'] ?? null),
                    'dinner_to' => $closed ? null : self::time($day['dinner_to'] ?? null),
                ];
            })
            ->all();

        $this->merge(['days' => $days]);
    }

    public function rules(): array
    {
        return [
            'days' => ['required', 'array', 'size:7'],
            'days.*.closed' => ['boolean'],

            // Half a range cannot be printed, so it cannot be saved: whichever
            // end was filled in, the other one is required with it.
            'days.*.lunch_from' => ['nullable', 'date_format:H:i', 'required_with:days.*.lunch_to'],
            'days.*.lunch_to' => ['nullable', 'date_format:H:i', 'required_with:days.*.lunch_from'],
            'days.*.dinner_from' => ['nullable', 'date_format:H:i', 'required_with:days.*.dinner_to'],
            'days.*.dinner_to' => ['nullable', 'date_format:H:i', 'required_with:days.*.dinner_from'],
        ];
    }

    /**
     * A service that ends before it starts. Checked here rather than with an
     * `after` rule, which would have to compare against a field that is
     * allowed to be missing.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            foreach ($this->input('days', []) as $day => $values) {
                foreach (['lunch', 'dinner'] as $service) {
                    $from = $values["{$service}_from"] ?? null;
                    $to = $values["{$service}_to"] ?? null;

                    if (filled($from) && filled($to) && $to <= $from) {
                        $validator->errors()->add("days.{$day}.{$service}_to", __('validation.after', [
                            'attribute' => $this->attributes()["days.{$day}.{$service}_to"] ?? "{$service}_to",
                            'date' => $from,
                        ]));
                    }
                }
            }
        });
    }

    public function attributes(): array
    {
        $days = collect(range(0, 6))->mapWithKeys(fn (int $day): array => [
            $day => (new OpeningHour(['day' => $day]))->name(),
        ]);

        return $days->flatMap(fn (string $name, int $day): array => [
            "days.{$day}.lunch_from" => "{$name} — " . __('admin.field.lunch_from'),
            "days.{$day}.lunch_to" => "{$name} — " . __('admin.field.lunch_to'),
            "days.{$day}.dinner_from" => "{$name} — " . __('admin.field.dinner_from'),
            "days.{$day}.dinner_to" => "{$name} — " . __('admin.field.dinner_to'),
        ])->all();
    }

    /**
     * The week, ready to be written: keyed by day, times normalised to the
     * "HH:MM" the column stores.
     *
     * @return array<int, array{closed: bool, lunch_from: ?string, lunch_to: ?string, dinner_from: ?string, dinner_to: ?string}>
     */
    public function week(): array
    {
        return collect($this->validated('days'))
            ->mapWithKeys(fn (array $day, int|string $number): array => [
                (int) $number => [
                    'closed' => (bool) ($day['closed'] ?? false),
                    'lunch_from' => $day['lunch_from'] ?? null,
                    'lunch_to' => $day['lunch_to'] ?? null,
                    'dinner_from' => $day['dinner_from'] ?? null,
                    'dinner_to' => $day['dinner_to'] ?? null,
                ],
            ])
            ->all();
    }

    /**
     * Browsers send "09:30" or "09:30:00" depending on the step; the column
     * holds five characters. Anything that is not a clock time is handed
     * through untouched, so the `date_format` rule reports it rather than
     * this method throwing on the way to the validator.
     */
    private static function time(mixed $value): mixed
    {
        if (! is_string($value) || blank($value)) {
            return null;
        }

        return preg_match('/^([01]\\d|2[0-3]):[0-5]\\d(:[0-5]\\d)?$/', $value) === 1
            ? substr($value, 0, 5)
            : $value;
    }
}
