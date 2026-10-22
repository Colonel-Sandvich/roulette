<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetDatabaseCommand extends Command
{
    protected $signature = 'db:reset';

    protected $description = 'Reset the sqlite database';

    public function handle(): void
    {
        if (! DB::connection() instanceof SQLiteConnection) {
            return;
        }

        Schema::dropAllTables();

        $this->call('migrate', ['--force' => true]);
    }
}
