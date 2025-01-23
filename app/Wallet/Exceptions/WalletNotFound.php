<?php

declare(strict_types=1);

namespace App\Wallet\Exceptions;

use App\Models\Wallet;

class WalletNotFound extends \RuntimeException
{
    public final const string MODEL = Wallet::class;

    // TODO: Refactor to general ModelNotFound
    // "[ModelFQN]: Failed to find by: ..."
    public static function byUserId(int $userId): self
    {
        return new self("Failed to find by: `user_id` = `{$userId}`");
    }
}
