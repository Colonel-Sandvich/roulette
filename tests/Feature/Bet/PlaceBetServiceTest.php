<?php

declare(strict_types=1);

use App\Bet\BetData;
use App\Bet\PlaceBetService;
use App\Models\Bet;
use App\Models\RouletteGame;
use App\Models\User;
use App\Models\Wallet;
use App\Roulette\Exceptions\InsufficientBalanceForBet;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->roulette = RouletteGame::factory()
        ->open()
        ->create();

    $this->placeBetService = app()->make(PlaceBetService::class);
});

test('bet is placed and wallet is updated', function () {
    $wallet = Wallet::factory()
        ->for($this->user)
        ->balance($amount = 10)
        ->create();

    ($this->placeBetService)(new BetData($amount, $position = 0));

    $this->assertDatabaseHas(Bet::class, [
        'amount' => $amount,
        'position' => $position,
        'wallet_id' => $this->user->wallet->user_id,
        'roulette_game_id' => $this->roulette->id,
    ]);

    $wallet->balance -= $amount;
    $this->assertModelExists($wallet);
});

test('fails to place bet when not enough balance in wallet', function () {
    Wallet::factory()
        ->for($this->user)
        ->balance($balance = 0)
        ->create();

    $amount = 1;
    $this->assertTrue($amount > $balance);
    $this->expectExceptionObject(
        new InsufficientBalanceForBet($balance, $amount),
    );

    ($this->placeBetService)(new BetData($amount, 0));
});
