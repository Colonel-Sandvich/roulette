<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Roulette\GetCurrentRouletteGame;
use App\Roulette\PreviousRouletteGamesStore;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Redis;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RouletteController extends Controller
{
    public function __construct(
        public GetCurrentRouletteGame $getCurrentRouletteGame,
        public PreviousRouletteGamesStore $previousRouletteGamesStore,
    ) {}

    public function index(): Response
    {
        return Inertia::render('Roulette', [
            'previousGames' => $this->previousRouletteGamesStore->get(),
            'bets' => fn () => $this->loadBets(),
        ]);
    }

    /** @return Collection<int, Bet> */
    public function loadBets(): Collection
    {
        $currentGame = ($this->getCurrentRouletteGame)();

        return Bet::query()
            ->whereWalletId(getUser()->walletId())
            ->whereRouletteGameId($currentGame->id)
            ->get();
    }

    /** @var array<string, string | int> */
    public const array STREAM_HEADERS = [
        "Content-Type" => "text/event-stream",
        "Cache-Control" => "no-cache",
        "Connection" => "keep-alive",
        "X-Accel-Buffering" => "no",
    ];

    // Use Server-Sent Events to inform the FE that the last game has finished
    public function stream(): StreamedResponse
    {
        $response = new StreamedResponse;

        $response->headers->add(self::STREAM_HEADERS);

        ini_set('default_socket_timeout', -1);
        set_time_limit(0);

        $response->setCallback(function () {
            if (ob_get_level() === 0) {
                ob_start();
            }

            echo ": heartbeat\n\n";

            ob_flush();
            flush();

            Redis::subscribe(
                config('roulette.cache.previous_games'),
                function () {
                    // Send `game_finished` event with no data
                    // It's easier to get inertia to reload the page than
                    // merge this data in with inertia's props
                    echo "event: game_finished\n";
                    echo "data: \n\n";

                    ob_flush();
                    flush();
                },
            );
        });

        return $response;
    }
}
