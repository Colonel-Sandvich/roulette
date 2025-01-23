<?php

declare(strict_types=1);

use App\Bet\BetData;
use App\Models\Bet;
use App\Models\RouletteGame;
use App\Models\User;
use App\Models\Wallet;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->roulette = RouletteGame::factory()
        ->open()
        ->create();
});

test('bet is placed and wallet is reduced', function () {
    $wallet = Wallet::factory()
        ->for($this->user)
        ->balance($amount = 10)
        ->create();

    $this->postJson(route('bet.store'), [
        'amount' => $amount,
        'position' => $position = 0,
    ])
        ->assertRedirectToRoute('roulette.index');

    $this->assertDatabaseHas(Bet::class, [
        'amount' => $amount,
        'position' => $position,
        'wallet_id' => $this->user->wallet->getKey(),
        'roulette_game_id' => $this->roulette->id,
    ]);

    $wallet->balance -= $amount;
    $this->assertModelExists($wallet);
});

test('fails to place bet with invalid position', function (mixed $position) {
    Wallet::factory()
        ->for($this->user)
        ->balance(100)
        ->create();

    $this->postJson(route('bet.store'), [
        'amount' => 1,
        'position' => $position,
    ])->assertUnprocessable();

    $this->assertDatabaseEmpty(Bet::class);
})->with([BetData::MIN_POSITION - 1, BetData::MAX_POSITION + 1, 'black']);

test('fails to place bet with invalid amount', function (mixed $amount) {
    Wallet::factory()
        ->for($this->user)
        ->balance(100)
        ->create();

    $this->postJson(route('bet.store'), [
        'amount' => 1,
        'position' => $amount,
    ])->assertUnprocessable();

    $this->assertDatabaseEmpty(Bet::class);
})->with([-1, 1001, '1 pound', 'a']);

test('fails to place bet with missing keys', function (array $body) {
    Wallet::factory()
        ->for($this->user)
        ->balance(100)
        ->create();

    $this->post(route('bet.store'), $body)->assertInvalid(
        array_diff(['amount', 'position'], array_keys($body)),
    );

    $this->assertDatabaseEmpty(Bet::class);
})->with([
    [['amount' => 1]],
    [['position' => 0]],
    [[]],
]);
