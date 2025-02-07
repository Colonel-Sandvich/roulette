<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

$schedule = Schedule::command('roulette-game:process')
    ->runInBackground();

$schedule->repeatSeconds = config('roulette.game_length_in_seconds');

// Reset database every day
Schedule::command('migrate:fresh')
    ->daily();
