<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Bet\BetData;
use App\Jobs\ProcessBets;
use App\Models\RouletteGame;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessRouletteGameCommand extends Command
{
    protected $signature = 'roulette-game:process';

    protected $description = 'Command description';

    public function handle(): void
    {
        $currentGame = RouletteGame::query()
            ->whereOpen(true)
            ->whereResult(null)
            // TODO: 60 as config
            ->where('created_at', '<', now()->subSeconds(60))
            ->latest()
            ->first();
        if (! $currentGame) {
            $this->ensureOpenRouletteGame();

            return;
        }

        $currentGame->open = false;

        $result = random_int(BetData::MIN_POSITION, BetData::MAX_POSITION);
        $currentGame->result = $result;

        DB::transaction(function () use ($currentGame) {
            $currentGame->save();

            RouletteGame::create();
        });

        dispatch(new ProcessBets($currentGame->id));
    }

    protected function ensureOpenRouletteGame(): void
    {
        $openGamesCount = RouletteGame::query()
            ->whereOpen(true)
            ->count();

        if ($openGamesCount === 0) {
            RouletteGame::create();
        }
    }
}
