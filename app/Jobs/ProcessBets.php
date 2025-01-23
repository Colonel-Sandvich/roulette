<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\RouletteGame;
use App\Models\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessBets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected const int BET_PAYOUT_RATIO = 35;

    public function __construct(
        public int $rouletteGameId,
    ) {}

    /**
     * TODO: Convert Raw SQL to this if Eloquent ever supports `UPDATE ... SET ... FROM` syntax
     * $winnings = Bet::query()
     *     ->select('wallet_id')
     *     ->selectRaw(sprintf("SUM(amount) * %d as total", static::BET_PAYOUT_RATIO))
     *     ->wherePosition($rouletteGame->result)
     *     ->whereRouletteGameId($this->rouletteGameId)
     *     ->groupBy('wallet_id');
     *
     * Wallet::query()
     *     ->fromSub($winnings, 'winnings')
     *     ->where('user_id', 'winnings.wallet_id')
     *     ->toBase() // Needed to avoid bug in `Builder::addUpdatedAtColumn` not expecting
     *                // query->from to be a sub query
     *     ->update(['balance' => DB::raw("balance + winnings.total")]);
     */
    public function handle(): void
    {
        $rouletteGame = RouletteGame::find($this->rouletteGameId);

        DB::statement(<<<'SQL'
            UPDATE wallets
            SET balance = balance + winnings.total
            FROM (SELECT bets.wallet_id, SUM(bets.amount) * ? as total
                  FROM bets
                  WHERE bets.position = ?
                    AND roulette_game_id = ?
                  GROUP BY bets.wallet_id) as winnings
            WHERE wallets.user_id = winnings.wallet_id
        SQL,
            [
                static::BET_PAYOUT_RATIO,
                $rouletteGame->result,
                $rouletteGame->id,
            ],
        );
    }
}
