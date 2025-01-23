<?php

declare(strict_types=1);

namespace App\Roulette\Exceptions;

class InsufficientBalanceForBet extends \RuntimeException
{
    public function __construct(
        public readonly int $balance,
        public readonly int $amount,
    ) {
        parent::__construct(
            "Tried to place a bet of: {$amount} with a balance of only: {$balance}",
        );
    }
}
