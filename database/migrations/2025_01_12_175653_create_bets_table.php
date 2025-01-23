<?php

declare(strict_types=1);

use App\Models\RouletteGame;
use App\Models\Wallet;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Wallet::class, 'wallet_id')->constrained()->restrictOnDelete();
            $table->foreignIdFor(RouletteGame::class)->constrained()->restrictOnDelete();
            $table->integer('position');
            $table->integer('amount');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bets');
    }
};
