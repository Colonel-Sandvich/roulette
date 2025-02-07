<?php

declare(strict_types=1);

namespace App\Roulette\Exceptions;

use App\Models\RouletteGame;

class RouletteGameNotFound extends \RuntimeException
{
    public final const string MODEL = RouletteGame::class;

    // TODO: Refactor to general ModelNotFound
    // "[ModelFQN]: Failed to find by: ..."
    public static function current(): self
    {
        return new self("Failed to find current RouletteGame");
    }
}
