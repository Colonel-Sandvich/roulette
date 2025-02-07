<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\RouletteGameFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperRouletteGame
 */
class RouletteGame extends Model
{
    /** @use HasFactory<RouletteGameFactory> */
    use HasFactory;

    protected $fillable = [];

    protected $attributes = [
        'result' => null,
    ];
}
