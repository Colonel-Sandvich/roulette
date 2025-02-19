<?php

declare(strict_types=1);

namespace App\Wallet\Exceptions;

use App\Exceptions\ModelNotFound;
use App\Models\Wallet;

class WalletNotFound extends ModelNotFound
{
    public final const string MODEL = Wallet::class;

    public static function byUserId(int $userId): self
    {
        return self::make("Failed to find by: `user_id` = `{$userId}`");
    }
}
