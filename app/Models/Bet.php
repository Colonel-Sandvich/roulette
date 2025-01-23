<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\BetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperBet
 */
class Bet extends Model
{
    /** @use HasFactory<BetFactory> */
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'roulette_game_id',
        'position',
        'amount',
    ];

    /** @return BelongsTo<Wallet, $this> */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
