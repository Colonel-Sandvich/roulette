<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

$schedule = Schedule::command('roulette-game:process')
    // Set lock to expire after one minute.
    ->withoutOverlapping(1)
    ->runInBackground();

$schedule->repeatSeconds = config('roulette.game_length_in_seconds');

// Reset database every day
Schedule::command('db:reset')
    ->daily();
