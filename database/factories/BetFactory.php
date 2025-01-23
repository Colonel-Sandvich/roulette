<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Bet\BetData;
use App\Models\Bet;
use App\Models\RouletteGame;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Bet> */
class BetFactory extends Factory
{
    protected $model = Bet::class;

    public function definition(): array
    {
        return [
            'wallet_id' => Wallet::factory(),
            'roulette_game_id' => RouletteGame::factory(),
            'position' => $this->faker->numberBetween(BetData::MIN_POSITION, BetData::MAX_POSITION),
            'amount' => $this->faker->numberBetween(0, 100),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function amount(int $amount): self
    {
        return $this->set('amount', $amount);
    }

    public function position(int $position): self
    {
        return $this->set('position', $position);
    }
}
