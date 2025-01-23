<?php

declare(strict_types=1);

namespace App\Roulette;

use App\Models\RouletteGame;
use App\Roulette\Exceptions\RouletteGameNotFound;

class GetCurrentRouletteGame
{
    public function __invoke(): RouletteGame
    {
        $game = RouletteGame::query()
            ->whereOpen(true)
            ->whereResult(null)
            ->latest()
            ->first();

        if (! $game) {
            throw RouletteGameNotFound::current();
        }

        return $game;
    }
}
