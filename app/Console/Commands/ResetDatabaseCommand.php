<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Bet\BetData;
use App\Jobs\ProcessBets;
use App\Models\RouletteGame;
use App\Roulette\Exceptions\MultipleOpenRouletteGames;
use App\Roulette\GetCurrentRouletteGame;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use function Pest\Laravel\artisan;

class ResetDatabaseCommand extends Command
{
    protected $signature = 'db:reset';

    //
    protected $description = 'Reset the database';

    public function handle(): void
    {
        if (config('database.default') !== 'sqlite') {
            return;
        }

        file_put_contents(config('database.connections.sqlite.database'), "");

        Artisan::call('migrate', ['--force' => true]);
    }
}
