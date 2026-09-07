<?php

namespace App\Jobs;

use App\Support\Translations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Finishes the translations the glossary could not.
 *
 * Dispatched with `dispatchAfterResponse`, deliberately and not as a queued
 * job: the auberge runs no queue worker, and a translation that waits for one
 * to be started is a translation that never arrives. After-response work runs
 * in this same process once the response has been sent, so the person who
 * pressed Save waits for nothing and the network call still happens.
 *
 * Failure is not an error. A rate limit or an unreachable service leaves the
 * field holding its French text, still marked as the machine's, and the next
 * save or `php artisan carte:translate` picks it up again.
 */
class TranslateRecord
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(private readonly Model $record)
    {
    }

    public function handle(): void
    {
        try {
            Translations::machinePass($this->record);
        } catch (\Throwable $e) {
            Log::warning('Could not finish translating a record.', [
                'model' => $this->record::class,
                'id' => $this->record->getKey(),
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
