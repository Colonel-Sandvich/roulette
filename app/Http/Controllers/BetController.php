<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Bet\BetData;
use App\Bet\PlaceBetService;
use App\Roulette\Exceptions\InsufficientBalanceForBet;
use Illuminate\Http\RedirectResponse;

class BetController extends Controller
{
    /**
     * @throws \Throwable
     */
    public function store(
        BetData $betData,
        PlaceBetService $placeBetService,
    ): RedirectResponse {
        try {
            $placeBetService(getUser()->id, $betData);
        } catch (InsufficientBalanceForBet $exception) {
            return to_route('roulette.index')
                ->withErrors(
                    ['balance' => "Tried to place a bet of {$exception->amount} with a balance of only {$exception->balance}"],
                );
        }

        return to_route('roulette.index');
    }
}
