<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Grammars\SQLiteGrammar;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\DB;

class ResetDatabaseCommand extends Command
{
    protected $signature = 'db:reset';

    protected $description = 'Reset the sqlite database';

    public function handle(): void
    {
        $connection = DB::connection();
        $grammar = $connection->getSchemaGrammar();

        if (! $connection instanceof SQLiteConnection) {
            return;
        }
        if (! $grammar instanceof SQLiteGrammar) {
            return;
        }

        // https://github.com/laravel/framework/discussions/53044#discussioncomment-10861735
        $connection->statement($grammar->compileEnableWriteableSchema());
        $connection->statement($grammar->compileDropAllTables());
        $connection->statement($grammar->compileEnableWriteableSchema());
        $connection->statement($grammar->compileRebuild());

        $this->call('migrate', ['--force' => true]);
    }
}
