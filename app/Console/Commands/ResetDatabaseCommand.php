<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ResetDatabaseCommand extends Command
{
    protected $signature = 'db:reset';

    protected $description = 'Reset the database';

    public function handle(): void
    {
        if (config('database.default') !== 'sqlite') {
            return;
        }

        DB::statement(/** @lang SQLite */ "PRAGMA WRITABLE_SCHEMA = 1");
        DB::statement(
            /** @lang SQLite */
            "DELETE FROM sqlite_master WHERE type in ('table', 'index', 'trigger')",
        );
        DB::statement(/** @lang SQLite */ "PRAGMA WRITABLE_SCHEMA = 0");
        DB::statement(/** @lang SQLite */ "VACUUM");

        /** @var object{'journal_mode': string} $result */
        [$result] = DB::select(/** @lang SQLite */ "PRAGMA JOURNAL_MODE");

        if ($result->journal_mode !== 'wal') {
            throw new \Exception(
                "Database reset failed. Journal mode is not WAL",
            );
        }

        Artisan::call('migrate', ['--force' => true]);
    }
}
