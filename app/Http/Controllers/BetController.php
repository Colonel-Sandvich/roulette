<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Bet\BetData;
use App\Bet\PlaceBetService;
use App\Roulette\Exceptions\InsufficientBalanceForBet;
use Illuminate\Http\RedirectResponse;

class BetController extends Controller
{
    public function store(
        BetData $betData,
        PlaceBetService $placeBetService,
    ): RedirectResponse {
        try {
            $placeBetService($betData);
        } catch (InsufficientBalanceForBet $exception) {
            return to_route('roulette.index')
                ->withErrors(
                    ['balance' => $exception->getMessage()],
                );
        }

        return to_route('roulette.index');
    }
}
