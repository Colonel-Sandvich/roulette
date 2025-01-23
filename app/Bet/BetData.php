<?php

declare(strict_types=1);

namespace App\Bet;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;

class BetData extends Data
{
    const int MIN_POSITION = 0;

    const int MAX_POSITION = 36;

    public function __construct(
        #[Min(1), Max(1000)]
        public int $amount,
        #[Min(self::MIN_POSITION), Max(self::MAX_POSITION)]
        public int $position,
    ) {}
}
