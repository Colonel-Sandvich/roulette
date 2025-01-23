<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Wallet> */
class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'balance' => $this->faker->randomNumber(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function balance(int $balance): self
    {
        return $this->set('balance', $balance);
    }
}
