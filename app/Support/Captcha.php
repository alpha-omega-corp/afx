<?php

namespace App\Support;

use Illuminate\Support\Facades\Session;

/**
 * The contact form's spam guard: a question a person answers without
 * thinking and a script cannot read.
 *
 * Two small numbers, spelled out as words in the visitor's language, added
 * together. Words rather than digits because a digit and a "+" are trivially
 * parsed; a localised word is not. Deliberately not reCAPTCHA or Turnstile:
 * those need an account and a key to work at all, and put a third party
 * between a village auberge and the person writing to it.
 *
 * The answer lives in the session rather than in a signed field, so it cannot
 * be solved once and replayed.
 */
class Captcha
{
    private const KEY = 'captcha.answer';

    /** The two ranges must stay inside the number words in `form.number`. */
    private const LEFT = [1, 5];
    private const RIGHT = [1, 4];

    /** Poses a fresh question and remembers what it expects back. */
    public static function question(): string
    {
        $left = random_int(...self::LEFT);
        $right = random_int(...self::RIGHT);

        Session::put(self::KEY, $left + $right);

        return __('form.captcha_question', [
            'left' => __('form.number.' . $left),
            'right' => __('form.number.' . $right),
        ]);
    }

    /**
     * One question, one attempt: the expected answer is spent whether or not
     * it matched, so a wrong answer gets a new question rather than another
     * go at the same one.
     */
    public static function check(?string $answer): bool
    {
        $expected = Session::pull(self::KEY);

        if ($expected === null || ! is_numeric($answer)) {
            return false;
        }

        return (int) $answer === $expected;
    }
}
