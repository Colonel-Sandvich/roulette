<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\RouletteGame;
use App\Wallet\GetUserWallet;
use Inertia\Inertia;
use Inertia\Response;

class RouletteController extends Controller
{
    public function index(GetUserWallet $getUserWallet): Response
    {
        $rouletteGames = RouletteGame::query()
            ->latest()
            ->limit(20)
            // TODO: 20 as config
            ->get();

        [$currentGame, $lastGame] = $rouletteGames->shift(2);

        $wallet = $getUserWallet(getUser()->id);
        $bets = Bet::query()
            ->whereWalletId($wallet->getKey())
            ->whereRouletteGameId($currentGame->id)
            ->get();

        return Inertia::render('Roulette', [
            'currentGame' => $currentGame,
            'lastGame' => $lastGame,
            'bets' => $bets,
            'games' => $rouletteGames,
        ]);
    }
}
