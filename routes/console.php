<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

Schedule::command('roulette-game:process')
    ->everySecond()
    ->runInBackground();
