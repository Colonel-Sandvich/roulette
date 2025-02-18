<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Bet\BetData;
use App\Jobs\ProcessBets;
use App\Models\RouletteGame;
use App\Roulette\Exceptions\MultipleOpenRouletteGames;
use App\Roulette\GetCurrentRouletteGame;
use App\Roulette\PreviousRouletteGamesStore;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessRouletteGameCommand extends Command
{
    protected $signature = 'roulette-game:process';

    protected $description = 'Close the current roulette game with a result and create a new one, dispatching a job to process bets as well';

    public function handle(
        GetCurrentRouletteGame $getCurrentRouletteGame,
        PreviousRouletteGamesStore $previousRouletteGamesStore,
    ): void {
        $this->ensureOneOpenRouletteGame();

        $currentGame = $getCurrentRouletteGame();

        $result = random_int(BetData::MIN_POSITION, BetData::MAX_POSITION);
        $currentGame->result = $result;

        DB::transaction(function () use ($currentGame) {
            $currentGame->save();

            RouletteGame::create();
        });

        $previousRouletteGamesStore->update();

        dispatch(new ProcessBets($currentGame->id));
    }

    /** @throws MultipleOpenRouletteGames */
    protected function ensureOneOpenRouletteGame(): void
    {
        $openGamesCount = RouletteGame::query()
            ->whereResult(null)
            ->count();

        if ($openGamesCount === 0) {
            RouletteGame::create();
        }

        if ($openGamesCount > 1) {
            throw new MultipleOpenRouletteGames;
        }
    }
}
