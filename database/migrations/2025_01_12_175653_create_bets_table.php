<?php

declare(strict_types=1);

use App\Models\RouletteGame;
use App\Models\Wallet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @see \App\Jobs\ProcessBets
     * @see \App\Http\Controllers\RouletteController
     */
    public function up(): void
    {
        Schema::create('bets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Wallet::class, 'wallet_id')
                ->index()
                ->constrained()
                ->restrictOnDelete();
            $table->foreignIdFor(RouletteGame::class)
                ->constrained()
                ->restrictOnDelete();
            $table->integer('position');
            $table->integer('amount');
            $table->timestamps();

            // Used in `ProcessBets`
            $table->index(['roulette_game_id', 'position', 'wallet_id']);
            // Used in `RouletteController`
            $table->index(['roulette_game_id', 'wallet_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bets');
    }
};
