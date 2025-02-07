<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\RouletteGame;
use App\Roulette\GetCurrentRouletteGame;
use Illuminate\Database\Eloquent\Collection;
use Inertia\Inertia;
use Inertia\Response;

class RouletteController extends Controller
{
    public function __construct(
        public GetCurrentRouletteGame $getCurrentRouletteGame,
    ) {}

    public function index(): Response
    {
        $rouletteGames = RouletteGame::query()
            ->whereNotNull('result')
            ->latest()
            // +1 because we hide last game while ball spins,
            // this prevents layout shift
            ->limit(config('roulette.previous_games_display_count') + 1)
            ->get();

        return Inertia::render('Roulette', [
            'previousGames' => $rouletteGames,
            'bets' => fn () => $this->loadBets(),
        ]);
    }

    /** @return Collection<int, Bet> */
    public function loadBets(): Collection
    {
        $currentGame = ($this->getCurrentRouletteGame)();

        return Bet::query()
            ->whereWalletId(getUser()->walletId())
            ->whereRouletteGameId($currentGame->id)
            ->get();
    }
}
