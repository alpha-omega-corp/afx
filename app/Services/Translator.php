<?php

namespace App\Services;

use App\Enums\Language;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Turns a phrase in one language into the same phrase in another, without an
 * account, a key or an invoice.
 *
 * Two passes, in this order:
 *
 *  1. The glossary in resources/glossary — the auberge's own carte, written
 *     out by hand. Instant, offline, deterministic, and better English than a
 *     machine will produce for "coupe chaud-froid". This is the pass that
 *     answers nearly every lookup on this site.
 *
 *  2. MyMemory's public endpoint, for anything the glossary has never seen.
 *     Free and keyless, rate-limited rather than billed, and it is never
 *     called on the request a visitor or a member of staff is waiting on —
 *     see App\Jobs\TranslateRecord.
 *
 * Neither pass is authoritative. Everything it writes is marked as machine
 * work and can be overwritten by hand, which is what the English fields in
 * the carte editor are for.
 */
class Translator
{
    /** MyMemory answers politely and slowly; nothing here is worth a long wait. */
    private const TIMEOUT = 6;

    /** Their limit for one call. Longer text is left alone rather than truncated. */
    private const MAX_LENGTH = 500;

    /** @var array<string, array<string, string>> */
    private static array $glossaries = [];

    /**
     * The glossary answer, or null when it has never seen the phrase.
     *
     * Matching is deliberately loose: staff type "Nos Entrées" one day and
     * "nos entrees" the next, and both mean the same section.
     */
    public static function phrase(string $text, Language $from, Language $to): ?string
    {
        $glossary = self::glossary($from, $to);

        return $glossary[self::key($text)] ?? null;
    }

    /**
     * The machine answer, or null when the service could not give one.
     *
     * Every failure — a timeout, a rate limit, a service having a bad day —
     * is a null and a log line, never an exception: a translation that did
     * not arrive must not take a save down with it.
     */
    public static function machine(string $text, Language $from, Language $to): ?string
    {
        if (blank($text) || mb_strlen($text) > self::MAX_LENGTH) {
            return null;
        }

        try {
            $response = Http::timeout(self::TIMEOUT)
                ->acceptJson()
                ->get('https://api.mymemory.translated.net/get', [
                    'q' => $text,
                    'langpair' => "{$from->value}|{$to->value}",
                ]);

            if ($response->failed()) {
                return null;
            }

            // A refusal comes back as a 200 with the complaint in the field
            // the translation would have been in, so the status is checked
            // rather than the shape of the body.
            if ((int) $response->json('responseStatus') !== 200) {
                Log::info('Translation declined.', ['detail' => $response->json('responseDetails')]);

                return null;
            }

            $translated = trim((string) $response->json('responseData.translatedText'));

            // An echo of the input is not a translation, and neither is a
            // blank: both mean "no answer", and saying so lets the phrase be
            // tried again later instead of being written down as done.
            return blank($translated) || self::key($translated) === self::key($text)
                ? null
                : $translated;
        } catch (\Throwable $e) {
            Log::warning('Translation service unreachable.', ['exception' => $e->getMessage()]);

            return null;
        }
    }

    /** The glossary first, the machine only if it has to. */
    public static function translate(string $text, Language $from, Language $to): ?string
    {
        return self::phrase($text, $from, $to) ?? self::machine($text, $from, $to);
    }

    /**
     * The lookup form of a phrase: case, accents, apostrophe shape and
     * surrounding punctuation all folded away, so the glossary is written
     * once and matches what staff actually type.
     */
    public static function key(string $text): string
    {
        // Curly and straight apostrophes are the same character to a reader
        // and two different ones to an array key.
        $text = str_replace(['’', '‘', '`'], "'", $text);

        // "Nos entrées" and "nos entrees" are the same section to the person
        // typing them, and a carte gets typed on a keyboard that does not
        // always have the accents on it.
        $text = Str::lower(Str::ascii(trim($text)));
        $text = preg_replace('/\s+/u', ' ', $text);

        return trim($text, " \t\n\r\0\x0B.:;!?");
    }

    /**
     * One direction of the glossary, read once per request.
     *
     * The file is written in one direction only; the other is derived by
     * flipping it, so a line never has to be kept correct in two places.
     *
     * @return array<string, string>
     */
    private static function glossary(Language $from, Language $to): array
    {
        $pair = "{$from->value}-{$to->value}";

        if (isset(self::$glossaries[$pair])) {
            return self::$glossaries[$pair];
        }

        $forward = resource_path("glossary/{$pair}.php");

        if (file_exists($forward)) {
            return self::$glossaries[$pair] = self::normalise(require $forward);
        }

        $reverse = resource_path("glossary/{$to->value}-{$from->value}.php");

        if (file_exists($reverse)) {
            return self::$glossaries[$pair] = self::normalise(array_flip(require $reverse));
        }

        return self::$glossaries[$pair] = [];
    }

    /**
     * Keys folded to their lookup form. A flipped glossary arrives with
     * English keys in whatever case they were written for display, so this
     * runs on both directions rather than trusting the file.
     *
     * @param  array<string, string>  $lines
     * @return array<string, string>
     */
    private static function normalise(array $lines): array
    {
        $out = [];

        foreach ($lines as $source => $target) {
            $out[self::key((string) $source)] = $target;
        }

        return $out;
    }
}
