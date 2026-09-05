<?php

namespace App\Support;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

/**
 * Runs a read against a table the site can render without.
 *
 * Code is deployed before migrations run often enough that a missing table
 * must not take the public site down: the open/closed switch and the holiday
 * calendar are enhancements, and a guest who cannot see the closed banner is
 * far better served than a guest who sees a 500.
 *
 * Only "this table does not exist" is absorbed. Every other database failure
 * — credentials, syntax, a dropped column — still throws, because those are
 * bugs rather than a deploy ordering, and silence would hide them.
 */
class OptionalTable
{
    /** SQLSTATE for an undefined table: PostgreSQL, then MySQL. */
    private const MISSING_TABLE = ['42P01', '42S02'];

    /** @var array<string, true> */
    private static array $reported = [];

    /**
     * @template T
     * @param  callable():T  $query
     * @param  T  $fallback
     * @return T
     */
    public static function read(callable $query, mixed $fallback): mixed
    {
        try {
            return $query();
        } catch (QueryException $e) {
            if (! self::isMissingTable($e)) {
                throw $e;
            }

            self::reportOnce($e);

            return $fallback;
        }
    }

    public static function isMissingTable(QueryException $e): bool
    {
        return in_array((string) $e->getCode(), self::MISSING_TABLE, true)
            // SQLite reports a generic code and says so in the message.
            || str_contains(strtolower($e->getMessage()), 'no such table');
    }

    /** One line per missing table per request, rather than one per query. */
    private static function reportOnce(QueryException $e): void
    {
        $key = md5($e->getMessage());

        if (isset(self::$reported[$key])) {
            return;
        }

        self::$reported[$key] = true;

        Log::warning('Skipped a read against a missing table; migrations are probably pending.', [
            'exception' => $e->getMessage(),
        ]);
    }
}
