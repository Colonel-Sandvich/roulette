<?php

declare(strict_types=1);

namespace App\Bet;

use App\Models\Bet;
use App\Roulette\Exceptions\InsufficientBalanceForBet;
use App\Roulette\GetCurrentRouletteGame;
use App\Wallet\GetCurrentUserWallet;
use Illuminate\Support\Facades\DB;

class PlaceBetService
{
    public function __construct(
        protected GetCurrentUserWallet $getWallet,
        protected GetCurrentRouletteGame $getCurrentRouletteGame,
    ) {}

    /**
     * @throws InsufficientBalanceForBet
     * @throws \Throwable
     *
     * @see /database/migrations/2025_01_12_125204_create_wallets_table.php
     */
    public function __invoke(BetData $betData): Bet
    {
        $wallet = ($this->getWallet)();

        if ($wallet->balance < $betData->amount) {
            throw (new InsufficientBalanceForBet($wallet->balance, $betData->amount));
        }

        $currentRouletteGame = ($this->getCurrentRouletteGame)();

        // Since SQLite only allows for one writer at a time
        // AND since we have a constraint check on `wallets`.`balance` ( >= 0 )
        // If the balance of the wallet were to change to be insufficient
        // then the check would throw an error and the transaction and hence the placed bet
        // would be rolled back
        return DB::transaction(
            function () use ($wallet, $currentRouletteGame, $betData) {
                $wallet->decrement('balance', $betData->amount);

                return Bet::create([
                    'wallet_id' => $wallet->getKey(),
                    'roulette_game_id' => $currentRouletteGame->id,
                    ...$betData->toArray(),
                ]);
            },
        );
    }
}
