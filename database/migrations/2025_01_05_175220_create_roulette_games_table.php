<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @see \App\Http\Controllers\RouletteController
     * @see \App\Roulette\GetCurrentRouletteGame
     *
     * Unique index on `result IS NULL` would unfortunately NOT guarantee one open game
     * @link https://www.sqlite.org/lang_createindex.html
     */
    public function up(): void
    {
        Schema::create('roulette_games', function (Blueprint $table) {
            $table->id();
            $table->integer('result')->nullable();
            $table->timestamps();

            // Used in `RouletteController`
            $table->rawIndex(
                'created_at DESC',
                'roulette_games_created_at_index',
            );
        });

        // Partial Index for the 'open' game
        // It being a partial index keeps the size on disk small
        // Used in `GetCurrentRouletteGame`
        DB::statement(
            <<<'SQL'
            CREATE INDEX roulette_games_null_result ON roulette_games(result) WHERE result IS NULL;
            SQL,
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('roulette_games');
    }
};
