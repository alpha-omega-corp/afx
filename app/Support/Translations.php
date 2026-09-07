<?php

namespace App\Support;

use App\Enums\Language;
use App\Jobs\TranslateRecord;
use App\Services\Translator;
use Illuminate\Database\Eloquent\Model;

/**
 * Writes a record's languages, and decides which of them a machine is allowed
 * to touch.
 *
 * One rule runs through all of it: **a person's words are never overwritten,
 * and a machine's words always follow their source.** Staff write the carte
 * in French; every other language is either something they typed themselves,
 * or a translation that keeps itself in step with the French underneath it.
 *
 * Whether a field was typed or generated is remembered per field on the
 * locale row, so correcting the English name of a dish leaves its English
 * description still following the French.
 *
 * The glossary pass runs here, on the request, because it is an array lookup.
 * The network pass never does — anything the glossary could not answer is
 * left holding the French text and handed to a job that runs once the
 * response has already gone out.
 */
class Translations
{
    /** The language staff write in. Everything else is derived from it. */
    public const SOURCE = Language::FR;

    /**
     * @param  Model  $record  the parent — a MenuSection, a MenuItem
     * @param  array<string, array<string, string|null>>  $input
     *         Text by language then field: ['fr' => ['title' => '…'], 'en' => […]].
     *         A language may be left out entirely, and so may any of its
     *         fields; see the note on `resolve()` for what each case means.
     */
    public static function write(Model $record, array $input): void
    {
        $fields = $record::translatable();
        $source = self::clean($input[self::SOURCE->value] ?? [], $fields);

        $row = $record->localeIn(self::SOURCE);
        $row->fill($source);
        $row->auto = [];
        $record->locales()->save($row);

        $pending = false;

        foreach (Language::cases() as $lang) {
            if ($lang === self::SOURCE) {
                continue;
            }

            $target = $record->localeIn($lang);
            $given = $input[$lang->value] ?? null;
            $auto = [];

            foreach ($fields as $field) {
                [$value, $machine, $missed] = self::resolve(
                    $target,
                    $field,
                    $source[$field] ?? null,
                    $given,
                    $lang,
                );

                $target->$field = $value;

                if ($machine) {
                    $auto[] = $field;
                }

                $pending = $pending || $missed;
            }

            $target->auto = $auto;
            $record->locales()->save($target);
        }

        // Only when the glossary came up short, and only after the person who
        // pressed Save has their page back.
        if ($pending) {
            TranslateRecord::dispatchAfterResponse($record);
        }
    }

    /**
     * The machine pass: everything still marked auto that never got a real
     * translation. Talks to the network, so it belongs in a job or a command
     * and nowhere near a request a person is waiting on.
     *
     * Idempotent by construction — a field is only touched while it is still
     * carrying its French source — so it is safe to run again at any time.
     *
     * @return int  how many fields were filled in
     */
    public static function machinePass(Model $record): int
    {
        $fields = $record::translatable();
        $source = $record->localeIn(self::SOURCE);
        $filled = 0;

        foreach (Language::cases() as $lang) {
            if ($lang === self::SOURCE) {
                continue;
            }

            $target = $record->localeIn($lang);
            $changed = false;

            foreach ($fields as $field) {
                $from = $source->$field;

                // Untouched means: the machine owns it, and what it holds is
                // still the French. A field a person has since corrected
                // fails this test and is left alone.
                $untouched = $target->isAuto($field)
                    && filled($from)
                    && Translator::key((string) $target->$field) === Translator::key((string) $from);

                // A phrase the glossary answers is already as good as it gets,
                // even when the answer happens to read the same in both
                // languages — "Spaghetti carbonara" is not an untranslated
                // string, and must not cost a network call on every run.
                if (! $untouched || Translator::phrase((string) $from, self::SOURCE, $lang) !== null) {
                    continue;
                }

                $translated = Translator::machine((string) $from, self::SOURCE, $lang);

                if ($translated === null) {
                    continue;
                }

                $target->$field = $translated;
                $changed = true;
                $filled++;
            }

            if ($changed) {
                $record->locales()->save($target);
            }
        }

        return $filled;
    }

    /**
     * What one field of one language should become.
     *
     * Three cases, and the difference between the second and the third is the
     * whole reason the editor and the plat du jour can share this code:
     *
     *  - The field arrived with text in it. A person typed that; keep it, and
     *    stop the machine from ever touching it again.
     *  - The field arrived empty. A person was looking at it and cleared it,
     *    which is how this editor says "translate this for me": hand it back
     *    to the machine.
     *  - The field did not arrive at all. That editor does not manage this
     *    language — the plat du jour is French-only — so anything a person
     *    wrote is left exactly as it is, and only machine text follows the
     *    French.
     *
     * @return array{0: string|null, 1: bool, 2: bool}  value, machine-owned, glossary missed
     */
    private static function resolve(
        Model $target,
        string $field,
        ?string $from,
        ?array $given,
        Language $lang,
    ): array {
        $managed = $given !== null && array_key_exists($field, $given);
        $typed = $managed ? trim((string) ($given[$field] ?? '')) : '';

        if ($typed !== '') {
            return [$typed, false, false];
        }

        $mine = ! $target->exists || $target->isAuto($field);

        if (! $managed && ! $mine) {
            return [$target->$field, false, false];
        }

        // Nothing to translate yet. The field is left empty and kept as the
        // machine's, so that on the day the French gains a line the English
        // follows it — recording an empty field as a person's work would
        // freeze it empty for good.
        if (blank($from)) {
            return [null, true, false];
        }

        $phrase = Translator::phrase($from, self::SOURCE, $lang);

        // No glossary line: the French stands in until the machine pass, so
        // the carte always reads as something rather than as a blank.
        return $phrase !== null
            ? [$phrase, true, false]
            : [$from, true, true];
    }

    /**
     * @param  array<string, string|null>  $given
     * @param  array<int, string>  $fields
     * @return array<string, string|null>
     */
    private static function clean(array $given, array $fields): array
    {
        $out = [];

        foreach ($fields as $field) {
            $value = trim((string) ($given[$field] ?? ''));
            $out[$field] = $value === '' ? null : $value;
        }

        return $out;
    }
}
