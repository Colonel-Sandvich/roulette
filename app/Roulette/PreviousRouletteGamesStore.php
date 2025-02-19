<?php

declare(strict_types=1);

namespace App\Roulette;

use App\Models\RouletteGame;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class PreviousRouletteGamesStore
{
    /** @return array<int, RouletteGame> */
    public function previousGamesQuery(bool $cacheMiss = false): array
    {
        if ($cacheMiss) {
            $cacheKey = config('roulette.cache.previous_games');
            Log::warning("Cache miss @{$cacheKey}");
        }

        return RouletteGame::query()
            ->select(['id', 'result', 'created_at'])
            ->whereNotNull('result')
            ->latest()
            // +1 because we hide last game while ball spins,
            // this prevents layout shift
            ->limit(config('roulette.previous_games_display_count') + 1)
            ->get()
            ->toArray();
    }

    /** @return array<int, RouletteGame> */
    public function get(): array
    {
        return Cache::get(
            config('roulette.cache.previous_games'),
            fn () => $this->previousGamesQuery(cacheMiss: true),
        );
    }

    public function update(): void
    {
        Cache::put(
            config('roulette.cache.previous_games'),
            $this->previousGamesQuery(),
            config('roulette.game_length_in_seconds') + 1,
        );

        info("Publishing event!");

        Redis::publish(config('roulette.cache.previous_games'), "");
    }
}
