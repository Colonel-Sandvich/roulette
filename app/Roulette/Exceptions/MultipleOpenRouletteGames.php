<?php

declare(strict_types=1);

namespace App\Roulette\Exceptions;

class MultipleOpenRouletteGames extends \Exception
{
    public function __construct()
    {
        parent::__construct(
            "More than 1 open roulette game found. This requires immediate attention!!!",
        );
    }
}
