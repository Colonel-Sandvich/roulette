<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite limitation: Constraints can't be added AFTER table creation
        // Just do raw sql for the necessary parts
        DB::statement(<<<'SQL'
            CREATE TABLE "wallets"
            (
                "user_id" INTEGER NOT NULL,
                "balance" INTEGER NOT NULL DEFAULT '0' CHECK ( balance >= 0 ),
                FOREIGN KEY ("user_id") REFERENCES "users" ("id") ON DELETE CASCADE,
                PRIMARY KEY ("user_id")
            )
            SQL
        );

        Schema::table('wallets', function (Blueprint $table) {
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
