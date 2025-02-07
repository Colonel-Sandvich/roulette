<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Bet\BetData;
use App\Models\RouletteGame;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<RouletteGame> */
class RouletteGameFactory extends Factory
{
    protected $model = RouletteGame::class;

    public function definition(): array
    {
        return [
            'result' => $this->makeRandomBettingPosition(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function open(): self
    {
        return $this->state(['result' => null]);
    }

    public function closed(): self
    {
        return $this->set('result', $this->makeRandomBettingPosition());
    }

    protected function makeRandomBettingPosition(): int
    {
        return $this->faker->numberBetween(
            BetData::MIN_POSITION,
            BetData::MAX_POSITION,
        );
    }
}
