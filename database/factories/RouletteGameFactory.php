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
            'open' => $this->faker->boolean(),
            'result' => $this->faker->numberBetween(
                BetData::MIN_POSITION,
                BetData::MAX_POSITION,
            ),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function open(): self
    {
        return $this->state(['open' => true, 'result' => null]);
    }

    public function closed(): self
    {
        return $this->state(
            [
                'open' => false,
                'result' => $this->faker->numberBetween(
                    BetData::MIN_POSITION,
                    BetData::MAX_POSITION,
                ),
            ],
        );
    }
}
