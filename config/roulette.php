<?php

declare(strict_types=1);

return [
    'previous_games_display_count' => (int) env('ROULETTE_PREVIOUS_GAMES_DISPLAY_COUNT', 20),
    // Must divide into 60 (1, 2, 30, 15, 60, etc.)
    'game_length_in_seconds' => (int) env('ROULETTE_GAME_LENGTH_IN_SECONDS', 60),
];
